<?php 

   if( function_exists( 'carbon_get_theme_option' ) ){

      $copyring = carbon_get_theme_option( 'copyring' );
      $logo = carbon_get_theme_option( 'logo' );
      $policy = carbon_get_theme_option( 'policy' );
      $persons = carbon_get_theme_option( 'persons' );

   }

?>

<!-- BREAK CONTENT -->
      
         <?php if ( ! is_page_template( 'template-page/page-sert.php' ) && ! is_page_template( 'template-page/page-links.php' ) && ! is_page_template( 'template-page/page-psycology.php' ) && ! is_page_template( 'template-post/post-set-type.php' ) && ! is_page_template( 'template-post/post-set-big-type.php' ) && ! is_page_template( 'template-post/post-doctors-type.php' ) && ! is_page_template( 'template-post/post-text-type.php' ) && ! is_page_template( 'template-post/post-text-colon-type.php' ) && ! is_page_template( 'template-post/post-text-full-type.php' ) && ! is_page_template( 'template-post/post-text-colon-type.php' ) && ! is_page_template( 'template-page/page-services-discount.php' ) ) : ?>
            <!-- <section class="rb-footer-info">
               <div class="rb-container flex-wrap">
                  <div class="col-6 col-m-12 rb-footer-info__left">
                     <?php if( is_tax( 'services' ) || is_page_template( 'template-page/page-biomarket.php' ) || is_page_template( 'template-page/page-promo.php' ) || is_page_template( 'template-page/page-documents.php' ) || is_page_template( 'template-page/page-programms.php' ) || is_singular( 'programms' ) || is_singular( 'doctors' ) ) : ?>
                        <p>
                           <strong>
                              Уважаемые пациенты! Указанные на сайте цены не являются публичной офертой.
                           </strong>
                        </p>
                        <p>
                           <strong>
                              Для уточнения стоимости услуг и записи на прием к врачу  или процедуру обращайтесь по телефону <a href="tel:84732111540">+7 (473) 211-15-40</a>
                           </strong>
                        </p>  
                     <?php endif; ?>
                     <p>
                        ООО «Центр Культуры Здоровья» 
                     </p>
                     <p>
                        ОГРН: 1203600024220 
                     </p>
                     <p>
                        Лицензия: № Л041-01136-36/00383183
                     </p>
					 <div class="t-work">
						<p>пн-пт: 08:00 — 20:00</p>
						<p>сб-вс: 08:00 — 18:00</p>
					 </div>
                  </div>
                  <div class="col-6 col-m-12 rb-footer-info__right">
                     <p>
                        По вопросам трудоустройства, а также по наличию открытых вакансий обращайтесь по тел.:  
                     </p>
                     <p>
                        <a href="tel:84732111568">+7 (473) 211-15-68</a> (доб. 723)
                     </p>
                     <p>
                        или e-mail: 
                     </p>
                     <p>
                        <a href="mailto:info@olimp5.ru">info@olimp5.ru</a>
                     </p>
                  </div>
               </div>
            </section> -->
         <?php endif; ?>
      </main>
      <footer class="rb-footer">

         <div class="rb-container">
            <div class="footer__menus">
               <div class="rb-footer__menu--item col-3 col-s-12">
                  <div class="footer__label">Центр</div>
                  <nav class="footer__menu">
                     <?php 

                        wp_nav_menu(
                           array(
                              'theme_location' => 'footer_menu',
                              'container' => 'false'
                           )
                        );

                     ?>
                  </nav>
               </div>
               <div class="rb-footer__menu--item col-3 col-s-12">
                  <div class="footer__label">Услуги</div>
                  <nav class="footer__menu">
                     <?php 

                        wp_nav_menu(
                           array(
                              'theme_location' => 'footer_menu_2',
                              'container' => 'false'
                           )
                        );

                     ?>
                  </nav>
               </div>
               <div class="rb-footer__menu--item col-3 col-s-12">
                  <div class="footer__label">Акции и новости</div>
                  <nav class="footer__menu">
                     <?php 

                        wp_nav_menu(
                           array(
                              'theme_location' => 'footer_menu_3',
                              'container' => 'false'
                           )
                        );

                     ?>
                  </nav>
               </div>
               <div class="rb-footer__menu--item col-3 col-s-12">
                  <div class="footer__label">Контакты</div>
                  <nav class="footer__menu">
                     <?php 

                        wp_nav_menu(
                           array(
                              'theme_location' => 'footer_menu_4',
                              'container' => 'false'
                           )
                        );

                     ?>
                  </nav>
                  <div class="rb-footer__menu-contacts">
                     <p>
                        ООО «Центр Культуры Здоровья» 
                     </p>
                     <p>
                        ОГРН: 1203600024220 
                     </p>
                     <p>
                        Лицензия: № Л041-01136-36/00383183
                     </p>
					 <div class="t-work">
						<br />
						<p>пн-пт: 08:00 — 20:00</p>
						<p>сб-вс: 08:00 — 18:00</p>
					 </div>
                  </div>
               </div>
            </div>
                  
            <div class="rb-footer__bot">
               <div class="footer__copyright"><?php echo esc_html( $copyring ); ?></div>
               <div class="footer__politic">
                  <div class="top_box">
                  <?php if ( $policy ) : ?>
                     <a target="_blank" href="https://olimp5.ru/privacy_policy/<?//php echo wp_get_attachment_url( $policy ); ?>">Политика обработки персональных данных</a><br>
                  <?php 
                     endif;
                     if ( $persons ) :
                  ?>
                     <a target="_blank" href="<?php echo wp_get_attachment_url( $persons ); ?>">Пользовательское соглашение</a>
                  <?php endif; ?>
                  </div>
                     <p class="footer__oferta">Цены, представленные на сайте, не являются публичной офертой</p>
               </div>
            </div>
         </div>
		  
		<!-- Кнопка наверх --> 
		<div class="toTop">
		  <a id="toTop" class="button-toTop" title="Наверх"><span>↑</span></a>
		</div>
		  
      </footer>
   </div>
   <!-- loader start -->
   <div class="loader">
      <div class="loader__wrapper">
         <div class="loader__logo"><?php echo wp_get_attachment_image( $logo, 'medium' ); ?></div>
         <div class="c-preloader">
            <div class="c-preloader__count"></div>
            <div class="c-preloader__progress"></div>
         </div>
      </div>
   </div>
   <!-- loader end -->
   <div class="overlay js-overlay-modal"></div>
   <div class="modal popup-modal" data-modal="1">
      <!--   Svg иконка для закрытия окна  -->
      <svg class="modal__cross js-modal-close" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M23.954 21.03l-9.184-9.095 9.092-9.174-2.832-2.807-9.09 9.179-9.176-9.088-2.81 2.81 9.186 9.105-9.095 9.184 2.81 2.81 9.112-9.192 9.18 9.1z"></path></svg>

      <div class="container_ no_padding" style="background-color: #fff;overflow-y: auto;max-height: 80vh;">
         <div class="ajax_content">

               <form action="" method="POST" id="rb-popup-form">
                  <?php 
                  // echo '<pre>';
                  //    print_r( get_post_meta( get_the_ID() ) );
                     $seo_ym = ( is_tax() ) ? get_term_meta( get_queried_object()->term_id, '_rb_services_seo_ym', true ) : get_post_meta( get_the_ID(), '_rb_services_seo_ym', true ); 
                     if ( $seo_ym ) :
                  ?>
                     <input type="hidden" id="seo_ym" name="seo_ym" value="<?php echo $seo_ym; ?>">
                  <?php endif; ?>
                  <input name="rb_popup_nonce" value="<?php echo wp_create_nonce( "rbPopupNonce" ); ?>" type="hidden">
                  <input type="hidden" name="action" value="rb_popup_form">
                  <input type="hidden" name="curr_url" value="<?php echo site_url() . $_SERVER['REQUEST_URI']; ?>">
                  <div class="popup__content--form">
                     <div class="popup-psy-modal--title">Запись на приём</div>
                     <!-- <div class="popup-psy-modal--text">Оставьте свой телефон, мы перезвоним и подберем<br> время первичной консультации</div> -->
                     
                     <div class="popup__modal--form">

                        <div class="popup__modal--form--group">
                           <div class="container no_padding">
                              <div class="grid-12-noGutter">

                                 <div class="col-12">
                                    <div class="popup__modal--form--input">
                                       <input type="text" name="user_name" value="" placeholder="Сначала фамилия, потом имя" autocomplete="off" required="">
                                    </div>
                                 </div>

                                 <div class="col-12">
                                    <div class="popup__modal--form--input">
                                       <input type="text" name="user_phone" class="PHONE_MASK" value="" placeholder="Телефон" autocomplete="off" required="">
                                       <input type="text" name="message" value="" placeholder="Сообщение" autocomplete="off">
                                    </div>
                                 </div>

                                 <div class="col-12">
                                    <div class="popup__modal--form--input">
                                       <textarea name="user_msg" placeholder="Опишите, чем мы можем вам помочь (максимум 5000 символов)" autocomplete="off" required=""></textarea>
                                    </div>
                                 </div>

                                 <input type="hidden" name="user_service" value="">
                                 <input type="hidden" name="user_servicestax" value="">
                                 <input type="hidden" name="user_doctors" value="">
                                 <input type="hidden" name="user_programms" value="">

                              </div>
                           </div>
                        </div>

                     </div>

                     <div class="popup__modal--info flex align-center flex-center" style="margin-top: 20px">
                        <div class="popup__modal--price">&nbsp;</div>
                        <div class="popup__modal--button">
                           <input type="submit" name="submit" value="Отправить" class="rb-btn-submit btn__more btn__more--orange">
                        </div>
                     </div>

                     <div class="popup__modal--form--privacy">

