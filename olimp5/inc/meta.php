<?php
// =============================
// Базовые настройки таксономий
// =============================

function rb_term_tpl_taxonomies(): array {
    return [ 'category', 'promo-cat' /* 'news_category' */ ];
}

const RB_TERM_CUSTOM_H1 = 'custom_field_key';

function rb_custom_h1_add_field( $taxonomy ) {
    $allowed = array_unique(array_merge(rb_term_tpl_taxonomies(), ['departments']));
    if ( ! in_array( $taxonomy, $allowed, true ) ) return; ?>
    <div class="form-field">
        <label for="<?php echo esc_attr(RB_TERM_CUSTOM_H1); ?>">Свой H1</label>
        <input type="text"
               name="<?php echo esc_attr(RB_TERM_CUSTOM_H1); ?>"
               id="<?php echo esc_attr(RB_TERM_CUSTOM_H1); ?>"
               value="">
        <p class="description">Введите свой заголовок h1.</p>
    </div>
<?php }

function add_custom_fields_to_taxonomy( $term ) {
    $allowed = array_unique(array_merge(rb_term_tpl_taxonomies(), ['departments']));
    if ( ! in_array( $term->taxonomy, $allowed, true ) ) return;

    $custom_field_value = get_term_meta($term->term_id, RB_TERM_CUSTOM_H1, true); ?>
    <tr class="form-field">
        <th scope="row"><label for="<?php echo esc_attr(RB_TERM_CUSTOM_H1); ?>">Свой H1</label></th>
        <td>
            <input type="text"
                   name="<?php echo esc_attr(RB_TERM_CUSTOM_H1); ?>"
                   id="<?php echo esc_attr(RB_TERM_CUSTOM_H1); ?>"
                   value="<?php echo esc_attr($custom_field_value); ?>"
                   class="regular-text">
            <p class="description">Введите свой заголовок h1</p>
        </td>
    </tr>
<?php }

function save_custom_fields_to_taxonomy( $term_id ) {
    if ( isset($_POST[RB_TERM_CUSTOM_H1]) ) {
        update_term_meta(
            $term_id,
            RB_TERM_CUSTOM_H1,
            sanitize_text_field( wp_unslash($_POST[RB_TERM_CUSTOM_H1]) )
        );
    }
}

add_action('admin_init', function () {
    $taxes = array_unique(array_merge(rb_term_tpl_taxonomies(), ['departments']));
    foreach ($taxes as $tax) {
        add_action("{$tax}_add_form_fields",  'rb_custom_h1_add_field',        10, 1);
        add_action("{$tax}_edit_form_fields", 'add_custom_fields_to_taxonomy', 10, 1);
        add_action("created_{$tax}",          'save_custom_fields_to_taxonomy',10, 1);
        add_action("edited_{$tax}",           'save_custom_fields_to_taxonomy',10, 1);
    }
});

// =============================
// Константы шаблонов терминов
// =============================

const RB_TERM_TITLE_TPL_KEY       = 'rb_single_title_tpl';
const RB_TERM_DESC_TPL_KEY        = 'rb_single_desc_tpl';
const RB_TERM_H1_TPL_KEY          = 'rb_single_h1_tpl';
const RB_TERM_PAGED_TITLE_TPL_KEY = 'rb_paged_title_tpl';
const RB_TERM_PAGED_DESC_TPL_KEY  = 'rb_paged_desc_tpl';
const RB_TERM_PAGED_H1_TPL_KEY    = 'rb_paged_h1_tpl';

// =============================
// Поля шаблонов для терминов
// =============================

add_action( 'category_add_form_fields', 'rb_term_tpl_add_fields' );
add_action( 'promo-cat_add_form_fields', 'rb_term_tpl_add_fields' );

