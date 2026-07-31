<?php 
/**
 * Шаблон страницы результатов поиска для WordPress
 * БЕЗ дублирования функций
 */

get_header();
?>

<div class="search-page rb-page">
    <div class="rb-container">
        <h1 class="rb-title">Поиск по сайту</h1>
        
        <?php if (get_search_query()): ?>
            <p>По запросу «<b><?php echo esc_html(get_search_query()); ?></b>» найдены следующие результаты:</p>
        <?php endif; ?>

        <div class="bx-ag-search-page search-page theme-blue">
            
            <!-- Скрытая форма поиска (как в оригинале) -->
            <form class="search-page-form" action="<?php echo esc_url(home_url('/')); ?>" method="get" style="display: none;">
                <input placeholder="Поиск по сайту" type="text" name="s" value="<?php echo esc_attr(get_search_query()); ?>" size="50" />
                <input type="submit" value="Найти" />
                <?php if (function_exists('SWP')): ?>
                    <input type="hidden" name="swp_engine" value="default" />
                <?php endif; ?>
            </form>

            <?php 
            // Показываем диагностику только админам с параметром debug
            if (is_search() && current_user_can('administrator') && isset($_GET['debug'])) {
                global $wp_query;
                echo '<div style="background: #f0f0f0; padding: 20px; margin: 20px; border: 2px solid #333;">';
                echo '<h3>🔍 ДИАГНОСТИКА ПОИСКА:</h3>';
                echo '<p><strong>Поисковый запрос:</strong> "' . get_search_query() . '"</p>';
                echo '<p><strong>Найдено постов:</strong> ' . $wp_query->found_posts . '</p>';
                echo '<p><strong>Количество постов в запросе:</strong> ' . $wp_query->post_count . '</p>';
                echo '<p><strong>SearchWP активен:</strong> ' . (function_exists('SWP') ? 'ДА' : 'НЕТ') . '</p>';
                
                // Простой тест поиска
                $test_query = new WP_Query(array(
                    'post_type' => array('post', 'page'),
                    'posts_per_page' => 5,
                    'post_status' => 'publish',
                    's' => get_search_query()
                ));
                echo '<p><strong>Тестовый поиск нашел:</strong> ' . $test_query->found_posts . ' результатов</p>';
                
                if ($test_query->have_posts()) {
                    echo '<ul>';
                    while ($test_query->have_posts()) {
                        $test_query->the_post();
                        echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
                    }
                    echo '</ul>';
                    wp_reset_postdata();
                }
                echo '</div>';
            }
            ?>

            <?php 
            // Проверяем есть ли поисковый запрос и результаты
            if (get_search_query() === ''): 
                // Пустой запрос - ничего не показываем
            elseif (have_posts()): ?>
                <div class="search-view-default">
                    
                    <!-- Пагинация сверху -->
                    <?php
                    global $wp_query;
                    if ($wp_query->max_num_pages > 1):
                        echo '<div class="search-pagination-top">';
                        echo paginate_links(array(
                            'prev_text' => '← Предыдущая',
                            'next_text' => 'Следующая →',
                        ));
                        echo '</div><br />';
                    endif;
                    ?>

                    <?php 
                    $item_counter = 0;
                    while (have_posts()): 
                        the_post(); 
                        $post_type = get_post_type();
                        $post_id = get_the_ID();
                        

                        // Определяем тип контента
                        $is_service = ($post_type === 'page' && (
                            strpos(get_permalink(), '/services/') !== false ||
                            strpos(get_permalink(), '/uslugi/') !== false ||
                            strpos(strtolower(get_the_title()), 'услуг') !== false ||
                            get_post_meta($post_id, 'is_service', true)
                        ));
                        
                        $is_first_item = ($item_counter === 0);
                        
                        // Получаем изображение поста
                        $post_image = '';
                        if (has_post_thumbnail()) {
                            $post_image = get_the_post_thumbnail_url($post_id, 'medium');
                        }
                        
                        // Получаем цену для услуг
                        $price = '';
                        if ($is_service) {
                            $price_fields = array('price', 'service_price', 'cost', 'stoimost');
                            foreach ($price_fields as $field) {
                                $price_value = get_post_meta($post_id, $field, true);
                                if (!empty($price_value) && is_numeric($price_value)) {
                                    $price = number_format($price_value, 0, '', '&nbsp;');
                                    break;
                                }
                            }
                        }
                        
                        // Функция подсветки (простая версия)
                        $search_query = get_search_query();
                        $highlight_title = $search_query ? str_ireplace($search_query, '<mark>' . $search_query . '</mark>', get_the_title()) : get_the_title();
                    ?>
                        <div class="<?php if($is_first_item) echo 'search-item-first '; ?>search-item">

                            <?php if ($post_image): ?>
                                <div class="search-preview">
                                    <img src="<?php echo esc_url($post_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                                </div>
                            <?php endif; ?>

                            <div class="search-description">
                                <div class="search-title <?php if($is_service) echo 'search-title--service'; ?>">
                                    
                                    <?php if ($is_service): ?>
                                        <div class="js_search_href bx_item_block_href search-title-price">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php echo wp_kses_post($highlight_title); ?>
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <a href="<?php the_permalink(); ?>">
                                            <?php echo wp_kses_post($highlight_title); ?>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($is_service && $price): ?>
                                        <div class="search__page--price"><?php echo $price; ?>&nbsp;₽</div>
                                    <?php endif; ?>
                                </div>

                                <div class="search-previewtext">
                                    <?php 
                                    $excerpt = get_the_excerpt();
                                    if (empty($excerpt)) {
                                        $content = get_the_content();
                                        $excerpt = wp_trim_words(strip_tags($content), 25);
                                    }
                                    
                                    // Подсветка в тексте
                                    if ($search_query) {
                                        $excerpt = str_ireplace($search_query, '<mark>' . $search_query . '</mark>', $excerpt);
                                    }
                                    
                                    echo wp_kses_post($excerpt);
                                    ?>
                                </div>

                                <?php 
                                // Показываем дополнительные поля для услуг
                                if ($is_service) {
                                    $display_fields = array(
                                        'service_duration' => 'Длительность',
                                        'duration' => 'Длительность',
                                        'service_doctor' => 'Врач',
                                        'doctor' => 'Врач'
                                    );
                                    
                                    $custom_fields = array();
                                    foreach ($display_fields as $field_key => $field_name) {
                                        $value = get_post_meta($post_id, $field_key, true);
                                        if (!empty($value)) {
                                            $custom_fields[] = array('name' => $field_name, 'value' => $value);
                                            break; // Показываем только одно поле
                                        }
                                    }
                                    
                                    if (!empty($custom_fields)):
                                ?>
                                    <div class="search-propslist">
                                        <?php foreach($custom_fields as $field): ?>
                                            <div class="search-propsitem">
                                                <span class="search-propsitem-name"><?php echo esc_html($field['name']); ?>:</span> 
                                                <span class="search-propsitem-value"><?php echo esc_html($field['value']); ?></span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php 
                                    endif;
                                } 
                                ?>

                                <div class="clear"></div>
                            </div>
                        </div>
                    <?php 
                        $item_counter++;
                    endwhile; 
                    ?>

                    <!-- Пагинация снизу -->
                    <?php if ($wp_query->max_num_pages > 1): ?>
                        <div class="search_page_paging">
                            <?php
                            echo paginate_links(array(
                                'prev_text' => '← Предыдущая',
                                'next_text' => 'Следующая →',
                                'mid_size' => 2,
                            ));
                            ?>
                        </div>
                    <?php endif; ?>

                    <div class="search-change-how">
                        <?php
                        // Добавляем сортировку результатов
                        $current_order = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'relevance';
                        $search_query = get_search_query();
                        $base_url = add_query_arg('s', urlencode($search_query), home_url('/'));
                        
                        if (isset($_GET['swp_engine'])) {
                            $base_url = add_query_arg('swp_engine', sanitize_text_field($_GET['swp_engine']), $base_url);
                        }
                        ?>
                        
                        <?php if ($current_order === 'date'): ?>
                            <a href="<?php echo esc_url(add_query_arg('orderby', 'relevance', $base_url)); ?>">Сортировать по релевантности</a>&nbsp;|&nbsp;
                            <b>Отсортировано по дате</b>
                        <?php else: ?>
                            <b>Отсортировано по релевантности</b>&nbsp;|&nbsp;
                            <a href="<?php echo esc_url(add_query_arg('orderby', 'date', $base_url)); ?>">Сортировать по дате</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="search-no-results">
                    <p><strong>По вашему запросу ничего не найдено.</strong></p>
                    <p>Попробуйте изменить поисковый запрос или воспользуйтесь поиском выше.</p>
                    
                    <?php 
                    // Простые предложения
                    $search_term = get_search_query();
                    $suggestions = array();
                    
                    $medical_terms = array('врач', 'доктор', 'лечение', 'консультация', 'невролог', 'кардиолог', 'гинеколог');
                    
                    foreach ($medical_terms as $term) {
                        if (stripos($term, $search_term) !== false || stripos($search_term, $term) !== false) {
                            $suggestions[] = $term;
                        }
                    }
                    
                    if (!empty($suggestions)): 
                    ?>
                        <div class="search-suggestions">
                            <p><strong>Возможно, вы искали:</strong></p>
                            <?php foreach(array_slice($suggestions, 0, 3) as $suggestion): ?>
                                <a href="<?php echo esc_url(home_url('/?s=' . urlencode($suggestion))); ?>" class="search-suggestion-link">
                                    <?php echo esc_html($suggestion); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php wp_reset_postdata(); ?>
        </div>
    </div>
