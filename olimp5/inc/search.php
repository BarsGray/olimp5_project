<?php
/**
 * OLIMP5 Search — строгий режим + морфология + выбор типов + приоритет сортировки
 */

remove_all_actions('pre_get_posts');
remove_all_filters('posts_pre_query');
remove_all_actions('parse_query');
remove_all_filters('posts_search');
remove_all_filters('posts_where');
remove_all_filters('posts_join');


function olimp_search_get_all_public_post_types() {
    $objs = get_post_types(array('public' => true), 'objects');

    unset($objs['attachment']);
    return $objs;
}

function olimp_search_get_all_relevant_post_types() {
    $curated_list = array(
        'post', 'page', 'service', 'programms', 
        'center-services', 'centres', 'promo', 
        'events', 'doctors', 'videos', 'bio-market'
    );
    
    $auto_detected_public = get_post_types(array('public' => true), 'objects');
    $auto_detected_ui = get_post_types(array('show_ui' => true), 'objects');
    $auto_detected_admin = get_post_types(array('show_in_admin_bar' => true), 'objects');
    
    $auto_detected = array_merge($auto_detected_public, $auto_detected_ui, $auto_detected_admin);
    
    $exclude_types = array('attachment', 'revision', 'nav_menu_item', 'custom_css', 'customize_changeset', 'oembed_cache', 'user_request');
    foreach ($exclude_types as $exclude) {
        unset($auto_detected[$exclude]);
    }
    
    $all_types = array();
    foreach ($auto_detected as $name => $obj) {
        $all_types[$name] = $obj;
    }
    
    foreach ($curated_list as $type_name) {
        if (!isset($all_types[$type_name])) {
            $type_obj = get_post_type_object($type_name);
            if ($type_obj) {
                $all_types[$type_name] = $type_obj;
            }
        }
    }
    
    $sorted_types = array();
    foreach ($curated_list as $type_name) {
        if (isset($all_types[$type_name])) {
            $sorted_types[$type_name] = $all_types[$type_name];
        }
    }
    foreach ($all_types as $name => $obj) {
        if (!in_array($name, $curated_list, true)) {
            $sorted_types[$name] = $obj;
        }
    }
    return $sorted_types;
}


function olimp_search_get_labels_map($all_types_objs) {
    $base_labels = array(
        'post'            => 'Записи (блог)',
        'page'            => 'Страницы',
        'service'         => 'Услуги',
        'programms'       => 'Программы',
        'center-services' => 'Услуги центров',
        'centres'         => 'Центры',
        'promo'           => 'Промо-акции',
        'events'          => 'События',
        'doctors'         => 'Врачи',
        'videos'          => 'Видео',
        'bio-market'      => 'Био-маркет',
        'olimp_term_result' => 'Направления (термины)'
    );
    $labels_map = array();
    foreach ($all_types_objs as $name => $obj) {
        if (isset($base_labels[$name])) {
            $labels_map[$name] = $base_labels[$name];
        } else {
            $labels_map[$name] = $obj->labels->singular_name ?: 
                               $obj->labels->name ?: 
                               ucfirst(str_replace(array('-', '_'), ' ', $name));
        }
    }
    $labels_map['olimp_term_result'] = $base_labels['olimp_term_result'];
    return $labels_map;
}
function olimp_search_default_post_types() {
    $defaults = array(
        'post',
        'page', 
        'service',
        'programms',
        'center-services',
        'centres', 
        'promo',
        'events',
        'doctors',
        'videos',
        'bio-market'
    );
    $available = olimp_search_get_all_public_post_types();
    return array_values(array_intersect($defaults, array_keys($available)));
}
function olimp_search_get_enabled_post_types() {
    $enabled = get_option('olimp_search_post_types');

    $available = array_keys(olimp_search_get_all_relevant_post_types());

    $defaults = array_values(array_intersect(olimp_search_default_post_types(), $available));

    if (!is_array($enabled) || empty($enabled)) {
        $enabled = $defaults;
    } else {
        $enabled = array_values(array_intersect($enabled, $available));

        if (empty($enabled)) {
            $enabled = $defaults;
        }
    }

    return $enabled;
}
function olimp_search_get_post_type_priority() {
    $priority = get_option('olimp_search_post_type_priority');
    $enabled  = olimp_search_get_enabled_post_types();
    if (!is_array($priority)) $priority = [];

    $allowed = array_merge($enabled, ['olimp_term_result']);

    $priority = array_values(array_intersect($priority, $allowed));

    foreach ($allowed as $pt) {
        if (!in_array($pt, $priority, true)) $priority[] = $pt;
    }

    return $priority;
}

function olimp_search_priority_map() {
    $order = olimp_search_get_post_type_priority();
    $map = array();
    foreach ($order as $i => $pt) {
        $map[$pt] = $i;
    }
    return $map;
}

function olimp_norm_hyphens_spaces($s) {
    if ($s === '' || $s === null) return $s;
    $s = strtr($s, array(
        "\xE2\x80\x93" => '-',
        "\xE2\x80\x94" => '-',
        "\xE2\x88\x92" => '-',
        "\xE2\x80\x90" => '-',
        "\xE2\x80\x91" => '-',
        "\xE2\x80\x95" => '-',
        "—" => '-', "–" => '-', "−" => '-', "-" => '-', "‒" => '-',
    ));
    $s = preg_replace('/[\x{00A0}\x{2009}\x{202F}]/u', ' ', $s);

    $s = preg_replace('/\s+/u', ' ', $s);
    return trim($s);
}


function olimp_get_doctor_position_keys() {
    $defaults = array('_rb_doc_occup','position','job_title','doctor_position','doljnost','dolzhnost','spec','speciality','specialty','acf_doctor_position','role','rank','title');
    $opt = get_option('olimp_doctor_position_keys');
    if (!is_array($opt) || empty(array_filter($opt))) {
        return $defaults;
    }
    $opt = array_map(function($x){ return trim(sanitize_key($x)); }, $opt);
    $opt = array_values(array_filter(array_unique($opt)));
    return !empty($opt) ? $opt : $defaults;
}


function olimp_search_admin_menu() {
    add_menu_page('Настройки поиска', 'Поиск', 'manage_options', 'olimp-search', 'olimp_search_admin_page', 'dashicons-search', 30);
    add_submenu_page('olimp-search', 'Настройки', 'Настройки', 'manage_options', 'olimp-search', 'olimp_search_admin_page');
    add_submenu_page('olimp-search', 'Синонимы', 'Синонимы', 'manage_options', 'olimp-search-synonyms', 'olimp_search_synonyms_page');
    add_submenu_page('olimp-search', 'Лог поиска', 'Лог поиска', 'manage_options', 'olimp-search-log', 'olimp_search_log_page');
}
add_action('admin_menu', 'olimp_search_admin_menu');