function rb_term_tpl_add_fields( $taxonomy ) {
    if ( ! in_array( $taxonomy, rb_term_tpl_taxonomies(), true ) ) return; ?>
    <div class="form-field">
        <label for="<?= esc_attr(RB_TERM_TITLE_TPL_KEY) ?>">Шаблон Title для записей рубрики</label>
        <input name="<?= esc_attr(RB_TERM_TITLE_TPL_KEY) ?>" id="<?= esc_attr(RB_TERM_TITLE_TPL_KEY) ?>" type="text" value="" placeholder="Напр.: %%title%% %%sep%% %%sitename%%">
        <p class="description">Переменные Yoast поддерживаются (%%title%%, %%sitename%%, %%sep%% и т.д.).</p>
    </div>
    <div class="form-field">
        <label for="<?= esc_attr(RB_TERM_DESC_TPL_KEY) ?>">Шаблон Description для записей рубрики</label>
        <textarea name="<?= esc_attr(RB_TERM_DESC_TPL_KEY) ?>" id="<?= esc_attr(RB_TERM_DESC_TPL_KEY) ?>" rows="3" placeholder="Напр.: Читайте %%title%% — советы и разборы на %%sitename%%."></textarea>
        <p class="description">Можно использовать ваши переменные, например %%h1%%.</p>
    </div>
    <div class="form-field">
        <label for="<?= esc_attr(RB_TERM_H1_TPL_KEY) ?>">Шаблон H1 для записей рубрики</label>
        <input name="<?= esc_attr(RB_TERM_H1_TPL_KEY) ?>" id="<?= esc_attr(RB_TERM_H1_TPL_KEY) ?>" type="text" value="" placeholder="Напр.: %%title%% — %%sitename%%">
        <p class="description">Шаблон заголовка H1 для записей этой рубрики. Переменные: %%title%%, %%sitename%% и т.д.</p>
    </div>
    <div class="form-field">
        <label for="<?= esc_attr(RB_TERM_PAGED_TITLE_TPL_KEY) ?>">Шаблон Title для страниц пагинации рубрики</label>
        <input name="<?= esc_attr(RB_TERM_PAGED_TITLE_TPL_KEY) ?>" id="<?= esc_attr(RB_TERM_PAGED_TITLE_TPL_KEY) ?>" type="text" value="" placeholder="Напр.: %%term_title%% — Страница %%page_number%% %%sep%% %%sitename%%">
        <p class="description">Используйте %%term_title%%, %%sitename%%, %%sep%%, а также: %%page_number%%, %%page_total%%.</p>
    </div>
    <div class="form-field">
        <label for="<?= esc_attr(RB_TERM_PAGED_DESC_TPL_KEY) ?>">Шаблон Description для страниц пагинации рубрики</label>
        <textarea name="<?= esc_attr(RB_TERM_PAGED_DESC_TPL_KEY) ?>" id="<?= esc_attr(RB_TERM_PAGED_DESC_TPL_KEY) ?>" rows="3" placeholder="Напр.: %%term_title%%, стр. %%page_number%% — свежие материалы на %%sitename%%."></textarea>
        <p class="description">Используйте %%term_title%%, %%sitename%%, %%page_number%%, %%page_total%%. Переменные Yoast тоже работают.</p>
    </div>
    <div class="form-field">
        <label for="<?= esc_attr(RB_TERM_PAGED_H1_TPL_KEY) ?>">Шаблон H1 для страниц пагинации рубрики</label>
        <input name="<?= esc_attr(RB_TERM_PAGED_H1_TPL_KEY) ?>" id="<?= esc_attr(RB_TERM_PAGED_H1_TPL_KEY) ?>" type="text" value="" placeholder="Напр.: %%term_title%% — Страница %%page_number%%">
        <p class="description">Шаблон H1 для страниц пагинации. Используйте: %%term_title%%, %%page_number%%, %%page_total%%.</p>
    </div>
<?php }

add_action( 'category_edit_form_fields', 'rb_term_tpl_edit_fields' );
add_action( 'promo-cat_edit_form_fields', 'rb_term_tpl_edit_fields' );

function rb_term_tpl_edit_fields( $term ) {
    if ( ! in_array( $term->taxonomy, rb_term_tpl_taxonomies(), true ) ) return;

    $title       = get_term_meta( $term->term_id, RB_TERM_TITLE_TPL_KEY, true );
    $desc        = get_term_meta( $term->term_id, RB_TERM_DESC_TPL_KEY, true );
    $h1          = get_term_meta( $term->term_id, RB_TERM_H1_TPL_KEY, true );
    $paged_title = get_term_meta( $term->term_id, RB_TERM_PAGED_TITLE_TPL_KEY, true );
    $paged_desc  = get_term_meta( $term->term_id, RB_TERM_PAGED_DESC_TPL_KEY, true );
    $paged_h1    = get_term_meta( $term->term_id, RB_TERM_PAGED_H1_TPL_KEY, true ); ?>

    <tr class="form-field">
        <th scope="row"><label for="<?= esc_attr(RB_TERM_TITLE_TPL_KEY) ?>">Шаблон Title для записей рубрики</label></th>
        <td>
            <input name="<?= esc_attr(RB_TERM_TITLE_TPL_KEY) ?>" id="<?= esc_attr(RB_TERM_TITLE_TPL_KEY) ?>" type="text" value="<?= esc_attr($title) ?>" class="regular-text">
            <p class="description">Пример: %%title%% %%sep%% %%sitename%%</p>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row"><label for="<?= esc_attr(RB_TERM_DESC_TPL_KEY) ?>">Шаблон Description для записей рубрики</label></th>
        <td>
            <textarea name="<?= esc_attr(RB_TERM_DESC_TPL_KEY) ?>" id="<?= esc_attr(RB_TERM_DESC_TPL_KEY) ?>" rows="3" class="large-text"><?= esc_textarea($desc) ?></textarea>
            <p class="description">Пример: %%h1%% — свежие материалы и советы на %%sitename%%.</p>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row"><label for="<?= esc_attr(RB_TERM_H1_TPL_KEY) ?>">Шаблон H1 для записей рубрики</label></th>
        <td>
            <input name="<?= esc_attr(RB_TERM_H1_TPL_KEY) ?>" id="<?= esc_attr(RB_TERM_H1_TPL_KEY) ?>" type="text" value="<?= esc_attr($h1) ?>" class="regular-text">
            <p class="description">Пример: %%title%% — %%sitename%%</p>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row"><label for="<?= esc_attr(RB_TERM_PAGED_TITLE_TPL_KEY) ?>">Шаблон Title для пагинации рубрики</label></th>
        <td>
            <input name="<?= esc_attr(RB_TERM_PAGED_TITLE_TPL_KEY) ?>" id="<?= esc_attr(RB_TERM_PAGED_TITLE_TPL_KEY) ?>" type="text" value="<?= esc_attr($paged_title) ?>" class="regular-text">
            <p class="description">Пример: %%term_title%% — Страница %%page_number%% %%sep%% %%sitename%%</p>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row"><label for="<?= esc_attr(RB_TERM_PAGED_DESC_TPL_KEY) ?>">Шаблон Description для пагинации рубрики</label></th>
        <td>
            <textarea name="<?= esc_attr(RB_TERM_PAGED_DESC_TPL_KEY) ?>" id="<?= esc_attr(RB_TERM_PAGED_DESC_TPL_KEY) ?>" rows="3" class="large-text"><?= esc_textarea($paged_desc) ?></textarea>
            <p class="description">Пример: %%term_title%%, стр. %%page_number%% — свежие материалы на %%sitename%%.</p>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row"><label for="<?= esc_attr(RB_TERM_PAGED_H1_TPL_KEY) ?>">Шаблон H1 для пагинации рубрики</label></th>
        <td>
            <input name="<?= esc_attr(RB_TERM_PAGED_H1_TPL_KEY) ?>" id="<?= esc_attr(RB_TERM_PAGED_H1_TPL_KEY) ?>" type="text" value="<?= esc_attr($paged_h1) ?>" class="regular-text">
            <p class="description">Пример: %%term_title%% — Страница %%page_number%%</p>
        </td>
    </tr>
<?php }

