<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php //=LANGUAGE_ID?>" lang="<?php //=LANGUAGE_ID?>" class="no-js">
	<head>

		<!-- Google Tag Manager -->
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','GTM-MF5TM82M');</script>
		<!-- End Google Tag Manager -->

		<meta name="facebook-domain-verification" content="9zfo8jz6k8vd82fpvzfmew8w5df4nx" />
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="format-detection" content="telephone=no">
		<meta name="format-detection" content="date=no">
		<meta name="format-detection" content="address=no">
		<meta name="format-detection" content="email=no">
		<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
		<meta name="yandex-verification" content="83be9db0bcd51674" />
		<meta name="google-site-verification" content="t3eSiB6qVJN4Oy_mi1ZmiekzmRBatdk4aeYQwTSsVno" />
		<title><?php echo wp_get_document_title(); ?></title>
<link href="https://cdn.jsdelivr.net/gh/oathanrex/font-awesome-pro@main/fontawesome-pro-6.5.2-web/css/all.min.css" rel="stylesheet">

		<?php wp_head(); ?>

		<?php if ( is_page_template( 'template-page/page-sert.php' ) ) : ?>
		    <!-- BEGIN GIFTERY WIDGET CODE -->
		    <script type="text/javascript">
		      (function(){
		        var s = document.createElement('script');
		        s.type = 'text/javascript';s.async = true;
		        s.src = 'https://launcher.giftery.cards/js/index.js';
		        var ss = document.getElementsByTagName('script')[0];
		        ss.parentNode.insertBefore(s, ss);
		      })();
		    </script>
		    <!-- END GIFTERY WIDGET CODE -->
		<?php endif; ?>

		<script>
			// Фиксация меню
			document.addEventListener('DOMContentLoaded', function() {
				const headerBot = document.querySelector('.rb-header__bot');
				const scrollOffset = 100; // Когда начинать фиксировать
				const adminBar = document.getElementById('wpadminbar');

				function updateHeaderPosition() {
					if (window.pageYOffset > scrollOffset) {
						headerBot.classList.add('fixed');

						// Если есть админ-панель, добавляем отступ
						if (adminBar) {
							const adminBarHeight = adminBar.offsetHeight;
							headerBot.style.top = adminBarHeight + 'px';
						} else {
							headerBot.style.top = '0';
						}
					} else {
						headerBot.classList.remove('fixed');
						headerBot.style.top = '';
					}
				}

				window.addEventListener('scroll', updateHeaderPosition);
				window.addEventListener('resize', updateHeaderPosition);

				// Инициализация при загрузке, если страница уже прокручена
				updateHeaderPosition();
			});
			
			// Обработка кнопки показа строки поиска для ПК-версии
			
		</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchButton = document.querySelector('.rb-header__mob-search');
    const searchBlock = document.querySelector('.rb-header__search');
    
    searchButton.addEventListener('click', function (e) {
        e.stopPropagation(); // Не даём событию всплыть
        searchBlock.classList.add('show-search');
    });

    document.addEventListener('click', function (e) {
        if (!searchBlock.contains(e.target) && !searchButton.contains(e.target)) {
            searchBlock.classList.remove('show-search');
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            searchBlock.classList.remove('show-search');
        }
    });
	
});
</script>
		<script>
document.addEventListener('DOMContentLoaded', function () {
    const headers = document.querySelectorAll(
        '.rb-page-header h1, h1.rb-page-header'
    );

    const MAX_LENGTH = 18;

    headers.forEach(h1 => {
        const textLength = h1.textContent.trim().length;

        if (textLength > MAX_LENGTH) {
            h1.classList.add('long');

            if (h1.closest('.rb-page-header')) {
                h1.closest('.rb-page-header').classList.add('long');
            }
        }
    });
});
</script>

    <style>
.title-search-result.show-search, .search-doctors-wrap.show-search
 {
    display: block;
    position: absolute;
    top: auto;
    max-height: 48vh;
    height: auto;
    overflow-y: scroll;
    background-color: #fff;
    z-index: 4;
    border-radius: 2px;
    box-shadow: 0px 0px 7px -2px #d6d6d6;
   /* margin-top: 62px;*/
}
		ul.search-doctors-wrap.show-search {
    width: auto;
    height: 370px;
}

        .rb-container {
            max-width: 1320px;
            margin-left: auto;
            margin-right: auto;
            padding: 0 20px;
        }

        .no_padding {
            padding: 0 !important;
        }

        .rb-flex {
            display: flex;
        }

        .f-center-center {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .f-center-end {
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .rb-header {
            font-family: 'Manrope', sans-serif;
            font-size: 14px;
            line-height: 120%;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,.1);
            padding-bottom: -5px;
            margin-bottom: 5px;
        }

        .rb-header__top {
            padding-top: 24px;
            margin-top: 0;
            margin-bottom: 10px;
        }

        .rb-header__logo svg, .rb-header__logo-link img {
            width: 169px;
        }

        .rb-header__location {
            display: flex;
        }

        .rb-header__location--img {
            height: 56px;
            margin-left: 30px;
            margin-right: 12px;
        }

        .rb-header__location--text {
            color: #1b1b1b;
            font-weight: 500;
            display: flex;
            flex-direction: column;
            white-space: nowrap;
        }

        .rb-header__location--address {
            font-weight: 400;
            color: rgba(18,18,18,.5);
            white-space: nowrap;
        }

        .col-2 { flex-basis: 16.66667%; max-width: 16.66667%; }
        .col-4 { flex-basis: 33.33333%; max-width: 33.33333%; }
        .col-8 { flex-basis: 66.66667%; max-width: 66.66667%; }
        .col-12 { flex-basis: 100%; max-width: 100%; }

        #rb-mobile {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 10;
            background: #fff;
            padding: 16px 0;
        }

.rb-mobile-menu-sidebar {
    CORNER-SHAPE: SUPERELLIPSE(0.5);
    position: fixed;
    top: 0;
    left: -100%;
    width: 292px;
    height: -webkit-fill-available;
    background: #ffffff;
    z-index: 1000;
    transition: left 0.3s ease;
    overflow-y: scroll;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
}

        .rb-mobile-menu-sidebar.open {
            left: 0;
        }

        .rb-mobile-menu-header {
            background: #42557d;
            height: 277px;
            position: relative;
            overflow: hidden;
        }

        .rb-mobile-logo {
            width: 125px;
            height: 30px;
            position: absolute;
            left: 20px;
            top: 32px;
        }

        .rb-mobile-contacts {
            display: flex;
            flex-direction: column;
            gap: 20px;
            position: absolute;
            left: 20px;
            top: 94px;
        }

        .rb-mobile-contact-item {
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }

        .rb-mobile-contact-icon {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }

        .rb-mobile-contact-text {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .rb-mobile-contact-main {
            color: #ffffff;
            font-family: "Manrope", sans-serif;
            font-size: 14px;
            line-height: 16px;
            font-weight: 400;
        }

        .rb-mobile-contact-sub {
            color: #ffffff;
            font-family: "Manrope", sans-serif;
            font-size: 14px;
            line-height: 16px;
            font-weight: 400;
            opacity: 0.4;
        }

        .rb-mobile-menu-content {

        }

        .rb-mobile-menu-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 8px;
            color: #1b1b1b;
            font-family: "Manrope", sans-serif;
            font-size: 16px;
            line-height: 19px;
            font-weight: 400;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .rb-mobile-menu-item:hover {
            background: rgba(243, 149, 29, 0.1);
            color: #f3951d;
        }

        .rb-mobile-menu-arrow {
            width: 15px;
            height: 15px;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="%23666" stroke-width="2"><polyline points="6,9 12,15 18,9"/></svg>') center/contain no-repeat;
            transition: transform 0.3s ease;
        }

        .rb-mobile-menu-item.expanded .rb-mobile-menu-arrow {
            transform: rotate(180deg);
        }

.rb-mobile-menu-buttons {
    display: flex
;
    flex-direction: column;
    gap: 8px;
    width: 252px;
    padding-left: 20px;
    margin-bottom: 21px;
}

        .rb-mobile-btn-biomarket {
            background: #f2f9f1;
            border-radius: 9px;
            border: 1px solid #7dc073;
            padding: 16px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 48px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .rb-mobile-btn-biomarket:hover {
            background: #e8f5e8;
            transform: translateY(-1px);
        }

        .rb-mobile-btn-biomarket-text {
            color: #7dc073;
            font-family: "Manrope", sans-serif;
            font-size: 14px;
            line-height: 18px;
            font-weight: 400;
        }

        .rb-mobile-btn-biomarket-arrow {
            width: 6px;
            height: 12px;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="6" height="12" viewBox="0 0 24 24" fill="none" stroke="%237dc073" stroke-width="2"><polyline points="9,18 15,12 9,6"/></svg>') center/contain no-repeat;
        }

        .rb-mobile-btn-appointment {
            background: #fef4e8;
            border-radius: 9px;
            border: 1px solid #f3951d;
            padding: 16px 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 48px;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .rb-mobile-btn-appointment:hover {
            background: #f3951d;
        }

        .rb-mobile-btn-appointment:hover .rb-mobile-btn-appointment-text {
            color: #ffffff;
        }

        .rb-mobile-btn-appointment-text {
            color: #f3951d;
            font-family: "Manrope", sans-serif;
            font-size: 14px;
            line-height: 18px;
            font-weight: 400;
            transition: color 0.3s ease;
        }

        .rb-mobile-scrollbar {
            background: #d9d9d9;
            width: 6px;
            height: 239px;
            position: absolute;
            right: 0;
            top: 297px;
            border-radius: 3px;
        }

        .rb-mobile-scrollbar-thumb {
            background: #bbbbbb;
            width: 6px;
            height: 183px;
            position: absolute;
            right: 0;
            top: 297px;
            border-radius: 3px;
        }

        /* Оверлей */
        .rb-mobile-menu-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .rb-mobile-menu-overlay.open {
            opacity: 1;
            visibility: visible;
        }

        .rb-header__burger {
            background: rgba(243,149,29,.1);
            color: rgba(243,149,29,.1);
            border-radius: 4px;
            width: 44px;
            height: 44px;
            cursor: pointer;
        }

@media (max-width: 600px) {
    .btn-lk-mob {
        display: block;
        padding: 13px;
    }
}
        .rb-header__burger-wrap span {
            background: #f3951d;
            opacity: 1;
            height: 2px;
            width: 24px;
            margin: 3px 0;
            display: block;
            border-radius: 5px;
            transition: all .4s;
        }

        .rb-burger-show .rb-header__burger-wrap span:nth-child(2) {
            display: none;
        }

        .rb-burger-show .rb-header__burger-wrap span:first-child {
            transition: all .4s;
            transform: rotate(135deg) translateY(-2px) translateX(3px);
        }

        .rb-burger-show .rb-header__burger-wrap span:last-child {
            transition: all .4s;
            transform: rotate(45deg) translateY(-2px) translateX(-3px);
        }

        .icon-location {
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="%23ffffff" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>') center/contain no-repeat;
        }

        .icon-clock {
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="%23ffffff" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/></svg>') center/contain no-repeat;
        }

        .icon-phone {
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="%23ffffff" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>') center/contain no-repeat;
        }

        @media screen and (max-width: 767px) {
            #rb-mobile {
                display: block;
            }
            
            #rb-desktop {
                display: none;
            }
			.rb-mobile-btn-biomarket {
    background: #f2f9f1;
    border-radius: 9px;
    border: 1px solid #7dc073;
    padding: 16px 24px;
    display: flex
;
    align-items: center;
    justify-content: space-between;
    height: 48px;
    text-decoration: none;
    transition: all 0.3s ease;
    flex-direction: column;
}
        }
		@media screen and (max-width: 767px) {
    .rb-header__menu-list {
        margin-left: 24px;
        height: auto;

    }
    #rb-mobile .rb-container .show-search {
        flex-basis: 43.33333%;
        max-width: 97%;
        padding: 0;
        margin: 0;
        border: 1px solid #bbbbbb;
        border-radius: 13px;
    }
.rb-header__search.show-search {
    position: absolute;
    top: 73px;
    left: 0;
    right: 0;
    width: 94%;
    flex-basis: 100%;
    max-width: 100%;
    transition: all .3s;
    background-color: #ffffff00;
    padding: 8px;
    z-index: 99;
}
			
}
		.modal {

    z-index: 9999;

}
		.rb-header__bot.fixed .rb-header__search.show-search {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    width: 103%;
    flex-basis: 100%;
    max-width: 120%;
    transition: all .3s;
    background-color: #fff;
    padding: 8px;
}
.rb-header__search.show-search {
    position: absolute;
    top: 66px;
    left: 0;
    right: 0;
    width: 94%;
    flex-basis: 100%;

    transition: all .3s;
    background-color: #ffffff00;
    padding: 8px 0px;
    z-index: 99;
    width: auto;
    margin-right: -36px;
}
		.rb-header__search #smart-title-search, .rb-header__search #header-search {
 background-color: #fff;
}
@media screen and (max-width: 767px) {
    #rb-mobile .rb-container .show-search {
        background-color: #fff;
    }
}
		@media screen and (max-width: 767px) {
    #rb-mobile .rb-container .show-search {
        flex-basis: 43.33333%;
        max-width: 94%;
        padding: 0;
        margin: 0;
        border: 1px solid #bbbbbb;
        border-radius: 13px;
        top: 131px;
        left: 9px;
    }
			.rb-container .col-8 {
    padding-top: 4px;
}
}
				.rb-header__market--phone {
			text-decoration: none;
		}
		.rb-header__location {
    display: flex
;
    align-items: center;
}
		.rb-header__location--img {
    display: flex
;
    align-items: center;
}
		.rb-header__logo-link{
			display: flex
;
    align-items: center;
    margin-top: 5px;
		}
		.rb-header__market-wrapper{
		margin-top: 2px;
		}
				.rb-header__button{
		margin-top: 2px!important;
		}
						.rb-header__order--btn{
		margin-top: 2px!important;
		}
		.rb-about__clinics .rb-container {
    padding: 0;
}
		@media screen and (max-width: 900px) {
    .bvi-shortcode a svg {
        height: 37px;
        margin-top: 82px;
    }
}
    </style>
	</head>

	<body>
		<!-- Google Tag Manager (noscript) -->
		<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MF5TM82M"
		height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<!-- End Google Tag Manager (noscript) -->
		<?php
			if( function_exists( 'carbon_get_theme_option' ) ){
				$logo = carbon_get_theme_option( 'logo' );
				$logo_mob = carbon_get_theme_option( 'logo_mob' );
				$phone = carbon_get_theme_option( 'phone' );
			}
		?>