<p><input type="checkbox" required="">Установите флажок здесь в соответствии с Федеральным законом № 152-ФЗ «О персональных данных» от 27.07.2006, отправляя форму на этом сайте,
вы подтверждаете свое <a target="_blank" href="wp-content/uploads/2026/01/soglasie-polzovatelej-sajta.pdf<?//php echo wp_get_attachment_url( $policy ); ?>">согласие на обработку персональных данных</a> в соответствии с 
<a target="_blank" href="/wp-content/uploads/2025/10/politika-obrabotki-personalnyh-dannyh-ooo-czkz.pdf">Политикой обработки персональных данных ООО "ЦКЗ"</a> .</p>    
						 <p><input type="checkbox" >Установите флажок здесь для согласия на получение <a target="_blank" href="/wp-content/uploads/2025/09/soglasie-na-poluchenie-reklamnyh-i-informaczionnyh-rassylok.pdf">рекламных и информационных рассылок</a>.</p> 
					  
					  </div>
                  </div>

               </form>

         </div>
      </div>
   </div>
   <?php if ( is_page_template( 'template-page/page-psycology.php' ) ) : ?>
      <div class="modal popup-psy-modal">
         <!--   Svg иконка для закрытия окна  -->
         <svg class="modal__cross js-modal-close" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M23.954 21.03l-9.184-9.095 9.092-9.174-2.832-2.807-9.09 9.179-9.176-9.088-2.81 2.81 9.186 9.105-9.095 9.184 2.81 2.81 9.112-9.192 9.18 9.1z"></path></svg>

         <div class="popup-psy-modal__inner" style="background-color: #fff;overflow-y: auto;max-height: 80vh;">

            <form action="" method="POST" id="rb-popup-form">
               <input name="rb_popup_nonce" value="<?php echo wp_create_nonce( "rbPopupNonce" ); ?>" type="hidden">
               <input type="hidden" name="action" value="rb_popup_form">
               <input type="hidden" name="curr_url" value="<?php echo site_url() . $_SERVER['REQUEST_URI']; ?>">
               <div class="popup__content--form">
                  <div class="rb-psy-1">
                     <span class="popup-psy-modal--title">Запись на приём</span>
                     <p class="popup-psy-modal--text">Врач поможет определиться с выбором на бесплатном приёме</p>
                  </div>
                  <div class="rb-psy-2">
                     <span class="popup-psy-modal--title">Записаться</span>
                     <!-- <p class="popup-psy-modal--text">Оставьте свой телефон, мы перезвоним и подберем<br> время первичной консультации</p> -->
                  </div>
                  
                  <div class="popup__modal--form">

                     <div class="popup__modal--form--group">
                        <div class="container no_padding">
                           <div class="grid-12-noGutter">

                              <div class="col-12">
                                 <div class="rb-psy-popup--input">
                                    <label for="rb-psy-popup-fio">ФИО</label>
                                    <input id="rb-psy-popup-phone" type="text" name="user_name" value="" placeholder="Сначала фамилия, потом имя" autocomplete="off" required="">
                                 </div>
                              </div>

                              <div class="col-12">
                                 <div class="rb-psy-popup--input">
                                    <label for="rb-psy-popup-phone">Телефон</label>
                                    <input id="rb-psy-popup-phone" type="text" name="user_phone" class="PHONE_MASK" value="" placeholder="" autocomplete="off" required="">
                                    <input type="text" name="message" value="" placeholder="Сообщение" autocomplete="off">
                                 </div>
                              </div>

                           </div>
                        </div>
                     </div>

                  </div>

                  <div class="popup__modal--button rb-psy-popup-btn">
                     <input type="submit" name="submit" value="Оставить заявку" class="rb-btn-submit">
                  </div>

                  <div class="popup__modal--form--privacy">