add_action( 'created_term', 'rb_term_tpl_save', 10, 3 );
add_action( 'edited_term',  'rb_term_tpl_save', 10, 3 );

function rb_term_tpl_save( $term_id, $tt_id, $taxonomy ) {
    if ( ! in_array( $taxonomy, rb_term_tpl_taxonomies(), true ) ) return;

    foreach ( [
        RB_TERM_TITLE_TPL_KEY,
        RB_TERM_DESC_TPL_KEY,
        RB_TERM_H1_TPL_KEY,
        RB_TERM_PAGED_TITLE_TPL_KEY,
        RB_TERM_PAGED_DESC_TPL_KEY,
        RB_TERM_PAGED_H1_TPL_KEY
    ] as $key ) {
        if ( isset($_POST[$key]) ) {
            update_term_meta(
                $term_id,
                $key,
                wp_kses_post( wp_unslash($_POST[$key]) )
            );
        }
    }
}

// =============================
// Вспомогательные функции
// =============================

function rb_get_current_page_number() {
    $paged  = get_query_var('paged');
    $page   = get_query_var('page');
    $pg     = get_query_var('pg');
    $pg_get = isset($_GET['pg']) ? (int) $_GET['pg'] : 0;

    if ($paged)  return (int) $paged;
    if ($page)   return (int) $page;
    if ($pg)     return (int) $pg;
    if ($pg_get) return $pg_get;

    return 1;
}

function rb_termtpl_get_paged_vars(): array {
    $paged = rb_get_current_page_number();
    $total = 1;

    if (isset($GLOBALS['wp_query']) && $GLOBALS['wp_query'] instanceof WP_Query) {
        $total = (int) $GLOBALS['wp_query']->max_num_pages;
        if ($total < 1) {
            $total = 1;
        }
    }

    return [$paged, $total];
}

function rb_render_tpl_for_term(string $tpl, WP_Term $term): string {
    $tpl = trim($tpl);
    if ($tpl === '') return '';

    if (function_exists('wpseo_replace_vars')) {
        $rendered = wpseo_replace_vars($tpl, $term);
    } else {
        $rendered = strtr($tpl, [
            '%%term_title%%' => $term->name,
            '%%sitename%%'   => get_bloginfo('name'),
            '%%sep%%'        => '—',
        ]);
    }

    [$page_number, $page_total] = rb_termtpl_get_paged_vars();

    $rendered = strtr($rendered, [
        '%%page_number%%' => (string) $page_number,
        '%%page_total%%'  => (string) $page_total,
    ]);

    $rendered = wp_strip_all_tags(strip_shortcodes($rendered), true);
    return preg_replace('/\s+/u', ' ', trim($rendered));
}

function rb_get_primary_term_for_post( int $post_id ) {
    $taxes = rb_term_tpl_taxonomies();

    foreach ( $taxes as $tax ) {
        if ( ! taxonomy_exists($tax) ) {
            continue;
        }

        if ( class_exists('WPSEO_Primary_Term') ) {
            $p = new WPSEO_Primary_Term( $tax, $post_id );
            $term_id = (int) $p->get_primary_term();
            if ( $term_id ) {
                $t = get_term($term_id, $tax);
                if ( $t && ! is_wp_error($t) ) {
                    return $t;
                }
            }
        }

        $ts = get_the_terms( $post_id, $tax );
        if ( is_array($ts) && ! empty($ts) ) {
            return array_shift($ts);
        }
    }

    return null;
}

function rb_replace_template_vars( $template, $post_id = null ) {
    if ( empty($template) ) return '';

    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    $post = get_post( $post_id );
    if ( ! $post ) return $template;

    $replacements = [
        '%%title%%'     => get_the_title( $post_id ),
        '%%sitename%%'  => get_bloginfo( 'name' ),
        '%%sep%%'       => '—',
        '%%post_type%%' => get_post_type( $post_id ),
    ];

    $taxonomies = rb_term_tpl_taxonomies();
    foreach ( $taxonomies as $taxonomy ) {
        $terms = get_the_terms( $post_id, $taxonomy );
        if ( $terms && ! is_wp_error( $terms ) ) {
            $term = array_shift( $terms );
            $replacements['%%term_title%%'] = $term->name;
            break;
        }
    }

    return str_replace( array_keys($replacements), array_values($replacements), $template );
}