function olimp_search_admin_page() {
    // Сохранение настроек
    if (isset($_POST['save_settings'])) {

        update_option('olimp_search_debug', isset($_POST['debug_enabled']) ? 1 : 0);
        update_option('olimp_search_logging', isset($_POST['logging_enabled']) ? 1 : 0);
        update_option('olimp_search_transliteration', isset($_POST['transliteration_enabled']) ? 1 : 0);
        update_option('olimp_search_fuzzy', isset($_POST['fuzzy_enabled']) ? 1 : 0);
        update_option('olimp_search_synonyms_enabled', isset($_POST['synonyms_enabled']) ? 1 : 0);
        update_option('olimp_search_taxonomy_search', isset($_POST['taxonomy_search_enabled']) ? 1 : 0);
        update_option('olimp_search_require_exact', isset($_POST['require_exact']) ? 1 : 0);

        $all_available = array_keys(olimp_search_get_all_relevant_post_types());
        $enabled = isset($_POST['enabled_post_types']) && is_array($_POST['enabled_post_types'])
            ? array_values(array_intersect(array_map('sanitize_text_field', $_POST['enabled_post_types']), $all_available))
            : array();
        if (empty($enabled)) {
            $enabled = olimp_search_default_post_types();
        }
        update_option('olimp_search_post_types', $enabled);

$priority_raw = isset($_POST['post_type_priority']) ? sanitize_text_field($_POST['post_type_priority']) : '';
$priority_in  = array_values(array_filter(array_map('trim', explode(',', $priority_raw))));

$allowed = array_merge($enabled, ['olimp_term_result']);

$priority = array_values(array_intersect($priority_in, $allowed));

foreach ($allowed as $pt) {
    if (!in_array($pt, $priority, true)) $priority[] = $pt;
}

update_option('olimp_search_post_type_priority', $priority);

		
        $pos_raw = isset($_POST['doctor_position_keys']) ? sanitize_text_field($_POST['doctor_position_keys']) : '';
        update_option('olimp_doctor_position_keys_raw', $pos_raw);
        $pos_list = array_values(array_filter(array_map(function($x){ return trim(sanitize_key($x)); }, explode(',', $pos_raw))));
        if (!empty($pos_list)) {
            update_option('olimp_doctor_position_keys', $pos_list);
        }

        echo '<div class="notice notice-success"><p>Настройки сохранены!</p></div>';
    }

    if (isset($_POST['clear_logs'])) {
        delete_option('olimp_search_logs');
        echo '<div class="notice notice-success"><p>Логи очищены!</p></div>';
    }

    $debug_enabled           = get_option('olimp_search_debug', 0);
    $logging_enabled         = get_option('olimp_search_logging', 1);
    $transliteration_enabled = get_option('olimp_search_transliteration', 1);
    $fuzzy_enabled           = get_option('olimp_search_fuzzy', 1);
    $synonyms_enabled        = get_option('olimp_search_synonyms_enabled', 1);
    $taxonomy_search_enabled = get_option('olimp_search_taxonomy_search', 1);
    $require_exact           = get_option('olimp_search_require_exact', 1);

    $all_types_objs = olimp_search_get_all_relevant_post_types();
    $enabled_types  = olimp_search_get_enabled_post_types();
    $priority_list  = olimp_search_get_post_type_priority();
    $labels_map     = olimp_search_get_labels_map($all_types_objs);

    $curated_list = array(
        'post', 'page', 'service', 'programms', 
        'center-services', 'centres', 'promo', 
        'events', 'doctors', 'videos', 'bio-market'
    );

    $logs = get_option('olimp_search_logs', array());
    $total_searches = count($logs);
    $unique_queries = count(array_unique(array_column($logs, 'query')));

    ?>
   <div class="wrap">
        <h1>Настройки поиска OLIMP5</h1>
        
        <form method="post">
            <table class="form-table">
                <tr>
                    <th>Режим отладки</th>
                    <td>
                        <label>
                            <input type="checkbox" name="debug_enabled" value="1" <?php checked($debug_enabled, 1); ?>> 
                            Показывать debug при ?debug=1
                        </label>
                    </td>
                </tr>
                
                <tr>
                    <th>Логирование</th>
                    <td>
                        <label>
                            <input type="checkbox" name="logging_enabled" value="1" <?php checked($logging_enabled, 1); ?>> 
                            Записывать поисковые запросы
                        </label>
                    </td>
                </tr>
                
                <tr>
                    <th>Транслитерация</th>
                    <td>
                        <label>
                            <input type="checkbox" name="transliteration_enabled" value="1" <?php checked($transliteration_enabled, 1); ?>> 
                            Автокоррекция раскладки EN↔RU
                        </label>
                    </td>
                </tr>
                
                <tr>
                    <th>Исправление опечаток</th>
                    <td>
                        <label>
                            <input type="checkbox" name="fuzzy_enabled" value="1" <?php checked($fuzzy_enabled, 1); ?>> 
                            Лёгкие автокоррекции
                        </label>
                    </td>
                </tr>
                
                <tr>
                    <th>Синонимы</th>
                    <td>
                        <label>
                            <input type="checkbox" name="synonyms_enabled" value="1" <?php checked($synonyms_enabled, 1); ?>> 
                            Использовать синонимы
                        </label>
                    </td>
                </tr>
                
                <tr>
                    <th>Таксономии</th>
                    <td>
                        <label>
                            <input type="checkbox" name="taxonomy_search_enabled" value="1" <?php checked($taxonomy_search_enabled, 1); ?>> 
                            Искать в категориях/тегах
                        </label>
                    </td>
                </tr>
                
                <tr>
                    <th>Точность</th>
                    <td>
                        <label>
                            <input type="checkbox" name="require_exact" value="1" <?php checked($require_exact, 1); ?>> 
                            Требовать точное совпадение (морфологическая основа)
                        </label>
                        <p class="description">В строгом режиме пост должен содержать исходную фразу или её морфологическую основу.</p>
                    </td>
                </tr>

                <tr>
                  <th>Поля «Должность» для врачей</th>
                  <td>
                    <?php
                      $pos_keys = olimp_get_doctor_position_keys();
                      $pos_keys_raw = get_option('olimp_doctor_position_keys_raw');
                      if (!$pos_keys_raw) { $pos_keys_raw = implode(',', $pos_keys); }
                    ?>
                    <input type="text" name="doctor_position_keys" value="<?php echo esc_attr($pos_keys_raw); ?>" style="width:520px;">
                    <p class="description">
                      Список meta_key через запятую. По ним будет искаться должность в CPT <code>doctors</code>.
                      Пример: <code>_rb_doc_occup, position, job_title</code>
                    </p>
                    <p>
                      <a href="<?php echo esc_url( add_query_arg('olimp_doctors_debug','1', admin_url('admin.php?page=olimp-search')) ); ?>" class="button">Сканировать мета-поля врачей</a>
                      <small style="opacity:.7">покажет самые частые meta_key и примеры значений</small>
                    </p>
                  </td>
                </tr>


                <tr>
                    <th>Типы записей для поиска</th>
                    <td>
                        <fieldset>
                            <?php foreach ($all_types_objs as $name => $obj): ?>
                                <?php 
                                $is_curated = in_array($name, $curated_list, true);
                                $marker = $is_curated ? '⭐' : '🔍';
                                ?>
                                <label style="display:inline-block;margin:4px 12px 4px 0;">
                                    <input type="checkbox" name="enabled_post_types[]" value="<?php echo esc_attr($name); ?>"
                                        <?php checked(in_array($name, $enabled_types, true)); ?>>
                                    <span style="opacity:0.7;"><?php echo $marker; ?></span>
                                    <?php echo esc_html($labels_map[$name]); ?> 
                                    <code style="opacity:.5"><?php echo esc_html($name); ?></code>
                                </label>
                            <?php endforeach; ?>
                        </fieldset>
                        <p class="description">
                            ⭐ - приоритетные типы для поиска, 🔍 - автообнаруженные типы.
                            <br>Отметьте, какие типы участвуют в поиске.
                        </p>
                    </td>
                </tr>

                <tr>
                    <th>Приоритет типов (перетащите)</th>
                    <td>
                        <ul id="post-type-priority" class="widefat" style="max-width:600px;padding:8px 12px;background:#fff;border:1px solid #ccd0d4;border-radius:6px;">
                            <?php foreach ($priority_list as $pt): ?>
                                <?php
                                if ($pt !== 'olimp_term_result' && !in_array($pt, $enabled_types, true)) continue;
                                $is_curated = in_array($pt, $curated_list, true);
                                $marker = ($pt === 'olimp_term_result') ? '🏷️' : ($is_curated ? '⭐' : '🔍');
                                ?>
                                <li class="pt-item" data-pt="<?php echo esc_attr($pt); ?>"
                                    style="display:flex;align-items:center;gap:10px;padding:8px 10px;border:1px solid #e2e4e7;border-radius:4px;margin:6px 0;background:#f8fafc;cursor:move;">
                                    <span class="dashicons dashicons-move"></span>
                                    <span style="opacity:0.7;"><?php echo $marker; ?></span>
                                    <strong><?php echo esc_html($labels_map[$pt] ?? $pt); ?></strong>
                                    <code style="opacity:.7;margin-left:auto;"><?php echo esc_html($pt); ?></code>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <input type="hidden" name="post_type_priority" id="post_type_priority_input" value="<?php echo esc_attr(implode(',', $priority_list)); ?>">
                        <p class="description">
                            Выше в списке — выше в результатах. 
                            ⭐ - приоритетные, 🔍 - автообнаруженные, 🏷️ - виртуальные термины.
                        </p>
                    </td>
                </tr>
            </table>

            <p class="submit">
                <button class="button-primary" name="save_settings" value="1">Сохранить</button>
                <button class="button" name="clear_logs" value="1" onclick="return confirm('Очистить все логи?')">Очистить логи</button>
            </p>
        </form>

        <hr>
        <h2>Статистика поиска</h2>
        <p><strong>Всего запросов:</strong> <?php echo intval($total_searches); ?></p>
        <p><strong>Уникальных запросов:</strong> <?php echo intval($unique_queries); ?></p>
        
        <?php if (get_option('olimp_search_debug', 0)): ?>
            <hr>
            <h2>Отладочная информация</h2>
            <?php
            $auto_detected = array_keys(get_post_types(array('public' => true), 'names'));
            $auto_detected = array_diff($auto_detected, array('attachment'));
            $auto_only = array_diff($auto_detected, $curated_list);
            $curated_only = array_diff($curated_list, $auto_detected);
            $both = array_intersect($auto_detected, $curated_list);
            ?>
            <div style="background:#f0f0f1;padding:15px;border-radius:4px;margin:10px 0;">
                <p><strong>📋 В обоих списках:</strong> <?php echo implode(', ', $both); ?></p>
                <p><strong>🔍 Только автообнаружение:</strong> <?php echo implode(', ', $auto_only); ?></p>
                <p><strong>⭐ Только кураторский:</strong> <?php echo implode(', ', $curated_only); ?></p>
                <p><strong>Всего типов:</strong> <?php echo count($all_types_objs); ?></p>
            </div>
        <?php endif; ?>

        <?php if ( current_user_can('manage_options') && isset($_GET['olimp_doctors_debug']) && $_GET['olimp_doctors_debug']=='1' ): ?>
          <hr>
          <h2>Диагностика мета-полей врачей</h2>
          <div style="background:#f8f9fa;padding:15px;border:1px solid #e2e4e7;border-radius:6px;">
            <?php
              $sample = get_posts(array(
                'post_type' => 'doctors',
                'post_status' => 'publish',
                'numberposts' => 30,
                'orderby' => 'date',
                'order' => 'DESC'
              ));
              if ($sample) {
                  $freq = array(); $examples = array();
                  foreach ($sample as $p) {
                      $meta = get_post_meta($p->ID);
                      foreach ($meta as $k=>$vals) {
                          $v = is_array($vals) ? reset($vals) : $vals;
                          if (is_array($v)) $v = implode(', ', array_slice(array_filter($v), 0, 2));
                          $vv = trim(wp_strip_all_tags((string)$v));
                          if ($vv === '' || mb_strlen($vv,'UTF-8') < 2) continue;
                          if (!isset($freq[$k])) { $freq[$k]=0; $examples[$k]=$vv; }
                          $freq[$k]++;
                      }
                  }
                  arsort($freq);
                  echo '<p><strong>Найдено '.count($freq).' уникальных meta_key</strong>. Ниже — топ 30 самых частых с примером значения:</p>';
                  echo '<table class="widefat striped" style="max-width:900px"><thead><tr><th>meta_key</th><th>частота</th><th>пример</th></tr></thead><tbody>';
                  $i=0; foreach ($freq as $k=>$n) {
                      echo '<tr><td><code>'.esc_html($k).'</code></td><td>'.intval($n).'</td><td>'.esc_html($examples[$k]).'</td></tr>';
                      if (++$i>=30) break;
                  }
                  echo '</tbody></table>';
              } else {
                  echo '<p>Не нашёл опубликованных записей <code>doctors</code>.</p>';
              }
            ?>
          </div>
        <?php endif; ?>
    </div>

    <script>
    jQuery(document).ready(function($) {
        // if (typeof $.fn.sortable === 'undefined') {
        //     console.warn('jQuery UI Sortable not available. Loading from CDN...');
        //     $('<link>').attr('rel','stylesheet').attr('href','https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css').appendTo('head');
        //     $.getScript('https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js', function() {
        //         initSortable();
        //     });
        // } else {
        //     initSortable();
        // }
        function initSortable() {
            $('#post-type-priority').sortable({
                handle: '.dashicons-move',
                placeholder: 'ui-state-highlight',
                cursor: 'move',
                tolerance: 'pointer',
                update: function() { updatePriorityInput(); }
            }).disableSelection();
            $('<style>').prop('type','text/css').html(`
                .ui-state-highlight{height:50px;background-color:#e6f3ff;border:2px dashed #007cba;margin:6px 0;border-radius:4px;}
                .pt-item:hover{background-color:#e6f3ff!important;border-color:#007cba!important;}
                .pt-item .dashicons-move{color:#666;cursor:move;}
                .pt-item .dashicons-move:hover{color:#007cba;}
            `).appendTo('head');
        }
        function updatePriorityInput() {
            var order = [];
            $('#post-type-priority .pt-item').each(function() {
                var pt = $(this).data('pt');
                if (pt) order.push(pt);
            });
            $('#post_type_priority_input').val(order.join(','));
        }
        $('input[name="enabled_post_types[]"]').on('change', function() {
            var pt = $(this).val();
            var $list = $('#post-type-priority');
            var $existingItem = $list.find('.pt-item[data-pt="' + pt + '"]');
            var exists = $existingItem.length > 0;

            if ($(this).is(':checked')) {
                if (!exists) {
                    var $label = $(this).closest('label');
                    var labelText = $label.text().trim();
                    labelText = labelText.replace(/^[⭐🔍]\s*/, '').replace(/\s+\w+\s*$/, '').trim();
                    var curated_types = ['post','page','service','programms','center-services','centres','promo','events','doctors','videos','bio-market'];
                    var marker = curated_types.includes(pt) ? '⭐' : '🔍';
                    var $newItem = $('<li class="pt-item" data-pt="' + pt + '" style="display:flex;align-items:center;gap:10px;padding:8px 10px;border:1px solid #e2e4e7;border-radius:4px;margin:6px 0;background:#f8fafc;cursor:move;">' +
                        '<span class="dashicons dashicons-move"></span>' +
                        '<span style="opacity:0.7;">' + marker + '</span>' +
                        '<strong>' + labelText + '</strong>' +
                        '<code style="opacity:.7;margin-left:auto;">' + pt + '</code>' +
                        '</li>');
                    $list.append($newItem);
                    if ($list.hasClass('ui-sortable')) $list.sortable('refresh');
                }
            } else {
                if (exists) $existingItem.remove();
            }
            updatePriorityInput();
        });
        updatePriorityInput();
        $('form').on('submit', function() {
            $(this).find('button[type="submit"]').prop('disabled', true).text('Сохранение...');
        });
    });
    </script>
    <?php
}

function olimp_search_synonyms_page() {
    if (isset($_POST['save_synonyms'])) {
        $synonyms_data = array();
        if (isset($_POST['synonym_groups'])) {
            foreach ($_POST['synonym_groups'] as $group) {
                $main = sanitize_text_field($group['main'] ?? '');
                $syns = sanitize_textarea_field($group['synonyms'] ?? '');
                $enabled = !empty($group['enabled']) ? 1 : 0;
                if ($main !== '') {
                    $synonyms_data[] = array('main'=>$main, 'synonyms'=>$syns, 'enabled'=>$enabled);
                }
            }
        }
        update_option('olimp_search_custom_synonyms', $synonyms_data);
        echo '<div class="notice notice-success"><p>Синонимы сохранены!</p></div>';
    }

    $custom_synonyms = get_option('olimp_search_custom_synonyms', array());
    ?>
    <div class="wrap">
        <h1>Синонимы поиска</h1>
        <form method="post">
            <div id="synonym-groups">
                <?php if (empty($custom_synonyms)): ?>
                    <div class="synonym-group" style="border:1px solid #ddd;padding:15px;margin-bottom:15px;">
                        <h3>Группа #1</h3>
                        <table class="form-table">
                            <tr><th>Основное слово</th><td><input type="text" name="synonym_groups[0][main]" placeholder="реабилитация" style="width:300px;"></td></tr>
                            <tr><th>Синонимы</th><td><textarea name="synonym_groups[0][synonyms]" rows="3" style="width:500px" placeholder="восстановление, физиотерапия, лфк"></textarea></td></tr>
                            <tr><th>Статус</th><td><label><input type="checkbox" name="synonym_groups[0][enabled]" value="1" checked> Активно</label></td></tr>
                        </table>
                        <button type="button" class="button remove-group">Удалить</button>
                    </div>
                <?php else: ?>
                    <?php foreach ($custom_synonyms as $i => $group): ?>
                        <div class="synonym-group" style="border:1px solid #ddd;padding:15px;margin-bottom:15px;">
                            <h3>Группа #<?php echo $i+1; ?></h3>
                            <table class="form-table">
                                <tr><th>Основное слово</th><td><input type="text" name="synonym_groups[<?php echo $i; ?>][main]" value="<?php echo esc_attr($group['main']); ?>" style="width:300px;"></td></tr>
                                <tr><th>Синонимы</th><td><textarea name="synonym_groups[<?php echo $i; ?>][synonyms]" rows="3" style="width:500px;"><?php echo esc_textarea($group['synonyms']); ?></textarea></td></tr>
                                <tr><th>Статус</th><td><label><input type="checkbox" name="synonym_groups[<?php echo $i; ?>][enabled]" value="1" <?php checked($group['enabled'],1); ?>> Активно</label></td></tr>
                            </table>
                            <button type="button" class="button remove-group">Удалить</button>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <p>
                <button type="button" id="add-group" class="button">Добавить группу</button>
                <button class="button-primary" name="save_synonyms" value="1">Сохранить</button>
            </p>
        </form>
    </div>
    <script>
        let groupIndex = <?php echo count($custom_synonyms); ?>;
        document.getElementById('add-group').addEventListener('click', function(){
            const c = document.getElementById('synonym-groups');
            const d = document.createElement('div');
            d.className='synonym-group';
            d.style='border:1px solid #ddd;padding:15px;margin-bottom:15px;';
            d.innerHTML = `
                <h3>Группа #${groupIndex+1}</h3>
                <table class="form-table">
                    <tr><th>Основное слово</th><td><input type="text" name="synonym_groups[${groupIndex}][main]" style="width:300px;"></td></tr>
                    <tr><th>Синонимы</th><td><textarea name="synonym_groups[${groupIndex}][synonyms]" rows="3" style="width:500px;"></textarea></td></tr>
                    <tr><th>Статус</th><td><label><input type="checkbox" name="synonym_groups[${groupIndex}][enabled]" value="1" checked> Активно</label></td></tr>
                </table>
                <button type="button" class="button remove-group">Удалить</button>`;
            c.appendChild(d);
            groupIndex++;
        });
        document.addEventListener('click', function(e){
            if (e.target.classList.contains('remove-group')) {
                if (confirm('Удалить эту группу?')) e.target.closest('.synonym-group').remove();
            }
        });
    </script>
    <?php
}

