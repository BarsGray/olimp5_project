<?php

new My_Best_Metaboxes;

class My_Best_Metaboxes {

	static $meta_key = 'rb_custom_choose_brand';

	public function __construct() {
		add_action( 'admin_print_footer_scripts', array( $this, 'show_assets' ), 10, 999 );

		// Save the fields values, using our callback function
		// if you have other taxonomy name, replace category with the name of your taxonomy. ex: edited_book, create_book
		add_action( 'edited_brand', array( $this, 'save_metabox' ), 10, 2 );
		add_action( 'create_brand', array( $this, 'save_metabox' ), 10, 2 );
		add_action( 'edited_ration_cat', array( $this, 'save_metabox' ), 10, 2 );
		add_action( 'create_ration_cat', array( $this, 'save_metabox' ), 10, 2 );
		add_action( 'edited_city', array( $this, 'save_metabox' ), 10, 2 );
		add_action( 'create_city', array( $this, 'save_metabox' ), 10, 2 );

		//add custom fields
		add_action( 'brand_add_form_fields', array( $this, 'add_metabox' ), 20, 2 );
		add_action( 'brand_edit_form_fields', array( $this, 'add_metabox' ), 20, 2 );
		add_action( 'ration_cat_add_form_fields', array( $this, 'add_metabox' ), 20, 2 );
		add_action( 'ration_cat_edit_form_fields', array( $this, 'add_metabox' ), 20, 2 );
		add_action( 'city_add_form_fields', array( $this, 'add_metabox' ), 20, 2 );
		add_action( 'city_edit_form_fields', array( $this, 'add_metabox' ), 20, 2 );
	}

	## Добавляет матабоксы
	// public function add_metabox() {
	// 	add_meta_box( 'box_info_company', 'Информация о компании', array( $this, 'render_metabox' ), $this->post_type, 'advanced', 'high' );
	// }

