<?php


/*Hide admin bar*/
// add_filter( 'show_admin_bar', '__return_true' );


/**
 * Disable Editor
 *
**/

/**
 * Templates and Page IDs without editor
 *
 */
function rb_disable_editor( $id = false ) {

	$excluded_templates = array(
		'template-page/page-main.php',
    	'template-page/page-contacts.php',
    	'template-page/page-documents.php',
    	'template-page/page-partners.php',
    	'template-page/page-sert.php',
    	'template-page/page-links.php',
    	'template-page/page-psycology.php',
	);

	$excluded_ids = array(
		// get_option( 'page_on_front' )
	);

	if( empty( $id ) )
		return false;

	$id = intval( $id );
	$template = get_page_template_slug( $id );

	return in_array( $id, $excluded_ids ) || in_array( $template, $excluded_templates );
}

/**
 * Disable Gutenberg by template
 *
 */
function rb_disable_gutenberg( $can_edit, $post_type ) {

	if( ! ( is_admin() && !empty( $_GET['post'] ) ) )
		return $can_edit;

	if( rb_disable_editor( $_GET['post'] ) )
		$can_edit = false;

	return $can_edit;

}
add_filter( 'gutenberg_can_edit_post_type', 'rb_disable_gutenberg', 10, 2 );
add_filter( 'use_block_editor_for_post_type', 'rb_disable_gutenberg', 10, 2 );

/**
 * Disable Classic Editor by template
 *
 */
function rb_disable_classic_editor() {

	$screen = get_current_screen();
	if( 'page' !== $screen->id || ! isset( $_GET['post']) )
		return;

	if( rb_disable_editor( $_GET['post'] ) ) {
		remove_post_type_support( 'page', 'editor' );
	}

}
add_action( 'admin_head', 'rb_disable_classic_editor' );



function rb_get_doctors_occup() {

    $occup = get_post_meta( get_the_ID(), '_rb_doc_occup', true );

    return $occup;
}

function rb_register_custom_yoast_variables() {
    wpseo_register_var_replacement('%%rb_get_doctors_occup%%', 'rb_get_doctors_occup', 'advanced', 'Вывести должность ljrnjhf');
}
add_action('wpseo_register_extra_replacements', 'rb_register_custom_yoast_variables');



add_shortcode( 'rb_form_bot', function( $atts ) {

	$atts = shortcode_atts( array(
		'title' => 'Записаться на прием',
		'subtitle' => 'Вы можете записаться на прием в центр культуры здоровья Олимп Пять',
		'desc' => 'Записаться на прием к специалисту можно заполнив форму на нашем сайте.Укажите ваше имя, контактный номер телефона и выберите интересующую вас услугу. Мы свяжемся с вами в ближайшее время для подтверждения времени и даты приема.',
		'form_desc' => 'Опишите, чем мы можем вам помочь?',
		'service' => '',
		'servicestax' => '',
		'doctors' => '',
		'programms' => ''
	), $atts );

    ob_start();

    ?>
    <section class="rb-main__form-bottom" id="form">
			<div class="rb-container flex rb-main__form-bottom-text">
				<div class="col-6 col-m-12 col-s-12">
					<div class="rb-main__form-bottom--info">
						<span class="rb-main__form-bottom--subtitle">
							<?php echo $atts['title']; ?>
						</span>
						<p class="rb-main__form-bottom--title">
							<?php echo $atts['subtitle']; ?>
						</p>
						<p class="rb-main__form-bottom--text">
							<?php echo $atts['desc']; ?>
						</p>
					</div>
				</div>
				<div class="col-6 col-m-12 col-s-12">
					<div class="rb-main__form-bottom--wrap">
					    <form action="" method="POST" class="rb-main__form" id="rb-main-bot">
							<input name="rb_popup_nonce" value="<?php echo wp_create_nonce( "rbPopupNonce" ); ?>" type="hidden">
						    <input type="hidden" name="action" value="rb_popup_form">
						    <input type="hidden" name="curr_url" value="<?php echo site_url() . $_SERVER['REQUEST_URI']; ?>">
								<span class="rb-main__form--title">Форма записи на прием</span>


								<div class="rb-main__form-bottom--inputs flex">
									<div class="rb-main__form--item col-6 col-m-6 col-s-12">
										<label for="rb-top-form-name">Ваше ФИО</label>
										<input id="rb-top-form-name" type="text" name="user_name" value="<?//$arResult["AUTHOR_NAME"]?>" placeholder="Иванов Иван Иванович" autocomplete="off" required>
									</div>

									<div class="rb-main__form--item col-6 col-m-6 col-s-12">
										<label for="rb-top-form-phone">Номер телефона</label>
										<input id="rb-top-form-phone" type="text" name="user_phone" class="PHONE_MASK" value="<?//$arResult["AUTHOR_PHONE"]?>" placeholder="+7" autocomplete="off" required>
										<input type="text" name="message" value="" placeholder="Сообщение" autocomplete="off">
									</div>
								</div>
								<div class="col-12 rb-main__form--item">
									<label for="rb-top-form-message">Сообщение</label>
									<textarea id="rb-top-form-message" type="text" rows="5" name="user_msg" placeholder="<?php echo $atts['form_desc']; ?>" autocomplete="off" required></textarea>
								</div>

								<input type="hidden" name="user_service" value="<?php echo $atts['service']; ?>">
								<input type="hidden" name="user_servicestax" value="<?php echo $atts['servicetax']; ?>">
								<input type="hidden" name="user_doctors" value="<?php echo $atts['doctors']; ?>">
								<input type="hidden" name="user_programms" value="<?php echo $atts['programms']; ?>">
								
								<div class="col-12 rb-main__form--button">
										<input type="submit" name="submit" value="Отправить заявку">
								</div>
		                        <div class="form__privacy">
		                           <p>В соответствии с Федеральным законом № 152-ФЗ «О персональных данных» от 27.07.2006, отправляя любую форму на этом сайте, вы подтверждаете свое <a target="_blank" href="<?php echo wp_get_attachment_url( $policy ); ?>">согласие на обработку персональных данных</a>.</p>
		                        </div>
						</form>
					</div>
				</div>
			</div>
		</section>
  <?php
   $out = ob_get_contents();
   ob_end_clean();

   return $out;

});