<p><input type="checkbox" required="">Установите флажок здесь в соответствии с Федеральным законом № 152-ФЗ «О персональных данных» от 27.07.2006, отправляя форму на этом сайте,
вы подтверждаете свое <a target="_blank" href="/wp-content/uploads/2026/01/soglasie-polzovatelej-sajta.pdf<?//php echo wp_get_attachment_url( $policy ); ?>">согласие на обработку персональных данных</a> в соответствии с 
<a target="_blank" href="/wp-content/uploads/2025/10/politika-obrabotki-personalnyh-dannyh-ooo-czkz.pdf">Политикой обработки персональных данных ООО "ЦКЗ"</a> .</p>    
						 <p><input type="checkbox" >Установите флажок здесь для согласия на получение <a target="_blank" href="/wp-content/uploads/2025/09/soglasie-na-poluchenie-reklamnyh-i-informaczionnyh-rassylok.pdf">рекламных и информационных рассылок</a>.</p> 
					  
					  </div>

               </div>

            </form>

         </div>
      </div>
      <div class="rb-popup-vrach modal">
         <div class="popup-vrach__img">
            <img src="<?php echo get_stylesheet_directory_uri()?>/assets/img/popup-vrach.jpeg" alt="">
            <svg class="modal__cross js-modal-close" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
               <rect width="32" height="32" rx="4" fill="white"/>
               <path d="M10 22L22 10M10 10L22 22" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
         </div>
         <div class="popup-vrach__info">
            <span class="popup-psy-modal--title">Бесплатная консультация терапевта</span>
            <p class="popup-psy-modal--text">Получите консультацию врача и подберите для себя оптимальную программу оздоровления</p>
            <div class="popup-psy-modal-btn rb-button__orange-full js-psy-open-modal">
                 Получить консультацию
             </div>
         </div>

      </div>
   <?php endif; ?>
