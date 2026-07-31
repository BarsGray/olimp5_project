<?php 

get_header(); 

$categories = get_terms(array('taxonomy'=>'category'));

$curr_tax = get_queried_object();

/* Переменные для пагинации - 2 шт.*/
$paged = get_query_var('paged');
$current_page = max(1, get_query_var('paged'));

?>

        <!-- news start -->
        <div class="rb-news rb-page">
            <div class="rb-container">
                <div class="rb-page-header">
                    <h1><?php echo $curr_tax->name;//get_the_title( get_option( 'page_for_posts' ) ); ?></h1>
                </div>
				
				<ul class="rb-programms__category flex-wrap">
					<?php
					if ( $categories && ! is_wp_error( $categories ) ) :
						$current_cat_id = get_queried_object_id(); // Получаем ID текущей категории

						foreach ( $categories as $categories_item ) :
							$is_active = $categories_item->term_id === $current_cat_id;
							?>
							<li class="<?php echo $is_active ? 'rb-programms__curr' : ''; ?>">
								<a href="<?php echo esc_url( get_term_link( $categories_item ) ); ?>">
									<?php echo esc_html( $categories_item->name ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					<?php endif; ?>
				</ul>

				<?php if( have_posts() ): ?>

				<ul class="flex-wrap rb-news-list">

					<?php while(  have_posts() ):
						the_post();
					?>

					<li class="rb-news-item flex-wrap">
						<div class="col-6 col-s-12 rb-news-item-info">
							<span class="rb-news-item-date"><?php //echo $arItem["DISPLAY_ACTIVE_FROM"]; ?></span>
							<h2 class="rb-news-item-title"><?php the_title(); ?></h2>
							<p class="rb-news-item-text"><?php the_excerpt(); ?></p>
							<div class="rb-news-item-bot flex">
								<a href="<?php the_permalink(); ?>" class="rb-button__grey rb-news-item-link">
									Подробнее
									<svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M0.875 0.75L7.125 7L0.875 13.25" stroke="#717171" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
									</svg>
								</a>
							</div>
						</div>
						<div class="col-6 col-s-12 rb-news-item-img">
							<a href="<?php the_permalink(); ?>">
								<picture class="rb-news-item-picture">
									<?php 
									    echo wp_get_attachment_image(
														get_post_thumbnail_id(),
														'rb_standart_2x',
														false,
														array('loading' => false)
                          );
										// echo get_the_post_thumbnail(get_the_ID(), 'full');
									?>
								</picture>
							</a>
						</div>
					</li>
					
				<?php endwhile; ?>
					
				</ul>
				
                <?php 
				    $max_page = $wp_query->max_num_pages;
					// paginate_links() — функция WordPress для формирования ссылок пагинации
					$pagination_links = paginate_links(array(
						// В шаблоне 'base' функция paginate_links() заменит заглушку '%#%' на реальный номер страницы при генерации ссылок
						'base'      => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
						'format'    => '',
						'total'     => $max_page,
						'current'   => $current_page,
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
						// Ищем совпадение - подстроку в строке для идентификации ссылки
						if (strpos($link, 'id="pagination-r-l"') !== false) {
							$prev_link = $link;
						} elseif (strpos($link, 'id="pagination-r-ll"') !== false) {
							$next_link = $link;
						} elseif (strpos($link, 'class="dots"') !== false) {
							// пропуск многоточия
						} else {
							$number_links[] = $link; // Формируем массив ссылок без многоточия
						}
					}

					// Очищаем массив $number_links, удаляя элементы c подстрокой '''class="dots"''', оставляя только true
					$number_links = array_filter($number_links, function($link) {
						return strpos($link, 'class="dots"') === false;
					});
				    // Пересортируем индексы, чтобы не было пропусков после очистки
					$number_links = array_values($number_links); 

					// Получаем номера страниц из ссылок
					$page_nums = [];
					foreach ($number_links as $link) {
						if (preg_match('/href=["\'][^"\']*page\/(\d+)\/?["\']/', $link, $matches)) {
							$page_nums[] = intval($matches[1]); // добавляем извлеченное число в массив
						} else {
							// если не нашли номер страницы из ссылок
							$page_nums[] = null;
						}
					}		

					$current_index = $current_page; 

					$total_pages = count($page_nums);
				
					$max_visible = 5; // максимальное число отображаемых ссылок для десктопа

					// Выбираем диапазон ссылок
					
					if ($total_pages <= $max_visible) {
					$visible_links = $number_links;
					} else {
						// Центрируем диапазон вокруг текущей страницы
						$start_index = max(0, min($current_index - floor($max_visible / 2) - 1, $total_pages - $max_visible));
						// Получаем срез массива от $start_index до $max_visible с переиндексацией ключей по умолчанию
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
								$prev_url = esc_url(get_pagenum_link($prev_page)); // функция WordPress, которая возвращает корректный URL пагинации для страницы с номером $prev_page
							}

							echo '<a class="pagination-r-l" href="' . $prev_url . '">
								<svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M7.5 1L1 7L7 13" stroke="#121212"></path>
								</svg>
							</a>';
       					} else {
            			// Неактивная стрелка, если мы на 1-й странице
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
						// Выводим номера страниц пагинации
						foreach ($visible_links as $link) {
							echo $link;
						}
					?>
    			    </div>

				    <div class="pagination-r-arrows-right">
        		    <?php 
						if ($current_page < $max_page) {
							// Одиночная стрелка "вперед" на следующую страницу
							$next_page = min($total_pages, $current_page + 1);
							echo '<a class="pagination-r-l" href="' . esc_url(trailingslashit(get_category_link(get_queried_object_id())) . 'page/' . $next_page . '/') . '">
								<svg width="9" height="14" viewBox="0 0 9 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L7.5 7L1.5 13" stroke="#121212"></path></svg>
							</a>';
						} else {
							// Неактивная стрелка, если мы на последней странице
							echo '<span class="pagination-r-l disabled">
								<svg width="9" height="14" viewBox="0 0 9 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L7.5 7L1.5 13" stroke="#121212"></path></svg>
							</span>';
						}

						// Двойная стрелка "в конец" всегда на последнюю страницу
						$disabled_class = ($current_page >= $max_page) ? ' disabled' : '';
						echo '<a class="pagination-r-ll' . $disabled_class . '" href="' . esc_url(trailingslashit(get_category_link(get_queried_object_id())) . 'page/' . $max_page . '/') . '">
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
				
				<?php else: ?>
				<div class="montain" style="">
					<div class="montain__wrapper">
						Раздел пуст
					</div>
				</div>
				<?php endif; ?>

			</div>
		</div>

<?php get_footer(); ?>