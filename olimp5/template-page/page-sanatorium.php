<?php 

get_header();

/*
Template Name: Расчёты проживания
*/

$curr_programm = $_GET['pr'] ?: '';

$programms_list = get_posts( 
	array(
		'post_type' => 'programms',
		'posts_per_page' => -1,
		'fields' => 'ids'
	) 
);


?>
	
<div class="rb-calc rb-page">

    <div class="rb-container">


        <h1 class="rb-title">Программа: "<?php echo get_the_title( $curr_programm ); ?>"</h1>

        <?php if ( $programms_list ) : ?>
			<div class="rb-calc__list">


	            <ul class="flex">
					<?php foreach( $programms_list as $programm ) : ?>
						<li <?php if( $programm == $curr_programm ) : ?> class="rb-active-curr"<?php endif; ?>>
							<a <?php if( $programm == $curr_programm ) : ?> class="active"<?php endif; ?> href="<?php echo site_url( 'sanatorium/?pr=' . $programm );?>/"><?php echo get_the_title( $programm ); ?></a>
						</li>
					<?php endforeach; ?>
				</ul>

	        </div>
	      <?php endif; ?>

        <form action="" id="rb-sanatorium-form">

            <input type="hidden" name="page_id" value="<?php echo $curr_programm; ?>">
            <input type="hidden" name="rb_sanatorium_nonce" value="<?php echo wp_create_nonce( "rbSanatoriumNonce" ); ?>">
            <input type="hidden" name="action" value="rb_sanatorium_form">

            <div class="flex-wrap rb-calc__form-info">
                <div class="sanatorium__section rb-calc__form-section flex">

                   
                    <span class="sanatorium__title rb-calc__form-title">Дата заезда</span>

                    
                    <div class="sanatorium__section--descr rb-calc__form-text">
                        <?php 
                        	// $page = getElementPropertiesById($arResult['ID']);
                        	// if($page['PROPERTIES']['DAYS']['VALUE']):
                        ?>
                        		<!-- Длительность программы: -->
                        <?php 
                        	// echo $page['PROPERTIES']['DAYS']['VALUE'];
                        	// echo plural_form($page['PROPERTIES']['DAYS']['VALUE'], ['день', 'дня', 'дней']); 
                        	// endif;
                        ?>
                        <input type="text" name="message" value="" placeholder="Сообщение" autocomplete="off">
                    </div>
                    <div class="datepicker--wrapper rb-calc__form-datepicker">
                        <input id="datepicker" class="easepick__input" name="dates" readonly />
                        <!-- <span><img src="/assets/img/calendar.svg" alt=""></span> -->
                    </div>

                            
                </div>

                <div class="sanatorium__section rb-calc__form-section flex">
                    <span class="sanatorium__title rb-calc__form-title">Количество гостей</span>

                    <div class="sanatorium__flex sanatorium__selector--wrapper">
                        <!-- <div class="sanatorium__selector--name">Двухместная палата</div> -->
                        <div class="sanatorium__selector flex">
                            <span class="sanatorium__selector--minus">
								<svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M13 10.5H7M19 10.5C19 11.6819 18.7672 12.8522 18.3149 13.9442C17.8626 15.0361 17.1997 16.0282 16.364 16.864C15.5282 17.6997 14.5361 18.3626 13.4442 18.8149C12.3522 19.2672 11.1819 19.5 10 19.5C8.8181 19.5 7.64778 19.2672 6.55585 18.8149C5.46392 18.3626 4.47177 17.6997 3.63604 16.864C2.80031 16.0282 2.13738 15.0361 1.68508 13.9442C1.23279 12.8522 1 11.6819 1 10.5C1 8.11305 1.94821 5.82387 3.63604 4.13604C5.32387 2.44821 7.61305 1.5 10 1.5C12.3869 1.5 14.6761 2.44821 16.364 4.13604C18.0518 5.82387 19 8.11305 19 10.5Z" stroke="#465477" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</span>
                            <input type="text" class="sanatorium__selector--input" name="people"
                                value="0" />
                            <span class="sanatorium__selector--plus">
                                <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M10 7.5V13.5M13 10.5H7M19 10.5C19 11.6819 18.7672 12.8522 18.3149 13.9442C17.8626 15.0361 17.1997 16.0282 16.364 16.864C15.5282 17.6997 14.5361 18.3626 13.4442 18.8149C12.3522 19.2672 11.1819 19.5 10 19.5C8.8181 19.5 7.64778 19.2672 6.55585 18.8149C5.46392 18.3626 4.47177 17.6997 3.63604 16.864C2.80031 16.0282 2.13738 15.0361 1.68508 13.9442C1.23279 12.8522 1 11.6819 1 10.5C1 8.11305 1.94821 5.82387 3.63604 4.13604C5.32387 2.44821 7.61305 1.5 10 1.5C12.3869 1.5 14.6761 2.44821 16.364 4.13604C18.0518 5.82387 19 8.11305 19 10.5Z" stroke="#465477" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
                            </span>
                        </div>
                    </div>
  
                </div>

                <div class="sanatorium__section rb-calc__form-section flex-wrap">
                    <span class="sanatorium__title rb-calc__form-title">Проживание</span>
                    <div class="flex-wrap rb-calc__form-items col-8 col-m-12">

                        <div class="col-12 flex rb-calc__form-item">
                            <div class="sanatorium__selector--name">Двухместная палата(кол-во
                                    мест)</div>
                            <div class="sanatorium__selector flex">
                                <span class="sanatorium__selector--minus">
	                                <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M13 10.5H7M19 10.5C19 11.6819 18.7672 12.8522 18.3149 13.9442C17.8626 15.0361 17.1997 16.0282 16.364 16.864C15.5282 17.6997 14.5361 18.3626 13.4442 18.8149C12.3522 19.2672 11.1819 19.5 10 19.5C8.8181 19.5 7.64778 19.2672 6.55585 18.8149C5.46392 18.3626 4.47177 17.6997 3.63604 16.864C2.80031 16.0282 2.13738 15.0361 1.68508 13.9442C1.23279 12.8522 1 11.6819 1 10.5C1 8.11305 1.94821 5.82387 3.63604 4.13604C5.32387 2.44821 7.61305 1.5 10 1.5C12.3869 1.5 14.6761 2.44821 16.364 4.13604C18.0518 5.82387 19 8.11305 19 10.5Z" stroke="#465477" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>
								</span>
                                <input type="text" class="sanatorium__selector--input" name="room_2"
                                    value="0" />
	                            <span class="sanatorium__selector--plus">
	                            	<svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M10 7.5V13.5M13 10.5H7M19 10.5C19 11.6819 18.7672 12.8522 18.3149 13.9442C17.8626 15.0361 17.1997 16.0282 16.364 16.864C15.5282 17.6997 14.5361 18.3626 13.4442 18.8149C12.3522 19.2672 11.1819 19.5 10 19.5C8.8181 19.5 7.64778 19.2672 6.55585 18.8149C5.46392 18.3626 4.47177 17.6997 3.63604 16.864C2.80031 16.0282 2.13738 15.0361 1.68508 13.9442C1.23279 12.8522 1 11.6819 1 10.5C1 8.11305 1.94821 5.82387 3.63604 4.13604C5.32387 2.44821 7.61305 1.5 10 1.5C12.3869 1.5 14.6761 2.44821 16.364 4.13604C18.0518 5.82387 19 8.11305 19 10.5Z" stroke="#465477" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>
	                            </span>
                            </div>
                        </div>
                        <div class="col-12 flex rb-calc__form-item">
                            <div class="sanatorium__selector--name">Одноместная палата(кол-во
                                    номеров)</div>
                            <div class="sanatorium__selector flex">
                                <span class="sanatorium__selector--minus">
	                                <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M13 10.5H7M19 10.5C19 11.6819 18.7672 12.8522 18.3149 13.9442C17.8626 15.0361 17.1997 16.0282 16.364 16.864C15.5282 17.6997 14.5361 18.3626 13.4442 18.8149C12.3522 19.2672 11.1819 19.5 10 19.5C8.8181 19.5 7.64778 19.2672 6.55585 18.8149C5.46392 18.3626 4.47177 17.6997 3.63604 16.864C2.80031 16.0282 2.13738 15.0361 1.68508 13.9442C1.23279 12.8522 1 11.6819 1 10.5C1 8.11305 1.94821 5.82387 3.63604 4.13604C5.32387 2.44821 7.61305 1.5 10 1.5C12.3869 1.5 14.6761 2.44821 16.364 4.13604C18.0518 5.82387 19 8.11305 19 10.5Z" stroke="#465477" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>
								</span>
                                <input type="text" class="sanatorium__selector--input" name="room_1"
                                    value="0" />
	                            <span class="sanatorium__selector--plus">
	                            	<svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M10 7.5V13.5M13 10.5H7M19 10.5C19 11.6819 18.7672 12.8522 18.3149 13.9442C17.8626 15.0361 17.1997 16.0282 16.364 16.864C15.5282 17.6997 14.5361 18.3626 13.4442 18.8149C12.3522 19.2672 11.1819 19.5 10 19.5C8.8181 19.5 7.64778 19.2672 6.55585 18.8149C5.46392 18.3626 4.47177 17.6997 3.63604 16.864C2.80031 16.0282 2.13738 15.0361 1.68508 13.9442C1.23279 12.8522 1 11.6819 1 10.5C1 8.11305 1.94821 5.82387 3.63604 4.13604C5.32387 2.44821 7.61305 1.5 10 1.5C12.3869 1.5 14.6761 2.44821 16.364 4.13604C18.0518 5.82387 19 8.11305 19 10.5Z" stroke="#465477" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>
	                                
	                            </span>
                            </div>
                        </div>

                        <div class="col-12 flex rb-calc__form-item">
                            <div class="sanatorium__selector--name">Палата для ММП
                            </div>
                            <div class="sanatorium__selector flex">
                                <span class="sanatorium__selector--minus">
	                                <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M13 10.5H7M19 10.5C19 11.6819 18.7672 12.8522 18.3149 13.9442C17.8626 15.0361 17.1997 16.0282 16.364 16.864C15.5282 17.6997 14.5361 18.3626 13.4442 18.8149C12.3522 19.2672 11.1819 19.5 10 19.5C8.8181 19.5 7.64778 19.2672 6.55585 18.8149C5.46392 18.3626 4.47177 17.6997 3.63604 16.864C2.80031 16.0282 2.13738 15.0361 1.68508 13.9442C1.23279 12.8522 1 11.6819 1 10.5C1 8.11305 1.94821 5.82387 3.63604 4.13604C5.32387 2.44821 7.61305 1.5 10 1.5C12.3869 1.5 14.6761 2.44821 16.364 4.13604C18.0518 5.82387 19 8.11305 19 10.5Z" stroke="#465477" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>
								</span>
                                <input type="text" class="sanatorium__selector--input" name="room_3"
                                    value="0" />
	                            <span class="sanatorium__selector--plus">
	                            	<svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M10 7.5V13.5M13 10.5H7M19 10.5C19 11.6819 18.7672 12.8522 18.3149 13.9442C17.8626 15.0361 17.1997 16.0282 16.364 16.864C15.5282 17.6997 14.5361 18.3626 13.4442 18.8149C12.3522 19.2672 11.1819 19.5 10 19.5C8.8181 19.5 7.64778 19.2672 6.55585 18.8149C5.46392 18.3626 4.47177 17.6997 3.63604 16.864C2.80031 16.0282 2.13738 15.0361 1.68508 13.9442C1.23279 12.8522 1 11.6819 1 10.5C1 8.11305 1.94821 5.82387 3.63604 4.13604C5.32387 2.44821 7.61305 1.5 10 1.5C12.3869 1.5 14.6761 2.44821 16.364 4.13604C18.0518 5.82387 19 8.11305 19 10.5Z" stroke="#465477" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>
	                                
	                            </span>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="sanatorium__section rb-calc__form-section flex-wrap">
                    <span class="sanatorium__title rb-calc__form-title">Питание</span>
                    <div class="flex-wrap rb-calc__form-items col-8 col-m-12">
                        <div class="col-12 flex rb-calc__form-item">
                            <div class="sanatorium__selector--name">Стандартное</div>
                            <div class="sanatorium__selector flex">
                                <span class="sanatorium__selector--minus">
	                                <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M13 10.5H7M19 10.5C19 11.6819 18.7672 12.8522 18.3149 13.9442C17.8626 15.0361 17.1997 16.0282 16.364 16.864C15.5282 17.6997 14.5361 18.3626 13.4442 18.8149C12.3522 19.2672 11.1819 19.5 10 19.5C8.8181 19.5 7.64778 19.2672 6.55585 18.8149C5.46392 18.3626 4.47177 17.6997 3.63604 16.864C2.80031 16.0282 2.13738 15.0361 1.68508 13.9442C1.23279 12.8522 1 11.6819 1 10.5C1 8.11305 1.94821 5.82387 3.63604 4.13604C5.32387 2.44821 7.61305 1.5 10 1.5C12.3869 1.5 14.6761 2.44821 16.364 4.13604C18.0518 5.82387 19 8.11305 19 10.5Z" stroke="#465477" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>
								</span>
                                <input type="text" class="sanatorium__selector--input" name="food_standart"
                                    value="0" />
                                <span class="sanatorium__selector--plus">
                                	<svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M10 7.5V13.5M13 10.5H7M19 10.5C19 11.6819 18.7672 12.8522 18.3149 13.9442C17.8626 15.0361 17.1997 16.0282 16.364 16.864C15.5282 17.6997 14.5361 18.3626 13.4442 18.8149C12.3522 19.2672 11.1819 19.5 10 19.5C8.8181 19.5 7.64778 19.2672 6.55585 18.8149C5.46392 18.3626 4.47177 17.6997 3.63604 16.864C2.80031 16.0282 2.13738 15.0361 1.68508 13.9442C1.23279 12.8522 1 11.6819 1 10.5C1 8.11305 1.94821 5.82387 3.63604 4.13604C5.32387 2.44821 7.61305 1.5 10 1.5C12.3869 1.5 14.6761 2.44821 16.364 4.13604C18.0518 5.82387 19 8.11305 19 10.5Z" stroke="#465477" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>
                                
                            	</span>
                            </div>
                        </div>
                       <div class="col-12 flex rb-calc__form-item">
                            <div class="sanatorium__selector--name">Индивидуальное</div>
                            <div class="sanatorium__selector flex">
                                <span class="sanatorium__selector--minus">
                                	<svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M13 10.5H7M19 10.5C19 11.6819 18.7672 12.8522 18.3149 13.9442C17.8626 15.0361 17.1997 16.0282 16.364 16.864C15.5282 17.6997 14.5361 18.3626 13.4442 18.8149C12.3522 19.2672 11.1819 19.5 10 19.5C8.8181 19.5 7.64778 19.2672 6.55585 18.8149C5.46392 18.3626 4.47177 17.6997 3.63604 16.864C2.80031 16.0282 2.13738 15.0361 1.68508 13.9442C1.23279 12.8522 1 11.6819 1 10.5C1 8.11305 1.94821 5.82387 3.63604 4.13604C5.32387 2.44821 7.61305 1.5 10 1.5C12.3869 1.5 14.6761 2.44821 16.364 4.13604C18.0518 5.82387 19 8.11305 19 10.5Z" stroke="#465477" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>
								</span>
                                <input type="text" class="sanatorium__selector--input" name="food_personal"
                                    value="0" />
                                <span class="sanatorium__selector--plus">
	                                <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M10 7.5V13.5M13 10.5H7M19 10.5C19 11.6819 18.7672 12.8522 18.3149 13.9442C17.8626 15.0361 17.1997 16.0282 16.364 16.864C15.5282 17.6997 14.5361 18.3626 13.4442 18.8149C12.3522 19.2672 11.1819 19.5 10 19.5C8.8181 19.5 7.64778 19.2672 6.55585 18.8149C5.46392 18.3626 4.47177 17.6997 3.63604 16.864C2.80031 16.0282 2.13738 15.0361 1.68508 13.9442C1.23279 12.8522 1 11.6819 1 10.5C1 8.11305 1.94821 5.82387 3.63604 4.13604C5.32387 2.44821 7.61305 1.5 10 1.5C12.3869 1.5 14.6761 2.44821 16.364 4.13604C18.0518 5.82387 19 8.11305 19 10.5Z" stroke="#465477" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>

	                            </span>
                            </div>
                        </div>
                       	<div class="col-12 flex rb-calc__form-item">
                            <div class="sanatorium__selector--name">Без питания</div>
                            <div class="sanatorium__selector flex">
                                <span class="sanatorium__selector--minus">
                                	<svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M13 10.5H7M19 10.5C19 11.6819 18.7672 12.8522 18.3149 13.9442C17.8626 15.0361 17.1997 16.0282 16.364 16.864C15.5282 17.6997 14.5361 18.3626 13.4442 18.8149C12.3522 19.2672 11.1819 19.5 10 19.5C8.8181 19.5 7.64778 19.2672 6.55585 18.8149C5.46392 18.3626 4.47177 17.6997 3.63604 16.864C2.80031 16.0282 2.13738 15.0361 1.68508 13.9442C1.23279 12.8522 1 11.6819 1 10.5C1 8.11305 1.94821 5.82387 3.63604 4.13604C5.32387 2.44821 7.61305 1.5 10 1.5C12.3869 1.5 14.6761 2.44821 16.364 4.13604C18.0518 5.82387 19 8.11305 19 10.5Z" stroke="#465477" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>
								</span>
                                <input type="text" class="sanatorium__selector--input" name="food_empty"
                                    value="0" />
                                <span class="sanatorium__selector--plus">
	                                <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M10 7.5V13.5M13 10.5H7M19 10.5C19 11.6819 18.7672 12.8522 18.3149 13.9442C17.8626 15.0361 17.1997 16.0282 16.364 16.864C15.5282 17.6997 14.5361 18.3626 13.4442 18.8149C12.3522 19.2672 11.1819 19.5 10 19.5C8.8181 19.5 7.64778 19.2672 6.55585 18.8149C5.46392 18.3626 4.47177 17.6997 3.63604 16.864C2.80031 16.0282 2.13738 15.0361 1.68508 13.9442C1.23279 12.8522 1 11.6819 1 10.5C1 8.11305 1.94821 5.82387 3.63604 4.13604C5.32387 2.44821 7.61305 1.5 10 1.5C12.3869 1.5 14.6761 2.44821 16.364 4.13604C18.0518 5.82387 19 8.11305 19 10.5Z" stroke="#465477" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>
	                            </span>
                            </div>
                        </div>


                    </div>
 
                </div>
                <div class="sanatorium__section rb-calc__form-section flex">
                    <span class="sanatorium__title rb-calc__form-title">Контактное лицо</span>
                    <div class="sanatorium__section--descr rb-calc__form-text rb-calc__form-input">
                        <input type="text"  id="rb-calc-name"  name="name" value="" required placeholder="Ваше имя" autocomplete="off">
                    </div>        
                </div>

                <div class="sanatorium__section rb-calc__form-section flex">
                    <span class="sanatorium__title rb-calc__form-title">Контактный телефон</span>
                    <div class="sanatorium__section--descr rb-calc__form-text rb-calc__form-input">
                        <input type="text" id="rb-calc-phone" name="phone" value="" required placeholder="Ваш телефон" autocomplete="off">
                    </div>
                </div>

            </div>

            <div class="rb-calc__message"></div>
            <input type="button" name="calc" id="rb-sanatorium-calc" value="Рассчитать стоимость" class="sanatorium__button footer__subscription--button rb-calc__btn">

			<div class="grid-12">

				<div class="col-12">
				<div class="sanatorium__flexes_" id="sanatorium__result"></div>
				</div>
			</div>

