<?php 
get_header();

$curr_obj = get_queried_object();

/* Переменные для пагинации - 2 шт.*/
$per_page = 6;
$current_page = isset($_GET['pg']) ? max(1, intval($_GET['pg'])) : 1;

$promo_cat = get_terms( 'promo-cat', array( 'hide_empty' => 0 ) );

$promo_page = get_pages(
	array(
    	'meta_key' => '_wp_page_template',
	    'meta_value' => 'template-page/page-promo.php'
	)
);

$promo = new WP_Query(array(
    'post_type'      => 'promo',
    'posts_per_page' => $per_page,
    'paged'          => $current_page,
    'tax_query' => array(
        array(
            'taxonomy' => 'promo-cat',
            'field'    => 'slug',
            'terms'    => $curr_obj->slug
        )
    )
));
?>

<div class="rb-promo rb-page">
    <div class="rb-container">
<div class="rb-page-header">
    <h1><?php 
        $current_page = rb_get_current_page_number();
        
        if ($current_page > 1) {
            $h1_tpl = get_term_meta($curr_obj->term_id, RB_TERM_PAGED_H1_TPL_KEY, true);
            echo !empty($h1_tpl) ? esc_html(rb_render_tpl_for_term($h1_tpl, $curr_obj)) : esc_html($curr_obj->name);
        } else {
            echo esc_html($curr_obj->name);
        }
    ?></h1>