<header class="rb-header">
    <div class="rb-container no_padding" id="rb-desktop">
        <div class="rb-header__top rb-flex">

            <!-- ЛОГО -->
            <div class="col-2 col-m-2 col-s-12 rb-header__logo" style="margin-right: 15px;">
                <a href="<?php echo site_url(); ?>" class="rb-header__logo-link">
                    <?php echo wp_get_attachment_image( $logo, 'medium', '', array( 'class' => 'rb-desktop' ) ); ?>
                    <?php echo wp_get_attachment_image( $logo_mob, 'medium', '', array( 'class' => 'rb-mobile' ) ); ?>
                </a>
            </div>
            <!-- Конец ЛОГО -->

            <!-- ЛОГО -->
<div class="col-2 col-m-2 col-s-12 rb-header__lgo"> <a href="https://olimp-medgroup.ru/" class="rb-header__logo-link" style="
    margin-top: 13px;
"> <svg width="130" height="25" viewBox="0 10 520 60" fill="none" xmlns="http://www.w3.org/2000/svg" class="common-logo__logo"><path d="M129.848 3.84613V28.4615H126.868V6.49036H114.467V28.4615H111.488V3.84613H129.848ZM134.797 35.3848V9.99997H137.537V12.9327H137.873C138.082 12.6121 138.37 12.2035 138.739 11.7067C139.115 11.2019 139.652 10.7532 140.349 10.3606C141.054 9.95993 142.007 9.7596 143.208 9.7596C144.762 9.7596 146.132 10.1482 147.318 10.9255C148.503 11.7027 149.429 12.8045 150.093 14.2307C150.758 15.6571 151.091 17.3397 151.091 19.2788C151.091 21.2339 150.758 22.9287 150.093 24.363C149.429 25.7892 148.507 26.895 147.33 27.6803C146.152 28.4575 144.795 28.8462 143.256 28.8462C142.071 28.8462 141.122 28.6498 140.409 28.2572C139.696 27.8566 139.147 27.4038 138.763 26.899C138.378 26.3862 138.082 25.9615 137.873 25.625H137.633V35.3848H134.797ZM137.585 19.2308C137.585 20.625 137.789 21.855 138.198 22.9207C138.606 23.9784 139.203 24.8077 139.988 25.4086C140.773 26.0016 141.734 26.2981 142.872 26.2981C144.058 26.2981 145.047 25.9856 145.84 25.3606C146.641 24.7275 147.242 23.8782 147.642 22.8125C148.051 21.7388 148.255 20.5448 148.255 19.2308C148.255 17.9327 148.055 16.7628 147.654 15.7212C147.262 14.6714 146.665 13.8421 145.864 13.2332C145.071 12.6162 144.074 12.3077 142.872 12.3077C141.719 12.3077 140.749 12.6001 139.964 13.1851C139.179 13.762 138.586 14.5713 138.186 15.613C137.785 16.6466 137.585 17.8525 137.585 19.2308ZM162.238 28.8462C160.571 28.8462 159.109 28.4495 157.852 27.6562C156.602 26.863 155.625 25.7532 154.92 24.3269C154.223 22.9006 153.875 21.2339 153.875 19.3269C153.875 17.4038 154.223 15.7251 154.92 14.2909C155.625 12.8566 156.602 11.7428 157.852 10.9495C159.109 10.1562 160.571 9.7596 162.238 9.7596C163.904 9.7596 165.362 10.1562 166.611 10.9495C167.869 11.7428 168.846 12.8566 169.543 14.2909C170.248 15.7251 170.601 17.4038 170.601 19.3269C170.601 21.2339 170.248 22.9006 169.543 24.3269C168.846 25.7532 167.869 26.863 166.611 27.6562C165.362 28.4495 163.904 28.8462 162.238 28.8462ZM162.238 26.2981C163.503 26.2981 164.545 25.9735 165.362 25.3245C166.179 24.6755 166.784 23.8221 167.176 22.7644C167.569 21.7067 167.765 20.5609 167.765 19.3269C167.765 18.0929 167.569 16.9431 167.176 15.8774C166.784 14.8117 166.179 13.9503 165.362 13.2933C164.545 12.6362 163.503 12.3077 162.238 12.3077C160.972 12.3077 159.931 12.6362 159.114 13.2933C158.296 13.9503 157.692 14.8117 157.299 15.8774C156.906 16.9431 156.71 18.0929 156.71 19.3269C156.71 20.5609 156.906 21.7067 157.299 22.7644C157.692 23.8221 158.296 24.6755 159.114 25.3245C159.931 25.9735 160.972 26.2981 162.238 26.2981ZM181.991 28.8462C180.212 28.8462 178.678 28.4535 177.389 27.6682C176.107 26.875 175.118 25.7692 174.421 24.3509C173.732 22.9247 173.387 21.266 173.387 19.375C173.387 17.484 173.732 15.8173 174.421 14.375C175.118 12.9246 176.087 11.7949 177.328 10.9856C178.578 10.1683 180.036 9.7596 181.702 9.7596C182.664 9.7596 183.613 9.91986 184.55 10.2404C185.487 10.5609 186.34 11.0817 187.109 11.8029C187.878 12.516 188.491 13.4615 188.948 14.6394C189.404 15.8173 189.633 17.2676 189.633 18.9904V20.1923H175.406V17.7404H186.749C186.749 16.6987 186.541 15.7692 186.124 14.9519C185.715 14.1346 185.131 13.4896 184.37 13.0168C183.617 12.5441 182.728 12.3077 181.702 12.3077C180.573 12.3077 179.595 12.5881 178.77 13.149C177.953 13.7019 177.325 14.4231 176.884 15.3125C176.443 16.2019 176.223 17.1554 176.223 18.1731V19.8077C176.223 21.2019 176.464 22.3838 176.944 23.3534C177.433 24.3149 178.11 25.0481 178.975 25.5529C179.84 26.0497 180.845 26.2981 181.991 26.2981C182.736 26.2981 183.408 26.1939 184.009 25.9856C184.618 25.7692 185.143 25.4487 185.583 25.024C186.024 24.5913 186.364 24.0545 186.605 23.4134L189.344 24.1827C189.056 25.1122 188.571 25.9295 187.89 26.6346C187.21 27.3317 186.368 27.8766 185.367 28.2692C184.366 28.6538 183.24 28.8462 181.991 28.8462ZM193.27 28.4615V9.99997H196.105V18.0288H197.98L204.612 9.99997H208.265L200.816 18.8942L208.362 28.4615H204.709L198.653 20.6731H196.105V28.4615H193.27ZM209.71 12.6442V9.99997H224.705V12.6442H218.649V28.4615H215.813V12.6442H209.71ZM248.395 9.99997V12.6442H239.84V28.4615H237.005V9.99997H248.395ZM251.858 35.3848V9.99997H254.598V12.9327H254.934C255.142 12.6121 255.431 12.2035 255.8 11.7067C256.176 11.2019 256.713 10.7532 257.41 10.3606C258.115 9.95993 259.068 9.7596 260.269 9.7596C261.823 9.7596 263.193 10.1482 264.379 10.9255C265.564 11.7027 266.49 12.8045 267.154 14.2307C267.819 15.6571 268.152 17.3397 268.152 19.2788C268.152 21.2339 267.819 22.9287 267.154 24.363C266.49 25.7892 265.568 26.895 264.391 27.6803C263.213 28.4575 261.856 28.8462 260.317 28.8462C259.132 28.8462 258.183 28.6498 257.47 28.2572C256.757 27.8566 256.208 27.4038 255.824 26.899C255.439 26.3862 255.142 25.9615 254.934 25.625H254.694V35.3848H251.858ZM254.646 19.2308C254.646 20.625 254.85 21.855 255.259 22.9207C255.667 23.9784 256.264 24.8077 257.049 25.4086C257.834 26.0016 258.795 26.2981 259.933 26.2981C261.118 26.2981 262.108 25.9856 262.901 25.3606C263.702 24.7275 264.303 23.8782 264.703 22.8125C265.112 21.7388 265.316 20.5448 265.316 19.2308C265.316 17.9327 265.116 16.7628 264.715 15.7212C264.323 14.6714 263.726 13.8421 262.925 13.2332C262.132 12.6162 261.135 12.3077 259.933 12.3077C258.78 12.3077 257.81 12.6001 257.025 13.1851C256.24 13.762 255.647 14.5713 255.247 15.613C254.846 16.6466 254.646 17.8525 254.646 19.2308ZM272.966 35.3848C272.486 35.3848 272.057 35.3445 271.68 35.2643C271.304 35.1922 271.044 35.1201 270.899 35.048L271.621 32.5481C272.309 32.7244 272.918 32.7884 273.447 32.7404C273.976 32.6923 274.444 32.456 274.853 32.0313C275.269 31.6146 275.65 30.9375 275.994 30L276.523 28.5577L269.698 9.99997H272.774L277.869 24.7115H278.061L283.156 9.99997H286.232L278.397 31.1538C278.045 32.1074 277.608 32.8967 277.088 33.5215C276.567 34.1548 275.962 34.6232 275.273 34.9278C274.592 35.2324 273.823 35.3848 272.966 35.3848ZM289.298 28.4615V9.99997H303.813V28.4615H300.977V12.6442H292.134V28.4615H289.298ZM308.315 28.4615V9.99997H322.83V28.4615H319.994V12.6442H311.151V28.4615H308.315ZM329.784 16.6827H335.07C337.232 16.6827 338.886 17.2315 340.033 18.3293C341.177 19.4271 341.752 20.8173 341.752 22.5C341.752 23.6058 341.495 24.6114 340.981 25.5168C340.47 26.4142 339.715 27.1314 338.724 27.6682C337.729 28.1971 336.511 28.4615 335.07 28.4615H327.332V9.99997H330.167V25.8173H335.07C336.193 25.8173 337.113 25.5208 337.834 24.9279C338.555 24.3349 338.917 23.5737 338.917 22.6442C338.917 21.6666 338.555 20.8694 337.834 20.2524C337.113 19.6354 336.193 19.3269 335.07 19.3269H329.784V16.6827ZM344.683 28.4615V9.99997H347.518V28.4615H344.683ZM360.873 28.4615V9.99997H363.709V18.0288H365.583L372.215 9.99997H375.87L368.419 18.8942L375.964 28.4615H372.31L366.257 20.6731H363.709V28.4615H360.873ZM385.107 28.8462C383.442 28.8462 381.981 28.4495 380.722 27.6562C379.473 26.863 378.495 25.7532 377.792 24.3269C377.095 22.9006 376.746 21.2339 376.746 19.3269C376.746 17.4038 377.095 15.7251 377.792 14.2909C378.495 12.8566 379.473 11.7428 380.722 10.9495C381.981 10.1562 383.442 9.7596 385.107 9.7596C386.775 9.7596 388.23 10.1562 389.482 10.9495C390.737 11.7428 391.715 12.8566 392.412 14.2909C393.119 15.7251 393.471 17.4038 393.471 19.3269C393.471 21.2339 393.119 22.9006 392.412 24.3269C391.715 25.7532 390.737 26.863 389.482 27.6562C388.23 28.4495 386.775 28.8462 385.107 28.8462ZM385.107 26.2981C386.373 26.2981 387.415 25.9735 388.23 25.3245C389.049 24.6755 389.655 23.8221 390.047 22.7644C390.44 21.7067 390.636 20.5609 390.636 19.3269C390.636 18.0929 390.44 16.9431 390.047 15.8774C389.655 14.8117 389.049 13.9503 388.23 13.2933C387.415 12.6362 386.373 12.3077 385.107 12.3077C383.842 12.3077 382.799 12.6362 381.984 13.2933C381.165 13.9503 380.563 14.8117 380.17 15.8774C379.778 16.9431 379.582 18.0929 379.582 19.3269C379.582 20.5609 379.778 21.7067 380.17 22.7644C380.563 23.8221 381.165 24.6755 381.984 25.3245C382.799 25.9735 383.842 26.2981 385.107 26.2981ZM407.216 24.6154L413.655 9.99997H416.348L408.37 28.4615H406.062L398.229 9.99997H400.871L407.216 24.6154ZM399.958 9.99997V28.4615H397.122V9.99997H399.958ZM414.474 28.4615V9.99997H417.309V28.4615H414.474ZM421.823 28.4615V9.99997H436.339V28.4615H433.503V12.6442H424.658V28.4615H421.823ZM446.27 28.8942C445.103 28.8942 444.04 28.6739 443.086 28.2332C442.135 27.7844 441.377 27.1394 440.815 26.2981C440.257 25.4487 439.976 24.4231 439.976 23.2211C439.976 22.1634 440.183 21.3061 440.599 20.649C441.015 19.984 441.573 19.4631 442.27 19.0865C442.967 18.7099 443.736 18.4295 444.578 18.2452C445.427 18.0529 446.28 17.9006 447.136 17.7884C448.259 17.6442 449.166 17.5361 449.863 17.4639C450.57 17.3838 451.081 17.2516 451.403 17.0673C451.731 16.883 451.893 16.5625 451.893 16.1057V16.0096C451.893 14.8237 451.572 13.9022 450.922 13.2452C450.279 12.5881 449.308 12.2596 448.002 12.2596C446.649 12.2596 445.586 12.5561 444.818 13.149C444.05 13.742 443.509 14.375 443.194 15.0481L440.504 14.0865C440.985 12.9647 441.624 12.0913 442.426 11.4663C443.235 10.8333 444.118 10.3926 445.069 10.1442C446.03 9.8878 446.977 9.7596 447.904 9.7596C448.5 9.7596 449.18 9.83172 449.948 9.97594C450.726 10.1122 451.474 10.3966 452.195 10.8293C452.925 11.262 453.528 11.9151 454.008 12.7885C454.489 13.6619 454.729 14.8317 454.729 16.2981V28.4615H451.893V25.9615H451.751C451.558 26.3621 451.237 26.7908 450.79 27.2476C450.34 27.7043 449.745 28.0929 449 28.4135C448.253 28.734 447.346 28.8942 446.27 28.8942ZM446.703 26.3461C447.826 26.3461 448.77 26.1258 449.538 25.6851C450.317 25.2444 450.902 24.6755 451.295 23.9784C451.694 23.2812 451.893 22.5481 451.893 21.7788V19.1827C451.775 19.3269 451.511 19.4591 451.102 19.5793C450.702 19.6915 450.235 19.7917 449.708 19.8798C449.187 19.9599 448.679 20.032 448.182 20.0961C447.694 20.1522 447.299 20.2003 446.994 20.2404C446.256 20.3365 445.566 20.4928 444.927 20.7091C444.294 20.9175 443.779 21.2339 443.387 21.6586C443.005 22.0753 442.812 22.6442 442.812 23.3654C442.812 24.3509 443.174 25.0961 443.905 25.6009C444.642 26.0977 445.576 26.3461 446.703 26.3461ZM471.583 17.9327V20.5769H461.391V17.9327H471.583ZM462.065 9.99997V28.4615H459.229V9.99997H462.065ZM473.745 9.99997V28.4615H470.91V9.99997H473.745ZM481.084 24.2788L489.973 9.99997H493.242V28.4615H490.406V14.1827L481.565 28.4615H478.245V9.99997H481.084V24.2788ZM500.594 24.2788L509.487 9.99997H512.755V28.4615H509.92V14.1827L501.075 28.4615H497.759V9.99997H500.594V24.2788ZM507.612 3.84613H510.255C510.255 5.14423 509.808 6.20592 508.911 7.03122C508.015 7.85656 506.797 8.26921 505.257 8.26921C503.744 8.26921 502.537 7.85656 501.64 7.03122C500.75 6.20592 500.307 5.14423 500.307 3.84613H502.949C502.949 4.47113 503.122 5.02002 503.467 5.49278C503.819 5.96554 504.418 6.20192 505.257 6.20192C506.1 6.20192 506.698 5.96554 507.06 5.49278C507.429 5.02002 507.612 4.47113 507.612 3.84613Z" fill="#666666"></path><path d="M125.635 76.2523C123.415 76.2523 121.372 75.8868 119.504 75.1557C117.636 74.4246 116.012 73.4092 114.631 72.1095C113.251 70.7828 112.182 69.2394 111.424 67.4794C110.666 65.6923 110.287 63.7428 110.287 61.6308C110.287 59.5188 110.666 57.5828 111.424 55.8228C112.182 54.0357 113.251 52.4923 114.631 51.1926C116.012 49.8658 117.636 48.8369 119.504 48.1058C121.372 47.3748 123.402 47.0092 125.594 47.0092C127.814 47.0092 129.844 47.3748 131.685 48.1058C133.553 48.8369 135.177 49.8658 136.557 51.1926C137.938 52.4923 139.007 54.0357 139.765 55.8228C140.523 57.5828 140.902 59.5188 140.902 61.6308C140.902 63.7428 140.523 65.6923 139.765 67.4794C139.007 69.2665 137.938 70.8098 136.557 72.1095C135.177 73.4092 133.553 74.4246 131.685 75.1557C129.844 75.8868 127.828 76.2523 125.635 76.2523ZM125.594 71.6221C127.029 71.6221 128.355 71.3784 129.574 70.8911C130.792 70.4037 131.847 69.7132 132.741 68.8197C133.634 67.8991 134.324 66.8431 134.811 65.6517C135.326 64.4332 135.583 63.0929 135.583 61.6308C135.583 60.1686 135.326 58.8418 134.811 57.6505C134.324 56.432 133.634 55.376 132.741 54.4824C131.847 53.5618 130.792 52.8578 129.574 52.3704C128.355 51.8831 127.029 51.6394 125.594 51.6394C124.16 51.6394 122.833 51.8831 121.615 52.3704C120.424 52.8578 119.368 53.5618 118.448 54.4824C117.555 55.376 116.851 56.432 116.337 57.6505C115.849 58.8418 115.606 60.1686 115.606 61.6308C115.606 63.0658 115.849 64.3926 116.337 65.6111C116.851 66.8295 117.555 67.8991 118.448 68.8197C119.341 69.7132 120.397 70.4037 121.615 70.8911C122.833 71.3784 124.16 71.6221 125.594 71.6221ZM142.328 75.8055L142.572 71.5409C142.761 71.568 142.937 71.5951 143.1 71.6221C143.262 71.6492 143.411 71.6628 143.546 71.6628C144.385 71.6628 145.035 71.4055 145.495 70.8911C145.955 70.3766 146.294 69.6861 146.51 68.8197C146.754 67.9261 146.916 66.9378 146.998 65.8548C147.106 64.7446 147.187 63.6344 147.241 62.5243L147.607 54.1575H165.148V75.8461H160.072V57.0818L161.25 58.4628H150.936L152.033 57.0412L151.748 62.768C151.667 64.6904 151.505 66.4775 151.261 68.1292C151.044 69.7538 150.693 71.1754 150.205 72.3938C149.745 73.6123 149.095 74.56 148.256 75.2369C147.444 75.9138 146.402 76.2523 145.13 76.2523C144.724 76.2523 144.277 76.2117 143.79 76.1304C143.33 76.0492 142.843 75.9409 142.328 75.8055ZM171.983 75.8461V54.1575H177.058V68.4541L189.036 54.1575H193.665V75.8461H188.59V61.5495L176.652 75.8461H171.983ZM200.493 75.8461V54.1575H205.812L214.542 69.5508H212.43L221.607 54.1575H226.357L226.398 75.8461H221.81V59.6L222.622 60.128L214.501 73.5717H212.308L204.147 59.7624L205.122 59.5188V75.8461H200.493ZM233.206 75.8461V54.1575H253.995V75.8461H248.96V57.2849L250.137 58.4628H237.103L238.281 57.2849V75.8461H233.206ZM269.831 73.0437L271.537 69.1852C272.755 70.0246 274.081 70.6744 275.516 71.1348C276.978 71.568 278.426 71.7981 279.861 71.8252C281.295 71.8523 282.595 71.6898 283.759 71.3378C284.95 70.9858 285.897 70.4578 286.601 69.7538C287.305 69.0498 287.657 68.1698 287.657 67.1138C287.657 65.8412 287.129 64.8935 286.073 64.2708C285.044 63.6209 283.637 63.296 281.85 63.296H275.232V59.1532H281.444C283.041 59.1532 284.273 58.8283 285.139 58.1785C286.032 57.5286 286.479 56.6486 286.479 55.5384C286.479 54.6178 286.181 53.8597 285.586 53.264C285.017 52.6683 284.232 52.2215 283.231 51.9237C282.256 51.6258 281.146 51.4904 279.901 51.5175C278.683 51.5175 277.411 51.7071 276.084 52.0861C274.758 52.4381 273.499 52.9661 272.308 53.6701L270.644 49.4055C272.43 48.4578 274.298 47.7944 276.247 47.4154C278.223 47.0363 280.131 46.928 281.972 47.0904C283.813 47.2529 285.464 47.6726 286.926 48.3495C288.414 49.0265 289.606 49.92 290.499 51.0301C291.392 52.1132 291.839 53.3994 291.839 54.8886C291.839 56.2425 291.473 57.4474 290.742 58.5034C290.039 59.5323 289.064 60.3311 287.819 60.8997C286.574 61.4683 285.126 61.7526 283.474 61.7526L283.677 60.4529C285.545 60.4529 287.169 60.7778 288.55 61.4277C289.957 62.0504 291.04 62.9169 291.798 64.0271C292.583 65.1372 292.976 66.4234 292.976 67.8855C292.976 69.2665 292.624 70.4984 291.92 71.5815C291.216 72.6375 290.255 73.5311 289.037 74.2621C287.819 74.9661 286.425 75.4941 284.855 75.8461C283.285 76.1711 281.634 76.2929 279.901 76.2117C278.169 76.1305 276.436 75.8326 274.704 75.3181C272.999 74.8037 271.374 74.0455 269.831 73.0437ZM313.294 73.4905V58.4628H304.971L304.849 61.9557C304.795 63.2012 304.714 64.4061 304.605 65.5705C304.497 66.7077 304.321 67.7637 304.077 68.7384C303.834 69.6861 303.482 70.4714 303.022 71.0941C302.562 71.7169 301.952 72.1231 301.195 72.3126L296.16 71.5409C296.972 71.5409 297.621 71.2837 298.109 70.7692C298.623 70.2277 299.015 69.4966 299.286 68.576C299.584 67.6283 299.8 66.5588 299.936 65.3674C300.071 64.1489 300.166 62.8898 300.22 61.5901L300.504 54.1575H318.37V73.4905H313.294ZM295.388 80.6794V71.5409H321.578V80.6794H316.827V75.8461H300.098V80.6794H295.388ZM335.773 76.1304C333.554 76.1304 331.578 75.6566 329.845 74.7089C328.114 73.7341 326.747 72.4074 325.745 70.7286C324.744 69.0498 324.243 67.1409 324.243 65.0018C324.243 62.8357 324.744 60.9268 325.745 59.2751C326.747 57.5963 328.114 56.2831 329.845 55.3354C331.578 54.3877 333.554 53.9138 335.773 53.9138C338.02 53.9138 340.01 54.3877 341.742 55.3354C343.502 56.2831 344.869 57.5828 345.843 59.2344C346.845 60.8861 347.345 62.8086 347.345 65.0018C347.345 67.1409 346.845 69.0498 345.843 70.7286C344.869 72.4074 343.502 73.7341 341.742 74.7089C340.01 75.6566 338.02 76.1304 335.773 76.1304ZM335.773 71.7846C337.019 71.7846 338.128 71.5138 339.103 70.9723C340.077 70.4308 340.835 69.6455 341.377 68.6166C341.945 67.5877 342.229 66.3828 342.229 65.0018C342.229 63.5938 341.945 62.3889 341.377 61.3871C340.835 60.3581 340.077 59.5729 339.103 59.0314C338.128 58.4898 337.032 58.2191 335.814 58.2191C334.569 58.2191 333.459 58.4898 332.484 59.0314C331.537 59.5729 330.779 60.3581 330.211 61.3871C329.642 62.3889 329.359 63.5938 329.359 65.0018C329.359 66.3828 329.642 67.5877 330.211 68.6166C330.779 69.6455 331.537 70.4308 332.484 70.9723C333.459 71.5138 334.555 71.7846 335.773 71.7846ZM364.379 76.1304C362.619 76.1304 361.009 75.7243 359.547 74.912C358.112 74.0997 356.962 72.8812 356.096 71.2566C355.256 69.6049 354.837 67.52 354.837 65.0018C354.837 62.4566 355.243 60.3717 356.055 58.7471C356.894 57.1224 358.031 55.9175 359.466 55.1323C360.9 54.32 362.538 53.9138 364.379 53.9138C366.517 53.9138 368.398 54.3741 370.023 55.2948C371.674 56.2154 372.973 57.5015 373.921 59.1532C374.895 60.8049 375.382 62.7544 375.382 65.0018C375.382 67.2492 374.895 69.2123 373.921 70.8911C372.973 72.5428 371.674 73.8289 370.023 74.7495C368.398 75.6701 366.517 76.1304 364.379 76.1304ZM352.36 83.7255V54.1575H357.192V59.2751L357.029 65.0425L357.435 70.8098V83.7255H352.36ZM363.81 71.7846C365.028 71.7846 366.111 71.5138 367.059 70.9723C368.033 70.4308 368.805 69.6455 369.373 68.6166C369.941 67.5877 370.226 66.3828 370.226 65.0018C370.226 63.5938 369.941 62.3889 369.373 61.3871C368.805 60.3581 368.033 59.5729 367.059 59.0314C366.111 58.4898 365.028 58.2191 363.81 58.2191C362.592 58.2191 361.496 58.4898 360.521 59.0314C359.547 59.5729 358.775 60.3581 358.207 61.3871C357.639 62.3889 357.354 63.5938 357.354 65.0018C357.354 66.3828 357.639 67.5877 358.207 68.6166C358.775 69.6455 359.547 70.4308 360.521 70.9723C361.496 71.5138 362.592 71.7846 363.81 71.7846ZM390.098 76.1304C387.878 76.1304 385.902 75.6566 384.17 74.7089C382.437 73.7341 381.07 72.4074 380.069 70.7286C379.067 69.0498 378.566 67.1409 378.566 65.0018C378.566 62.8357 379.067 60.9268 380.069 59.2751C381.07 57.5963 382.437 56.2831 384.17 55.3354C385.902 54.3877 387.878 53.9138 390.098 53.9138C392.345 53.9138 394.334 54.3877 396.067 55.3354C397.826 56.2831 399.193 57.5828 400.168 59.2344C401.169 60.8861 401.67 62.8086 401.67 65.0018C401.67 67.1409 401.169 69.0498 400.168 70.7286C399.193 72.4074 397.826 73.7341 396.067 74.7089C394.334 75.6566 392.345 76.1304 390.098 76.1304ZM390.098 71.7846C391.343 71.7846 392.453 71.5138 393.427 70.9723C394.402 70.4308 395.16 69.6455 395.701 68.6166C396.27 67.5877 396.554 66.3828 396.554 65.0018C396.554 63.5938 396.27 62.3889 395.701 61.3871C395.16 60.3581 394.402 59.5729 393.427 59.0314C392.453 58.4898 391.357 58.2191 390.138 58.2191C388.893 58.2191 387.783 58.4898 386.809 59.0314C385.861 59.5729 385.104 60.3581 384.535 61.3871C383.967 62.3889 383.682 63.5938 383.682 65.0018C383.682 66.3828 383.967 67.5877 384.535 68.6166C385.104 69.6455 385.861 70.4308 386.809 70.9723C387.783 71.5138 388.88 71.7846 390.098 71.7846ZM406.684 75.8461V54.1575H417.282C419.908 54.1575 421.965 54.6449 423.454 55.6197C424.97 56.5944 425.728 57.9754 425.728 59.7624C425.728 61.5224 425.024 62.9034 423.616 63.9052C422.209 64.88 420.341 65.3674 418.013 65.3674L418.622 64.1083C421.248 64.1083 423.197 64.5957 424.469 65.5705C425.768 66.5181 426.418 67.9126 426.418 69.7538C426.418 71.6763 425.701 73.1791 424.266 74.2621C422.831 75.3181 420.652 75.8461 417.729 75.8461H406.684ZM411.516 72.1501H417.323C418.703 72.1501 419.745 71.9335 420.449 71.5003C421.153 71.04 421.505 70.3495 421.505 69.4289C421.505 68.4541 421.18 67.7366 420.53 67.2763C419.881 66.816 418.866 66.5858 417.485 66.5858H411.516V72.1501ZM411.516 63.2148H416.835C418.135 63.2148 419.109 62.9846 419.759 62.5243C420.436 62.0369 420.774 61.36 420.774 60.4935C420.774 59.6 420.436 58.9366 419.759 58.5034C419.109 58.0701 418.135 57.8535 416.835 57.8535H411.516V63.2148ZM442.703 61.3058C445.492 61.3058 447.616 61.9286 449.078 63.1741C450.54 64.4197 451.271 66.1797 451.271 68.4541C451.271 70.8098 450.459 72.6511 448.835 73.9778C447.21 75.2775 444.923 75.9138 441.973 75.8868L431.822 75.8461V54.1575H436.897V61.2652L442.703 61.3058ZM441.526 72.1501C443.015 72.1772 444.152 71.8658 444.937 71.216C445.722 70.5661 446.114 69.6184 446.114 68.3729C446.114 67.1274 445.722 66.2338 444.937 65.6923C444.179 65.1237 443.042 64.8258 441.526 64.7988L436.897 64.7581V72.1095L441.526 72.1501ZM467.952 75.8461V68.4541L468.683 69.3071H462.186C459.209 69.3071 456.867 68.6843 455.162 67.4388C453.484 66.1661 452.645 64.3384 452.645 61.9557C452.645 59.4104 453.538 57.4745 455.324 56.1477C457.138 54.8209 459.547 54.1575 462.552 54.1575H472.5V75.8461H467.952ZM452.36 75.8461L458.207 67.5606H463.405L457.801 75.8461H452.36ZM467.952 66.8295V57.0818L468.683 58.4221H462.714C461.144 58.4221 459.926 58.72 459.06 59.3157C458.221 59.8843 457.801 60.8185 457.801 62.1181C457.801 64.5551 459.385 65.7735 462.552 65.7735H468.683L467.952 66.8295Z" fill="#666666"></path><path d="M22.5876 1.04335C21.5848 1.12694 19.5236 1.55883 18.6183 1.86534C17.9498 2.10218 17.6991 2.19971 16.181 2.95205C14.2312 3.91336 13.0055 4.80502 11.4457 6.40721C9.41226 8.4831 7.79667 11.0048 6.98888 13.3593C6.89139 13.6241 6.76604 13.9724 6.71033 14.1256C6.47356 14.7386 6.18109 15.9786 5.87468 17.734C5.69363 18.8068 5.69363 22.053 5.87468 23.2372C6.20894 25.3549 6.33429 26.0097 6.52927 26.7342C6.64069 27.1522 6.77997 27.6816 6.84961 27.9184C7.05852 28.7265 7.47634 29.7575 8.2145 31.3318C8.5209 31.9866 9.89972 34.3272 10.22 34.7452C13.7437 39.3288 15.7632 41.2375 23.3258 47.103C28.2143 50.8925 29.9274 52.3554 31.6265 54.1248C32.9775 55.5458 34.231 57.0923 34.5931 57.8028C34.6906 57.9979 34.802 58.179 34.8438 58.2208C34.9273 58.2905 35.763 60.032 35.9719 60.5196C36.1808 61.0351 36.6265 62.4701 36.7658 63.097C37.0722 64.3788 37.114 64.741 37.1975 66.3014C37.295 68.2101 37.0722 68.0847 39.9273 67.8479C44.983 67.4299 48.5762 66.8169 53.3394 65.5212C54.1751 65.2983 57.2809 64.2813 57.7544 64.0723C57.9076 64.0166 58.7851 63.6683 59.7043 63.306C61.3199 62.6512 64.3561 61.2301 65.6234 60.5057C70.3866 57.7889 73.2 55.6991 76.7097 52.2718C78.5203 50.5163 79.4395 49.4575 81.1108 47.2423C82.1275 45.8769 83.5202 43.7453 83.7152 43.2438C83.7431 43.1741 83.8127 43.0766 83.8684 43.0348C84.0077 42.9233 85.6093 39.5517 85.9575 38.6462C86.1247 38.1864 86.3196 37.6848 86.3754 37.5316C87.1274 35.7483 87.9631 32.2792 88.4505 28.8658C88.6455 27.5562 88.6455 22.9864 88.4505 21.6908C87.9631 18.361 87.1553 15.7278 85.9157 13.3593C85.6093 12.7881 85.289 12.1751 85.2054 12.0079C85.1219 11.8268 85.0105 11.6875 84.9547 11.6875C84.8851 11.6875 84.8433 11.6039 84.8433 11.5064C84.8433 11.4088 84.7319 11.1999 84.5926 11.0466C84.4534 10.9073 84.2027 10.559 84.0077 10.2943C83.0745 8.92893 79.9966 6.17037 78.3949 5.26478C77.6568 4.83288 75.4841 3.78798 74.7459 3.4954C73.9381 3.17496 71.5426 2.57588 70.2056 2.36689C69.0357 2.17184 64.8992 2.17185 63.9243 2.35297C63.5204 2.43656 62.838 2.56195 62.4201 2.64554C61.3199 2.85452 59.5511 3.42574 58.6597 3.85763L57.8937 4.21987L59.7739 4.31739C61.7516 4.42885 63.1026 4.60997 64.37 4.95827C64.7878 5.06973 65.3031 5.20905 65.526 5.23691C65.7349 5.27871 65.9438 5.36231 65.9856 5.43197C66.0273 5.50163 66.1527 5.55736 66.2641 5.55736C66.4452 5.55736 66.8212 5.71061 68.1304 6.29576C69.2167 6.78338 71.3337 8.32985 72.434 9.43049C75.2891 12.2866 77.0161 15.9647 77.7264 20.6737C78.1721 23.6273 78.2417 25.7171 78.0189 28.1971C77.6568 31.9727 77.1554 34.2018 75.7905 38.0889C75.3587 39.287 73.7153 42.6307 72.8657 44.01C72.239 45.0271 70.8741 46.9915 70.6373 47.2144C70.5955 47.2562 70.3449 47.5627 70.0802 47.911C69.2307 49.0117 67.239 51.0736 65.7627 52.3832C62.629 55.1279 58.8129 57.4824 55.0386 58.9871C54.3144 59.2657 53.5901 59.5583 53.4369 59.614C53.1862 59.7255 51.9885 60.1156 50.93 60.4499C49.2865 60.9515 46.4175 61.5645 45.4286 61.6063L44.8019 61.6342L44.6348 60.3106C44.2727 57.5242 42.7128 53.9297 40.8883 51.6727C40.7908 51.5612 40.4148 51.0875 40.0387 50.6278C39.3841 49.7919 38.7713 49.1231 37.0722 47.3119C36.5708 46.7825 34.6906 44.9574 32.8939 43.2716C27.7965 38.4929 26.5987 37.2947 23.4372 33.7699C22.7687 33.0315 21.3342 31.2482 20.9163 30.6352C20.7353 30.3705 20.5403 30.1197 20.4985 30.0779C20.3314 29.9525 19.0222 27.9184 18.5487 27.0825C17.2534 24.7698 16.4874 22.8611 16.0974 21.022C15.8746 20.0189 15.8328 17.3857 16.0278 16.7588C16.0974 16.5359 16.2228 16.0064 16.3203 15.5885C16.4178 15.1705 16.5431 14.7247 16.5988 14.6132C16.6685 14.5018 16.9052 13.9863 17.1559 13.4708C17.9916 11.7432 19.6072 10.0992 21.1949 9.36082C22.5041 8.74781 22.7826 8.69209 24.3286 8.69209C26.0973 8.70602 27.0583 8.95679 28.8271 9.89024C30.582 10.8237 31.5569 11.59 33.3535 13.4011C34.8159 14.8919 35.3591 15.5328 37.6989 18.5839C41.7797 23.9338 43.5067 25.745 46.0275 27.389C47.0164 28.0299 48.8826 28.9215 50.0247 29.2838C51.9328 29.9107 52.5874 29.9943 54.9689 30.0082C57.3645 30.0082 57.7127 29.9664 59.565 29.3534C63.6458 28.0299 66.5984 24.714 67.7126 20.2279C67.9076 19.4337 67.9075 16.1179 67.6986 15.477C67.6151 15.1845 67.4897 14.7386 67.4062 14.4739C67.1694 13.6519 66.3477 12.0776 65.6931 11.1999C64.8296 10.0296 63.3393 8.78961 62.058 8.14873C60.7767 7.52179 60.526 7.43819 58.8686 7.14561C57.8101 6.9645 57.4202 6.95057 56.3617 7.08989C55.6792 7.18741 54.6625 7.41032 54.1194 7.59144C53.047 7.93974 51.5985 8.59456 51.4871 8.76174C51.4453 8.8314 51.9049 8.915 52.4899 8.97072C54.5093 9.15184 56.3338 9.93204 57.6152 11.1999C58.5204 12.1055 58.9661 12.8717 59.2168 14.0142C59.7321 16.3408 57.3645 18.5142 54.3144 18.5142C53.3255 18.5142 51.8353 18.1241 50.4425 17.4972C47.9495 16.3826 45.5401 14.5157 42.4342 11.2835C38.5206 7.21527 36.8772 5.7106 34.6488 4.24773C33.4232 3.42574 31.0834 2.21365 30.7491 2.21365C30.6377 2.21365 30.5123 2.15792 30.4706 2.08826C30.4288 2.01859 30.2477 1.935 30.0806 1.8932C29.8995 1.85141 29.5096 1.73995 29.2032 1.65636C27.3926 1.1548 24.4818 0.890093 22.5876 1.04335Z" fill="#B22E3C"></path><path d="M3.66018 30.7327C2.74097 32.92 2.58777 33.4494 2.18387 35.7761C1.43179 40.1926 3.01952 45.5147 6.34818 49.6525C6.91921 50.363 9.42615 52.9126 10.0529 53.4142C12.3509 55.2672 15.1503 56.9529 20.6378 59.7951C27.504 63.3478 30.2477 65.145 32.5736 67.5971C33.5346 68.6002 34.983 70.5925 34.983 70.899C34.983 70.9687 35.0805 71.0383 35.1919 71.0383C35.7908 71.0383 35.0805 65.9252 34.1474 63.5846C33.6181 62.2611 32.1 59.5165 31.6404 59.0567C31.5987 59.0149 31.2087 58.5412 30.7769 57.9979C29.0082 55.8105 26.3341 53.5117 21.1252 49.7222C18.0055 47.4512 17.7548 47.2422 15.986 45.7794C10.8607 41.4743 7.37881 37.434 5.61003 33.7699C4.78831 32.0562 4.7326 31.9308 4.30084 30.3147L4.11979 29.646L3.66018 30.7327Z" fill="#B22E3C"></path><path d="M87.963 45.1524C87.6844 45.7097 87.4059 46.1834 87.3363 46.2113C87.2666 46.2392 87.2109 46.3227 87.2109 46.3924C87.2109 46.5317 86.1385 48.1897 85.9574 48.329C85.9157 48.3708 85.7207 48.6215 85.5396 48.8863C84.9686 49.7222 82.8377 52.1046 81.4728 53.4281C74.899 59.8369 66.4172 64.7689 56.9187 67.6807C56.0831 67.9454 53.0051 68.6559 52.3226 68.7535C51.9466 68.7953 51.5009 68.8649 51.3477 68.9067C50.5956 69.06 48.4926 69.2411 46.6402 69.3247C45.4982 69.3665 44.6208 69.4501 44.6904 69.4919C45.1639 69.8959 49.3282 71.1777 51.4174 71.5817C53.8547 72.0415 54.3003 72.0833 57.7543 72.0833C61.1526 72.0833 62.2808 71.9997 64.161 71.5956C64.4674 71.526 65.0941 71.4006 65.5537 71.317C66.0133 71.2195 66.5426 71.0941 66.7376 71.0383C66.9325 70.9826 67.3643 70.8572 67.7125 70.7597C73.2277 69.2272 78.1024 66.3989 81.9881 62.484C84.5229 59.9344 86.0271 57.7471 87.2666 54.7795C88.0605 52.9126 88.3112 51.9931 88.6733 49.7361C88.9936 47.716 88.9379 44.1493 88.5897 44.1493C88.5201 44.1493 88.2415 44.6091 87.963 45.1524Z" fill="#B22E3C"></path></svg> </a></div>
            <!-- Конец ЛОГО -->
            <!-- Адрес -->
            <div class="col-2 col-m-2 col-s-12 rb-header__location">
                <div class="rb-header__location--img">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15 10.5C15 11.2956 14.6839 12.0587 14.1213 12.6213C13.5587 13.1839 12.7956 13.5 12 13.5C11.2044 13.5 10.4413 13.1839 9.87868 12.6213C9.31607 12.0587 9 11.2956 9 10.5C9 9.70435 9.31607 8.94129 9.87868 8.37868C10.4413 7.81607 11.2044 7.5 12 7.5C12.7956 7.5 13.5587 7.81607 14.1213 8.37868C14.6839 8.94129 15 9.70435 15 10.5V10.5Z" stroke="#323232" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M19.5 10.5C19.5 17.642 12 21.75 12 21.75C12 21.75 4.5 17.642 4.5 10.5C4.5 8.51088 5.29018 6.60322 6.6967 5.1967C8.10322 3.79018 10.0109 3 12 3C13.9891 3 15.8968 3.79018 17.3033 5.1967C18.7098 6.60322 19.5 8.51088 19.5 10.5V10.5Z" stroke="#323232" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="rb-header__location--text">
                    <span class="rb-header__location--city">г. Воронеж</span>
                    <span class="rb-header__location--address">ул. Моисеева, 2/2</span>
                </div>
            </div>
            <!-- Конец Адрес -->

            <!-- График -->
            <div class="col-2 col-m-2 col-s-12 rb-header__location rb-header__location--work">
                <div class="rb-header__location--img">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="9" stroke="#323232" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 7V12L15 15" stroke="#323232" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="rb-header__location--text">
                    <span class="rb-header__location--city">пн-пт: 08:00 — 20:00</span>
                    <span class="rb-header__location--address">сб-вс: 08:00 — 18:00</span>
                </div>
            </div>
            <!-- Конец График -->

            <!-- Телефон -->
            <div class="col-2 col-m-2 col-s-12 rb-header__location">
                <div class="rb-header__location--img">
                    <a class="rb-header__market--phone" href="tel:<?php echo $phone; ?>">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 4H9L11 9L8.5 10.5C9.57096 12.6715 11.3285 14.429 13.5 15.5L15 13L20 15V19C20 19.5304 19.7893 20.0391 19.4142 20.4142C19.0391 20.7893 18.5304 21 18 21C14.0993 20.763 10.4202 19.1065 7.65683 16.3432C4.8935 13.5798 3.23705 9.90074 3 6C3 5.46957 3.21071 4.96086 3.58579 4.58579C3.96086 4.21071 4.46957 4 5 4" stroke="#323232" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M15 7C15.5304 7 16.0391 7.21071 16.4142 7.58579C16.7893 7.96086 17 8.46957 17 9" stroke="#323232" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M15 3C16.5913 3 18.1174 3.63214 19.2426 4.75736C20.3679 5.88258 21 7.4087 21 9" stroke="#323232" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
                <div class="rb-header__location--text">
					<a class="rb-header__market--phone" href="tel:<?php echo $phone; ?>">
                    <span class="rb-header__location--city"><?php echo $phone; ?></span>
					</a>
                    <span class="rb-header__location--address">08:00 — 20:00</span>
                </div>
            </div>
            <!-- Конец Телефон -->

            <!-- Версия для слабовидящих -->
            <?php echo do_shortcode( '<div class="mob_no">[bvi text=""]</div>' ); ?>
            <!-- Конец Версия для слабовидящих -->
            
            <!-- МОБИЛЬНОЕ МЕНЮ -->
            <nav class="rb-header__menu-list rb-header__menu-list-mobile">
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'main_menu',
                        'container' => 'false'
                    )
                );
                ?>
            </nav>
            <!-- Конец МОБИЛЬНОЕ МЕНЮ -->
            
            <!-- БИОМАРКЕТ -->
            <div class="col-2 col-m-3 col-s-12 rb-header__market">
                <script defer src="https://booking.olimp-medgroup.ru/b.js?inline&animate=true&button=0&text=%D0%97%D0%B0%D0%BF%D0%B8%D1%81%D0%B0%D1%82%D1%8C%D1%81%D1%8F+%D0%BE%D0%BD%D0%BB%D0%B0%D0%B9%D0%BD&position=br&verticalPadding=32&horizontalPadding=32&url=%2F%3Fclinics%255B%255D%3D25350%26city%3D368"></script>
                <?php /*<div class="rb-header__market-wrapper">
                    <a style="    margin: 0px 5px 0px;
    padding-right: 24px;" class="rb-header__market--link" href="<?php echo site_url('bio-market/'); ?>">
                        Биомаркет
                        <svg class="rb-header__market--svg" width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1.25 1.5L8.75 9L1.25 16.5" stroke="#7DC073" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <svg class="rb-header__market--svg-hover" width="22" height="20" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1.25 1H2.636C3.146 1 3.591 1.343 3.723 1.835L4.106 3.272M4.106 3.272C9.67664 3.11589 15.2419 3.73515 20.642 5.112C19.818 7.566 18.839 9.95 17.718 12.25H6.5M4.106 3.272L6.5 12.25M6.5 12.25C5.70435 12.25 4.94129 12.5661 4.37868 13.1287C3.81607 13.6913 3.5 14.4544 3.5 15.25H19.25M5 18.25C5 18.4489 4.92098 18.6397 4.78033 18.7803C4.63968 18.921 4.44891 19 4.25 19C4.05109 19 3.86032 18.921 3.71967 18.7803C3.57902 18.6397 3.5 18.4489 3.5 18.25C3.5 18.0511 3.57902 17.8603 3.71967 17.7197C3.86032 17.579 4.05109 17.5 4.25 17.5C4.44891 17.5 4.63968 17.579 4.78033 17.7197C4.92098 17.8603 5 18.0511 5 18.25ZM17.75 18.25C17.75 18.4489 17.671 18.6397 17.5303 18.7803C17.3897 18.921 17.1989 19 17 19C16.8011 19 16.6103 18.921 16.4697 18.7803C16.329 18.6397 16.25 18.4489 16.25 18.25C16.25 18.0511 16.329 17.8603 16.4697 17.7197C16.6103 17.579 16.8011 17.5 17 17.5C17.1989 17.5 17.3897 17.579 17.5303 17.7197C17.671 17.8603 17.75 18.0511 17.75 18.25Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div> */ ?>
            </div> 
            <!-- Конец БИОМАРКЕТ -->

            <!-- Личный кабинет -->
            <div class="col-2 col-m-3 col-s-12 rb-header__button">
                <a href="https://lk.olimp03.ru/" class="btn-lk">Личный кабинет</a>
            </div>
            <!-- Конец Личный кабинет -->

            <!-- ЗАПИСАТЬСЯ -->
            <div class="col-2 col-m-3 col-s-12 rb-header__order--btn">
                <a href="" class="js-open-modal rb-header__order" data-modal="1">Записаться</a>
            </div>
            <!-- Конец ЗАПИСАТЬСЯ -->

        </div>

        <!-- МЕНЮ и ПОИСК -->
        <div class="rb-header__bot">
            <div class="col-12 col-m-12 col-s-12 rb-header__menu-wrapper">
                <nav class="rb-header__menu-list">
                    <?php
                        wp_nav_menu(
                            array(
                                'theme_location' => 'main_menu',
                                'container' => 'false'
                            )
                        );
                    ?>
                </nav>
                
                <!-- Кнопка поиска на ПК -->
                <div class="rb-mobile">
                    <div class="rb-header__search-button rb-header__mob-search f-center-center">