<!-- <input id="checkout"  class="easepick__input" name="dates2" readonly /> -->

                            <script src="https://cdn.jsdelivr.net/npm/@easepick/datetime@1.2.0/dist/index.umd.min.js">
                            </script>
                            <script src="https://cdn.jsdelivr.net/npm/@easepick/core@1.2.0/dist/index.umd.min.js">
                            </script>
                            <script
                                src="https://cdn.jsdelivr.net/npm/@easepick/base-plugin@1.2.0/dist/index.umd.min.js">
                            </script>
                            <script
                                src="https://cdn.jsdelivr.net/npm/@easepick/range-plugin@1.2.0/dist/index.umd.min.js">
                            </script>
                            <script
                                src="https://cdn.jsdelivr.net/npm/@easepick/lock-plugin@1.2.0/dist/index.umd.min.js">
                            </script>

                            <script>
                            // document.onreadystatechange = function () {
                            // 	if (document.readyState === "interactive") {



                            const picker = new easepick.create({
                                element: document.getElementById('datepicker'),
                                css: [
                                    'https://cdn.jsdelivr.net/npm/@easepick/core@1.2.0/dist/index.css',
                                    // 'https://cdn.jsdelivr.net/npm/@easepick/range-plugin@1.2.0/dist/index.css',
                                    'https://cdn.jsdelivr.net/npm/@easepick/lock-plugin@1.2.0/dist/index.css',
                                    // '/assets/css/addon.css'
                                ],

                                setup(picker) {
                                    picker.on('select', (e) => {
                                        const {
                                            start,
                                            end
                                        } = e.detail;
                                        // do something

                                        console.log(start, end);
                                    });
                                },

                                zIndex: 1,

                                plugins: [
                                    // 'RangePlugin',
                                    "LockPlugin",
                                ],

                                // RangePlugin: {

                                // 	tooltip: true,
                                // 	locale: {
                                // 		zero: "дней",
                                // 		one: "день",
                                // 		two: "дня",
                                // 		few: "дня",
                                // 		many: "дней",
                                // 		other: "дней"
                                // 	}
                                // },


                                LockPlugin: {
                                    minDate: new Date(),
                                    // minDate: new Date().toISOString(),
                                    // selectForward: true,
                                    // maxDate: new Date().setMonth(new Date().getMonth() + 12)
                                },

                                format: "DD.MM.YYYY",

                                // grid: 2,
                                readonly: true,
                                delimiter: " - ",
                                // calendars: 2,
                                lang: "ru-RU",
                                // inline: true,
                            });


                            const DateTime = easepick.DateTime;
                            const today = new DateTime();
                            const tomorrow = today.clone().add(1, 'day');

                            // picker.setStartDate(today);
                            // picker.setEndDate(tomorrow);

                            picker.setDate(today);

                            // picker.setDate('2022-01-01');


                            // 	}
                            // }
                            </script>

                            <style>
                            .easepick__input {
                                font-family: Bebas Neue Bold;
                                padding: 10px;
                                display: block;
                                font-size: 22px;
                                border-radius: 100px;
                                border: none;
                                background: #e6e6e6;
                                padding-left: 20px;
								height: 54px;
                            }
                            </style>

        </form>

    </div>
</div>
<?php get_footer(); ?>