function rb_render_tpl_for_post( string $tpl, int $post_id ): string {
    $tpl = trim($tpl);
    if ( $tpl === '' ) return '';

    if ( function_exists('wpseo_replace_vars') ) {
        $post = get_post($post_id);
        $rendered = wpseo_replace_vars($tpl, $post);
    } else {
        $rendered = strtr($tpl, [
            '%%title%%'    => get_the_title($post_id),
            '%%sitename%%' => get_bloginfo('name'),
            '%%sep%%'      => '—',
        ]);
    }

    $rendered = wp_strip_all_tags(strip_shortcodes($rendered), true);
    return preg_replace('/\s+/u', ' ', trim($rendered));
}

function rb_term_tpl_for_single(): array {
    if ( ! is_singular() ) return ['', ''];

    $post_id = get_queried_object_id();
    if ( ! $post_id ) return ['', ''];

    $term = rb_get_primary_term_for_post( $post_id );
    if ( ! $term ) return ['', ''];

    $title_tpl = get_term_meta( $term->term_id, RB_TERM_TITLE_TPL_KEY, true );
    $desc_tpl  = get_term_meta( $term->term_id, RB_TERM_DESC_TPL_KEY, true );

    $title = $title_tpl ? rb_render_tpl_for_post( $title_tpl, $post_id ) : '';
    $desc  = $desc_tpl  ? rb_render_tpl_for_post( $desc_tpl,  $post_id ) : '';

    return [ $title, $desc ];
}

// =============================
// H1 фильтр для записей
// =============================

add_filter( 'the_title', 'rb_term_tpl_filter_h1', 10, 2 );
function rb_term_tpl_filter_h1( $title, $post_id = null ) {
    if ( is_admin() || ! is_singular() || ! in_the_loop() || ! $post_id ) {
        return $title;
    }

    $post = get_post( $post_id );
    if ( ! $post ) return $title;

    $taxonomies = rb_term_tpl_taxonomies();
    foreach ( $taxonomies as $taxonomy ) {
        $terms = get_the_terms( $post_id, $taxonomy );
        if ( $terms && ! is_wp_error( $terms ) ) {
            $term = array_shift( $terms );
            $h1_template = get_term_meta( $term->term_id, RB_TERM_H1_TPL_KEY, true );
            if ( ! empty( $h1_template ) ) {
                return rb_replace_template_vars( $h1_template, $post_id );
            }
        }
    }

    return $title;
}

// =============================
// Yoast Title/Desc/OG/Twitter
// =============================

add_filter('wpseo_title', function ($title) {
    if ((is_category() || is_tag() || is_tax())) {
        $term = get_queried_object();
        if ($term instanceof WP_Term) {
            $current_page = rb_get_current_page_number();

            if ($current_page > 1) {
                $tpl = get_term_meta($term->term_id, RB_TERM_PAGED_TITLE_TPL_KEY, true);
                if (!empty($tpl)) {
                    $rendered = rb_render_tpl_for_term($tpl, $term);
                    if ($rendered !== '') return $rendered;
                }
            } else {
                $tpl = get_term_meta($term->term_id, RB_TERM_TITLE_TPL_KEY, true);
                if (!empty($tpl)) {
                    $rendered = rb_render_tpl_for_term($tpl, $term);
                    if ($rendered !== '') return $rendered;
                }
            }
        }
    }

    [ $t ] = rb_term_tpl_for_single();
    return $t !== '' ? $t : $title;
}, 9999);

add_filter('wpseo_metadesc', function ($desc) {
    if ((is_category() || is_tag() || is_tax())) {
        $term = get_queried_object();
        if ($term instanceof WP_Term) {
            $current_page = rb_get_current_page_number();

            if ($current_page > 1) {
                $tpl = get_term_meta($term->term_id, RB_TERM_PAGED_DESC_TPL_KEY, true);
                if (!empty($tpl)) {
                    $rendered = rb_render_tpl_for_term($tpl, $term);
                    if ($rendered !== '') return $rendered;
                }
            } else {
                $tpl = get_term_meta($term->term_id, RB_TERM_DESC_TPL_KEY, true);
                if (!empty($tpl)) {
                    $rendered = rb_render_tpl_for_term($tpl, $term);
                    if ($rendered !== '') return $rendered;
                }
            }
        }
    }

    [, $d ] = rb_term_tpl_for_single();
    return $d !== '' ? $d : $desc;
}, 9999);

add_filter('wpseo_opengraph_title', function ($og) {
    if ((is_category() || is_tag() || is_tax())) {
        $term = get_queried_object();
        if ($term instanceof WP_Term) {
            $current_page = rb_get_current_page_number();

            if ($current_page > 1) {
                $tpl = get_term_meta($term->term_id, RB_TERM_PAGED_TITLE_TPL_KEY, true);
                if (!empty($tpl)) {
                    $rendered = rb_render_tpl_for_term($tpl, $term);
                    if ($rendered !== '') return $rendered;
                }
            } else {
                $tpl = get_term_meta($term->term_id, RB_TERM_TITLE_TPL_KEY, true);
                if (!empty($tpl)) {
                    $rendered = rb_render_tpl_for_term($tpl, $term);
                    if ($rendered !== '') return $rendered;
                }
            }
        }
    }

    [ $t ] = rb_term_tpl_for_single();
    return $t !== '' ? $t : $og;
}, 9999);