<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M11.3104 11.226L15.1725 14.8966M13.0124 7.05797C13.0124 10.3465 10.3465 13.0124 7.05797 13.0124C3.76942 13.0124 1.10352 10.3465 1.10352 7.05797C1.10352 3.76942 3.76942 1.10352 7.05797 1.10352C10.3465 1.10352 13.0124 3.76942 13.0124 7.05797Z" stroke="#1B1B1B" stroke-width="1.08263" stroke-linecap="round"/>
</svg>

                    </div>
                </div>                    
                <div class="col-4 col-m-2 col-s-12 rb-header__search">                                                    
                    <div id="header-search" class="bx-searchtitle theme-blue">
                        <form class="header-search-form <?php if ( isset( $_GET[ 's' ] ) && $_GET[ 's' ] ) { echo 'active'; } ?>" action="<?php echo site_url(); ?>" method="GET">
                            <div class="bx-input-group header__search--group">
                                <input id="smart-title-search-input" placeholder="Поиск по сайту" type="text" name="s" value="" autocomplete="off" class="bx-form-control"/>
                                <span class="bx-input-group-btn">
                                    <span class="bx-searchtitle-preloader view" id="smart-title-search_preloader_item"></span>
                                    <button class="" type="submit" ></button>
                                </span>
                            </div>
                            <ul class="title-search-result">
                            </ul>
                        </form>
                    </div>
                </div>
            </div>
            
        </div>
        <!-- Конец Поля меню и поиска -->
    </div>

    <!--noindex-->
    <div id="rb-mobile">
        <div class="rb-flex rb-container">
            <div class="col-8 rb-header__logo">
                <a href="<?php echo site_url(); ?>">
                    <?php echo wp_get_attachment_image( $logo, 'medium' ); ?>
                </a>
	            <div class="col-8 rb-header__logo">
                <a href="https://olimp-medgroup.ru/">
                   <svg width="130" height="15" viewBox="0 0 520 85" fill="none" xmlns="http://www.w3.org/2000/svg" class="common-logo__logo"><path d="M129.848 3.84613V28.4615H126.868V6.49036H114.467V28.4615H111.488V3.84613H129.848ZM134.797 35.3848V9.99997H137.537V12.9327H137.873C138.082 12.6121 138.37 12.2035 138.739 11.7067C139.115 11.2019 139.652 10.7532 140.349 10.3606C141.054 9.95993 142.007 9.7596 143.208 9.7596C144.762 9.7596 146.132 10.1482 147.318 10.9255C148.503 11.7027 149.429 12.8045 150.093 14.2307C150.758 15.6571 151.091 17.3397 151.091 19.2788C151.091 21.2339 150.758 22.9287 150.093 24.363C149.429 25.7892 148.507 26.895 147.33 27.6803C146.152 28.4575 144.795 28.8462 143.256 28.8462C142.071 28.8462 141.122 28.6498 140.409 28.2572C139.696 27.8566 139.147 27.4038 138.763 26.899C138.378 26.3862 138.082 25.9615 137.873 25.625H137.633V35.3848H134.797ZM137.585 19.2308C137.585 20.625 137.789 21.855 138.198 22.9207C138.606 23.9784 139.203 24.8077 139.988 25.4086C140.773 26.0016 141.734 26.2981 142.872 26.2981C144.058 26.2981 145.047 25.9856 145.84 25.3606C146.641 24.7275 147.242 23.8782 147.642 22.8125C148.051 21.7388 148.255 20.5448 148.255 19.2308C148.255 17.9327 148.055 16.7628 147.654 15.7212C147.262 14.6714 146.665 13.8421 145.864 13.2332C145.071 12.6162 144.074 12.3077 142.872 12.3077C141.719 12.3077 140.749 12.6001 139.964 13.1851C139.179 13.762 138.586 14.5713 138.186 15.613C137.785 16.6466 137.585 17.8525 137.585 19.2308ZM162.238 28.8462C160.571 28.8462 159.109 28.4495 157.852 27.6562C156.602 26.863 155.625 25.7532 154.92 24.3269C154.223 22.9006 153.875 21.2339 153.875 19.3269C153.875 17.4038 154.223 15.7251 154.92 14.2909C155.625 12.8566 156.602 11.7428 157.852 10.9495C159.109 10.1562 160.571 9.7596 162.238 9.7596C163.904 9.7596 165.362 10.1562 166.611 10.9495C167.869 11.7428 168.846 12.8566 169.543 14.2909C170.248 15.7251 170.601 17.4038 170.601 19.3269C170.601 21.2339 170.248 22.9006 169.543 24.3269C168.846 25.7532 167.869 26.863 166.611 27.6562C165.362 28.4495 163.904 28.8462 162.238 28.8462ZM162.238 26.2981C163.503 26.2981 164.545 25.9735 165.362 25.3245C166.179 24.6755 166.784 23.8221 167.176 22.7644C167.569 21.7067 167.765 20.5609 167.765 19.3269C167.765 18.0929 167.569 16.9431 167.176 15.8774C166.784 14.8117 166.179 13.9503 165.362 13.2933C164.545 12.6362 163.503 12.3077 162.238 12.3077C160.972 12.3077 159.931 12.6362 159.114 13.2933C158.296 13.9503 157.692 14.8117 157.299 15.8774C156.906 16.9431 156.71 18.0929 156.71 19.3269C156.71 20.5609 156.906 21.7067 157.299 22.7644C157.692 23.8221 158.296 24.6755 159.114 25.3245C159.931 25.9735 160.972 26.2981 162.238 26.2981ZM181.991 28.8462C180.212 28.8462 178.678 28.4535 177.389 27.6682C176.107 26.875 175.118 25.7692 174.421 24.3509C173.732 22.9247 173.387 21.266 173.387 19.375C173.387 17.484 173.732 15.8173 174.421 14.375C175.118 12.9246 176.087 11.7949 177.328 10.9856C178.578 10.1683 180.036 9.7596 181.702 9.7596C182.664 9.7596 183.613 9.91986 184.55 10.2404C185.487 10.5609 186.34 11.0817 187.109 11.8029C187.878 12.516 188.491 13.4615 188.948 14.6394C189.404 15.8173 189.633 17.2676 189.633 18.9904V20.1923H175.406V17.7404H186.749C186.749 16.6987 186.541 15.7692 186.124 14.9519C185.715 14.1346 185.131 13.4896 184.37 13.0168C183.617 12.5441 182.728 12.3077 181.702 12.3077C180.573 12.3077 179.595 12.5881 178.77 13.149C177.953 13.7019 177.325 14.4231 176.884 15.3125C176.443 16.2019 176.223 17.1554 176.223 18.1731V19.8077C176.223 21.2019 176.464 22.3838 176.944 23.3534C177.433 24.3149 178.11 25.0481 178.975 25.5529C179.84 26.0497 180.845 26.2981 181.991 26.2981C182.736 26.2981 183.408 26.1939 184.009 25.9856C184.618 25.7692 185.143 25.4487 185.583 25.024C186.024 24.5913 186.364 24.0545 186.605 23.4134L189.344 24.1827C189.056 25.1122 188.571 25.9295 187.89 26.6346C187.21 27.3317 186.368 27.8766 185.367 28.2692C184.366 28.6538 183.24 28.8462 181.991 28.8462ZM193.27 28.4615V9.99997H196.105V18.0288H197.98L204.612 9.99997H208.265L200.816 18.8942L208.362 28.4615H204.709L198.653 20.6731H196.105V28.4615H193.27ZM209.71 12.6442V9.99997H224.705V12.6442H218.649V28.4615H215.813V12.6442H209.71ZM248.395 9.99997V12.6442H239.84V28.4615H237.005V9.99997H248.395ZM251.858 35.3848V9.99997H254.598V12.9327H254.934C255.142 12.6121 255.431 12.2035 255.8 11.7067C256.176 11.2019 256.713 10.7532 257.41 10.3606C258.115 9.95993 259.068 9.7596 260.269 9.7596C261.823 9.7596 263.193 10.1482 264.379 10.9255C265.564 11.7027 266.49 12.8045 267.154 14.2307C267.819 15.6571 268.152 17.3397 268.152 19.2788C268.152 21.2339 267.819 22.9287 267.154 24.363C266.49 25.7892 265.568 26.895 264.391 27.6803C263.213 28.4575 261.856 28.8462 260.317 28.8462C259.132 28.8462 258.183 28.6498 257.47 28.2572C256.757 27.8566 256.208 27.4038 255.824 26.899C255.439 26.3862 255.142 25.9615 254.934 25.625H254.694V35.3848H251.858ZM254.646 19.2308C254.646 20.625 254.85 21.855 255.259 22.9207C255.667 23.9784 256.264 24.8077 257.049 25.4086C257.834 26.0016 258.795 26.2981 259.933 26.2981C261.118 26.2981 262.108 25.9856 262.901 25.3606C263.702 24.7275 264.303 23.8782 264.703 22.8125C265.112 21.7388 265.316 20.5448 265.316 19.2308C265.316 17.9327 265.116 16.7628 264.715 15.7212C264.323 14.6714 263.726 13.8421 262.925 13.2332C262.132 12.6162 261.135 12.3077 259.933 12.3077C258.78 12.3077 257.81 12.6001 257.025 13.1851C256.24 13.762 255.647 14.5713 255.247 15.613C254.846 16.6466 254.646 17.8525 254.646 19.2308ZM272.966 35.3848C272.486 35.3848 272.057 35.3445 271.68 35.2643C271.304 35.1922 271.044 35.1201 270.899 35.048L271.621 32.5481C272.309 32.7244 272.918 32.7884 273.447 32.7404C273.976 32.6923 274.444 32.456 274.853 32.0313C275.269 31.6146 275.65 30.9375 275.994 30L276.523 28.5577L269.698 9.99997H272.774L277.869 24.7115H278.061L283.156 9.99997H286.232L278.397 31.1538C278.045 32.1074 277.608 32.8967 277.088 33.5215C276.567 34.1548 275.962 34.6232 275.273 34.9278C274.592 35.2324 273.823 35.3848 272.966 35.3848ZM289.298 28.4615V9.99997H303.813V28.4615H300.977V12.6442H292.134V28.4615H289.298ZM308.315 28.4615V9.99997H322.83V28.4615H319.994V12.6442H311.151V28.4615H308.315ZM329.784 16.6827H335.07C337.232 16.6827 338.886 17.2315 340.033 18.3293C341.177 19.4271 341.752 20.8173 341.752 22.5C341.752 23.6058 341.495 24.6114 340.981 25.5168C340.47 26.4142 339.715 27.1314 338.724 27.6682C337.729 28.1971 336.511 28.4615 335.07 28.4615H327.332V9.99997H330.167V25.8173H335.07C336.193 25.8173 337.113 25.5208 337.834 24.9279C338.555 24.3349 338.917 23.5737 338.917 22.6442C338.917 21.6666 338.555 20.8694 337.834 20.2524C337.113 19.6354 336.193 19.3269 335.07 19.3269H329.784V16.6827ZM344.683 28.4615V9.99997H347.518V28.4615H344.683ZM360.873 28.4615V9.99997H363.709V18.0288H365.583L372.215 9.99997H375.87L368.419 18.8942L375.964 28.4615H372.31L366.257 20.6731H363.709V28.4615H360.873ZM385.107 28.8462C383.442 28.8462 381.981 28.4495 380.722 27.6562C379.473 26.863 378.495 25.7532 377.792 24.3269C377.095 22.9006 376.746 21.2339 376.746 19.3269C376.746 17.4038 377.095 15.7251 377.792 14.2909C378.495 12.8566 379.473 11.7428 380.722 10.9495C381.981 10.1562 383.442 9.7596 385.107 9.7596C386.775 9.7596 388.23 10.1562 389.482 10.9495C390.737 11.7428 391.715 12.8566 392.412 14.2909C393.119 15.7251 393.471 17.4038 393.471 19.3269C393.471 21.2339 393.119 22.9006 392.412 24.3269C391.715 25.7532 390.737 26.863 389.482 27.6562C388.23 28.4495 386.775 28.8462 385.107 28.8462ZM385.107 26.2981C386.373 26.2981 387.415 25.9735 388.23 25.3245C389.049 24.6755 389.655 23.8221 390.047 22.7644C390.44 21.7067 390.636 20.5609 390.636 19.3269C390.636 18.0929 390.44 16.9431 390.047 15.8774C389.655 14.8117 389.049 13.9503 388.23 13.2933C387.415 12.6362 386.373 12.3077 385.107 12.3077C383.842 12.3077 382.799 12.6362 381.984 13.2933C381.165 13.9503 380.563 14.8117 380.17 15.8774C379.778 16.9431 379.582 18.0929 379.582 19.3269C379.582 20.5609 379.778 21.7067 380.17 22.7644C380.563 23.8221 381.165 24.6755 381.984 25.3245C382.799 25.9735 383.842 26.2981 385.107 26.2981ZM407.216 24.6154L413.655 9.99997H416.348L408.37 28.4615H406.062L398.229 9.99997H400.871L407.216 24.6154ZM399.958 9.99997V28.4615H397.122V9.99997H399.958ZM414.474 28.4615V9.99997H417.309V28.4615H414.474ZM421.823 28.4615V9.99997H436.339V28.4615H433.503V12.6442H424.658V28.4615H421.823ZM446.27 28.8942C445.103 28.8942 444.04 28.6739 443.086 28.2332C442.135 27.7844 441.377 27.1394 440.815 26.2981C440.257 25.4487 439.976 24.4231 439.976 23.2211C439.976 22.1634 440.183 21.3061 440.599 20.649C441.015 19.984 441.573 19.4631 442.27 19.0865C442.967 18.7099 443.736 18.4295 444.578 18.2452C445.427 18.0529 446.28 17.9006 447.136 17.7884C448.259 17.6442 449.166 17.5361 449.863 17.4639C450.57 17.3838 451.081 17.2516 451.403 17.0673C451.731 16.883 451.893 16.5625 451.893 16.1057V16.0096C451.893 14.8237 451.572 13.9022 450.922 13.2452C450.279 12.5881 449.308 12.2596 448.002 12.2596C446.649 12.2596 445.586 12.5561 444.818 13.149C444.05 13.742 443.509 14.375 443.194 15.0481L440.504 14.0865C440.985 12.9647 441.624 12.0913 442.426 11.4663C443.235 10.8333 444.118 10.3926 445.069 10.1442C446.03 9.8878 446.977 9.7596 447.904 9.7596C448.5 9.7596 449.18 9.83172 449.948 9.97594C450.726 10.1122 451.474 10.3966 452.195 10.8293C452.925 11.262 453.528 11.9151 454.008 12.7885C454.489 13.6619 454.729 14.8317 454.729 16.2981V28.4615H451.893V25.9615H451.751C451.558 26.3621 451.237 26.7908 450.79 27.2476C450.34 27.7043 449.745 28.0929 449 28.4135C448.253 28.734 447.346 28.8942 446.27 28.8942ZM446.703 26.3461C447.826 26.3461 448.77 26.1258 449.538 25.6851C450.317 25.2444 450.902 24.6755 451.295 23.9784C451.694 23.2812 451.893 22.5481 451.893 21.7788V19.1827C451.775 19.3269 451.511 19.4591 451.102 19.5793C450.702 19.6915 450.235 19.7917 449.708 19.8798C449.187 19.9599 448.679 20.032 448.182 20.0961C447.694 20.1522 447.299 20.2003 446.994 20.2404C446.256 20.3365 445.566 20.4928 444.927 20.7091C444.294 20.9175 443.779 21.2339 443.387 21.6586C443.005 22.0753 442.812 22.6442 442.812 23.3654C442.812 24.3509 443.174 25.0961 443.905 25.6009C444.642 26.0977 445.576 26.3461 446.703 26.3461ZM471.583 17.9327V20.5769H461.391V17.9327H471.583ZM462.065 9.99997V28.4615H459.229V9.99997H462.065ZM473.745 9.99997V28.4615H470.91V9.99997H473.745ZM481.084 24.2788L489.973 9.99997H493.242V28.4615H490.406V14.1827L481.565 28.4615H478.245V9.99997H481.084V24.2788ZM500.594 24.2788L509.487 9.99997H512.755V28.4615H509.92V14.1827L501.075 28.4615H497.759V9.99997H500.594V24.2788ZM507.612 3.84613H510.255C510.255 5.14423 509.808 6.20592 508.911 7.03122C508.015 7.85656 506.797 8.26921 505.257 8.26921C503.744 8.26921 502.537 7.85656 501.64 7.03122C500.75 6.20592 500.307 5.14423 500.307 3.84613H502.949C502.949 4.47113 503.122 5.02002 503.467 5.49278C503.819 5.96554 504.418 6.20192 505.257 6.20192C506.1 6.20192 506.698 5.96554 507.06 5.49278C507.429 5.02002 507.612 4.47113 507.612 3.84613Z" fill="#666666"></path><path d="M125.635 76.2523C123.415 76.2523 121.372 75.8868 119.504 75.1557C117.636 74.4246 116.012 73.4092 114.631 72.1095C113.251 70.7828 112.182 69.2394 111.424 67.4794C110.666 65.6923 110.287 63.7428 110.287 61.6308C110.287 59.5188 110.666 57.5828 111.424 55.8228C112.182 54.0357 113.251 52.4923 114.631 51.1926C116.012 49.8658 117.636 48.8369 119.504 48.1058C121.372 47.3748 123.402 47.0092 125.594 47.0092C127.814 47.0092 129.844 47.3748 131.685 48.1058C133.553 48.8369 135.177 49.8658 136.557 51.1926C137.938 52.4923 139.007 54.0357 139.765 55.8228C140.523 57.5828 140.902 59.5188 140.902 61.6308C140.902 63.7428 140.523 65.6923 139.765 67.4794C139.007 69.2665 137.938 70.8098 136.557 72.1095C135.177 73.4092 133.553 74.4246 131.685 75.1557C129.844 75.8868 127.828 76.2523 125.635 76.2523ZM125.594 71.6221C127.029 71.6221 128.355 71.3784 129.574 70.8911C130.792 70.4037 131.847 69.7132 132.741 68.8197C133.634 67.8991 134.324 66.8431 134.811 65.6517C135.326 64.4332 135.583 63.0929 135.583 61.6308C135.583 60.1686 135.326 58.8418 134.811 57.6505C134.324 56.432 133.634 55.376 132.741 54.4824C131.847 53.5618 130.792 52.8578 129.574 52.3704C128.355 51.8831 127.029 51.6394 125.594 51.6394C124.16 51.6394 122.833 51.8831 121.615 52.3704C120.424 52.8578 119.368 53.5618 118.448 54.4824C117.555 55.376 116.851 56.432 116.337 57.6505C115.849 58.8418 115.606 60.1686 115.606 61.6308C115.606 63.0658 115.849 64.3926 116.337 65.6111C116.851 66.8295 117.555 67.8991 118.448 68.8197C119.341 69.7132 120.397 70.4037 121.615 70.8911C122.833 71.3784 124.16 71.6221 125.594 71.6221ZM142.328 75.8055L142.572 71.5409C142.761 71.568 142.937 71.5951 143.1 71.6221C143.262 71.6492 143.411 71.6628 143.546 71.6628C144.385 71.6628 145.035 71.4055 145.495 70.8911C145.955 70.3766 146.294 69.6861 146.51 68.8197C146.754 67.9261 146.916 66.9378 146.998 65.8548C147.106 64.7446 147.187 63.6344 147.241 62.5243L147.607 54.1575H165.148V75.8461H160.072V57.0818L161.25 58.4628H150.936L152.033 57.0412L151.748 62.768C151.667 64.6904 151.505 66.4775 151.261 68.1292C151.044 69.7538 150.693 71.1754 150.205 72.3938C149.745 73.6123 149.095 74.56 148.256 75.2369C147.444 75.9138 146.402 76.2523 145.13 76.2523C144.724 76.2523 144.277 76.2117 143.79 76.1304C143.33 76.0492 142.843 75.9409 142.328 75.8055ZM171.983 75.8461V54.1575H177.058V68.4541L189.036 54.1575H193.665V75.8461H188.59V61.5495L176.652 75.8461H171.983ZM200.493 75.8461V54.1575H205.812L214.542 69.5508H212.43L221.607 54.1575H226.357L226.398 75.8461H221.81V59.6L222.622 60.128L214.501 73.5717H212.308L204.147 59.7624L205.122 59.5188V75.8461H200.493ZM233.206 75.8461V54.1575H253.995V75.8461H248.96V57.2849L250.137 58.4628H237.103L238.281 57.2849V75.8461H233.206ZM269.831 73.0437L271.537 69.1852C272.755 70.0246 274.081 70.6744 275.516 71.1348C276.978 71.568 278.426 71.7981 279.861 71.8252C281.295 71.8523 282.595 71.6898 283.759 71.3378C284.95 70.9858 285.897 70.4578 286.601 69.7538C287.305 69.0498 287.657 68.1698 287.657 67.1138C287.657 65.8412 287.129 64.8935 286.073 64.2708C285.044 63.6209 283.637 63.296 281.85 63.296H275.232V59.1532H281.444C283.041 59.1532 284.273 58.8283 285.139 58.1785C286.032 57.5286 286.479 56.6486 286.479 55.5384C286.479 54.6178 286.181 53.8597 285.586 53.264C285.017 52.6683 284.232 52.2215 283.231 51.9237C282.256 51.6258 281.146 51.4904 279.901 51.5175C278.683 51.5175 277.411 51.7071 276.084 52.0861C274.758 52.4381 273.499 52.9661 272.308 53.6701L270.644 49.4055C272.43 48.4578 274.298 47.7944 276.247 47.4154C278.223 47.0363 280.131 46.928 281.972 47.0904C283.813 47.2529 285.464 47.6726 286.926 48.3495C288.414 49.0265 289.606 49.92 290.499 51.0301C291.392 52.1132 291.839 53.3994 291.839 54.8886C291.839 56.2425 291.473 57.4474 290.742 58.5034C290.039 59.5323 289.064 60.3311 287.819 60.8997C286.574 61.4683 285.126 61.7526 283.474 61.7526L283.677 60.4529C285.545 60.4529 287.169 60.7778 288.55 61.4277C289.957 62.0504 291.04 62.9169 291.798 64.0271C292.583 65.1372 292.976 66.4234 292.976 67.8855C292.976 69.2665 292.624 70.4984 291.92 71.5815C291.216 72.6375 290.255 73.5311 289.037 74.2621C287.819 74.9661 286.425 75.4941 284.855 75.8461C283.285 76.1711 281.634 76.2929 279.901 76.2117C278.169 76.1305 276.436 75.8326 274.704 75.3181C272.999 74.8037 271.374 74.0455 269.831 73.0437ZM313.294 73.4905V58.4628H304.971L304.849 61.9557C304.795 63.2012 304.714 64.4061 304.605 65.5705C304.497 66.7077 304.321 67.7637 304.077 68.7384C303.834 69.6861 303.482 70.4714 303.022 71.0941C302.562 71.7169 301.952 72.1231 301.195 72.3126L296.16 71.5409C296.972 71.5409 297.621 71.2837 298.109 70.7692C298.623 70.2277 299.015 69.4966 299.286 68.576C299.584 67.6283 299.8 66.5588 299.936 65.3674C300.071 64.1489 300.166 62.8898 300.22 61.5901L300.504 54.1575H318.37V73.4905H313.294ZM295.388 80.6794V71.5409H321.578V80.6794H316.827V75.8461H300.098V80.6794H295.388ZM335.773 76.1304C333.554 76.1304 331.578 75.6566 329.845 74.7089C328.114 73.7341 326.747 72.4074 325.745 70.7286C324.744 69.0498 324.243 67.1409 324.243 65.0018C324.243 62.8357 324.744 60.9268 325.745 59.2751C326.747 57.5963 328.114 56.2831 329.845 55.3354C331.578 54.3877 333.554 53.9138 335.773 53.9138C338.02 53.9138 340.01 54.3877 341.742 55.3354C343.502 56.2831 344.869 57.5828 345.843 59.2344C346.845 60.8861 347.345 62.8086 347.345 65.0018C347.345 67.1409 346.845 69.0498 345.843 70.7286C344.869 72.4074 343.502 73.7341 341.742 74.7089C340.01 75.6566 338.02 76.1304 335.773 76.1304ZM335.773 71.7846C337.019 71.7846 338.128 71.5138 339.103 70.9723C340.077 70.4308 340.835 69.6455 341.377 68.6166C341.945 67.5877 342.229 66.3828 342.229 65.0018C342.229 63.5938 341.945 62.3889 341.377 61.3871C340.835 60.3581 340.077 59.5729 339.103 59.0314C338.128 58.4898 337.032 58.2191 335.814 58.2191C334.569 58.2191 333.459 58.4898 332.484 59.0314C331.537 59.5729 330.779 60.3581 330.211 61.3871C329.642 62.3889 329.359 63.5938 329.359 65.0018C329.359 66.3828 329.642 67.5877 330.211 68.6166C330.779 69.6455 331.537 70.4308 332.484 70.9723C333.459 71.5138 334.555 71.7846 335.773 71.7846ZM364.379 76.1304C362.619 76.1304 361.009 75.7243 359.547 74.912C358.112 74.0997 356.962 72.8812 356.096 71.2566C355.256 69.6049 354.837 67.52 354.837 65.0018C354.837 62.4566 355.243 60.3717 356.055 58.7471C356.894 57.1224 358.031 55.9175 359.466 55.1323C360.9 54.32 362.538 53.9138 364.379 53.9138C366.517 53.9138 368.398 54.3741 370.023 55.2948C371.674 56.2154 372.973 57.5015 373.921 59.1532C374.895 60.8049 375.382 62.7544 375.382 65.0018C375.382 67.2492 374.895 69.2123 373.921 70.8911C372.973 72.5428 371.674 73.8289 370.023 74.7495C368.398 75.6701 366.517 76.1304 364.379 76.1304ZM352.36 83.7255V54.1575H357.192V59.2751L357.029 65.0425L357.435 70.8098V83.7255H352.36ZM363.81 71.7846C365.028 71.7846 366.111 71.5138 367.059 70.9723C368.033 70.4308 368.805 69.6455 369.373 68.6166C369.941 67.5877 370.226 66.3828 370.226 65.0018C370.226 63.5938 369.941 62.3889 369.373 61.3871C368.805 60.3581 368.033 59.5729 367.059 59.0314C366.111 58.4898 365.028 58.2191 363.81 58.2191C362.592 58.2191 361.496 58.4898 360.521 59.0314C359.547 59.5729 358.775 60.3581 358.207 61.3871C357.639 62.3889 357.354 63.5938 357.354 65.0018C357.354 66.3828 357.639 67.5877 358.207 68.6166C358.775 69.6455 359.547 70.4308 360.521 70.9723C361.496 71.5138 362.592 71.7846 363.81 71.7846ZM390.098 76.1304C387.878 76.1304 385.902 75.6566 384.17 74.7089C382.437 73.7341 381.07 72.4074 380.069 70.7286C379.067 69.0498 378.566 67.1409 378.566 65.0018C378.566 62.8357 379.067 60.9268 380.069 59.2751C381.07 57.5963 382.437 56.2831 384.17 55.3354C385.902 54.3877 387.878 53.9138 390.098 53.9138C392.345 53.9138 394.334 54.3877 396.067 55.3354C397.826 56.2831 399.193 57.5828 400.168 59.2344C401.169 60.8861 401.67 62.8086 401.67 65.0018C401.67 67.1409 401.169 69.0498 400.168 70.7286C399.193 72.4074 397.826 73.7341 396.067 74.7089C394.334 75.6566 392.345 76.1304 390.098 76.1304ZM390.098 71.7846C391.343 71.7846 392.453 71.5138 393.427 70.9723C394.402 70.4308 395.16 69.6455 395.701 68.6166C396.27 67.5877 396.554 66.3828 396.554 65.0018C396.554 63.5938 396.27 62.3889 395.701 61.3871C395.16 60.3581 394.402 59.5729 393.427 59.0314C392.453 58.4898 391.357 58.2191 390.138 58.2191C388.893 58.2191 387.783 58.4898 386.809 59.0314C385.861 59.5729 385.104 60.3581 384.535 61.3871C383.967 62.3889 383.682 63.5938 383.682 65.0018C383.682 66.3828 383.967 67.5877 384.535 68.6166C385.104 69.6455 385.861 70.4308 386.809 70.9723C387.783 71.5138 388.88 71.7846 390.098 71.7846ZM406.684 75.8461V54.1575H417.282C419.908 54.1575 421.965 54.6449 423.454 55.6197C424.97 56.5944 425.728 57.9754 425.728 59.7624C425.728 61.5224 425.024 62.9034 423.616 63.9052C422.209 64.88 420.341 65.3674 418.013 65.3674L418.622 64.1083C421.248 64.1083 423.197 64.5957 424.469 65.5705C425.768 66.5181 426.418 67.9126 426.418 69.7538C426.418 71.6763 425.701 73.1791 424.266 74.2621C422.831 75.3181 420.652 75.8461 417.729 75.8461H406.684ZM411.516 72.1501H417.323C418.703 72.1501 419.745 71.9335 420.449 71.5003C421.153 71.04 421.505 70.3495 421.505 69.4289C421.505 68.4541 421.18 67.7366 420.53 67.2763C419.881 66.816 418.866 66.5858 417.485 66.5858H411.516V72.1501ZM411.516 63.2148H416.835C418.135 63.2148 419.109 62.9846 419.759 62.5243C420.436 62.0369 420.774 61.36 420.774 60.4935C420.774 59.6 420.436 58.9366 419.759 58.5034C419.109 58.0701 418.135 57.8535 416.835 57.8535H411.516V63.2148ZM442.703 61.3058C445.492 61.3058 447.616 61.9286 449.078 63.1741C450.54 64.4197 451.271 66.1797 451.271 68.4541C451.271 70.8098 450.459 72.6511 448.835 73.9778C447.21 75.2775 444.923 75.9138 441.973 75.8868L431.822 75.8461V54.1575H436.897V61.2652L442.703 61.3058ZM441.526 72.1501C443.015 72.1772 444.152 71.8658 444.937 71.216C445.722 70.5661 446.114 69.6184 446.114 68.3729C446.114 67.1274 445.722 66.2338 444.937 65.6923C444.179 65.1237 443.042 64.8258 441.526 64.7988L436.897 64.7581V72.1095L441.526 72.1501ZM467.952 75.8461V68.4541L468.683 69.3071H462.186C459.209 69.3071 456.867 68.6843 455.162 67.4388C453.484 66.1661 452.645 64.3384 452.645 61.9557C452.645 59.4104 453.538 57.4745 455.324 56.1477C457.138 54.8209 459.547 54.1575 462.552 54.1575H472.5V75.8461H467.952ZM452.36 75.8461L458.207 67.5606H463.405L457.801 75.8461H452.36ZM467.952 66.8295V57.0818L468.683 58.4221H462.714C461.144 58.4221 459.926 58.72 459.06 59.3157C458.221 59.8843 457.801 60.8185 457.801 62.1181C457.801 64.5551 459.385 65.7735 462.552 65.7735H468.683L467.952 66.8295Z" fill="#666666"></path><path d="M22.5876 1.04335C21.5848 1.12694 19.5236 1.55883 18.6183 1.86534C17.9498 2.10218 17.6991 2.19971 16.181 2.95205C14.2312 3.91336 13.0055 4.80502 11.4457 6.40721C9.41226 8.4831 7.79667 11.0048 6.98888 13.3593C6.89139 13.6241 6.76604 13.9724 6.71033 14.1256C6.47356 14.7386 6.18109 15.9786 5.87468 17.734C5.69363 18.8068 5.69363 22.053 5.87468 23.2372C6.20894 25.3549 6.33429 26.0097 6.52927 26.7342C6.64069 27.1522 6.77997 27.6816 6.84961 27.9184C7.05852 28.7265 7.47634 29.7575 8.2145 31.3318C8.5209 31.9866 9.89972 34.3272 10.22 34.7452C13.7437 39.3288 15.7632 41.2375 23.3258 47.103C28.2143 50.8925 29.9274 52.3554 31.6265 54.1248C32.9775 55.5458 34.231 57.0923 34.5931 57.8028C34.6906 57.9979 34.802 58.179 34.8438 58.2208C34.9273 58.2905 35.763 60.032 35.9719 60.5196C36.1808 61.0351 36.6265 62.4701 36.7658 63.097C37.0722 64.3788 37.114 64.741 37.1975 66.3014C37.295 68.2101 37.0722 68.0847 39.9273 67.8479C44.983 67.4299 48.5762 66.8169 53.3394 65.5212C54.1751 65.2983 57.2809 64.2813 57.7544 64.0723C57.9076 64.0166 58.7851 63.6683 59.7043 63.306C61.3199 62.6512 64.3561 61.2301 65.6234 60.5057C70.3866 57.7889 73.2 55.6991 76.7097 52.2718C78.5203 50.5163 79.4395 49.4575 81.1108 47.2423C82.1275 45.8769 83.5202 43.7453 83.7152 43.2438C83.7431 43.1741 83.8127 43.0766 83.8684 43.0348C84.0077 42.9233 85.6093 39.5517 85.9575 38.6462C86.1247 38.1864 86.3196 37.6848 86.3754 37.5316C87.1274 35.7483 87.9631 32.2792 88.4505 28.8658C88.6455 27.5562 88.6455 22.9864 88.4505 21.6908C87.9631 18.361 87.1553 15.7278 85.9157 13.3593C85.6093 12.7881 85.289 12.1751 85.2054 12.0079C85.1219 11.8268 85.0105 11.6875 84.9547 11.6875C84.8851 11.6875 84.8433 11.6039 84.8433 11.5064C84.8433 11.4088 84.7319 11.1999 84.5926 11.0466C84.4534 10.9073 84.2027 10.559 84.0077 10.2943C83.0745 8.92893 79.9966 6.17037 78.3949 5.26478C77.6568 4.83288 75.4841 3.78798 74.7459 3.4954C73.9381 3.17496 71.5426 2.57588 70.2056 2.36689C69.0357 2.17184 64.8992 2.17185 63.9243 2.35297C63.5204 2.43656 62.838 2.56195 62.4201 2.64554C61.3199 2.85452 59.5511 3.42574 58.6597 3.85763L57.8937 4.21987L59.7739 4.31739C61.7516 4.42885 63.1026 4.60997 64.37 4.95827C64.7878 5.06973 65.3031 5.20905 65.526 5.23691C65.7349 5.27871 65.9438 5.36231 65.9856 5.43197C66.0273 5.50163 66.1527 5.55736 66.2641 5.55736C66.4452 5.55736 66.8212 5.71061 68.1304 6.29576C69.2167 6.78338 71.3337 8.32985 72.434 9.43049C75.2891 12.2866 77.0161 15.9647 77.7264 20.6737C78.1721 23.6273 78.2417 25.7171 78.0189 28.1971C77.6568 31.9727 77.1554 34.2018 75.7905 38.0889C75.3587 39.287 73.7153 42.6307 72.8657 44.01C72.239 45.0271 70.8741 46.9915 70.6373 47.2144C70.5955 47.2562 70.3449 47.5627 70.0802 47.911C69.2307 49.0117 67.239 51.0736 65.7627 52.3832C62.629 55.1279 58.8129 57.4824 55.0386 58.9871C54.3144 59.2657 53.5901 59.5583 53.4369 59.614C53.1862 59.7255 51.9885 60.1156 50.93 60.4499C49.2865 60.9515 46.4175 61.5645 45.4286 61.6063L44.8019 61.6342L44.6348 60.3106C44.2727 57.5242 42.7128 53.9297 40.8883 51.6727C40.7908 51.5612 40.4148 51.0875 40.0387 50.6278C39.3841 49.7919 38.7713 49.1231 37.0722 47.3119C36.5708 46.7825 34.6906 44.9574 32.8939 43.2716C27.7965 38.4929 26.5987 37.2947 23.4372 33.7699C22.7687 33.0315 21.3342 31.2482 20.9163 30.6352C20.7353 30.3705 20.5403 30.1197 20.4985 30.0779C20.3314 29.9525 19.0222 27.9184 18.5487 27.0825C17.2534 24.7698 16.4874 22.8611 16.0974 21.022C15.8746 20.0189 15.8328 17.3857 16.0278 16.7588C16.0974 16.5359 16.2228 16.0064 16.3203 15.5885C16.4178 15.1705 16.5431 14.7247 16.5988 14.6132C16.6685 14.5018 16.9052 13.9863 17.1559 13.4708C17.9916 11.7432 19.6072 10.0992 21.1949 9.36082C22.5041 8.74781 22.7826 8.69209 24.3286 8.69209C26.0973 8.70602 27.0583 8.95679 28.8271 9.89024C30.582 10.8237 31.5569 11.59 33.3535 13.4011C34.8159 14.8919 35.3591 15.5328 37.6989 18.5839C41.7797 23.9338 43.5067 25.745 46.0275 27.389C47.0164 28.0299 48.8826 28.9215 50.0247 29.2838C51.9328 29.9107 52.5874 29.9943 54.9689 30.0082C57.3645 30.0082 57.7127 29.9664 59.565 29.3534C63.6458 28.0299 66.5984 24.714 67.7126 20.2279C67.9076 19.4337 67.9075 16.1179 67.6986 15.477C67.6151 15.1845 67.4897 14.7386 67.4062 14.4739C67.1694 13.6519 66.3477 12.0776 65.6931 11.1999C64.8296 10.0296 63.3393 8.78961 62.058 8.14873C60.7767 7.52179 60.526 7.43819 58.8686 7.14561C57.8101 6.9645 57.4202 6.95057 56.3617 7.08989C55.6792 7.18741 54.6625 7.41032 54.1194 7.59144C53.047 7.93974 51.5985 8.59456 51.4871 8.76174C51.4453 8.8314 51.9049 8.915 52.4899 8.97072C54.5093 9.15184 56.3338 9.93204 57.6152 11.1999C58.5204 12.1055 58.9661 12.8717 59.2168 14.0142C59.7321 16.3408 57.3645 18.5142 54.3144 18.5142C53.3255 18.5142 51.8353 18.1241 50.4425 17.4972C47.9495 16.3826 45.5401 14.5157 42.4342 11.2835C38.5206 7.21527 36.8772 5.7106 34.6488 4.24773C33.4232 3.42574 31.0834 2.21365 30.7491 2.21365C30.6377 2.21365 30.5123 2.15792 30.4706 2.08826C30.4288 2.01859 30.2477 1.935 30.0806 1.8932C29.8995 1.85141 29.5096 1.73995 29.2032 1.65636C27.3926 1.1548 24.4818 0.890093 22.5876 1.04335Z" fill="#B22E3C"></path><path d="M3.66018 30.7327C2.74097 32.92 2.58777 33.4494 2.18387 35.7761C1.43179 40.1926 3.01952 45.5147 6.34818 49.6525C6.91921 50.363 9.42615 52.9126 10.0529 53.4142C12.3509 55.2672 15.1503 56.9529 20.6378 59.7951C27.504 63.3478 30.2477 65.145 32.5736 67.5971C33.5346 68.6002 34.983 70.5925 34.983 70.899C34.983 70.9687 35.0805 71.0383 35.1919 71.0383C35.7908 71.0383 35.0805 65.9252 34.1474 63.5846C33.6181 62.2611 32.1 59.5165 31.6404 59.0567C31.5987 59.0149 31.2087 58.5412 30.7769 57.9979C29.0082 55.8105 26.3341 53.5117 21.1252 49.7222C18.0055 47.4512 17.7548 47.2422 15.986 45.7794C10.8607 41.4743 7.37881 37.434 5.61003 33.7699C4.78831 32.0562 4.7326 31.9308 4.30084 30.3147L4.11979 29.646L3.66018 30.7327Z" fill="#B22E3C"></path><path d="M87.963 45.1524C87.6844 45.7097 87.4059 46.1834 87.3363 46.2113C87.2666 46.2392 87.2109 46.3227 87.2109 46.3924C87.2109 46.5317 86.1385 48.1897 85.9574 48.329C85.9157 48.3708 85.7207 48.6215 85.5396 48.8863C84.9686 49.7222 82.8377 52.1046 81.4728 53.4281C74.899 59.8369 66.4172 64.7689 56.9187 67.6807C56.0831 67.9454 53.0051 68.6559 52.3226 68.7535C51.9466 68.7953 51.5009 68.8649 51.3477 68.9067C50.5956 69.06 48.4926 69.2411 46.6402 69.3247C45.4982 69.3665 44.6208 69.4501 44.6904 69.4919C45.1639 69.8959 49.3282 71.1777 51.4174 71.5817C53.8547 72.0415 54.3003 72.0833 57.7543 72.0833C61.1526 72.0833 62.2808 71.9997 64.161 71.5956C64.4674 71.526 65.0941 71.4006 65.5537 71.317C66.0133 71.2195 66.5426 71.0941 66.7376 71.0383C66.9325 70.9826 67.3643 70.8572 67.7125 70.7597C73.2277 69.2272 78.1024 66.3989 81.9881 62.484C84.5229 59.9344 86.0271 57.7471 87.2666 54.7795C88.0605 52.9126 88.3112 51.9931 88.6733 49.7361C88.9936 47.716 88.9379 44.1493 88.5897 44.1493C88.5201 44.1493 88.2415 44.6091 87.963 45.1524Z" fill="#B22E3C"></path></svg> 
                </a>
            </div>
            </div>

            <?php echo do_shortcode( '<div class="mob_bvi">[bvi text=""]</div>' ); ?>

            <div class="col-4 f-center-end">
                <div class="rb-mobile">
                    <div class="rb-header__search-button rb-header__mob-search f-center-center">