<?php if ( is_page_template( 'template-page/page-schedule.php' ) ) : ?>
      <div class="modal popup-schedule-modal">
         <!--   Svg иконка для закрытия окна  -->
         <svg class="modal__cross js-modal-close" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M23.954 21.03l-9.184-9.095 9.092-9.174-2.832-2.807-9.09 9.179-9.176-9.088-2.81 2.81 9.186 9.105-9.095 9.184 2.81 2.81 9.112-9.192 9.18 9.1z"></path></svg>

         <div class="popup-psy-modal__inner" style="background-color: #fff;overflow-y: auto;max-height: 80vh;">

            <form action="" method="POST" id="rb-schedule-form">
                  <input name="rb_schedule_nonce" value="<?php echo wp_create_nonce( "rbScheduleNonce" ); ?>" type="hidden">
                  <input type="hidden" name="action" value="rb_schedule_form">
                  <input type="hidden" name="curr_url" value="<?php echo site_url() . $_SERVER['REQUEST_URI']; ?>">
                  <div class="popup__content--form">
                     <div class="popup-psy-modal--title">Форма записи на мероприятие</div>
                     
                     <div class="popup__modal--form">

                        <div class="popup__modal--form--group">
                           <div class="container no_padding">
                              <div class="grid-12-noGutter">

                                 <div class="col-12">
                                 <div class="rb-psy-popup--input">
                                    <label for="rb-psy-popup-fio">Ваше ФИО</label>
                                    <input id="rb-psy-popup-phone" type="text" name="sch_name" value="" placeholder="Иванов Иван Иванович" autocomplete="off" required="">
                                 </div>
                                 </div>

                                 <div class="col-12">
                                    <div class="rb-psy-popup--input">
                                       <label for="rb-psy-popup-phone">Номер телефона</label>
                                       <input id="rb-psy-popup-phone" type="text" name="sch_phone" class="PHONE_MASK" value="" placeholder="" autocomplete="off" required="">
                                       <input type="text" name="message" value="" placeholder="Сообщение" autocomplete="off">
                                    </div>
                                 </div>

                                 <input type="hidden" name="prog-name" value="">

                              </div>
                           </div>
                        </div>

                     </div>

                     <div class="popup-schedule-btn popup__modal--info flex align-center flex-center" style="margin-top: 20px">
                           <button class="rb-button__orange-full">Оставить заявку</button>
                     </div>


                  </div>

               </form>

         </div>
      </div>
      <div class="rb-popup-vrach modal">
         <div class="popup-vrach__img">
            <img src="<?php echo get_stylesheet_directory_uri()?>/assets/img/popup-vrach.jpeg" alt="">
            <svg class="modal__cross js-modal-close" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
               <rect width="32" height="32" rx="4" fill="white"/>
               <path d="M10 22L22 10M10 10L22 22" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
         </div>
         <div class="popup-vrach__info">
            <span class="popup-psy-modal--title">Бесплатная консультация терапевта</span>
            <p class="popup-psy-modal--text">Получите консультацию врача и подберите для себя оптимальную программу оздоровления</p>
            <div class="popup-psy-modal-btn rb-button__orange-full js-psy-open-modal">
                 Получить консультацию
             </div>
         </div>

      </div>
   <?php endif; ?>
   <?php if ( is_page_template( 'template-page/page-sert.php' ) ) : ?>
      <div class="modal rb-popup-modal">
         <!--   Svg иконка для закрытия окна  -->
         <svg class="modal__cross js-modal-close" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M23.954 21.03l-9.184-9.095 9.092-9.174-2.832-2.807-9.09 9.179-9.176-9.088-2.81 2.81 9.186 9.105-9.095 9.184 2.81 2.81 9.112-9.192 9.18 9.1z"></path></svg>

         <div class="container">

               <form action="" method="POST" id="rb-popup-sert-form">
                  
                  <div class="popup__content--form">
                     <div class="rb-popup-sert--header">Оформление сертификата</div>

                     <div class="rb-popup-sert--inputs flex flex-wrap">
                        <div class="rb-popup-sert--item-half rb-popup-sert--item-left rb-popup-sert--item">
                           <label for="sert_email">Email получателя</label>
                           <input type="email" name="email" id="sert_email" value="" placeholder="example@mail.com">
                        </div>
                        <div class="rb-popup-sert--item-half rb-popup-sert--item-right rb-popup-sert--item">
                           <label for="second_email">Подтвердите Email</label>
                           <input type="email" name="second_email" id="second_email" value="" placeholder="example@mail.com">
                        </div>
                        <div class="rb-popup-sert--item">
                           <label for="for_whom">Для кого</label>
                           <input type="text" name="for_whom" id="for_whom" value="" placeholder="Имя получателя">
                        </div>
                        <div class="rb-popup-sert--item">
                           <label for="for_occasion">По какому поводу</label>
                           <input type="text" name="for_occasion" id="sert-occasion" value="" placeholder="Например: день рождения">
                        </div>
                        <div class="rb-popup-sert--item">
                           <label for="sert_cost">Сумма сертификата</label>
                           <input type="hidden" name="sert_cost" id="sert_cost" value="" placeholder="Например: день рождения">
                           <div class="rb-popup-sert--select">
                              <span class="rb-popup-sert--select-title">1 000 ₽</span>
                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                 <g opacity="0.4">
                                 <path d="M19.5 8.25L12 15.75L4.5 8.25" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                 </g>
                              </svg>
                              <ul class="rb-popup-sert--select-list">
                                 <li data-val="1000" class="rb-active">1 000 ₽</li>
                                 <li data-val="3000">3 000 ₽</li>
                                 <li data-val="5000">5 000 ₽</li>
                                 <li data-val="7000">7 000 ₽</li>
                                 <li data-val="10000">10 000 ₽</li>
                                 <li data-val="30000">30 000 ₽</li>
                              </ul>
                           </div>
                        </div>
                        <div class="rb-popup-sert--item-half rb-popup-sert--item-left rb-popup-sert--item">
                           <label for="from_whom">От кого</label>
                           <input type="text" name="from_whom" id="from_whom" value="" placeholder="Имя отправителя">
                        </div>
                        <div class="rb-popup-sert--item-half rb-popup-sert--item-right rb-popup-sert--item">
                           <label for="your_email">Ваш Email</label>
                           <input type="email" name="your_email" id="your_email" value="" placeholder="example@mail.com">
                        </div>
                        <div class="rb-popup-sert--item">
                           <label for="rb-msg">Сообщение</label>
                           <textarea name="rb-msg" id="rb-msg" placeholder="Введите текст">
                           </textarea>
                        </div>
                     </div>

                     <input type="submit" name="submit" value="Оставить заявку" class="rb-btn-sert-submit rb-button__orange-full">

                     <div class="popup__modal--form--privacy">