add_filter('wpseo_opengraph_desc', function ($og) {
    if ((is_category() || is_tag() || is_tax())) {
        $term = get_queried_object();
        if ($term instanceof WP_Term) {
            $current_page = rb_get_current_page_number();

            if ($current_page > 1) {
                $tpl = get_term_meta($term->term_id, RB_TERM_PAGED_DESC_TPL_KEY, true);
                if (!empty($tpl)) {
                    $rendered = rb_render_tpl_for_term($tpl, $term);
                    if ($rendered !== '') return $rendered;
                }
            } else {
                $tpl = get_term_meta($term->term_id, RB_TERM_DESC_TPL_KEY, true);
                if (!empty($tpl)) {
                    $rendered = rb_render_tpl_for_term($tpl, $term);
                    if ($rendered !== '') return $rendered;
                }
            }
        }
    }

    [, $d ] = rb_term_tpl_for_single();
    return $d !== '' ? $d : $og;
}, 9999);

add_filter('wpseo_twitter_title', function ($tw) {
    if ((is_category() || is_tag() || is_tax())) {
        $term = get_queried_object();
        if ($term instanceof WP_Term) {
            $current_page = rb_get_current_page_number();

            if ($current_page > 1) {
                $tpl = get_term_meta($term->term_id, RB_TERM_PAGED_TITLE_TPL_KEY, true);
                if (!empty($tpl)) {
                    $rendered = rb_render_tpl_for_term($tpl, $term);
                    if ($rendered !== '') return $rendered;
                }
            } else {
                $tpl = get_term_meta($term->term_id, RB_TERM_TITLE_TPL_KEY, true);
                if (!empty($tpl)) {
                    $rendered = rb_render_tpl_for_term($tpl, $term);
                    if ($rendered !== '') return $rendered;
                }
            }
        }
    }

    [ $t ] = rb_term_tpl_for_single();
    return $t !== '' ? $t : $tw;
}, 9999);

add_filter('wpseo_twitter_description', function ($tw) {
    if ((is_category() || is_tag() || is_tax())) {
        $term = get_queried_object();
        if ($term instanceof WP_Term) {
            $current_page = rb_get_current_page_number();

            if ($current_page > 1) {
                $tpl = get_term_meta($term->term_id, RB_TERM_PAGED_DESC_TPL_KEY, true);
                if (!empty($tpl)) {
                    $rendered = rb_render_tpl_for_term($tpl, $term);
                    if ($rendered !== '') return $rendered;
                }
            } else {
                $tpl = get_term_meta($term->term_id, RB_TERM_DESC_TPL_KEY, true);
                if (!empty($tpl)) {
                    $rendered = rb_render_tpl_for_term($tpl, $term);
                    if ($rendered !== '') return $rendered;
                }
            }
        }
    }

    [, $d ] = rb_term_tpl_for_single();
    return $d !== '' ? $d : $tw;
}, 9999);

// =============================
// Доп. переменные Yoast
// =============================

add_action('wpseo_register_extra_replacements', function () {
    if (!function_exists('wpseo_register_var_replacement')) return;

    wpseo_register_var_replacement(
        '%%page_number%%',
        function () {
            return (string) rb_get_current_page_number();
        },
        'basic',
        __('Номер страницы пагинации (для архивов таксономий).', 'your-textdomain')
    );

    wpseo_register_var_replacement(
        '%%page_total%%',
        function () {
            [, $t] = rb_termtpl_get_paged_vars();
            return (string) $t;
        },
        'basic',
        __('Всего страниц в пагинации (для архивов таксономий).', 'your-textdomain')
    );

    wpseo_register_var_replacement(
        '%%custom_h1%%',
        function ( $context = [] ) {
            $term_id = 0;

            if ( isset($context['term_id']) ) {
                $term_id = (int) $context['term_id'];
            }
            if ( ! $term_id ) {
                $obj = get_queried_object();
                if ( $obj && ! empty($obj->term_id) ) {
                    $term_id = (int) $obj->term_id;
                }
            }
            if ( ! $term_id ) return '';

            return (string) get_term_meta( $term_id, RB_TERM_CUSTOM_H1, true );
        },
        'term',
        __( 'Пользовательский H1 из метаполя термина (custom_field_key).', 'your-textdomain' )
    );
});

// =============================
// Нормализация URL в schema.org
// =============================
/**
 * Нормализация URL для хлебных крошек
 */
if (!function_exists('rb_normalize_url_clean')) {
    function rb_normalize_url_clean($url) {
        if (!is_string($url) || $url === '') {
            return $url;
        }

        // Точные кейсы
        $url = str_replace(
            [
                'https://olimp5.ru/./actions/',
                'https://olimp5.ru/./news/',
                'https://olimp5.ru/./articles/',
            ],
            [
                'https://olimp5.ru/actions/',
                'https://olimp5.ru/news/',
                'https://olimp5.ru/articles/',
            ],
            $url
        );

        // Общий случай: выкидываем /./
        if (strpos($url, '/./') !== false) {
            $url = str_replace('/./', '/', $url);
        }

        return $url;
    }
}

/**
 * Чиним schema.org BreadcrumbList от Yoast:
 * - правим битые URL с /./
 * - убираем ссылку у последнего элемента
 */
add_filter('wpseo_schema_breadcrumb', function ($data) {
    if (!is_array($data) || empty($data['itemListElement']) || !is_array($data['itemListElement'])) {
        return $data;
    }

    $items = $data['itemListElement'];
    $last_index = count($items) - 1;

    foreach ($items as $i => &$item) {
        if (!is_array($item)) {
            continue;
        }

        // item может быть строкой (URL) или объектом
        if (isset($item['item'])) {
            // Вариант: строка-URL
            if (is_string($item['item'])) {
                $item['item'] = rb_normalize_url_clean($item['item']);
            }
            // Вариант: объект с @id/url
            elseif (is_array($item['item'])) {
                if (isset($item['item']['@id'])) {
                    $item['item']['@id'] = rb_normalize_url_clean($item['item']['@id']);
                }
                if (isset($item['item']['url'])) {
                    $item['item']['url'] = rb_normalize_url_clean($item['item']['url']);
                }
            }
        }

        // Последний элемент хлебных крошек — без ссылки
        if ($i === $last_index && isset($item['item'])) {
            unset($item['item']);
        }
    }
    unset($item);

    $data['itemListElement'] = $items;
    return $data;
}, 9999);