</div>

<style>
.search-page .search-item, 
.search-page input[type=text], 
.search-page input[type=submit], 
.ag-spage-clarify-item, 
.ag-spage-clarify-item:hover {
    border-color: #f3951d33 !important;
}

.search-page input[type=submit] {
    background-color: #f3951d !important;
    color: white;
    border: none;
    padding: 8px 16px;
    cursor: pointer;
}

.search-page .search-item.search-item-first {
    padding-top: 15px;
    border-top: none !important;
}

.search-page {
    margin-top: 50px;
    margin-bottom: 50px;
}

.search-page h1 {
    margin-bottom: 30px;
}

.search-page .search-preview {
    text-align: left;
    margin-right: 15px;
    flex-shrink: 0;
}

.search-page .search-preview img {
    max-width: 120px;
    height: auto;
    border-radius: 5px;
}

.search-page .search-title a {
    font-weight: bold;
    color: #333;
    text-decoration: none;
    font-size: 18px;
}

.search-page .search-title a:hover {
    color: #f3951d;
}

.search-page.theme-blue .search-title b {
    color: #f3951d;
}

.search-page .search-item {
    margin: 15px 0;
    padding: 15px 0px;
    border-bottom: 1px solid #eee;
    display: flex;
    align-items: flex-start;
    gap: 15px;
}