<p>Установите флажок здесь в соответствии с Федеральным законом № 152-ФЗ «О персональных данных» от 27.07.2006, отправляя любую форму на этом сайте,
вы подтверждаете свое <a target="_blank" href="/wp-content/uploads/2025/10/politika-obrabotki-personalnyh-dannyh-ooo-czkz.pdf<?//php echo wp_get_attachment_url( $policy ); ?>">согласие на обработку персональных данных</a> в соответствии с Политикой обработки персональных данных ООО "ЦКЗ".</p>          </div>

                  </div>

               </form>

         </div>
      </div>
   <?php endif; ?>
   <div class="rb-thakyou-modal modal">
         <svg class="modal__cross js-modal-close" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M23.954 21.03l-9.184-9.095 9.092-9.174-2.832-2.807-9.09 9.179-9.176-9.088-2.81 2.81 9.186 9.105-9.095 9.184 2.81 2.81 9.112-9.192 9.18 9.1z"></path></svg>

         <div class="container_ no_padding" style="background-color: #fff;overflow-y: auto;max-height: 80vh;">
            <div class="ajax_content">

                  <div class="popup__modal--header">Спасибо!</div>

                  <div class="popup__content--form--anons"><p>Ваша заявка успешно отправлена. В ближайшее время мы с вами свяжемся для подтверждения записи.</p></div>

            </div>
         </div>
      </div>

      <!-- Yandex.Metrika counter -->
      <!-- <script type="text/javascript" >
         (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
         m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
         (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

         ym(84731377, "init", {
              clickmap:true,
              trackLinks:true,
              accurateTrackBounce:true,
              webvisor:true
         });
      </script>
      <script>
      document.addEventListener('click', function(e) {
         if (e.target.closest('.ondoc-s-btn.ondoc-s-btn-0')) {
            ym(84731377, 'reachGoal', 'online_booking_click');
         }
      });
      </script>
      <noscript><div><img src="https://mc.yandex.ru/watch/84731377" style="position:absolute; left:-9999px;" alt="" /></div></noscript> -->
      <!-- /Yandex.Metrika counter -->

      <!-- Yandex.Metrika -->
      <script type="text/javascript">
      function initMyMetrika() {
         if (window.ymInitialized) return;
         window.ymInitialized = true;

         (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
         m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
         (window, document, "script", "https://yandex.ru", "ym");
         ym(84731377, "init", {clickmap:true,trackLinks:true,accurateTrackBounce:true,webvisor:true});

         document.addEventListener('click', function(e) {if (e.target.closest('.ondoc-s-btn.ondoc-s-btn-0')) ym(84731377, 'reachGoal', 'online_booking_click');});
      }

      document.addEventListener('cookieyes_banner_load', function(eventData) {
         var data = eventData.detail;
         if (data && data.categories && data.categories.analytics) initMyMetrika();
      });

      document.addEventListener("cookieyes_consent_update", function (eventData) {
         var data = eventData.detail;
         if (data && data.accepted && data.accepted.includes("analytics")) initMyMetrika();
      });
      </script>

      <noscript><div><img src="https://yandex.ru" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
      <!-- /Yandex.Metrika counter -->




      <!-- calltouch -->
      <script type="text/javascript">
      (function(w,d,n,c){w.CalltouchDataObject=n;w[n]=function(){w[n]["callbacks"].push(arguments)};if(!w[n]["callbacks"]){w[n]["callbacks"]=[]}w[n]["loaded"]=false;if(typeof c!=="object"){c=[c]}w[n]["counters"]=c;for(var i=0;i<c.length;i+=1){p(c[i])}function p(cId){var a=d.getElementsByTagName("script")[0],s=d.createElement("script"),i=function(){a.parentNode.insertBefore(s,a)},m=typeof Array.prototype.find === 'function',n=m?"init-min.js":"init.js";s.type="text/javascript";s.async=true;s.src="https://mod.calltouch.ru/"+n+"?id="+cId;if(w.opera=="[object Opera]"){d.addEventListener("DOMContentLoaded",i,false)}else{i()}}})(window,document,"ct","meuj0n6v");
      </script>
      <!-- calltouch -->

      <!-- Top.Mail.Ru counter -->
      <script type="text/javascript">
      var _tmr = window._tmr || (window._tmr = []);
      _tmr.push({id: "3383184", type: "pageView", start: (new Date()).getTime()});
      (function (d, w, id) {
        if (d.getElementById(id)) return;
        var ts = d.createElement("script"); ts.type = "text/javascript"; ts.async = true; ts.id = id;
        ts.src = "https://top-fwz1.mail.ru/js/code.js";
        var f = function () {var s = d.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ts, s);};
        if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); }
      })(document, window, "tmr-code");
      </script>
      <noscript><div><img src="https://top-fwz1.mail.ru/counter?id=3383184;js=na" style="position:absolute;left:-9999px;" alt="Top.Mail.Ru" /></div></noscript>
      <!-- /Top.Mail.Ru counter -->
<script>

