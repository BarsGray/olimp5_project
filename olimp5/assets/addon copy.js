function test() {

    // console.log('reinti inputmask')
    var $mask = "+7 (999) 999-99-99";
    $(".PHONE_MASK").inputmask('mask', {
        'mask': $mask
    });


    $(document).on("ajaxComplete", function (e) {
        $(".PHONE_MASK").inputmask('mask', { 'mask': $mask });
        // console.log('reinti ajaxComplete')
    });

}


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



document.addEventListener("DOMContentLoaded", function () {


    $('#video_sections_select').change(function (e) {
        // set the window's location property to the value of the option the user has selected
        window.location = $(this).val();
    });



    var faq = document.getElementsByClassName("faq-page");
    var i;

    for (i = 0; i < faq.length; i++) {
        faq[i].addEventListener("click", function () {
            /* Toggle between adding and removing the "active" class,
            to highlight the button that controls the panel */
            this.classList.toggle("active");

            /* Toggle between hiding and showing the active panel */
            var body = this.parentNode.nextElementSibling;

            console.log(this.parentNode)
            if (body.style.display === "block") {
                body.style.display = "none";
            } else {
                body.style.display = "block";
            }
        });
    }





    var tabs = document.querySelectorAll('.tabbed li');
    var switchers = document.querySelectorAll('.switcher-box a');
    var skinable = document.getElementById('skinable');

    var content_tabs = document.querySelectorAll('.tabbed__content');

    for (var i = 0, len = tabs.length; i < len; i++) {
        tabs[i].addEventListener("click", function (e) {
            e.preventDefault();

            if (this.classList.contains('active'))
                return;

            var parent = this.parentNode,
                innerTabs = parent.querySelectorAll('li');

            for (var index = 0, iLen = innerTabs.length; index < iLen; index++) {
                innerTabs[index].classList.remove('active');
            }

            console.log(this.getAttribute('data-id'))

            content_tabs.forEach(el => el.style.display = 'none')

            const element = document.getElementById('tab_' + this.getAttribute('data-id'))
            element.style.display = 'block';
            this.classList.add('active');
        });
    }

    for (var i = 0, len = switchers.length; i < len; i++) {
        switchers[i].addEventListener("click", function () {
            if (this.classList.contains('active'))
                return;

            var parent = this.parentNode,
                innerSwitchers = parent.querySelectorAll('a'),
                skinName = this.getAttribute('skin');

            for (var index = 0, iLen = innerSwitchers.length; index < iLen; index++) {
                innerSwitchers[index].classList.remove('active');
            }

            this.classList.add('active');
            skinable.className = 'tabbed round ' + skinName;
        });
    }
});


