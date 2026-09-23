<?php
add_action( 'wp_ajax_rb_header_ajax_search', 'rb_header_ajax_search' );
add_action( 'wp_ajax_nopriv_rb_header_ajax_search', 'rb_header_ajax_search' );

function rb_header_ajax_search() {
    $search_term = isset($_GET['term']) ? sanitize_text_field($_GET['term']) : '';
    
    if (empty($search_term) || mb_strlen($search_term, 'UTF-8') < 2) {
        wp_send_json(array());
        wp_die();
    }

    global $wpdb;
    
    $variants      = get_search_variants($search_term);
    $enabled_types = olimp_search_get_enabled_post_types();

    $posts     = array();
    $found_ids = array();

    $post_types_sql = "'" . implode("','", array_map('esc_sql', $enabled_types)) . "'";

    // --- Поиск по title/content/excerpt ---
    foreach ($variants as $variant) {
        $variant = trim($variant);
        if (mb_strlen($variant, 'UTF-8') < 3) continue;

        $like = '%' . esc_sql($wpdb->esc_like($variant)) . '%';

        $found = $wpdb->get_col("
            SELECT DISTINCT {$wpdb->posts}.ID
            FROM {$wpdb->posts}
            WHERE {$wpdb->posts}.post_status = 'publish'
            AND {$wpdb->posts}.post_type IN ({$post_types_sql})
            AND (
                {$wpdb->posts}.post_title   LIKE '{$like}'
                OR {$wpdb->posts}.post_content LIKE '{$like}'
                OR {$wpdb->posts}.post_excerpt LIKE '{$like}'
            )
            LIMIT 20
        ");

        if ($found) {
            foreach ($found as $id) {
                if (!in_array($id, $found_ids, true)) {
                    $found_ids[] = $id;
                }
            }
        }

        if (count($found_ids) >= 20) break;
    }
if (isset($_GET['debug'])) {
    // Что реально находится по ms pro
    $r1 = $wpdb->get_results("
        SELECT ID, post_title, post_type FROM {$wpdb->posts}
        WHERE post_status='publish' AND post_title LIKE '%MS PRO%' LIMIT 5
    ");
    
    // Есть ли слово мсифит хоть где-то
    $r2 = $wpdb->get_results("
        SELECT ID, post_title FROM {$wpdb->posts}
        WHERE post_status='publish' AND (
            post_title LIKE '%мсифит%'
            OR post_content LIKE '%мсифит%'
        ) LIMIT 5
    ");

    // Что в postmeta service_keywords у поста 4651
    $r3 = $wpdb->get_results("
        SELECT meta_key, meta_value FROM {$wpdb->postmeta}
        WHERE post_id = 4651
        AND meta_key IN ('service_keywords','service_synonyms','search_terms','_rb_search_title')
    ");

    wp_send_json_success([
        'ms_pro_posts'  => $r1,
        'мсифит_found'  => $r2,
        'post_4651_meta'=> $r3,
    ]);
    wp_die();
}
    // --- Поиск по postmeta ---
    foreach ($variants as $variant) {
        $variant = trim($variant);
        if (mb_strlen($variant, 'UTF-8') < 3) continue;
        if (count($found_ids) >= 20) break;

        $like = '%' . esc_sql($wpdb->esc_like($variant)) . '%';

        $found = $wpdb->get_col("
            SELECT DISTINCT {$wpdb->posts}.ID
            FROM {$wpdb->posts}
            INNER JOIN {$wpdb->postmeta} pm ON pm.post_id = {$wpdb->posts}.ID
            WHERE {$wpdb->posts}.post_status = 'publish'
            AND {$wpdb->posts}.post_type IN ({$post_types_sql})
            AND pm.meta_key IN ('service_keywords','service_synonyms','search_terms')
            AND pm.meta_value LIKE '{$like}'
            LIMIT 20
        ");

        if ($found) {
            foreach ($found as $id) {
                if (!in_array($id, $found_ids, true)) {
                    $found_ids[] = $id;
                }
            }
        }
    }

    // --- Загружаем полные объекты постов ---
    if (!empty($found_ids)) {
        foreach (array_slice($found_ids, 0, 20) as $id) {
            $post = get_post($id);
            if ($post) $posts[] = $post;
        }
    }

    // --- Дополнительный поиск врачей по должности ---
    if (in_array('doctors', $enabled_types)) {
        $doctor_keys = olimp_get_doctor_position_keys();
        $doctor_meta_query = array('relation' => 'OR');
        
        foreach ($doctor_keys as $key) {
            foreach ($variants as $variant) {
                if (mb_strlen($variant, 'UTF-8') >= 3) {
                    $doctor_meta_query[] = array(
                        'key'     => $key,
                        'value'   => $variant,
                        'compare' => 'LIKE'
                    );
                }
            }
        }
        
        if (count($doctor_meta_query) > 1) {
            $doctor_args = array(
                'post_type'      => 'doctors',
                'post_status'    => 'publish',
                'posts_per_page' => 10,
                'meta_query'     => $doctor_meta_query
            );
            
            $doctor_query = new WP_Query($doctor_args);
            
            if ($doctor_query->have_posts()) {
                $existing_ids = array_map(function($p) { return $p->ID; }, $posts);
                
                while ($doctor_query->have_posts()) {
                    $doctor_query->the_post();
                    $doctor = get_post();
                    
                    if (!in_array($doctor->ID, $existing_ids)) {
                        $posts[] = $doctor;
                        $existing_ids[] = $doctor->ID;
                    }
                }
                wp_reset_postdata();
            }
        }
    }

    // --- Поиск по таксономиям ---
    if (get_option('olimp_search_taxonomy_search', 1) && count($posts) < 20) {
        $found_terms = array();
        
        foreach ($variants as $variant) {
            $variant = trim($variant);
            if (mb_strlen($variant, 'UTF-8') < 3) continue;

            $like = '%' . esc_sql($wpdb->esc_like($variant)) . '%';

            $terms = $wpdb->get_results("
                SELECT t.term_id, t.name, t.slug, tt.description, tt.taxonomy, tt.count
                FROM {$wpdb->terms} t
                INNER JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id
                WHERE tt.taxonomy IN ('services','category')
                  AND (t.name LIKE '{$like}' OR t.slug LIKE '{$like}')
                ORDER BY tt.count DESC, t.name ASC
                LIMIT 5
            ");
            
            if ($terms) $found_terms = array_merge($found_terms, $terms);
        }
        
        if (!empty($found_terms)) {
            $uniq = array();
            foreach ($found_terms as $term) {
                $uniq[$term->taxonomy . '_' . $term->term_id] = $term;
            }
            
            foreach ($uniq as $term) {
                $virtual_post = new WP_Post((object)[
                    'ID'           => 'term_' . $term->term_id,
                    'post_title'   => $term->name,
                    'post_content' => $term->description ?: "Направление: {$term->name}",
                    'post_excerpt' => wp_trim_words($term->description ?: "Услуги по направлению {$term->name}", 14),
                    'post_type'    => 'olimp_term_result',
                    'post_status'  => 'publish',
                    'post_date'    => current_time('mysql'),
                ]);
                
                $virtual_post->taxonomy   = $term->taxonomy;
                $virtual_post->term_id    = $term->term_id;
                $virtual_post->term_count = $term->count;
                $virtual_post->term_slug  = $term->slug;
                
                if ($term->taxonomy === 'services') {
                    $virtual_post->guid = home_url("/services/{$term->slug}/");
                } else {
                    $link = get_term_link($term->term_id, $term->taxonomy);
                    $virtual_post->guid = !is_wp_error($link) ? $link : home_url("/{$term->taxonomy}/{$term->slug}/");
                }
                
                $posts[] = $virtual_post;
                
                if (count($posts) >= 20) break;
            }
        }
    }

    // --- Сортировка результатов ---
    if (!empty($posts)) {
        $search_normalized = mb_strtolower(trim($search_term), 'UTF-8');
        $search_clean      = olimp_norm_hyphens_spaces($search_normalized);
        $search_length     = mb_strlen($search_clean, 'UTF-8');
        
        $exact_matches  = array();
        $high_relevance = array();
        $other_posts    = array();
        
        $doctor_keys = olimp_get_doctor_position_keys();
        
        foreach ($posts as $post) {
            $title_lower = mb_strtolower(trim($post->post_title), 'UTF-8');
            $title_clean = olimp_norm_hyphens_spaces($title_lower);
            
            $category = 'other';
            
            if ($title_lower === $search_normalized || $title_clean === $search_clean) {
                $category = 'exact';
            }
            
            if ($category === 'other' && $search_length >= 10) {
                if (mb_strpos($title_lower, $search_normalized) === 0 ||
                    mb_strpos($title_clean, $search_clean) === 0) {
                    $category = 'exact';
                }
                
                if ($category === 'other') {
                    $title_start = mb_substr($title_clean, 0, $search_length, 'UTF-8');
                    if ($title_start === $search_clean) {
                        $char_after = mb_substr($title_clean, $search_length, 1, 'UTF-8');
                        if (in_array($char_after, [',', '-', '(', ')', '.', ':', ';', '—', ''], true)) {
                            $category = 'exact';
                        }
                    }
                }
            }
            
            if ($category === 'other' && $search_length >= 5) {
                if (mb_strpos($title_lower, $search_normalized) !== false) {
                    $category = 'high';
                }
            }
            
            if ($category === 'other' && $post->post_type === 'doctors') {
                foreach ($doctor_keys as $key) {
                    $position = get_post_meta($post->ID, $key, true);
                    if ($position) {
                        $position_lower = mb_strtolower($position, 'UTF-8');
                        foreach ($variants as $variant) {
                            $variant_lower = mb_strtolower(trim($variant), 'UTF-8');
                            if ($variant_lower === '' || mb_strlen($variant_lower, 'UTF-8') < 3) continue;
                            if (mb_strpos($position_lower, $variant_lower) !== false) {
                                $category = 'high';
                                break 2;
                            }
                        }
                    }
                }
            }
            
            if ($category === 'exact') {
                $exact_matches[] = $post;
            } elseif ($category === 'high') {
                $high_relevance[] = $post;
            } else {
                $other_posts[] = $post;
            }
        }
        
        $prio    = olimp_search_priority_map();
        $sort_fn = function($a, $b) use ($prio) {
            $pta = $a->post_type ?? 'post';
            $ptb = $b->post_type ?? 'post';
            $ia  = $prio[$pta] ?? 9999;
            $ib  = $prio[$ptb] ?? 9999;
            if ($ia === $ib) {
                $da = strtotime($a->post_date ?? '1970-01-01');
                $db = strtotime($b->post_date ?? '1970-01-01');
                return $db <=> $da;
            }
            return $ia <=> $ib;
        };
        
        usort($exact_matches, $sort_fn);
        usort($high_relevance, $sort_fn);
        usort($other_posts, $sort_fn);
        
        $posts = array_merge($exact_matches, $high_relevance, $other_posts);
    }

    // --- Рендер HTML ---
    if (!empty($posts)) {
        $doctor_keys = olimp_get_doctor_position_keys();
        ob_start();
        
        foreach ($posts as $post) {
            $title     = get_post_meta($post->ID, '_rb_search_title', true) ?: $post->post_title;
            $post_type = $post->post_type;
            
            $corrected    = final_fuzzy_search($search_term);
            $search_words = array_unique(array($search_term, $corrected));
            
            $highlighted_title = $title;
            foreach ($search_words as $word) {
                $highlighted_title = str_ireplace($word, '<b>' . $word . '</b>', $highlighted_title);
            }
            
            if ($post_type === 'olimp_term_result') {
                ?>
                <li class="search-result-item search-result-term">
                    <a class="search-result-link" href="<?php echo esc_url($post->guid); ?>">
                        <span class="search-result-badge">Направление</span>
                        <span class="search-result-title"><?php echo $highlighted_title; ?></span>
                        <?php if (!empty($post->term_count)): ?>
                            <p class="search-result-text">Найдено услуг: <?php echo intval($post->term_count); ?></p>
                        <?php endif; ?>
                    </a>
                </li>
                <?php
                continue;
            }
            
            if ($post_type === 'doctors') {
                $occup = '';
                foreach ($doctor_keys as $key) {
                    $val = get_post_meta($post->ID, $key, true);
                    if ($val) { $occup = $val; break; }
                }
                $highlighted_occup = $occup;
                foreach ($search_words as $word) {
                    $highlighted_occup = str_ireplace($word, '<b>' . $word . '</b>', $highlighted_occup);
                }
                ?>
                <li class="search-result-item">
                    <a class="search-result-link" href="<?php echo get_permalink($post->ID); ?>">
                        <span class="search-result-title"><?php echo $highlighted_title; ?></span>
                        <?php if ($occup): ?>
                            <p class="search-result-text"><?php echo $highlighted_occup; ?></p>
                        <?php endif; ?>
                    </a>
                </li>
                <?php
                continue;
            }
            
            if ($post_type === 'center-services') {
                $price        = get_post_meta($post->ID, '_rb_serv_price', true);
                $service_link = get_center_service_link($post->ID);
                ?>
                <li class="search-result-item">
                    <a class="search-result-link" href="<?php echo esc_url($service_link); ?>">
                        <span class="search-result-title"><?php echo $highlighted_title; ?></span>
                        <?php if ($price): ?>
                            <span class="search-result-price"><?php echo esc_html($price); ?> ₽</span>
                        <?php endif; ?>
                    </a>
                </li>
                <?php
                continue;
            }
            ?>
            <li class="search-result-item">
                <a class="search-result-link" href="<?php echo get_permalink($post->ID); ?>">
                    <span class="search-result-title"><?php echo $highlighted_title; ?></span>
                    <p class="search-result-text"><?php echo wp_trim_words(get_the_excerpt(), 14, ' ...'); ?></p>
                </a>
            </li>
            <?php
        }
        
        $content = ob_get_clean();
    } else {
        $content = '<li class="search-result-empty">По запросу "' . esc_html($search_term) . '" ничего не найдено</li>';
    }
    
    log_search_query($search_term . ' (header ajax)', count($posts), $variants);
    
    wp_send_json($content);
}

if (!empty($posts) && isset($_GET['debug'])) {
    $debug_info = array(
        'total' => count($posts),
        'exact' => count($exact_matches),
        'high' => count($high_relevance),
        'other' => count($other_posts),
        'first_3' => array()
    );
    
    foreach (array_slice($posts, 0, 3) as $i => $p) {
        $debug_info['first_3'][] = array(
            'pos' => $i + 1,
            'type' => $p->post_type,
            'title' => $p->post_title
        );
    }
    
    error_log('=== AJAX SEARCH DEBUG ===');
    error_log('Query: ' . $search_term);
    error_log(print_r($debug_info, true));
}

//ajax search doctors page
add_action( 'wp_ajax_rb_doctors_ajax_search', 'rb_doctors_ajax_search' );
add_action( 'wp_ajax_nopriv_rb_doctors_ajax_search', 'rb_doctors_ajax_search' );
 
function rb_doctors_ajax_search() {
 
  $search_term = isset( $_GET[ 'term' ] ) ? $_GET[ 'term' ] : '';
 
  $posts = get_posts( array(
    'posts_per_page' => -1,
    'post_type' => array( 'doctors' ),
    's' => $search_term
  ) );
 
  $results = array();
 
  if( $posts ) {

    ob_start();
 
    foreach( $posts as $post ) {

      // $results[] = array(
      //   'id'    => $post->ID,
      //   'value' => $post->post_title,
      //   'url'   => get_permalink( $post->ID )
      // );

      $title = get_post_meta( $post->ID, '_rb_search_title', true ) ?: $post->post_title;
      $occup = get_post_meta( $post->ID, '_rb_doc_occup', true );

      ?>

        <li class="search-doctors-item">
          <a class="search-doctors-link" href="<?php echo get_permalink( $post->ID ); ?>">
            <div class="search-doctors-image" style="background-image: url('<?php echo get_the_post_thumbnail_url( $post->ID )?>');"></div>
            <div class="search-doctors-info">
              <span class="search-doctors-fio"><?php echo str_replace( array( $search_term ), '<b>' . $search_term . '</b>', $title );  ?></span>
              <?php if( $occup ) : ?>
                <p class="search-doctors-occup">Должность: <?php echo $occup; ?></p>
              <?php endif; ?>
            </div>
          </a>
        </li>

      <?php

    }

    $content = ob_get_contents();
    ob_end_clean();
 
  }


 
  wp_send_json( $content );
 
}

add_action('wp_ajax_rb_popup_form', 'rb_popup_form');
add_action('wp_ajax_nopriv_rb_popup_form', 'rb_popup_form');

function rb_popup_form(){

    check_ajax_referer( 'rbPopupNonce', 'rb_popup_nonce' );

    $name = isset( $_POST[ 'user_name' ] ) ? sanitize_text_field( $_POST[ 'user_name' ] ) : '';
    $phone = isset( $_POST[ 'user_phone' ] ) ? sanitize_text_field( $_POST[ 'user_phone' ] ) : '';
    $user_msg = isset( $_POST[ 'user_msg' ] ) ? sanitize_textarea_field( $_POST[ 'user_msg' ] ) : '';
    $curr_url = isset( $_POST[ 'curr_url' ] ) ? sanitize_text_field( $_POST[ 'curr_url' ] ) : '';

    $user_service = isset( $_POST[ 'user_service' ] ) ? sanitize_text_field( $_POST[ 'user_service' ] ) : '';
    $user_servicestax = isset( $_POST[ 'user_servicestax' ] ) ? sanitize_text_field( $_POST[ 'user_servicestax' ] ) : '';
    $user_doctors = isset( $_POST[ 'user_doctors' ] ) ? sanitize_text_field( $_POST[ 'user_doctors' ] ) : '';
    $user_programms = isset( $_POST[ 'user_programms' ] ) ? sanitize_text_field( $_POST[ 'user_programms' ] ) : '';

    $center_name = isset( $_POST[ 'center-name' ] ) ? sanitize_text_field( $_POST[ 'center-name' ] ) : '';

    $msg = $_POST[ 'message' ] ? $_POST[ 'message' ] : '';

    if ( $msg ) {wp_die( 'Недопустимые данные! Попробуйте снова' );}

    if ( ! $phone  )  {$error = 'Пожалуйста заполните поле с телефоном.';}
    if ( ! $name  )  {$error = 'Пожалуйста укажите имя.';}
    // if ( ! $user_msg  )  { $error = 'Пожалуйста укажите сообщение.'; }
    if ( ! $name && ! $phone )  {
        $error = 'Пожалуйста заполните поле имя и телефон.';
    }
    // if ( ! $name && ! $user_msg )  {$error = 'Пожалуйста заполните поле имя и сообщение.';}
    // if ( ! $name && ! $phone && ! $user_msg )  {$error = 'Пожалуйста заполните все поля.';}
    if ( $error ) {
        $form_array[ 'result' ] = 'error';
        $form_array[ 'content' ] = $error;
        echo json_encode( $form_array );
    } else {
        $msg = "
            <h2>Сообщение с сайта olimp5.ru</h2>
            <p><strong>Имя: </strong> {$name}</p>
            <p><strong>Телефон: </strong> {$phone}</p>
            <p><strong>Сообщение: </strong> {$user_msg}</p>
            <p><strong>Страница сайта, с которой отправили заявку: </strong> {$curr_url}</p>
            ";
        if ( $user_service ) {$msg .= "<p><strong>Услуга, на которую планируется запись: </strong> {$user_service}</p>";}
        if ( $user_servicestax ) {$msg .= "<p><strong>Направление, на которое планируется запись: </strong> {$user_servicestax}</p>";}
        if ( $user_doctors ) {$msg .= "<p><strong>Врач, к которому планируется запись: </strong> {$user_doctors}</p>";}
        if ( $user_programms ) {$msg .= "<p><strong>Программа, на которую планируется запись: </strong> {$user_programms}</p>";}
        if ( $center_name ) {$msg .= "<p><strong>Центр в который хотят записаться: </strong> {$center_name}</p>";}

        if( function_exists( 'carbon_get_theme_option' ) ){
          $emails = carbon_get_theme_option( 'emails' ) ? explode( ',', carbon_get_theme_option( 'emails' )  ) : get_option( 'admin_email' );
        }
        // $emails = get_option( 'admin_email' );
        // $emails = 'burtnek.roman@yandex.ru';
        $site_url = site_url();
        $protocols =  array( 'http://', 'https://' );
        $url = str_replace( $protocols, '', $site_url );
        $headers = array(
            'From: Сообщение с сайта <no-reply@'. $url .'>',
            'content-type: text/html',
            'Reply-To: Olimp5 <no-reply@'. $url .'>',
            'X-Sender: Сообщение с сайта <no-reply@'. $url .'>',
            'X-Mailer: PHP/' . phpversion().'',
            'X-Priority: 1',
            'Return-Path: <no-reply@'. $url .'>',
            'MIME-Version: 1.0',
        );
        $mail = wp_mail( $emails, "Новое сообщение с сайта", $msg, $headers );
        if( $mail ){
            $form_array[ 'result' ] = 'success';
            $form_array[ 'content' ] = 'Сообщение успешно отправлено.';
            echo json_encode( $form_array );
        } else {
            $error = 'Сообщение не удалось отправить.';
            $form_array[ 'result' ] = 'error';
            $form_array[ 'content' ] = $error;
            echo json_encode( $form_array );
        };
    }
    wp_die();
}

//subscribe form
add_action('wp_ajax_rb_subscribe_form', 'rb_subscribe_form');
add_action('wp_ajax_nopriv_rb_subscribe_form', 'rb_subscribe_form');

function rb_subscribe_form(){

    check_ajax_referer( 'rbSubscribeNonce', 'rb_subscribe_nonce' );

    $email = isset( $_POST[ 'email' ] ) ? sanitize_text_field( $_POST[ 'email' ] ) : '';

    $msg = $_POST[ 'message' ] ? $_POST[ 'message' ] : '';

    if ( $msg ) {
        wp_die( 'Недопустимые данные! Попробуйте снова' );
    }

    if ( ! $email  )  {
        $error = 'Пожалуйста заполните поле с email.';
    }
    

    if ( $error ) {

        $form_array[ 'result' ] = 'error';
        $form_array[ 'content' ] = $error;

        echo json_encode( $form_array );

    } else {

        $msg = "
            <h2>Подписка на рассылку на сайте olimp5.ru</h2>

            <p>
              <strong>email: </strong> {$email}
            ";

        if( function_exists( 'carbon_get_theme_option' ) ){

          $emails = carbon_get_theme_option( 'emails' ) ? explode( ',', carbon_get_theme_option( 'emails' )  ) : get_option( 'admin_email' );

        }

        // $admin_email = get_option( 'admin_email' );
        $site_url = site_url();
        $protocols =  array( 'http://', 'https://' );
        $url = str_replace( $protocols, '', $site_url );
        $headers = array(
            'From: Сообщение с сайта <no-reply@'. $url .'>',
            'content-type: text/html',
            'Reply-To: Olimp5 <no-reply@'. $url .'>',
            'X-Sender: Сообщение с сайта <no-reply@'. $url .'>',
            'X-Mailer: PHP/' . phpversion().'',
            'X-Priority: 1',
            'Return-Path: <no-reply@'. $url .'>',
            'MIME-Version: 1.0',
        );

        $mail = wp_mail( $emails, "Подписка на рассылку (olimp5)", $msg, $headers );

        if( $mail ){

            $form_array[ 'result' ] = 'success';
            $form_array[ 'content' ] = 'Сообщение успешно отправлено.';

            echo json_encode( $form_array );

        } else {

            $error = 'Сообщение не удалось отправить.';

            $form_array[ 'result' ] = 'error';
            $form_array[ 'content' ] = $error;

            echo json_encode( $form_array );

        };

    }


    wp_die();
}


//change cat
add_action('wp_ajax_rb_change_cat', 'rb_change_cat');
add_action('wp_ajax_nopriv_rb_change_cat', 'rb_change_cat');

function rb_change_cat(){


    // $promo_pages = get_pages(
    //   array(
    //       'meta_key' => '_wp_page_template',
    //       'meta_value' => 'template-page/page-promo.php'
    //   )
    // );

    // $exclude_ids = array();

    // if ( $promo_pages ) {

    //   $exclude_ids = carbon_get_post_meta( $promo_pages[0]->ID, 'rb_actions' );

    //   $exclude_ids = array_map( function( $item ){ return $item['id']; }, $exclude_ids );

    // }

    $currcat = isset( $_POST[ 'currcat' ] ) ? sanitize_text_field( $_POST[ 'currcat' ] ) : '';


    $news_query = new WP_Query(
      array(
        'post_type'   => 'post',
        'post_status' => 'publish',
        'cat'         => $currcat,
        // 'post__not_in' => $exclude_ids
      )
    );

    if ( $news_query->have_posts() ) {


      ob_start();

      while( $news_query->have_posts() ){
         $news_query->the_post();

         get_template_part( 'template-part/main', 'blog-item' );

      }

      $html = ob_get_contents();
      ob_end_clean();


          $form_array[ 'result' ] = 'success';
          $form_array[ 'content' ] = $html;

          echo json_encode( $form_array );

      } else {

          $error = 'Не удалось.';

          $form_array[ 'result' ] = 'error';
          $form_array[ 'content' ] = $error;

          echo json_encode( $form_array );

      };

    wp_die();

}


//sanatorium form
add_action('wp_ajax_rb_sanatorium_form', 'rb_sanatorium_form');
add_action('wp_ajax_nopriv_rb_sanatorium_form', 'rb_sanatorium_form');

function rb_sanatorium_form(){

    check_ajax_referer( 'rbSanatoriumNonce', 'rb_sanatorium_nonce' );

    $name = isset( $_POST[ 'name' ] ) ? sanitize_text_field( $_POST[ 'name' ] ) : '';
    $phone = isset( $_POST[ 'phone' ] ) ? sanitize_text_field( $_POST[ 'phone' ] ) : '';
    $page_id = isset( $_POST[ 'page_id' ] ) ? get_the_title( $_POST[ 'page_id' ] ) : '';
    $people = isset( $_POST[ 'people' ] ) ? sanitize_text_field( $_POST[ 'people' ] ) : '';
    $dates = isset( $_POST[ 'dates' ] ) ? sanitize_text_field( $_POST[ 'dates' ] ) : '';
    $room_1 = isset( $_POST[ 'room_1' ] ) ? sanitize_text_field( $_POST[ 'room_1' ] ) : '';
    $room_2 = isset( $_POST[ 'room_2' ] ) ? sanitize_text_field( $_POST[ 'room_2' ] ) : '';
    $room_3 = isset( $_POST[ 'room_3' ] ) ? sanitize_text_field( $_POST[ 'room_3' ] ) : '';
    $food_standart = isset( $_POST[ 'food_standart' ] ) ? sanitize_text_field( $_POST[ 'food_standart' ] ) : '';
    $food_personal = isset( $_POST[ 'food_personal' ] ) ? sanitize_text_field( $_POST[ 'food_personal' ] ) : '';
    $food_empty = isset( $_POST[ 'food_empty' ] ) ? sanitize_text_field( $_POST[ 'food_empty' ] ) : '';

    $msg = $_POST[ 'message' ] ? $_POST[ 'message' ] : '';

    if ( $msg ) {
        wp_die( 'Недопустимые данные! Попробуйте снова' );
    }

    if ( ! $name  )  {
        $error = 'Пожалуйста заполните поле с контактным лицом.';
    }
    
    if ( ! $phone  )  {
        $error = 'Пожалуйста заполните поле с контактным телефоном.';
    }

    if ( ! $phone && ! $name  )  {
        $error = 'Пожалуйста заполните поле с контактным телефоном и c контактным лицом.';
    }


    if ( $error ) {

        $form_array[ 'result' ] = 'error';
        $form_array[ 'content' ] = $error;

        echo json_encode( $form_array );

    } else {

        $msg = "
            <h2>Заявка на санаторий </h2>
            <p>
              <strong>Программа: </strong> {$page_id}
              <strong>Дата: </strong> {$dates}
              <strong>Кол-во людей: </strong> {$people}
              <strong>Одноместная палата: </strong> {$room_1}
              <strong>Двухместная палата: </strong> {$room_2}
              <strong>Палата для ММП: </strong> {$room_3}
              <strong>Стандартное питание: </strong> {$food_standart}
              <strong>Индивидуальное питание: </strong> {$food_personal}
              <strong>Без питания: </strong> {$food_empty}
              <strong>Контактное лицо: </strong> {$name}
              <strong>Телефон: </strong> {$phone}
            </p>
            ";
       


        if( function_exists( 'carbon_get_theme_option' ) ){

          $emails = carbon_get_theme_option( 'emails' ) ? explode( ',', carbon_get_theme_option( 'emails' )  ) : get_option( 'admin_email' );

        }
        // $admin_email = get_option( 'admin_email' );
        $site_url = site_url();
        $protocols =  array( 'http://', 'https://' );
        $url = str_replace( $protocols, '', $site_url );
        $headers = array(
            'From: Сообщение с сайта <no-reply@'. $url .'>',
            'content-type: text/html',
            'Reply-To: Olimp5 <no-reply@'. $url .'>',
            'X-Sender: Сообщение с сайта <no-reply@'. $url .'>',
            'X-Mailer: PHP/' . phpversion().'',
            'X-Priority: 1',
            'Return-Path: <no-reply@'. $url .'>',
            'MIME-Version: 1.0',
        );

        $mail = wp_mail( $emails, "Заказ места в санатории (Olimp5.ru)", $msg, $headers );

        if( $mail ){

            $form_array[ 'result' ] = 'success';
            $form_array[ 'content' ] = 'Сообщение успешно отправлено.';

            echo json_encode( $form_array );

        } else {

            $error = 'Сообщение не удалось отправить.';

            $form_array[ 'result' ] = 'error';
            $form_array[ 'content' ] = $error;

            echo json_encode( $form_array );

        };

    }


    wp_die();
}


//video form
add_action('wp_ajax_rb_video_form', 'rb_video_form');
add_action('wp_ajax_nopriv_rb_video_form', 'rb_video_form');

function rb_video_form(){

    // check_ajax_referer( 'rbVideoNonce', 'rb_video_nonce' );

    $name = isset( $_POST[ 'user_name' ] ) ? sanitize_text_field( $_POST[ 'user_name' ] ) : '';
    $birthday = isset( $_POST[ 'user_birth' ] ) ? sanitize_text_field( $_POST[ 'user_birth' ] ) : '';
    $phone = isset( $_POST[ 'user_phone' ] ) ? sanitize_text_field( $_POST[ 'user_phone' ] ) : '';
    $email = isset( $_POST[ 'user_email' ] ) ? sanitize_text_field( $_POST[ 'user_email' ] ) : '';
    $user_lpu = isset( $_POST[ 'user_lpu' ] ) ? sanitize_textarea_field( $_POST[ 'user_lpu' ] ) : '';
    $user_spacialnost = isset( $_POST[ 'user_spacialnost' ] ) ? sanitize_text_field( $_POST[ 'user_spacialnost' ] ) : '';

    $user_info_tele = isset( $_POST[ 'user_info_tele' ] ) ? 'Telegram' : '';
    $user_info_wa = isset( $_POST[ 'user_info_wa' ] ) ? 'WhatsApp' : '';
    $user_info_email = isset( $_POST[ 'user_info_email' ] ) ? 'Email' : '';
    $user_info_sms = isset( $_POST[ 'user_info_sms' ] ) ? 'Телефон' : '';

    $msg = $_POST[ 'message' ] ? $_POST[ 'message' ] : '';

    if ( $msg ) {
        wp_die( 'Недопустимые данные! Попробуйте снова' );
    }

    if ( ! $birthday  )  {
        $error = 'Пожалуйста заполните поле с датойрождения.';
    }
    if ( ! $phone  )  {
        $error = 'Пожалуйста заполните поле с телефоном.';
    }
    if ( ! $name  )  {
        $error = 'Пожалуйста укажите имя.';
    }
    if ( ! $email  )  {
        $error = 'Пожалуйста укажите email.';
    }
    if ( ! $user_lpu  )  {
        $error = 'Пожалуйста укажите учреждение.';
    }
    if ( ! $user_spacialnost  )  {
        $error = 'Пожалуйста укажите специальность.';
    }

    if ( ! $name && ! $phone )  {
        $error = 'Пожалуйста заполните поле имя и телефон.';
    }
    if ( ! $name && ! $email )  {
        $error = 'Пожалуйста заполните поле имя и email.';
    }
    if ( ! $name && ! $phone && ! $email )  {
        $error = 'Пожалуйста заполните все поля.';
    }

    if ( $error ) {

        $form_array[ 'result' ] = 'error';
        $form_array[ 'content' ] = $error;

        echo json_encode( $form_array );

    } else {

        $msg = "
            <h2>Сообщение со страницы с видео для врачей сайта olimp5.ru</h2>

            <p>
              <strong>Имя: </strong> {$name}
            </p>
            <p>
              <strong>Дата рождения: </strong> {$birthday}
            </p>
            <p>
              <strong>Телефон: </strong> {$phone}
            </p>
            <p>
              <strong>Телефон: </strong> {$email}
            </p>
            <p>
              <strong>ЛПУ: </strong> {$user_lpu}
            </p>
            <p>
              <strong>Специальность: </strong> {$user_spacialnost}
            </p>
            <p>
              <strong>Предпочитаемые виды получения информации: </strong> {$user_info_tele} {$user_info_wa} {$user_info_email} {$user_info_sms}
            </p>
            ";

        if( function_exists( 'carbon_get_theme_option' ) ){

          $emails = carbon_get_theme_option( 'video_emails' ) ? explode( ',', carbon_get_theme_option( 'video_emails' )  ) : 'v.karpov@olimp5.ru';

        }

        //$emails = get_option( 'admin_email' );
        // $emails = 'burtnek.roman@yandex.ru';
        // $emails = 'i.liventseva@olimp5.ru';
        $site_url = site_url();
        $protocols =  array( 'http://', 'https://' );
        $url = str_replace( $protocols, '', $site_url );
        $headers = array(
            'From: Сообщение с сайта <no-reply@'. $url .'>',
            'content-type: text/html',
            'Reply-To: Olimp5 <no-reply@'. $url .'>',
            'X-Sender: Сообщение с сайта <no-reply@'. $url .'>',
            'X-Mailer: PHP/' . phpversion().'',
            'X-Priority: 1',
            'Return-Path: <no-reply@'. $url .'>',
            'MIME-Version: 1.0',
        );

        $mail = wp_mail( $emails, "Новое сообщение с сайта", $msg, $headers );

        if( $mail ){

            $form_array[ 'result' ] = 'success';
            $form_array[ 'content' ] = 'Сообщение успешно отправлено.';

            echo json_encode( $form_array );

        } else {

            $error = 'Сообщение не удалось отправить.';

            $form_array[ 'result' ] = 'error';
            $form_array[ 'content' ] = $error;

            echo json_encode( $form_array );

        };

    }


    wp_die();
}

//contact form
add_action('wp_ajax_rb_upload_more_promo', 'rb_upload_more_promo');
add_action('wp_ajax_nopriv_rb_upload_more_promo', 'rb_upload_more_promo');

function rb_upload_more_promo(){

    $curr_obj_slug = isset( $_POST[ 'tax' ] ) ? sanitize_text_field( $_POST[ 'tax' ] ) : '';

    $promo = new WP_Query(
        array(
            'post_type' => 'promo',
            'posts_per_page' => -1,
            'offset' => 4,
            // 'paged' => 2,
            'tax_query' => array(
                array(
                    'taxonomy' => 'promo-cat',
                    'field' => 'slug',
                    'terms' => $curr_obj_slug
                )
            )
        )
    );

    if ( $news_query->have_posts() ) {


      ob_start();

      while( $news_query->have_posts() ){
         $news_query->the_post();

         get_template_part( 'template-part/promo', 'item' );

      }

      $html = ob_get_contents();
      ob_end_clean();


          $form_array[ 'result' ] = 'success';
          $form_array[ 'content' ] = $html;

          echo json_encode( $form_array );

      } else {

          $error = 'Не удалось.';

          $form_array[ 'result' ] = 'error';
          $form_array[ 'content' ] = $error;

          echo json_encode( $form_array );

      };

    wp_die();

}

//loadmore rations

add_action('wp_ajax_rb_loadmore_videos', 'rb_loadmore_videos');
add_action('wp_ajax_nopriv_rb_loadmore_videos', 'rb_loadmore_videos');

function rb_loadmore_videos(){

    $currcat = isset( $_POST['currcat'] ) ? $_POST['currcat'] : '';

    $args = array(
      'post_type'     => 'videos',
      'post_status'   => 'publish',
      'offset'        => 8,
      'tax_query' => array(
          array(
              'taxonomy'  => 'videos-type',
              'field'     => 'term_id',
              'terms'     => array( $currcat )
          )
      ),
      'posts_per_page' => 9999
    );

    $new_query = new WP_Query( $args );

    if ( $new_query->have_posts() ) {



      // global $wp_query;

      ob_start();

      while ( $new_query->have_posts() ) {
        $new_query->the_post();


        $video_desc = get_post_meta( get_the_ID(), '_rb_desc', true );
              $video_file = get_post_meta( get_the_ID(), '_rb_file', true ) ? wp_get_attachment_url( get_post_meta( get_the_ID(), '_rb_file', true ) ) : '';
              $video_link = get_post_meta( get_the_ID(), '_rb_link', true );
              $rb_not_show_title = get_post_meta( get_the_ID(), '_rb_not_show_title', true );
          ?>
          <div class="rb-school__item">

              <a data-fancybox="video-gallery" data-type="iframe" href="<?php echo esc_url( str_replace( 'video', 'play/embed', $video_link ) ); ?>">
                  <?php if ( $video_link ) : ?>
                      <iframe loading="lazy" width="100%" height="200px" src="<?php echo esc_url( str_replace( 'video', 'play/embed', $video_link ) ); ?>" frameBorder="0" allow="clipboard-write; autoplay" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
                  <?php elseif( has_post_thumbnail() ) : ?>
                      <picture class="rb-school__item-img rb-play <?php if( $rb_not_show_title && 'yes' === $rb_not_show_title ) { echo 'rb-video-without-text'; } ?>">
                          <?php the_post_thumbnail( 'large', array( 'class' => 'rb-img-cover' ) ); ?>
                          <span class="rb-school__item-overlay"></span>

                      </picture>
                  <?php endif; ?>
                  <?php if( !$rb_not_show_title && 'yes' != $rb_not_show_title ) : ?>
                      <div class="rb-school__item-info rb-school__item-info2">
                          <h3 class="rb-school__item-title"><?php the_title(); ?></h3>
                          <?php if ( $video_desc ) : ?>
                              <span class="rb-school__item-fio">
                                  <?php echo esc_html( $video_desc ); ?>
                              </span>
                          <?php endif; ?>
                      </div>
                  <?php endif; ?>
              </a>
              
          </div>

        <?php

      }
      wp_reset_query();

      $content = ob_get_contents();
      ob_end_clean();

        $form_array[ 'result' ] = 'success';
        $form_array[ 'content' ] = $content;

    } else {

        $form_array[ 'result' ] = 'error';
        $form_array[ 'content' ] = $new_query;

    }

    echo json_encode( $form_array );

    wp_die();
}


//schedule form
add_action('wp_ajax_rb_schedule_form', 'rb_schedule_form');
add_action('wp_ajax_nopriv_rb_schedule_form', 'rb_schedule_form');

function rb_schedule_form(){

    check_ajax_referer( 'rbScheduleNonce', 'rb_schedule_nonce' );

    $name = isset( $_POST[ 'sch_name' ] ) ? sanitize_text_field( $_POST[ 'sch_name' ] ) : '';
    $phone = isset( $_POST[ 'sch_phone' ] ) ? sanitize_text_field( $_POST[ 'sch_phone' ] ) : '';
    $user_msg = isset( $_POST[ 'user_msg' ] ) ? sanitize_textarea_field( $_POST[ 'user_msg' ] ) : '';

    $prog_name = isset( $_POST[ 'prog-name' ] ) ? sanitize_text_field( $_POST[ 'prog-name' ] ) : '';

    $msg = $_POST[ 'message' ] ? $_POST[ 'message' ] : '';

    if ( $msg ) {
        wp_die( 'Недопустимые данные! Попробуйте снова' );
    }

    if ( ! $phone  )  {
        $error = 'Пожалуйста заполните поле с телефоном.';
    }
    if ( ! $name  )  {
        $error = 'Пожалуйста укажите имя.';
    }

    if ( ! $name && ! $phone )  {
        $error = 'Пожалуйста заполните поле имя и телефон.';
    }

    if ( $error ) {

        $form_array[ 'result' ] = 'error';
        $form_array[ 'content' ] = $error;

        echo json_encode( $form_array );

    } else {

        $msg = "
            <h2>Сообщение с сайта olimp5.ru</h2>

            <p>
              <strong>Имя: </strong> {$name}
            </p>
            <p>
              <strong>Телефон: </strong> {$phone}
            </p>
            <p>
              <strong>Запись на вебинар: </strong> {$prog_name}
            </p>
            ";


        if( function_exists( 'carbon_get_theme_option' ) ){

          $emails = carbon_get_theme_option( 'emails' ) ? explode( ',', carbon_get_theme_option( 'emails' )  ) : get_option( 'admin_email' );

        }

        // $emails = get_option( 'admin_email' );
        $site_url = site_url();
        $protocols =  array( 'http://', 'https://' );
        $url = str_replace( $protocols, '', $site_url );
        $headers = array(
            'From: Сообщение с сайта <no-reply@'. $url .'>',
            'content-type: text/html',
            'Reply-To: Olimp5 <no-reply@'. $url .'>',
            'X-Sender: Сообщение с сайта <no-reply@'. $url .'>',
            'X-Mailer: PHP/' . phpversion().'',
            'X-Priority: 1',
            'Return-Path: <no-reply@'. $url .'>',
            'MIME-Version: 1.0',
        );

        $mail = wp_mail( $emails, "Запись на вебинар", $msg, $headers );

        if( $mail ){

            $form_array[ 'result' ] = 'success';
            $form_array[ 'content' ] = 'Сообщение успешно отправлено.';

            echo json_encode( $form_array );

        } else {

            $error = 'Сообщение не удалось отправить.';

            $form_array[ 'result' ] = 'error';
            $form_array[ 'content' ] = $error;

            echo json_encode( $form_array );

        };

    }


    wp_die();
}

//vychet form
add_action('wp_ajax_rb_vychet', 'rb_vychet');
add_action('wp_ajax_nopriv_rb_vychet', 'rb_vychet');

function rb_vychet(){

    check_ajax_referer('rbVychetNonce', 'rb_vychet_nonce');

    $honeypot = isset($_POST['message']) ? $_POST['message'] : '';
    if ($honeypot) {
        wp_die('Недопустимые данные! Попробуйте снова');
    }

    $forwhom = isset($_POST['forwhom']) ? sanitize_text_field($_POST['forwhom']) : '';

    $error = false;
    $msg = '';

    if ($forwhom === 'one') {

        $rb_vychet_name         = isset($_POST['rb_vychet_name']) ? sanitize_text_field($_POST['rb_vychet_name']) : '';
        $rb_vychet_birthday     = isset($_POST['rb_vychet_birthday']) ? sanitize_text_field($_POST['rb_vychet_birthday']) : '';
        $rb_vychet_period       = isset($_POST['rb_vychet_period']) ? sanitize_text_field($_POST['rb_vychet_period']) : '';
        $rb_vychet_phone        = isset($_POST['rb_vychet_phone']) ? sanitize_text_field($_POST['rb_vychet_phone']) : '';
        $rb_vychet_inn          = isset($_POST['rb_vychet_inn']) ? sanitize_text_field($_POST['rb_vychet_inn']) : '';

        $rb_vychet_copy         = isset($_POST['rb_vychet_copy']) ? sanitize_text_field($_POST['rb_vychet_copy']) : '';
        $rb_vychet_post         = isset($_POST['rb_vychet_post']) ? sanitize_text_field($_POST['rb_vychet_post']) : '';
        $rb_vychet_post_address = isset($_POST['rb_vychet_post_address']) ? sanitize_text_field($_POST['rb_vychet_post_address']) : '';

        $rb_vychet_lk           = isset($_POST['rb_vychet_lk']) ? sanitize_text_field($_POST['rb_vychet_lk']) : '';
        $rb_vychet_paper        = isset($_POST['rb_vychet_paper']) ? sanitize_text_field($_POST['rb_vychet_paper']) : '';

        $msg = "
            <h2>Запрос формы налогового вычета с сайта olimp5.ru</h2>

            <p><strong>Для кого: </strong> Для себя</p>

            <p><strong>ФИО Налогоплательщика: </strong> {$rb_vychet_name}</p>
            <p><strong>Дата рождения: </strong> {$rb_vychet_birthday}</p>
            <p><strong>Период: </strong> {$rb_vychet_period}</p>
            <p><strong>Телефон: </strong> {$rb_vychet_phone}</p>
            <p><strong>ИНН: </strong> {$rb_vychet_inn}</p>

            <hr>

            <p><strong>Требуется ли копия договора: </strong> {$rb_vychet_copy}</p>
            <p><strong>Отправить справку заказным письмом (Почта России): </strong> {$rb_vychet_post}</p>
            <p><strong>Адрес для отправки: </strong> {$rb_vychet_post_address}</p>

            <p><strong>Отправить в личный кабинет налогоплательщика: </strong> {$rb_vychet_lk}</p>
            <p><strong>Выдать на бумажном носителе при личном обращении: </strong> {$rb_vychet_paper}</p>
        ";

    } elseif ($forwhom === 'two') {

        $rb_vychet_name2             = isset($_POST['rb_vychet_name2']) ? sanitize_text_field($_POST['rb_vychet_name2']) : '';
        $rb_vychet_phone2            = isset($_POST['rb_vychet_phone2']) ? sanitize_text_field($_POST['rb_vychet_phone2']) : '';
        $rb_vychet_birthday2         = isset($_POST['rb_vychet_birthday2']) ? sanitize_text_field($_POST['rb_vychet_birthday2']) : '';
        $rb_vychet_inn2              = isset($_POST['rb_vychet_inn2']) ? sanitize_text_field($_POST['rb_vychet_inn2']) : '';
        $rb_vychet_period2           = isset($_POST['rb_vychet_period2']) ? sanitize_text_field($_POST['rb_vychet_period2']) : '';

        $rb_vychet_patient_name2     = isset($_POST['rb_vychet_patient_name2']) ? sanitize_text_field($_POST['rb_vychet_patient_name2']) : '';
        $rb_vychet_patient_birthday  = isset($_POST['rb_vychet_birthday']) ? sanitize_text_field($_POST['rb_vychet_birthday']) : '';
        $rb_vychet_sibling2          = isset($_POST['rb_vychet_sibling2']) ? str_replace('/', '', sanitize_text_field($_POST['rb_vychet_sibling2'])) : '';

        $rb_vychet_copy2             = isset($_POST['rb_vychet_copy2']) ? sanitize_text_field($_POST['rb_vychet_copy2']) : '';
        $rb_vychet_post              = isset($_POST['rb_vychet_post']) ? sanitize_text_field($_POST['rb_vychet_post']) : '';
        $rb_vychet_post_address      = isset($_POST['rb_vychet_post_address']) ? sanitize_text_field($_POST['rb_vychet_post_address']) : '';

        $rb_vychet_lk                = isset($_POST['rb_vychet_lk']) ? sanitize_text_field($_POST['rb_vychet_lk']) : '';
        $rb_vychet_paper             = isset($_POST['rb_vychet_paper']) ? sanitize_text_field($_POST['rb_vychet_paper']) : '';

        $msg = "
            <h2>Запрос формы налогового вычета с сайта olimp5.ru</h2>

            <p><strong>Для кого: </strong> Для другого человека</p>

            <p><strong>ФИО Налогоплательщика: </strong> {$rb_vychet_name2}</p>
            <p><strong>Телефон Налогоплательщика: </strong> {$rb_vychet_phone2}</p>
            <p><strong>Дата рождения Налогоплательщика: </strong> {$rb_vychet_birthday2}</p>
            <p><strong>ИНН Налогоплательщика: </strong> {$rb_vychet_inn2}</p>
            <p><strong>Период: </strong> {$rb_vychet_period2}</p>

            <hr>

            <p><strong>ФИО Пациента: </strong> {$rb_vychet_patient_name2}</p>
            <p><strong>Дата рождения Пациента: </strong> {$rb_vychet_patient_birthday}</p>
            <p><strong>Степень родства: </strong> {$rb_vychet_sibling2}</p>

            <hr>

            <p><strong>Требуется ли копия договора: </strong> {$rb_vychet_copy2}</p>
            <p><strong>Отправить справку заказным письмом (Почта России): </strong> {$rb_vychet_post}</p>
            <p><strong>Адрес для отправки: </strong> {$rb_vychet_post_address}</p>

            <p><strong>Отправить в личный кабинет налогоплательщика: </strong> {$rb_vychet_lk}</p>
            <p><strong>Выдать на бумажном носителе при личном обращении: </strong> {$rb_vychet_paper}</p>
        ";

    } else {
        $error = 'Не удалось определить тип формы (forwhom).';
    }

    if ($error) {
        echo json_encode([
            'result'  => 'error',
            'content' => $error,
        ]);
        wp_die();
    }

    if (function_exists('carbon_get_theme_option')) {
        $emails = carbon_get_theme_option('emails')
            ? explode(',', carbon_get_theme_option('emails'))
            : get_option('admin_email');
    } else {
        $emails = get_option('admin_email');
    }

    $emails = 'admin.service@olimp5.ru';

    $site_url = site_url();
    $protocols = ['http://', 'https://'];
    $url = str_replace($protocols, '', $site_url);

    $headers = [
        'From: Сообщение с сайта <no-reply@' . $url . '>',
        'content-type: text/html',
        'Reply-To: Olimp5 <no-reply@' . $url . '>',
        'X-Sender: Сообщение с сайта <no-reply@' . $url . '>',
        'X-Mailer: PHP/' . phpversion(),
        'X-Priority: 1',
        'Return-Path: <no-reply@' . $url . '>',
        'MIME-Version: 1.0',
    ];

    $mail = wp_mail($emails, 'Запрос справки на налоговый вычет', $msg, $headers);

    if ($mail) {
        echo json_encode([
            'result'  => 'success',
            'content' => 'Сообщение успешно отправлено.',
        ]);
    } else {
        echo json_encode([
            'result'  => 'error',
            'content' => 'Сообщение не удалось отправить.',
        ]);
    }

    wp_die();
}



//rb vychet load

add_action('wp_ajax_rb_vychet_load', 'rb_vychet_load');
add_action('wp_ajax_nopriv_rb_vychet_load', 'rb_vychet_load');

function rb_vychet_load(){

    $vychetTab = isset( $_POST['data'] ) ? $_POST['data'] : '';

    if ( $vychetTab  ) {

      ob_start();

      if ( $vychetTab == 'one' ) {

        get_template_part( 'template-part/forms/form', 'vychet-one' );

      } else {

        get_template_part( 'template-part/forms/form', 'vychet-two' );

      }

      $content = ob_get_contents();
      ob_end_clean();

        $form_array[ 'result' ] = 'success';
        $form_array[ 'content' ] = $content;

    } else {

        $form_array[ 'result' ] = 'error';
        $form_array[ 'content' ] = $new_query;

    }

    echo json_encode( $form_array );

    wp_die();
}