// function rb_course_post_link( $post_link, $id = 0 ){
//     $post = get_post($id);  
//     if ( $post->post_type != 'programms' ) {
//     	return;
//     }
//     if ( is_object( $post ) ){
//         $terms = wp_get_object_terms( $post->ID, 'programms-type' );
//         if( $terms ){
//             return str_replace( 'programms-type' , $terms[0]->slug , $post_link );
//         }
//     }
//     return $post_link;  
// }
// add_filter( 'post_type_link', 'rb_course_post_link', 1, 3 );


//фильтрация

add_filter( 'manage_center-services_posts_columns', 'rb_center_services_columns' );

function rb_center_services_columns( $column_array ) {

	$column_array['update'] 	= 'Обновление';

	return $column_array;
}


add_filter( 'manage_edit-center-services_sortable_columns', 'rb_make_column_sortable' );

function rb_make_column_sortable( $columns ) {

    $columns['update'] 	= '_rb_serv_update_date'; //название параметра в url

    return $columns;
}

add_action( 'pre_get_posts', 'rb_sort_enter_services_custom_column_query' );

function rb_sort_enter_services_custom_column_query( $query ) {

      //проверяем, что мы в админке и тип записи тот, что нам нужен
      if ( ! is_admin() && $query->get( 'post_type' ) != 'center-services' ) {

        return;

      }

	  if ( isset( $_GET[ 'orderby' ] ) && $_GET[ 'orderby' ] ) {

	      $query->set( 'meta_key', $_GET[ 'orderby' ] );
		  $query->set( 'orderby', 'meta_value_num' );

	  }

}


//услуги
add_filter( 'manage_service_posts_columns', 'rb_service_columns' );

function rb_service_columns( $column_array ) {

	$column_array['update'] 	= 'Обновление';
	$column_array['prev_price'] 	= 'Старая цена';
	$column_array['curr_price'] 	= 'Текущая цена';

	return $column_array;
}



add_filter( 'manage_edit-service_sortable_columns', 'rb_make_service_column_sortable' );

function rb_make_service_column_sortable( $columns ) {

    $columns['update'] 	= '_rb_serv_update_date'; //название параметра в url
    $columns['prev_price'] 	= '_rb_serv_prev_price'; //название параметра в url
    $columns['curr_price'] 	= '_rb_serv_curr_price'; //название параметра в url

    return $columns;
}

add_action( 'pre_get_posts', 'rb_sort_service_custom_column_query' );

function rb_sort_service_custom_column_query( $query ) {

      //проверяем, что мы в админке и тип записи тот, что нам нужен
      if ( ! is_admin() && $query->get( 'post_type' ) != 'service' ) {

        return;

      }

	  if ( isset( $_GET[ 'orderby' ] ) && $_GET[ 'orderby' ] ) {

	      $query->set( 'meta_key', $_GET[ 'orderby' ] );
		  $query->set( 'orderby', 'meta_value_num' );

	  }

}



