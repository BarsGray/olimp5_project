<?php 

get_header();

/*
Template Name: Стационар
*/


?>
	<div class="rb-stationar">
         <div class="rb-container">            
         
               <section class="rb-stationar__head flex">
                  <div class="col-6 col-s-12 rb-stationar__head-left">
                     <h1 class="rb-page-header rb-stationar__head-title">Стационар в ЦКЗ «Олимп Пять»</h1>
                     <p class="rb-stationar__head-desc rb-text">
                        Стационар в «Олимп Пять» — это возможность пройти комплексное восстановление с помощью современных технологий в комфортных условиях, полноценное обследование (чек-ап) в течение 3-5 дней в клиниках группы компаний, с возможностью прохождения инфузионной терапии,  индивидуальной кинезиотерапии, ЛФК, водо- и грязелечения, аппаратной физиотерапии
                     </p>
                  </div>
                  <picture class="col-6 rb-stationar__head-right">
                     <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/butterfly-only.png" class="rb-img-cover" alt="Стационар в ЦКЗ «Олимп Пять»">
                  </picture>
               </section>
               <section class="rb-stationar__types flex-wrap">

                     <div class="col-6 col-m-12 rb-stationar__types-item flex-wrap">
                        
                           <div class="rb-stationar__types-info col-6 col-s-12">
                              <h3 class="rb-stationar__types-title">Восстановительная терапия</h3>

                              <ul class="rb-stationar__types-list">
                                 <li>заболевания опорно-двигательного аппарата</li>
                                 <li>нервной системы</li>
                                 <li>сердечно-сосудистой системы</li>
                                 <li>дыхательной системы</li>
                                 <li>метаболический синдром</li>
                                 <li>восстановление после оперативного вмешательства (суставы, позвоночник, пластическая хирургия)</li>
                              </ul>

                           </div>
                           <picture class="rb-stationar__types-img col-6 col-s-12">
                              <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/stationar.jpg" alt="" class="rb-img-cover">   
                           </picture>

                     </div>
                     <div class="col-6 col-m-12 rb-stationar__types-item flex-wrap">

                           <div class="rb-stationar__types-info col-6 col-s-12">
                              <h3 class="rb-stationar__types-title">Санаторно-курортное лечение</h3>

                              <ul class="rb-stationar__types-list">
                                 <li>медикаментозная терапия</li>
                                 <li>физиотерапия</li>
                                 <li>лечебная физкультура</li>
                                 <li>мануальная терапия</li>
                                 <li>массаж</li>
                                 <li>рефлексотерапия</li>
                              </ul>
                           </div>
                           <picture class="rb-stationar__types-img col-6 col-s-12">
                              <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/statsionar2.jpg" alt="" class="rb-img-cover">
                           </picture>
                     </div>


               </section>
               <section class="rb-stationar__video-wrap flex-wrap">
                  <div class="rb-stationar__video-text col-6 col-m-12">
                     <span class="rb-title">Для пациентов центра работает круглосуточный стационар</span> 
                     <p class="rb-text">Двухместные и одноместные палаты, палата для маломобильных пациентов с сопровождением и палата интенсивной терапии.</p>
                  </div>
                  <div class="rb-stationar__video col-6 col-m-12">
                     <iframe class="stationar__iframe" src="https://vk.com/video_ext.php?oid=-207014980&id=456239275&hash=9a42e29324199561&hd=2&autoplay=0" width="100%" height="365" allow=" encrypted-media; fullscreen; picture-in-picture;" frameborder="0" allowfullscreen></iframe>
                  </div>
               </section>
         </div>
      </div>



      <div class="rb-stationar__grey">
         <div class="rb-container">
            
            <section class="rb-stationar__item">
               <div class="flex-wrap rb-stationar__item-wrap">
                  <div class="col-6 col-m-12 rb-stationar__item-text">
                     <h2 class="rb-title rb-stationar__item-title">Круглосуточный Стационар</h2>
                     <div class="rb-stationar__item-desc rb-text">
                        <p>Для поступления в круглосуточный стационар необходимо предоставить направление от врача-терапевта или профильного специалиста. Также потребуются результаты флюорографического обследования. Другие исследования (ЭКГ, анализы) можно
                           сделать на базе «Олимп Пять»</p>
                        <p>Маломобильные пациенты проходят лечение по специально разработанному для них плану в сопровождении медработника (дежурной медсестры).</p>
                     </div>
                     <a class="rb-stationar__item-btn rb-stationar__item-btn-3 rb-button__orange js-open-modal" data-stat_id="12754" data-modal="111" href="">Запись в стационар</a>
                  </div>

                  <div class="col-6 col-m-12 rb-stationar__item-slider">
                     <div class="rb-stationar__item-slide--wrap">

                        <div class="swiper" id="rb-stationar">
                           <div class="swiper-wrapper">

                            <div class="swiper-slide rb-stationar__item-slide">
                               <picture class="rb-stationar__item-img">
                                  <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/statsionar3.jpg" alt="" class="rb-img-cover">
                               </picture>
                            </div>
                              
                           </div>
                           <div class="swiper-pagination"></div>
                        </div>
                     </div>
                  </div>
               </div>
               <!-- <div class="rb-stationar__item-info flex">
                  <div class="col-6 col-m-12 rb-stationar__item-info--text">
                     <span class="rb-stationar__item-info--title">Питание входит в стоимость пребывания.</span>
                     
                     <span class="rb-stationar__item-info--subtitle">Стоимость пребывания:</span>
                     <ul>
                        <li>Двухместная палата – 7 500 рублей (с человека).</li>
                        <li>Одноместная палата – 10 000 рублей.</li>
                        <li>Палата повышенной комфортности – 11 000 рублей.</li>
                        <li>Палата повышенной комфортности "семейная" (на 2-х человек) – 12 000 рублей.</li>
                        <li>Палата для маломобильных пациентов с сопровождением – 13 500 рублей.</li>
                     </ul>
                     <p>В стационаре оборудована удобная комната для приёма пищи с холодильником и микроволновой печью.</p>
                     <a class="rb-stationar__item-btn rb-stationar__item-btn-2 rb-button__orange js-open-modal" data-stat_id="12754" data-modal="111" href="">Запись в стационар</a>
                  </div>
               </div> -->
            </section>
         </div>
      </div>

    <?php get_template_part( 'template-part/forms/form', 'subscribe' ); ?>

<?php get_footer(); ?>