	## Отображает метабокс на странице редактирования поста
	public function add_metabox( $term ) {


		if( $term->term_id ) {
    
			    $rb_brand_similar_desc = get_term_meta($term->term_id, 'rb_brand_similar_desc', true);
			    $curr_similar_posts = get_term_meta( $term->term_id, self::$meta_key, true );
			    $curr_similar_block_title = get_term_meta( $term->term_id, 'rb_brand_similar_title', true );
			    $rb_custom_choose_desc = get_term_meta( $term->term_id, 'rb_custom_choose_desc', true );
			    $brands_list = get_terms( array( 'taxonomy' => 'brand', 'exclude' => $term->term_id ) );
			    $checkbox_val = get_term_meta( $term->term_id, 'rb_brand_similar_show', true ) ? 1 : 0;

			    if ( $term->taxonomy == 'ration_cat' ) {

				    $args = array(
				          'posts_per_page' => -1,
				          'post_type'   => 'ration',
				          'tax_query' => array(
				              array(
				                  'taxonomy' => 'ration_cat',
				                  'field'    => 'term_id',
				                  'terms'    => $term->term_id,
				              ),
				          ),
				          'fields' => 'ids'
				    );

				    $cat_posts  = get_posts($args);

				    $brands_list  = wp_get_object_terms ($cat_posts, 'brand');

			    } else if ( $term->taxonomy == 'city' ) {


				    $args = array(
				          'posts_per_page' => -1,
				          'post_type'   => 'ration',
				          'tax_query' => array(
				              array(
				                  'taxonomy' => 'city',
				                  'field'    => 'term_id',
				                  'terms'    => $term->term_id,
				              ),
				          ),
				          'fields' => 'ids'
				    );

				    $cat_posts  = get_posts($args);

				    $brands_list  = wp_get_object_terms ($cat_posts, 'brand');

			    }

		} else {

			$rb_brand_similar_desc = '';
		    $curr_similar_posts = '';
		    $curr_similar_block_title = '';
		    $rb_custom_choose_desc = '';
		    $brands_list = get_terms( array( 'taxonomy' => 'brand' ) );
		    $checkbox_val = '';

		}

		$curr_tab_count = $curr_similar_posts ? count( $curr_similar_posts ) : 1; 

	    $tab_pattern = '<li class="rb-similar-brand-tabs-item %s" data-tab="%s">
						<span class="rb-similar-tab">%s</span>
					</li>';

	    $brand_pattern = '<li class="rb-similar-brand-item %s" data-tab="%s">
	    					<div class="rb-similar-brand-item-desc">
	    						<textarea name="rb_custom_choose_desc[%s]" rows="5" id="rb_brand_tiny_%s" cols="50" class="rb-brand-item--desc" aria-describedby="description-description">%s</textarea>
	    					</div>
							<select name="rb_add_brand_to_choose[]">
								<option value="0">Выберите бренд</option>
								%s
							</select>
							%s
							<span class="dashicons dashicons-trash remove-similar-item"></span>
						</li>';

	    ?>
		    <table class="form-table company-info">

				<tr>
					<th>
						<h2>Похожие рационы</h2>
					</th>
					<td class="rb-brand-list rb-brand-list-first">
						<div>
							<p class="rb-similar-brand-show">
								<label>
									Выводить блок?
									<input type="hidden" class="rb_brand_similar_show" name="rb_brand_similar_show" value="0" />
									<input type="checkbox" class="rb_brand_similar_show" name="rb_brand_similar_show" <?php echo checked( $checkbox_val, 1, false ); ?> value="1" />
								</label>
							</p>
							<p>Заголовок
								<input type="text" name="rb_brand_similar_title" value="<?php echo $curr_similar_block_title; ?>" style="width:100%" />
							</p>
							<p>Описание</p>
								<textarea name="rb_brand_similar_desc" id="rb_brand_similar_desc"  style="width:100%" cols="30" rows="10"><?php echo esc_html( $rb_brand_similar_desc ); ?></textarea>
							

						</div>
						<p class="rb-similar-brand-title">Выберите похожий бренд, потом выберите рационы из этого бренда для вывода</p>
						<ul class="rb-similar-brand-tabs">
							<?php 

								if ( is_array( $curr_similar_posts ) ) {

									$i = 1;

									foreach ( $curr_similar_posts as $key => $val ) {

										$curr_active = ( $i == 1 ) ? 'tab-selected' : '';

										printf( $tab_pattern, $curr_active, $i, $i );

										$i++;
										
									}

								} else {

									printf( $tab_pattern, 'tab-selected', 1, 1 );

								}

							?>
							<li class="rb-similar-add-new-tab">
								<span class="dashicons dashicons-plus-alt rb-new-tab" data-currtab="<?php echo $curr_tab_count; ?>"></span>
							</li>
							
						</ul>
						<div class="rb-similar-brand-elements-wrap">
							<ul class="rb-similar-brand-elements">
								
								<?php 

									$elements = '';


									if ( is_array( $curr_similar_posts ) ) {

										$i = 1;

										foreach ( $curr_similar_posts as $similar_key => $similar_val ) { 
										//key - id бренда, val - массив с рационами

											$descr_i = $i-1;

											$curr_descr_val = $rb_custom_choose_desc[$descr_i];

											$curr_active = ( $i == 1 ) ? 'brand-item-show' : '';

											foreach( $brands_list as $brand_val => $brand_item  ) {

												$selected = ( $brand_item->term_id == $similar_key ) ? "selected" : '';

												$elements .= '<option value="' . $brand_item->term_id . '" ' . $selected . '>' . $brand_item->name . '</option>';

											}


											$second_elements  = '<div class="rb-brand-item--rations">';

											$args = array(
										        'post_type'       => 'ration',
										        'post_status'     => 'publish',
										        'tax_query'       => array(
										            array(
										              'taxonomy'  => 'brand',
										              'field'     => 'term_id',
										              'terms'     => array( $similar_key )
										            )
										        ),
										        'posts_per_page'  => -1,
										    );

										    $ration_posts = get_posts( $args );

											foreach( $ration_posts as $ration ) {

												$checked = in_array( $ration->ID, $similar_val ) ? "checked" : '';

												$second_elements  .= '<label>
																		<img width="50" height="auto" src="' . get_the_post_thumbnail_url( $ration->ID ) . '">
														              	<input type="checkbox" name="rb_custom_choose_brand[' . $similar_key . '][]" value="' . $ration->ID . '" ' . $checked . '>
														              	' . get_the_title( $ration->ID ) . '
														            </label>';

								            }

											$second_elements  .= '</div>';


											printf( $brand_pattern, $curr_active, $i, $descr_i, $descr_i, $curr_descr_val, $elements, $second_elements );

											$i++;
											
										}

									} else {

										foreach( $brands_list as $brand_val => $brand_item  ) {

											$elements .= '<option value="' . $brand_item->term_id . '" >' . $brand_item->name . '</option>';

										}
										
										printf( $brand_pattern, 'brand-item-show', 1, 0, 0, '',  $elements, '' );

									}

								?>
							</ul>
						</div>
					</td>
				</tr>

			</table>
			
	    <?php

	}