</div>
		
		<ul class="rb-programms__category flex">
                    <li><a href="<?php echo get_the_permalink( $promo_page[0]->ID ); ?>">Все</a></li>
                    <?php 
                        if ( $promo_cat ) : 
                            foreach ( $promo_cat as $promo_cat_item ) :

                                $rb_hide_cat = get_term_meta( $promo_cat_item->term_id, '_rb_hide_cat', true );
                                if( $promo_cat_item->count > 0 && $rb_hide_cat != 'yes' ) :
                                    ?>
                                        <li class="<?php if ( $curr_obj->term_id == $promo_cat_item->term_id  ) { echo 'rb-programms__curr'; }?>">											  <a href="<?php echo get_term_link( $promo_cat_item->term_id ); ?>"><?php echo $promo_cat_item->name; ?></a></li>
                                    <?php
                                endif;
                            endforeach;  
                        endif; 
                    ?>
        </ul>
		
        <div class="rb-programms__list-wrap flex-wrap">
            <ul class="rb-programms__list flex-wrap">
                <?php 
                    while( $promo->have_posts() ) {
                        $promo->the_post();
                        get_template_part( 'template-part/promo', 'item' );
                    }
                ?>
            </ul>
			
			<?php
			    
				$max_page = $promo->max_num_pages;

				$pagination_links = paginate_links(array(
					'base' => get_term_link(get_queried_object()->term_id) . '?pg=%#%',
					'format' => '',
					'total' => $max_page,
					'current' => $current_page,
					'end_size' => 4,
					'mid_size'  => 4,
					'prev_text' => '<div id="pagination-r-l" class="pagination-r-l"><svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.5 1L1 7L7 13" stroke="#121212"></path></svg></div>',
					'next_text' => '<div id="pagination-r-ll" class="pagination-r-l"><svg width="9" height="14" viewBox="0 0 9 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L7.5 7L1.5 13" stroke="#121212"></path></svg></div>',
					'type'      => 'array',
				));
			
			    // Страховка на случай, если вернет false
				if (!$pagination_links) {
					$pagination_links = [];
				}

				$prev_link = '';
				$next_link = '';
				$number_links = [];

				foreach ($pagination_links as $link) {
					if (strpos($link, 'id="pagination-r-l"') !== false) {
						$prev_link = $link;
					} elseif (strpos($link, 'id="pagination-r-ll"') !== false) {
						$next_link = $link;
					} elseif (strpos($link, 'class="dots"') !== false) {
						// пропуск многоточия
					} else {
						$number_links[] = $link; // Формируем массив только из нумерованных ссылок пагинации
					}
				}

				// Убираем оставшиеся "многоточия" из массива ссылок
				$number_links = array_filter($number_links, function($link) {
					return strpos($link, 'class="dots"') === false;
				});
				$number_links = array_values($number_links); // пересортировка индексов

				// Получаем номера страниц из ссылок
				$page_nums = [];

				foreach ($number_links as $link) {
					if (preg_match('/href=["\'][^"\']*\?pg=(\d+)[^"\']*["\']/', $link, $matches)) {
						$page_nums[] = intval($matches[1]); // добавляем извлеченное число в массив
					} else {
						// не нашли номер страницы
						$page_nums[] = $link;
					}
				}		
			
				$current_index = $current_page;

				$total_pages = count($page_nums);
			
				$max_visible = 5; // максимальное число отображаемых ссылок

				// Выбираем диапазон ссылок
				if ($total_pages <= $max_visible) {
					$visible_links = $number_links;
				} else {
					// Центрируем диапазон вокруг текущей страницы
					$start_index = max(0, min($current_index - floor($max_visible / 2) - 1, $total_pages - $max_visible));
					$visible_links = array_slice($number_links, $start_index, $max_visible);
				}
				
			?>

			<div class="pagination-r">
   				<div class="pagination-r-arrows-left">
        			<?php 
						
						$category_url = esc_url(get_category_link(get_queried_object_id()));
						
        				// Двойная стрелка "в начало" - всегда на первую страницу
       					$disabled_class = ($current_page <= 1) ? ' disabled' : ''; // если нет пагинации, добавим класс disabled
        				echo '<a class="pagination-r-ll' . $disabled_class . '" href="' . $category_url . '">
							<svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M7.5 1L1 7L7 13" stroke="#121212"></path>
							</svg>
							<svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M7.5 1L1 7L7 13" stroke="#121212"></path>
							</svg>
        				</a>';
        
        				// Одиночная стрелка "назад" - на предыдущую страницу
        				if ($current_page > 1) {
        				    $prev_page = max(1, $current_page - 1);
							if ($prev_page == 1) {
								$prev_url = $category_url; // заменяем дублирующий url, если это 1-я страница пагинации
							} else {
								$prev_url = esc_url(get_pagenum_link($prev_page));
							}
			

							echo '<a class="pagination-r-l" href="' . $prev_url . '">
								<svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M7.5 1L1 7L7 13" stroke="#121212"></path>
								</svg>
							</a>';
       					} else {
            			// Неактивная стрелка, если мы на первой странице
            			echo '<span class="pagination-r-l disabled">
                			<svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                  				<path d="M7.5 1L1 7L7 13" stroke="#121212"></path>
                			</svg>
            			</span>';
        				}
        			?>
    			    </div>
														 
    			    <div class="pagination-list">
					<?php 
						foreach ($visible_links as $link) {
							echo $link;
						}
					?>
    			    </div>
				
				    <div class="pagination-r-arrows-right">
        		    <?php 
        				
						if ($current_page < $max_page) {
							// Одиночная стрелка "вперед" - на следующую страницу
							$next_page = min($max_page, $current_page + 1);
							echo '<a class="pagination-r-l" href="' . esc_url(trailingslashit(get_term_link(get_queried_object()->term_id)) . '?pg=' . $next_page) . '">
								<svg width="9" height="14" viewBox="0 0 9 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L7.5 7L1.5 13" stroke="#121212"></path></svg>
							</a>';
						} else {
							// Неактивная стрелка, если мы на последней странице
							echo '<span class="pagination-r-l disabled">
								<svg width="9" height="14" viewBox="0 0 9 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L7.5 7L1.5 13" stroke="#121212"></path></svg>
							</span>';
						}
						
                        // Двойная стрелка на последнюю страницу
						$disabled_class = ($current_page == $max_page) ? ' disabled' : '';	
						echo '<a class="pagination-r-ll' . $disabled_class . '" href="' . esc_url(trailingslashit(get_term_link(get_queried_object()->term_id)) . '?pg=' . $max_page) . '">
							<svg width="9" height="14" viewBox="0 0 9 14" fill="none" xmlns="http://www.w3.org/2000/svg">
							  <path d="M1 1L7.5 7L1.5 13" stroke="#121212"></path>
							</svg>
							<svg width="9" height="14" viewBox="0 0 9 14" fill="none" xmlns="http://www.w3.org/2000/svg">
							  <path d="M1 1L7.5 7L1.5 13" stroke="#121212"></path>
							</svg>
						</a>';
    				?>
    			    </div>
					
				</div>

			</div>
		</div>
	</div>

<!--?php //get_template_part( 'template-part/forms/form', 'subscribe' ); ? --> 

<?php get_footer(); ?>