// =============================
// Общие вспомогательные для описаний и H1
// =============================

function rb_fallback_description(int $post_id, int $words = 28): string {
    $excerpt = get_the_excerpt($post_id);
    if ($excerpt !== '') {
        return wp_strip_all_tags($excerpt, true);
    }

    $content = get_post_field('post_content', $post_id);
    $plain   = wp_strip_all_tags(strip_shortcodes($content), true);
    $plain   = preg_replace('/\s+/u', ' ', trim($plain));

    if ($plain === '') return '';

    $parts = preg_split('~\s+~u', $plain);
    if (count($parts) <= $words) return $plain;

    return implode(' ', array_slice($parts, 0, $words)) . '…';
}

function rb_compute_single_h1_for_post(int $post_id): string {
    $title_fallback = wp_strip_all_tags(get_the_title($post_id), true);

    $term = rb_get_primary_term_for_post($post_id);
    if (!$term instanceof WP_Term) {
        return $title_fallback;
    }

    $tpl = get_term_meta($term->term_id, RB_TERM_H1_TPL_KEY, true);
    if (empty($tpl)) {
        return $title_fallback;
    }

    $rendered = rb_replace_template_vars($tpl, $post_id);
    $rendered = wp_strip_all_tags($rendered, true);
    $rendered = preg_replace('/\s+/u', ' ', trim($rendered));

    return $rendered !== '' ? $rendered : $title_fallback;
}

// =============================
// Article ("/articles/")
// =============================

function rb_is_articles_section(int $post_id): bool {
    $ptype = get_post_type($post_id);
    if ($ptype === 'articles') return true;

    if (function_exists('has_category') && has_category('articles', $post_id)) {
        return true;
    }

    $permalink = get_permalink($post_id);
    if (is_string($permalink)) {
        $path = parse_url($permalink, PHP_URL_PATH) ?? '';
        if (strpos($path, '/articles/') !== false) {
            return true;
        }
    }

    return false;
}

add_action('wp_head', function () {
    if (!is_singular()) return;

    $post_id = get_queried_object_id();
    if (!$post_id) return;

    if (!rb_is_articles_section($post_id)) return;

    $headline = rb_compute_single_h1_for_post($post_id);

    $yoast_desc  = get_post_meta($post_id, '_yoast_wpseo_metadesc', true);
    $description = $yoast_desc !== '' ? $yoast_desc : rb_fallback_description($post_id);

    $image_url = get_the_post_thumbnail_url($post_id, 'full');
    $image     = $image_url ? [$image_url] : [];

    $date_published = get_the_date('c', $post_id);
    $date_modified  = get_the_modified_date('c', $post_id);

    $data = [
        '@context'      => 'https://schema.org',
        '@type'         => 'Article',
        'headline'      => $headline,
        'description'   => $description,
        'datePublished' => $date_published,
        'dateModified'  => $date_modified,
        'author'        => [[
            '@type' => 'Organization',
            'name'  => 'ЦКЗ «Олимп Пять»',
        ]],
    ];

    if (!empty($image)) {
        $data['image'] = $image;
    }

    echo "\n<script type=\"application/ld+json\">" .
        wp_json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) .
        "</script>\n";
}, 99);

// =============================
// NewsArticle ("/news/")
// =============================

function rb_is_news_section(int $post_id): bool {
    $ptype = get_post_type($post_id);
    if ($ptype === 'news') return true;

    if (function_exists('has_category') && has_category('news', $post_id)) {
        return true;
    }

    $taxes = get_object_taxonomies($ptype, 'names');
    if (is_array($taxes)) {
        foreach ($taxes as $tax) {
            $terms = get_the_terms($post_id, $tax);
            if (!is_wp_error($terms) && $terms) {
                foreach ($terms as $t) {
                    if (in_array(sanitize_title($t->slug), ['news', 'novosti'], true)) {
                        return true;
                    }
                }
            }
        }
    }

    $path = parse_url(get_permalink($post_id), PHP_URL_PATH) ?? '';
    if (strpos($path, '/news/') !== false) return true;

    return false;
}

add_action('wp_head', function () {
    if (!is_singular()) return;

    $post_id = get_queried_object_id();
    if (!$post_id) return;
    if (!rb_is_news_section($post_id)) return;

    $headline = rb_compute_single_h1_for_post($post_id);

    $yoast_desc  = get_post_meta($post_id, '_yoast_wpseo_metadesc', true);
    $description = $yoast_desc !== '' ? $yoast_desc : rb_fallback_description($post_id);

    $image_url = get_the_post_thumbnail_url($post_id, 'full');
    $image     = $image_url ? [$image_url] : [];

    $date_published = get_the_date('c', $post_id);
    $date_modified  = get_the_modified_date('c', $post_id);

    $data = [
        '@context'      => 'https://schema.org',
        '@type'         => 'NewsArticle',
        'headline'      => $headline,
        'description'   => $description,
        'datePublished' => $date_published,
        'dateModified'  => $date_modified,
        'author'        => [[
            '@type' => 'Organization',
            'name'  => 'ЦКЗ «Олимп Пять»',
        ]],
    ];

    if (!empty($image)) {
        $data['image'] = $image;
    }

    echo "\n<script type=\"application/ld+json\">" .
        wp_json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) .
        "</script>\n";
}, 99);