	## Очищает и сохраняет значения полей
	public function save_metabox( $term_id ) {

		if ( isset( $_POST[ self::$meta_key ] ) ) {

		    $brands = array_filter( $_POST[ self::$meta_key ] ); // уберем пустые поля

			update_term_meta( $term_id, self::$meta_key, $brands );

		}

		if ( isset( $_POST[ 'rb_custom_choose_desc' ] ) ) {

		    $descs = array_filter( $_POST[ 'rb_custom_choose_desc' ] ); // уберем пустые поля

			update_term_meta( $term_id, 'rb_custom_choose_desc', $descs );

		}

		if ( isset( $_POST[ 'rb_brand_similar_desc' ] ) ) {

			update_term_meta( $term_id, 'rb_brand_similar_desc', sanitize_text_field( $_POST[ 'rb_brand_similar_desc' ] ) );

		}


		if ( isset( $_POST[ 'rb_brand_similar_show' ] ) ) {

			update_term_meta( $term_id, 'rb_brand_similar_show', $_POST[ 'rb_brand_similar_show' ] );

		}

		if ( isset( $_POST[ 'rb_brand_similar_title' ] ) ) {

			update_term_meta( $term_id, 'rb_brand_similar_title', sanitize_text_field( $_POST[ 'rb_brand_similar_title' ] ) );

		}

	}

	## Подключает скрипты и стили
	public function show_assets() {
		if ( is_admin() && ( get_current_screen()->id == 'edit-brand' || get_current_screen()->id == 'edit-ration_cat' || get_current_screen()->id == 'edit-city' ) ) {
			$this->show_styles();
			$this->show_scripts();
		}
	}