function olimp_search_log_page() {
    if (isset($_POST['clear_logs'])) {
        delete_option('olimp_search_logs');
        echo '<div class="notice notice-success"><p>Логи очищены!</p></div>';
    }

    $logs = get_option('olimp_search_logs', array());
    $per_page = 50;
    $current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
    $total_logs = count($logs);
    $total_pages = max(1, ceil($total_logs / $per_page));
    $offset = ($current_page - 1) * $per_page;
    $logs_page = array_slice(array_reverse($logs), $offset, $per_page);
    ?>
    <div class="wrap">
        <h1>Лог поисковых запросов</h1>
        <div class="tablenav top">
            <div class="alignleft actions">
                <form method="post" style="display:inline;">
                    <input type="submit" name="clear_logs" class="button" value="Очистить все логи" onclick="return confirm('Очистить все логи?')">
                </form>
            </div>
            <div class="tablenav-pages">
                <span class="displaying-num"><?php echo $total_logs; ?> записей</span>
                <?php if ($total_pages > 1): ?>
                    <span class="pagination-links">
                        <?php if ($current_page > 1): ?><a href="?page=olimp-search-log&paged=<?php echo $current_page-1; ?>" class="prev-page button">‹</a><?php endif; ?>
                        <span class="current-page"><?php echo $current_page; ?> из <?php echo $total_pages; ?></span>
                        <?php if ($current_page < $total_pages): ?><a href="?page=olimp-search-log&paged=<?php echo $current_page+1; ?>" class="next-page button">›</a><?php endif; ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!empty($logs_page)): ?>
            <table class="wp-list-table widefat fixed striped">
                <thead><tr><th style="width:150px;">Время</th><th>Запрос</th><th style="width:80px;">Результаты</th><th style="width:120px;">IP</th><th style="width:200px;">User Agent</th></tr></thead>
                <tbody>
                <?php foreach ($logs_page as $log): ?>
                    <tr>
                        <td><?php echo date('d.m.Y H:i:s', $log['time']); ?></td>
                        <td><strong><?php echo esc_html($log['query']); ?></strong><?php if (!empty($log['variants'])): ?><br><small style="color:#666">Варианты: <?php echo esc_html(implode(', ', array_slice($log['variants'],0,3))); ?></small><?php endif; ?></td>
                        <td><?php echo intval($log['results']); ?></td>
                        <td><?php echo esc_html($log['ip']); ?></td>
                        <td><small><?php echo esc_html(substr($log['user_agent'],0,50)); ?>...</small></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Логи поиска пусты.</p>
        <?php endif; ?>

        <div class="tablenav bottom">
            <div class="tablenav-pages">
                <?php if ($total_pages > 1): ?>
                    <span class="pagination-links">
                        <?php if ($current_page > 1): ?><a href="?page=olimp-search-log&paged=<?php echo $current_page-1; ?>" class="prev-page button">‹</a><?php endif; ?>
                        <span class="current-page"><?php echo $current_page; ?> из <?php echo $total_pages; ?></span>
                        <?php if ($current_page < $total_pages): ?><a href="?page=olimp-search-log&paged=<?php echo $current_page+1; ?>" class="next-page button">›</a><?php endif; ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
}

if (!function_exists('olimp_has_cyrillic')) {
    function olimp_has_cyrillic($s){ return (bool)preg_match('/\p{Cyrillic}/u', $s); }
}

if (!function_exists('ru_core_word')) {
function ru_core_word($w) {
    $w = mb_strtolower(trim($w), 'UTF-8');
    $w = preg_replace('/[^а-яё\-]/u', '', $w);
    if ($w === '') return '';
    $endings = array(
        'иями','ями','ями','ами','ями','ями','ями',
        'ины','ены','ёны','аны','оны',
        'иной','евой','овой','ной','иной','иной','иной',
        'ием','ем','ом',
        'ию','ью','ью','ую',
        'ии','ьи','ой','ей',
        'ия','ья','ая','яя','ея','иа',
        'ого','его','ому','ему','ому','ему','ою','ею',
        'ын','ен','ён',
        'ина','ына','ена','ёна',
        'иной','евой','овой',
        'ину','ыну','ену','ёну',
        'ине','ыне','ене','ёне',
        'ах','ях',
        'ов','ев','ёв',
        'ым','им',
        'ой','ей',
        'ою','ею',
        'а','я','е','и','о','у','ы','й','ь'
    );
    foreach ($endings as $suf) {
        $len = mb_strlen($suf,'UTF-8');
        if ($len >= 1 && mb_substr($w, -$len, null, 'UTF-8') === $suf) {
            $stem = mb_substr($w, 0, mb_strlen($w,'UTF-8') - $len, 'UTF-8');
            if (mb_strlen($stem,'UTF-8') >= 5) return $stem;
        }
    }
    return (mb_strlen($w,'UTF-8') >= 5) ? $w : $w;
}}
if (!function_exists('ru_core_phrase')) {
function ru_core_phrase($s) {
    $s = mb_strtolower($s,'UTF-8');
    $words = preg_split('/\s+/u', $s, -1, PREG_SPLIT_NO_EMPTY);
    $cores = array();
    foreach ($words as $w) {
        if (!olimp_has_cyrillic($w)) continue;
        $c = ru_core_word($w);
        if ($c !== '') $cores[] = $c;
    }
    return trim(implode(' ', array_unique($cores)));
}}

function final_transliterate($text) {
    if (!get_option('olimp_search_transliteration', 1)) return $text;
    $map = array(
        'q'=>'й','w'=>'ц','e'=>'у','r'=>'к','t'=>'е','y'=>'н','u'=>'г','i'=>'ш','o'=>'щ','p'=>'з','['=>'х',']'=>'ъ',
        'a'=>'ф','s'=>'ы','d'=>'в','f'=>'а','g'=>'п','h'=>'р','j'=>'о','k'=>'л','l'=>'д',';'=>'ж',"'“"=>"э","'"=>'э',
        'z'=>'я','x'=>'ч','c'=>'с','v'=>'м','b'=>'и','n'=>'т','m'=>'ь',','=>'б','.'=>'ю','/'=>'.',
        'Q'=>'Й','W'=>'Ц','E'=>'У','R'=>'К','T'=>'Е','Y'=>'Н','U'=>'Г','I'=>'Ш','O'=>'Щ','P'=>'З','{'=>'Х','}'=>'Ъ',
        'A'=>'Ф','S'=>'Ы','D'=>'В','F'=>'А','G'=>'П','H'=>'Р','J'=>'О','K'=>'Л','L'=>'Д',':' =>'Ж','"'=>'Э',
        'Z'=>'Я','X'=>'Ч','C'=>'С','V'=>'М','B'=>'И','N'=>'Т','M'=>'Ь','<' =>'Б','>' =>'Ю','?' =>',',
    );
    return strtr($text, $map);
}
function normalize_search_term($term) {
    $term = mb_strtolower($term, 'UTF-8');
    $term = trim($term);
    $term = preg_replace('/\s+/u', ' ', $term);
    return $term;
}
function olimp_has_latin($s) {
    return (bool) preg_match('/[A-Za-z]/u', $s);
}
function olimp_advanced_levenshtein($str1, $str2) {
    $len1 = mb_strlen($str1, 'UTF-8');
    $len2 = mb_strlen($str2, 'UTF-8');
    
    $keyboard_ru = array(
        'й' => array('ц', 'ф', 'я'),
        'ц' => array('й', 'у', 'ф', 'ы', 'ч'),
        'у' => array('ц', 'к', 'ы', 'в', 'а'),
        'к' => array('у', 'е', 'в', 'а', 'п'),
        'е' => array('к', 'н', 'а', 'п', 'р'),
        'н' => array('е', 'г', 'п', 'р', 'о'),
        'г' => array('н', 'ш', 'р', 'о', 'л'),
        'ш' => array('г', 'щ', 'о', 'л', 'д'),
        'щ' => array('ш', 'з', 'л', 'д', 'ж'),
        'з' => array('щ', 'х', 'д', 'ж', 'э'),
        'х' => array('з', 'ъ', 'ж', 'э'),
        'ф' => array('й', 'ц', 'я', 'ч', 'ы'),
        'ы' => array('ц', 'у', 'ф', 'в', 'ч', 'с'),
        'в' => array('у', 'к', 'ы', 'а', 'с', 'м'),
        'а' => array('к', 'е', 'в', 'п', 'м', 'и'),
        'п' => array('е', 'н', 'а', 'р', 'и', 'т'),
        'р' => array('н', 'г', 'п', 'о', 'т', 'ь'),
        'о' => array('г', 'ш', 'р', 'л', 'ь', 'б'),
        'л' => array('ш', 'щ', 'о', 'д', 'б', 'ю'),
        'д' => array('щ', 'з', 'л', 'ж', 'ю'),
        'ж' => array('з', 'х', 'д', 'э', 'ю'),
        'э' => array('х', 'ж'),
        'я' => array('й', 'ф', 'ч'),
        'ч' => array('ц', 'ф', 'ы', 'я', 'с'),
        'с' => array('ы', 'в', 'ч', 'м'),
        'м' => array('в', 'а', 'с', 'и'),
        'и' => array('а', 'п', 'м', 'т'),
        'т' => array('п', 'р', 'и', 'ь'),
        'ь' => array('р', 'о', 'т', 'б'),
        'б' => array('о', 'л', 'ь', 'ю'),
        'ю' => array('л', 'д', 'б', 'ж'),
    );
    
    $similar_letters = array(
        'а' => array('о'), 'о' => array('а'),
        'и' => array('е', 'ы'), 'е' => array('и', 'э'), 'ы' => array('и'),
        'б' => array('п'), 'п' => array('б'),
        'д' => array('т'), 'т' => array('д'),
        'г' => array('к'), 'к' => array('г'),
        'ж' => array('ш'), 'ш' => array('ж'),
        'з' => array('с'), 'с' => array('з'),
    );
    
    $matrix = array();
    
    for ($i = 0; $i <= $len1; $i++) {
        $matrix[$i][0] = $i;
    }
    
    for ($j = 0; $j <= $len2; $j++) {
        $matrix[0][$j] = $j;
    }
    
    for ($i = 1; $i <= $len1; $i++) {
        $char1 = mb_substr($str1, $i - 1, 1, 'UTF-8');
        
        for ($j = 1; $j <= $len2; $j++) {
            $char2 = mb_substr($str2, $j - 1, 1, 'UTF-8');
            
            if ($char1 === $char2) {
                $cost = 0;
            } else {
                $cost = 1;
                if (isset($keyboard_ru[$char1]) && in_array($char2, $keyboard_ru[$char1])) {
                    $cost = 0.5; 
                } elseif (isset($similar_letters[$char1]) && in_array($char2, $similar_letters[$char1])) {
                    $cost = 0.6; 
                }
            }
            
            $matrix[$i][$j] = min(
                $matrix[$i - 1][$j] + 1,      
                $matrix[$i][$j - 1] + 1,      
                $matrix[$i - 1][$j - 1] + $cost  
            );
            
            if ($i > 1 && $j > 1) {
                $prev_char1 = mb_substr($str1, $i - 2, 1, 'UTF-8');
                $prev_char2 = mb_substr($str2, $j - 2, 1, 'UTF-8');
                
                if ($char1 === $prev_char2 && $prev_char1 === $char2) {
                    $matrix[$i][$j] = min($matrix[$i][$j], $matrix[$i - 2][$j - 2] + 0.7);
                }
            }
        }
    }
    
    return $matrix[$len1][$len2];
}