// =============================
// FAQPage для услуг (/services/)
// =============================

function rb_is_services_section(int $post_id): bool {
    // Проверяем URL - главный критерий
    $permalink = get_permalink($post_id);
    if (is_string($permalink)) {
        $path = parse_url($permalink, PHP_URL_PATH) ?? '';
        if (strpos($path, '/services/') !== false) {
            return true;
        }
    }

    // Проверяем тип записи
    $ptype = get_post_type($post_id);
    if (in_array($ptype, ['service', 'services'], true)) return true;

    // Проверяем шаблон страницы
    $tpl = get_page_template_slug($post_id);
    if (is_string($tpl) && strpos($tpl, 'service') !== false) return true;

    return false;
}

/**
 * Вывод FAQPage для страниц услуг
 */
add_action('wp_head', function () {
    if (!is_singular()) return;

    $post_id = get_queried_object_id();
    if (!$post_id) return;
    
    // Проверяем, что это страница услуг
    if (!rb_is_services_section($post_id)) return;

    // Собираем FAQ items
    $items = rb_collect_faq_items($post_id);
    
    // Если нет вопросов - не выводим схему
    if (empty($items)) return;

    $faq = [
        '@context'   => 'https://schema.org',
        '@type'      => 'FAQPage',
        'mainEntity' => array_map(function ($it) {
            return [
                '@type' => 'Question',
                'name'  => $it['q'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => $it['a'],
                ],
            ];
        }, $items),
    ];

    echo "\n<script type=\"application/ld+json\">" .
        wp_json_encode($faq, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) .
        "</script>\n";
}, 98); // Приоритет 98, чтобы выполнялось раньше других схем

/**
 * Сбор FAQ из различных источников
 */
function rb_collect_faq_items(int $post_id): array {
    $items = [];

    // 1. Пробуем Carbon Fields
    if (function_exists('carbon_get_post_meta')) {
        foreach (['rb_services_faq', 'rb_faq', 'faq', 'faq_items'] as $meta_key) {
            $raw = carbon_get_post_meta($post_id, $meta_key);
            if (is_array($raw) && !empty($raw)) {
                $items = array_merge($items, rb_normalize_faq_array($raw));
            }
        }
    }

    // 2. Пробуем ACF
    if (function_exists('get_field')) {
        foreach (['faq', 'faq_items', 'service_faq'] as $acf_key) {
            $raw = get_field($acf_key, $post_id);
            if (is_array($raw) && !empty($raw)) {
                $items = array_merge($items, rb_normalize_faq_array($raw));
            }
        }
    }

    // 3. Извлекаем из контента
    $content = (string) get_post_field('post_content', $post_id);

    if ($content !== '') {
        // Блоки Yoast FAQ
        $yoast = rb_extract_yoast_faq_from_blocks($content);
        if (!empty($yoast)) {
            $items = array_merge($items, $yoast);
        }

        // HTML с классами faq-item
        $from_html = rb_extract_faq_from_html($content);
        if (!empty($from_html)) {
            $items = array_merge($items, $from_html);
        }
    }

    // Нормализуем и удаляем дубли
    $norm = [];
    foreach ($items as $it) {
        $q = trim(wp_strip_all_tags($it['q'], true));
        $a = trim($it['a']);
        if ($q === '' || $a === '') continue;
        $norm[] = ['q' => $q, 'a' => rb_allow_basic_html($a)];
    }

    $seen = [];
    $out  = [];

    foreach ($norm as $it) {
        $key = mb_strtolower($it['q']);
        if (isset($seen[$key])) continue;
        $seen[$key] = true;
        $out[] = $it;
        if (count($out) >= 20) break; // Максимум 20 вопросов
    }

    return $out;
}

/**
 * Нормализация массивов FAQ из метаполей
 */
function rb_normalize_faq_array(array $raw): array {
    $out = [];
    foreach ($raw as $row) {
        if (!is_array($row)) continue;
        $q = $row['title'] ?? $row['question'] ?? $row['q'] ?? $row['name'] ?? '';
        $a = $row['desc']  ?? $row['answer']   ?? $row['a'] ?? $row['text'] ?? '';
        if (is_array($q)) $q = implode(' ', array_map('wp_strip_all_tags', $q));
        if (is_array($a)) $a = implode(' ', array_map('wp_kses_post', $a));
        $q = trim((string) $q);
        $a = trim((string) $a);
        if ($q === '' || $a === '') continue;
        $out[] = ['q' => $q, 'a' => $a];
    }
    return $out;
}

/**
 * Извлечение FAQ из блоков Yoast
 */