<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M11.3104 11.226L15.1725 14.8966M13.0124 7.05797C13.0124 10.3465 10.3465 13.0124 7.05797 13.0124C3.76942 13.0124 1.10352 10.3465 1.10352 7.05797C1.10352 3.76942 3.76942 1.10352 7.05797 1.10352C10.3465 1.10352 13.0124 3.76942 13.0124 7.05797Z" stroke="#1B1B1B" stroke-width="1.08263" stroke-linecap="round"/>
</svg>

                    </div>
                </div> 
				                <div class="col-4 col-m-2 col-s-12 rb-header__search">                                                    
                    <div id="header-search" class="bx-searchtitle theme-blue">
                        <form class="header-search-form <?php if ( isset( $_GET[ 's' ] ) && $_GET[ 's' ] ) { echo 'active'; } ?>" action="<?php echo site_url(); ?>" method="GET">
                            <div class="bx-input-group header__search--group">
                                <input id="smart-title-search-input" placeholder="Поиск по сайту" type="text" name="s" value="" autocomplete="off" class="bx-form-control"/>
                                <span class="bx-input-group-btn">
                                    <span class="bx-searchtitle-preloader view" id="smart-title-search_preloader_item"></span>
                                    <button class="" type="submit" ></button>
                                </span>
                            </div>
                            <ul class="title-search-result">
                            </ul>
                        </form>
                    </div>
                </div>
                <div class="btn-lk-div">
                    <a href="https://lk.olimp03.ru/" class="btn-lk-mob"><img src="/wp-content/uploads/2025/05/vector.png"></a>
                </div>
                <div class="rb-header__burger f-center-center" onclick="toggleMobileMenu()">
                    <div class="rb-header__burger-wrap">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>
        </div>
	
    </div>
    <!--/noindex-->
