(function($){

    //phone mask
    function PHONE_MASK() {

        var $mask = "+7 (999) 999-99-99";
        $(".PHONE_MASK").inputmask('mask', {
            'mask': $mask
        });

        $(document).on("ajaxComplete", function (e) {
            $(".PHONE_MASK").inputmask('mask', { 'mask': $mask });
        });

    }
    PHONE_MASK();



    //cookie
    /*
      * Create cookie with name and value.
      * In your case the value will be a json array.
      */
    function createCookie(name, value, days) {
        var expires = '',
            date = new Date();
        if (days) {
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = '; expires=' + date.toGMTString();
        }
        document.cookie = name + '=' + value + expires + '; path=/';
    }
    /*
    * Read cookie by name.
    * In your case the return value will be a json array with list of pages saved.
    */
    function readCookie(name) {
        var nameEQ = name + '=',
            allCookies = document.cookie.split(';'),
            i,
            cookie;
        for (i = 0; i < allCookies.length; i += 1) {
            cookie = allCookies[i];
            while (cookie.charAt(0) === ' ') {
                cookie = cookie.substring(1, cookie.length);
            }
            if (cookie.indexOf(nameEQ) === 0) {
                return cookie.substring(nameEQ.length, cookie.length);
            }
        }
        return null;
    }
    function eraseCookie(name) {
        createCookie(name, "", -1);
    }


    document.onreadystatechange = function () {
        if (document.readyState === "interactive") {


            if ( $('#rbSwiper').length ) {

                var mainPageSlider = new Swiper("#rbSwiper", {
                    slidesPerView: 1.1,
                    spaceBetween: 24,
                    // slidesPerGroup: 1,
                    centeredSlides: true,
                    loop: true,
                    effect: 'fade',
                    fadeEffect: {
                        crossFade: true
                    },
                    autoplay: {
                        delay: 4000,
                        disableOnInteraction: false,
                        pauseOnMouseEnter: true,
                    },
                    pagination: {
                        el: ".rb-main__slider .swiper-pagination",
                        // type: "fraction",
                        // dynamicBullets: true,
                        clickable: true,
                    },
                    navigation: {
                        nextEl: ".swiper-button-next",
                        prevEl: ".swiper-button-prev",
                    },
                    breakpoints: {
                        1320: {
                            slidesPerView: "auto",
                        }

                    },
                });

            }

            //page-about
            const rb_about_swiper = new Swiper('#rb-about__reviews-slider', {
                slidesPerView: 1,
                spaceBetween: 30,
                autoHeight: true,
                calculateHeight: true,
                loop: true,
                // Navigation arrows
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    1200: {
                        slidesPerView: 2,
                    },

                }

            });

            if( $('#rb-doctor__reviews-slider').length ) {
                const rb_about_swiper = new Swiper('#rb-doctor__reviews-slider', {
                    slidesPerView: 1,
                    spaceBetween: 30,
                    autoHeight: true,
                    calculateHeight: true,
                    loop: true,
                    // Navigation arrows
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    breakpoints: {
                        1200: {
                            slidesPerView: 2,
                        },

                    }

                });
            }

             var rbStationarDayly = new Swiper("#rb-stationar-dayly", {
                // autoHeight: true,
                // calculateHeight: true,
                slidesPerView: 1,
                slidesPerGroup: 1,
                effect: 'fade',
                fadeEffect: {
                    crossFade: true
                },
                autoplay: {
                    delay: 4000,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true,
                },
                pagination: {
                    el: ".swiper-pagination",
                    // dynamicBullets: true,
                    clickable: true,
                },

            });



            const school_archive = new Swiper('#school-archive', {
                slidesPerView: 1,
                spaceBetween: 20,
                autoHeight: true,
                // calculateHeight: true,
                loop: true,
                // Navigation arrows
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    768: {
                        slidesPerView: 2,
                    },
                    1320: {
                        slidesPerView: 4,
                    },

                }

            });

            

            var video_school_archive = new Swiper('#school-video-archive', {
                slidesPerView: 1,
                spaceBetween: 20,
                autoHeight: true,
                // calculateHeight: true,
                loop: true,
                // Navigation arrows
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    768: {
                        slidesPerView: 2,
                    },
                    1320: {
                        slidesPerView: 4,
                    },

                }

            });

            var video_school_archive2 = new Swiper('#school-video-archive2', {
                slidesPerView: 1,
                spaceBetween: 20,
                autoHeight: true,
                // calculateHeight: true,
                loop: false,
                // Navigation arrows
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    768: {
                        slidesPerView: 2,
                    },
                    1320: {
                        slidesPerView: 4,
                    },

                }

            });

            var swiper25 = new Swiper(".mySwiper25", {
                slidesPerView: 1,
                // slidesPerGroup: 1,
                effect: 'fade',
                fadeEffect: {
                    crossFade: true
                },
                autoplay: {
                    delay: 4000,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true,
                },
                pagination: {
                    el: ".swiper-pagination",
                    type: "fraction",
                },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },


            });


            $('body').on('click', '.rb-school__archive-menu--tab', function(){

                const tabID = $(this).data('tab');

                $('.rb-school__archive-menu--tab.active').removeClass('active');
                $(this).addClass('active');
                $('.rb-school__archive-sect.rb-school__archive-sect-active').removeClass('rb-school__archive-sect-active');
                $('.rb-school__archive-sect[data-tab=' + tabID + ']').addClass('rb-school__archive-sect-active');

                video_school_archive.update();
                video_school_archive2.update();


            });

            const sanatorium__selector_pluses = document.querySelectorAll('.sanatorium__selector--plus');
            const sanatorium__selector_minuses = document.querySelectorAll('.sanatorium__selector--minus');

            if(sanatorium__selector_pluses) {
                function handlePlus(e){
                    e.preventDefault();
                    const val = this.parentElement.querySelector('input');
                    val.value = parseInt(val.value) + 1;
                    console.log(val.value);
                }

                sanatorium__selector_pluses.forEach(el => {
                    el.addEventListener('click', handlePlus)
                })
            }

            if(sanatorium__selector_minuses) {
                function handleMinus(e){
                    e.preventDefault();
                    const val = this.parentElement.querySelector('input');
                    val.value = parseInt(val.value) - 1;
                    if(val.value < 0) val.value = 0;
                }

                sanatorium__selector_minuses.forEach(el => {
                    el.addEventListener('click', handleMinus)
                })
            }

            !function (e) { "function" != typeof e.matches && (e.matches = e.msMatchesSelector || e.mozMatchesSelector || e.webkitMatchesSelector || function (e) { for (var t = this, o = (t.document || t.ownerDocument).querySelectorAll(e), n = 0; o[n] && o[n] !== t;)++n; return Boolean(o[n]) }), "function" != typeof e.closest && (e.closest = function (e) { for (var t = this; t && 1 === t.nodeType;) { if (t.matches(e)) return t; t = t.parentNode } return null }) }(window.Element.prototype);



            function initPopups() {



                const modalButtons = document.querySelectorAll('.js-open-modal'),
                    overlay = document.querySelector('.js-overlay-modal'),
                    closeButtons = document.querySelectorAll('.js-modal-close'),
                    body = document.body,
                    modalElem = document.querySelector('.popup-modal');

                modalButtons.forEach(function (item) {
                    item.addEventListener('click', function (e) {
                        e.preventDefault();

                        var doctorsName = this.getAttribute('data-doctors'),
                            serviceName = this.getAttribute('data-service'),
                            servicestaxName = this.getAttribute('data-servicestax'),
                            programmsName = this.getAttribute('data-programms');


                        modalElem.querySelector('input[name="user_service"]').value = serviceName;
                        modalElem.querySelector('input[name="user_doctors"]').value = doctorsName;
                        modalElem.querySelector('input[name="user_programms"]').value = programmsName;
                        modalElem.querySelector('input[name="user_servicestax"]').value = servicestaxName;


                        if ( $(this).hasClass('js-open-modal--spec') ) {

                            $('.rb-popup__modal--item').removeClass('rb-popup__modal--hide');

                        }


                        modalElem.classList.add('active');
                        overlay.classList.add('active');

                        body.classList.add('preventScroll');

                    });
                }); // end foreach
            


            closeButtons.forEach(function (item) {
                item.addEventListener('click', function (e) {
                    var parentModal = this.closest('.modal');

                    parentModal.classList.remove('active');
                    overlay.classList.remove('active');
                    body.classList.remove('preventScroll')
                    modalElem.querySelector('input[name="user_service"]').value = '';
                    modalElem.querySelector('input[name="user_doctors"]').value = '';
                    modalElem.querySelector('input[name="user_programms"]').value = '';
                    modalElem.querySelector('input[name="user_servicestax"]').value = '';
                });
            }); // end foreach


            document.body.addEventListener('keyup', function (e) {
                var key = e.keyCode;
                if (key == 27) {
                    document.querySelector('.modal.active').classList.remove('active');
                    document.querySelector('.overlay').classList.remove('active');
                };
            }, false);

            if (overlay) {

                overlay.addEventListener('click', function () {

                    const modal_active = document.querySelector('.modal.active');
                    if (modal_active) {
                        modal_active.classList.remove('active');
                    }

                    this.classList.remove('active');
                    document.querySelector('body').classList.remove('preventScroll')
                });

            }


        }


        initPopups();



            // do something here...
            console.log('DOM ready')

            const menuItems2 = $('.scrollTo');
            const topMenuHeight = 140;

            // so we can get a fancy scroll animation
            menuItems2.click(function (e) {
                e.preventDefault();
                var href = $(this).attr("href"),
                    offsetTop = href === "#" ? 0 : $(href).offset().top - topMenuHeight + 1;

                $('html, body').stop().animate({
                    scrollTop: offsetTop
                }, 300);

                console.log(href)

                if (href === '#more') {
                    document.getElementById('more').classList.add('open');
                }
            });


            $('.dropdown').change(function () {
                var path = $(this).val();
                window.location = '/' + path;
            });


            $('body').on('click', '.rb-header__mob-search', function(){

                $('.rb-header__search').toggleClass('show-search');
                $('.rb-header__top').removeClass('rb-header-show').parent().removeClass('rb-show-head');
                $('.rb-header__bot').removeClass('rb-header-show');
                $('.rb-header__burger').removeClass('rb-burger-show');

            });

            $('body').on('click', '.rb-header__burger', function(){

                $('.rb-header__top').toggleClass('rb-header-show').parent().toggleClass('rb-show-head');
                $('.rb-header__bot').toggleClass('rb-header-show');
                $(this).toggleClass('rb-burger-show');
                $('.rb-header__search').removeClass('show-search');

            });

            $('body').on('click', '.rb-contacts__other-question', function(){

                $(this).parent().toggleClass('rb-contacts__other-show');

            });

        }
    };

    function handleResize(e) {
        //    xxxl: 123em,
        //    xxl: 105em,
        //    xl: 90em,
        //    lg: 80em,
        //    md: 64em,
        //    sm: 48em,
        //    xs: 36em)

        let ww = '';

        if (window.innerWidth < 576) ww = 'xs';
        if (window.innerWidth < 768 && window.innerWidth >= 576) ww = 'sm';
        if (window.innerWidth < 1024 && window.innerWidth >= 768) ww = 'md';
        if (window.innerWidth < 1280 && window.innerWidth >= 1024) ww = 'lg';
        if (window.innerWidth < 1440 && window.innerWidth >= 1280) ww = 'xl';
        if (window.innerWidth < 1680 && window.innerWidth >= 1440) ww = 'xxl';
        if (window.innerWidth < 1968 && window.innerWidth >= 1680) ww = 'xxxl';

        console.log(window.innerWidth, ww);
        const size = document.getElementById('size');
        if (size) {
            size.innerHTML = ww;
        }
    }


    window.addEventListener('load', function () {
        handleResize();
        // do something here ...
        console.log('window ready')
        window.addEventListener('resize', handleResize)
    }, false);


    document.addEventListener("DOMContentLoaded", function(event) {
      Fancybox.bind('[data-fancybox="video"]');
      Fancybox.bind('[data-fancybox="video-gallery"]');
      Fancybox.bind('[data-fancybox="show-photo"]');
      });


    const ajax_url = ajax_path.url;


    /*поиск*/
    //ajax_search
    $( 'input#smart-title-search-input' ).autocomplete({
        source: function( request, response ) {

            $.ajax( {
                url : ajax_url, // URL ajax-запроса, /wp-admin/admin-ajax.php
                data : {
                    action : 'rb_header_ajax_search',
                    term : request.term // поисковой запрос
                },
                success : function( data ) {
                    
                    if( data != null && data.length ){

                        $('.title-search-result').addClass('show-search').html(data);

                    } else {

                        $('.title-search-result').removeClass('show-search').html('')

                    }
                    
                }
            } );
     
        },
        select: function( event, ui ) {
            //console.log( ui );
            window.location = ui.item.url;
        }
    });


    
    //prevent search from submitting by enter
    // $('body').on('click', 'input#smart-title-search-input', function(e){

    //     $(window).keydown(function(event){
    //         if(event.keyCode == 13) {
    //           event.preventDefault();
    //           return false;
    //         }
    //       });
        
    // });


    $('body').on('click',function(e){

       if( !(($(e.target).closest("#smart-title-search-input").length > 0 ) || ($(e.target).closest(".search-result-item").length > 0)) && $('.title-search-result').hasClass('show-search') ){

         $('.title-search-result').removeClass('show-search').html('')

       }

    });

    //ajax_search doctors page
    $( 'input#doctors_search_input' ).autocomplete({
        source: function( request, response ) {

            $.ajax( {
                url : ajax_url, // URL ajax-запроса, /wp-admin/admin-ajax.php
                data : {
                    action : 'rb_doctors_ajax_search',
                    term : request.term // поисковой запрос
                },
                success : function( data ) {
                    
                    if( data != null && data.length ){

                        $('.search-doctors-wrap').addClass('show-search').html(data);

                    } else {

                        $('.search-doctors-wrap').removeClass('show-search').html('')

                    }
                    
                }
            } );
     
        },
        select: function( event, ui ) {
            //console.log( ui );
            window.location = ui.item.url;
        }
    });


    // $('body').on('click',function(e){

    //    if( !(($(e.target).closest("#smart-title-search-input").length > 0 ) || ($(e.target).closest(".search-result-item").length > 0)) && $('.title-search-result').hasClass('show-search') ){

    //      $('.title-search-result').removeClass('show-search').html('')

    //    }

    // });


    //popup form
   const rbPopupForm = $('#rb-popup-form'),
         rbPopupFormBtn = rbPopupForm.find('.rb-btn-submit');

   if( rbPopupForm.length ) {

       rbPopupForm.on('submit', function (e) {
          e.preventDefault();

           $.ajax({
               type: 'POST',
               url: ajax_url,
               data: rbPopupForm.serialize(),
               dataType: 'json',
               beforeSend: function() {
                   // rbCommentFormBtn.find('span').text('Загружаем...');
                    // console.log( rbPopupForm.serialize() );
               },
               success: function (data) {

                   if( data.result == 'success' ){
                        $('.popup-modal').removeClass('active');
                        $('.rb-thakyou-modal').addClass('active');
                        rbPopupForm.trigger("reset");
                   }
        
                    if ( $('#seo_ym').length > 0 ) {

                        const ymTag = $('#seo_ym').val();
                        ym( 84731377,'reachGoal', ymTag );

                    }
        

               },
               error: function(xhr, status, error) {
                 console.log(xhr.responseText);
                 console.log(status);
                 console.log(error);
                 console.log(xhr);
               }
           });

           return false;
       });
   }

    //main page bot form
    const rbMainBotForm = $('#rb-main-bot');

    if( rbMainBotForm.length ) {

       rbMainBotForm.on('submit', function (e) {
          e.preventDefault();

           $.ajax({
               type: 'POST',
               url: ajax_url,
               data: rbMainBotForm.serialize(),
               dataType: 'json',
               beforeSend: function() {
                   // rbCommentFormBtn.find('span').text('Загружаем...');
                    // console.log( rbPopupForm.serialize() );
               },
               success: function (data) {

                   if( data.result == 'success' ){
                        rbMainBotForm.trigger("reset");
                        $('.rb-thakyou-modal').addClass('active');
                   }

               },
               error: function(xhr, status, error) {
                 console.log(xhr.responseText);
                 console.log(status);
                 console.log(error);
                 console.log(xhr);
               }
           });

           return false;
       });
    }

    //main page top form
    const rbMainTopForm = $('.rb-mainpage--form');

    if( rbMainTopForm.length ) {

       rbMainTopForm.on('submit', function (e) {

          e.preventDefault();

           $.ajax({
               type: 'POST',
               url: ajax_url,
               data: rbMainTopForm.serialize(),
               dataType: 'json',
               beforeSend: function() {
                   // rbCommentFormBtn.find('span').text('Загружаем...');
                    // console.log( rbPopupForm.serialize() );
               },
               success: function (data) {

                   if( data.result == 'success' ){
                        rbMainTopForm.trigger("reset");
                        $('.rb-thakyou-modal').addClass('active');
                   }

               },
               error: function(xhr, status, error) {
                 console.log(xhr.responseText);
                 console.log(status);
                 console.log(error);
                 console.log(xhr);
               }
           });

           return false;
       });
    }

    //main page bot form
    const rbMainbotForm = $('.rb-mainpage--form-bot');

    if( rbMainbotForm.length ) {

       rbMainbotForm.on('submit', function (e) {
        
          e.preventDefault();

           $.ajax({
               type: 'POST',
               url: ajax_url,
               data: rbMainbotForm.serialize(),
               dataType: 'json',
               beforeSend: function() {
                   // rbCommentFormBtn.find('span').text('Загружаем...');
                    // console.log( rbPopupForm.serialize() );
               },
               success: function (data) {

                   if( data.result == 'success' ){
                        rbMainbotForm.trigger("reset");
                        $('.rb-thakyou-modal').addClass('active');
                   }

               },
               error: function(xhr, status, error) {
                 console.log(xhr.responseText);
                 console.log(status);
                 console.log(error);
                 console.log(xhr);
               }
           });

           return false;
       });
    }

    //main page market form
    const rbMarketForm = $('#rb-form-market');

    if( rbMarketForm.length ) {

       rbMarketForm.on('submit', function (e) {
          e.preventDefault();

           $.ajax({
               type: 'POST',
               url: ajax_url,
               data: rbMarketForm.serialize(),
               dataType: 'json',
               beforeSend: function() {
                   // rbCommentFormBtn.find('span').text('Загружаем...');
                    // console.log( rbPopupForm.serialize() );
               },
               success: function (data) {

                   if( data.result == 'success' ){
                        rbMarketForm.trigger("reset");
                        $('.rb-thakyou-modal').addClass('active');
                   }

               },
               error: function(xhr, status, error) {
                 console.log(xhr.responseText);
                 console.log(status);
                 console.log(error);
                 console.log(xhr);
               }
           });

           return false;
       });
    }

    let mainPageSliderBot;

    if ( $('#rbSwiperBot').length ) {

        mainPageSliderBot = new Swiper("#rbSwiperBot", {
            slidesPerView: "auto",
            spaceBetween: 20,
            autoHeight: true,
            calculateHeight: true,
            pagination: {
                el: ".rb-news__body--wrap .swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                768: {
                    slidesPerView: 1,
                    // spaceBetween: 20,
                    // centeredSlides: true,
                },
                1320: {
                    slidesPerView: 1.3,
                    // centeredSlides: true,
                }

            },

        });

        $('body').on('click', '.category_selector', function(){

            const   localThis = $(this),
                    currCat = localThis.data('catid');

            $.ajax({
               type: 'POST',
               url: ajax_url,
               data: {
                    action: 'rb_change_cat',
                    currcat: currCat
               },
               dataType: 'json',
               beforeSend: function() {
                // mainPageSliderBot.destroy(false);
                   // rbCommentFormBtn.find('span').text('Загружаем...');
                    // console.log( rbPopupForm.serialize() );
               },
               success: function (data) {

                   if( data.result == 'success' ){

                        mainPageSliderBot.destroy(false);
                        $('#rbSwiperBot').find('.filter_list').html(data.content);
                        // rbMarketForm.reset();
                        // $('.rb-thakyou-modal').addClass('active');

                        mainPageSliderBot = new Swiper("#rbSwiperBot", {
                            slidesPerView: "auto",
                            spaceBetween: 20,
                            autoHeight: true,
                            calculateHeight: true,
                            pagination: {
                                el: ".rb-news__body--wrap .swiper-pagination",
                                clickable: true,
                            },
                            navigation: {
                                nextEl: ".swiper-button-next",
                                prevEl: ".swiper-button-prev",
                            },
                            breakpoints: {
                                768: {
                                    slidesPerView: 1,
                                    // spaceBetween: 20,
                                    // centeredSlides: true,
                                },
                                1320: {
                                    slidesPerView: 1.3,
                                    // centeredSlides: true,
                                }

                            },

                        });

                        $('.category_selector.active').removeClass('active');
                        localThis.addClass('active');

                        $('.rb-news__main-btn.rb-news__main-btn--active').removeClass('rb-news__main-btn--active');
                        $('.rb-news__main-btn[data-catid='+currCat+']').addClass('rb-news__main-btn--active');

                   }

               },
               error: function(xhr, status, error) {
                 console.log(xhr.responseText);
                 console.log(status);
                 console.log(error);
                 console.log(xhr);
               }
        });

    });

    }




    //main page top form
    const rbSanatoriumForm = $('#rb-sanatorium-form'),
          rbSanatoriumFormBtn = $('#rb-sanatorium-calc');

    if( rbSanatoriumForm.length ) {

       rbSanatoriumFormBtn.on('click', function (e) {
          e.preventDefault();

            const sanatoriumName = rbSanatoriumForm.find('#rb-calc-name'),
                  sanatoriumPhone = rbSanatoriumForm.find('#rb-calc-phone');

            if ( sanatoriumName.val().length == 0 ) {

                sanatoriumName.addClass('rb-calc-red');

            } else {

                sanatoriumName.removeClass('rb-calc-red');

            }

            if ( sanatoriumPhone.val().length == 0 ) {

                sanatoriumPhone.addClass('rb-calc-red');

            } else {

                sanatoriumPhone.removeClass('rb-calc-red');

            }


            $.ajax({
               type: 'POST',
               url: ajax_url,
               data: rbSanatoriumForm.serialize(),
               dataType: 'json',
               beforeSend: function() {
                   // rbCommentFormBtn.find('span').text('Загружаем...');
                    console.log( rbSanatoriumForm.serialize() );
               },
               success: function (data) {

                   if( data.result == 'success' ){
                        rbSanatoriumForm.trigger("reset");
                        $('.rb-thakyou-modal').addClass('active');
                   } else {

                        console.log(data);
                        $('.rb-calc__message').addClass('show-message').text(data.content);

                   }

               },
               error: function(xhr, status, error) {
                 console.log(xhr.responseText);
                 console.log(status);
                 console.log(error);
                 console.log(xhr);
               }
           });

           return false;

       });
    }


    /*page video*/

    document.addEventListener("DOMContentLoaded", function(event) {
        Fancybox.bind('[data-fancybox="video"]');
    });


    //page video form
    const rbVideoForm = $('#rb-video-form');

    if( rbVideoForm.length ) {

        // rbVideoForm.find('#subscribe_form_button').on('click', function (e) {

        //     console.log( rbVideoForm.serialize() );

        // });

       rbVideoForm.on('submit', function (e) {
          e.preventDefault();

           $.ajax({
               type: 'POST',
               url: ajax_url,
               data: rbVideoForm.serialize(),
               dataType: 'json',
               beforeSend: function() {
                   // rbCommentFormBtn.find('span').text('Загружаем...');
                    // console.log( rbVideoForm.serialize() );
               },
               success: function (data) {

                   if( data.result == 'success' ){
                        rbVideoForm.trigger("reset");
                        createCookie('video__cookie', true, 365);
                        $('.rb-thakyou-modal').addClass('active');
                        location.reload();
                   }

               },
               error: function(xhr, status, error) {
                 console.log(xhr.responseText);
                 console.log(status);
                 console.log(error);
                 console.log(xhr);
               }
           });

           return false;
       });
    }

    //hide/show elem

    $('.rb-service__methodics-show').click(function(){

        $(this).toggleClass('methodic-show').parent().find('.rb-service__methodics-item--desc').slideToggle();

        if( $(this).hasClass('methodic-show') ){

            $(this).text('Скрыть');

        } else {

            $(this).text('Показать');            

        }

    });


    //rb show popup sert

    $('.rb-js-open-modal').on('click', function(){

        $('.rb-popup-modal').addClass('active');
        $('.js-overlay-modal').addClass('active');
        $('body').addClass('preventScroll');

    });

    $('.js-psy-open-modal').on('click', function(){

        if ( $(this).hasClass('js-psy-open-modal-spec') ) {

            $('.rb-psy-1').hide();
            $('.rb-psy-2').show();

        } else {

            $('.rb-psy-2').hide();
            $('.rb-psy-1').show();

        }

        $('.popup-psy-modal').addClass('active');
        $('.js-overlay-modal').addClass('active');
        $('body').addClass('preventScroll');

    });

    $('.rb-popup-sert--select').click(function(){

        $('.rb-popup-sert--select-list').toggleClass('rb-active');

    });

    $('.rb-popup-sert--select-list li').click(function(){

        if( ! $(this).hasClass('rb-active') ){

            $('.rb-popup-sert--select-list li.rb-active').removeClass('rb-active');
            $(this).addClass('rb-active');
            $('#sert_cost').val($(this).data('val'));
            $('.rb-popup-sert--select-title').text($(this).text());

        }

    });

    //page video form
    const promoBtn = $('#rb_upload_more_promo');

    if( promoBtn.length ) {

        // rbVideoForm.find('#subscribe_form_button').on('click', function (e) {

        //     console.log( rbVideoForm.serialize() );

        // });

       promoBtn.on('click', function (e) {

           $.ajax({
               type: 'POST',
               url: ajax_url,
               data: {
                    action: 'rb_upload_more_promo',
                    tax: promoBtn.data('tax'),
               },
               dataType: 'json',
               beforeSend: function() {
               },
               success: function (data) {

                   if( data.result == 'success' ){
                            
                        $('.rb-programms__list').append(data.content);
                        promoBtn.hide();
                        
                   }    

               },
               error: function(xhr, status, error) {
                 console.log(xhr.responseText);
                 console.log(status);
                 console.log(error);
                 console.log(xhr);
               }
           });

       });
    }


    $('.rb-psyco__elem-title').click(function(){

        $(this).parent().toggleClass('rb-psyco__elem-open');

    });

    $('.rb-psyco__programms-tabs li').click(function(){

        $('.rb-psyco__programms-tabs li.rb-programms__curr').removeClass('rb-programms__curr');
        $(this).addClass('rb-programms__curr');
        $('.rb-psyco__programms-block-tab.rb-curr-active').removeClass('rb-curr-active');
        $('.rb-psyco__programms-block-tab[data-num="' + $(this).data('num') + '"]').addClass('rb-curr-active');

    });

    $('.popup-psy-modal-btn').click(function(){
        $('.rb-popup-vrach').removeClass('active');
    })

    const psyPage = $('.rb-psyco');

    if ( psyPage.length ) {

        $('html').on('click', 'header, footer', function(e){

            if ( ! psyPage.hasClass('rb-psy-clicked') ) {

                e.preventDefault();

                $('.rb-popup-vrach').addClass('active');
                $('.js-overlay-modal').addClass('active');
                $('body').addClass('preventScroll');

                psyPage.addClass('rb-psy-clicked');

            }
            
        })

    }

    // Function that actually builds the swiper 
    const buildSwiperSlider = sliderElm => {
        const sliderIdentifier = sliderElm.dataset.id;
        return new Swiper(`#${sliderElm.id}`, {
            slidesPerView: 1,
            spaceBetween: 20,
            autoHeight: true,
            calculateHeight: true,
            loop: false,
            // Navigation arrows
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                768: {
                    slidesPerView: 2,
                },
                1320: {
                    slidesPerView: 4,
                },

            }
        });
    }

    // Get all of the swipers on the page
    const allSliders = document.querySelectorAll('.rb-discount-list');

    // Loop over all of the fetched sliders and apply Swiper on each one.
    allSliders.forEach(slider => buildSwiperSlider(slider));

    // Get all of the swipers on the page
    const allArchiveSliders = document.querySelectorAll('.rb-school__archive-list');
    if ( allArchiveSliders.length > 0 ) {
        
        // Loop over all of the fetched sliders and apply Swiper on each one.
        allArchiveSliders.forEach(slider => buildSwiperSlider(slider));    
    }


    $('body').on('click', '.rb-discount-menu li', function(){

        $(this).parent().find('.rb-discount__tab-item.active').removeClass('active');
        $(this).find('.rb-discount__tab-item').addClass('active');

        const numb = $(this).find('.rb-discount__tab-item').data('tab'),
                serv = $(this).find('.rb-discount__tab-item').text();

        $(this).parent().parent().parent().find('.rb-swiper.rb-swiper-show').removeClass('rb-swiper-show');
        $(this).parent().parent().parent().find('.rb-swiper[data-num='+numb+']').addClass('rb-swiper-show');

        $(this).parent().parent().parent().find('.rb-programms-page__btn-modal').attr('data-service', serv);

    });


    $('body').on('click', '.rb-doctors__cat-sublist--parent', function(e){

        e.preventDefault();
        
        if ( $(this).hasClass('active') ){
            $(this).removeClass('active');   
        }else {
            $('.rb-doctors__cat-sublist').removeClass('open');
            $('.rb-doctors__cat-sublist--parent').removeClass('active');
            $(this).addClass('active');    
        }
        $(this).parent().next('.rb-doctors__cat-sublist').toggleClass('open');
        

    });


    $('.rb-service__all--btn span').click(function(){

        $('.rb-service__list-2').addClass('rb-service__list-show');
        $(this).parent().hide();

    });

    $('.rb-header__menu-list li.menu-item-type-custom').click( function(){

        $(this).toggleClass('rb-open-sub-menu');

    } );


    $('body').on('click', '.rb-programms_cat--item', function(){

        const catID = $(this).data('termid');

        $('.rb-programms_cat--item.rb-programms__curr').removeClass('rb-programms__curr');
        $(this).addClass('rb-programms__curr');

        $('.rb-programms__list--wrap.rb-programms__list-curr').removeClass('rb-programms__list-curr');
        $('.rb-programms__list--wrap[data-termid='+catID+']').addClass('rb-programms__list-curr');

    });

    $('body').on( 'click', '.rb-programms__more>span', function(){

        $(this).parent().parent().find('.rb-programms__list.rb-programms__list--hide').removeClass('rb-programms__list--hide ');
        $(this).hide();

    } );


    const rbSchool = $('.rb-school');

    if ( rbSchool.length ){

        console.log(rbSchool.length);

        $('body').on('click', '.rb-school__list--more', function(){

            console.log('1');

            const   rbSchoolThis = $(this),
                    rbSchoolThiscurrCat = rbSchoolThis.data('tax'),
                    rbSchoolThisSibling = rbSchoolThis.parent().find('.space-between');

            $.ajax({
               type: 'POST',
               url: ajax_url,
               data: {
                    action: 'rb_loadmore_videos',
                    currcat: rbSchoolThiscurrCat
               },
               dataType: 'json',
               beforeSend: function() {
                    console.log('2');
                    console.log(rbSchoolThiscurrCat);
               },
               success: function (data) {

                    console.log('3');

                   if( data.result == 'success' ){

                        console.log('4');

                        rbSchoolThisSibling.append(data.content);
                        rbSchoolThis.hide();

                   } else {

                        console.log(data);

                   }

               },
               error: function(xhr, status, error) {
                 console.log(xhr.responseText);
                 console.log(status);
                 console.log(error);
                 console.log(xhr);
               }
            });

        });

    }


    $('body').on('click', '.rb-school__archive-menu--item', function(){

        const catID = $(this).data('tab');

        $('.rb-school__archive-menu--item.active').removeClass('active');
        $(this).addClass('active');

        $('.rb-schedule__list-wrap').addClass('rb-schedule__hide');
        $('.rb-schedule__list-wrap[data-tab='+catID+']').removeClass('rb-schedule__hide');

    });


    $('body').on( 'click', '.js-open-modal-schedule', function(){

        const scheduleName = $(this).data('schedule');

        $('.popup-schedule-modal').addClass('active');
        $('.js-overlay-modal').addClass('active');
        $('body').addClass('preventScroll');

        $('.popup-schedule-modal input[name=prog-name]').attr('val', scheduleName);

    } );

     //popup form
   const rbScheduleForm = $('#rb-schedule-form'),
         rbScheduleFormBtn = rbScheduleForm.find('.popup-schedule-btn');

   if( rbScheduleForm.length ) {

       rbScheduleForm.on('submit', function (e) {
          e.preventDefault();

           $.ajax({
               type: 'POST',
               url: ajax_url,
               data: rbScheduleForm.serialize(),
               dataType: 'json',
               beforeSend: function() {
                   // rbCommentFormBtn.find('span').text('Загружаем...');
                    // console.log( rbPopupForm.serialize() );
               },
               success: function (data) {

                   if( data.result == 'success' ){
                        $('.popup-schedule-modal').removeClass('active');
                        $('.rb-thakyou-modal').addClass('active');
                   }
        

               },
               error: function(xhr, status, error) {
                 console.log(xhr.responseText);
                 console.log(status);
                 console.log(error);
                 console.log(xhr);
               }
           });

           return false;
       });
   }


   if ( $('#rbSwiperProgramms').length ) {

        programmsSlider = new Swiper("#rbSwiperProgramms", {
            slidesPerView: 1,
            spaceBetween: 20,
            autoHeight: true,
            calculateHeight: true,
            pagination: {
                el: ".rb-news__body--wrap .swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                768: {
                    slidesPerView: 1.3,
                    // spaceBetween: 20,
                    // centeredSlides: true,
                },
                1320: {
                    slidesPerView: 2,
                    // centeredSlides: true,
                }

            },

        });

    }

    if ( $('#rbMainEvents').length ) {

        eventsSlider = new Swiper("#rbMainEvents", {
            slidesPerView: 1,
            spaceBetween: 20,
            autoHeight: true,
            calculateHeight: true,
            pagination: {
                el: ".rb-news__body--wrap .swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                768: {
                    slidesPerView: 2,
                    // spaceBetween: 20,
                    // centeredSlides: true,
                },
                1320: {
                    slidesPerView: 3,
                    // centeredSlides: true,
                }

            },

        });

    }


    if ( $('#rbSeminar').length ) {

        eventsSlider = new Swiper("#rbSeminar", {
            slidesPerView: 1,
            spaceBetween: 20,
            autoHeight: true,
            calculateHeight: true,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                768: {
                    slidesPerView: 2,
                    // spaceBetween: 20,
                    // centeredSlides: true,
                },
                1320: {
                    slidesPerView: 3,
                    // centeredSlides: true,
                }

            },

        });

    }

    /*page vychet*/

    if ( $('.rb-vychet').length ) {

        $('body').on('click', '.rb-vychet__form-input', function(){
            $(this).next('.rb-vychet__form-list').toggleClass('open');
        })

        $('body').on('click', '.rb-vychet__form-list li', function(){

            const  currText = $(this).text();

            $('.rb-vychet__form-list li.choosen').removeClass('choosen');

            $(this).addClass('choosen').parent().parent().find('input').prop('val', currText);
            $(this).parent().parent().find('.rb-vychet__form-input').text(currText);
            $(this).parent().removeClass('open');

        });

        $('body').on('click', '.rb-vychet__forwhom-tab', function(){
            
            const   vychetTab = $(this).data('tab'),
                    vychetThis = $(this);

            $.ajax({
               type: 'POST',
               url: ajax_url,
               data: {
                    action: 'rb_vychet_load',
                    data: vychetTab
               },
               dataType: 'json',
               beforeSend: function() {

                    $('.rb-vychet__forwhom-tab.rb-vychet__active').removeClass('rb-vychet__active');
                    vychetThis.addClass('rb-vychet__active');

               },
               success: function (data) {

                   if( data.result == 'success' ){
                        $('.rb-vychet__container').html(data.content);
                        
                        
                   }

                    new AirDatepicker('#rb-vychet-birthday', {
                        selectedDates: [new Date()],
                        position: 'top center'   
                    })

                    new AirDatepicker('#rb-vychet-birthday2', {
                        selectedDates: [new Date()],
                        position: 'top center'     
                    })

                    new AirDatepicker('#rb-vychet-passport-date', {
                        selectedDates: [new Date()],
                        position: 'top center'     
                    })

               },
               error: function(xhr, status, error) {
                 console.log(xhr.responseText);
                 console.log(status);
                 console.log(error);
                 console.log(xhr);
               }
           });

            
            // $('.rb-vychet__wrap-open.rb-vychet__wrap').removeClass('rb-vychet__wrap-open');
            
            // $('.rb-vychet__wrap[data-tab="'+vychetTab+'"]').addClass('rb-vychet__wrap-open');
            

        });

        $('body').on('click', '.rb-vychet__choose-tab', function(){

            const vychetChoose = $(this).text(),
                  vychetTab = $(this).parent().data('where');

            $(this).parent().find('.rb-vychet__choose-tab.rb-vychet__active').removeClass('rb-vychet__active');
            $(this).addClass('rb-vychet__active').parent().parent().find('input').prop('val', vychetChoose);

            if ( vychetTab != undefined ) {

                $('.rb-vychet__form-item[data-where="'+vychetTab+'"]').toggleClass('rb-vychet__form-item-hide');
            }

        });

        new AirDatepicker('#rb-vychet-birthday', {
            selectedDates: [new Date()],
            position: 'top center'   
        })

        new AirDatepicker('#rb-vychet-birthday2', {
            selectedDates: [new Date()],
            position: 'top center'     
        })

        new AirDatepicker('#rb-vychet-passport-date', {
            selectedDates: [new Date()],
            position: 'top center'     
        })

        $('#rb-vychet-form').on('submit', function (e) {
          e.preventDefault();

           $.ajax({
               type: 'POST',
               url: ajax_url,
               data: $('#rb-vychet-form').serialize(),
               dataType: 'json',
               beforeSend: function() {
                   // rbCommentFormBtn.find('span').text('Загружаем...');
                    console.log( $('#rb-vychet-form').serialize() );
               },
               success: function (data) {

                   if( data.result == 'success' ){
                        $('.popup-schedule-modal').removeClass('active');
                        $('.rb-thakyou-modal').addClass('active');
                   }

               },
               error: function(xhr, status, error) {
                 console.log(xhr.responseText);
                 console.log(status);
                 console.log(error);
                 console.log(xhr);
               }
           });

           return false;
       });

    }

    $('body').on('click', '.rb-pricelist__btn', function(){

        $(this).next('.rb-pricelist__sub').toggleClass('show');

    });


})(jQuery)