(function() {
    'use strict';

    function isServicesPage() {
        const path = window.location.pathname;
        return path.includes('/services/');
    }

    function hasFAQSchema() {
        const scripts = document.querySelectorAll('script[type="application/ld+json"]');
        for (let script of scripts) {
            try {
                const data = JSON.parse(script.textContent);
                if (data['@type'] === 'FAQPage') {
                    return true;
                }
            } catch (e) {}
        }
        return false;
    }

    function cleanText(html) {
        if (!html) return '';
        const temp = document.createElement('div');
        temp.innerHTML = html;
        let text = temp.textContent || temp.innerText || '';
        text = text.replace(/\s+/g, ' ').trim();
        return text;
    }

    function extractFAQFromAccordion() {
        const faqItems = [];
        const faqBlock = document.getElementById('y-qest');
        if (!faqBlock) return faqItems;

        const accordionContainer = faqBlock.querySelector('.rb-accordion, .rb-accordion-qe');
        if (!accordionContainer) return faqItems;

        const items = accordionContainer.querySelectorAll('.rb-accordion-item');

        items.forEach((item) => {
            try {
                const questionElement = item.querySelector('.rb-accordion__label h3');
                const question = questionElement ? cleanText(questionElement.innerHTML) : '';

                const answerElement = item.querySelector('.rb-accordion__content');
                const answer = answerElement ? cleanText(answerElement.innerHTML) : '';

                if (question && answer) {
                    faqItems.push({
                        question: question,
                        answer: answer
                    });
                }
            } catch (e) {}
        });

        return faqItems;
    }

    function extractFAQFromOtherBlocks() {
        const faqItems = [];
        const items = document.querySelectorAll('.faq-item');
        
        items.forEach((item) => {
            try {
                const questionElement = item.querySelector('.faq-q, .faq-question, [class*="question"]');
                const answerElement = item.querySelector('.faq-a, .faq-answer, [class*="answer"]');

                const question = questionElement ? cleanText(questionElement.innerHTML) : '';
                const answer = answerElement ? cleanText(answerElement.innerHTML) : '';

                if (question && answer) {
                    faqItems.push({
                        question: question,
                        answer: answer
                    });
                }
            } catch (e) {}
        });

        return faqItems;
    }

    function addFAQSchema(faqItems) {
        if (!faqItems || faqItems.length === 0) return;

        const schema = {
            "@context": "https://schema.org",
            "@type": "FAQPage",
            "mainEntity": faqItems.map(item => ({
                "@type": "Question",
                "name": item.question,
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": item.answer
                }
            }))
        };

        const script = document.createElement('script');
        script.type = 'application/ld+json';
        script.textContent = JSON.stringify(schema, null, 2);
        document.head.appendChild(script);
    }

    function init() {
        if (!isServicesPage()) return;
        if (hasFAQSchema()) return;

        let faqItems = extractFAQFromAccordion();
        
        if (faqItems.length === 0) {
            faqItems = extractFAQFromOtherBlocks();
        }

        if (faqItems.length > 0) {
            addFAQSchema(faqItems);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();

</script>
<script>
(function(){
  function isMobile(){ return window.matchMedia('(max-width: 768px)').matches; }

  function init(){
    var root = document.querySelector('.rb-doctors__cat-list');
    if(!root) return;

    var parents = root.querySelectorAll('a.rb-doctors__cat-sublist--parent');

    parents.forEach(function(a){
      var sub = a.parentElement && a.parentElement.nextElementSibling;
      if(!sub || !sub.classList.contains('rb-doctors__cat-sublist')) return;

      if(!isMobile()) return;

      sub.classList.remove('open');
      sub.classList.remove('is-open');
      a.classList.remove('is-open');

      if(a.classList.contains('active')){
        sub.classList.add('is-open');
        a.classList.add('is-open');
      }

      a.addEventListener('click', function(e){
        if(!isMobile()) return;
        e.preventDefault();

        var isOpen = sub.classList.contains('is-open');

        root.querySelectorAll('.rb-doctors__cat-sublist').forEach(function(x){
          x.classList.remove('is-open');
          x.classList.remove('open');
        });
        root.querySelectorAll('a.rb-doctors__cat-sublist--parent').forEach(function(x){
          x.classList.remove('is-open');
        });

        if(!isOpen){
          sub.classList.add('is-open');
          a.classList.add('is-open');
        }
      }, {passive:false});
    });
  }

  init();
  window.addEventListener('resize', function(){
    init();
  });
})();
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.rb-accordion').forEach(function (accordion) {

        accordion.querySelectorAll('.rb-accordion__label').forEach(function (label) {
            label.addEventListener('click', function (e) {
                e.preventDefault();

                const item = this.closest('.rb-accordion-item');
                const content = item.querySelector('.rb-accordion__content');
                const isOpen = item.classList.contains('is-open');

                accordion.querySelectorAll('.rb-accordion-item').forEach(function (otherItem) {
                    otherItem.classList.remove('is-open');
                    const otherContent = otherItem.querySelector('.rb-accordion__content');
                    if (otherContent) {
                        otherContent.style.maxHeight = null;
                    }
                });

                if (!isOpen) {
                    item.classList.add('is-open');
                    if (content) {
                        content.style.maxHeight = content.scrollHeight + 'px';
                    }
                }
            });
        });

    });
});
</script>
<style>
.rb-accordion__content {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
}

.rb-accordion-item.is-open > .rb-accordion__content {

}
.rb-accordion__content {
    padding: 0 20px;
}
.rb-accordion-item.is-open > .rb-accordion__content {
    padding: 20px;
}