</header>

<!-- БОКОВОЕ МОБИЛЬНОЕ МЕНЮ - ИСПРАВЛЕННАЯ ВЕРСИЯ -->
<div class="rb-mobile-menu-overlay" onclick="closeMobileMenu()"></div>
<div class="rb-mobile-menu-sidebar" id="mobileMenuSidebar">
    <!-- Верхняя секция с контактами -->
    <div class="rb-mobile-menu-header">
        <div class="rb-mobile-logo">
            <?php echo wp_get_attachment_image( $logo_mob, 'medium' ); ?>
        </div>
        <div class="rb-mobile-contacts">
            <div class="rb-mobile-contact-item">
                <div class="rb-mobile-contact-icon icon-location"></div>
                <div class="rb-mobile-contact-text">
                    <div class="rb-mobile-contact-main">г. Воронеж</div>
                    <div class="rb-mobile-contact-sub">ул. Моисеева, 2/2</div>
                </div>
            </div>
            <div class="rb-mobile-contact-item">
                <div class="rb-mobile-contact-icon icon-clock"></div>
                <div class="rb-mobile-contact-text">
                    <div class="rb-mobile-contact-main">пн-пт: 08:00 — 20:00</div>
                    <div class="rb-mobile-contact-sub">сб-вс: 08:00 — 18:00</div>
                </div>
            </div>
            <div class="rb-mobile-contact-item">
                <div class="rb-mobile-contact-icon icon-phone"></div>
                <div class="rb-mobile-contact-text">
					<a class="rb-header__market--phone" href="tel:<?php echo $phone; ?>">
                    <div class="rb-mobile-contact-main"><?php echo $phone; ?></div>
					</a>
                    <div class="rb-mobile-contact-sub">08:00 — 20:00</div>
                </div>
            </div>
        </div>
    </div>