add_action( 'manage_posts_custom_column', 'rb_center_services_columns_populate', 10, 2 );

function rb_center_services_columns_populate( $columns, $post_id ) {

	switch( $columns ) :
		case 'update': {
			$upd_date = get_post_meta( $post_id, '_rb_serv_update_date', true );
			echo ($upd_date) ? date( 'd-m-Y', $upd_date ) : '-';
			break;
		}
		case 'prev_price': {
			$prev_price = get_post_meta( $post_id, '_rb_services_prev_price', true );
			echo ($prev_price) ? $prev_price : '-';
			break;
		}
		case 'curr_price': {
			$prev_price = get_post_meta( $post_id, '_rb_services_price', true );
			echo ($prev_price) ? $prev_price : '-';
			break;
		}
	endswitch;

}


//акции
add_filter( 'manage_promo_posts_columns', 'rb_promo_columns' );

function rb_promo_columns( $column_array ) {

	$column_array['rb_period'] 	= 'Время действия';

	return $column_array;
}


add_filter( 'manage_edit-promo_sortable_columns', 'rb_make_promo_column_sortable' );

function rb_make_promo_column_sortable( $columns ) {

    $columns['rb_period'] 	= '_rb_time'; //название параметра в url

    return $columns;
}

add_action( 'pre_get_posts', 'rb_sort_promo_custom_column_query' );

function rb_sort_promo_custom_column_query( $query ) {

      //проверяем, что мы в админке и тип записи тот, что нам нужен
      if ( ! is_admin() && $query->get( 'post_type' ) != 'promo' ) {

        return;

      }

	  if ( isset( $_GET[ 'orderby' ] ) && $_GET[ 'orderby' ] ) {

	      $query->set( 'meta_key', $_GET[ 'orderby' ] );
		  $query->set( 'orderby', 'meta_value_num' );

	  }

}



add_action( 'manage_posts_custom_column', 'rb_promo_columns_populate', 10, 2 );

function rb_promo_columns_populate( $columns, $post_id ) {

	switch( $columns ) :
		case 'rb_period': {
			$rb_period = get_post_meta( $post_id, '_rb_time', true );
			echo ($rb_period) ? $rb_period : '-';
			break;
		}
	endswitch;

}



add_action( 'pre_get_posts', 'rb_exclude_actions_from_query' );

function rb_exclude_actions_from_query( $query ) {

      //проверяем, что мы в админке и тип записи тот, что нам нужен
      if ( is_admin() && $query->get( 'post_type' ) != 'post' ) {

        return;

      }

	  if ( ! is_admin() && $query->is_category() && is_page_template( '/template-page/page-promo.php' ) ) {

		$pages = get_pages(
			array(
		    	'meta_key' => '_wp_page_template',
			    'meta_value' => 'template-page/page-promo.php'
			)
		);

	  	if ( function_exists( 'carbon_get_post_meta' ) ) {

	  		$exclude_ids = carbon_get_post_meta( $pages[0]->ID, 'rb_actions' );

	  		if ( $exclude_ids ) {

		        $exclude_ids = array_map( function( $item ){ return $item['id']; }, $exclude_ids );

	  			$query->set( 'post__not_in', $exclude_ids );

	  		}

	  	}

	  }

}



//edit roles


function rb_editor_caps(){
  // global $pagenow;
  $role = get_role( 'editor' ); // к примеру возьмем роль автора
  // $role = new WP_User( $user_id ); таким образом мы можем взять конкретного пользователя
  // if ( 'themes.php' == $pagenow && isset( $_GET['activated'] ) ){ // если тема была активирована
    $role->add_cap( 'install_plugins' ); 
    $role->add_cap( 'update_plugins' ); 
    $role->add_cap( 'activate_plugins' ); 
  // } else { // если тема деактивирована
  //   $role->remove_cap( 'edit_others_posts' ); 
  // }
}
 
add_action( 'after_setup_theme', 'rb_editor_caps' ); // вешаем функцию на хук


function rb_remove_editor() {
    if (isset($_GET['post'])) {
        $id = $_GET['post'];
        $template = get_post_meta($id, '_wp_page_template', true);
        switch ($template) {
            case 'template-page/page-service.php':
            // the below removes 'editor' support for 'pages'
            // if you want to remove for posts or custom post types as well
            // add this line for posts:
            // remove_post_type_support('post', 'editor');
            // add this line for custom post types and replace 
            // custom-post-type-name with the name of post type:
            // remove_post_type_support('custom-post-type-name', 'editor');
            remove_post_type_support('page', 'editor');
            break;
            default :
            // Don't remove any other template.
            break;
        }
    }
}
add_action('init', 'rb_remove_editor');