document.onreadystatechange = function () {
    if (document.readyState === "interactive") {


        const sticky = document.querySelectorAll('.js-sticky-widget')
        if (sticky.length) {
            var stickyEl = new Sticksy('.js-sticky-widget', { topSpacing: 140 })
        }


        async function getConsultans(department_id) {
            try {
                const url = '/local/ajax/getconsultants.php/?department_id=' + department_id;
                const response = await fetch(url, {
                   method: 'GET', // или 'PUT'
                    // body: {department_id:department_id}, // данные могут быть 'строкой' или {объектом}!
                //    headers: {
                //       'Content-Type': 'application/json'
                //    }
                });

                return await response.text();

             } catch (error) {
                console.error('Ошибка:', error);
             }
        }


        getConsultans(11767)
            .then(data => {

                const consults_specialists__cards = document.getElementById('consults_specialists__cards');
                if (consults_specialists__cards) {
                    consults_specialists__cards.innerHTML = data;
                    initPopups();
                }
        })


        const consult_department = document.querySelectorAll('.consult_department');
        if (consult_department) {
            function handleConsultDoctorsDep(e) {
                e.preventDefault();
                const department_id = this.dataset.department_id;
                const _this = this;

                getConsultans(department_id)
                    .then(data => {

                        const consults_specialists__cards = document.getElementById('consults_specialists__cards');
                        if (consults_specialists__cards) {
                            consults_specialists__cards.innerHTML = data;
                            consult_department.forEach(el => el.classList.remove('active'));
                            _this.classList.add('active')
                            initPopups();
                        }
                })
            }
            consult_department.forEach(el => el.addEventListener('click', handleConsultDoctorsDep))
        }


        const consult_department_select = document.querySelector('#consult_department_select');
        if (consult_department_select) {

            function handleConsultDoctorsDepSelect(e){
                e.preventDefault();

                getConsultans(this.value)
                    .then(data => {
                        const consults_specialists__cards = document.getElementById('consults_specialists__cards');
                        if (consults_specialists__cards) {
                            consults_specialists__cards.innerHTML = data;
                            initPopups();
                        }
                    })

                e.stopPropagation();

                return false;
            }

            consult_department_select.addEventListener('change', handleConsultDoctorsDepSelect)
        }



        // const consult_department_select = document.getElementById('consult_department_select');
        // if (consult_department_select) {
        //     function handleConsultDoctorsDep(e) {
        //         e.preventDefault();
        //         const department_id = this.dataset.department_id;
        //         const _this = this;

        //         getConsultans(department_id)
        //             .then(data => {

        //                 const consults_specialists__cards = document.getElementById('consults_specialists__cards');
        //                 if (consults_specialists__cards) {
        //                     consults_specialists__cards.innerHTML = data;
        //                     consult_department.forEach(el => el.classList.remove('active'));
        //                     _this.classList.add('active')
        //                     initPopups();
        //                 }
        //             })
        //     }
        //     // consult_department.forEach(el => el.addEventListener('click', handleConsultDoctorsDep))

        // }


        async function fetchData(url, data) {
            try {
               const response = await fetch(url, {
                  method: 'POST', // или 'PUT'
                  body: JSON.stringify(data), // данные могут быть 'строкой' или {объектом}!
                  headers: {
                     'Content-Type': 'application/json'
                  }
               });

               return await response.json();

            } catch (error) {
               console.error('Ошибка:', error);
            }
         }



        const subscribe_form = document.getElementById('subscribe_form');
        const subscribe_form_button = document.getElementById('subscribe_form_button');
        if (subscribe_form_button) {
            function handleSubscriptionButton(e){
                e.preventDefault();

                const email = subscribe_form.querySelector('.subscribe_email').value;
                // alert(email);

                // let formData = new FormData()
                // formData.append("email", email)

                formData = { 'email': email };

                fetchData('/local/ajax/sub.php', formData)
                    .then(res => {

                        const modal__subscribe = document.querySelector('.modal__subscribe')
                        if (modal__subscribe) {


                            $('.modal[data-modal="4"] .ajax_content').html('<div class="mod__head">'+res.msg+'<br/><br /></div>');

                            // test();



                            overlay = document.querySelector('.js-overlay-modal');

                            /* После того как нашли нужное модальное окно, добавим классы подложке и окну чтобы показать их. */
                            modal__subscribe.classList.add('active');
                            overlay.classList.add('active');

                            document.body.classList.add('preventScroll')
                        }
                    });

            }
            subscribe_form_button.addEventListener('click', handleSubscriptionButton)
        }

        const mobile__search_cross = document.querySelector('.mobile__search--cross');
        const header__search_mobile = document.querySelector('.header__search--mobile a');
        const mobile__search = document.querySelector('.mobile__search');
        if (mobile__search) {
            if (header__search_mobile) {
                function handleOpenSearch(e) {
                    e.preventDefault();
                    mobile__search.classList.add('open');
                    document.body.classList.add('mobile-search-open');
                }
                header__search_mobile.addEventListener('click', handleOpenSearch, false);
            }

            if (mobile__search_cross) {
                function handleCloseSearch(e) {
                    e.preventDefault();
                    mobile__search.classList.remove('open');
                    document.body.classList.remove('mobile-search-open');

                    document.querySelector('.title-search-result').style.display = 'none';
                }
                mobile__search_cross.addEventListener('click', handleCloseSearch, false);
            }
        }


        const infographics__cubes = document.querySelectorAll('.infographics__cube--link');
        if (infographics__cubes) {
            infographics__cubes.forEach(el => el.addEventListener('click', e => e.preventDefault()))
        }

        // if (!readCookie('NY4')) {
        //     const NY = document.querySelector('.modal[data-modal="3"]');
        //     if (NY) {
        //         NY.classList.add('active');
        //     }
        //     const overlay = document.querySelector('.js-overlay-modal');
        //     if (overlay) {
        //         overlay.classList.add('active');
        //     }
        //     document.body.classList.add('preventScroll')
        // }



        // $(document.body).on('click', function () {
        //     createCookie('NY4', true, 0.3);
        // });


        /* После того как нашли нужное модальное окно, добавим классы подложке и окну чтобы показать их. */
        // document.querySelector('.modal[data-modal="3"]').classList.add('active');
        // document.querySelector('.js-overlay-modal').classList.add('active');
        // document.body.classList.add('preventScroll')




        var statioanar__slider_daily = new Swiper("#statioanar__slider_daily", {
            autoHeight: true,
            calculateHeight: true,
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
                el: "#statioanar__slider_daily .swiper-pagination",
                // type: "fraction",
            },
            navigation: {
                prevEl: "#statioanar__slider_daily .statioanar_arrow--left",
                nextEl: "#statioanar__slider_daily .statioanar_arrow--right",
            },

            // breakpoints: {
            //     567: {
            //         slidesPerView: 1,
            //         spaceBetween: 0,
            //     },
            //     768: {
            //         slidesPerView: 1,
            //         spaceBetween: 10,
            //     },
            //     996: {
            //         slidesPerView: 1,
            //         spaceBetween: 0,
            //     },

            //     1200: {
            //         slidesPerView: 1,
            //         spaceBetween: 0,
            //     },

            //     1900: {
            //         slidesPerView: 1,
            //         spaceBetween: 0,
            //     },

            // },

        });


        var statioanar__slider_daily = new Swiper("#statioanar__slider", {
            autoHeight: true,
            calculateHeight: true,

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
                el: "#statioanar__slider .swiper-pagination",
                // type: "fraction",
            },
            navigation: {
                prevEl: "#statioanar__slider .statioanar_arrow--left",
                nextEl: "#statioanar__slider .statioanar_arrow--right",
            },

            // breakpoints: {
            //     567: {
            //         slidesPerView: 1,
            //         spaceBetween: 0,
            //     },
            //     768: {
            //         slidesPerView: 1,
            //         spaceBetween: 10,
            //     },
            //     996: {
            //         slidesPerView: 1,
            //         spaceBetween: 0,
            //     },

            //     1200: {
            //         slidesPerView: 1,
            //         spaceBetween: 0,
            //     },

            //     1900: {
            //         slidesPerView: 1,
            //         spaceBetween: 0,
            //     },

            // },

        });



        var swiper__oborudovanie = new Swiper(".swiper__oborudovanie", {
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
                nextEl: ".oborudovanie__next",
                prevEl: ".oborudovanie__prev",
            },

            // breakpoints: {
            //     567: {
            //         slidesPerView: 1,
            //         spaceBetween: 0,
            //     },
            //     768: {
            //         slidesPerView: 1,
            //         spaceBetween: 10,
            //     },
            //     996: {
            //         slidesPerView: 1,
            //         spaceBetween: 0,
            //     },

            //     1200: {
            //         slidesPerView: 1,
            //         spaceBetween: 0,
            //     },

            //     1900: {
            //         slidesPerView: 1,
            //         spaceBetween: 0,
            //     },

            // },

        }); 

        if ( $('#rbSwiper').length ) {

            var mainPageSlider = new Swiper("#rbSwiper", {
                slidesPerView: 1.1,
                spaceBetween: 24,
                // slidesPerGroup: 1,
                centeredSlides: true,
                loop: true,
                // effect: 'fade',
                // fadeEffect: {
                //     crossFade: true
                // },
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

        if ( $('#rbSwiperBot').length ) {

            var mainPageSliderBot = new Swiper("#rbSwiperBot", {
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
                        centeredSlides: true,
                    }

                },

            });

        }

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


        var rbStationar = new Swiper("#rb-stationar", {
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

            // breakpoints: {
            //     567: {
            //         slidesPerView: 1,
            //         spaceBetween: 0,
            //     },
            //     768: {
            //         slidesPerView: 1,
            //         spaceBetween: 10,
            //     },
            //     996: {
            //         slidesPerView: 1,
            //         spaceBetween: 0,
            //     },

            //     1200: {
            //         slidesPerView: 1,
            //         spaceBetween: 0,
            //     },

            //     1900: {
            //         slidesPerView: 1,
            //         spaceBetween: 0,
            //     },

            // },

        });



        const swiper = new Swiper('.swiper_', {
            // Optional parameters
            // direction: 'vertical',
            // loop: true,

            slidesPerView: 3,
            spaceBetween: 30,

            // If we need pagination
            // pagination: {
            //     el: '.swiper-pagination',
            // },



            // Navigation arrows
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },

            // Responsive breakpoints
            breakpoints: {
                // when window width is >= 320px
                320: {
                    slidesPerView: 1,
                    spaceBetween: 20
                },

                // when window width is >= 640px
                1200: {
                    slidesPerView: 3,
                    spaceBetween: 30
                },
                // when window width is >= 640px
                1900: {
                    slidesPerView: 4,
                    spaceBetween: 30
                },

            }

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




        // const sanatorium__calc_form = document.getElementById('sanatorium__calc--form')
        // const sanatorium__calc = document.getElementById('sanatorium__calc')
        // if (sanatorium__calc) {
        //     function handleCalc(e){
        //         e.preventDefault()

        //         alert('shrrsyh');

        //         // const data =
        //     }
        //     sanatorium__calc.addEventListener('click', handleCalc)
        // }




        $("#sanatorium__calc--form").submit(function (e) {
            e.preventDefault();
            const type = document.getElementById('sanatorium__calc--type');
            type.value = 'send_calc';

            $.ajax({
                type: "POST",
                url: "/local/ajax/calc_form.php",
                data: $("#sanatorium__calc--form").serialize(),
                success: function (data) {
                    $("#sanatorium__result").html(data);
                }
            });
        });




        $("#sanatorium__calc").click(function (e) {
            e.preventDefault();
            const type = document.getElementById('sanatorium__calc--type');
            type.value = '';
            $.ajax({
                type: "POST",
                url: "/local/ajax/calc_form.php",
                data: $("#sanatorium__calc--form").serialize(),
                success: function (data) {
                    $("#sanatorium__result").html(data);
                }
            });
        });











        $(".educations").mCustomScrollbar({
            axis: "y",
            setHeight: 300,

        });



        // $(".ajax_content--scroller").mCustomScrollbar({
        //     axis: "y",
        // });





        var fullprice = document.getElementById('fullprice');
        if (fullprice) {
            function handleFullPrice(e) {
                e.preventDefault();

                var service_id = this.dataset.sevice_id;
                var url = '/local/ajax/get_full_price.php?service_id=' + service_id;

                fetch(url, {
                    // headers: { "Content-Type": "text/html; charset=utf-8" }
                })
                    .then((response) => {
                        return response.text();
                    })
                    .then((html) => {
                        document.querySelector('.fullprice_container').innerHTML = html;
                    })
                    .catch(err => {
                        alert("sorry, there are no results for your search")
                    });
            }
            fullprice.addEventListener('click', handleFullPrice);
        }


        !function (e) { "function" != typeof e.matches && (e.matches = e.msMatchesSelector || e.mozMatchesSelector || e.webkitMatchesSelector || function (e) { for (var t = this, o = (t.document || t.ownerDocument).querySelectorAll(e), n = 0; o[n] && o[n] !== t;)++n; return Boolean(o[n]) }), "function" != typeof e.closest && (e.closest = function (e) { for (var t = this; t && 1 === t.nodeType;) { if (t.matches(e)) return t; t = t.parentNode } return null }) }(window.Element.prototype);



        function initPopups() {


            /* Записываем в переменные массив элементов-кнопок и подложку.
               Подложке зададим id, чтобы не влиять на другие элементы с классом overlay*/
            var modalButtons = document.querySelectorAll('.js-open-modal'),
                overlay = document.querySelector('.js-overlay-modal'),
                closeButtons = document.querySelectorAll('.js-modal-close');


            const body = document.body;


            /* Перебираем массив кнопок */
            modalButtons.forEach(function (item) {
                /* Назначаем каждой кнопке обработчик клика */
                item.addEventListener('click', function (e) {

                    /* Предотвращаем стандартное действие элемента. Так как кнопку разные
                       люди могут сделать по-разному. Кто-то сделает ссылку, кто-то кнопку.
                       Нужно подстраховаться. */
                    e.preventDefault();

                    /* При каждом клике на кнопку мы будем забирать содержимое атрибута data-modal
                       и будем искать модальное окно с таким же атрибутом. */
                    var modalId = this.getAttribute('data-modal'),
                        modalElem = document.querySelector('.modal[data-modal="' + modalId + '"]');

                    // modalElem.form[0].reset();

                    // document.forms['order_form'].reset();

                    // $('.modal[data-modal="' + modalId + '"] .ajax_content').load("/local/ajax/order_form.php", function () {
                    // });

                    var data_type_id = this.getAttribute('data-type_id_');
                    var type_id = e.target.dataset.type_id;
                    var doctor_id = e.target.dataset.doctor_id;
                    var napravlenie_id = e.target.dataset.napravlenie_id;
                    var program_id = e.target.dataset.program_id;
                    var event_id = e.target.dataset.event_id;
                    var stat_id = e.target.dataset.stat_id;

                    var popup = this.getAttribute('data-popup');

                    // console.log('popup=', popup);

                    // console.log('event_id', event_id);

                    if (!popup) {

                        $('.modal[data-modal="' + modalId + '"] .ajax_content').html('');

                        // console.log(modalId)

                        let url = '/local/ajax/order_form.php';
                        // url = '/local/ajax/order_form.php?service=' + type_id;

                        if (type_id) {
                            url = url + '?service=' + type_id
                        }

                        if (doctor_id) {
                            url = url + '?doctor_id=' + doctor_id;
                        }

                        if (napravlenie_id) {
                            url = url + '?napravlenie_id=' + napravlenie_id;
                        }


                        if (program_id) {
                            url = url + '?program_id=' + program_id;
                        }

                        if (event_id) {
                            url = '/local/ajax/order_event.php';
                            url = url + '?event_id=' + event_id;
                        }

                        if (stat_id) {
                            url = '/local/ajax/order_stat.php';
                            url = url + '?stat_id=' + stat_id;
                        }

                        // if (modalId == 1) {
                        //     url = '/local/ajax/order_form.php';
                        // }

                        // if (modalId == 2) {
                        //     url = '/local/ajax/order_service_form.php';
                        // }

                        console.log(url)
                        fetch(url, {
                            // headers: { "Content-Type": "text/html; charset=utf-8" }
                        })
                        .then((response) => {
                            return response.text();
                        })
                        .then((html) => {
                            // here you do what you want with response
                            // popup__content.innerHTML = html;
                            // popup.classList.add('open');
                            // console.log(html);
                            // alert(html);
                            $('.modal[data-modal="' + modalId + '"] .ajax_content').html(html);

                            // test();



                            /* После того как нашли нужное модальное окно, добавим классы подложке и окну чтобы показать их. */
                            modalElem.classList.add('active');
                            overlay.classList.add('active');

                            body.classList.add('preventScroll')
                        })
                        .catch(err => {
                            alert("sorry, there are no results for your search")
                        });

                    } else {
                        /* После того как нашли нужное модальное окно, добавим классы подложке и окну чтобы показать их. */
                        modalElem.classList.add('active');
                        overlay.classList.add('active');
                        body.classList.add('preventScroll')
                    }



                }, true); // end click

            }); // end foreach




        const popup__open_services = document.querySelectorAll('.popup__open--service');
        if (popup__open_services) {
            function handleTogglePrice(e) {
                e.preventDefault();

                popup__open_services.forEach(function (el) {
                    el.classList.remove('active');
                })

                this.classList.toggle('active');
            }
            popup__open_services.forEach(function (el) {
                el.addEventListener('click', handleTogglePrice);
            })
        }


        closeButtons.forEach(function (item) {
            item.addEventListener('click', function (e) {
                var parentModal = this.closest('.modal');

                parentModal.classList.remove('active');
                overlay.classList.remove('active');
                body.classList.remove('preventScroll')
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



        var mask = "+7 (999) 999-99-99";
        $(".PHONE_MASK").inputmask('mask', { 'mask': mask });

        $(document).on("ajaxComplete", function (e) {
            $(".PHONE_MASK").inputmask('mask', { 'mask': mask });
        });

        // $("#sendform").submit(function (e) {
        //     e.preventDefault();
        //     $.ajax({
        //         type: "POST",
        //         url: "/local/ajax/order_form.php",
        //         data: $("#sendform").serialize(),
        //         success: function (data) {
        //             $("#sendform").html(data);
        //         }
        //     });
        // });


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

        // const scrollTo = document.querySelectorAll('.scrollTo');
        // if (scrollTo) {
        //     function handleScrollTo(e) {
        //         e.preventDefault();

        //         $('html, body').stop().animate({
        //             scrollTop: offsetTop
        //         }, 300);
        //     }
        //     scrollTo.forEach(el => el.addEventListener('click', handleScrollTo))
        // }

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

        $(window).on('load resize', function(){

            if( $(window).width() < 768 ) {

                $('.rb-header__menu-list').insertAfter(".rb-header__location");

            } else {

                $('.rb-header__menu-list').appendTo(".rb-header__menu-wrapper");

            }
            //rb-header__menu-wrapper

        })
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