<div>
	
	
    <div class="rb-mobile-menu-content">
        						<nav class="rb-header__menu-list">
							<?php
								wp_nav_menu(
									array(
										'theme_location' => 'main_menu',
										'container' => 'false'
									)
								);
							?>
						</nav>
    </div>

    <div class="rb-mobile-menu-buttons">
        <script defer src="https://booking.olimp-medgroup.ru/b.js?inline&animate=true&button=0&text=%D0%97%D0%B0%D0%BF%D0%B8%D1%81%D0%B0%D1%82%D1%8C%D1%81%D1%8F+%D0%BE%D0%BD%D0%BB%D0%B0%D0%B9%D0%BD&position=br&verticalPadding=32&horizontalPadding=32&url=%2F%3Fclinics%255B%255D%3D25350%26city%3D368"></script>
        <?php /*<a href="<?php echo site_url('bio-market/'); ?>" class="rb-mobile-btn-biomarket">
            <span class="rb-mobile-btn-biomarket-text">Биомаркет</span>
            <div class="rb-mobile-btn-biomarket-arrow"></div>
        </a>*/ ?>
        <a href="#" class="rb-mobile-btn-appointment js-open-modal" data-modal="1">
            <span class="rb-mobile-btn-appointment-text">Записаться</span>
        </a>
    </div>
