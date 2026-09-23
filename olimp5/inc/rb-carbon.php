<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

add_action( 'carbon_fields_register_fields', 'rb_carbon' );
function rb_carbon() {
	if ( ! function_exists( 'rb_cf_article_categories_options' ) ) {
    function rb_cf_article_categories_options() {
        global $wpdb;

        $options = array();

        /**
         * Берём рубрики напрямую из БД, чтобы Carbon Fields не терял пустые
         * или неактивные рубрики в списках настроек.
         */
        $terms = $wpdb->get_results("
            SELECT 
                t.term_id,
                t.name,
                t.slug
            FROM {$wpdb->terms} AS t
            INNER JOIN {$wpdb->term_taxonomy} AS tt 
                ON tt.term_id = t.term_id
            WHERE tt.taxonomy = 'category'
            ORDER BY t.name ASC
        ");

        if ( ! empty( $terms ) ) {
            foreach ( $terms as $term ) {
                $options[ (string) $term->term_id ] = $term->name . ' / ' . $term->slug;
            }
        }

        return $options;
    }
}
Container::make('post_meta', 'Конструктор статьи')
    ->where('post_type', '=', 'post')
    ->add_tab(__('Конструктор'), array(
      Field::make('complex', 'rb_post_builder', 'Блоки статьи')
        ->set_layout('tabbed-vertical')

        ->add_fields('heading', array(
          Field::make('select', 'pos', 'Позиция')
            ->set_options(array(
              'before_content' => 'Перед контентом',
              'after_content'  => 'После контента',
              'custom_1'       => 'Пользовательский слот 1',
              'custom_2'       => 'Пользовательский слот 2',
            ))
            ->set_default_value('before_content')
            ->set_width(40),
          Field::make('select', 'tag', 'Тег')
            ->set_options(array('h2' => 'H2', 'h3' => 'H3', 'h4' => 'H4'))
            ->set_default_value('h2')
            ->set_width(20),
          Field::make('text', 'text', 'Текст заголовка')->set_width(40),
        ))

        ->add_fields('text', array(
          Field::make('select', 'pos', 'Позиция')
            ->set_options(array(
              'before_content' => 'Перед контентом',
              'after_content'  => 'После контента',
              'custom_1'       => 'Пользовательский слот 1',
              'custom_2'       => 'Пользовательский слот 2',
            ))
            ->set_default_value('before_content'),
          Field::make('rich_text', 'content', 'Текст/HTML'),
        ))

        ->add_fields('shortcode', array(
          Field::make('select', 'pos', 'Позиция')
            ->set_options(array(
              'before_content' => 'Перед контентом',
              'after_content'  => 'После контента',
              'custom_1'       => 'Пользовательский слот 1',
              'custom_2'       => 'Пользовательский слот 2',
            ))
            ->set_default_value('before_content'),
          Field::make('text', 'code', 'Шорткод'),
        ))

        ->add_fields('image', array(
          Field::make('select', 'pos', 'Позиция')
            ->set_options(array(
              'before_content' => 'Перед контентом',
              'after_content'  => 'После контента',
              'custom_1'       => 'Пользовательский слот 1',
              'custom_2'       => 'Пользовательский слот 2',
            ))
            ->set_default_value('before_content'),
          Field::make('image', 'image', 'Изображение')->set_value_type('url'),
          Field::make('text', 'alt', 'Alt')->set_width(50),
          Field::make('text', 'caption', 'Подпись')->set_width(50),
          Field::make('select', 'align', 'Выравнивание')
            ->set_options(array(
              ''       => 'По ширине',
              'left'   => 'Слева',
              'right'  => 'Справа',
              'center' => 'По центру',
            )),
        ))

        ->set_header_template('
          <% if (entry._type == "heading") { %>
            🅷 Заголовок: <%- text %>
          <% } else if (entry._type == "text") { %>
            ✍️ Текстовый блок
          <% } else if (entry._type == "shortcode") { %>
            🔗 Шорткод: <%- code %>
          <% } else if (entry._type == "image") { %>
            🖼 Картинка
          <% } else { %>
            Блок
          <% } %>
        ')
    ));
Container::make( 'theme_options', 'Настройки сайта' )

    ->set_page_menu_position( 8 )

    ->set_icon( 'dashicons-admin-generic' )

    ->add_tab( __( 'Основные' ), array(

        Field::make( 'image', 'logo', 'Выберите логотип для сайта' )
            ->set_width( 33 ),

        Field::make( 'image', 'logo_mob', 'Выберите логотип мобильный для сайта' )
            ->set_width( 33 ),

        Field::make( 'image', 'footer_logo', 'Выберите логотип в футере для сайта' )
            ->set_width( 33 ),

        Field::make( 'text', 'copyring', 'Запись в конце страницы' ),

        Field::make( 'text', 'email', 'Почта' ),

        Field::make( 'text', 'phone', 'Телефон' ),

        Field::make( 'file', 'policy', 'Политика конфиденциальности' )
            ->set_width( 50 ),

        Field::make( 'file', 'persons', 'Пользовательское соглашение' )
            ->set_width( 50 ),

        Field::make( 'text', 'emails', 'Укажите, через запятую почты, на которые нужно отправлять письма' ),

        Field::make( 'text', 'video_emails', 'Укажите, через запятую почты, на которые нужно отправлять письма со странице с видео для врачей' ),

    ) )

    ->add_tab( __( 'Статьи' ), array(

        Field::make( 'separator', 'rb_blog_blocks_sep', 'Управление блоками статей' )
            ->set_help_text( 'Настройки применяются к одиночным страницам записей: Новости, Блог, Пресса, Акции, Статьи, События и другим рубрикам.' ),

        Field::make( 'select', 'rb_blog_show_author_card', 'Карточка автора по умолчанию' )
            ->set_options( array(
                'yes' => 'Показывать',
                'no'  => 'Скрывать',
            ) )
            ->set_default_value( 'yes' )
            ->set_width( 50 )
            ->set_help_text( 'Глобальная настройка. Можно переопределить ниже для отдельных рубрик.' ),

        Field::make( 'select', 'rb_blog_show_related_articles', 'Блок «Похожие статьи» по умолчанию' )
            ->set_options( array(
                'yes' => 'Показывать',
                'no'  => 'Скрывать',
            ) )
            ->set_default_value( 'yes' )
            ->set_width( 50 )
            ->set_help_text( 'Глобальная настройка. Можно переопределить ниже для отдельных рубрик.' ),

        Field::make( 'separator', 'rb_blog_author_sep', 'Карточка автора по рубрикам' ),

        Field::make( 'multiselect', 'rb_blog_author_card_show_categories', 'Рубрики, где карточку автора показывать принудительно' )
            ->set_options( 'rb_cf_article_categories_options' )
            ->set_width( 50 )
            ->set_help_text( 'Если глобально карточка автора скрыта, здесь можно выбрать рубрики, где её всё равно показывать.' ),

Field::make( 'multiselect', 'rb_blog_author_card_hide_categories', 'Рубрики, где карточку автора скрывать' )
    ->set_options( 'rb_cf_article_categories_options' )
    ->set_width( 50 )
    ->set_help_text( 'Имеет приоритет над показом. Если статья относится к этой рубрике — карточка автора будет скрыта.' ),

        Field::make( 'separator', 'rb_blog_related_sep', 'Похожие статьи по рубрикам' ),

        Field::make( 'multiselect', 'rb_blog_related_show_categories', 'Рубрики, где похожие статьи показывать принудительно' )
            ->set_options( 'rb_cf_article_categories_options' )
            ->set_width( 50 )
            ->set_help_text( 'Если глобально похожие статьи скрыты, здесь можно выбрать рубрики, где их всё равно показывать.' ),

        Field::make( 'multiselect', 'rb_blog_related_hide_categories', 'Рубрики, где похожие статьи скрывать' )
            ->set_options( 'rb_cf_article_categories_options' )
            ->set_width( 50 )
            ->set_help_text( 'Имеет приоритет над показом. Если статья относится к этой рубрике — похожие статьи будут скрыты.' ),

    ) );
    // ->add_tab( __( 'Контактные' ), array(
    //      Field::make( 'complex', 'social', 'Slides' )
    //             ->set_layout( 'tabbed-horizontal' )
    //             ->add_fields( array(
    //                 Field::make( 'textarea', 'svg', 'SVG' )
    //                     ->set_width(50),
    //                 Field::make( 'text', 'link', 'Link' )
    //                     ->set_width(50),
    //             ) ),
    // ) )
    // ->add_tab( __( 'Форма' ), array(
    //     Field::make( 'text', 'form_title', 'Название' )
    //           ->set_width(50),
    //     Field::make( 'image', 'form_image', 'Фотография' )
    //           ->set_width(50),
    //     Field::make( 'text', 'form_name', 'Имя' )
    //           ->set_width(50),
    //     Field::make( 'text', 'form_phone', 'Телефон' )
    //           ->set_width(50),
    //     Field::make( 'text', 'form_email', 'Email' )
    //           ->set_width(50),
    //     Field::make( 'text', 'form_accept', 'Согласие' )
    //           ->set_width(50),
    // ) )
    // ->add_tab( __( 'Промо' ), array(
    //     Field::make( 'text', 'promo_green', 'Название зелёной кнопки' )
    //           ->set_width(50),
    //     Field::make( 'text', 'promo_red', 'Название красной кнопки' )
    //           ->set_width(50),
    // ) );
// ── 1. Поля карточки автора ──────────────────────────────────────────────────
Container::make( 'post_meta', 'Данные автора' )
    ->where( 'post_type', '=', 'rb_author' )
    ->add_fields( array(

        Field::make( 'image', 'rb_author_photo', 'Фото' )
            ->set_width( 25 )
            ->set_help_text( 'Если не задана — используется стандартная миниатюра записи' ),

        Field::make( 'text', 'rb_author_area', 'Область деятельности' )
            ->set_width( 75 )
            ->set_help_text( 'Например: Кардиология, Диетология' ),

        Field::make( 'textarea', 'rb_author_bio', 'Краткое описание' )
            ->set_help_text( '2–4 предложения об авторе' ),

        Field::make( 'text', 'rb_author_link', 'Ссылка на профиль' )
            ->set_help_text( 'URL страницы врача или внешнего профиля (необязательно)' ),

    ) );


// ── 2. Привязка автора к статьям блога ──────────────────────────────────────
Container::make( 'post_meta', 'Автор статьи' )
    ->where( 'post_type', '=', 'post' )
    ->add_fields( array(

        Field::make( 'association', 'rb_post_author', 'Выберите автора' )
            ->set_types( array(
                array(
                    'type'      => 'post',
                    'post_type' => 'rb_author',
                ),
            ) )
            ->set_max( 1 )
            ->set_help_text( 'Привяжите одного автора к этой статье' ),

    ) );
    //для слайдера
    Container::make( 'post_meta', 'Настройки' )
        ->where( 'post_type', '=', 'slider' )
        ->add_fields( array(
                Field::make( 'color', 'rb_slider_text_color', 'Цвет всего текста' )
                        ->set_width(50),
                Field::make( 'text', 'rb_slider_font_size', 'Размер всего текста' )
                        ->set_width(50),
                Field::make( 'checkbox', 'rb_slider_hide_text', 'Скрыть текст?' ),
                Field::make( 'separator', 'rb_slider_title_separator', 'Заголовок' ),
                Field::make( 'checkbox', 'rb_slider_hide_title', 'Скрыть заголовок?' )
                        ->set_width(33),
                Field::make( 'color', 'rb_slider_title_color', 'Цвет заголовка' )
                        ->set_width(33),
                Field::make( 'text', 'rb_slider_title_size', 'Размер заголовка' )
                        ->set_width(33),
                Field::make( 'separator', 'rb_slider_image_separator', 'Фотографии' ),
                Field::make( 'image', 'rb_slider_img', 'Фото' )
                        ->set_width(33),
                Field::make( 'image', 'rb_slider_img_tab', 'Фото планшет' )
                        ->set_width(33),
                Field::make( 'image', 'rb_slider_img_mob', 'Фото мобильное' )
                        ->set_width(33),
                Field::make( 'checkbox', 'rb_slider_img_mob_contain', 'Вписываем фото в фон?' ),
                Field::make( 'text', 'rb_slider_subtitle', 'Подзаголовок' ),
                Field::make( 'textarea', 'rb_slider_text', 'Описание' ),
                Field::make( 'color', 'rb_slider_desc_color', 'Цвет текста описания' )
                        ->set_width(50),
                Field::make( 'text', 'rb_slider_desc_size', 'Размер описания' )
                        ->set_width(50),
                Field::make( 'separator', 'rb_slider_link_separator', 'Ссылка' ),
                Field::make( 'text', 'rb_slider_link', 'Ссылка' ),
                Field::make( 'separator', 'rb_slider_btn_separator', 'Кнопка' ),
                Field::make( 'checkbox', 'rb_slider_hide_btn', 'Cкрыть кнопку?' )
                        ->set_width(50),
                Field::make( 'checkbox', 'rb_slider_up_btn_mob', 'Поднять кнопку на мобилке?' )
                        ->set_width(50),
                Field::make( 'text', 'rb_slider_btn_text', 'Текст кнопки' )
                        ->set_width(50),
                Field::make( 'color', 'rb_slider_text_btn_color', 'Цвет текста кнопки' )
                        ->set_width(50),
                Field::make( 'text', 'rb_slider_btn_offset_mob', 'Отступ кнопки от низа на мобилке' )
                        ->set_width(50),
                Field::make( 'text', 'rb_slider_btn_offset_left_desk', 'Отступ кнопки слева десктоп' )
                        ->set_width(50),
                Field::make( 'text', 'rb_slider_text_on_the_bot', 'Текст внизу слайда' ),
                Field::make( 'separator', 'rb_slider_utm_separator', 'Метка для метрики' ),
                Field::make( 'text', 'rb_slider_utm_link', 'Метка для метрики' ),
                
                
    ));

    //для новостей
    Container::make( 'post_meta', 'Настройки' )
        ->where( 'post_type', '=', 'post' )
        ->add_fields( array(
                Field::make( 'text', 'rb_start_date', 'Дата начала события' )
                        ->set_width(50),
                Field::make( 'text', 'rb_end_date', 'Дата окончания события' )
                        ->set_width(50),
                Field::make( 'checkbox', 'rb_add_btn', 'Добавить кнопку записаться на приём?' ),
                
    ));

    //для товара биомаркета
    Container::make( 'post_meta', 'Настройки' )
        ->where( 'post_type', '=', 'bio-market' )
        ->add_fields( array(
                Field::make( 'text', 'rb_market_country', 'Производитель' )
                        ->set_width(33),
                Field::make( 'text', 'rb_market_volume', 'Объём' )
                        ->set_width(33),
                Field::make( 'text', 'rb_market_price', 'Цена' )
                        ->set_width(33),
                Field::make( 'complex', 'rb_market_features', 'Дополнительные свойства товара' )
                        ->set_layout( 'tabbed-vertical' )
                        ->add_fields( 'info', array(
                            Field::make( 'text', 'title', 'Название' )
                                ->set_width(50),
                            Field::make( 'text', 'val', 'Описание' )
                                ->set_width(50),

                        ) )
                        ->set_header_template( '
                              <% if (title) { %>
                                  <%- title %>
                              <% } %>
                      ' ),
                
    ));

    //поля для услуг
    Container::make( 'post_meta', 'Настройки' )
        ->where( 'post_type', '=', 'services' )
        ->add_tab( __( 'Основные' ), array(
                Field::make( 'text', 'rb_big_title', 'Большой заголовок' ),
                Field::make( 'rich_text', 'rb_desc', 'Описание' ),
                Field::make( 'association', 'rb_review_brand', 'Выберите бренд, с которым ассоциирован этот отзыв' )
                      ->set_types( array(
                          array(
                                  'type'       => 'term',
                                  'taxonomy'   => 'brand',
                          ),

                      ) )
                      ->set_max( 1 ),
          ))
          ->add_tab( __( 'Карточка для главной' ), array(
                Field::make( 'media_gallery', 'rb_video', 'Видео для карточки на главной' )
                        ->set_type( array( 'video' ) )
                        ->set_width(50),
                Field::make( 'text', 'rb_block_name', 'Надпись для карточки на главной' )
                        ->set_width(50),
                Field::make( 'color', 'rb_block_bg_color', 'Цвет фона' )
                        ->set_width(50),
                Field::make( 'color', 'rb_block_text_color', 'Цвет текста' )
                        ->set_width(50),

    ));

    //поля для главной страницы
    Container::make( 'post_meta', 'Настройки страницы' )
    ->where( 'post_type', '=', 'page' )
    ->where( 'post_template', '=', 'template-page/page-main.php' )
    ->add_tab( __( 'Слайдер' ), array(
             Field::make( 'association', 'rb_main_slider', 'Выберите слайды, которые хотите вывести на главной' )
                    ->set_types( array(
                          array(
                                  'type'        => 'post',
                                  'post_type'   => 'slider',
                          ),

                    ) ),

    ) )
    ->add_tab( __( 'Инфоблок' ), array(
            Field::make( 'text', 'rb_main_about_upper_title', 'Надзаголовок' ),
            Field::make( 'text', 'rb_main_about_title', 'Заголовок' ),
            Field::make( 'textarea', 'rb_main_about_text', 'Заголовок' ),
            Field::make( 'image', 'rb_main_about_img_1', 'Фото 1' )
                    ->set_width(50),
            Field::make( 'image', 'rb_main_about_img_2', 'Фото 2' )
                    ->set_width(50),
    ) )
    ->add_tab( __( 'Блок услуги' ), array(
            Field::make( 'text', 'rb_main_services_title', 'Заголовок блока "Услуги"' ),
            Field::make( 'complex', 'rb_main_services_before', 'Введите блоки, которые указать до основного списка' )
                    ->set_layout( 'tabbed-vertical' )
                    ->add_fields( 'info', array(
                        Field::make( 'text', 'title', 'Название' )
                            ->set_width(50),
                        Field::make( 'text', 'link', 'Ссылка' )
                            ->set_width(50),
                        Field::make( 'color', 'back_color', 'цвет фона' )
                            ->set_width(50),
                        Field::make( 'color', 'text_color', 'цвет текста' )
                            ->set_width(50),
                        Field::make( 'image', 'bg_1', 'Фоновая фото 1' )
                            ->set_width(50),
                        Field::make( 'image', 'bg_2', 'Фоновая фото 2' )
                            ->set_width(50),
                    ) )
                    ->set_header_template( '
                          <% if (title) { %>
                              <%- title %>
                          <% } %>
                  ' ),
            Field::make( 'association', 'rb_main_services', 'Выберите услуги, которые хотите вывести на главной' )
                    ->set_types( array(
                        array(
                              'type'        => 'term',
                              'taxonomy'   => 'services',
                        ),

                    ) ),
            Field::make( 'complex', 'rb_main_services_after', 'Введите блоки, которые указать после основного списка' )
                    ->set_layout( 'tabbed-vertical' )
                    ->add_fields( 'info', array(
                        Field::make( 'text', 'title', 'Название' )
                            ->set_width(50),
                        Field::make( 'text', 'link', 'Ссылка' )
                            ->set_width(50),
                        Field::make( 'color', 'back_color', 'цвет фона' )
                            ->set_width(50),
                        Field::make( 'color', 'text_color', 'цвет текста' )
                            ->set_width(50),
                    ) )
                    ->set_header_template( '
                          <% if (title) { %>
                              <%- title %>
                          <% } %>
                  ' ),


    ) )
		
		    ->add_tab( __( 'Блок специалистов' ), array(
            Field::make( 'text', 'rb_main_doctors_title', 'Заголовок блока "Специалисты"' )
                    ->set_default_value('Специалисты')
                    ->set_help_text('Введите заголовок для блока специалистов'),
            
            Field::make( 'association', 'rb_main_doctors', 'Выберите специалистов для вывода на главной' )
                    ->set_types( array(
                        array(
                              'type'        => 'post',
                              'post_type'   => 'doctors',
                        ),
                    ) )
                    ->set_help_text('Выберите врачей, которых нужно показать на главной странице'),
            
            Field::make( 'checkbox', 'rb_main_doctors_show', 'Отображать блок специалистов' )
                    ->set_default_value(true)
                    ->set_help_text('Снимите галочку, чтобы скрыть блок специалистов на главной'),
    ) )
		->add_tab( __( 'Конструктор' ), array(
    Field::make( 'complex', 'rb_main_builder', 'Блоки (Конструктор главной)' )
        ->set_layout( 'tabbed-vertical' )

        ->add_fields( 'heading', array(
            Field::make( 'select', 'pos', 'Куда вставить' )->set_options( array(
                'after_slider'        => 'После слайдера',
                'after_form_top'      => 'После формы (form-main-top)',
                'about_top'           => 'Внутри About: в самом начале секции',
                'about_after_adv'     => 'Внутри About: после [advantages]',
                'about_bottom'        => 'Внутри About: в конце секции',

                'services_top'        => 'Перед секцией Услуги',
                'services_header'     => 'Внутри Услуги: после заголовка/описания',
                'services_inside_top' => 'Внутри Услуги: перед списком кубиков',
                'services_inside_bot' => 'Внутри Услуги: после списка кубиков',
                'services_bottom'     => 'После секции Услуги',

                'before_doctors'      => 'Перед секцией Специалисты',
                'after_doctors'       => 'После секции Специалисты',

                'before_news'         => 'Перед секцией Новости',
                'after_news'          => 'После секции Новости',

                'before_biomarket'    => 'Перед секцией Биомаркет',
                'after_biomarket'     => 'После секции Биомаркет',

                'before_events'       => 'Перед секцией События',
                'after_events'        => 'После секции События',

                'before_form_bottom'  => 'Перед нижней формой',
                'after_form_bottom'   => 'После нижней формы',

                'before_contacts'     => 'Перед контактами',
                'after_contacts'      => 'После контактов',
            ) )
                ->set_default_value('after_form_top')
                ->set_width(40),

            Field::make( 'select', 'tag', 'Уровень' )
                ->set_options( array( 'h2'=>'H2','h3'=>'H3','h4'=>'H4' ) )
                ->set_default_value('h2')
                ->set_width(20),

            Field::make( 'text', 'text', 'Текст заголовка' )->set_width(40),
        ) )

        ->add_fields( 'text', array(
            Field::make( 'select', 'pos', 'Куда вставить' )->set_options( array(
                'after_slider'        => 'После слайдера',
                'after_form_top'      => 'После формы (form-main-top)',
                'about_top'           => 'Внутри About: в самом начале секции',
                'about_after_adv'     => 'Внутри About: после [advantages]',
                'about_bottom'        => 'Внутри About: в конце секции',

                'services_top'        => 'Перед секцией Услуги',
                'services_header'     => 'Внутри Услуги: после заголовка/описания',
                'services_inside_top' => 'Внутри Услуги: перед списком кубиков',
                'services_inside_bot' => 'Внутри Услуги: после списка кубиков',
                'services_bottom'     => 'После секции Услуги',

                'before_doctors'      => 'Перед секцией Специалисты',
                'after_doctors'       => 'После секции Специалисты',

                'before_news'         => 'Перед секцией Новости',
                'after_news'          => 'После секции Новости',

                'before_biomarket'    => 'Перед секцией Биомаркет',
                'after_biomarket'     => 'После секции Биомаркет',

                'before_events'       => 'Перед секцией События',
                'after_events'        => 'После секции События',

                'before_form_bottom'  => 'Перед нижней формой',
                'after_form_bottom'   => 'После нижней формы',

                'before_contacts'     => 'Перед контактами',
                'after_contacts'      => 'После контактов',
            ) )->set_default_value('after_form_top'),

            Field::make( 'rich_text', 'content', 'Текст/HTML' ),
        ) )

        ->add_fields( 'shortcode', array(
            Field::make( 'select', 'pos', 'Куда вставить' )->set_options( array(
                'after_slider'        => 'После слайдера',
                'after_form_top'      => 'После формы (form-main-top)',
                'about_top'           => 'Внутри About: в самом начале секции',
                'about_after_adv'     => 'Внутри About: после [advantages]',
                'about_bottom'        => 'Внутри About: в конце секции',

                'services_top'        => 'Перед секцией Услуги',
                'services_header'     => 'Внутри Услуги: после заголовка/описания',
                'services_inside_top' => 'Внутри Услуги: перед списком кубиков',
                'services_inside_bot' => 'Внутри Услуги: после списка кубиков',
                'services_bottom'     => 'После секции Услуги',

                'before_doctors'      => 'Перед секцией Специалисты',
                'after_doctors'       => 'После секции Специалисты',

                'before_news'         => 'Перед секцией Новости',
                'after_news'          => 'После секции Новости',

                'before_biomarket'    => 'Перед секцией Биомаркет',
                'after_biomarket'     => 'После секции Биомаркет',

                'before_events'       => 'Перед секцией События',
                'after_events'        => 'После секции События',

                'before_form_bottom'  => 'Перед нижней формой',
                'after_form_bottom'   => 'После нижней формы',

                'before_contacts'     => 'Перед контактами',
                'after_contacts'      => 'После контактов',
            ) )->set_default_value('after_form_top'),

            Field::make( 'text', 'code', 'Шорткод' )
                ->set_help_text('Например: [advantages] или [contact-form-7 id="123"]'),
        ) )

        ->add_fields( 'image', array(
            Field::make( 'select', 'pos', 'Куда вставить' )->set_options( array(
                'after_slider'        => 'После слайдера',
                'after_form_top'      => 'После формы (form-main-top)',
                'about_top'           => 'Внутри About: в самом начале секции',
                'about_after_adv'     => 'Внутри About: после [advantages]',
                'about_bottom'        => 'Внутри About: в конце секции',

                'services_top'        => 'Перед секцией Услуги',
                'services_header'     => 'Внутри Услуги: после заголовка/описания',
                'services_inside_top' => 'Внутри Услуги: перед списком кубиков',
                'services_inside_bot' => 'Внутри Услуги: после списка кубиков',
                'services_bottom'     => 'После секции Услуги',

                'before_doctors'      => 'Перед секцией Специалисты',
                'after_doctors'       => 'После секции Специалисты',

                'before_news'         => 'Перед секцией Новости',
                'after_news'          => 'После секции Новости',

                'before_biomarket'    => 'Перед секцией Биомаркет',
                'after_biomarket'     => 'После секции Биомаркет',

                'before_events'       => 'Перед секцией События',
                'after_events'        => 'После секции События',

                'before_form_bottom'  => 'Перед нижней формой',
                'after_form_bottom'   => 'После нижней формы',

                'before_contacts'     => 'Перед контактами',
                'after_contacts'      => 'После контактов',
            ) )->set_default_value('after_form_top'),

            Field::make( 'image', 'image', 'Изображение' )->set_value_type( 'url' ),
            Field::make( 'text', 'alt', 'Alt' )->set_width(50),
            Field::make( 'text', 'caption', 'Подпись' )->set_width(50),
            Field::make( 'select', 'align', 'Выравнивание' )
                ->set_options( array( ''=>'По ширине','left'=>'Слева','right'=>'Справа','center'=>'По центру' ) ),
        ) )

        ->set_header_template(
            '<% if (entry._type == "heading") { %>🅷 Заголовок: <%- text %>'
          . '<% } else if (entry._type == "text") { %>✍️ Текстовый блок'
          . '<% } else if (entry._type == "shortcode") { %>🔗 Шорткод: <%- code %>'
          . '<% } else if (entry._type == "image") { %>🖼 Картинка'
          . '<% } else { %>Блок<% } %>'
        )
) )

		;

    //поля для отзывов
    Container::make( 'post_meta', 'Настройки' )
        ->where( 'post_type', '=', 'reviews' )
        ->add_fields( array(
            Field::make( 'text', 'rb_reviews_title', 'Заголовок' ),
            Field::make( 'text', 'rb_review_date', 'Дата отзыва' ),
            
    ));

    //поля для страницы О нас
Container::make( 'post_meta', 'О нас' )
->where( 'post_type', '=', 'page' )
->where( 'post_template', '=', 'template-page/page-about.php' )
->add_tab( __( 'О центре' ), array(
    Field::make( 'text', 'rb_about_title', 'Имя' ),
    Field::make( 'rich_text', 'rb_about_text', 'Текст' )
        ->set_width(70),
    Field::make( 'image', 'rb_about_image', 'Фото' )
        ->set_width(30),
) )
->add_tab( __( 'Руководство' ), array(
    Field::make( 'complex', 'rb_about_managers_list', 'Укажите другие блоки с отзывами' )
        ->set_layout( 'tabbed-vertical' )
        ->add_fields( 'info', array(
            Field::make( 'textarea', 'title', 'Имя' )->set_width(35),
            Field::make( 'textarea', 'occup', 'Должность' )->set_width(35),
            Field::make( 'image', 'image', 'Фото' )->set_width(30),
            Field::make( 'textarea', 'cite', 'Цитата' ),
            Field::make( 'rich_text', 'desc', 'Описание' ),
        ) )
        ->set_header_template( '
            <% if (title) { %><%- title %><% } %>
        ' ),
) )
->add_tab( __( 'Отзывы' ), array(
    Field::make( 'text', 'rb_about_reviews_title', 'Заголовок' ),
    Field::make( 'complex', 'rb_about_reviews_more', 'Укажите другие блоки с отзывами' )
        ->set_layout( 'tabbed-vertical' )
        ->add_fields( 'info', array(
            Field::make( 'text', 'title', 'Название' )->set_width(50),
            Field::make( 'image', 'image', 'Изображение' )->set_width(50),
            Field::make( 'text', 'link', 'Ссылка' ),
        ) )
        ->set_header_template( '
            <% if (title) { %><%- title %><% } %>
        ' ),
) );
    //поля для страницы Институт здоровой семьи
    Container::make( 'post_meta', 'Инфоблок' )
    ->where( 'post_type', '=', 'page' )
    ->where( 'post_template', '=', 'template-page/page-school.php' )
    ->add_fields( array(
            Field::make( 'image', 'image', 'Изображение' )
                            ->set_width(50),
            Field::make( 'image', 'image_mob', 'Изображение мобильное' )
                            ->set_width(50),
            Field::make( 'complex', 'rb_speakers', 'Спикеры' )
                    ->set_layout( 'tabbed-vertical' )
                    ->add_fields( 'info', array(
                        Field::make( 'text', 'title', 'Имя' ),
                        Field::make( 'text', 'link', 'Ссылка' ),
                        Field::make( 'image', 'image', 'Фото' ),
                    ) )
                    ->set_header_template( '
                          <% if (title) { %>
                              <%- title %>
                          <% } %>
                  ' ),
            Field::make( 'complex', 'rb_reviews', 'Отзывы' )
                    ->set_layout( 'tabbed-vertical' )
                    ->add_fields( 'info', array(
                        Field::make( 'text', 'title', 'Имя' ),
                        Field::make( 'rich_text', 'desc', 'Текст отзыва' ),
                    ) )
                    ->set_header_template( '
                          <% if (title) { %>
                              <%- title %>
                          <% } %>
                  ' ),

    ));
    
    
    //поля для услуг
    Container::make( 'post_meta', 'Настройки' )
        ->where( 'post_type', '=', 'service' )
        ->add_tab( __( 'Основные' ), array(
            Field::make( 'checkbox', 'rb_services_checkbox', 'Заменить кнопку заказать на перейти?' )
                ->set_width(50),
            Field::make( 'checkbox', 'rb_services_price_hide', 'Скрыть цену на карточке услуги?' )
                ->set_width(50),
            Field::make( 'rich_text', 'rb_services_desc', 'Описание' ),
            Field::make( 'rich_text', 'rb_services_brief_desc', 'Краткое описание в карточке' ),
            Field::make( 'text', 'rb_services_price', 'Цена' ),
            Field::make( 'text', 'rb_services_code', 'Код' ),
            Field::make( 'text', 'rb_services_prev_price', 'Предыдущая цена' ),
        ) )
        ->add_tab( __( 'SEO' ), array(
            Field::make( 'text', 'rb_services_seo_ym', 'YM код' ),
        ) )


->add_tab( __( 'Список услуг' ), array(
    Field::make( 'text', 'rb_services_block_title', 'Заголовок блока услуг' )
        ->set_help_text('Укажите заголовок для блока услуг. Если оставить пустым, будет использован "Все услуги направления"')
        ->set_default_value('Все услуги направления'),
        
    Field::make( 'association', 'rb_doc_services_list', 'Выберите услуги' )
        ->set_types( array(
            array(
                'type' => 'post',
                'post_type' => 'service',
            ),
        ) ),
    
    Field::make( 'checkbox', 'rb_services_show_as_list', 'Показывать услуги списком' )
        ->set_help_text('При активации услуги будут отображаться списком вместо карусели')
        ->set_default_value(false),
) )
        ->add_tab( __( 'Кому' ), array(
            Field::make( 'rich_text', 'rb_services_for_whom_text', 'Кому подойдёт услуга - описание' ),
            Field::make( 'complex', 'rb_services_for_whom', 'Кому подойдёт услуга ' )
                    ->set_layout( 'tabbed-vertical' )
                    ->add_fields( 'info', array(
                        Field::make( 'textarea', 'text', 'Описание' ),
                    ) ),
        ) )
        ->add_tab( __( 'Мега Инфоблок' ), array(
            Field::make( 'complex', 'rb_services_main', 'Элемент мегаблока' )
                    ->set_layout( 'tabbed-vertical' )
                    ->add_fields( 'info', array(
                        Field::make( 'text', 'title', 'Заголовок' ),
                        Field::make( 'rich_text', 'desc', 'Описание' ),
						Field::make( 'association', 'services', 'Выберите услуги' )
							->set_types( array(
								array(
									  'type'        => 'post',
									  'post_type'   => 'center-services',
								),

							) ),
                    ) )
                    ->set_header_template( '
                          <% if (title) { %>
                              <%- title %>
                          <% } %>
                  ' ),
        ) )
        ->add_tab( __( 'Инфоблок' ), array(
            Field::make( 'text', 'rb_services_infoblock_title', 'Заголовок' ),
            Field::make( 'complex', 'rb_services_infoblock_process', 'Описание процесса' )
                    ->set_layout( 'tabbed-vertical' )
                    ->add_fields( 'info', array(
                        Field::make( 'text', 'title', 'Заголовок' ),
                        Field::make( 'textarea', 'desc', 'Описание' ),
                    ) )
                    ->set_header_template( '
                          <% if (title) { %>
                              <%- title %>
                          <% } %>
                  ' ),
        ) )
        ->add_tab( __( 'Результат' ), array(
            Field::make( 'text', 'rb_services_result_title', 'Результат - заголовок' )
                    ->set_default_value('Ожидаемые результаты'),
            Field::make( 'complex', 'rb_services_result', 'Ожидаемые результаты' )
                    ->set_layout( 'tabbed-vertical' )
                    ->add_fields( 'info', array(
                        Field::make( 'text', 'title', 'Заголовок' ),
                        Field::make( 'textarea', 'desc', 'Описание' ),
                    ) )
                    ->set_header_template( '
                          <% if (title) { %>
                              <%- title %>
                          <% } %>
                  ' ),
        ) )
        ->add_tab( __( 'Услуги' ), array(
            Field::make( 'text', 'rb_services_list_title', 'Список услуг - заголовок' ),
            Field::make( 'textarea', 'rb_services_list_text', 'Список услуг - описание' ),
            Field::make( 'complex', 'rb_services_list', 'Разбивка на подуслуги' )
                    ->set_layout( 'tabbed-vertical' )
                    ->add_fields( 'info', array(
                        Field::make( 'text', 'title', 'Заголовок' ),
                        Field::make( 'rich_text', 'desc', 'Описание 1' ),
                        Field::make( 'rich_text', 'desc2', 'Описание 2' ),
                        Field::make( 'rich_text', 'desc3', 'Описание 3' ),
                    ) )
                    ->set_header_template( '
                          <% if (title) { %>
                              <%- title %>
                          <% } %>
                  ' ),
        ) )
		->add_tab( __( 'Популярные услуги' ), array(
    Field::make( 'association', 'rb_services_popular_service', 'Выберите популярные услуги' )
        ->set_types( array(
            array(
                'type'      => 'post',
                'post_type' => 'service',
            ),
        ) )
) )
        ->add_tab( __( 'Процесс' ), array(
            Field::make( 'text', 'rb_services_process_title', 'Процесс - заголовок' ),
            Field::make( 'complex', 'rb_services_process', 'Описание процесса' )
                    ->set_layout( 'tabbed-vertical' )
                    ->add_fields( 'info', array(
                        Field::make( 'text', 'title', 'Заголовок' ),
                        Field::make( 'rich_text', 'desc', 'Описание' ),
                    ) )
                    ->set_header_template( '
                          <% if (title) { %>
                              <%- title %>
                          <% } %>
                  ' ),
        ) )
        // ->add_tab( __( 'Методика проведения' ), array(
        //     Field::make( 'text', 'rb_services_methodic_subtitle', 'Подзаголовок' ),
        //     Field::make( 'complex', 'rb_services_methodic', 'Список методик' )
        //             ->set_layout( 'tabbed-vertical' )
        //             ->add_fields( 'info', array(
        //                 Field::make( 'text', 'title', 'Заголовок' ),
        //                 Field::make( 'rich_text', 'desc', 'Описание' ),
        //             ) )
        //             ->set_header_template( '
        //                   <% if (title) { %>
        //                       <%- title %>
        //                   <% } %>
        //           ' ),
        // ) )
        ->add_tab( __( 'Риски и последствия' ), array(
            Field::make( 'complex', 'rb_services_risks', 'Список рисков' )
                    ->set_layout( 'tabbed-vertical' )
                    ->add_fields( 'info', array(
                        Field::make( 'textarea', 'desc', 'Описание' ),
                    ) ),
        ) )
        ->add_tab( __( 'Врачи' ), array(
            Field::make( 'association', 'rb_services_doctors', 'Выберите врачей' )
                    ->set_types( array(
                          array(
                                  'type'        => 'post',
                                  'post_type'   => 'doctors',
                          ),

                    ) ),
        ) )
        ->add_tab( __( 'Противопоказания' ), array(
            Field::make( 'text', 'rb_services_protive_title', 'Противопоказания - заголовок' ),
            Field::make( 'complex', 'rb_services_protive', 'Противопоказания' )
                    ->set_layout( 'tabbed-vertical' )
                    ->add_fields( 'info', array(
                        Field::make( 'text', 'title', 'Заголовок' ),
                        Field::make( 'textarea', 'desc', 'Описание' ),
                    ) )
                    ->set_header_template( '
                          <% if (title) { %>
                              <%- title %>
                          <% } %>
                  ' ),
        ) )
		        ->add_tab( __( 'Что это такое?' ), array(
            Field::make( 'text', 'rb_services_whou_is_title', 'Что это такое?' ),
            Field::make( 'complex', 'rb_services_whou_is', 'Что это такое?' )
                    ->set_layout( 'tabbed-vertical' )
                    ->add_fields( 'info', array(
                        Field::make( 'text', 'title', 'Заголовок' ),
                        Field::make( 'rich_text', 'desc', 'Описание' ),
                    ) )
                    ->set_header_template( '
                          <% if (title) { %>
                              <%- title %>
                          <% } %>
                  ' ),
        ) )
        ->add_tab( __( 'Документы' ), array(
            Field::make( 'text', 'rb_services_documents_title', 'Документы - заголовок' ),
            Field::make( 'textarea', 'rb_services_documents_text', 'Документы - описание' ),
            Field::make( 'complex', 'rb_services_documents', 'Документы' )
                    ->set_layout( 'tabbed-vertical' )
                    ->add_fields( 'info', array(
                        Field::make( 'text', 'title', 'Заголовок' ),
                        Field::make( 'textarea', 'desc', 'Описание' ),
                        Field::make( 'checkbox', 'diff', 'Обязательно?' )
                    ) )
                    ->set_header_template( '
                          <% if (title) { %>
                              <%- title %>
                          <% } %>
                  ' ),
        ))
        ->add_tab( __( 'Нижний инфоблок' ), array(
            Field::make( 'text', 'rb_services_bot_title', 'Документы - заголовок' ),
            Field::make( 'rich_text', 'rb_services_bot_text', 'Описание' ),
        ) )
        ->add_tab( __( 'Связанные услуги' ), array(
            Field::make( 'association', 'rb_services_related', 'Выберите связанные услуги' )
                    ->set_types( array(
                          array(
                                  'type'        => 'post',
                                  'post_type'   => 'service',
                          ),

                    ) ),

    ));

    //поля для направлений
    Container::make( 'term_meta', 'Настройки' )
        ->where( 'term_taxonomy', '=', 'services' )
        ->add_fields( array(
            Field::make( 'image', 'rb_service_image', 'Фотография блока' )
                    ->set_width(50),
            Field::make( 'image', 'rb_service_image_inner', 'Фотография на странице' )
                    ->set_width(50),
            Field::make( 'image', 'rb_service_image_mob', 'Фотография мобильная' )
                    ->set_width(50),
            Field::make( 'image', 'rb_service_inner_img', 'Фотография большая внутренняя' )
                    ->set_width(50),
            Field::make( 'text', 'rb_services_other_link', 'Ссылка на другую страницу' ),
            Field::make( 'file', 'rb_service_file', 'Видео' )
                    ->set_type( 'video' )
                    ->set_width(50),
            Field::make( 'text', 'rb_service_mainp_text', 'Текст для главной страницы' )
                    ->set_width(50),
            Field::make( 'checkbox', 'rb_different', 'Другой вид' )
                    ->set_help_text('Выберите, если хотите сделать нестандартный вид страницы')
                    ->set_width(50),
            Field::make( 'checkbox', 'rb_new', 'Новый дизайн' )
                    ->set_help_text('Выберите, если хотите сделать новый дизайн')
                    ->set_width(50),
            Field::make( 'complex', 'rb_new_text_blocks', 'Инфоблоки' )
                    ->set_layout( 'tabbed-vertical' )
                    ->add_fields( 'info', array(
                        Field::make( 'image', 'image', 'Фотография' )
                                ->set_width(50),
                        Field::make( 'textarea', 'title', 'Заголовок блока' )
                                ->set_width(50),
                        Field::make( 'textarea', 'subtitle', 'Подзаголовок блока' ),
                        Field::make( 'rich_text', 'text', 'Инфоблок' ),
                        Field::make( 'rich_text', 'left', 'Инфоблок слева' ),
                        Field::make( 'rich_text', 'right', 'Инфоблок справа' ),
						
                        
                    ) )
                    ->set_conditional_logic( array(
                        array(
                            'field' => 'rb_new',
                            'value' => true,
                        )
                    ) )
                    ->set_header_template( '
                          <% if (title) { %>
                              <%- title %>
                          <% } else {  %>
                            <%- item %>
                          <% }  %>
                  ' ),
            Field::make( 'image', 'rb_service_bg', 'Фон, если хотите свой' )
                    ->set_conditional_logic( array(
                        array(
                            'field' => 'rb_different',
                            'value' => true,
                        )
                    ) ),
            Field::make( 'rich_text', 'rb_different_text', 'Инфоблок' )
                     ->set_conditional_logic( array(
                        array(
                            'field' => 'rb_different',
                            'value' => true,
                        )
                    ) ),
            Field::make( 'text', 'rb_different_clinic', 'Название' )
                    ->set_conditional_logic( array(
                        array(
                            'field' => 'rb_different',
                            'value' => true,
                        )
                    ) ),
            Field::make( 'text', 'rb_different_clinic_desc', 'Надпись под названием ' )
                    ->set_conditional_logic( array(
                        array(
                            'field' => 'rb_different',
                            'value' => true,
                        )
                    ) ),
            Field::make( 'text', 'rb_different_link', 'Ссылка на другую страницу' )
                    ->set_conditional_logic( array(
                        array(
                            'field' => 'rb_different',
                            'value' => true,
                        )
                    ) ),
            Field::make( 'text', 'rb_different_phone', 'Телефон' )
                    ->set_conditional_logic( array(
                        array(
                            'field' => 'rb_different',
                            'value' => true,
                        )
                    ) ),
            Field::make( 'rich_text', 'rb_service_desc', 'Описание услуги' )
                    ->set_conditional_logic( array(
                        array(
                            'field' => 'rb_different',
                            'value' => false,
                        )
                    ) ),
			Field::make( 'text', 'rb_service_price', 'Цена' )
    ->set_help_text('Текущая цена, например: 3500')
    ->set_attribute( 'inputmode', 'numeric' )
    ->set_width(33)
    ->set_conditional_logic( array(
        array(
            'field' => 'rb_different',
            'value' => false,
        )
    ) ),

Field::make( 'text', 'rb_service_code', 'Код' )
    ->set_help_text('Артикул / внутренний код')
    ->set_width(34)
    ->set_conditional_logic( array(
        array(
            'field' => 'rb_different',
            'value' => false,
        )
    ) ),

Field::make( 'text', 'rb_service_old_price', 'Предыдущая цена' )
    ->set_help_text('Старая (зачёркнутая) цена')
    ->set_attribute( 'inputmode', 'numeric' )
    ->set_width(33)
    ->set_conditional_logic( array(
        array(
            'field' => 'rb_different',
            'value' => false,
        )
    ) ),
            Field::make( 'text', 'rb_service_info_title', 'Заголовок инфоблока' )
                    ->set_conditional_logic( array(
                        array(
                            'field' => 'rb_different',
                            'value' => false,
                        )
                    ) ),
            Field::make( 'rich_text', 'rb_service_info_desc', 'Описание инфоблока' )
                    ->set_conditional_logic( array(
                        array(
                            'field' => 'rb_different',
                            'value' => false,
                        )
                    ) ),
            Field::make( 'complex', 'rb_services_infoblocks', 'Добавьте инфоблоки' )
                    ->set_layout( 'tabbed-vertical' )
                    ->add_fields( 'info', array(
                        Field::make( 'checkbox', 'wide', 'Сделать блок на всю ширину?' ),
                        Field::make( 'text', 'title', 'Название' ),
                        Field::make( 'rich_text', 'desc', 'Описание' ),
                    ) )
                    ->set_conditional_logic( array(
                        array(
                            'field' => 'rb_different',
                            'value' => false,
                        )
                    ) )
                    ->set_header_template( '
                          <% if (title) { %>
                              <%- title %>
                          <% } %>
                  ' ),


            Field::make( 'checkbox', 'rb_services_second_info_checkbox', 'Добавить второй инфоблок?' )
                    ->set_conditional_logic( array(
                        array(
                            'field' => 'rb_different',
                            'value' => false,
                        )
                    ) ), 
            Field::make( 'rich_text', 'rb_services_second_info_left', 'Описание инфоблока2 слева' )
                    ->set_conditional_logic( array(
                        array(
                            'field' => 'rb_different',
                            'value' => false,
                        )
                    ) )
                    ->set_conditional_logic( array(
                        array(
                            'field' => 'rb_services_second_info_checkbox',
                            'value' => true,
                        )
                    ) ), 
            Field::make( 'rich_text', 'rb_services_second_info_right', 'Описание инфоблока2 справа' )
                    ->set_conditional_logic( array(
                        array(
                            'field' => 'rb_services_second_info_checkbox',
                            'value' => true,
                        )
                    ) ),
            Field::make( 'association', 'rb_services_popular', 'Выберите популярные услуги' )
                    ->set_types( array(
                        array(
                              'type'        => 'post',
                              'post_type'   => 'service',
                        ),

                    ) )
                    ->set_conditional_logic( array(
                        array(
                            'field' => 'rb_different',
                            'value' => false,
                        )
                    ) ),
            Field::make( 'checkbox', 'rb_services_equip_show', 'Выводить информацию по тренажёрам?' )
                    ->set_conditional_logic( array(
                        array(
                            'field' => 'rb_different',
                            'value' => false,
                        )
                    ) ),       
            Field::make( 'complex', 'rb_services_equip', 'Укажите оборудование' )
                    ->set_layout( 'tabbed-vertical' )
                    ->add_fields( 'info', array(
                        Field::make( 'text', 'title', 'Название' ),
                        Field::make( 'textarea', 'desc', 'Описание' ),
                        Field::make( 'complex', 'items', 'Оборудование' )
                                ->set_layout( 'tabbed-vertical' )
                                ->add_fields( 'info', array(
                                    Field::make( 'text', 'title', 'Название' )
                                        ->set_width(50),
                                    Field::make( 'image', 'image', 'Изображение' )
                                        ->set_width(50),
                                    Field::make( 'rich_text', 'desc', 'Описание' ),
                                ) )
                                ->set_header_template( '
                                      <% if (title) { %>
                                          <%- title %>
                                      <% } %>
                              ' ),
                    ) )
                    ->set_conditional_logic( array(
                        array(
                            'field' => 'rb_different',
                            'value' => false,
                        )
                    ) )
                    ->set_header_template( '
                          <% if (title) { %>
                              <%- title %>
                          <% } %>
                  ' ),

            Field::make( 'association', 'rb_services_spec', 'Выберите специалистов' )
                    ->set_types( array(
                        array(
                              'type'        => 'post',
                              'post_type'   => 'doctors',
                        ),

                    ) )
                    ->set_conditional_logic( array(
                        array(
                            'field' => 'rb_different',
                            'value' => false,
                        )
                    ) ),
            Field::make( 'checkbox', 'rb_add', 'Добавить блоки с услугами?' )
                    ->set_help_text('Выберите, если хотите добавить блок с услугами')
                    ->set_conditional_logic( array(
                        array(
                            'field' => 'rb_different',
                            'value' => false,
                        )
                    ) ),
            Field::make( 'checkbox', 'rb_all_services_hide', 'Скрыть все услуги' )
                    ->set_conditional_logic( array(
                        array(
                            'field' => 'rb_add',
                            'value' => true,
                        )
                    ) ),
            Field::make( 'complex', 'rb_services_add', 'Укажите блок c услугами' )
                    ->set_layout( 'tabbed-vertical' )
                    ->add_fields( 'info', array(
                        Field::make( 'text', 'title', 'Название' ),
                        Field::make( 'rich_text', 'desc', 'Описание' ),
                        Field::make( 'checkbox', 'rb_services_add_diff2', 'Другой вид отображения услуг' ),
						Field::make( 'checkbox', 'rb_services_add_top', 'Позиция топ' ),
                        Field::make( 'association', 'services', 'Выберите услуги' )
                            ->set_types( array(
                                array(
                                      'type'        => 'post',
                                      'post_type'   => 'service',
                                ),

                            ) ),
                    ) )
                    ->set_conditional_logic( array(
                        array(
                            'field' => 'rb_add',
                            'value' => true,
                        )
                    ) ),
                       Field::make( 'text', 'rb_services_whou_is_title', 'Заголовок текстового блока' ),
            Field::make( 'complex', 'rb_services_whou_is', 'Содержимое текстового блока' )
                    ->set_layout( 'tabbed-vertical' )
                    ->add_fields( 'info', array(
                        Field::make( 'text', 'title', 'Заголовок' ),
                        Field::make( 'textarea', 'desc', 'Описание' ),
                    ) )
                    ->set_header_template( '<% if (title) { %><%- title %><% } %>' ),
            Field::make( 'text', 'rb_services_seo_ym', 'Название метки для кода YM' ),
			 /* ===================== КОНСТРУКТОР СО СЛОТАМИ ===================== */
        Field::make( 'complex', 'rb_services_term_builder', 'Блоки страницы (Конструктор)' )
            ->set_layout( 'tabbed-vertical' )

            // Заголовок
            ->add_fields( 'heading', array(
                Field::make( 'select', 'pos', 'Позиция на странице' )
                    ->set_options(array(
                        'after_title'        => 'Сразу после H1',
                        'after_intro'        => 'После описания',
                        'before_services'    => 'Перед блоком «Услуги»',
                        'after_services'     => 'После блока «Услуги»',
                        'before_doctors'     => 'Перед блоком «Врачи»',
                        'after_doctors'      => 'После блока «Врачи»',
                        'before_footer_info' => 'Перед нижним инфоблоком',
                        'custom_1'           => 'Пользовательский слот 1',
                        'custom_2'           => 'Пользовательский слот 2',
                    ))
                    ->set_default_value('after_intro')
                    ->set_width(40),
                Field::make( 'select', 'tag', 'Уровень заголовка' )
                    ->set_options(array('h2'=>'H2','h3'=>'H3','h4'=>'H4'))
                    ->set_default_value('h2')->set_width(20),
                Field::make( 'text', 'text', 'Текст заголовка' )->set_width(40),
            ))

            // Текст/HTML
            ->add_fields( 'text', array(
                Field::make( 'select', 'pos', 'Позиция на странице' )
                    ->set_options(array(
                        'after_title'        => 'Сразу после H1',
                        'after_intro'        => 'После описания',
                        'before_services'    => 'Перед блоком «Услуги»',
                        'after_services'     => 'После блока «Услуги»',
                        'before_doctors'     => 'Перед блоком «Врачи»',
                        'after_doctors'      => 'После блока «Врачи»',
                        'before_footer_info' => 'Перед нижним инфоблоком',
                        'custom_1'           => 'Пользовательский слот 1',
                        'custom_2'           => 'Пользовательский слот 2',
                    ))
                    ->set_default_value('after_intro'),
                Field::make( 'rich_text', 'content', 'Текст/HTML' ),
            ))

            // Шорткод
            ->add_fields( 'shortcode', array(
                Field::make( 'select', 'pos', 'Позиция на странице' )
                    ->set_options(array(
                        'after_title'        => 'Сразу после H1',
                        'after_intro'        => 'После описания',
                        'before_services'    => 'Перед блоком «Услуги»',
                        'after_services'     => 'После блока «Услуги»',
                        'before_doctors'     => 'Перед блоком «Врачи»',
                        'after_doctors'      => 'После блока «Врачи»',
                        'before_footer_info' => 'Перед нижним инфоблоком',
                        'custom_1'           => 'Пользовательский слот 1',
                        'custom_2'           => 'Пользовательский слот 2',
                    ))
                    ->set_default_value('after_intro'),
                Field::make( 'text', 'code', 'Шорткод' )
                    ->set_help_text('Например: [contact-form-7 id="123"] или любой кастомный'),
            ))

            // Картинка
            ->add_fields( 'image', array(
                Field::make( 'select', 'pos', 'Позиция на странице' )
                    ->set_options(array(
                        'after_title'        => 'Сразу после H1',
                        'after_intro'        => 'После описания',
                        'before_services'    => 'Перед блоком «Услуги»',
                        'after_services'     => 'После блока «Услуги»',
                        'before_doctors'     => 'Перед блоком «Врачи»',
                        'after_doctors'      => 'После блока «Врачи»',
                        'before_footer_info' => 'Перед нижним инфоблоком',
                        'custom_1'           => 'Пользовательский слот 1',
                        'custom_2'           => 'Пользовательский слот 2',
                    ))
                    ->set_default_value('after_intro'),
                Field::make( 'image', 'image', 'Изображение' )->set_value_type( 'url' ),
                Field::make( 'text', 'alt', 'Alt' )->set_width(50),
                Field::make( 'text', 'caption', 'Подпись' )->set_width(50),
                Field::make( 'select', 'align', 'Выравнивание' )
                    ->set_options(array(
                        ''       => 'По ширине',
                        'left'   => 'Слева',
                        'right'  => 'Справа',
                        'center' => 'По центру',
                    )),
            ))
            ->set_header_template('
                <% if (entry._type == "heading") { %>
                    🅷 Заголовок: <%- text %>
                <% } else if (entry._type == "text") { %>
                    ✍️ Текстовый блок
                <% } else if (entry._type == "shortcode") { %>
                    🔗 Шорткод: <%- code %>
                <% } else if (entry._type == "image") { %>
                    🖼 Картинка
                <% } else { %>
                    Блок
                <% } %>
            ' ),
        /* =================== /КОНСТРУКТОР =================== */
    ));


    //поля для врачей
    Container::make( 'post_meta', 'Настройки' )
        ->where( 'post_type', '=', 'doctors' )
        ->add_tab( __( 'Общая информация' ), array(
            Field::make( 'text', 'rb_doc_surname', 'Фамилия' )
                    ->set_width(33),
            Field::make( 'text', 'rb_doc_name', 'Имя' )
                    ->set_width(33),
            Field::make( 'text', 'rb_doc_patronimyc', 'Отчество' )
                    ->set_width(33),
            Field::make( 'checkbox', 'rb_doc_checkbox', 'Доктор?' )
                    ->set_option_value( 'yes' )
                    ->set_help_text('Выберите, если это доктор, а не средний медперсонал')
                    ->set_width(50),
            Field::make( 'date', 'rb_doc_stage', 'Начало стажа' )
                    ->set_width(50)
                    ->set_storage_format( 'd.m.Y' ),
            Field::make( 'text', 'rb_doc_occup', 'Должность' ),
            Field::make( 'text', 'rb_doc_id_lk', 'ID для онлайн записи' ),
        ) )
        ->add_tab( __( 'Услуги' ), array(
            Field::make( 'association', 'rb_doc_services_list', 'Выберите услуги' )
                    ->set_types( array(
                        array(
                            'type'        => 'post',
                            'post_type'   => 'center-services',
                        ),

                    ) ),
            Field::make( 'complex', 'rb_doc_serv_compl', 'Блоки с услугами врача' )
                    ->set_layout( 'tabbed-vertical' )
                    ->add_fields( 'info', array(
                        Field::make( 'text', 'title', 'Название услуг' ),
                        Field::make( 'association', 'list', 'Выберите услуги' )
                                ->set_types( array(
                                    array(
                                        'type'        => 'post',
                                        'post_type'   => 'center-services',
                                    ),

                                ) ),
                    ) )
                    ->set_header_template( '
                        <% if (title) { %>
                            <%- title %>
                        <% } %>
                ' ),
        ) )
        ->add_tab( __( 'Образование' ), array(
            Field::make( 'rich_text', 'rb_doc_education_1', 'Сведения из документа об образовании' ),
            Field::make( 'rich_text', 'rb_doc_education_2', 'Сведения о прохождении интернатуры / ординатуры' ),
            Field::make( 'rich_text', 'rb_doc_education_3', 'Сведения из сертификата специалиста /диплома о переподготовке /усовершенствования' ),
            Field::make( 'rich_text', 'rb_doc_education_4', 'Сведения о достижениях' ),
        ) )
        ->add_tab( __( 'Инфоблоки' ), array(
            Field::make( 'rich_text', 'rb_doc_diagnosis', 'Диагнозы и состояния, при которых стоит обратиться к специалисту' ),
            Field::make( 'rich_text', 'rb_doc_infoblock', 'Специализация' ),
            Field::make( 'complex', 'rb_doc_additional', 'Блоки с информацией' )
                    ->set_layout( 'tabbed-vertical' )
                    ->add_fields( 'info', array(
                        Field::make( 'text', 'title', 'Заголовок' ),
                        Field::make( 'rich_text', 'text', 'Описание' ),
                    ) )
                    ->set_header_template( '
                        <% if (title) { %>
                            <%- title %>
                        <% } %>
                ' ),
        ) )
        ->add_tab( __( 'Отзывы' ), array(
            Field::make( 'checkbox', 'rb_doc_review_show', 'Выводить блок с отзывами продокторов?' )
            ->set_option_value( 'yes' ),
            Field::make( 'text', 'rb_doc_review_btn_link', 'Ссылка на отзывы на продокторов' )
                    ->set_conditional_logic( array(
                        array(
                            'field' => 'rb_doc_review_show',
                            'value' => true,
                        )
                    ) ),
            Field::make( 'textarea', 'rb_doc_review_code', 'Код виджета с продоктор' )
                    ->set_conditional_logic( array(
                        array(
                            'field' => 'rb_doc_review_show',
                            'value' => true,
                        )
                    ) ),
            Field::make( 'complex', 'rb_doc_review_items', 'Блоки с отзывами' )
                    ->set_layout( 'tabbed-vertical' )
                    ->add_fields( 'info', array(
                        Field::make( 'text', 'title', 'Заголовок' ),
                        Field::make( 'rich_text', 'desc', 'Описание' ),
                        Field::make( 'text', 'name', 'Имя' )
                            ->set_width(50),
                        Field::make( 'text', 'date', 'Дата' )
                            ->set_width(50),
                    ) )
                    ->set_header_template( '
                        <% if (name) { %>
                            <%- name %>
                        <% } %>
                ' ),
            ) )
                    ->add_tab( __( 'Cтатьи' ), array(
                        Field::make( 'association', 'rb_doctors_related_articles', 'Добавь статью' )
                        ->set_types( array(
                            array(
                                'type'      => 'post',
                                'post_type' => 'post',
                                ),
                                ) )
                                ->set_max( 8 )
                                ->set_help_text( 'Если выбрать статьи здесь, в блоке “Похожие статьи” будут показаны именно они. Если поле пустое — блок заполнится автоматически по рубрикам.' ),
                                ))
                    ->add_tab( __( 'Видеовизитка' ), array(
                        Field::make('file', 'rb_doctors_video', 'Видео')
                        ->set_type(['video']),
                ));

    //поля для услуг центра
Container::make( 'post_meta', 'Настройки' )
    ->where( 'post_type', '=', 'center-services' )
    ->add_fields( array(
        Field::make( 'text', 'rb_serv_price', 'Стоимость' ),
        Field::make( 'text', 'rb_serv_code', 'Кодировка' ),
        Field::make( 'text', 'rb_serv_min_age', 'Мин возраст' )
                ->set_width(50),
        Field::make( 'text', 'rb_serv_max_age', 'Макс возраст' )
                ->set_width(50),
        Field::make( 'text', 'rb_serv_key_id', 'ID для парсера' ),
        Field::make( 'text', 'rb_serv_cat', 'Направление' ),
        Field::make( 'text', 'rb_serv_serv', 'Услуга' ),
        Field::make( 'text', 'rb_serv_subserv', 'Услуга 2 уровень' ),
        Field::make( 'text', 'rb_serv_update_date', 'Дата обновления' ),
        
        // Привязка к направлениям
        Field::make( 'association', 'rb_serv_directions', 'Выберите направления' )
                ->set_types( array(
                    array(
                          'type'        => 'term',
                          'taxonomy'    => 'services',
                    ),
                ) )
                ->set_help_text('Выберите одно или несколько направлений для данной услуги'),
        
        // Привязка к услугам
        Field::make( 'association', 'rb_serv_services', 'Выберите услуги' )
                ->set_types( array(
                    array(
                          'type'        => 'post',
                          'post_type'   => 'service',
                    ),
                ) )
                ->set_help_text('Выберите одну или несколько услуг для данной услуги центра'),
));

    //поля для программ
    Container::make( 'post_meta', 'Настройки' )
        ->where( 'post_type', '=', 'programms' )
        ->add_fields( array(
            Field::make( 'text', 'rb_link', 'Ссылка' )
                    ->set_width(50),
            Field::make( 'text', 'rb_price', 'Цена' )
                    ->set_width(50),
            Field::make( 'checkbox', 'rb_price_from_hide', 'Показать "от"?' ),
            Field::make( 'rich_text', 'rb_price_short_desc', 'Короткое описание' ),
            Field::make( 'text', 'rb_for_whom_title', 'Для кого заголовок' ),
            Field::make( 'rich_text', 'rb_for_whom', 'Для кого' ),
            Field::make( 'text', 'rb_effect_title', 'Эффект заголовок' ),
            Field::make( 'rich_text', 'rb_effect', 'Эффект' ),
            Field::make( 'text', 'rb_compound_title', 'Состав заголовок' ),
            Field::make( 'rich_text', 'rb_compound', 'Состав' ),
			 // === КОНСТРУКТОР ШОРТКОДОВ ===
        Field::make( 'complex', 'rb_programs_builder', 'Конструктор (шорткоды)' )
            ->set_layout( 'tabbed-vertical' )
            ->add_fields( 'shortcode', array(
                Field::make( 'select', 'pos', 'Позиция на странице' )
                    ->set_options( array(
                        'after_title'       => 'Сразу после H1',
                        'after_intro'       => 'После короткого описания',
                        'before_for_whom'   => 'Перед блоком «Для кого»',
                        'after_for_whom'    => 'После блока «Для кого»',
                        'before_effect'     => 'Перед блоком «Эффект»',
                        'after_effect'      => 'После блока «Эффект»',
                        'before_compound'   => 'Перед блоком «Состав»',
                        'after_compound'    => 'После блока «Состав»',
                        'before_price'      => 'Перед ценой/кнопкой',
                        'after_price'       => 'После цены/кнопки',
                        'custom_1'          => 'Пользовательский слот 1',
                        'custom_2'          => 'Пользовательский слот 2',
                    ) )
                    ->set_default_value( 'after_intro' ),
                Field::make( 'text', 'code', 'Шорткод' )
                    ->set_help_text( 'Например: [contact-form-7 id="123"]' ),
            ) )
            ->set_header_template( '
                <% if (entry._type == "shortcode") { %>
                    🔗 Шорткод: <%- code %>
                <% } %>
            ' ),
        // === /КОНЕЦ КОНСТРУКТОРА ШОРТКОДОВ ===
    ));

     //поля для страницы Контакты
    Container::make( 'post_meta', 'Настройки страницы' )
    ->where( 'post_type', '=', 'page' )
    ->where( 'post_template', '=', 'template-page/page-contacts.php' )
    ->add_fields(  array(
            Field::make( 'text', 'rb_phone', 'Телефон' )
                    ->set_width(50),
            Field::make( 'text', 'rb_email', 'E-mail' )
                    ->set_width(50), 
            Field::make( 'text', 'rb_time_1', 'Время работы пн-пт' )
                    ->set_width(33), 
            Field::make( 'text', 'rb_time_2', 'Время работы сб' )
                    ->set_width(33), 
            Field::make( 'text', 'rb_time_3', 'Время работы вс' )
                    ->set_width(33), 
            Field::make( 'textarea', 'rb_map', 'Код с картой' ),
            Field::make( 'association', 'rb_administrators', 'Выберите админитративный состав' )
                    ->set_types( array(
                        array(
                              'type'        => 'post',
                              'post_type'   => 'doctors',
                        ),

                    ) ),

    ) );

     //поля для страницы Документы
    Container::make( 'post_meta', 'Настройки страницы' )
    ->where( 'post_type', '=', 'page' )
    ->where( 'post_template', '=', 'template-page/page-documents.php' )
    ->add_fields(  array(
            Field::make( 'complex', 'rb_documents_list', 'Укажите оборудование' )
                    ->set_layout( 'tabbed-vertical' )
                    ->add_fields( 'info', array(
                        Field::make( 'text', 'title', 'Название' )
                            ->set_width(70), 
                        Field::make( 'file', 'file', 'Файл' )
                            ->set_type( 'pdf' )
                            ->set_width(30),
                    ) )
                    ->set_header_template( '
                          <% if (title) { %>
                              <%- title %>
                          <% } %>
                  ' ),

    ) );

    //поля для видео
    Container::make( 'post_meta', 'Настройки' )
        ->where( 'post_type', '=', 'videos' )
        ->add_fields( array(
            Field::make( 'text', 'rb_link', 'Ссылка на видео' ),
            Field::make( 'checkbox', 'rb_not_show_title', 'Спрятать заголовок?' )
                    ->set_option_value( 'yes' ),
            Field::make( 'File', 'rb_file', 'Видео' )
                    ->set_type( 'video' ),
            Field::make( 'text', 'rb_desc', 'Описание' ),
            Field::make( 'text', 'rb_occup', 'Должность лектора' ),
            Field::make( 'text', 'rb_fio', 'ФИО лектора' ),
    ));

    //для новостей
    Container::make( 'post_meta', 'Настройки' )
        ->where( 'post_type', '=', 'video-doctors' )
        ->add_fields( array(
                Field::make( 'text', 'rb_youtube', 'Ссылка на видео' ),
                Field::make( 'text', 'rb_duration', 'Продолжительность' ),
                
    ));


    //для курсов
    Container::make( 'post_meta', 'Настройки' )
        ->where( 'post_type', '=', 'promo' )
        ->add_fields( array(
                Field::make( 'text', 'rb_title', 'Заголовок' ),
                Field::make( 'rich_text', 'rb_desc', 'Описание акции' ),
                Field::make( 'text', 'rb_badge', 'Описание бейджа' ),
                Field::make( 'text', 'rb_time', 'Время проведения' ),
                Field::make( 'textarea', 'rb_question', 'Описание вопроса' ),
                Field::make( 'checkbox', 'rb_check', 'Особенное?' )
                    ->set_option_value( 'yes' ),
                Field::make( 'text', 'rb_check_link', 'Ссылка' )
                
    ));

    //поля для направлений
    Container::make( 'term_meta', 'Настройки' )
        ->where( 'term_taxonomy', '=', 'promo-cat' )
        ->add_fields( array(
             Field::make( 'color', 'rb_promo_color', 'Выберите цвет фона' )
                    ->set_width(33),
             Field::make( 'color', 'rb_promo_bd_color', 'Выберите цвет границы' )
                    ->set_width(33),
             Field::make( 'color', 'rb_promo_text_color', 'Выберите цвет текста' )
                    ->set_width(33),
    ));


     //поля для страницы Партнёры
    Container::make( 'post_meta', 'Настройки страницы' )
    ->where( 'post_type', '=', 'page' )
    ->where( 'post_template', '=', 'template-page/page-partners.php' )
    ->add_fields(  array(
            Field::make( 'textarea', 'rb_partners_desc', 'Описание' ),
            Field::make( 'complex', 'rb_partners_list', 'Укажите партнёров' )
                    ->set_layout( 'tabbed-vertical' )
                    ->add_fields( 'info', array(
                        Field::make( 'text', 'title', 'Название' )
                            ->set_width(70), 
                        Field::make( 'image', 'img', 'Изображение' )
                            ->set_width(30),
                        Field::make( 'rich_text', 'desc', 'Описание' ),
                        Field::make( 'text', 'discount', 'Скидка' )
                            ->set_width(50), 
                        Field::make( 'text', 'promo', 'Промокод' )
                            ->set_width(50), 
                    ) )
                    ->set_header_template( '
                          <% if (title) { %>
                              <%- title %>
                          <% } %>
                  ' ),

    ) );

     //поля для страницы Партнёры
    Container::make( 'post_meta', 'Настройки страницы' )
    ->where( 'post_type', '=', 'page' )
    ->where( 'post_template', '=', 'template-page/page-sert.php' )
    ->add_fields(  array(
            Field::make( 'text', 'rb_sert_title', 'Заголовок' ),
            Field::make( 'textarea', 'rb_sert_desc', 'Описание' ),
            Field::make( 'textarea', 'rb_sert_adv_title', 'Название раздела' ),
            Field::make( 'text', 'rb_sert_adv_btn_name', 'Название кнопки' ),
            Field::make( 'complex', 'rb_sert_adv_list', 'Список преимуществ' )
                    ->set_layout( 'tabbed-vertical' )
                    ->add_fields( 'adv', array(
                        Field::make( 'textarea', 'text', 'Текст' ), 
                    ) ),
            Field::make( 'textarea', 'rb_sert_subtext', 'Ссылка под блоком с преимуществами' ),
            Field::make( 'image', 'rb_sert_bot_image', 'Фото нижнего блока' )
                    ->set_width(25),
            Field::make( 'text', 'rb_sert_bot_title', 'Заголовок нижнего блока' )
                    ->set_width(75),
            Field::make( 'textarea', 'rb_sert_bot_text', 'Текст с нижнего блока' ),
            Field::make( 'text', 'rb_sert_bot_btn_name', 'Текст с кнопки c нижнего блока' ),
            Field::make( 'rich_text', 'rb_sert_bot_info', 'Текст в самом конце сайта' ),

    ) );

     //поля для страницы Партнёры
    Container::make( 'post_meta', 'Настройки страницы' )
    ->where( 'post_type', '=', 'page' )
    ->where( 'post_template', '=', 'template-page/page-links.php' )
    ->add_fields(  array(
            Field::make( 'text', 'rb_links_title', 'Заголовок' ),
            Field::make( 'text', 'rb_links_first_title', 'Первая ссылка заголовок' )
                    ->set_width(50),
            Field::make( 'text', 'rb_links_first', 'Первая ссылка' )
                    ->set_width(50),
            Field::make( 'complex', 'rb_links_list', 'Список ссылок' )
                    ->set_layout( 'tabbed-vertical' )
                    ->add_fields( 'adv', array(
                        Field::make( 'text', 'title', 'Заголовок' )
                                ->set_width(50), 
                        Field::make( 'text', 'link', 'Ссылка' )
                                ->set_width(50),
                        Field::make( 'textarea', 'desc', 'Описание' ), 
                         
                    ) )
                     ->set_header_template( '
                          <% if (title) { %>
                              <%- title %>
                          <% } %>
                  ' ),
            Field::make( 'text', 'rb_links_last_title', 'Последняя ссылка заголовок' )
                    ->set_width(50),
            Field::make( 'text', 'rb_links_last', 'Последняя ссылка' )
                    ->set_width(50),

    ) );

     //поля для страницы Партнёры
    Container::make( 'post_meta', 'Настройки страницы' )
    ->where( 'post_type', '=', 'page' )
    ->where( 'post_template', '=', 'template-page/page-psycology.php' )
    ->add_fields(  array(
            Field::make( 'textarea', 'rb_psy_desc', 'Описание' ),
            Field::make( 'text', 'rb_psy_5_elements_title', 'Заголовок блока 5 стихий' ),
            Field::make( 'complex', 'rb_psy_5_elements', '5 стихий' )
                    ->set_layout( 'tabbed-vertical' )
                    ->add_fields( 'adv', array(
                        Field::make( 'text', 'title', 'Заголовок' )
                                ->set_width(20), 
                        Field::make( 'textarea', 'desc', 'Описание' )
                                ->set_width(55),
                        Field::make( 'image', 'image', 'Фотография' )
                                ->set_width(25),
                         
                    ) )
                     ->set_header_template( '
                          <% if (title) { %>
                              <%- title %>
                          <% } %>
                  ' ),
            Field::make( 'text', 'rb_psy_5_under_title', 'Заголовок под блоком 5 стихий' ),
            Field::make( 'rich_text', 'rb_psy_5_under_desc_left', 'Описание под блоком 5 стихий слева' )
                    ->set_width(50),
            Field::make( 'rich_text', 'rb_psy_5_under_desc_right', 'Описание под блоком 5 стихий справа' )
                    ->set_width(50),
            Field::make( 'textarea', 'rb_psy_programms_title', 'Описание блока с программами' ),
            Field::make( 'complex', 'rb_psy_programms', 'Программы' )
                    ->set_layout( 'tabbed-vertical' )
                    ->add_fields( 'block', array(
                        Field::make( 'text', 'title', 'кол-во дней' ), 
                        Field::make( 'complex', 'elements', 'Программы' )
                            ->set_layout( 'tabbed-vertical' )
                            ->add_fields( 'adv', array(
                                Field::make( 'text', 'title', 'Название программы' ), 
                                Field::make( 'text', 'service', 'Кол-во услуг' ), 
                                Field::make( 'complex', 'items', 'Услуга' )
                                        ->set_layout( 'tabbed-vertical' )
                                        ->add_fields( 'adv', array(
                                            Field::make( 'rich_text', 'desc', 'Заголовок' ), 
                                            Field::make( 'text', 'price', 'Цена' ),
                                        ) ),
                                 
                            ) )
                             ->set_header_template( '
                                  <% if (title) { %>
                                      <%- title %>
                                  <% } %>
                          ' ),
                    ) )
                     ->set_header_template( '
                          <% if (title) { %>
                              <%- title %>
                          <% } %>
                  ' ),
            Field::make( 'text', 'rb_psy_banner_title', 'Заголовок баннера' )
                    ->set_width(40),
            Field::make( 'text', 'rb_psy_banner_link', 'Ссылка с баннера' )
                    ->set_width(40),
            Field::make( 'image', 'rb_psy_banner_image', 'Заголовок баннера' )
                    ->set_width(20),
            Field::make( 'textarea', 'rb_psy_banner_desc', 'Описание баннера' ),
    ) );


     //поля для постов Акции
    Container::make( 'post_meta', 'Сеты' )
    ->where( 'post_type', '=', 'post' )
    ->where( 'post_template', '=', 'template-post/post-set-type.php' )
    ->add_fields(  array(
            Field::make( 'text', 'rb_set_title', 'Заголовок блока' ),
            Field::make( 'text', 'rb_set_price', 'Цена сета' ),
            Field::make( 'complex', 'rb_set_list', 'Список сетов' )
                    ->set_layout( 'tabbed-vertical' )
                    ->add_fields( 'adv', array(
                        Field::make( 'text', 'title', 'Текст' ), 
                        Field::make( 'textarea', 'text', 'Описание' ), 
                    ) )
            ->set_header_template( '
                  <% if (title) { %>
                      <%- title %>
                  <% } %>
          ' ),
    ) );

     //поля для постов Акции
    Container::make( 'post_meta', 'Врачи' )
    ->where( 'post_type', '=', 'post' )
    ->where( 'post_template', '=', 'template-post/post-doctors-type.php' )
    ->add_fields(  array(
            Field::make( 'text', 'rb_doctors_title', 'Заголовок блока' ),
            Field::make( 'rich_text', 'rb_doctors_left', 'Текст слева' )
                    ->set_width(50),
            Field::make( 'rich_text', 'rb_doctors_right', 'Текст справа' )
                    ->set_width(50),
            Field::make( 'association', 'rb_doctors_list', 'Выберите врачей для скидки' )
                    ->set_types( array(
                        array(
                              'type'        => 'post',
                              'post_type'   => 'doctors',
                        ),

                    ) ),
    ) );

     //поля для постов Акции
    Container::make( 'post_meta', 'Текст' )
    ->where( 'post_type', '=', 'post' )
    ->where( 'post_template', '=', 'template-post/post-text-type.php' )
    ->or_where( 'post_template', '=', 'template-post/post-text-full-type.php' )
    ->add_fields(  array(
            Field::make( 'textarea', 'rb_text_title', 'Заголовок блока' ),
            Field::make( 'rich_text', 'rb_text', 'Текст' ),
            Field::make( 'rich_text', 'rb_text_additional', 'Дополнительная информация' ),
    ) );

     //поля для постов Акции
    Container::make( 'post_meta', 'Текст' )
    ->where( 'post_type', '=', 'post' )
    ->where( 'post_template', '=', 'template-post/post-text-colon-type.php' )
    ->add_fields(  array(
            Field::make( 'textarea', 'rb_text_title', 'Заголовок блока' ),
            Field::make( 'rich_text', 'rb_text_left', 'Текст слева' )
                    ->set_width(50),
            Field::make( 'rich_text', 'rb_text_right', 'Текст справа' )
                    ->set_width(50),
    ) );

     //поля для постов Акции
    Container::make( 'post_meta', 'Сеты большие' )
    ->where( 'post_type', '=', 'post' )
    ->where( 'post_template', '=', 'template-post/post-set-big-type.php' )
    ->add_fields(  array(
            Field::make( 'text', 'rb_set_title', 'Заголовок блока' ),
            Field::make( 'complex', 'rb_big_set_list', 'Список сетов' )
                    ->set_layout( 'tabbed-vertical' )
                    ->add_fields( 'item', array(
                        Field::make( 'text', 'title', 'Заголовок' ), 
                        Field::make( 'textarea', 'text', 'Описание' ), 
                        Field::make( 'complex', 'params', 'Список сетов' )
                                ->set_layout( 'tabbed-vertical' )
                                ->add_fields( 'item', array(
                                    Field::make( 'text', 'title', 'Заголовок' ), 
                                    Field::make( 'textarea', 'text', 'Текст' ), 
                                ) )
                                ->set_header_template( '
                                      <% if (title) { %>
                                          <%- title %>
                                      <% } %>
                              ' ),
                        Field::make( 'text', 'price', 'Стоимость' ),
                    ) )
            ->set_header_template( '
                  <% if (title) { %>
                      <%- title %>
                  <% } %>
            ' ),

    ) );
Container::make( 'post_meta', 'Параметры статьи' )
    ->where( 'post_type', '=', 'post' )
    ->add_fields( array(

        Field::make( 'text', 'rb_post_read_time', 'Время чтения (минут)' )
            ->set_width( 50 )
            ->set_attribute( 'type', 'number' )
            ->set_attribute( 'min', '1' )
            ->set_attribute( 'placeholder', '5' )
            ->set_help_text( 'Примерное время чтения в минутах. Если не заполнено — считается автоматически (~200 слов/мин)' ),

        Field::make( 'text', 'rb_post_views', 'Количество просмотров' )
            ->set_width( 50 )
            ->set_attribute( 'type', 'number' )
            ->set_attribute( 'min', '0' )
            ->set_attribute( 'placeholder', '0' )
            ->set_help_text( 'Введите количество просмотров вручную' ),

        Field::make( 'separator', 'rb_post_related_sep', 'Похожие статьи' ),

        Field::make( 'association', 'rb_post_related_articles', 'Похожие статьи вручную' )
            ->set_types( array(
                array(
                    'type'      => 'post',
                    'post_type' => 'post',
                ),
            ) )
            ->set_max( 8 )
            ->set_help_text( 'Если выбрать статьи здесь, в блоке “Похожие статьи” будут показаны именно они. Если поле пустое — блок заполнится автоматически по рубрикам.' ),

    ) );
    //поля для страницы 15% скидки на услуги
    Container::make( 'post_meta', 'Настройки страницы' )
    ->where( 'post_type', '=', 'page' )
    ->where( 'post_template', '=', 'template-page/page-services-discount.php' )
    ->add_fields(  array(
            Field::make( 'text', 'rb_discount_title', 'Заголовок верхнего блока' ),
            Field::make( 'rich_text', 'rb_discount_textarea', 'Описание верхнего блока' ),
            Field::make( 'complex', 'rb_discount_list', 'Список блоков' )
                    ->set_layout( 'tabbed-vertical' )
                    ->add_fields( 'item', array(
                        Field::make( 'text', 'title', 'Заголовок блока' ), 
                        Field::make( 'text', 'subtitle', 'Подзаголовок блока' ), 
                        Field::make( 'complex', 'params', 'Раздел' )
                                ->set_layout( 'tabbed-vertical' )
                                ->add_fields( 'item', array(
                                    Field::make( 'text', 'title', 'Заголовок' ), 
                                    Field::make( 'association', 'rb_services_list', 'Выберите услуги для скидки' )
                                    ->set_types( array(
                                        array(
                                            'type'        => 'post',
                                            'post_type'   => 'service',
                                        ),

                                    ) ),
                                    Field::make( 'text', 'price', 'Стоимость по отдельности' ),
                                    Field::make( 'text', 'discount', 'Стоимость сочетания услуг' ),
                                ) )
                              //   ->set_header_template( '
                              //         <% if (title) { %>
                              //             <%- title %>
                              //         <% } %>
                              // ' ),
                    ) )
            ->set_header_template( '
                  <% if (title) { %>
                      <%- title %>
                  <% } %>
            ' ),

    ) );


   //страница Консультативное отделение
    Container::make( 'post_meta', 'Настройки страницы' )
    ->where( 'post_type', '=', 'page' )
    ->where( 'post_template', '=', 'template-page/page-consult.php' )
    ->add_fields(  array(
            // Field::make( 'text', 'rb_discount_title', 'Заголовок верхнего блока' ),
            Field::make( 'association', 'rb_consult_list', 'Выберите услуги' )
                    ->set_types( array(
                        array(
                              'type'       => 'term',
                              'taxonomy'   => 'services',
                        ),

                    ) ),

    ) );


     //поля для страницы Акции
    Container::make( 'post_meta', 'Настройки страницы' )
    ->where( 'post_type', '=', 'page' )
    ->where( 'post_template', '=', 'template-page/page-promo.php' )
    ->add_fields(  array(
            Field::make( 'association', 'rb_actions', 'Выберите акцию' )
                    ->set_types( array(
                        array(
                              'type'        => 'post',
                              'post_type'   => 'post',
                        ),

                    ) ),

    ) );


     //поля для страницы Программы
    Container::make( 'post_meta', 'Настройки страницы' )
    ->where( 'post_type', '=', 'page' )
    ->where( 'post_template', '=', 'template-page/page-programms.php' )
    ->add_fields(  array(
            Field::make( 'image', 'image', 'Фото' )
                ->set_width(50),
            Field::make( 'image', 'image_mob', 'Фото мобильное' )
                ->set_width(50),

    ) );

Container::make( 'post_meta', 'Настройки страницы' )
->where( 'post_type', '=', 'page' )
->where( 'post_template', '=', 'template-page/page-competations.php' )
->add_fields(  array(
        Field::make( 'complex', 'rb_competations_tabs', 'Текстовые табы' )
            ->set_layout( 'tabbed-vertical' )
            ->add_fields( 'item', array(
                Field::make( 'text', 'title', 'Заголовок' ), 
                Field::make( 'textarea', 'desc', 'Описание' ),
                 
            ) )
             ->set_header_template( '
                  <% if (title) { %>
                      <%- title %>
                  <% } %>
          ' ),
        Field::make( 'text', 'rb_centers_title', 'Заголовок блока с центрами' ), 
        Field::make( 'complex', 'rb_centers_items', 'Блоки с центрами' )
            ->set_layout( 'tabbed-vertical' )
            ->add_fields( 'item', array(
                Field::make( 'text', 'title', 'Заголовок' ), 
                Field::make( 'text', 'link', 'Ссылка' ),
                 
            ) )
             ->set_header_template( '
                  <% if (title) { %>
                      <%- title %>
                  <% } %>
          ' ),
        
        Field::make( 'complex', 'rb_anchor_menu', 'Якорное меню' )
            ->set_layout( 'tabbed-vertical' )
            ->add_fields( array(
                Field::make( 'text', 'title', 'Название пункта меню' ),
                Field::make( 'text', 'anchor', 'Якорь (например: #services)' ),
            ) )
            ->set_header_template( '
                <% if (title) { %>
                    <%- title %>
                <% } %>
            ' ),


Field::make( 'text', 'rb_services_whou_is_title', 'Заголовок блока "Что это такое"' )
    ->set_default_value( 'Что это такое' ),

Field::make( 'complex', 'rb_services_whou_is', 'Блоки "Что это такое"' )
    ->set_layout( 'tabbed-vertical' )
    ->add_fields( 'item', array( // ВАЖНО: имя layout, например 'item'
        Field::make( 'text', 'title', 'Заголовок блока' ),
        Field::make( 'textarea', 'desc', 'Описание блока' ),
        // если нужен визуальный редактор, можно так:
        // Field::make( 'rich_text', 'desc', 'Описание блока' ),
    ) )
    ->set_header_template( '
        <% if (title) { %>
            <%- title %>
        <% } else { %>
            Пункт
        <% } %>
    ' ),


        Field::make( 'complex', 'rb_services_add', 'Блоки услуг' )
            ->set_layout( 'tabbed-vertical' )
            ->add_fields( 'item', array(
                Field::make( 'text', 'title', 'Заголовок блока услуг' )
                    ->set_default_value( 'Все услуги направления' ),
                Field::make( 'checkbox', 'rb_services_add_top', 'Показывать блок (позиция топ)' ),
                Field::make( 'checkbox', 'rb_services_add_diff2', 'Отображать плиткой (вместо слайдера)' ),
                Field::make( 'association', 'services', 'Выберите услуги' )
                    ->set_types( array(
                        array(
                            'type' => 'post',
                            'post_type' => 'service',
                        )
                    ) ),
            ) )
            ->set_header_template( '
                <% if (title) { %>
                    <%- title %>
                <% } %>
            ' ),
        Field::make('complex', 'rb_programms_list', 'Блок программ')
            ->set_layout('tabbed-horizontal')
            ->add_fields(array(
                Field::make('association', 'id', 'Выберите программу')
                    ->set_types(array(
                        array(
                            'type' => 'post',
                            'post_type' => 'programms',
                        ),
                    )),
            )),
        Field::make( 'text', 'rb_price_title', 'Заголовок блока цен' )
            ->set_default_value( 'Цены' ),
        
        Field::make( 'complex', 'rb_price_list', 'Список цен' )
            ->set_layout( 'tabbed-vertical' )
            ->add_fields( 'item', array(
                Field::make( 'association', 'service_select', 'Выберите услугу' )
                    ->set_types( array(
                        array(
                            'type' => 'post',
                            'post_type' => 'service',
                        )
                    ) )
                    ->set_max( 1 ),
                Field::make( 'association', 'doctor', 'Выберите врача (опционально)' )
                    ->set_types( array(
                        array(
                            'type' => 'post',
                            'post_type' => 'doctor',
                        )
                    ) )
                    ->set_max( 1 ),
            ) )
            ->set_header_template( '
                <% if (service_select && service_select.length > 0) { %>
                    Услуга: <%- service_select[0].post_title %>
                <% } else { %>
                    Новая услуга
                <% } %>
            ' ),

        Field::make( 'text', 'rb_portfolio_title', 'Заголовок блока портфолио' )
            ->set_default_value( 'Портфолио' ),
        
        Field::make( 'complex', 'rb_portfolio_list_ttt', 'Список портфолио' )
            ->set_layout( 'tabbed-vertical' )
            ->add_fields( 'item', array(
                Field::make( 'image', 'image', 'Изображение' ),
                Field::make( 'text', 'label', 'Метка' ),
                Field::make( 'text', 'name', 'Имя' ),
                Field::make( 'text', 'position', 'Должность' ),
                Field::make( 'textarea', 'desc', 'Описание' ),
            ) )
            ->set_header_template( '
                <% if (name) { %>
                    <%- name %>
                <% } else { %>
                    Слайд
                <% } %>
            ' ),

        Field::make('complex', 'rb_doctors_list', 'Специалисты')
            ->set_layout('tabbed-horizontal')
            ->add_fields(array(
                Field::make('association', 'id', 'Выберите врача')
                    ->set_types(array(
                        array(
                            'type' => 'post',
                            'post_type' => 'doctors',
                        ),
                    )),
            )),

        Field::make( 'text', 'rb_direction_info_title', 'Заголовок блока "О направлении"' )
            ->set_default_value( 'О направлении' ),
        
        Field::make( 'complex', 'rb_direction_info', 'Информация о направлении' )
            ->set_layout( 'tabbed-vertical' )
            ->add_fields( 'item', array(
                Field::make( 'text', 'question', 'Вопрос' ),
                Field::make( 'rich_text', 'answer', 'Ответ' ),
            ) )
            ->set_header_template( '
                <% if (question) { %>
                    <%- question %>
                <% } %>
            ' ),

        Field::make( 'complex', 'rb_faq', 'Часто задаваемые вопросы' )
            ->set_layout( 'tabbed-vertical' )
            ->add_fields( 'item', array(
                Field::make( 'text', 'question', 'Вопрос' ),
                Field::make( 'rich_text', 'answer', 'Ответ' ),
            ) )
            ->set_header_template( '
                <% if (question) { %>
                    <%- question %>
                <% } %>
            ' ),

        Field::make( 'association', 'rb_services_popular', 'Популярные услуги' )
            ->set_types( array(
                array(
                    'type' => 'post',
                    'post_type' => 'service',
                )
            ) ),
) );


    //поля для мероприятий
    Container::make( 'post_meta', 'Настройки' )
        ->where( 'post_type', '=', 'events' )
        ->add_fields( array(
            Field::make( 'date_time', 'rb_date', 'Дата мероприятия' ),
            Field::make( 'text', 'mts_link', 'Ссылка на трансляцию' ),
            Field::make( 'text', 'form_link', 'Ссылка на форму записи' ),
            Field::make( 'complex', 'rb_speakers_list', 'Спикеры' )
                    ->set_layout( 'tabbed-vertical' )
                    ->add_fields( 'info', array(
                        Field::make( 'text', 'title', 'ФИО' )
                            ->set_width(50),
                        Field::make( 'textarea', 'desc', 'Должность' )
                            ->set_width(50),
                        Field::make( 'image', 'image', 'Фото' )
                            ->set_width(25),
                        Field::make( 'text', 'link', 'Ссылка' )
                            ->set_width(75),

                    ) )
                    ->set_header_template( '
                          <% if (title) { %>
                              <%- title %>
                          <% } %>
                  ' ),
    ));

	//поля для страницы Центр метаболических нарушений и управления весом
	
Container::make('post_meta', 'Настройки страницы: Управление весом')
    ->where('post_template', '=', 'template-page/page-upravleniya-vesom.php')
    ->add_fields(array(
        Field::make('rich_text', 'rb_weight_desc', 'Текстовый блок (описание)'),
        
        Field::make('complex', 'rb_programms_list', 'Блок программ')
            ->set_layout('tabbed-horizontal')
            ->add_fields(array(
                Field::make('association', 'id', 'Выберите программу')
                    ->set_types(array(
                        array(
                            'type' => 'post',
                            'post_type' => 'programms',
                        ),
                    )),
            )),
            
        Field::make('complex', 'rb_doctors_list', 'Специалисты')
            ->set_layout('tabbed-horizontal')
            ->add_fields(array(
                Field::make('association', 'id', 'Выберите врача')
                    ->set_types(array(
                        array(
                            'type' => 'post',
                            'post_type' => 'doctors',
                        ),
                    )),
            )),
    ));


}