function olimp_find_similar_in_db($word, $max_results = 5) {
    global $wpdb;
    
    $word_len = mb_strlen($word, 'UTF-8');
    if ($word_len < 3) return array();
    
    $min_len = max(3, $word_len - 2);
    $max_len = $word_len + 2;
    
    $words_from_db = $wpdb->get_col($wpdb->prepare("
        SELECT DISTINCT SUBSTRING_INDEX(SUBSTRING_INDEX(post_title, ' ', numbers.n), ' ', -1) as word
        FROM {$wpdb->posts}
        CROSS JOIN (
            SELECT 1 n UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5
            UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10
        ) numbers
        WHERE post_status = 'publish'
        AND CHAR_LENGTH(SUBSTRING_INDEX(SUBSTRING_INDEX(post_title, ' ', numbers.n), ' ', -1)) BETWEEN %d AND %d
        AND SUBSTRING_INDEX(SUBSTRING_INDEX(post_title, ' ', numbers.n), ' ', -1) REGEXP '^[а-яА-ЯёЁ]+$'
        LIMIT 500
    ", $min_len, $max_len));
    
    $meta_words = $wpdb->get_col($wpdb->prepare("
        SELECT DISTINCT SUBSTRING_INDEX(SUBSTRING_INDEX(meta_value, ' ', numbers.n), ' ', -1) as word
        FROM {$wpdb->postmeta}
        CROSS JOIN (
            SELECT 1 n UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5
        ) numbers
        WHERE meta_key IN ('service_keywords', 'service_synonyms', 'search_terms', '_rb_doc_occup', 'position')
        AND CHAR_LENGTH(SUBSTRING_INDEX(SUBSTRING_INDEX(meta_value, ' ', numbers.n), ' ', -1)) BETWEEN %d AND %d
        AND SUBSTRING_INDEX(SUBSTRING_INDEX(meta_value, ' ', numbers.n), ' ', -1) REGEXP '^[а-яА-ЯёЁ]+$'
        LIMIT 300
    ", $min_len, $max_len));
    
    $all_words = array_unique(array_merge($words_from_db, $meta_words));
    
    $candidates = array();
    $word_lower = mb_strtolower($word, 'UTF-8');
    
    foreach ($all_words as $db_word) {
        $db_word_lower = mb_strtolower($db_word, 'UTF-8');
        
        if (mb_strlen($db_word_lower, 'UTF-8') < 3) continue;
        
        if ($db_word_lower === $word_lower) continue;
        
        $first1 = mb_substr($word_lower, 0, 1, 'UTF-8');
        $first2 = mb_substr($db_word_lower, 0, 1, 'UTF-8');
        
        if ($word_len > 5 && $first1 !== $first2) {
            $keyboard_dist = olimp_advanced_levenshtein($first1, $first2);
            if ($keyboard_dist > 0.5) continue;
        }
        
        $distance = olimp_advanced_levenshtein($word_lower, $db_word_lower);
        
        $threshold = 1.5;
        if ($word_len >= 8) $threshold = 2.5;
        if ($word_len >= 12) $threshold = 3;
        
        if ($distance <= $threshold) {
            $candidates[] = array(
                'word' => $db_word_lower,
                'distance' => $distance,
                'score' => 1 / ($distance + 0.1) 
            );
        }
    }
    
    usort($candidates, function($a, $b) {
        return $b['score'] <=> $a['score'];
    });
    
    return array_slice(array_column($candidates, 'word'), 0, $max_results);
}

function final_fuzzy_search($term) {
    if (!get_option('olimp_search_fuzzy', 1)) return $term;
    
    static $cache = array();
    $cache_key = mb_strtolower($term, 'UTF-8');
    if (isset($cache[$cache_key])) return $cache[$cache_key];
    
    $original = $term;
    
    $corrections = array(
        'реаабилитация' => 'реабилитация',
        'реабилитацыя'  => 'реабилитация',
        'реабитация'    => 'реабилитация',
        'реобилитация'  => 'реабилитация',
        'реабилитацея'  => 'реабилитация',
        'невралог'      => 'невролог',
        'неврол'        => 'невролог',
        'невралогия'    => 'неврология',
        'гинекологг'    => 'гинеколог',
        'генеколог'     => 'гинеколог',
        'гинеколог'     => 'гинеколог',
        'кардилог'      => 'кардиолог',
        'кардеолог'     => 'кардиолог',
        'кардиолк'      => 'кардиолог',
        'обследованее'  => 'обследование',
        'обсле'         => 'обследование',
        'диогностика'   => 'диагностика',
        'диагностека'   => 'диагностика',
        'узии'          => 'узи',
        'консултация'   => 'консультация',
        'консультацыя'  => 'консультация',
    );
    
    $low = mb_strtolower($term, 'UTF-8');
    
    if (isset($corrections[$low])) {
        $cache[$cache_key] = $corrections[$low];
        return $corrections[$low];
    }
    
    $words = preg_split('/[\s\-]+/u', $low, -1, PREG_SPLIT_NO_EMPTY);
    $corrected_words = array();
    $was_corrected = false;
    
    foreach ($words as $word) {
        if (mb_strlen($word, 'UTF-8') < 3) {
            $corrected_words[] = $word;
            continue;
        }
        
        if (isset($corrections[$word])) {
            $corrected_words[] = $corrections[$word];
            $was_corrected = true;
            continue;
        }
        
        $static_dict = array(
            'реабилитация', 'невролог', 'неврология', 'невропатолог',
            'гинеколог', 'гинекология', 'кардиолог', 'кардиология',
            'терапевт', 'терапия', 'педиатр', 'педиатрия',
            'офтальмолог', 'офтальмология', 'эндокринолог', 'эндокринология',
            'уролог', 'урология', 'дерматолог', 'дерматология',
            'отоларинголог', 'лор', 'хирург', 'хирургия',
            'ортопед', 'травматолог', 'онколог', 'онкология',
            'психиатр', 'психиатрия', 'нарколог', 'наркология',
            'стоматолог', 'стоматология', 'анестезиолог',
            'рентгенолог', 'радиолог', 'сонолог',
            'обследование', 'диагностика', 'консультация', 'прием',
            'лечение', 'физиотерапия', 'массаж', 'анализы',
            'узи', 'мрт', 'томография', 'рентген', 'флюорография',
            'кардиограмма', 'энцефалограмма', 'эхокардиография'
        );
        
        $custom_synonyms = get_option('olimp_search_custom_synonyms', array());
        foreach ($custom_synonyms as $group) {
            if (empty($group['enabled'])) continue;
            if (!empty($group['main'])) $static_dict[] = mb_strtolower($group['main'], 'UTF-8');
            if (!empty($group['synonyms'])) {
                $syns = array_map('trim', explode(',', $group['synonyms']));
                foreach ($syns as $s) {
                    if ($s) $static_dict[] = mb_strtolower($s, 'UTF-8');
                }
            }
        }
        
        $static_dict = array_unique($static_dict);
        
        $best_match = $word;
        $min_distance = PHP_INT_MAX;
        
        foreach ($static_dict as $dict_word) {
            $distance = olimp_advanced_levenshtein($word, $dict_word);
            
            $threshold = 1.5;
            $word_len = mb_strlen($word, 'UTF-8');
            if ($word_len >= 8) $threshold = 2.5;
            if ($word_len >= 12) $threshold = 3;
            
            if ($distance > 0 && $distance <= $threshold && $distance < $min_distance) {
                $min_distance = $distance;
                $best_match = $dict_word;
                $was_corrected = true;
            }
        }
        
        if ($best_match === $word && mb_strlen($word, 'UTF-8') >= 4) {
            $similar = olimp_find_similar_in_db($word, 1);
            if (!empty($similar)) {
                $best_match = $similar[0];
                $was_corrected = true;
            }
        }
        
        $corrected_words[] = $best_match;
    }
    
    $result = $was_corrected ? implode(' ', $corrected_words) : $original;
    $cache[$cache_key] = $result;
    
    return $result;
}
function get_additional_synonyms($term) {
    $map = array(
        'реабилитация' => array('восстановление', 'физиотерапия', 'лфк'),
        'невролог'     => array('неврология', 'невропатолог', 'нейролог', 'прием невролога', 'консультация невролога'),
        'гинеколог'    => array('гинекология', 'женский врач'),
        'кардиолог'    => array('кардиология', 'сердце'),
        'обследование' => array('чекап', 'диагностика', 'check-up'),
        'диагностика'  => array('узи', 'мрт', 'кт', 'обследование'),
        'прием'        => array('консультация', 'запись', 'визит'),
    );
    $low = mb_strtolower($term, 'UTF-8');
    $res = array();
    if (isset($map[$low])) $res = $map[$low];
    foreach ($map as $k=>$vals) {
        if (stripos($k, $low)!==false || stripos($low, $k)!==false) $res = array_merge($res, $vals);
    }
    return array_values(array_unique(array_filter($res)));
}
function get_custom_synonyms($term) {
    if (!get_option('olimp_search_synonyms_enabled', 1)) return array();
    $custom_synonyms = get_option('olimp_search_custom_synonyms', array());
    $result = array();
    $needle = mb_strtolower($term, 'UTF-8');
    foreach ($custom_synonyms as $group) {
        if (empty($group['enabled'])) continue;
        $main = mb_strtolower($group['main'] ?? '', 'UTF-8');
        $syns = array_map('trim', explode(',', mb_strtolower($group['synonyms'] ?? '', 'UTF-8')));
        if ($main !== '' && ($main === $needle || stripos($main, $needle)!==false || stripos($needle, $main)!==false)) {
            $result[] = $main;
            $result   = array_merge($result, $syns);
        }
        foreach ($syns as $s) {
            if ($s !== '' && ($s === $needle || stripos($s, $needle)!==false || stripos($needle, $s)!==false)) {
                $result[] = $main;
                $result   = array_merge($result, $syns);
                break;
            }
        }
    }
    $with_cores = array();
    foreach (array_unique(array_filter($result)) as $w) {
        $with_cores[] = $w;
        $core = ru_core_phrase($w);
        if ($core && $core !== $w) $with_cores[] = $core;
    }
    return array_values(array_unique(array_map('trim', $with_cores)));
}
function get_search_variants($original_term) {
    $variants = [];
    $variants[] = $original_term;
    
    $corrected = final_fuzzy_search($original_term);
    if ($corrected !== $original_term) {
        $variants[] = $corrected;
        
        $corrected_core = ru_core_phrase($corrected);
        if ($corrected_core && $corrected_core !== $corrected) {
            $variants[] = $corrected_core;
        }
        
        $variants[] = mb_strtolower($corrected, 'UTF-8');
        $variants[] = ucfirst(mb_strtolower($corrected, 'UTF-8'));
    }
    
    $t_raw = final_transliterate($original_term);
    if ($t_raw !== $original_term) {
        $variants[] = $t_raw;
        
        $t_corrected = final_fuzzy_search($t_raw);
        if ($t_corrected !== $t_raw) {
            $variants[] = $t_corrected;
        }
    }
    
    $norm = normalize_search_term($original_term);
    if ($norm !== $original_term) $variants[] = $norm;
    
    $core_from_norm = ru_core_phrase($norm);
    if ($core_from_norm && $core_from_norm !== $norm) {
        $variants[] = $core_from_norm;
    }
    
    
    $variants = array_merge($variants, get_additional_synonyms($norm));
    $variants = array_merge($variants, get_custom_synonyms($norm));
    
    $synonym_variants = array();
    foreach (array_merge(get_additional_synonyms($norm), get_custom_synonyms($norm)) as $syn) {
        $syn_corrected = final_fuzzy_search($syn);
        if ($syn_corrected !== $syn) {
            $synonym_variants[] = $syn_corrected;
        }
    }
    $variants = array_merge($variants, $synonym_variants);
    
    $case_variants = [];
    foreach ($variants as $v) {
        $case_variants[] = $v;
        $case_variants[] = mb_strtolower($v, 'UTF-8');
        $case_variants[] = ucfirst(mb_strtolower($v, 'UTF-8'));
    }
    
    $case_variants = array_values(array_unique(array_filter($case_variants, function($v) {
        return trim($v) !== '' && mb_strlen(trim($v), 'UTF-8') >= 2;
    })));
    
    return $case_variants;
}
function log_search_query($query, $results, $variants = array()) {
    if (!get_option('olimp_search_logging', 1)) return;
    $logs = get_option('olimp_search_logs', array());
    $logs[] = array(
        'time'       => time(),
        'query'      => $query,
        'results'    => intval($results),
        'variants'   => $variants,
        'ip'         => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
    );
    if (count($logs) > 1000) $logs = array_slice($logs, -1000);
    update_option('olimp_search_logs', $logs);
}


function ru_simple_stem($word) {
    $w = mb_strtolower(trim($word), 'UTF-8');
    $w = preg_replace('/[^\p{L}\p{N}\-]+/u', '', $w);
    $endings = array(
        'иями','ями','ами','ьев','ёв','ов','ев','ей','ой','ый','ий','ью',
        'иях','ях','ах',
        'ием','ем','ом',
        'ию','ью','ю','у',
        'ии','ьи','ы','и',
        'ия','ья','ая','яя','ея','иа',
        'ым','им',
        'ина','ына','ена','ёна',
        'иной','евой','овой',
        'ину','ыну','ену','ёну',
        'ине','ыне','ене','ёне',
        'ою','ею',
        'а','я','е','и','о','у','ы','й','ь'
    );
    foreach ($endings as $suf) {
        $len = mb_strlen($suf, 'UTF-8');
        if ($len >= 1 && mb_substr($w, -$len, null, 'UTF-8') === $suf) {
            $stem = mb_substr($w, 0, mb_strlen($w,'UTF-8') - $len, 'UTF-8');
            if (mb_strlen($stem, 'UTF-8') >= 5) return $stem;
        }
    }
    return (mb_strlen($w,'UTF-8') >= 5) ? $w : $word;
}
function build_must_phrases($search_term, $search_variants) {
    $orig = normalize_search_term($search_term);
    $tr   = normalize_search_term(final_transliterate($search_term));
    $must = [];
    if ($orig !== '' && mb_strlen($orig,'UTF-8') >= 2) $must[] = $orig;
    if ($tr !== '' && $tr !== $orig && mb_strlen($tr,'UTF-8') >= 2) $must[] = $tr;
    if (olimp_has_cyrillic($orig)) {
        $core = ru_core_phrase($orig);
        if ($core && mb_strlen($core,'UTF-8') >= 4) $must[] = $core;
    }
    if (olimp_has_cyrillic($tr)) {
        $core_tr = ru_core_phrase($tr);
        if ($core_tr && mb_strlen($core_tr,'UTF-8') >= 4) $must[] = $core_tr;
    }
    $corr = final_fuzzy_search($orig);
    if ($corr && $corr !== $orig) {
        $must[] = $corr;
        if (olimp_has_cyrillic($corr)) {
            $corr_core = ru_core_phrase($corr);
            if ($corr_core && $corr_core !== $corr) $must[] = $corr_core;
        }
    }
    $syns = array_merge(get_custom_synonyms($orig), get_additional_synonyms($orig));
    foreach ($syns as $s) {
        if (!$s) continue;
        $s_norm = normalize_search_term($s);
        $must[] = $s_norm;
        if (olimp_has_cyrillic($s_norm)) {
            $s_core = ru_core_phrase($s_norm);
            if ($s_core && $s_core !== $s_norm) $must[] = $s_core;
        }
    }
    $must = array_values(array_unique(array_filter($must, function($t){
        return (mb_strlen($t,'UTF-8') >= 2);
    })));
    if (empty($must) && $orig !== '') $must = [$orig];
    return $must;
}

function final_enhanced_search($query) {
    if (is_admin() || !$query->is_search() || !$query->is_main_query()) return;

    $search_term = $query->get('s');
    if (empty($search_term)) return;

    $search_variants = get_search_variants($search_term);

    $enabled_types = olimp_search_get_enabled_post_types();
    $query->set('post_type', $enabled_types);
add_filter('posts_clauses', function($clauses, $wp_query) use ($search_term, $search_variants) {
    if (!$wp_query->is_search() || !$wp_query->is_main_query()) return $clauses;
    global $wpdb;

    $prio = olimp_search_priority_map(); // ['doctors'=>0,'service'=>1,...]
    $typeCase = "CASE {$wpdb->posts}.post_type ";
    foreach ($prio as $pt => $i) {
        $typeCase .= $wpdb->prepare("WHEN %s THEN %d ", $pt, $i);
    }
    $typeCase .= "ELSE 9999 END";

    $doctor_keys = array_map('esc_sql', olimp_get_doctor_position_keys());
    $doctor_keys_list = "'" . implode("','", $doctor_keys) . "'";

    $scoreParts = [];
    $orig = esc_sql(mb_strtolower(trim($search_term), 'UTF-8'));
    if ($orig !== '') {
        $scoreParts[] = "CASE WHEN LOWER({$wpdb->posts}.post_title) = '{$orig}' THEN 300 ELSE 0 END";
        $scoreParts[] = "CASE WHEN LOWER({$wpdb->posts}.post_title) LIKE '{$orig} %' THEN 200 ELSE 0 END";
        $scoreParts[] = "CASE WHEN LOWER({$wpdb->posts}.post_title) LIKE '% {$orig} %' THEN 140 ELSE 0 END";
        $scoreParts[] = "CASE WHEN LOWER({$wpdb->posts}.post_title) LIKE '%{$orig}%' THEN 120 ELSE 0 END";

        $scoreParts[] = "CASE WHEN LOWER({$wpdb->posts}.post_content) LIKE '%{$orig}%' THEN 60 ELSE 0 END";
        $scoreParts[] = "CASE WHEN LOWER({$wpdb->posts}.post_excerpt) LIKE '%{$orig}%' THEN 60 ELSE 0 END";

        if (!empty($doctor_keys)) {
            $scoreParts[] =
            "CASE WHEN {$wpdb->posts}.post_type='doctors' AND EXISTS (
                SELECT 1 FROM {$wpdb->postmeta} pm1
                WHERE pm1.post_id={$wpdb->posts}.ID
                  AND pm1.meta_key IN ({$doctor_keys_list})
                  AND LOWER(pm1.meta_value) LIKE '%{$orig}%'
            ) THEN 220 ELSE 0 END";
        }
    }

    foreach ($search_variants as $v) {
        $v = esc_sql(mb_strtolower(trim($v), 'UTF-8'));
        if ($v === '') continue;
        $scoreParts[] = "CASE WHEN LOWER({$wpdb->posts}.post_title) LIKE '%{$v}%' THEN 80 ELSE 0 END";
        $scoreParts[] = "CASE WHEN LOWER({$wpdb->posts}.post_content) LIKE '%{$v}%' THEN 30 ELSE 0 END";
        if (!empty($doctor_keys)) {
            $scoreParts[] =
            "CASE WHEN {$wpdb->posts}.post_type='doctors' AND EXISTS (
                SELECT 1 FROM {$wpdb->postmeta} pm2
                WHERE pm2.post_id={$wpdb->posts}.ID
                  AND pm2.meta_key IN ({$doctor_keys_list})
                  AND LOWER(pm2.meta_value) LIKE '%{$v}%'
            ) THEN 120 ELSE 0 END";
        }
    }

    $scoreExpr = '(' . implode(' + ', $scoreParts) . ')';
    $clauses['fields'] .= ", {$typeCase} AS olimp_type_prio, {$scoreExpr} AS olimp_relevance";

    $order = "olimp_type_prio ASC, olimp_relevance DESC, {$wpdb->posts}.post_date DESC, {$wpdb->posts}.ID DESC";

    if (!empty($clauses['orderby'])) {
        $clauses['orderby'] = $order;
    } else {
        $clauses['orderby'] = $order;
    }

    return $clauses;
}, 999, 2);

    $query->set('post_status', 'publish');

    add_filter('posts_search', function($search, $wp_query) use ($search_variants, $search_term) {
        if (!$wp_query->is_search() || !$wp_query->is_main_query()) return $search;
        global $wpdb;

        $require_exact = get_option('olimp_search_require_exact', 1);

$must_conditions = array();
if ($require_exact) {
    $must_phrases = build_must_phrases($search_term, $search_variants);
    if (!empty($must_phrases)) {
        $doctor_keys_for_must = olimp_get_doctor_position_keys();
        $doctor_keys_sql = array();
        foreach ($doctor_keys_for_must as $dk) {
            if ($dk === '') continue;
            $doctor_keys_sql[] = "'" . esc_sql($dk) . "'";
        }
        $doctor_keys_sql_str = !empty($doctor_keys_sql) ? implode(',', $doctor_keys_sql) : '';

        $must_or_chunks = array();

        foreach ($must_phrases as $m) {
            if ($m === '') continue;

            $m_base   = olimp_norm_hyphens_spaces($m);
            $m_hy     = str_replace('-', '-', $m_base);
            $m_spaced = preg_replace('/-+/u', ' ', $m_base);

            $patterns = array();
            foreach (array($m, $m_base, $m_hy, $m_spaced) as $pp) {
                $pp = trim($pp);
                if ($pp !== '' && !in_array($pp, $patterns, true)) $patterns[] = $pp;
            }

            foreach ($patterns as $pat) {
                $p = esc_sql($pat);

                $must_or_chunks[] = "({$wpdb->posts}.post_title   COLLATE utf8mb4_general_ci LIKE '%{$p}%')";
                $must_or_chunks[] = "({$wpdb->posts}.post_content COLLATE utf8mb4_general_ci LIKE '%{$p}%')";
                $must_or_chunks[] = "({$wpdb->posts}.post_excerpt COLLATE utf8mb4_general_ci LIKE '%{$p}%')";

                $must_or_chunks[] = "EXISTS (SELECT 1 FROM {$wpdb->postmeta} pm_m
                    WHERE pm_m.post_id = {$wpdb->posts}.ID
                      AND (
                           (pm_m.meta_key = 'service_keywords' AND pm_m.meta_value COLLATE utf8mb4_general_ci LIKE '%{$p}%')
                        OR (pm_m.meta_key = 'service_synonyms' AND pm_m.meta_value COLLATE utf8mb4_general_ci LIKE '%{$p}%')
                        OR (pm_m.meta_key = 'search_terms'    AND pm_m.meta_value COLLATE utf8mb4_general_ci LIKE '%{$p}%')
                        OR (pm_m.meta_key LIKE '%description%' AND pm_m.meta_value COLLATE utf8mb4_general_ci LIKE '%{$p}%')
                        OR (pm_m.meta_key LIKE '%content%'     AND pm_m.meta_value COLLATE utf8mb4_general_ci LIKE '%{$p}%')
                        OR (pm_m.meta_key LIKE '%title%'       AND pm_m.meta_value COLLATE utf8mb4_general_ci LIKE '%{$p}%')
                      )
                )";

                if ($doctor_keys_sql_str !== '') {
                    $must_or_chunks[] = "EXISTS (
                        SELECT 1 FROM {$wpdb->postmeta} pm_dm
                        WHERE pm_dm.post_id = {$wpdb->posts}.ID
                          AND {$wpdb->posts}.post_type = 'doctors'
                          AND pm_dm.meta_key IN ($doctor_keys_sql_str)
                          AND pm_dm.meta_value COLLATE utf8mb4_general_ci LIKE '%{$p}%'
                    )";
                }

                if (get_option('olimp_search_taxonomy_search', 1)) {
                    $must_or_chunks[] = "EXISTS (
                        SELECT 1 FROM {$wpdb->term_relationships} trm
                        INNER JOIN {$wpdb->term_taxonomy} ttm ON trm.term_taxonomy_id = ttm.term_taxonomy_id
                        INNER JOIN {$wpdb->terms} tm ON ttm.term_id = tm.term_id
                        WHERE trm.object_id = {$wpdb->posts}.ID
                          AND (
                               tm.name COLLATE utf8mb4_general_ci LIKE '%{$p}%'
                            OR tm.slug COLLATE utf8mb4_general_ci LIKE '%{$p}%'
                            OR ttm.description COLLATE utf8mb4_general_ci LIKE '%{$p}%'
                          )
                          AND ttm.taxonomy IN ('services','category','post_tag','service_category')
                    )";
                }
            }
        }

        if (!empty($must_or_chunks)) {
            $must_conditions = array('(' . implode(' OR ', $must_or_chunks) . ')');
        }
    }
}



        $or = array();
        foreach ($search_variants as $variant) {
            $v = esc_sql($variant);
            $or[] = "({$wpdb->posts}.post_title   COLLATE utf8mb4_general_ci LIKE '%{$v}%')";
            $or[] = "({$wpdb->posts}.post_content COLLATE utf8mb4_general_ci LIKE '%{$v}%')";
            $or[] = "({$wpdb->posts}.post_excerpt COLLATE utf8mb4_general_ci LIKE '%{$v}%')";

            $or[] = "EXISTS (SELECT 1 FROM {$wpdb->postmeta} pm
                    WHERE pm.post_id = {$wpdb->posts}.ID
                      AND (
                        (pm.meta_key = 'service_keywords' AND pm.meta_value COLLATE utf8mb4_general_ci LIKE '%{$v}%')
                     OR (pm.meta_key = 'service_synonyms' AND pm.meta_value COLLATE utf8mb4_general_ci LIKE '%{$v}%')
                     OR (pm.meta_key = 'search_terms'    AND pm.meta_value COLLATE utf8mb4_general_ci LIKE '%{$v}%')
                     OR (pm.meta_key LIKE '%description%' AND pm.meta_value COLLATE utf8mb4_general_ci LIKE '%{$v}%')
                     OR (pm.meta_key LIKE '%content%'     AND pm.meta_value COLLATE utf8mb4_general_ci LIKE '%{$v}%')
                     OR (pm.meta_key LIKE '%title%'       AND pm.meta_value COLLATE utf8mb4_general_ci LIKE '%{$v}%')
                      )
            )";

            $doctor_keys = olimp_get_doctor_position_keys();
            if (!empty($doctor_keys)) {
                $keys_sql = array();
                foreach ($doctor_keys as $dk) {
                    if ($dk === '') continue;
                    $keys_sql[] = "'" . esc_sql($dk) . "'";
                }
                if (!empty($keys_sql)) {
                    $keys_sql_str = implode(',', $keys_sql);
                    $or[] = "EXISTS (
                        SELECT 1
                        FROM {$wpdb->postmeta} pm_d
                        WHERE pm_d.post_id = {$wpdb->posts}.ID
                          AND {$wpdb->posts}.post_type = 'doctors'
                          AND pm_d.meta_key IN ($keys_sql_str)
                          AND pm_d.meta_value COLLATE utf8mb4_general_ci LIKE '%{$v}%'
                    )";
                }
            }

            if (get_option('olimp_search_taxonomy_search', 1)) {
                $or[] = "EXISTS (
                    SELECT 1 FROM {$wpdb->term_relationships} tr
                    INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
                    INNER JOIN {$wpdb->terms} t ON tt.term_id = t.term_id
                    WHERE tr.object_id = {$wpdb->posts}.ID
                      AND (t.name COLLATE utf8mb4_general_ci LIKE '%{$v}%' OR t.slug COLLATE utf8mb4_general_ci LIKE '%{$v}%')
                      AND tt.taxonomy IN ('services','category','post_tag','service_category')
                )";
            }
        }

        if (!empty($or)) {
            if (!empty($must_conditions)) {
                $search = " AND ((" . implode(' AND ', $must_conditions) . ") AND (" . implode(' OR ', $or) . "))";
            } else {
                $search = " AND (" . implode(' OR ', $or) . ")";
            }
        }

        if ( isset($_GET['debug']) && $_GET['debug']=='1' && current_user_can('manage_options') ) {
            $doctor_keys_dbg = implode(', ', olimp_get_doctor_position_keys());
            add_action('wp_footer', function() use ($search_term, $search_variants, $doctor_keys_dbg, $must_conditions, $or){
                echo '<div style="z-index:99999;position:fixed;right:12px;bottom:12px;background:#111;color:#eee;font:12px/1.4 monospace;padding:12px 14px;border-radius:8px;max-width:48vw;opacity:.95">';
                echo '<div style="font-weight:bold;margin-bottom:6px;">OLIMP Search Debug</div>';
                echo '<div><b>Query:</b> '.esc_html($search_term).'</div>';
                echo '<div><b>Variants ('.count($search_variants).'):</b> '.esc_html(implode(' | ', array_slice($search_variants,0,15))).'</div>';
                echo '<div><b>Doctor meta keys:</b> '.esc_html($doctor_keys_dbg).'</div>';
                if (!empty($must_conditions)) echo '<div><b>Strict must:</b> '.esc_html(mb_substr(implode(' ; ', $must_conditions),0,400)).'...</div>';
                echo '<div><b>OR-chunks:</b> '.esc_html(min(count($or), 999)).'</div>';
                echo '<div style="margin-top:6px;opacity:.7">Ключи должности настраиваются в «Поиск → Настройки».</div>';
                echo '</div>';
            });
        }

