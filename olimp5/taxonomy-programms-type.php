<?php 

get_header();

$curr_obj = get_queried_object();

$term_image = get_term_meta( $curr_obj->term_id, '_rb_service_image', true );
$rb_service_image_mob = get_term_meta( $curr_obj->term_id, '_rb_service_image_mob', true );
$rb_service_desc = get_term_meta( $curr_obj->term_id, '_rb_service_desc', true ) ?: term_description( $curr_obj->term_id );
$rb_service_info_title = get_term_meta( $curr_obj->term_id, '_rb_service_info_title', true );
$rb_service_info_desc = get_term_meta( $curr_obj->term_id, '_rb_service_info_desc', true );

?>

<div class="rb-service rb-page">

	<div class="rb-container">

	    <!-- service start -->
	    <section class="rb-service__top flex-wrap">
            <div class="col-6 col-s-12 rb-service__top-text rb-page-header">
                <h1><?php echo $curr_obj->name; ?></h1>

                <picture class="col-6 col-s-12 rb-service__top-img-mob">
                	<?php echo wp_get_attachment_image( $rb_service_image_mob, 'medium', '', array( 'class' => 'rb-img-cover' ) ); ?>
                </picture>
                <div class="rb-text rb-service__top-desc">
                	<?php echo apply_filters( 'the_content', $rb_service_desc ); ?>
                </div>
                <div class="rb-service__top-link rb-button__orange-bg js-open-modal" data-modal="1">
                    Записаться на прием
                </div>
            </div>
			<?php if( $term_image ) : ?>
                <picture class="col-6 col-s-12 rb-service__top-img">
                    <?php echo wp_get_attachment_image( $term_image, 'large', '', array( 'class' => 'rb-img-cover' ) ); ?>
                </picture>
            <?php endif; ?>
	    </section>

	    <section class="rb-service__mid">
	        
            <div class="col-6 col-m-12">
                <span class="rb-service__mid-title rb-title">
                	<?php echo esc_html( $rb_service_info_title ); ?>
                </span>
                <div class="rb-service__mid-text">
                    <?php echo apply_filters( 'the_content', $rb_service_info_desc ); ?>
                </div>
            </div>
	            
	    </section>
	    <!-- service end -->



	    <!-- types start -->
	    <?php 
	    	if ( function_exists( 'carbon_get_term_meta' ) ) {
	    		$popular_services = carbon_get_term_meta( $curr_obj->term_id, 'rb_services_popular' ) ? array_map( function( $val ){ return $val['id']; }, carbon_get_term_meta( $curr_obj->term_id, 'rb_services_popular' ) ) : '';	
	    	}
	    	
	    	if ( $popular_services ) :

	    		$popular_services = new WP_Query(
	    			array(
	    				'post_type' => 'services-item',
	    				'post__in' => $popular_services
	    			)
	    		);

	    		if ( $popular_services->have_posts() ) : 
	    			?>
				    <section class="rb-service__popular">
				    	<h2 class="rb-title">Популярные услуги</h2>
				    	<ul class="rb-service__list flex-wrap">
				    		<?php 
				    			while( $popular_services->have_posts() ) {
				    				$popular_services->the_post();

				    				get_template_part( 'template-part/service', 'item' ); 

				    			}
				    		?>
				    	</ul>
				    </section>
					<?php 
				endif; 
				wp_reset_postdata();
			endif; 
			$all_services = new WP_Query(
	    			array(
	    				'post_type' => 'services-item',
	    				'tax_query' => array(
	    					array(
	    						'taxonomy' => 'services',
	    						'field'	   => 'term_id',
	    						'terms'    => $curr_obj->term_id

	    					)
	    				)
	    			)
	    		);

	    	if ( $all_services->have_posts() ) :
	    		?>		
				<section class="rb-service__all">
			        <h2 class="rb-title">Все услуги направления</h2>
			        <ul class="rb-service__list flex-wrap">
		                    <?php 
				    			while( $all_services->have_posts() ) {
				    				$all_services->the_post();

				    				get_template_part( 'template-part/service', 'item' ); 

				    			}
				    		?>
			        </ul>
			    </section>
			    <!-- types end -->


	    <?php 
	    	endif;
	    	wp_reset_postdata();

			if( function_exists( 'carbon_get_term_meta' ) ) {
				$equip_list = carbon_get_term_meta( $curr_obj->term_id, 'rb_services_equip' );
			}

			if ( $equip_list ) :

		?>
			<div class="rb-container rb-service__equipment " id="oborudovanie">
				<?php
					
					foreach( $equip_list as $equip_list_item ) {

						get_template_part( 'template-part/equip', 'item', array( 'item' => $equip_list_item ) );

					}

				?>
			</div>
		<?php
			endif;
	    	

	    	$specialists = $arResult['PROPERTIES']['SPECIALISTS']['VALUE'];

	    	if ( $specialists ) : 

	    ?>

			    <!-- specialists start -->
			    <section class="rb-service__specialists">
			        <h2 class="rb-title">Специалисты направления</h2>

		            <ul class="rb-service__list flex-wrap">

						<?php

		                    
		                    $arData = array();
		                    $arFilter = Array(
		                        "IBLOCK_ID" => 5,
		                        "ID" => $specialists,
		                        "ACTIVE" => "Y",
		                        "PROPERTY_ORDER" =>"Y"
		                    );
		                    $arSort = Array("NAME"=>"ASC");
		                    $resList = CIBlockElement::GetList($arSort, $arFilter);
		                    while($obList = $resList->GetNextElement()){
		                        $arListFields = $obList->GetFields();

		                        // echo $arListFields["NAME"];
		                        //запись данных по ключам элементов
		                        $arData[] = $arListFields["ID"];
		                    }
		                    
		                    foreach($arData as $doctor_id):

		                    	$APPLICATION->IncludeComponent(
		                            "bitrix:news.detail",
		                            "specialist_for_service",
		                            Array(
		                                "ACTIVE_DATE_FORMAT" => "d.m.Y",
		                                "ADD_ELEMENT_CHAIN" => "N",
		                                "ADD_SECTIONS_CHAIN" => "Y",
		                                "AJAX_MODE" => "N",
		                                "AJAX_OPTION_ADDITIONAL" => "",
		                                "AJAX_OPTION_HISTORY" => "N",
		                                "AJAX_OPTION_JUMP" => "N",
		                                "AJAX_OPTION_STYLE" => "Y",
		                                "BROWSER_TITLE" => "-",
		                                "CACHE_GROUPS" => "Y",
		                                "CACHE_TIME" => "36000000",
		                                "CACHE_TYPE" => "A",
		                                "CHECK_DATES" => "Y",
		                                "COMPONENT_TEMPLATE" => ".default",
		                                "DETAIL_URL" => "",
		                                "DISPLAY_BOTTOM_PAGER" => "Y",
		                                "DISPLAY_DATE" => "Y",
		                                "DISPLAY_NAME" => "Y",
		                                "DISPLAY_PICTURE" => "Y",
		                                "DISPLAY_PREVIEW_TEXT" => "Y",
		                                "DISPLAY_TOP_PAGER" => "N",
		                                "ELEMENT_CODE" => "",
		                                "ELEMENT_ID" => $doctor_id,
		                                "FIELD_CODE" => array(0=>"NAME",1=>"PREVIEW_PICTURE",2=>"",),
		                                "IBLOCK_ID" => "5",
		                                "IBLOCK_TYPE" => "olimp",
		                                "IBLOCK_URL" => "",
		                                "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		                                "MESSAGE_404" => "",
		                                "META_DESCRIPTION" => "-",
		                                "META_KEYWORDS" => "-",
		                                "PAGER_BASE_LINK_ENABLE" => "N",
		                                "PAGER_SHOW_ALL" => "N",
		                                "PAGER_TEMPLATE" => ".default",
		                                "PAGER_TITLE" => "Страница",
		                                "PROPERTY_CODE" => array(0=>"STAZH",1=>"DOLZHNOST",2=>"",),
		                                "SET_BROWSER_TITLE" => "N",
		                                "SET_CANONICAL_URL" => "N",
		                                "SET_LAST_MODIFIED" => "N",
		                                "SET_META_DESCRIPTION" => "N",
		                                "SET_META_KEYWORDS" => "N",
		                                "SET_STATUS_404" => "N",
		                                "SET_TITLE" => "N",
		                                "SHOW_404" => "N",
		                                "SORT_BY1" => 'NAME',
		                                "STRICT_SECTION_CHECK" => "N",
		                                "USE_PERMISSIONS" => "N",
		                                "USE_SHARE" => "N",
		                            ),
		                            false,
		                            array('HIDE_ICONS'=>'Y')
		                        );

		                    endforeach;

		                ?>

		            </ul>

			    </section>
			    <!-- specialists end -->

		<?php endif; ?>

	</div>

</div>

<?php get_footer(); ?>