</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const showMoreBtn = document.getElementById('show-more-prices');
  if (showMoreBtn) {
    showMoreBtn.addEventListener('click', function (e) {
      e.preventDefault();
      document.querySelectorAll('.rb-pricelist__item.hidden-by-default').forEach(el => {
        el.classList.remove('hidden-by-default');
      });
      showMoreBtn.style.display = 'none';
    });
  }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  new Swiper('.sl-swiper-spec', {
    slidesPerView: 1,
    spaceBetween: 20,

    navigation: {
      nextEl: '#spec .spec-arrow-next',
      prevEl: '#spec .spec-arrow-prev',
    },
    breakpoints: {
      768: { slidesPerView: 2 },
      1024: { slidesPerView: 4 }
    }
  });
});
</script>

      <script>
              (function(w,d,u){
                      var s=d.createElement('script');s.async=true;s.src=u+'?'+(Date.now()/60000|0);
                      var h=d.getElementsByTagName('script')[0];h.parentNode.insertBefore(s,h);
              })(window,document,'https://portal.olimp03.ru/upload/crm/site_button/loader_4_947q3e.js');
      </script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  // 1. .sl-swiper-serv
  document.querySelectorAll('.js-serv-section').forEach(function(section) {
    const slider = section.querySelector('.sl-swiper-serv');
    const nextBtn = section.querySelector('.serv-arrow-next');
    const prevBtn = section.querySelector('.serv-arrow-prev');

    if (!slider) return;

    new Swiper(slider, {
      slidesPerView: 1,
      spaceBetween: 20,
      loop: false,
      navigation: {
        nextEl: nextBtn,
        prevEl: prevBtn,
      },
      breakpoints: {
        640: { slidesPerView: 1.2 },
        768: { slidesPerView: 2 },
        1024: { slidesPerView: 3 },
        1280: { slidesPerView: 4 }
      }
    });
  });


    new Swiper(document.querySelector('.servs'), {
      slidesPerView: 1,
      spaceBetween: 20,
      loop: false,
      navigation: {
        nextEl: '.serv-arrow-next',
        prevEl: '.serv-arrow-prev',
      },
      breakpoints: {
        640: { slidesPerView: 1.2 },
        768: { slidesPerView: 2 },
        1024: { slidesPerView: 3 },
        1280: { slidesPerView: 4 }
      }
    });


  // 2. .rb-services__slider
  if (document.querySelector('.rb-services__slider')) {
    new Swiper('.rb-services__slider', {
      slidesPerView: 1,
      spaceBetween: 24,
      loop: false,
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      breakpoints: {
        768: { slidesPerView: 2 },
        1024: { slidesPerView: 3 },
      },
    });
  }

  // 3. .feedback-swiper
  if (document.querySelector('.feedback-swiper')) {
	  console.log("feedback")
    new Swiper('.feedback-swiper', {
      slidesPerView: 1,
      spaceBetween: 20,
      loop: true,
	autoHeight: true,
      navigation: {
        nextEl: '#y-portfolio .serv-arrow-next',
        prevEl: '#y-portfolio .serv-arrow-prev',
      },
      breakpoints: {
        768: { slidesPerView: 1 },
        1024: { slidesPerView: 2 },
      }
    });
  }

  // 4. .sl-swiper-pop
  if (document.querySelector('.sl-swiper-pop')) {
    new Swiper('.sl-swiper-pop', {
      slidesPerView: 1,
      spaceBetween: 20,
      loop: false,
      navigation: {
        nextEl: '.pop-arrow-next',
        prevEl: '.pop-arrow-prev',
      },
      breakpoints: {
        576: { slidesPerView: 2 },
        768: { slidesPerView: 3 },
        1024: { slidesPerView: 4 },
      }
    });
  }
	
	if($(window).width() >= 767) {
	$(".feedback-item").matchHeight({
    byRow: true,
});	
	}
	
$(".rb-doctors__spec-item--wrap").matchHeight({
    byRow: true,
});
	
$(".feedback__col").matchHeight({
  byRow: true,
});
	
$("#y-serv .rb-service__item").matchHeight({
  byRow: true,
});
	
$(".rb-service__item_h").matchHeight({
  byRow: false
});
	
$(".rb-program__item_h").matchHeight({
  byRow: false
});

	$(".rb-service__item").matchHeight({
  byRow: false
});
	
	$(".rb-links a").click(function () {
    let anchor = $(this).attr("href");
    let offsetTop = $(anchor).offset().top - 100;
    
    $("html, body").animate({
        scrollTop: offsetTop
    }, 500);
});
	
});
</script>

<script>
// Слайдер Программы
document.addEventListener("DOMContentLoaded", function() {
	if (typeof Swiper !== 'undefined') {
		new Swiper("#y-programs .sl-swiper-prog", {
			slidesPerView: 1, 
			spaceBetween: 20,
			navigation: {
				nextEl: "#y-programs .prog-arrow-next",
				prevEl: "#y-programs .prog-arrow-prev",
			},
			breakpoints: {
				640: {
					slidesPerView: 2
				},
				1024: {
					slidesPerView: 3
				},
				1280: {
					slidesPerView: 4
				}
			}
		});
	}
});
</script>


<script>
document.addEventListener("DOMContentLoaded", function () {
  const popSwiper = new Swiper(".sl-swiper-pop", {
    slidesPerView: 1,
    spaceBetween: 20,
    loop: false,
    navigation: {
      nextEl: ".pop-arrow-next",
      prevEl: ".pop-arrow-prev",
    },
    breakpoints: {
      576: {
        slidesPerView: 2,
      },
      768: {
        slidesPerView: 2.5,
      },
      992: {
        slidesPerView: 3,
      },
      1200: {
        slidesPerView: 4,
      },
    },
  });
});
	
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const showMoreBtn = document.getElementById('show-more-services');
    
    if (showMoreBtn) {
        showMoreBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            let hiddenItems = document.querySelectorAll('.hidden-by-default');
            console.log('Найдено скрытых элементов (.hidden-by-default):', hiddenItems.length);
            
            if (hiddenItems.length === 0) {
                hiddenItems = document.querySelectorAll('li.rb-servicelist__item.hidden-by-default');
                console.log('Найдено скрытых элементов (li.rb-servicelist__item.hidden-by-default):', hiddenItems.length);
            }
            
            if (hiddenItems.length > 0) {
                hiddenItems.forEach((item, index) => {
                    console.log('Показываем элемент ' + (index + 1) + ':', item);
                    item.classList.remove('hidden-by-default');
                    item.style.display = '';
                });
                
                this.style.display = 'none';
            } else {
                console.log('Скрытых элементов не найдено');
                const allItems = document.querySelectorAll('.rb-servicelist__item');
                console.log('Всего элементов в списке:', allItems.length);
                allItems.forEach((item, index) => {
                    console.log('Элемент ' + (index + 1) + ' классы:', item.className);
                });
                this.style.display = 'none';
            }
        });
    }
    
    setTimeout(() => {
        const initialHiddenItems = document.querySelectorAll('.hidden-by-default');
        console.log('Изначально скрытых элементов:', initialHiddenItems.length);
        console.log('Все элементы списка при загрузке:');
        const allItems = document.querySelectorAll('.rb-servicelist__item');
        allItems.forEach((item, index) => {
            console.log('Элемент ' + (index + 1) + ':', item.className);
        });
        
        if (initialHiddenItems.length === 0 && showMoreBtn) {
            showMoreBtn.style.display = 'none';
        }
    }, 100);
});
</script>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.matchHeight/0.7.2/jquery.matchHeight-min.js"></script> -->