add_action('wp', function() use ($search_term, $search_variants) {
    if (!is_search() || !is_main_query()) return;
    global $wp_query, $wpdb;

    static $terms_added = false;
    if ($terms_added) return;
    $terms_added = true;

    $require_exact = get_option('olimp_search_require_exact', 1);

    $found_terms = array();
    foreach ($search_variants as $variant) {
        if ($require_exact) {
            $must_phr = build_must_phrases($search_term, $search_variants);
            $ok = false;
            foreach ($must_phr as $m) {
                if (mb_stripos(normalize_search_term($variant), $m) !== false || mb_stripos($m, normalize_search_term($variant)) !== false) { $ok = true; break; }
            }
            if (!$ok) continue;
        }
        $like = '%' . $wpdb->esc_like($variant) . '%';
        $terms = $wpdb->get_results($wpdb->prepare("
            SELECT t.term_id, t.name, t.slug, tt.description, tt.taxonomy, tt.count
            FROM {$wpdb->terms} t
            INNER JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id
            WHERE tt.taxonomy IN ('services','category','post_tag')
              AND (t.name LIKE %s OR t.slug LIKE %s OR tt.description LIKE %s)
            ORDER BY tt.count DESC, t.name ASC
            LIMIT 10
        ", $like, $like, $like));
        if ($terms) $found_terms = array_merge($found_terms, $terms);
    }
    if (empty($found_terms)) return;

    $uniq = array();
    foreach ($found_terms as $t) $uniq[$t->taxonomy.'_'.$t->term_id] = $t;
    $found_terms = array_values($uniq);

    $tax_priority = array(
        'services'         => 0,
        'service_category' => 0,
        'category'         => 1,
        'post_tag'         => 2,
    );
    $by_name = array();
    foreach ($found_terms as $t) {
        $k = mb_strtolower(trim($t->name), 'UTF-8');
        if (!isset($by_name[$k])) {
            $by_name[$k] = $t;
            continue;
        }
        $cur = $by_name[$k];
        $curP = $tax_priority[$cur->taxonomy] ?? 99;
        $newP = $tax_priority[$t->taxonomy]   ?? 99;

        if ($newP < $curP || ($newP === $curP && intval($t->count) > intval($cur->count))) {
            $by_name[$k] = $t;
        }
    }
    $found_terms = array_values($by_name);

    $term_posts = array();
    foreach ($found_terms as $term) {
        $p = new WP_Post((object)[
            'ID' => 'term_' . $term->term_id,
            'post_title' => $term->name,
            'post_content' => $term->description ?: "Направление: {$term->name}",
            'post_excerpt' => wp_trim_words($term->description ?: "Услуги по направлению {$term->name}", 20),
            'post_type' => 'olimp_term_result',
            'post_status' => 'publish',
            'post_date' => current_time('mysql'),
            'post_date_gmt' => current_time('mysql', 1),
            'post_modified' => current_time('mysql'),
            'post_modified_gmt' => current_time('mysql', 1),
            'post_author' => 1,
            'comment_count' => 0,
            'comment_status' => 'closed',
            'ping_status' => 'closed',
            'post_name' => $term->slug,
            'post_parent' => 0,
            'menu_order' => 0
        ]);
        $p->taxonomy   = $term->taxonomy;
        $p->term_id    = $term->term_id;
        $p->term_count = $term->count;
        $p->term_slug  = $term->slug;
        if ($term->taxonomy === 'services') {
            $p->guid = home_url("/services/{$term->slug}/");
        } else {
            $link = get_term_link($term->term_id, $term->taxonomy);
            $p->guid = !is_wp_error($link) ? $link : home_url("/{$term->taxonomy}/{$term->slug}/");
        }
        $term_posts[] = $p;
    }

    if (!empty($term_posts)) {
        $wp_query->posts = array_merge((array)$wp_query->posts, $term_posts);
        $wp_query->post_count  = count($wp_query->posts);
        $wp_query->found_posts = $wp_query->post_count;
        
        log_search_query($search_term . ' (with terms)', $wp_query->found_posts, $search_variants);
    }
}, 999);

        return $search;
    }, 999, 2);

