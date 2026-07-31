<?php

get_header();

/*
Template Name: Отзывы
*/


    // if ( function_exists( 'carbon_get_post_meta' ) ) {

    //     $rb_services_for_whom = carbon_get_post_meta( get_the_id(), 'rb_services_for_whom' );

    // }

    // $rb_services_desc = get_post_meta( get_the_ID(), '_rb_services_desc', true );




?>


    <div class="rb-service-page rb-page">

        <div class="rb-container">

            <!-- service start -->
            <section class="rb-service__top flex-wrap">
                <div class="col-12 rb-service__top-text rb-page-header">
                    <h1>Отзывы об «Олимп Пять» Центр Культуры Здоровья</h1>

                </div>
            </section>

            <section class="rb-service__section">
                <div class="flex-wrap">
                    <div id="pd_widget_big"  data-lpu="78388">
						<div class="pd_rate_header">Отзывы о центре здоровья «Олимп пять»<br>
							<a target="_blank" class="pd_rate_new" href="https://prodoctorov.ru/new/rate/lpu/78388/"
							>Оставить отзыв</a>
						</div>

					<div id="pd_widget_big_content"></div>
						<a target="_blank" href="https://prodoctorov.ru/voronezh/lpu/78388-olimp-pyat/#otzivi" class="pd_read_all">Читать все отзывы</a>

						<span id="pd_powered_by"><a target="_blank" href="https://prodoctorov.ru"><img class='pd_logo' src="https://prodoctorov.ru/static/_v1/pd/logos/logo-pd-widget.png"></a></span>
					</div>
					<script defer src="https://prodoctorov.ru/static/js/widget_big.js?v7"></script>

                </div>

            </section>

        </div>

    </div>
<?php get_footer(); ?>