function rb_extract_yoast_faq_from_blocks(string $content): array {
    if (!function_exists('parse_blocks')) return [];
    $blocks = parse_blocks($content);
    if (!is_array($blocks) || empty($blocks)) return [];

    $items = [];

    $walker = function ($blocks) use (&$walker, &$items) {
        foreach ($blocks as $b) {
            if (!is_array($b)) continue;
            $name = $b['blockName'] ?? '';
            $attrs = $b['attrs'] ?? $b['attributes'] ?? [];

            if ($name === 'yoast/faq-block' || $name === 'yoast-seo/faq-block') {
                if (!empty($attrs['questions']) && is_array($attrs['questions'])) {
                    foreach ($attrs['questions'] as $q) {
                        $title  = $q['question'] ?? $q['title'] ?? '';
                        $answer = $q['answer'] ?? $q['content'] ?? '';
                        $title  = trim((string) $title);
                        $answer = trim((string) $answer);
                        if ($title !== '' && $answer !== '') {
                            $items[] = ['q' => $title, 'a' => $answer];
                        }
                    }
                } elseif (!empty($attrs['questionsSerialized']) && is_string($attrs['questionsSerialized'])) {
                    $decoded = json_decode($attrs['questionsSerialized'], true);
                    if (is_array($decoded)) {
                        foreach ($decoded as $q) {
                            $title  = $q['question'] ?? $q['title'] ?? '';
                            $answer = $q['answer'] ?? $q['content'] ?? '';
                            $title  = trim((string) $title);
                            $answer = trim((string) $answer);
                            if ($title !== '' && $answer !== '') {
                                $items[] = ['q' => $title, 'a' => $answer];
                            }
                        }
                    }
                }
            }

            if (!empty($b['innerBlocks']) && is_array($b['innerBlocks'])) {
                $walker($b['innerBlocks']);
            }
        }
    };

    $walker($blocks);
    return $items;
}

/**
 * Извлечение FAQ из HTML-разметки
 */
function rb_extract_faq_from_html(string $content): array {
    $out = [];

    // Ищем блоки с классом faq-item
    if (preg_match_all('~<[^>]*class=("|\')(?:[^"\']*\s)?faq-item(?:\s[^"\']*)?\\1[^>]*>(.*?)</[^>]+>~is', $content, $m)) {
        foreach ($m[2] as $blockHtml) {
            $q = '';
            $a = '';
            if (preg_match('~<[^>]*class=("|\')(?:[^"\']*\s)?faq-q(?:\s[^"\']*)?\\1[^>]*>(.*?)</[^>]+>~is', $blockHtml, $mq)) {
                $q = trim(wp_strip_all_tags($mq[2], true));
            }
            if (preg_match('~<[^>]*class=("|\')(?:[^"\']*\s)?faq-a(?:\s[^"\']*)?\\1[^>]*>(.*?)</[^>]+>~is', $blockHtml, $ma)) {
                $a = trim($ma[2]);
            }
            if ($q !== '' && $a !== '') {
                $out[] = ['q' => $q, 'a' => $a];
            }
        }
    }

    return $out;
}

/**
 * Разрешаем базовый HTML в ответах FAQ
 */
function rb_allow_basic_html(string $html): string {
    $allowed = [
        'p' => ['class' => []],
        'br' => [],
        'strong' => [], 'b' => [], 'em' => [], 'i' => [], 'u' => [],
        'ul' => ['class' => []], 'ol' => ['class' => []], 'li' => ['class' => []],
        'a' => ['href' => [], 'title' => [], 'target' => [], 'rel' => []],
        'span' => ['class' => []],
    ];
    return wp_kses($html, $allowed);
}

// =============================
// LocalBusiness для главной и ключевых страниц
// =============================

if ( ! function_exists( 'olimp5_get_localbusiness_schema' ) ) {
    function olimp5_get_localbusiness_schema(): array {
        return [
            '@context' => 'https://schema.org',
            '@type'    => 'LocalBusiness',
            '@id'      => 'https://olimp5.ru/#localbusiness',
            'name'     => 'Центр культуры здоровья «Олимп Пять»',
            'url'      => 'https://olimp5.ru/',
            'telephone'=> '+74732112035',
            'email'    => 'info@olimp5.ru',
            'priceRange' => 'RUB',
            'logo'     => 'https://olimp5.ru/wp-content/uploads/2023/08/logo.png',
            'image'    => 'https://olimp5.ru/wp-content/uploads/2023/08/logo.png',
            'description' => 'Центр культуры здоровья «Олимп Пять» – крупнейший центр восстановительной медицины в Черноземье, не имеющий аналогов в регионе. Действующий как санаторий в городе, он предлагает широкий спектр санаторно-курортных, лечебных и бьюти-услуг в центре Воронежа.',
            'address'  => [
                '@type'           => 'PostalAddress',
                'streetAddress'   => 'ул. Моисеева, 2/21',
                'addressLocality' => 'Воронеж',
                'addressRegion'   => 'Воронежская область',
                'postalCode'      => '394006',
                'addressCountry'  => 'RU',
            ],
            'openingHoursSpecification' => [
                [
                    '@type'    => 'OpeningHoursSpecification',
                    'dayOfWeek'=> [
                        'Monday',
                        'Tuesday',
                        'Wednesday',
                        'Thursday',
                        'Friday',
                    ],
                    'opens'    => '08:00',
                    'closes'   => '20:00',
                ],
                [
                    '@type'    => 'OpeningHoursSpecification',
                    'dayOfWeek'=> [
                        'Saturday',
                        'Sunday',
                    ],
                    'opens'    => '08:00',
                    'closes'   => '18:00',
                ],
            ],
        ];
    }
}

add_action( 'wp_head', function () {
    // Главная
    if ( is_front_page() ) {
        $data = olimp5_get_localbusiness_schema();
        echo "\n<script type=\"application/ld+json\">"
           . wp_json_encode( $data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES )
           . "</script>\n";
        return;
    }

    // Страницы "О компании" и "Контакты"
    if ( is_page( array( 'about', 'contacts' ) ) ) {
        $data = olimp5_get_localbusiness_schema();
        echo "\n<script type=\"application/ld+json\">"
           . wp_json_encode( $data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES )
           . "</script>\n";
    }
}, 90);