<?php

get_header();

/*
Template Name: Контакты
*/

$phone = get_post_meta( get_the_ID(), '_rb_phone', true );
$email = get_post_meta( get_the_ID(), '_rb_email', true );
$rb_time_1 = get_post_meta( get_the_ID(), '_rb_time_1', true );
$rb_time_2 = get_post_meta( get_the_ID(), '_rb_time_2', true );
$rb_time_3 = get_post_meta( get_the_ID(), '_rb_time_3', true );
$rb_map = get_post_meta( get_the_ID(), '_rb_map', true );

if ( function_exists( 'carbon_get_post_meta' ) ) {

    $rb_administrators = carbon_get_post_meta( get_the_ID(), 'rb_administrators' );

}

?>


    <div class="rb-contacts">
        <section class="rb-contacts__top">
            <div class="rb-container">
                <div class="rb-contacts__top-wrap flex-wrap">
                    <div class="col-6 col-s-12">
                        <div class="rb-page-header">
                            <h1><?php the_title(); ?></h1>
                        </div>
												<span class="rb-contacts__top-info flex"><b>
г. Воронеж, ул. Моисеева, 2/2</b></p>
						</span>
                        <div class="rb-contacts__top-info flex">
                            <div class="col-6">
                                <span class="rb-contacts__top-info--name">Телефон для записи</span>
                                <a class="rb-contacts__top-info--link" href="tel:<?php echo formatPhone( $phone ); ?>"><?php echo $phone; ?></a>
                            </div>
                            <div class="col-6">
                                <span  class="rb-contacts__top-info--name">Электронная почта</span>
                                <a class="rb-contacts__top-info--link" href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a>
                            </div>
                        </div>
                        <ul class="rb-contacts__top-worktime flex">
                            <li class="col-4 rb-contacts__worktime-item">
                                <span class="rb-contacts__worktime-days">пн-пт</span>
                                <span class="rb-contacts__worktime-time"><?php echo $rb_time_1; ?></span>
                            </li>
                            <li class="col-4 rb-contacts__worktime-item">
                                <span class="rb-contacts__worktime-days">сб</span>
                                <span class="rb-contacts__worktime-time"><?php echo $rb_time_2; ?></span>
                            </li>
                             <li class="col-4 rb-contacts__worktime-item">
                                <span class="rb-contacts__worktime-days">вс</span>
                                <span class="rb-contacts__worktime-time"><?php echo $rb_time_3; ?></span>
                            </li>
                        </ul>
												<span class="rb-contacts__top-info flex">
						<p>ООО "ЦКЗ"<br>
ОГРН: 1203600024220 дата регистрации: 10 августа 2020 г.<br>
							Юр. адрес: 394006, г. Воронеж, ул. Моисеева 2/2, оф. 604</p>
							
						</span>
                    </div>
                    <div class="col-6 col-s-12 rb-contacts__top-map" id="map">
                        <?php echo $rb_map; ?>
                    </div>
                </div>
            </div>
        </section>
        <!-- contacts end -->
        <?php if ( $rb_administrators ) : ?>
            <section class="rb-contacts__admin rb-page">
                <div class="rb-container">

                    <h2 class="rb-title">Администрация ЦКЗ <span>«Олимп Пять»</span></h2>

                    <div class="rb-contacts__admin-list flex-wrap" >
                        <?php foreach( $rb_administrators as $arItem ) : ?>
                            <div class="rb-contacts__admin-item">
                                <a href="<?php echo get_the_permalink( $arItem['id'] ); ?>">
                                    <picture class="rb-contacts__admin-img">
                                        <?php echo get_the_post_thumbnail( $arItem['id'], 'medium', array( 'class' => 'rb-img-contain' ) ); ?>
                                    </picture>
                                    <div class="rb-contacts__admin-info">
                                        <?php $fio = explode( ' ', get_the_title( $arItem['id'] ) ); ?>
                                        <span class="rb-contacts__admin-fio"><?php echo $fio[0]; ?><br><?php echo $fio[1] . ' ' . $fio[2]; ?></span>
                                        <span class="rb-contacts__admin-occup"><?php echo get_post_meta( $arItem['id'], '_rb_doc_occup', true ); ?></span>

                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>
        <!-- specialists end -->
        <section class="rb-contacts__other">
            <div class="rb-container flex-wrap rb-contacts__other-wrap">
                <div class="col-6 col-m-12 rb-contacts__other-left">
                    <h2 class="rb-title">Другие клиники группы <br>компаний <span>«Олимп Здоровья»</span></h2>
                </div>
                <div class="col-6 col-m-12 rb-contacts__other-right">

<?$clinics = carbon_get_post_meta(125, 'rb_contacts_other');

if (!empty($clinics)) : ?>
        <div class="content_">
            <?php foreach ($clinics as $clinic): ?>
                <div class="rb-contacts__other-item">
                    <span class="rb-contacts__other-question"><?= esc_html($clinic['clinic_name']); ?></span>
                    <div class="rb-contacts__other-answer rb-text">
                        <?= apply_filters('the_content', $clinic['clinic_info']); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
<?php endif; ?>



                </div>
                <div class="col-12 rb-contacts__form-wrap">
                        <?php get_template_part( 'template-part/forms/form', 'main-top' ); ?>
                </div>
            </div>

        </section>
    </div>


<?php get_footer(); ?>