</div>

</div>

<script>
function toggleMobileMenu() {
    const sidebar = document.getElementById('mobileMenuSidebar');
    const overlay = document.querySelector('.rb-mobile-menu-overlay');
    const burger = document.querySelector('.rb-header__burger');
    
    sidebar.classList.toggle('open');
    overlay.classList.toggle('open');
    burger.classList.toggle('rb-burger-show');
    
    if (sidebar.classList.contains('open')) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
}

function closeMobileMenu() {
    const sidebar = document.getElementById('mobileMenuSidebar');
    const overlay = document.querySelector('.rb-mobile-menu-overlay');
    const burger = document.querySelector('.rb-header__burger');
    
    sidebar.classList.remove('open');
    overlay.classList.remove('open');
    burger.classList.remove('rb-burger-show');
    document.body.style.overflow = '';
}

function toggleSubmenu(element) {
    const menuItem = element;
    const submenuId = 'submenu-' + menuItem.getAttribute('data-id');
    
    let submenu = null;
    if (menuItem.nextElementSibling && menuItem.nextElementSibling.classList.contains('rb-mobile-submenu')) {
        submenu = menuItem.nextElementSibling;
    }
    
    if (submenu) {
        menuItem.classList.toggle('expanded');
        submenu.classList.toggle('open');
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeMobileMenu();
    }
});

window.addEventListener('resize', function() {
    if (window.innerWidth > 767) {
        closeMobileMenu();
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const menuItems = document.querySelectorAll('.rb-mobile-menu-item.has-submenu');
    menuItems.forEach((item, index) => {
        const submenu = item.nextElementSibling;
        if (submenu && submenu.classList.contains('rb-mobile-submenu')) {
            const submenuId = submenu.id.replace('submenu-', '');
            item.setAttribute('data-id', submenuId);
        }
    });
    
    const searchButton = document.querySelector('.rb-header__mob-search');
    if (searchButton) {
        searchButton.addEventListener('click', function() {
            console.log('Открыть поиск');
        });
    }
});
</script>

		<main>
			<nav class="breadcrumbs"><?php display_breadcrumbs(); ?></nav>