	## Выводит на экран стили
	public function show_styles() {
		?>
		<style>
			.rb-similar-brand-tabs{
				display: flex;
				flex-wrap: wrap;
				margin-bottom: 0;
				background-color: #fff;
				padding: 12px;
				padding-bottom: 0;
			}
			.rb-similar-add-new-tab{
				margin-left: 3px;
				margin-top: 2px;
			}
			.rb-similar-brand-tabs-item{
				display: flex;
			    align-items: center;
			    border: 1px solid #e2e4e7;
			    margin: 0 8px 0 0;
			    background-color: #fbfbfc;
			    font-size: 13px;
			    cursor: pointer;
			    transition: background-color .1s linear,border-color .1s linear;
			    margin-bottom: -2px;
			}
			.rb-similar-brand-tabs-item.tab-selected{
				background-color: #fff;
    			border-bottom-color: #fff;
    			margin-bottom: -2px;
    			padding-top: 2px;
			}
			.rb-similar-tab{
				background: 0;
			    border: 0;
			    padding: 10px 12px;
			    margin: 0;
			    flex: 1;
			    cursor: pointer;
			    display: flex;
			    align-items: center;
			}
			.rb-similar-brand-elements{
				background-color: #fff;
				padding: 12px;
				margin-top: 0;
				border: solid #e2e4e7;
			    border-width: 1px 0 0 0;
			}
			.rb-similar-brand-item{
			    display: none;
			}
			.rb-similar-brand-item.brand-item-show{
			    display: flex;
			    justify-content: space-between;
			    flex-wrap: wrap;
			}
			.rb-similar-brand-item-desc{
				display: block;
				width: 100%;
			}
			.rb-brand-item--rations{
				width: 60%;
				display: flex;
				flex-wrap: wrap;
				/*height: 75px;
				overflow: scroll;*/
			}
			.rb-brand-item--desc{
				display: block;
				width: 100%;
				margin-bottom: 15px;
			}
			.rb-brand-item--rations label{
				width: 100%;
				margin-bottom: 7px;
				align-items: center;
				display: flex;
			}
			.rb-brand-item--rations label img{
				max-height: 50px;
				object-fit: contain;
				margin-right: 7px;
			}
			.rb-similar-brand-item select{
				width: 40%;
				height: 40px;
				margin-right: 20px;
			}
			.form-table td p.rb-similar-brand-show{
				margin-bottom: 20px;
				display: inline-block;
			}
			.form-table td p.rb-similar-brand-title{
				margin: 20px 0;
				display: inline-block;
			}
			.rb-new-tab {
				color: #00a0d2;
				cursor: pointer;
			}
			.rb-brand-list .item-address {
				display: flex;
				align-items: center;
			}
			.rb-brand-list .item-address input {
				width: 100%;
				max-width: 400px;
			}
			.remove-similar-item {
				color: brown;
				cursor: pointer;
			}

			#col-container .company-info tbody tr{
				display: flex;
				flex-direction: column;
			}
		</style>
		<?php
	}

	## Выводит на экран JS
	public function show_scripts() {
		?>
		<script>
			jQuery(document).ready(function ($) {

				// Добавляет новый таб
				$('body').on( 'click', '.rb-new-tab', function () {
					const listWrap = $('.rb-similar-brand-elements'),
						  listItem = listWrap.find('.rb-similar-brand-item').first().clone(),
						  tabsWrap = $('.rb-similar-brand-tabs'),
						  tabItem = tabsWrap.find('.rb-similar-brand-tabs-item').first().clone(),
						  tabNumber = Number($('.rb-new-tab').data('currtab'));



					$('.rb-similar-brand-item.brand-item-show').removeClass('brand-item-show');
		            $('.rb-similar-brand-tabs-item.tab-selected').removeClass('tab-selected');
		           	$('.rb-new-tab').data('currtab', tabNumber + 1 );

					listItem.find('select').val('0'); // чистим значение
					listItem.find('.rb-brand-item--rations').remove(); // чистим значение
					listWrap.append( listItem );
					// listItem[0].find('.rb-brand-item--desc');rb-similar-brand-item-desc
					listWrap.find('.rb-similar-brand-item').last().find('.rb-brand-item--desc').attr('name', 'rb_custom_choose_desc['+tabNumber+']').attr('id', 'rb_brand_tiny_' + tabNumber ).text('');//change name attr

					const newList = listWrap.find('.rb-similar-brand-item').last().find('.rb-brand-item--desc').attr('style', '').clone();

					listWrap.find('.rb-similar-brand-item').last().prepend( newList );

					listWrap.find('.rb-similar-brand-item').last().find('.rb-similar-brand-item-desc').remove();


					
				    if ( typeof( tinyMCE ) == "object") {
				        tinyMCE.init({
				            selector: '#rb_brand_tiny_' + tabNumber,
				        });
				    }
					 // id="rb_brand_tiny_%s"


					listWrap.find('.rb-similar-brand-item').last().addClass('brand-item-show').attr('data-tab', $('.rb-new-tab').data('currtab'));

					$('.rb-similar-add-new-tab').before( tabItem );
					tabsWrap.find('.rb-similar-brand-tabs-item').last().attr('data-tab', $('.rb-new-tab').data('currtab')).addClass('tab-selected').find('.rb-similar-tab').text( $('.rb-new-tab').data('currtab'));
					

				});

				jQuery(document).ready(function() {
				    if ( typeof( tinyMCE ) == "object") {
				        tinyMCE.init({
				            selector: '.rb-brand-item--desc'
				        });
				    }
				});

				// Удаляет бокс с вводом адреса фирмы
				$('body').on('click', '.remove-similar-item', function () {
					if ($('.rb-similar-brand-item').length > 1) {
						const currNumb = $(this).closest('.rb-similar-brand-item').data('tab');
						$(this).closest('.rb-similar-brand-item').remove();
						$('.rb-similar-brand-tabs-item[data-tab='+currNumb+']').remove();
						$('.rb-new-tab').data('currtab', Number( $('.rb-new-tab').data('currtab') ) - 1 );
					} else {
						$(this).closest('.rb-similar-brand-item').find('select').val('');
						$(this).closest('.rb-similar-brand-item').find('.rb-brand-item--rations').remove();
					}
				});

				//смена табов
				$('body').on('click', '.rb-similar-brand-tabs-item', function() {

					const currTabNumber = $(this).data('tab'),
						  tabsWrap = $('.rb-similar-brand-tabs');

					$('.rb-similar-brand-tabs-item.tab-selected').removeClass('tab-selected');
					tabsWrap.find('.rb-similar-brand-tabs-item').last().addClass('tab-selected');

					$('.rb-similar-brand-item.brand-item-show').removeClass('brand-item-show');
					$('.rb-similar-brand-item').each(function(){

						if( $(this).data('tab') == currTabNumber ){

							$(this).addClass('brand-item-show');

						}

					});

				});

				$('body').on('change', '.rb-similar-brand-item select', function () {
				

					const currTag = $(this),
						  dataSend = {
			              	action: 'rb_ajax_load_rations_for_brand',
			              	termid: $(this).val(),
			              	number:  $('.rb-new-tab').data('currtab')
		            	};


					$.ajax({
		              type: "POST",
		              url: "<?php echo admin_url('admin-ajax.php'); ?>",
		              data: dataSend,
		              dataType: 'json',
		              beforeSend: function() {
		                  // console.log( dataSend );
		              },
		              success: function (data) {

		              	if( data.result == 'success' ) {

		              		currTag.parent().find('.rb-brand-item--rations').remove();
		              		currTag.parent().find('.remove-similar-item').before(data.content);

		              	}

		              },
		              error: function (data2) {
		                console.log(data2);
		                console.log(data2.responseText);
		              }
		          });
		          return false;

				});

			});
		</script>
		<?php
	}

}