/*
add_filter('the_posts', function($posts, $q){
    if (!$q->is_main_query() || !$q->is_search()) return $posts;
    if (empty($posts)) return $posts;
    $prio = olimp_search_priority_map();
    usort($posts, function($a, $b) use ($prio){
        $pta = $a->post_type ?? 'post';
        $ptb = $b->post_type ?? 'post';
        $ia = $prio[$pta] ?? 9999;
        $ib = $prio[$ptb] ?? 9999;
        if ($ia === $ib) {
            $da = strtotime($a->post_date_gmt ?? $a->post_date ?? '1970-01-01');
            $db = strtotime($b->post_date_gmt ?? $b->post_date ?? '1970-01-01');
            return $db <=> $da;
        }
        return $ia <=> $ib;
    });
    return $posts;
}, 999, 2);
*/



    add_action('wp', function() use ($search_term, $search_variants) {
    if (!is_search() || !is_main_query()) return;
    global $wp_query, $wpdb;

    $require_exact = get_option('olimp_search_require_exact', 1);

    $found_terms = array();
    foreach ($search_variants as $variant) {
        if ($require_exact) {
            $must_phr = build_must_phrases($search_term, $search_variants);
            $ok = false;
            foreach ($must_phr as $m) {
                if (mb_stripos(normalize_search_term($variant), $m) !== false || mb_stripos($m, normalize_search_term($variant)) !== false) { $ok = true; break; }
            }
            if (!$ok) continue;
        }
        $like = '%' . $wpdb->esc_like($variant) . '%';
        $terms = $wpdb->get_results($wpdb->prepare("
            SELECT t.term_id, t.name, t.slug, tt.description, tt.taxonomy, tt.count
            FROM {$wpdb->terms} t
            INNER JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id
            WHERE tt.taxonomy IN ('services','category','post_tag')
              AND (t.name LIKE %s OR t.slug LIKE %s OR tt.description LIKE %s)
            ORDER BY tt.count DESC, t.name ASC
            LIMIT 10
        ", $like, $like, $like));
        if ($terms) $found_terms = array_merge($found_terms, $terms);
    }
    if (empty($found_terms)) return;

    $uniq = array();
    foreach ($found_terms as $t) $uniq[$t->taxonomy.'_'.$t->term_id] = $t;
    $found_terms = array_values($uniq);

    $tax_priority = array(
        'services'         => 0,
        'service_category' => 0,
        'category'         => 1,
        'post_tag'         => 2,
    );
    $by_name = array();
    foreach ($found_terms as $t) {
        $k = mb_strtolower(trim($t->name), 'UTF-8');
        if (!isset($by_name[$k])) {
            $by_name[$k] = $t;
            continue;
        }
        $cur = $by_name[$k];
        $curP = $tax_priority[$cur->taxonomy] ?? 99;
        $newP = $tax_priority[$t->taxonomy]   ?? 99;

        if ($newP < $curP || ($newP === $curP && intval($t->count) > intval($cur->count))) {
            $by_name[$k] = $t;
        }
    }
    $found_terms = array_values($by_name);

    $term_posts = array();
    foreach ($found_terms as $term) {
        $p = new WP_Post((object)[
            'ID' => 'term_' . $term->term_id,
            'post_title' => $term->name,
            'post_content' => $term->description ?: "Направление: {$term->name}",
            'post_excerpt' => wp_trim_words($term->description ?: "Услуги по направлению {$term->name}", 20),
            'post_type' => 'olimp_term_result',
            'post_status' => 'publish',
            'post_date' => current_time('mysql'),
            'post_date_gmt' => current_time('mysql', 1),
            'post_modified' => current_time('mysql'),
            'post_modified_gmt' => current_time('mysql', 1),
            'post_author' => 1,
            'comment_count' => 0,
            'comment_status' => 'closed',
            'ping_status' => 'closed',
            'post_name' => $term->slug,
            'post_parent' => 0,
            'menu_order' => 0
        ]);
        $p->taxonomy   = $term->taxonomy;
        $p->term_id    = $term->term_id;
        $p->term_count = $term->count;
        $p->term_slug  = $term->slug;
        if ($term->taxonomy === 'services') {
            $p->guid = home_url("/services/{$term->slug}/");
        } else {
            $link = get_term_link($term->term_id, $term->taxonomy);
            $p->guid = !is_wp_error($link) ? $link : home_url("/{$term->taxonomy}/{$term->slug}/");
        }
        $term_posts[] = $p;
    }

    if (!empty($term_posts)) {
        static $olimp_terms_injected = false;
        if ($olimp_terms_injected) return;
        $olimp_terms_injected = true;

        $existing_posts = $wp_query->posts;
        
        $exact_matches = array();
        $other_existing = array();
        
        $search_normalized = mb_strtolower(trim($search_term), 'UTF-8');
        $search_clean = olimp_norm_hyphens_spaces($search_normalized);
        
        foreach ($existing_posts as $post) {
            $post_title = $post->post_title;
            $post_title_lower = mb_strtolower(trim($post_title), 'UTF-8');
            $post_title_clean = olimp_norm_hyphens_spaces($post_title_lower);
            $is_exact = false;
            
            if ($post_title_lower === $search_normalized || 
                $post_title_clean === $search_clean) {
                $is_exact = true;
            }
            
            if (!$is_exact && mb_strlen($search_clean, 'UTF-8') >= 10) { 
                if (mb_strpos($post_title_lower, $search_normalized) === 0 ||
                    mb_strpos($post_title_clean, $search_clean) === 0) {
                    $is_exact = true;
                }
                
                if (!$is_exact) {
                    $search_len = mb_strlen($search_clean, 'UTF-8');
                    $title_start = mb_substr($post_title_clean, 0, $search_len, 'UTF-8');
                    
                    if ($title_start === $search_clean) {
                        $char_after = mb_substr($post_title_clean, $search_len, 1, 'UTF-8');
                        if ($char_after === ',' || $char_after === '-' || $char_after === '(' || $char_after === '') {
                            $is_exact = true;
                        }
                    }
                }
            }
            
            if ($is_exact) {
                $exact_matches[] = $post;
            } else {
                $other_existing[] = $post;
            }
        }
        
        $combined = array_merge($other_existing, $term_posts);
        
        $prio = olimp_search_priority_map();
        usort($combined, function($a, $b) use ($prio){
            $pta = $a->post_type ?? 'post';
            $ptb = $b->post_type ?? 'post';
            $ia = $prio[$pta] ?? 9999;
            $ib = $prio[$ptb] ?? 9999;
            if ($ia === $ib) {
                $da = strtotime($a->post_date_gmt ?? $a->post_date ?? '1970-01-01');
                $db = strtotime($b->post_date_gmt ?? $b->post_date ?? '1970-01-01');
                return $db <=> $da;
            }
            return $ia <=> $ib;
        });
        
        usort($exact_matches, function($a, $b) use ($prio){
            $pta = $a->post_type ?? 'post';
            $ptb = $b->post_type ?? 'post';
            $ia = $prio[$pta] ?? 9999;
            $ib = $prio[$ptb] ?? 9999;
            if ($ia === $ib) {
                $da = strtotime($a->post_date_gmt ?? $a->post_date ?? '1970-01-01');
                $db = strtotime($b->post_date_gmt ?? $b->post_date ?? '1970-01-01');
                return $db <=> $da;
            }
            return $ia <=> $ib;
        });
        
        $wp_query->posts = array_merge($exact_matches, $combined);
        $wp_query->post_count  = count($wp_query->posts);
        $wp_query->found_posts = $wp_query->post_count;
        
        log_search_query($search_term . ' (with terms)', $wp_query->found_posts, $search_variants);
    }
}, 999);
}
add_action('template_redirect', function() {
    if (is_search()) {
        add_filter('post_link', function($permalink, $post) {
            if (is_object($post) && $post->post_type === 'center-services') {
                return get_center_service_link($post->ID);
            }
            return $permalink;
        }, 10, 2);
        
        add_filter('get_permalink', function($permalink, $post) {
            if (is_object($post) && isset($post->post_type) && $post->post_type === 'center-services') {
                return get_center_service_link($post->ID);
            }
            return $permalink;
        }, 10, 2);
        
        add_filter('the_permalink', function($permalink) {
            global $post;
            if (isset($post->post_type) && $post->post_type === 'center-services') {
                return get_center_service_link($post->ID);
            }
            return $permalink;
        }, 10);
    }
});
add_action('pre_get_posts', 'final_enhanced_search', 999);


function olimp_force_taxonomy_search($query) {
    if (is_admin() || !$query->is_search() || !$query->is_main_query()) return;
    $search_term = $query->get('s');
    if (empty($search_term)) return;

    add_action('wp', function() use ($search_term) {
        global $wp_query, $wpdb;
        if ($wp_query->found_posts > 0) return;

        $require_exact = get_option('olimp_search_require_exact', 1);
        $variants = get_search_variants($search_term);
        $must_phr = $require_exact ? build_must_phrases($search_term, $variants) : array();

        $found_terms = array();
        foreach ($variants as $variant) {
            if ($require_exact && !empty($must_phr)) {
                $ok = false;
                foreach ($must_phr as $m) {
                    if (mb_stripos(normalize_search_term($variant), $m) !== false || mb_stripos($m, normalize_search_term($variant)) !== false) { $ok = true; break; }
                }
                if (!$ok) continue;
            }
            $like = '%' . $wpdb->esc_like($variant) . '%';
            $terms = $wpdb->get_results($wpdb->prepare("
                SELECT t.term_id, t.name, tt.taxonomy, tt.count
                FROM {$wpdb->terms} t
                INNER JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id
                WHERE tt.taxonomy IN ('services','category','post_tag')
                  AND (t.name LIKE %s OR t.slug LIKE %s)
                  AND tt.count > 0
                ORDER BY tt.count DESC
                LIMIT 10
            ", $like, $like));
            if ($terms) $found_terms = array_merge($found_terms, $terms);
        }

        if (empty($found_terms)) return;

        $term_ids = array_unique(wp_list_pluck($found_terms, 'term_id'));
        $enabled_types = olimp_search_get_enabled_post_types();
        $tax_posts = get_posts(array(
            'post_type'   => $enabled_types,
            'post_status' => 'publish',
            'numberposts' => 20,
            'tax_query'   => array(
                array(
                    'taxonomy' => array('services','category','post_tag'),
                    'field'    => 'term_id',
                    'terms'    => $term_ids,
                    'operator' => 'IN'
                )
            )
        ));

        if (!empty($tax_posts)) {
            $wp_query->posts = $tax_posts;
            $wp_query->post_count = count($tax_posts);
            $wp_query->found_posts = count($tax_posts);
            $wp_query->max_num_pages = 1;
            log_search_query($search_term . ' (forced taxonomy)', count($tax_posts), $variants);
        }
    });
}
add_action('pre_get_posts', 'olimp_force_taxonomy_search', 1000);

function final_backup_search() {
    if (!is_search() || is_admin()) return;
    global $wp_query;
    if ($wp_query->found_posts > 0) return;

    $search_term = get_search_query();
    if (empty($search_term)) return;

    $require_exact = get_option('olimp_search_require_exact', 1);
    $variants = get_search_variants($search_term);
    $must_phr = $require_exact ? build_must_phrases($search_term, $variants) : array();
    $enabled_types = olimp_search_get_enabled_post_types();

    foreach ($variants as $variant) {
        if ($require_exact && !empty($must_phr)) {
            $ok = false;
            foreach ($must_phr as $m) {
                if (mb_stripos(normalize_search_term($variant), $m) !== false || mb_stripos($m, normalize_search_term($variant)) !== false) { $ok = true; break; }
            }
            if (!$ok) continue;
        }
        $posts = get_posts(array(
            'post_type'      => $enabled_types,
            'post_status'    => 'publish',
            'numberposts'    => 10,
            's'              => $variant
        ));
        if (!empty($posts)) {
            $wp_query->posts = $posts;
            $wp_query->post_count = count($posts);
            $wp_query->found_posts = count($posts);
            $wp_query->max_num_pages = 1;
            log_search_query($search_term . ' (backup)', count($posts), $variants);
            return;
        }
    }

    if ($wp_query->found_posts == 0 && get_option('olimp_search_taxonomy_search', 1)) {
        foreach ($variants as $variant) {
            if ($require_exact && !empty($must_phr)) {
                $ok = false;
                foreach ($must_phr as $m) {
                    if (mb_stripos(normalize_search_term($variant), $m) !== false || mb_stripos($m, normalize_search_term($variant)) !== false) { $ok = true; break; }
                }
                if (!$ok) continue;
            }
            $terms = get_terms(array(
                'taxonomy'   => array('services','category','post_tag'),
                'search'     => $variant,
                'hide_empty' => false
            ));
            if (!empty($terms)) {
                $term_ids = wp_list_pluck($terms, 'term_id');
                $tax_posts = get_posts(array(
                    'post_type'   => $enabled_types,
                    'post_status' => 'publish',
                    'numberposts' => 10,
                    'tax_query'   => array(
                        array(
                            'taxonomy' => array('services','category','post_tag'),
                            'field'    => 'term_id',
                            'terms'    => $term_ids,
                            'operator' => 'IN'
                        )
                    )
                ));
                if (!empty($tax_posts)) {
                    $wp_query->posts = $tax_posts;
                    $wp_query->post_count = count($tax_posts);
                    $wp_query->found_posts = count($tax_posts);
                    $wp_query->max_num_pages = 1;
                    log_search_query($search_term . ' (taxonomy backup)', count($tax_posts), $variants);
                    return;
                }
            }
        }
    }
}
add_action('wp', 'final_backup_search', 999);


function handle_virtual_term_posts() {
    add_filter('the_title', function($title){
        global $post;
        if (isset($post->post_type) && $post->post_type === 'olimp_term_result') return $post->post_title;
        return $title;
    }, 10, 1);

    add_filter('the_excerpt', function($excerpt){
        global $post;
        if (isset($post->post_type) && $post->post_type === 'olimp_term_result') return $post->post_excerpt;
        return $excerpt;
    });

    add_filter('the_content', function($content){
        global $post;
        if (isset($post->post_type) && $post->post_type === 'olimp_term_result') {
            $link = '';
            if (!empty($post->guid)) {
                $link = $post->guid;
            } elseif (isset($post->term_id, $post->taxonomy)) {
                if ($post->taxonomy === 'services') {
                    $fresh = get_term($post->term_id, $post->taxonomy);
                    if ($fresh && !is_wp_error($fresh)) $link = home_url("/services/{$fresh->slug}/");
                } else {
                    $wp_link = get_term_link($post->term_id, $post->taxonomy);
                    if (!is_wp_error($wp_link)) $link = $wp_link;
                }
            }
            $btn  = $link ? '<p><a href="'.esc_url($link).'" class="button">Смотреть все услуги направления →</a></p>' : '';

            $info  = '<div class="olimp-term-result" style="background:#f8f9fa;padding:15px;border-left:4px solid #007cba;margin-bottom:20px;">';
            $info .= '<h3 style="margin-top:0;color:#007cba;">🏷️ Направление: '.esc_html($post->post_title).'</h3>';
            if (!empty($post->post_content) && $post->post_content !== "Направление: {$post->post_title}") {
                $info .= '<p>'.esc_html($post->post_content).'</p>';
            }
            if (!empty($post->term_count)) $info .= '<p><strong>Связанных услуг:</strong> '.intval($post->term_count).'</p>';
            $info .= $btn;
            $info .= '</div>';
            return $info;
        }
        return $content;
    });

    $link_cb = function($permalink, $post){
        if (is_object($post) && isset($post->post_type) && $post->post_type === 'olimp_term_result') {
            if (!empty($post->guid)) return $post->guid;
            if (isset($post->taxonomy, $post->term_slug)) {
                if ($post->taxonomy === 'services') return home_url("/services/{$post->term_slug}/");
                return home_url("/{$post->taxonomy}/{$post->term_slug}/");
            }
        }
        return $permalink;
    };
    add_filter('post_link',     $link_cb, 10, 2);
    add_filter('get_permalink', $link_cb, 10, 2);
    add_filter('the_permalink', function($permalink){
        global $post;
        if (isset($post->post_type) && $post->post_type === 'olimp_term_result' && !empty($post->guid)) return $post->guid;
        return $permalink;
    }, 10, 1);
}
add_action('init', 'handle_virtual_term_posts');


function get_correct_term_link($term_id, $taxonomy) {
    $term_link = get_term_link($term_id, $taxonomy);
    if (!is_wp_error($term_link)) return $term_link;
    $term = get_term($term_id, $taxonomy);
    if ($term && !is_wp_error($term)) {
        if ($taxonomy === 'services') return home_url("/services/{$term->slug}/");
        return home_url("/{$taxonomy}/{$term->slug}/");
    }
    return home_url('/');
}
function get_post_type_icon($post_type) {
    $icons = array(
        'programms'       => '<i class="fas fa-clipboard-list"></i>',
        'service'         => '<i class="fas fa-stethoscope"></i>',
        'center-services' => '<i class="fas fa-hospital"></i>',
        'centres'         => '<i class="fas fa-building"></i>', 
        'promo'           => '<i class="fas fa-percentage"></i>',
        'doctors'         => '<i class="fas fa-user-md"></i>',
        'page'            => '<i class="fas fa-file-medical"></i>',
        'post'            => '<i class="fas fa-newspaper"></i>',
        'videos'          => '<i class="fas fa-video"></i>',
        'events'          => '<i class="fas fa-calendar"></i>',
        'bio-market'      => '<i class="fas fa-shopping-cart"></i>'
    );
    return $icons[$post_type] ?? '<i class="fas fa-file"></i>';
}
function olimp_ajax_search() {
    if (!wp_verify_nonce($_POST['nonce'] ?? '', 'search_nonce')) wp_send_json_error('Ошибка безопасности');

    $search_term = sanitize_text_field($_POST['q'] ?? '');
    if (empty($search_term) || mb_strlen($search_term,'UTF-8') < 2) wp_send_json_error('Введите минимум 2 символа');

    $require_exact = get_option('olimp_search_require_exact', 1);
    $variants   = get_search_variants($search_term);
    $must_phr   = $require_exact ? build_must_phrases($search_term, $variants) : array();
    $enabled_types = olimp_search_get_enabled_post_types();

    $args = array(
        'post_type'      => $enabled_types,
        'post_status'    => 'publish',
        'posts_per_page' => 12,
        's'              => $search_term
    );

    $q = new WP_Query($args);
    $results = array();

    if ($q->have_posts()) {
        while ($q->have_posts()) { $q->the_post();
            if ($require_exact && !empty($must_phr)) {
                $t = mb_strtolower(get_the_title(), 'UTF-8');
                $c = mb_strtolower(wp_strip_all_tags(get_the_content('')), 'UTF-8');
                $e = mb_strtolower(wp_strip_all_tags(get_the_excerpt() ?: ''), 'UTF-8');
                $ok = false;
                foreach ($must_phr as $m) {
                    if (mb_stripos($t, $m)!==false || mb_stripos($c, $m)!==false || mb_stripos($e, $m)!==false) { $ok=true; break; }
                }
                if (!$ok) continue;
            }
            $post_type = get_post_type();
            $labels = array(
                'programms'       => 'Программа',
                'service'         => 'Услуга', 
                'center-services' => 'Услуги центра',
                'centres'         => 'Центр',
                'promo'           => 'Акция',
                'doctors'         => 'Врач',
                'page'            => 'Страница',
                'post'            => 'Статья',
                'videos'          => 'Видео',
                'events'          => 'Событие', 
                'bio-market'      => 'Товар'
            );
								  
            $results[] = array(
                'id'        => get_the_ID(),
                'title'     => get_the_title(),
                'url'       => get_permalink(),
                'excerpt'   => wp_trim_words(get_the_excerpt() ?: get_the_content(), 15),
                'type'      => $labels[$post_type] ?? 'Контент',
                'post_type' => $post_type,
                'icon'      => get_post_type_icon($post_type)
            );
        }
        wp_reset_postdata();
    }

    if (get_option('olimp_search_taxonomy_search', 1) && count($results) < 12) {
        global $wpdb;
        $found_terms = array();
        foreach ($variants as $variant) {
            if ($require_exact && !empty($must_phr)) {
                $ok = false;
                foreach ($must_phr as $m) {
                    if (mb_stripos(normalize_search_term($variant), $m)!==false || mb_stripos($m, normalize_search_term($variant))!==false) { $ok=true; break; }
                }
                if (!$ok) continue;
            }
            $like = '%' . $wpdb->esc_like($variant) . '%';
            $terms = $wpdb->get_results($wpdb->prepare("
                SELECT t.term_id, t.name, t.slug, tt.description, tt.taxonomy, tt.count
                FROM {$wpdb->terms} t
                INNER JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id
                WHERE tt.taxonomy IN ('services','category')
                  AND (t.name LIKE %s OR t.slug LIKE %s OR tt.description LIKE %s)
                ORDER BY tt.count DESC, t.name ASC
                LIMIT 5
            ", $like, $like, $like));
            if ($terms) $found_terms = array_merge($found_terms, $terms);
        }

        $uniq = array();
        foreach ($found_terms as $term) $uniq[$term->taxonomy.'_'.$term->term_id] = $term;

        foreach ($uniq as $term) {
            $term_url = ($term->taxonomy === 'services') ? home_url("/services/{$term->slug}/") : get_correct_term_link($term->term_id, $term->taxonomy);
            $results[] = array(
                'id'         => 'term_' . $term->term_id,
                'title'      => $term->name,
                'url'        => $term_url,
                'excerpt'    => $term->description ?: "Направление: {$term->name}",
                'type'       => 'Направление (' . ucfirst($term->taxonomy) . ')',
                'post_type'  => 'olimp_term_result',
                'icon'       => '<i class="fas fa-tags"></i>',
                'is_term'    => true,
                'term_count' => intval($term->count)
            );
            if (count($results) >= 12) break;
        }
    }

    $prio = olimp_search_priority_map();
    usort($results, function($a, $b) use ($prio){
        $pta = $a['post_type'] ?? 'post';
        $ptb = $b['post_type'] ?? 'post';
        $ia = $prio[$pta] ?? 9999;
        $ib = $prio[$ptb] ?? 9999;
        return $ia <=> $ib;
    });

    log_search_query($search_term . ' (ajax with terms)', count($results), $variants);

    if (!empty($results)) {
        wp_send_json_success(array('results'=>$results, 'total'=>count($results), 'search_term'=>$search_term));
    } else {
        wp_send_json_success(array('results'=>array(), 'suggestions'=>array_slice($variants, 1, 3), 'message'=>'По запросу "'.$search_term.'" ничего не найдено'));
    }
}
add_action('wp_ajax_olimp_search',        'olimp_ajax_search');
add_action('wp_ajax_nopriv_olimp_search', 'olimp_ajax_search');
function get_center_service_link($post_id) {
    $service_id = null;
    
    $service_id_field = get_post_meta($post_id, '_rb_serv_services|||0|id', true);
    
    if (!empty($service_id_field)) {
        if (is_array($service_id_field) && isset($service_id_field[0])) {
            $service_id = intval($service_id_field[0]);
        } else {
            $service_id = intval($service_id_field);
        }
    }
    
    if (!$service_id) { 
        $services = get_post_meta($post_id, '_rb_serv_services', true);
        
        if (!empty($services) && is_array($services)) {
            $first_service = $services[0];
            
            if (isset($first_service['type']) && $first_service['type'] === 'post' && isset($first_service['id'])) {
                $service_id = $first_service['id'];
            } elseif (isset($first_service['ID'])) {
                $service_id = $first_service['ID'];
            } elseif (is_numeric($first_service)) {
                $service_id = $first_service;
            }
        }
    }
    
    if ($service_id) {
        $service_post = get_post($service_id);
        
        if ($service_post && $service_post->post_type === 'service') {
            return get_permalink($service_id);
        }
    }
    
    $term_id = null;
    
    $term_id_field = get_post_meta($post_id, '_rb_serv_directions|||0|id', true);
    
    if (!empty($term_id_field)) {
        if (is_array($term_id_field) && isset($term_id_field[0])) {
            $term_id = intval($term_id_field[0]);
        } else {
            $term_id = intval($term_id_field);
        }
    }
    
    if (!$term_id) { 
        $directions = get_post_meta($post_id, '_rb_serv_directions', true);
        
        if (!empty($directions) && is_array($directions)) {
            $first_direction = $directions[0];
            
            if (isset($first_direction['type']) && $first_direction['type'] === 'term' && isset($first_direction['id'])) {
                $term_id = $first_direction['id'];
            } elseif (isset($first_direction['term_id'])) {
                $term_id = $first_direction['term_id'];
            } elseif (is_numeric($first_direction)) {
                $term_id = $first_direction;
            } elseif (isset($first_direction['ID'])) {
                $term_id = $first_direction['ID'];
            }
        }
    }
    
    if ($term_id) {
        $term = get_term($term_id, 'services');
        
        if ($term && !is_wp_error($term)) {
            return home_url("/services/{$term->slug}/");
        }
    }
    
    return get_permalink($post_id);
}

function olimp_search_init_defaults() {
    if (get_option('olimp_search_initialized_v4') !== '1') {
        update_option('olimp_search_debug', 0);
        update_option('olimp_search_logging', 1);
        update_option('olimp_search_transliteration', 1);
        update_option('olimp_search_fuzzy', 1);
        update_option('olimp_search_synonyms_enabled', 1);
        update_option('olimp_search_taxonomy_search', 1);
        update_option('olimp_search_require_exact', 1);


        $enabled = olimp_search_default_post_types();
        update_option('olimp_search_post_types', $enabled);
        $priority = $enabled;
        $priority[] = 'olimp_term_result';
        update_option('olimp_search_post_type_priority', $priority);

        $default_synonyms = array(
            array('main'=>'реабилитация','synonyms'=>'восстановление, физиотерапия, лфк, реабилитология','enabled'=>1),
            array('main'=>'чекап','synonyms'=>'обследование, диагностика, check-up, чек-ап','enabled'=>1),
            array('main'=>'невролог','synonyms'=>'неврология, невропатолог, нейролог, прием невролога, консультация невролога','enabled'=>1),
        );
        update_option('olimp_search_custom_synonyms', $default_synonyms);

        if ( !get_option('olimp_doctor_position_keys') ) {
            update_option('olimp_doctor_position_keys', array(
                '_rb_doc_occup','position','job_title','doctor_position','doljnost','spec','speciality'
            ));
            update_option('olimp_doctor_position_keys_raw',
                '_rb_doc_occup, position, job_title, doctor_position, doljnost, spec, speciality'
            );
        }

        update_option('olimp_search_initialized_v4', '1');
    }
}
add_action('admin_init', 'olimp_search_init_defaults');

add_filter('the_posts', function($posts, $query) {
    if (!$query->is_main_query() || !$query->is_search() || is_admin()) {
        return $posts;
    }
    
    static $already_sorted = false;
    if ($already_sorted) {
        return $posts;
    }
    $already_sorted = true;
    
    if (empty($posts)) {
        return $posts;
    }
    
    $search_term = $query->get('s');
    if (empty($search_term)) {
        return $posts;
    }
    
    $search_normalized = mb_strtolower(trim($search_term), 'UTF-8');
    $search_clean = olimp_norm_hyphens_spaces($search_normalized);
    $search_length = mb_strlen($search_clean, 'UTF-8');
    
    $exact_matches = array();   
    $high_relevance = array();   
    $other_posts = array();     
    
    foreach ($posts as $post) {
        $post_title = $post->post_title;
        $post_title_lower = mb_strtolower(trim($post_title), 'UTF-8');
        $post_title_clean = olimp_norm_hyphens_spaces($post_title_lower);
        
        $category = 'other';
        
        if ($post_title_lower === $search_normalized || 
            $post_title_clean === $search_clean) {
            $category = 'exact';
        }
        
        if ($category === 'other' && $search_length >= 10) {
            if (mb_strpos($post_title_lower, $search_normalized) === 0 ||
                mb_strpos($post_title_clean, $search_clean) === 0) {
                $category = 'exact';
            }
            
            if ($category === 'other') {
                $title_start = mb_substr($post_title_clean, 0, $search_length, 'UTF-8');
                if ($title_start === $search_clean) {
                    $char_after = mb_substr($post_title_clean, $search_length, 1, 'UTF-8');
                    if (in_array($char_after, [',', '-', '(', ')', '.', ':', ';', '—', ''], true)) {
                        $category = 'exact';
                    }
                }
            }
        }
        
        if ($category === 'other' && $search_length >= 5) {
            if (mb_strpos($post_title_lower, $search_normalized) !== false ||
                mb_strpos($post_title_clean, $search_clean) !== false) {
                $category = 'high';
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
    
    $prio = olimp_search_priority_map();
    $sort_by_priority = function($a, $b) use ($prio) {
        $type_a = $a->post_type ?? 'post';
        $type_b = $b->post_type ?? 'post';
        $prio_a = $prio[$type_a] ?? 9999;
        $prio_b = $prio[$type_b] ?? 9999;
        
        if ($prio_a !== $prio_b) {
            return $prio_a <=> $prio_b;
        }
        
        $rel_a = isset($a->olimp_relevance) ? floatval($a->olimp_relevance) : 0;
        $rel_b = isset($b->olimp_relevance) ? floatval($b->olimp_relevance) : 0;
        
        if ($rel_a !== $rel_b) {
            return $rel_b <=> $rel_a;
        }
        
        $date_a = strtotime($a->post_date_gmt ?? $a->post_date ?? '1970-01-01');
        $date_b = strtotime($b->post_date_gmt ?? $b->post_date ?? '1970-01-01');
        
        return $date_b <=> $date_a;
    };
    
    usort($exact_matches, $sort_by_priority);
    usort($high_relevance, $sort_by_priority);
    usort($other_posts, $sort_by_priority);
    
    $sorted_posts = array_merge($exact_matches, $high_relevance, $other_posts);
    
    if (isset($_GET['debug']) && $_GET['debug'] == '1' && current_user_can('manage_options')) {
        error_log('=== OLIMP FINAL SORT ===');
        error_log('Total: ' . count($sorted_posts));
        error_log('Exact: ' . count($exact_matches));
        error_log('High: ' . count($high_relevance));
        error_log('Other: ' . count($other_posts));
        if (!empty($sorted_posts)) {
            error_log('First 3:');
            foreach (array_slice($sorted_posts, 0, 3) as $i => $p) {
                error_log(sprintf('#%d [%s] %s', $i+1, $p->post_type, $p->post_title));
            }
        }
    }
    
    return $sorted_posts;
    
}, 999999, 2);