<script>
/* Кнопка наверх */
jQuery(document).ready(function($) {
  // Показывать/скрывать кнопку при скролле
  $(window).scroll(function() {
	
	// Показать кнопку, только когда прокручено до самого низа страницы
    $('#toTop').toggle($(window).scrollTop() > 0);
    
  }).trigger('scroll'); // Инициализировать сразу

  // Плавный скролл вверх
  $('#toTop').on('click', function(e) {
    e.preventDefault();
    $('html, body').animate({scrollTop:0}, 500);
  });
	
});
</script>
<style>
	.popup__modal--form--privacy p
 {
    text-align: justify;
}
.rb-main__form-top {
    padding: 32px !important;
}
</style>
      <?php wp_footer(); ?>
<script>
    Element.prototype.matches || (Element.prototype.matches = Element.prototype.matchesSelector || Element.prototype.webkitMatchesSelector || Element.prototype.mozMatchesSelector || Element.prototype.msMatchesSelector), Element.prototype.closest || (Element.prototype.closest = function (e) { for (var t = this; t;) { if (t.matches(e)) return t; t = t.parentElement } return null });
    var ct_get_val = function (form, selector) { if (!!form.querySelector(selector)) { return form.querySelector(selector).value; } else { return ''; } }
    document.addEventListener('click', function (e) { SendCalltouch(e) });
    document.addEventListener('mousedown', function (e) { SendCalltouch(e) });
    document.addEventListener('touchend', function (e) { SendCalltouch(e) });
    function SendCalltouch(e) {
        var t_el = e.target;
        if (t_el.closest('form [type="submit"]')) {
            try {
                var form = t_el.closest('form');
                var fio = ct_get_val(form, 'input[name="user_name"]');
                var phoneNumber = ct_get_val(form, 'input[name="user_phone"]');
                var sub = 'Заявка с ' + location.hostname;
                var site_id = window.ct('calltracking_params', 'meuj0n6v').siteId;
                var ct_data = {
                    fio: fio,
                    phoneNumber: phoneNumber,
                    subject: sub,
                    requestUrl: location.href,
                    sessionId: window.ct('calltracking_params', 'meuj0n6v').sessionId
                };
                var post_data = Object.keys(ct_data).reduce(function (a, k) { if (!!ct_data[k]) { a.push(k + '=' + encodeURIComponent(ct_data[k])); } return a }, []).join('&');
                var CT_URL = 'https://api.calltouch.ru/calls-service/RestAPI/requests/' + site_id + '/register/';
                console.log(ct_data);
                if (!!phoneNumber && phoneNumber.replace(/[^0-9]/gim, '').length >= 10 && !window.ct_snd_flag) {
                    window.ct_snd_flag = 1; setTimeout(function () { window.ct_snd_flag = 0; }, 30000);
                    var request = window.ActiveXObject ? new ActiveXObject("Microsoft.XMLHTTP") : new XMLHttpRequest();
                    request.open("POST", CT_URL, true); request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    request.send(post_data);
                }
            } catch (e) { console.log(e); }
        }
    }
    var _ctreq_b24 = function (data) {
        var sid = window.ct('calltracking_params', 'meuj0n6v').siteId;
        var request = window.ActiveXObject ? new ActiveXObject("Microsoft.XMLHTTP") : new XMLHttpRequest();
        var post_data = Object.keys(data).reduce(function (a, k) { if (!!data[k]) { a.push(k + '=' + encodeURIComponent(data[k])); } return a }, []).join('&');
        var url = 'https://api.calltouch.ru/calls-service/RestAPI/' + sid + '/requests/orders/register/';
        request.open("POST", url, true); request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'); request.send(post_data);
    };
    window.addEventListener('b24:form:submit', function (e) {
        var form = event.detail.object;
        if (form.validated) {
            var fio = ''; var phone = ''; var email = ''; var comment = '';
            form.getFields().forEach(function (el) {
                if (el.name == 'LEAD_NAME' || el.name == 'CONTACT_NAME' || /имя/i.test(el.label)) { fio = el.value(); }
                if (el.name == 'LEAD_PHONE' || el.name == 'CONTACT_PHONE' || /телефон/i.test(el.label)) { phone = el.value(); }
                if (el.name == 'LEAD_EMAIL' || el.name == 'CONTACT_EMAIL') { email = el.value(); }
                if (el.name == 'LEAD_COMMENTS' || el.name == 'DEAL_COMMENTS ') { comment = el.value(); }
            });
            var sub = event.detail.object.title || 'Заявка с формы Bitrix24';
            var ct_data = { fio: fio, phoneNumber: phone, email: email, comment: comment, subject: sub, requestUrl: location.href, sessionId: window.ct('calltracking_params', 'meuj0n6v').sessionId };
            console.log(ct_data);
            if ((!!phone || !!email) && !window.ct_snd_flag) {
                window.ct_snd_flag = 1; setTimeout(function () { window.ct_snd_flag = 0; }, 10000);
                _ctreq_b24(ct_data);
            }
        }
    });
</script>
   </body>
</html>