.search-description {
    flex: 1;
}

.search-previewtext {
    margin: 10px 0;
    color: #666;
    line-height: 1.5;
}

.search_page_paging {
    margin: 30px 0;
    text-align: center;
}

.search_page_paging .page-numbers {
    display: inline-block;
    padding: 8px 12px;
    margin: 0 2px;
    border: 1px solid #ddd;
    text-decoration: none;
    color: #333;
    border-radius: 3px;
}

.search_page_paging .page-numbers.current {
    background-color: #f3951d;
    color: white;
    border-color: #f3951d;
}

.search_page_paging .page-numbers:hover {
    background-color: #f5f5f5;
}

.search-title--service {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 10px;
}

.search__page--price {
    font-weight: bold;
    color: #f3951d;
    font-size: 18px;
    white-space: nowrap;
}

.search-propslist {
    margin-top: 10px;
    font-size: 14px;
}

.search-propsitem {
    margin-bottom: 5px;
}

.search-propsitem-name {
    font-weight: 600;
    color: #666;
}

.search-propsitem-value {
    color: #333;
}

.search-change-how {
    margin-top: 20px;
    text-align: center;
    font-size: 14px;
    padding: 15px;
    background-color: #f9f9f9;
    border-radius: 5px;
}

.search-change-how a {
    color: #f3951d;
    text-decoration: none;
}

.search-change-how a:hover {
    text-decoration: underline;
}

.search-no-results {
    text-align: center;
    padding: 40px 20px;
    background-color: #f9f9f9;
    border-radius: 5px;
    margin: 20px 0;
}

.search-suggestions {
    margin-top: 20px;
}

.search-suggestion-link {
    display: inline-block;
    margin: 5px;
    padding: 8px 12px;
    background-color: #f3951d;
    color: white;
    text-decoration: none;
    border-radius: 3px;
    font-size: 14px;
}

.search-suggestion-link:hover {
    background-color: #d63384;
    color: white;
}

/* Подсветка найденных слов */
mark {
    background-color: #fff3cd;
    padding: 2px 4px;
    border-radius: 3px;
    font-weight: 600;
}

.search-pagination-top {
    margin-bottom: 20px;
}

@media (max-width: 768px) {
    .search-page .search-item {
        flex-direction: column;
    }
    
    .search-page .search-preview {
        margin-right: 0;
        margin-bottom: 10px;
    }
    
    .search-title--service {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .search-page .search-title a {
        font-size: 16px;
    }
}
</style>

<?php get_footer(); ?>