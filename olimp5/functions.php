<?php

function exp_text($text_old,$max_char){
	$text_old=strip_shortcodes($text_old);
	$text_old=preg_replace('~\[[^\]]+\]~','',$text_old);
	$text_old=strip_tags($text_old);
	
	if(iconv_strlen($text_old,'utf-8') < $max_char)
		return $text_old;
	else {
		$text_new=iconv_substr($text_old,0,$max_char,'utf-8');
		$text_new=preg_replace('@(.*)\s[^\s]*$@s','\\1 ...',$text_new);
		return $text_new;
	}
}

require get_template_directory() . '/inc/rb-carbon.php';
require get_template_directory() . '/inc/rb-cpt.php';
require get_template_directory() . '/inc/rb-ajax-handler.php';
require get_template_directory() . '/inc/rb-settings.php';
require get_template_directory() . '/inc/rb-functions.php';
require get_template_directory() . '/inc/search.php';
require get_template_directory() . '/inc/slider-programs.php';
require get_template_directory() . '/inc/meta.php';
if (!function_exists('rb_builder_log')) {
    function rb_builder_log(string $msg, array $ctx = []): void {
        $file = WP_CONTENT_DIR . '/rb-builder-debug.log';
        $line = '[' . gmdate('Y-m-d H:i:s') . " UTC] " . $msg;

        if (!empty($ctx)) {
            $line .= ' | ' . json_encode($ctx, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        $line .= PHP_EOL;
        @file_put_contents($file, $line, FILE_APPEND);
    }
}
add_action('wp', function () {
    if (!is_front_page()) return;
    if (!function_exists('carbon_get_post_meta')) {
        rb_builder_log('Carbon not loaded: carbon_get_post_meta missing');
        return;
    }

    $id = get_queried_object_id();
    $tpl = get_page_template_slug($id);

    $rows = carbon_get_post_meta($id, 'rb_main_builder');

    rb_builder_log('FRONT PAGE CHECK', [
        'queried_id' => $id,
        'template'   => $tpl,
        'rows_type'  => gettype($rows),
        'rows_count' => is_array($rows) ? count($rows) : null,
        'rows_head'  => is_array($rows) ? array_slice($rows, 0, 2) : $rows,
    ]);
}, 99);
add_action('wp', function () {
    if (!is_front_page()) return;
    if (!function_exists('carbon_get_post_meta')) return;

    $id = get_queried_object_id();

    $keys = ['rb_main_builder', 'rb_main_builder2', 'rb_main_builder_blocks'];
    foreach ($keys as $k) {
        $v = carbon_get_post_meta($id, $k);
        rb_builder_log('META KEY CHECK', [
            'id' => $id,
            'key' => $k,
            'type' => gettype($v),
            'count' => is_array($v) ? count($v) : null,
        ]);
    }
}, 100);

// ВРЕМЕННО: показываем прямую ссылку на услугу
add_action('admin_notices', function() {
    if (!current_user_can('manage_options')) return;
    
    // Ищем первую услугу
    $services = get_posts([
        'post_type' => 'service',
        'posts_per_page' => 1,
        'post_status' => 'publish'
    ]);
    
    if (!empty($services)) {
        $url = get_permalink($services[0]->ID);
        echo '<div class="notice notice-success is-dismissible">';
        echo '<p><strong>Ссылка на услугу для теста микроразметки:</strong></p>';
        echo '<p><a href="' . esc_url($url) . '" target="_blank">' . esc_html($url) . '</a></p>';
        echo '<p>Post type: service, ID: ' . $services[0]->ID . ', Title: ' . get_the_title($services[0]->ID) . '</p>';
        echo '</div>';
    } else {
        echo '<div class="notice notice-error"><p>Услуги (post_type=service) не найдены!</p></div>';
    }
});
add_filter( 'wpseo_json_ld_output', '__return_false' );

/**
 * Рендер конструктора главной по позиции (pos).
 */
function rb_main_builder_render($pos) {
    $page_id = (int) get_option('page_on_front');
    if (!$page_id) $page_id = get_queried_object_id();

    $rows = function_exists('carbon_get_post_meta') ? carbon_get_post_meta($page_id, 'rb_main_builder') : null;

    rb_builder_log('RENDER ROWS RAW', [
        'type' => gettype($rows),
        'count' => is_array($rows) ? count($rows) : null,
        'sample' => (is_array($rows) && !empty($rows)) ? array_slice($rows, 0, 3) : $rows
    ]);

    if (!is_array($rows) || empty($rows)) {
        return;
    }

    foreach ($rows as $i => $row) {
        $rowType = $row['_type'] ?? null;
        $rowPos  = $row['pos'] ?? null;
        $code    = $row['code'] ?? null;

        rb_builder_log('RENDER ROW', [
            'i' => $i,
            '_type' => $rowType,
            'row_pos' => $rowPos,
            'need_pos' => $pos,
            'pos_equal' => ($rowPos === $pos) ? 1 : 0,
            'code' => $code
        ]);

        if ($rowPos !== $pos) continue;

        if ($rowType === 'shortcode') {

            $out = do_shortcode((string)$code);

            rb_builder_log('RENDER SHORTCODE OUT', [
                'len' => strlen((string)$out),
                'head' => mb_substr(strip_tags((string)$out), 0, 120)
            ]);

            echo $out;
        }
        elseif ($rowType === 'text') {
            $content = $row['content'] ?? '';
            echo apply_filters('the_content', $content);
        }
        elseif ($rowType === 'heading') {
            $tag  = $row['tag'] ?? 'h2';
            $text = $row['text'] ?? '';
            echo '<' . esc_attr($tag) . '>' . esc_html($text) . '</' . esc_attr($tag) . '>';
        }
        elseif ($rowType === 'image') {
            $img = $row['image'] ?? '';
            if ($img) echo '<img src="'.esc_url($img).'" alt="">';
        }
    }

}
function rb_render_builder_slot( $items, $slot ) {
    if ( empty($items) || !is_array($items) ) return;

    foreach ( $items as $item ) {
        $type = $item['_type'] ?? '';
        $pos  = $item['pos']   ?? '';
        if ( $pos !== $slot ) continue;

        switch ( $type ) {
            case 'heading':
                $tag  = $item['tag']  ?? 'h2';
                $text = $item['text'] ?? '';
                if ($text) printf('<%1$s class="rb-heading">%2$s</%1$s>', esc_attr($tag), esc_html($text));
                break;

            case 'text':
                $content = $item['content'] ?? '';
                if ($content) {
                    $content = apply_filters('the_content', $content);
                    echo '<div class="rb-text-block">'. do_shortcode($content) .'</div>';
                }
                break;

            case 'shortcode':
                $code = trim($item['code'] ?? '');
                if ($code) echo '<div class="rb-shortcode-block">'. do_shortcode($code) .'</div>';
                break;

            case 'image':
                $url     = esc_url($item['image'] ?? '');
                $alt     = esc_attr($item['alt'] ?? '');
                $caption = esc_html($item['caption'] ?? '');
                $align   = sanitize_html_class($item['align'] ?? '');
                if ($url) {
                    $cls = 'rb-image-block' . ($align ? ' align-'.$align : '');
                    echo '<figure class="'. $cls .'"><img src="'.$url.'" alt="'.$alt.'" />';
                    if ($caption) echo '<figcaption>'.$caption.'</figcaption>';
                    echo '</figure>';
                }
                break;
        }
    }
}

add_action( 'wpseo_register_extra_replacements', function () {

    wpseo_register_var_replacement(
        '%%custom_h1%%',
        function( $context = [] ) {
            $term_id = 0;

            if ( isset( $context['term_id'] ) ) {
                $term_id = (int) $context['term_id'];
            }
            elseif ( is_admin() && isset( $_GET['tag_ID'] ) ) {
                $term_id = (int) $_GET['tag_ID'];
            }
            else {
                $obj = get_queried_object();
                if ( $obj && isset( $obj->term_id ) ) {
                    $term_id = (int) $obj->term_id;
                }
            }

            if ( ! $term_id ) {
                return '';
            }

           
            return (string) get_term_meta( $term_id, 'custom_field_key', true );
        },
        'term',
        'Пользовательский H1 из метаполя термина (custom_field_key).'
    );
});

add_action('admin_enqueue_scripts', function () {

    $js = <<<JS
    (function(){
      function refreshAssociations(){
        document.querySelectorAll('.cf-association').forEach(function(el){
          // Carbon Fields слушает эти события для перерисовки выбранных элементов
          el.dispatchEvent(new Event('crb-refresh', { bubbles: true }));
          el.dispatchEvent(new Event('change', { bubbles: true }));
        });
      }

      window.addEventListener('carbon-fields-register-fields', function(){
        setTimeout(refreshAssociations, 300);
      });

      window.addEventListener('load', function(){
        setTimeout(refreshAssociations, 600);
      });
    })();
    JS;
    wp_add_inline_script('carbon-fields-boot', $js, 'after');
});

add_action('admin_enqueue_scripts', function ($hook) {
    if (!isset($_GET['page']) || !in_array($_GET['page'], ['olimp-search','olimp-search-synonyms','olimp-search-log'], true)) return;

    wp_enqueue_script('jquery');


    wp_register_script(
        'jquery-ui-sortable-cdn',
        'https://code.jquery.com/ui/1.13.2/jquery-ui.min.js',
        ['jquery'], 
        '1.13.2',
        true 
    );
    wp_enqueue_script('jquery-ui-sortable-cdn');


    wp_enqueue_style(
        'jquery-ui-base',
        'https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css',
        [],
        '1.13.2'
    );


    $inline = 'jQuery(function($){ if ($.fn.sortable){ $("#post-type-priority").sortable({update:function(){var a=[];$("#post-type-priority .pt-item").each(function(){a.push($(this).data("pt"))});$("#post_type_priority_input").val(a.join(","));}});} });';
    wp_add_inline_script('jquery-ui-sortable-cdn', $inline);
});
		add_action('wp_print_scripts', function(){
    global $wp_scripts;
    if (is_user_logged_in()) {
        echo "<script>console.log('ENQUEUED:', ".json_encode(wp_list_pluck($wp_scripts->queue, null)).");</script>";
    }
});


add_action('wp_enqueue_scripts', function () {
    wp_enqueue_script('jquery');


    wp_register_script('olimp-suggest-bind', false, ['jquery'], null, true);
    wp_localize_script('olimp-suggest-bind', 'OlimpSuggest', [
        'ajaxUrl'  => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('search_nonce'),
        'minLen'   => 2,
        'delay'    => 200,

        'selectors'=> [
            'input'    => '#smart-title-search-input1',
            'list'     => 'ul.title-search-result',
            'preloader'=> '#smart-title-search_preloader_item'
        ]
    ]);

    $js = <<<JS
jQuery(function($){
  var cfg = window.OlimpSuggest || {};
  var \$inp = $(cfg.selectors.input);
  var \$list = $(cfg.selectors.list);
  var \$pre = $(cfg.selectors.preloader);

  if (!\$inp.length || !\$list.length) return;

  // Если какой-то другой автокомплит уже навешан — отключим его, чтобы не конфликтовал
  \$inp.off('.ui-autocomplete').removeClass('ui-autocomplete-input');

  var timer = null;
  function render(items){
    // Ожидаемый вашей темой HTML:
    // <li class="search-result-item"><a class="search-result-link" href="..."><span class="search-result-title">...</span><p class="search-result-text"></p></a></li>
    var html = '';
    for (var i=0;i<items.length;i++) {
      var it = items[i];
      var url = $('<div>').text(it.url).html();
      var ttl = $('<div>').text(it.label).html();
      html += '<li class="search-result-item">'
            +   '<a class="search-result-link" href="'+url+'">'
            +     '<span class="search-result-title">'+ttl+'</span>'
            +     '<p class="search-result-text"></p>'
            +   '</a>'
            + '</li>';
    }
    \$list.html(html);
    if (items.length) {
      \$list.addClass('show-search'); // если тема так показывает список
    } else {
      \$list.removeClass('show-search');
    }
  }

  function suggest(q){
    $.ajax({
      url: cfg.ajaxUrl,
      method: 'POST',
      dataType: 'json',
      data: { action:'olimp_search_suggest', q:q, nonce:cfg.nonce },
      beforeSend: function(){ \$pre.addClass('view'); },
      complete:   function(){ \$pre.removeClass('view'); }
    }).done(function(resp){
      if (!resp || !resp.success || !resp.data) { render([]); return; }
      render(resp.data.results || []);
    }).fail(function(){ render([]); });
  }

  \$inp.on('input', function(){
    var q = $.trim(\$(this).val());
    if (q.length < (cfg.minLen||2)) { render([]); return; }
    clearTimeout(timer);
    timer = setTimeout(function(){ suggest(q); }, cfg.delay||200);
  });

  // UX: скрывать список вне фокуса
  $(document).on('click', function(e){
    if (!$(e.target).closest(cfg.selectors.list).length &&
        !$(e.target).closest(cfg.selectors.input).length) {
      \$list.removeClass('show-search');
    }
  });
  \$inp.on('focus', function(){
    if (\$list.children().length) \$list.addClass('show-search');
  });
});
JS;
    wp_add_inline_script('olimp-suggest-bind', $js);
    wp_enqueue_script('olimp-suggest-bind');
});
// Скрываем метабоксы для конкретного шаблона
add_action('add_meta_boxes', function() {
    global $post;
    
    if ($post && $post->post_type === 'page') {
        $template = get_page_template_slug($post->ID);
        
        if ($template === 'template-page/page-competations.php') {
            remove_meta_box('submitdiv', 'page', 'side');
            remove_meta_box('pageparentdiv', 'page', 'side');
            remove_meta_box('commentstatusdiv', 'page', 'normal');
            remove_meta_box('slugdiv', 'page', 'normal');
            remove_meta_box('authordiv', 'page', 'normal');
            remove_meta_box('postexcerpt', 'page', 'normal');
            remove_meta_box('trackbacksdiv', 'page', 'normal');
            remove_meta_box('postcustom', 'page', 'normal');
        }
    }
});


function rb_register_styles() {

	wp_enqueue_style( 'app-style',  get_stylesheet_directory_uri() . '/assets/css/rb-styles.css', array(), '1.1.2' );
	wp_enqueue_style( 'swiper-style',  get_stylesheet_directory_uri() . '/assets/css/swiper-bundle.min.css' );
	wp_enqueue_style( 'popup-style',  get_stylesheet_directory_uri() . '/assets/css/magnific-popup.css' );
	wp_enqueue_style( 'fancybox-style',  get_stylesheet_directory_uri() . '/assets/css/fancybox.min.css' );
	wp_enqueue_style( 'main-style', get_stylesheet_uri(), array( 'app-style', 'swiper-style', 'popup-style', 'fancybox' ) );


	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-ui-autocomplete' );


	if ( is_page_template( 'template-page/page-vychet.php' ) ) {

		wp_enqueue_script( 'air-datepicker', get_stylesheet_directory_uri() . '/assets/js/air-datepicker.js', array( 'jquery' ), null, true );
		wp_enqueue_style( 'air-datepicker-style',  get_stylesheet_directory_uri() . '/assets/css/air-datepicker.css' );

	}

	// if( is_singular('review') || is_page_template( 'template-page/page-about.php' ) ){
	// 	wp_enqueue_script( 'fancybox-js', get_stylesheet_directory_uri() . '/assets/js/fancybox.umd.js' );
	// }
	wp_enqueue_script( 'inputmask', get_stylesheet_directory_uri() . '/assets/js/jquery.inputmask.min.js', array( 'jquery' ), null, true );
	wp_enqueue_script( 'popup', get_stylesheet_directory_uri() . '/assets/js/jquery.magnific-popup.min.js', array( 'jquery' ), null, true );
	wp_enqueue_script( 'sticky', get_stylesheet_directory_uri() . '/assets/js/sticksy.min.js', array( 'jquery' ), null, true );
	wp_enqueue_script( 'swiper', get_stylesheet_directory_uri() . '/assets/js/swiper-bundle.min.js', array( 'jquery' ), null, true );
	wp_enqueue_script( 'fancybox', get_stylesheet_directory_uri() . '/assets/js/fancybox.min.js', array( 'jquery' ), null, true );
	wp_enqueue_script( 'script', get_stylesheet_directory_uri() . '/assets/js/addon.js', array( 'sticky', 'swiper', 'inputmask', 'popup', 'fancybox' ), null, true );



	wp_localize_script('script', 'ajax_path', array(
		'url' => admin_url('admin-ajax.php'),
));

}

register_nav_menus([
'uslugi_anchor_menu' => 'Меню якорей на странице "Услуги"'
]);

class Walker_Anchor_Only extends Walker_Nav_Menu {
function start_lvl( &$output, $depth = 0, $args = null ) {}
function end_lvl( &$output, $depth = 0, $args = null ) {}

function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
$class = 'class="rb-link"';
$output .= '<a ' . $class . ' href="' . esc_url( $item->url ) . '">' . esc_html( $item->title ) . '</a>';
}

function end_el( &$output, $item, $depth = 0, $args = null ) {}
}

add_action( 'wp_enqueue_scripts', 'rb_register_styles' );

add_action( 'admin_enqueue_scripts', 'rb_add_scripts_to_admin' );

function rb_add_scripts_to_admin(){

if( ! current_user_can( 'administrator' ) ) {

wp_enqueue_style( 'admin-style', get_stylesheet_directory_uri() .'/assets/css/admin-style.css' );

}

}

add_action( 'after_setup_theme', function(){
register_nav_menus(
array(
'main_menu' 				=> 'Мain menu',
// 'main_menu_mob' 		=> 'Mobile menu',
'footer_menu' 			=> 'Footer menu',
'footer_menu_2' 		=> 'Footer menu 2',
'footer_menu_3' 		=> 'Footer menu 3',
'footer_menu_4' 		=> 'Footer menu 4',
)
);

add_theme_support( 'post-thumbnails' );

foreach ( get_intermediate_image_sizes() as $size ) {
if ( !in_array( $size, array( '1536×1536', 'medium_large', '2048×2048' ) ) ) {
remove_image_size( $size );
}
}

add_image_size('rb_medium_2x', 600, 600);
add_image_size('rb_standart', 660, 380);
add_image_size('rb_standart_2x', 1320, 760);

add_image_size('rb_news', 310, 420);
add_image_size('rb_news_2x', 620, 840);

add_image_size('rb_programms', 320, 650);
add_image_size('rb_programms_2x', 640, 1300);

add_image_size('rb_blog', 900, 450);
add_image_size('rb_blog_2x', 1800, 900);


} );


function cleanup_attachment_link( $link ) {
return;
}
add_filter( 'attachment_link', 'cleanup_attachment_link' );


function cleanup_default_rewrite_rules( $rules ) {
foreach ( $rules as $regex => $query ) {
if ( strpos( $regex, 'attachment' ) || strpos( $query, 'attachment' ) ) {
unset( $rules[ $regex ] );
}
}

return $rules;
}
add_filter( 'rewrite_rules_array', 'cleanup_default_rewrite_rules' );



add_action('do_feed', 'wpb_disable_feed', 1);
add_action('do_feed_rdf', 'wpb_disable_feed', 1);
add_action('do_feed_rss', 'wpb_disable_feed', 1);
add_action('do_feed_rss2', 'wpb_disable_feed', 1);
add_action('do_feed_atom', 'wpb_disable_feed', 1);
add_action('do_feed_rss2_comments', 'wpb_disable_feed', 1);
add_action('do_feed_atom_comments', 'wpb_disable_feed', 1);

remove_action( 'wp_head', 'wp_shortlink_wp_head' );

remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wp_generator' );

add_action('init', 'remove_oembed_discovery_links');
function remove_oembed_discovery_links() {
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('wp_head', 'wp_oembed_add_host_js');
}

add_action('init', 'remove_rest_api_link');
function remove_rest_api_link() {
remove_action('wp_head', 'rest_output_link_wp_head');
}

add_action('init', 'remove_feed_links');
function remove_feed_links() {
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'feed_links_extra', 3);
}

add_filter('wpseo_next_rel_link', '__return_false');
add_filter('wpseo_prev_rel_link', '__return_false');

function handle_last_modified_and_304() {
if (!is_singular() && !is_archive()) {
return;
}

global $post;
if (!$post) {
return;
}

$last_modified = gmdate("D, d M Y H:i:s", strtotime($post->post_modified)) . " GMT";

header("Last-Modified: $last_modified");

if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
$if_modified_since = strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']);
$post_modified_time = strtotime($post->post_modified);

if ($if_modified_since >= $post_modified_time) {
header("HTTP/1.1 304 Not Modified");
exit;
}
}
}
add_action('send_headers', 'handle_last_modified_and_304', 1);


function fix_category_pagination_rewrite() {
add_rewrite_rule(
'^([^/]+)/page/([0-9]+)/?$',
'index.php?category_name=$matches[1]&paged=$matches[2]',
'top'
);
}
add_action('init', 'fix_category_pagination_rewrite');

/* Добавление виджетов */
function olimp5_register_header_widget() {
    register_sidebar( array(
        'name'          => 'Header Widget Area',
'id'            => 'header-widget',
'before_widget' => '<div class="header-widget">',
  'after_widget'  => '</div>',
'before_title'  => '<h3 class="widget-title">',
  'after_title'   => '</h3>',
) );
}
add_action( 'widgets_init', 'olimp5_register_header_widget' );


/* Шорткод с кнопкой для статей со всплывающим модальным окном */
function modal_button_shortcode() {
    return '<div class="button__modal">
				<a href="#" class="js-open-modal" data-modal="1">Записаться</a>
			</div>';
}
add_shortcode('modal_button', 'modal_button_shortcode');

/* Шорткод кнопки с настраиваемой в админке ссылкой */
function link_button_shortcode($atts) {
    // Атрибуты по умолчанию
    $atts = shortcode_atts(
        array(
            'url' => '#', // Если ссылка не указана
            'text' => 'Подробно' // Текст по умолчанию
        ), 
        $atts,
        'link_button'
    );

    return '<div class="button__link">
                <a href="' . esc_url($atts['url']) . '" class="b-link">' . esc_html($atts['text']) . '</a>
            </div>';
}
add_shortcode('link_button', 'link_button_shortcode');


/* Вывод популярных услуг */

function rb_output_services_slider_by_term($term_id) {
$services = get_posts([
'post_type'      => 'service',
'post_status'    => 'publish',
'posts_per_page' => -1,
'tax_query'      => [[
'taxonomy' => 'services',
'field'    => 'term_id',
'terms'    => $term_id,
]],
]);

if (empty($services)) {
return;
}

echo '<div class="rb-container" id="y-serv">';
  echo '  <div class="feedback-block">';
    echo '    <div class="rb-page-header"><h2>Все услуги направления</h2></div>';
    echo '    <div class="feedback-arrows">';
      echo '      <a href="#" class="serv-arrow-prev" tabindex="0" role="button" aria-label="Previous slide">';
        echo '        <svg width="32" height="32" viewBox="0 0 32 32" fill="none">';
          echo '          <rect width="32" height="32" rx="4" fill="#F3951D"></rect>';
          echo '          <path d="M19 22L12.5 16L18.5 10" stroke="white"></path>';
          echo '        </svg>';
        echo '      </a>';
      echo '      <a href="#" class="serv-arrow-next" tabindex="0" role="button" aria-label="Next slide">';
        echo '        <svg width="32" height="32" viewBox="0 0 32 32" fill="none">';
          echo '          <rect width="32" height="32" rx="4" transform="matrix(-1 0 0 1 32 0)" fill="#F3951D"></rect>';
          echo '          <path d="M13 22L19.5 16L13.5 10" stroke="white"></path>';
          echo '        </svg>';
        echo '      </a>';
      echo '    </div>';
    echo '  </div>';

  echo '  <div class="sl-swiper-serv swiper">';
    echo '    <div class="swiper-wrapper" id="swiper-wrapper-services">';

      foreach ($services as $service) {
      $thumb = has_post_thumbnail($service->ID)
      ? get_the_post_thumbnail($service->ID, 'medium', ['class' => 'rb-img-cover', 'loading' => 'lazy'])
      : '<img class="rb-img-cover" src="' . get_stylesheet_directory_uri() . '/assets/img/nopic.png" alt="">';

      echo '<div class="swiper-slide">';
        echo '  <div class="rb-service__item rb-service__item_h">';
          echo '    <picture class="col-12 rb-service__item-image">' . $thumb . '</picture>';
          echo '    <div class="rb-service__item-info">';
            echo '      <a href="' . esc_url(get_permalink($service->ID)) . '" class="rb-service__item-title">' . esc_html(get_the_title($service->ID)) . '</a>';
            echo '      <p class="rb-service__item-desc">' . esc_html(get_the_excerpt($service->ID)) . '</p>';
            echo '      <div class="rb-service__item-bot">';
              echo '        <span class="rb-service__item-btn rb-button__orange js-open-modal"';
              echo '              data-service="' . esc_attr(get_the_title($service->ID)) . '">Записаться на прием</span>';
              echo '      </div>';
            echo '    </div>';
          echo '  </div>';
        echo '</div>';
      }

      echo '    </div>';
    echo '    <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>';
    echo '  </div>';
  echo '</div>';
}


/**
 * Хлебные крошки для WordPress с гибкими настройками
 */


add_action('admin_menu', 'breadcrumbs_add_admin_menu');
function breadcrumbs_add_admin_menu() {
    add_menu_page(
        'Хлебные крошки',
        'Хлебные крошки',
        'manage_options',
        'breadcrumbs-settings',
        'breadcrumbs_admin_page',
        'dashicons-share-alt2',
        30
    );
}

add_action('admin_init', 'breadcrumbs_settings_init');
function breadcrumbs_settings_init() {
    register_setting('breadcrumbs_settings', 'breadcrumbs_options');
    
    add_settings_section(
        'breadcrumbs_main_section',
        'Основные настройки',
        'breadcrumbs_section_callback',
        'breadcrumbs_settings'
    );
    
    add_settings_field(
        'enabled',
        'Включить хлебные крошки',
        'breadcrumbs_enabled_callback',
        'breadcrumbs_settings',
        'breadcrumbs_main_section'
    );
    
    add_settings_field(
        'separator',
        'Разделитель',
        'breadcrumbs_separator_callback',
        'breadcrumbs_settings',
        'breadcrumbs_main_section'
    );
    
    add_settings_field(
        'home_text',
        'Текст главной страницы',
        'breadcrumbs_home_text_callback',
        'breadcrumbs_settings',
        'breadcrumbs_main_section'
    );
    
    add_settings_field(
        'show_on_home',
        'Показывать на главной странице',
        'breadcrumbs_show_on_home_callback',
        'breadcrumbs_settings',
        'breadcrumbs_main_section'
    );
    
    add_settings_field(
        'container_class',
        'CSS класс контейнера',
        'breadcrumbs_container_class_callback',
        'breadcrumbs_settings',
        'breadcrumbs_main_section'
    );
    
    add_settings_field(
        'structured_data',
        'Включить структурированные данные (JSON-LD)',
        'breadcrumbs_structured_data_callback',
        'breadcrumbs_settings',
        'breadcrumbs_main_section'
    );
    
    add_settings_field(
        'post_type_parents',
        'Родительские страницы для типов записей',
        'breadcrumbs_post_type_parents_callback',
        'breadcrumbs_settings',
        'breadcrumbs_main_section'
    );
    
    add_settings_field(
        'category_pages',
        'Связь категорий со страницами',
        'breadcrumbs_category_pages_callback',
        'breadcrumbs_settings',
        'breadcrumbs_main_section'
    );
    
    add_settings_field(
        'page_parents',
        'Родительские страницы для отдельных страниц',
        'breadcrumbs_page_parents_callback',
        'breadcrumbs_settings',
        'breadcrumbs_main_section'
    );
    
    add_settings_field(
        'hidden_categories',
        'Скрытые категории в хлебных крошках',
        'breadcrumbs_hidden_categories_callback',
        'breadcrumbs_settings',
        'breadcrumbs_main_section'
    );
}

function breadcrumbs_section_callback() {
    echo '<p>Настройте отображение хлебных крошек на вашем сайте.</p>';
}

function breadcrumbs_enabled_callback() {
    $options = get_option('breadcrumbs_options');
    $enabled = isset($options['enabled']) ? $options['enabled'] : 1;
    echo '<input type="checkbox" name="breadcrumbs_options[enabled]" value="1" ' . checked(1, $enabled, false) . '>';
}

function breadcrumbs_separator_callback() {
    $options = get_option('breadcrumbs_options');
    $separator = isset($options['separator']) ? $options['separator'] : ' → ';
    echo '<input type="text" name="breadcrumbs_options[separator]" value="' . esc_attr($separator) . '" class="regular-text">';
    echo '<p class="description">Символы разделения между элементами хлебных крошек</p>';
}

function breadcrumbs_home_text_callback() {
    $options = get_option('breadcrumbs_options');
    $home_text = isset($options['home_text']) ? $options['home_text'] : 'Главная';
    echo '<input type="text" name="breadcrumbs_options[home_text]" value="' . esc_attr($home_text) . '" class="regular-text">';
}

function breadcrumbs_show_on_home_callback() {
    $options = get_option('breadcrumbs_options');
    $show_on_home = isset($options['show_on_home']) ? $options['show_on_home'] : 0;
    echo '<input type="checkbox" name="breadcrumbs_options[show_on_home]" value="1" ' . checked(1, $show_on_home, false) . '>';
}

function breadcrumbs_container_class_callback() {
    $options = get_option('breadcrumbs_options');
    $container_class = isset($options['container_class']) ? $options['container_class'] : 'breadcrumbs';
    echo '<input type="text" name="breadcrumbs_options[container_class]" value="' . esc_attr($container_class) . '" class="regular-text">';
    echo '<p class="description">CSS класс для стилизации контейнера хлебных крошек</p>';
}

function breadcrumbs_structured_data_callback() {
    $options = get_option('breadcrumbs_options');
    $structured_data = isset($options['structured_data']) ? $options['structured_data'] : 1;
    echo '<input type="checkbox" name="breadcrumbs_options[structured_data]" value="1" ' . checked(1, $structured_data, false) . '>';
    echo '<p class="description">Добавляет разметку для поисковых систем</p>';
}

function breadcrumbs_post_type_parents_callback() {
    $options = get_option('breadcrumbs_options');
    $post_type_parents = isset($options['post_type_parents']) ? $options['post_type_parents'] : array();
    
    $post_types = get_post_types(array('public' => true, '_builtin' => false), 'objects');
    $pages = get_pages();
    
    echo '<table class="form-table">';
    foreach ($post_types as $post_type) {
        echo '<tr>';
        echo '<td><label for="post_type_parent_' . $post_type->name . '">' . esc_html($post_type->labels->name) . ':</label></td>';
        echo '<td>';
        echo '<select name="breadcrumbs_options[post_type_parents][' . $post_type->name . ']" id="post_type_parent_' . $post_type->name . '">';
        echo '<option value="">-- Выберите родительскую страницу --</option>';
        
        foreach ($pages as $page) {
            $selected = isset($post_type_parents[$post_type->name]) && $post_type_parents[$post_type->name] == $page->ID ? 'selected' : '';
            $indent = str_repeat('— ', count(get_ancestors($page->ID, 'page')));
            echo '<option value="' . $page->ID . '" ' . $selected . '>' . $indent . esc_html($page->post_title) . '</option>';
        }
        
        echo '</select>';
        echo '</td>';
        echo '</tr>';
    }
    echo '</table>';
    echo '<p class="description">Выберите родительскую страницу для каждого типа записи. Это страница будет добавлена в хлебные крошки.</p>';
}

function breadcrumbs_category_pages_callback() {
    $options = get_option('breadcrumbs_options');
    $category_pages = isset($options['category_pages']) ? $options['category_pages'] : array();
    
    $taxonomies = get_taxonomies(array('public' => true), 'objects');
    $pages = get_pages();
    
    echo '<div id="category-pages-settings">';
    
    foreach ($taxonomies as $taxonomy) {
        if ($taxonomy->hierarchical) {
            echo '<h4>' . esc_html($taxonomy->labels->name) . ' (' . $taxonomy->name . ')</h4>';
            
            $terms = get_terms(array(
                'taxonomy' => $taxonomy->name,
                'hide_empty' => false,
                'orderby' => 'name'
            ));
            
            if (!empty($terms) && !is_wp_error($terms)) {
                echo '<table class="form-table" style="margin-bottom: 20px;">';
                foreach ($terms as $term) {
                    echo '<tr>';
                    echo '<td style="width: 200px;"><label for="category_page_' . $term->term_id . '">' . esc_html($term->name) . ':</label></td>';
                    echo '<td>';
                    echo '<select name="breadcrumbs_options[category_pages][' . $term->term_id . ']" id="category_page_' . $term->term_id . '">';
                    echo '<option value="">-- Не связывать --</option>';
                    
                    foreach ($pages as $page) {
                        $selected = isset($category_pages[$term->term_id]) && $category_pages[$term->term_id] == $page->ID ? 'selected' : '';
                        $indent = str_repeat('— ', count(get_ancestors($page->ID, 'page')));
                        echo '<option value="' . $page->ID . '" ' . $selected . '>' . $indent . esc_html($page->post_title) . '</option>';
                    }
                    
                    echo '</select>';
                    echo '</td>';
                    echo '</tr>';
                }
                echo '</table>';
            }
        }
    }
    
    echo '</div>';
    echo '<p class="description">Свяжите категории с конкретными страницами. Например, категория "Запись на прием к гинекологу" может быть связана со страницей "Консультативное отделение".</p>';
}

function breadcrumbs_page_parents_callback() {
    $options = get_option('breadcrumbs_options');
    $page_parents = isset($options['page_parents']) ? $options['page_parents'] : array();
    
    $pages = get_pages(array('sort_column' => 'post_title'));
    
    echo '<div id="page-parents-settings">';
    echo '<p><strong>Настройте дополнительные родительские страницы:</strong></p>';
    echo '<table class="form-table">';
    
    foreach ($pages as $page) {
        $indent = str_repeat('— ', count(get_ancestors($page->ID, 'page')));
        echo '<tr>';
        echo '<td style="width: 300px;"><label for="page_parent_' . $page->ID . '">' . $indent . esc_html($page->post_title) . ':</label></td>';
        echo '<td>';
        echo '<select name="breadcrumbs_options[page_parents][' . $page->ID . ']" id="page_parent_' . $page->ID . '">';
        echo '<option value="">-- Не добавлять родительскую --</option>';
        
        foreach ($pages as $parent_page) {
            if ($parent_page->ID != $page->ID) {
                $selected = isset($page_parents[$page->ID]) && $page_parents[$page->ID] == $parent_page->ID ? 'selected' : '';
                $parent_indent = str_repeat('— ', count(get_ancestors($parent_page->ID, 'page')));
                echo '<option value="' . $parent_page->ID . '" ' . $selected . '>' . $parent_indent . esc_html($parent_page->post_title) . '</option>';
            }
        }
        
        echo '</select>';
        echo '</td>';
        echo '</tr>';
    }
    
    echo '</table>';
    echo '</div>';
    echo '<p class="description">Добавьте дополнительную родительскую страницу в хлебные крошки. Например, для страницы "Консультативное отделение" можно добавить "Услуги центра".</p>';
}

function breadcrumbs_hidden_categories_callback() {
    $options = get_option('breadcrumbs_options');
    $hidden_categories = isset($options['hidden_categories']) ? $options['hidden_categories'] : array();
    
    $taxonomies = get_taxonomies(array(), 'objects');
    
    echo '<div id="hidden-categories-settings">';
    
    foreach ($taxonomies as $taxonomy) {
        if ($taxonomy->hierarchical && !in_array($taxonomy->name, array('nav_menu', 'link_category', 'post_format'))) {
            echo '<h4>' . esc_html($taxonomy->labels->name) . ' (' . $taxonomy->name . ')</h4>';
            
            $terms = get_terms(array(
                'taxonomy' => $taxonomy->name,
                'hide_empty' => false,
                'orderby' => 'name'
            ));
            
            if (!empty($terms) && !is_wp_error($terms)) {
                echo '<div style="margin-bottom: 20px;">';
                foreach ($terms as $term) {
                    $checked = isset($hidden_categories[$term->term_id]) && $hidden_categories[$term->term_id] ? 'checked' : '';
                    echo '<label style="display: block; margin: 5px 0;">';
                    echo '<input type="checkbox" name="breadcrumbs_options[hidden_categories][' . $term->term_id . ']" value="1" ' . $checked . '> ';
                    echo esc_html($term->name);
                    echo '</label>';
                }
                echo '</div>';
            }
        }
    }
    
    echo '</div>';
    echo '<p class="description">Отметьте категории, которые нужно скрыть из хлебных крошек. Например, если вы не хотите показывать промежуточную категорию "Оздоровительные".</p>';
}

function breadcrumbs_admin_page() {
    ?>
    <div class="wrap">
        <h1>Настройки хлебных крошек</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('breadcrumbs_settings');
            do_settings_sections('breadcrumbs_settings');
            submit_button();
            ?>
        </form>
        
        <div class="card">
            <h2>Как использовать</h2>
            <p>Для вывода хлебных крошек в шаблоне используйте:</p>
            <code>&lt;?php display_breadcrumbs(); ?&gt;</code>
            
            <h3>Или с параметрами:</h3>
            <code>&lt;?php display_breadcrumbs(array('separator' =&gt; ' / ', 'home_text' =&gt; 'Home')); ?&gt;</code>
        </div>
    </div>
    <?php
}

function display_breadcrumbs($args = array()) {
    $options = get_option('breadcrumbs_options');
    
    if (false === $options) {
        $options = array(
            'enabled' => 1,
            'separator' => ' → ',
            'home_text' => 'Главная',
            'show_on_home' => 0,
            'container_class' => 'breadcrumbs',
            'structured_data' => 1
        );
        update_option('breadcrumbs_options', $options);
    }
    
    if (isset($options['enabled']) && empty($options['enabled'])) {
        return;
    }
    
    $defaults = array(
        'separator' => isset($options['separator']) ? $options['separator'] : ' → ',
        'home_text' => isset($options['home_text']) ? $options['home_text'] : 'Главная',
        'show_on_home' => isset($options['show_on_home']) ? $options['show_on_home'] : 0,
        'container_class' => isset($options['container_class']) ? $options['container_class'] : 'breadcrumbs',
        'structured_data' => isset($options['structured_data']) ? $options['structured_data'] : 1
    );
    
    $args = wp_parse_args($args, $defaults);
    
    if ((is_home() && is_front_page()) || (!is_home() && is_front_page())) {
        if (!$args['show_on_home']) {
            return;
        }
    }
    
    $breadcrumbs = array();
    $structured_data = array();
    
    $home_url = home_url('/');
    $breadcrumbs[] = '<a href="' . $home_url . '">' . esc_html($args['home_text']) . '</a>';
    
    if ($args['structured_data']) {
        $structured_data[] = array(
            '@type' => 'ListItem',
            'position' => 1,
            'name' => $args['home_text'],
            'item' => $home_url
        );
    }
    
    $position = 2;
    
    $is_taxonomy_page = (is_category() || is_tag() || is_tax());
    if ($is_taxonomy_page) {
        $current_term = get_queried_object();
        if ($current_term) {
            $temp_check = true;
        }
    }
    
    if (is_category() || is_tag() || is_tax()) {
        $term = get_queried_object();
        
        if (is_tax()) {
            $taxonomy = get_taxonomy($term->taxonomy);
            $options = get_option('breadcrumbs_options');
            $category_pages = isset($options['category_pages']) ? $options['category_pages'] : array();
            $category_page_id = isset($category_pages[$term->term_id]) ? $category_pages[$term->term_id] : null;
            
            if ($category_page_id) {
                $page_parents = isset($options['page_parents']) ? $options['page_parents'] : array();
                $category_page_parent_id = isset($page_parents[$category_page_id]) ? $page_parents[$category_page_id] : null;
                
                if ($category_page_parent_id) {
                    $parent_ancestors = get_ancestors($category_page_parent_id, 'page');
                    $parent_ancestors = array_reverse($parent_ancestors);
                    
                    foreach ($parent_ancestors as $ancestor_id) {
                        $ancestor = get_post($ancestor_id);
                        $ancestor_link = get_permalink($ancestor_id);
                        $breadcrumbs[] = '<a href="' . $ancestor_link . '">' . esc_html($ancestor->post_title) . '</a>';
                        
                        if ($args['structured_data']) {
                            $structured_data[] = array(
                                '@type' => 'ListItem',
                                'position' => $position++,
                                'name' => $ancestor->post_title,
                                'item' => $ancestor_link
                            );
                        }
                    }
                    
                    $category_page_parent = get_post($category_page_parent_id);
                    $category_page_parent_link = get_permalink($category_page_parent_id);
                    $breadcrumbs[] = '<a href="' . $category_page_parent_link . '">' . esc_html($category_page_parent->post_title) . '</a>';
                    
                    if ($args['structured_data']) {
                        $structured_data[] = array(
                            '@type' => 'ListItem',
                            'position' => $position++,
                            'name' => $category_page_parent->post_title,
                            'item' => $category_page_parent_link
                        );
                    }
                } else {
                    $page_ancestors = get_ancestors($category_page_id, 'page');
                    $page_ancestors = array_reverse($page_ancestors);
                    
                    foreach ($page_ancestors as $ancestor_id) {
                        $ancestor = get_post($ancestor_id);
                        $ancestor_link = get_permalink($ancestor_id);
                        $breadcrumbs[] = '<a href="' . $ancestor_link . '">' . esc_html($ancestor->post_title) . '</a>';
                        
                        if ($args['structured_data']) {
                            $structured_data[] = array(
                                '@type' => 'ListItem',
                                'position' => $position++,
                                'name' => $ancestor->post_title,
                                'item' => $ancestor_link
                            );
                        }
                    }
                }
                
                $category_page = get_post($category_page_id);
                $category_page_link = get_permalink($category_page_id);
                $breadcrumbs[] = '<a href="' . $category_page_link . '">' . esc_html($category_page->post_title) . '</a>';
                
                if ($args['structured_data']) {
                    $structured_data[] = array(
                        '@type' => 'ListItem',
                        'position' => $position++,
                        'name' => $category_page->post_title,
                        'item' => $category_page_link
                    );
                }
            } elseif ($taxonomy && !empty($taxonomy->object_type)) {
                $post_type = $taxonomy->object_type[0];
                $post_type_parents = isset($options['post_type_parents']) ? $options['post_type_parents'] : array();
                $archive_page_id = isset($post_type_parents[$post_type]) ? $post_type_parents[$post_type] : null;
                
                if ($archive_page_id) {
                    $page_ancestors = get_ancestors($archive_page_id, 'page');
                    $page_ancestors = array_reverse($page_ancestors);
                    
                    foreach ($page_ancestors as $ancestor_id) {
                        $ancestor = get_post($ancestor_id);
                        $ancestor_link = get_permalink($ancestor_id);
                        $breadcrumbs[] = '<a href="' . $ancestor_link . '">' . esc_html($ancestor->post_title) . '</a>';
                        
                        if ($args['structured_data']) {
                            $structured_data[] = array(
                                '@type' => 'ListItem',
                                'position' => $position++,
                                'name' => $ancestor->post_title,
                                'item' => $ancestor_link
                            );
                        }
                    }
                    
                    $archive_page = get_post($archive_page_id);
                    $archive_link = get_permalink($archive_page_id);
                    $breadcrumbs[] = '<a href="' . $archive_link . '">' . esc_html($archive_page->post_title) . '</a>';
                    
                    if ($args['structured_data']) {
                        $structured_data[] = array(
                            '@type' => 'ListItem',
                            'position' => $position++,
                            'name' => $archive_page->post_title,
                            'item' => $archive_link
                        );
                    }
                }
            }
        }
        
        if ($term->parent) {
            $parent_terms = get_ancestors($term->term_id, $term->taxonomy);
            $parent_terms = array_reverse($parent_terms);
            
            $options = get_option('breadcrumbs_options');
            $hidden_categories = isset($options['hidden_categories']) ? $options['hidden_categories'] : array();
            
            foreach ($parent_terms as $parent_id) {
                if (!isset($hidden_categories[$parent_id]) || !$hidden_categories[$parent_id]) {
                    $parent = get_term($parent_id, $term->taxonomy);
                    $parent_link = get_term_link($parent);
                    $breadcrumbs[] = '<a href="' . $parent_link . '">' . esc_html($parent->name) . '</a>';
                    
                    if ($args['structured_data']) {
                        $structured_data[] = array(
                            '@type' => 'ListItem',
                            'position' => $position++,
                            'name' => $parent->name,
                            'item' => $parent_link
                        );
                    }
                }
            }
        }
        
        $breadcrumbs[] = '<span class="current">' . esc_html($term->name) . '</span>';
        
        if ($args['structured_data']) {
            $structured_data[] = array(
                '@type' => 'ListItem',
                'position' => $position,
                'name' => $term->name,
                'item' => get_term_link($term)
            );
        }
        
    } elseif (is_single()) {
        $post = get_queried_object();
        
        if ($post->post_type == 'post') {
            $categories = get_the_category($post->ID);
            if ($categories) {
                $category = $categories[0];
                
                if ($category->parent) {
                    $parent_cats = get_ancestors($category->cat_ID, 'category');
                    $parent_cats = array_reverse($parent_cats);
                    
                    $options = get_option('breadcrumbs_options');
                    $hidden_categories = isset($options['hidden_categories']) ? $options['hidden_categories'] : array();
                    
                    foreach ($parent_cats as $parent_id) {
                        if (!isset($hidden_categories[$parent_id]) || !$hidden_categories[$parent_id]) {
                            $parent = get_category($parent_id);
                            $parent_link = get_category_link($parent->term_id);
                            $breadcrumbs[] = '<a href="' . $parent_link . '">' . esc_html($parent->name) . '</a>';
                            
                            if ($args['structured_data']) {
                                $structured_data[] = array(
                                    '@type' => 'ListItem',
                                    'position' => $position++,
                                    'name' => $parent->name,
                                    'item' => $parent_link
                                );
                            }
                        }
                    }
                }
                
                if (!isset($hidden_categories[$category->term_id]) || !$hidden_categories[$category->term_id]) {
                    $cat_link = get_category_link($category->term_id);
                    $breadcrumbs[] = '<a href="' . $cat_link . '">' . esc_html($category->name) . '</a>';
                    
                    if ($args['structured_data']) {
                        $structured_data[] = array(
                            '@type' => 'ListItem',
                            'position' => $position++,
                            'name' => $category->name,
                            'item' => $cat_link
                        );
                    }
                }
            }
        }
        
        if ($post->post_type != 'post' && $post->post_type != 'page') {
            $post_type_obj = get_post_type_object($post->post_type);
            
            $options = get_option('breadcrumbs_options');
            $post_type_parents = isset($options['post_type_parents']) ? $options['post_type_parents'] : array();
            $archive_page_id = isset($post_type_parents[$post->post_type]) ? $post_type_parents[$post->post_type] : null;
            
            if (!$archive_page_id && $post_type_obj) {
                $archive_page = breadcrumbs_get_page_by_title($post_type_obj->labels->name);
                if ($archive_page) {
                    $archive_page_id = $archive_page->ID;
                }
                
                if (!$archive_page_id && isset($post_type_obj->rewrite['slug'])) {
                    $archive_page = breadcrumbs_get_page_by_path($post_type_obj->rewrite['slug']);
                    if ($archive_page) {
                        $archive_page_id = $archive_page->ID;
                    }
                }
            }
            
            if ($archive_page_id) {
                $page_ancestors = get_ancestors($archive_page_id, 'page');
                $page_ancestors = array_reverse($page_ancestors);
                
                foreach ($page_ancestors as $ancestor_id) {
                    $ancestor = get_post($ancestor_id);
                    $ancestor_link = get_permalink($ancestor_id);
                    $breadcrumbs[] = '<a href="' . $ancestor_link . '">' . esc_html($ancestor->post_title) . '</a>';
                    
                    if ($args['structured_data']) {
                        $structured_data[] = array(
                            '@type' => 'ListItem',
                            'position' => $position++,
                            'name' => $ancestor->post_title,
                            'item' => $ancestor_link
                        );
                    }
                }
                
                $archive_page = get_post($archive_page_id);
                $archive_link = get_permalink($archive_page_id);
                $breadcrumbs[] = '<a href="' . $archive_link . '">' . esc_html($archive_page->post_title) . '</a>';
                
                if ($args['structured_data']) {
                    $structured_data[] = array(
                        '@type' => 'ListItem',
                        'position' => $position++,
                        'name' => $archive_page->post_title,
                        'item' => $archive_link
                    );
                }
            } else {
                if ($post_type_obj && $post_type_obj->has_archive) {
                    $archive_link = get_post_type_archive_link($post->post_type);
                    $breadcrumbs[] = '<a href="' . $archive_link . '">' . esc_html($post_type_obj->labels->name) . '</a>';
                    
                    if ($args['structured_data']) {
                        $structured_data[] = array(
                            '@type' => 'ListItem',
                            'position' => $position++,
                            'name' => $post_type_obj->labels->name,
                            'item' => $archive_link
                        );
                    }
                }
            }
            
            $taxonomies = get_object_taxonomies($post->post_type, 'objects');
            $category_used = false;
            
            foreach ($taxonomies as $taxonomy) {
                if ($taxonomy->hierarchical && $taxonomy->show_ui && !$category_used) {
                    $terms = get_the_terms($post->ID, $taxonomy->name);
                    if ($terms && !is_wp_error($terms)) {
                        $term = $terms[0];
                        
                        $options = get_option('breadcrumbs_options');
                        $category_pages = isset($options['category_pages']) ? $options['category_pages'] : array();
                        $category_page_id = isset($category_pages[$term->term_id]) ? $category_pages[$term->term_id] : null;
                        
                        if ($category_page_id) {
                            $page_parents = isset($options['page_parents']) ? $options['page_parents'] : array();
                            $category_page_parent_id = isset($page_parents[$category_page_id]) ? $page_parents[$category_page_id] : null;
                            
                            if ($category_page_parent_id) {
                                $parent_ancestors = get_ancestors($category_page_parent_id, 'page');
                                $parent_ancestors = array_reverse($parent_ancestors);
                                
                                foreach ($parent_ancestors as $ancestor_id) {
                                    $ancestor = get_post($ancestor_id);
                                    $ancestor_link = get_permalink($ancestor_id);
                                    $ancestor_title = esc_html($ancestor->post_title);
                                    
                                    $already_added = false;
                                    foreach ($breadcrumbs as $breadcrumb) {
                                        if (strpos($breadcrumb, $ancestor_title) !== false) {
                                            $already_added = true;
                                            break;
                                        }
                                    }
                                    
                                    if (!$already_added) {
                                        $breadcrumbs[] = '<a href="' . $ancestor_link . '">' . $ancestor_title . '</a>';
                                        
                                        if ($args['structured_data']) {
                                            $structured_data[] = array(
                                                '@type' => 'ListItem',
                                                'position' => $position++,
                                                'name' => $ancestor->post_title,
                                                'item' => $ancestor_link
                                            );
                                        }
                                    }
                                }
                                
                                $category_page_parent = get_post($category_page_parent_id);
                                $category_page_parent_link = get_permalink($category_page_parent_id);
                                $category_page_parent_title = esc_html($category_page_parent->post_title);
                                
                                $already_added = false;
                                foreach ($breadcrumbs as $breadcrumb) {
                                    if (strpos($breadcrumb, $category_page_parent_title) !== false) {
                                        $already_added = true;
                                        break;
                                    }
                                }
                                
                                if (!$already_added) {
                                    $breadcrumbs[] = '<a href="' . $category_page_parent_link . '">' . $category_page_parent_title . '</a>';
                                    
                                    if ($args['structured_data']) {
                                        $structured_data[] = array(
                                            '@type' => 'ListItem',
                                            'position' => $position++,
                                            'name' => $category_page_parent->post_title,
                                            'item' => $category_page_parent_link
                                        );
                                    }
                                }
                            } else {
                                $page_ancestors = get_ancestors($category_page_id, 'page');
                                $page_ancestors = array_reverse($page_ancestors);
                                
                                foreach ($page_ancestors as $ancestor_id) {
                                    $ancestor = get_post($ancestor_id);
                                    $ancestor_link = get_permalink($ancestor_id);
                                    $ancestor_title = esc_html($ancestor->post_title);
                                    
                                    $already_added = false;
                                    foreach ($breadcrumbs as $breadcrumb) {
                                        if (strpos($breadcrumb, $ancestor_title) !== false) {
                                            $already_added = true;
                                            break;
                                        }
                                    }
                                    
                                    if (!$already_added) {
                                        $breadcrumbs[] = '<a href="' . $ancestor_link . '">' . $ancestor_title . '</a>';
                                        
                                        if ($args['structured_data']) {
                                            $structured_data[] = array(
                                                '@type' => 'ListItem',
                                                'position' => $position++,
                                                'name' => $ancestor->post_title,
                                                'item' => $ancestor_link
                                            );
                                        }
                                    }
                                }
                            }
                            
                            $category_page = get_post($category_page_id);
                            $category_page_link = get_permalink($category_page_id);
                            $category_page_title = esc_html($category_page->post_title);
                            
                            $already_added = false;
                            foreach ($breadcrumbs as $breadcrumb) {
                                if (strpos($breadcrumb, $category_page_title) !== false) {
                                    $already_added = true;
                                    break;
                                }
                            }
                            
                            if (!$already_added) {
                                $breadcrumbs[] = '<a href="' . $category_page_link . '">' . $category_page_title . '</a>';
                                
                                if ($args['structured_data']) {
                                    $structured_data[] = array(
                                        '@type' => 'ListItem',
                                        'position' => $position++,
                                        'name' => $category_page->post_title,
                                        'item' => $category_page_link
                                    );
                                }
                            }
                        }
                        
                        if ($term->parent) {
                            $parent_terms = get_ancestors($term->term_id, $taxonomy->name);
                            $parent_terms = array_reverse($parent_terms);
                            
                            $options = get_option('breadcrumbs_options');
                            $hidden_categories = isset($options['hidden_categories']) ? $options['hidden_categories'] : array();
                            
                            foreach ($parent_terms as $parent_id) {
                                if (!isset($hidden_categories[$parent_id]) || !$hidden_categories[$parent_id]) {
                                    $parent = get_term($parent_id, $taxonomy->name);
                                    $parent_link = get_term_link($parent);
                                    if (!is_wp_error($parent_link)) {
                                        $breadcrumbs[] = '<a href="' . $parent_link . '">' . esc_html($parent->name) . '</a>';
                                        
                                        if ($args['structured_data']) {
                                            $structured_data[] = array(
                                                '@type' => 'ListItem',
                                                'position' => $position++,
                                                'name' => $parent->name,
                                                'item' => $parent_link
                                            );
                                        }
                                    }
                                }
                            }
                        }
                        
                        $term_link = get_term_link($term);
                        if (!is_wp_error($term_link)) {
                            $options = get_option('breadcrumbs_options');
                            $hidden_categories = isset($options['hidden_categories']) ? $options['hidden_categories'] : array();
                            
                            if (!isset($hidden_categories[$term->term_id]) || !$hidden_categories[$term->term_id]) {
                                $breadcrumbs[] = '<a href="' . $term_link . '">' . esc_html($term->name) . '</a>';
                                
                                if ($args['structured_data']) {
                                    $structured_data[] = array(
                                        '@type' => 'ListItem',
                                        'position' => $position++,
                                        'name' => $term->name,
                                        'item' => $term_link
                                    );
                                }
                            }
                        }
                        $category_used = true;
                    }
                }
            }
        }
        
        $breadcrumbs[] = '<span class="current">' . esc_html(get_the_title()) . '</span>';
        
        if ($args['structured_data']) {
            $structured_data[] = array(
                '@type' => 'ListItem',
                'position' => $position,
                'name' => get_the_title(),
                'item' => get_permalink()
            );
        }
        
    } elseif (is_page()) {
        $post = get_queried_object();
        
        $options = get_option('breadcrumbs_options');
        $page_parents = isset($options['page_parents']) ? $options['page_parents'] : array();
        $additional_parent_id = isset($page_parents[$post->ID]) ? $page_parents[$post->ID] : null;
        
        if ($additional_parent_id) {
            $additional_ancestors = get_ancestors($additional_parent_id, 'page');
            $additional_ancestors = array_reverse($additional_ancestors);
            
            foreach ($additional_ancestors as $ancestor_id) {
                $ancestor = get_post($ancestor_id);
                $ancestor_link = get_permalink($ancestor_id);
                $breadcrumbs[] = '<a href="' . $ancestor_link . '">' . esc_html($ancestor->post_title) . '</a>';
                
                if ($args['structured_data']) {
                    $structured_data[] = array(
                        '@type' => 'ListItem',
                        'position' => $position++,
                        'name' => $ancestor->post_title,
                        'item' => $ancestor_link
                    );
                }
            }
            
            $additional_parent = get_post($additional_parent_id);
            $additional_parent_link = get_permalink($additional_parent_id);
            $breadcrumbs[] = '<a href="' . $additional_parent_link . '">' . esc_html($additional_parent->post_title) . '</a>';
            
            if ($args['structured_data']) {
                $structured_data[] = array(
                    '@type' => 'ListItem',
                    'position' => $position++,
                    'name' => $additional_parent->post_title,
                    'item' => $additional_parent_link
                );
            }
        }
        
        if ($post->post_parent) {
            $parent_pages = get_ancestors($post->ID, 'page');
            $parent_pages = array_reverse($parent_pages);
            
            foreach ($parent_pages as $parent_id) {
                if ($parent_id != $additional_parent_id) {
                    $parent = get_post($parent_id);
                    $parent_link = get_permalink($parent->ID);
                    $breadcrumbs[] = '<a href="' . $parent_link . '">' . esc_html($parent->post_title) . '</a>';
                    
                    if ($args['structured_data']) {
                        $structured_data[] = array(
                            '@type' => 'ListItem',
                            'position' => $position++,
                            'name' => $parent->post_title,
                            'item' => $parent_link
                        );
                    }
                }
            }
        }
        
        $breadcrumbs[] = '<span class="current">' . esc_html(get_the_title()) . '</span>';
        
        if ($args['structured_data']) {
            $structured_data[] = array(
                '@type' => 'ListItem',
                'position' => $position,
                'name' => get_the_title(),
                'item' => get_permalink()
            );
        }
        
    } elseif (is_search()) {
        $search_query = get_search_query();
        $breadcrumbs[] = '<span class="current">Поиск: ' . esc_html($search_query) . '</span>';
        
        if ($args['structured_data']) {
            $structured_data[] = array(
                '@type' => 'ListItem',
                'position' => $position,
                'name' => 'Поиск: ' . $search_query,
                'item' => get_search_link($search_query)
            );
        }
        
    } elseif (is_404()) {
        $breadcrumbs[] = '<span class="current">Страница не найдена</span>';
        
        if ($args['structured_data']) {
            $structured_data[] = array(
                '@type' => 'ListItem',
                'position' => $position,
                'name' => 'Страница не найдена'
            );
        }
    }
    
    if (count($breadcrumbs) > 0) {
        echo '<div class="rb-container">';
        echo '<nav class="breadcrumbs" id="breadcrumbs">';
        echo '<span>';
        
        $breadcrumb_items = array();
        $total_items = count($breadcrumbs);
        
        for ($i = 0; $i < $total_items; $i++) {
            $item = $breadcrumbs[$i];
            
            if ($i === $total_items - 1) {
                $item = str_replace('<span class="current">', '<span class="breadcrumb_last" aria-current="page">', $item);
                $breadcrumb_items[] = $item;
            } else {
                $breadcrumb_items[] = '<span>' . $item . '</span>';
            }
        }
        
        echo implode(' ' . $args['separator'] . ' ', $breadcrumb_items);
        
        echo '</span>';
        echo '</nav>';
        echo '</div>';
if (!empty($structured_data)) {
    $last_index = count($structured_data) - 1;

    if (isset($structured_data[$last_index]['item'])) {
        unset($structured_data[$last_index]['item']);
    }
}

if ( $args['structured_data'] && !empty($structured_data) ) {
    breadcrumbs_print_schema_graph( $structured_data );
}
    }
}
function olimp5_normalize_url($url) {
    if (!is_string($url) || $url === '') {
        return $url;
    }

    // Точные фиксы под твои кейсы
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

    // Общий случай: выкидываем /./ где бы ни всплыло
    if (strpos($url, '/./') !== false) {
        $url = str_replace('/./', '/', $url);
    }

    return $url;
}

function olimp5_normalize_breadcrumb_items(array $items): array {
    foreach ($items as &$item) {
        if (!is_array($item)) {
            continue;
        }

        if (isset($item['item'])) {
            $item['item'] = olimp5_normalize_url($item['item']);
        }
    }
    unset($item);

    return $items;
}

function breadcrumbs_print_schema_graph( array $itemListElements ) {
    if ( is_front_page() ) {
        return;
    }

    // Нормализуем URL в элементах хлебных крошек
    $itemListElements = olimp5_normalize_breadcrumb_items($itemListElements);

    $site_url  = olimp5_normalize_url( home_url( '/' ) );
    $site_name = get_bloginfo( 'name' );
    $site_desc = get_bloginfo( 'description' );
    $lang      = get_bloginfo( 'language' ) ?: 'ru-RU';

    $page_id  = get_queried_object_id();

    if ( is_singular() && $page_id ) {
        $page_url = get_permalink( $page_id );
    } elseif ( is_category() || is_tax() || is_tag() ) {
        $term     = get_queried_object();
        $page_url = $term ? get_term_link( $term ) : '';
    } else {
        // fallback: текущий URL
        $page_url = home_url( add_query_arg( [], $GLOBALS['wp']->request ) . '/' );
    }

    if ( is_wp_error( $page_url ) || empty( $page_url ) ) {
        $page_url = home_url( $_SERVER['REQUEST_URI'] );
    }

    $page_url = olimp5_normalize_url( $page_url );

    $page_name = wp_get_document_title();

    // описание страницы
    $page_desc = '';
    if ( is_singular() && $page_id ) {
        if ( function_exists('get_post_meta') ) {
            $yoast_desc = get_post_meta($page_id, '_yoast_wpseo_metadesc', true);
            if ($yoast_desc !== '') {
                $page_desc = $yoast_desc;
            }
        }
        if ($page_desc === '' && has_excerpt($page_id)) {
            $page_desc = get_the_excerpt($page_id);
        }
    }
    if ($page_desc === '') {
        $page_desc = $site_desc ?: '';
    }

    $is_collection = ( is_archive() || is_tax() || is_category() || is_tag() || is_home() || is_post_type_archive() );
    $page_type     = $is_collection ? 'CollectionPage' : 'WebPage';

    $website_id   = trailingslashit( $site_url ) . '#website';
    $breadcrumb_id = trailingslashit( $page_url ) . '#breadcrumb';

    $graph = [
        [
            '@type'       => $page_type,
            '@id'         => $page_url,
            'url'         => $page_url,
            'name'        => $page_name,
            'isPartOf'    => [ '@id' => $website_id ],
            'description' => $page_desc,
            'breadcrumb'  => [ '@id' => $breadcrumb_id ],
            'inLanguage'  => $lang,
        ],
        [
            '@type'          => 'BreadcrumbList',
            '@id'            => $breadcrumb_id,
            'itemListElement'=> $itemListElements,
        ],
        [
            '@type'        => 'WebSite',
            '@id'          => $website_id,
            'url'          => $site_url,
            'name'         => $site_name,
            'description'  => $site_desc,
            'potentialAction' => [
                [
                    '@type' => 'SearchAction',
                    'target' => [
                        '@type'       => 'EntryPoint',
                        'urlTemplate' => $site_url . '?s={search_term_string}',
                    ],
                    'query-input' => [
                        '@type'        => 'PropertyValueSpecification',
                        'valueRequired'=> true,
                        'valueName'    => 'search_term_string',
                    ],
                ],
            ],
            'inLanguage'   => $lang,
        ],
    ];

    $json = [
        '@context' => 'https://schema.org',
        '@graph'   => $graph,
    ];

    echo '<script type="application/ld+json">' .
         wp_json_encode($json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) .
         '</script>';
}


add_action('wp_head', 'breadcrumbs_styles');
function breadcrumbs_styles() {
    $options = get_option('breadcrumbs_options');
    
    if (false === $options) {
        $options = array('enabled' => 1, 'container_class' => 'breadcrumbs');
    }
    
    if (isset($options['enabled']) && empty($options['enabled'])) {
        return;
    }
    
    $container_class = isset($options['container_class']) ? $options['container_class'] : 'breadcrumbs';
    ?>
    <style>
    .rb-container {
        width: 100%;
        margin: 0 auto;
    }
    
    .breadcrumbs {
        font-size: 14px;

        padding: 10px 0;
        color: #666;
        line-height: 1.5;
    }
    
    .breadcrumbs span {
        display: inline;
    }
    
    .breadcrumbs a {
        color: #0073aa;
        text-decoration: none;
        transition: color 0.2s;
    }
    
    .breadcrumbs a:hover {
        color: #005a87;
        text-decoration: underline;
    }
    
    .breadcrumbs .breadcrumb_last {
        color: #333;
        font-weight: 500;
    }
    
    @media (max-width: 768px) {
        .breadcrumbs {
            font-size: 13px;
        }
    }
    </style>
    <?php
}

add_shortcode('breadcrumbs', 'breadcrumbs_shortcode');
function breadcrumbs_shortcode($atts) {
    $atts = shortcode_atts(array(
        'separator' => null,
        'home_text' => null,
        'show_on_home' => null,
        'container_class' => null
    ), $atts);
    
    $atts = array_filter($atts, function($value) {
        return $value !== null;
    });
    
    ob_start();
    display_breadcrumbs($atts);
    return ob_get_clean();
}

function breadcrumbs_get_page_by_title($title, $post_type = 'page') {
    $query = new WP_Query(array(
        'post_type' => $post_type,
        'title' => $title,
        'post_status' => 'publish',
        'posts_per_page' => 1,
        'no_found_rows' => true,
        'ignore_sticky_posts' => true,
        'update_post_term_cache' => false,
        'update_post_meta_cache' => false
    ));
    
    $page = null;
    if ($query->have_posts()) {
        $page = $query->posts[0];
    }
    
    wp_reset_postdata();
    return $page;
}

function breadcrumbs_get_page_by_path($path, $post_type = 'page') {
    $query = new WP_Query(array(
        'post_type' => $post_type,
        'name' => $path,
        'post_status' => 'publish',
        'posts_per_page' => 1,
        'no_found_rows' => true,
        'ignore_sticky_posts' => true,
        'update_post_term_cache' => false,
        'update_post_meta_cache' => false
    ));
    
    $page = null;
    if ($query->have_posts()) {
        $page = $query->posts[0];
    }
    
    wp_reset_postdata();
    return $page;
}

add_action('init', 'breadcrumbs_init_defaults');
function breadcrumbs_init_defaults() {
    $options = get_option('breadcrumbs_options');
    if (false === $options) {
        $default_options = array(
            'enabled' => 1,
            'separator' => ' → ',
            'home_text' => 'Главная',
            'show_on_home' => 0,
            'container_class' => 'breadcrumbs',
            'structured_data' => 1
        );
        update_option('breadcrumbs_options', $default_options);
    }
}

// Лог сохранения term_meta Carbon Fields
add_action( 'carbon_fields_term_meta_container_saved', function( $term_id ) {

    $term     = get_term( $term_id );
    $user     = wp_get_current_user();
    $taxonomy = $term ? $term->taxonomy : 'unknown';
    $term_name = $term ? $term->name : 'unknown';

    // Поля которые отслеживаем
    $fields_to_watch = array(
        'rb_services_add',
        'rb_services_whou_is',
        'rb_services_equip',
        'rb_services_infoblocks',
        'rb_add',
        'rb_different',
        'rb_all_services_hide',
        'rb_services_popular',
        'rb_services_spec',
        'rb_new_text_blocks',
    );

    $log_lines = array();
    $log_lines[] = str_repeat('=', 80);
    $log_lines[] = '[' . date('Y-m-d H:i:s') . '] СОХРАНЕНИЕ TERM META';
    $log_lines[] = 'Кто    : ' . $user->user_login . ' (ID: ' . $user->ID . ', email: ' . $user->user_email . ')';
    $log_lines[] = 'Термин : ' . $term_name . ' (term_id: ' . $term_id . ', taxonomy: ' . $taxonomy . ')';
    $log_lines[] = 'URL    : ' . ( isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'n/a' );
    $log_lines[] = str_repeat('-', 80);

    foreach ( $fields_to_watch as $field ) {

        $val = carbon_get_term_meta( $term_id, $field );

        if ( is_array( $val ) && empty( $val ) ) {
            $display = '[] (ПУСТО — данные могли быть затёрты!)';
        } elseif ( is_array( $val ) ) {
            $display = json_encode( $val, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
        } else {
            $display = ( $val === '' || $val === null ) ? '(пусто)' : $val;
        }

        $log_lines[] = 'ПОЛЕ: ' . $field;
        $log_lines[] = $display;
        $log_lines[] = '';
    }

    $log_lines[] = str_repeat('=', 80) . "\n\n";

    $log_content = implode( "\n", $log_lines );
    file_put_contents( WP_CONTENT_DIR . '/cf-term-save-log.txt', $log_content, FILE_APPEND );

}, 10, 1 );


// Отдельно логируем post_meta (страницы, посты)
add_action( 'carbon_fields_post_meta_container_saved', function( $post_id ) {

    // Пропускаем автосохранения и ревизии
    if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) return;

    $user     = wp_get_current_user();
    $post     = get_post( $post_id );
    $post_type = $post ? $post->post_type : 'unknown';
    $post_title = $post ? $post->post_title : 'unknown';

    // Отслеживаем только нужные post_type
    $watch_post_types = array( 'service', 'page', 'doctors', 'programms' );
    if ( ! in_array( $post_type, $watch_post_types ) ) return;

    $fields_to_watch = array(
        'rb_services_add',
        'rb_services_whou_is',
        'rb_services_main',
        'rb_services_result',
        'rb_services_process',
        'rb_services_protive',
        'rb_services_risks',
        'rb_services_for_whom',
        'rb_about_managers_list',
        'rb_about_reviews_more',
        'rb_main_services_before',
        'rb_main_services_after',
    );

    $log_lines = array();
    $log_lines[] = str_repeat('=', 80);
    $log_lines[] = '[' . date('Y-m-d H:i:s') . '] СОХРАНЕНИЕ POST META';
    $log_lines[] = 'Кто       : ' . $user->user_login . ' (ID: ' . $user->ID . ', email: ' . $user->user_email . ')';
    $log_lines[] = 'Пост      : ' . $post_title . ' (post_id: ' . $post_id . ', post_type: ' . $post_type . ')';
    $log_lines[] = 'Ред. ссылка: ' . get_edit_post_link( $post_id );
    $log_lines[] = str_repeat('-', 80);

    foreach ( $fields_to_watch as $field ) {

        $val = carbon_get_post_meta( $post_id, $field );

        if ( is_array( $val ) && empty( $val ) ) {
            $display = '[] (ПУСТО — данные могли быть затёрты!)';
        } elseif ( is_array( $val ) ) {
            $display = json_encode( $val, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
        } else {
            $display = ( $val === '' || $val === null ) ? '(пусто)' : $val;
        }

        $log_lines[] = 'ПОЛЕ: ' . $field;
        $log_lines[] = $display;
        $log_lines[] = '';
    }

    $log_lines[] = str_repeat('=', 80) . "\n\n";

    $log_content = implode( "\n", $log_lines );
    file_put_contents( WP_CONTENT_DIR . '/cf-post-save-log.txt', $log_content, FILE_APPEND );

}, 10, 1 );

add_filter('post_row_actions', function ($actions, $post) {
    if (!is_admin()) {
        return $actions;
    }

    if (!$post || $post->post_type !== 'service') {
        return $actions;
    }

    if (!current_user_can('edit_posts')) {
        return $actions;
    }

    $url = wp_nonce_url(
        admin_url('admin.php?action=duplicate_service_post&post=' . $post->ID),
        'duplicate_service_post_' . $post->ID
    );

    $actions['duplicate_service'] = '<a href="' . esc_url($url) . '">Копировать</a>';

    return $actions;
}, 10, 2);

add_action('admin_action_duplicate_service_post', function () {
    if (!isset($_GET['post'])) {
        wp_die('Не передан ID записи.');
    }

    $post_id = absint($_GET['post']);
    if (!$post_id) {
        wp_die('Некорректный ID записи.');
    }

    check_admin_referer('duplicate_service_post_' . $post_id);

    $post = get_post($post_id);
    if (!$post) {
        wp_die('Запись не найдена.');
    }

    if ($post->post_type !== 'service') {
        wp_die('Можно копировать только услуги.');
    }

    if (!current_user_can('edit_post', $post_id)) {
        wp_die('Недостаточно прав.');
    }

    $new_post_id = wp_insert_post([
        'post_type'      => $post->post_type,
        'post_status'    => 'draft',
        'post_title'     => $post->post_title . ' (копия)',
        'post_content'   => $post->post_content,
        'post_excerpt'   => $post->post_excerpt,
        'post_author'    => get_current_user_id(),
        'post_parent'    => $post->post_parent,
        'menu_order'     => $post->menu_order,
        'comment_status' => $post->comment_status,
        'ping_status'    => $post->ping_status,
    ], true);

    if (is_wp_error($new_post_id)) {
        wp_die('Ошибка создания копии: ' . $new_post_id->get_error_message());
    }

    $taxonomies = get_object_taxonomies($post->post_type);
    foreach ($taxonomies as $taxonomy) {
        $terms = wp_get_object_terms($post_id, $taxonomy, ['fields' => 'ids']);
        if (!is_wp_error($terms) && !empty($terms)) {
            wp_set_object_terms($new_post_id, $terms, $taxonomy);
        }
    }

    $all_meta = get_post_meta($post_id);

    if (!empty($all_meta)) {
        foreach ($all_meta as $meta_key => $meta_values) {
            if (in_array($meta_key, ['_edit_lock', '_edit_last'], true)) {
                continue;
            }

            foreach ($meta_values as $meta_value) {
                add_post_meta($new_post_id, $meta_key, maybe_unserialize($meta_value));
            }
        }
    }

    $thumbnail_id = get_post_thumbnail_id($post_id);
    if ($thumbnail_id) {
        set_post_thumbnail($new_post_id, $thumbnail_id);
    }

    wp_safe_redirect(admin_url('post.php?action=edit&post=' . $new_post_id));
    exit;
});

add_filter('services_row_actions', function ($actions, $term) {
    if (!is_admin()) {
        return $actions;
    }

    if (!$term || empty($term->term_id)) {
        return $actions;
    }

    if (!current_user_can('manage_categories')) {
        return $actions;
    }

    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    if (!$screen || $screen->taxonomy !== 'services') {
        return $actions;
    }

    $url = wp_nonce_url(
        admin_url('admin.php?action=duplicate_service_term&term_id=' . $term->term_id . '&taxonomy=services&post_type=service'),
        'duplicate_service_term_' . $term->term_id
    );

    $actions['duplicate_term'] = '<a href="' . esc_url($url) . '">Копировать</a>';

    return $actions;
}, 10, 2);

add_action('admin_action_duplicate_service_term', function () {
    $term_id = isset($_GET['term_id']) ? absint($_GET['term_id']) : 0;
    $taxonomy = isset($_GET['taxonomy']) ? sanitize_key($_GET['taxonomy']) : '';

    if (!$term_id || $taxonomy !== 'services') {
        wp_die('Некорректные данные.');
    }

    check_admin_referer('duplicate_service_term_' . $term_id);

    if (!current_user_can('manage_categories')) {
        wp_die('Недостаточно прав.');
    }

    $term = get_term($term_id, $taxonomy);
    if (!$term || is_wp_error($term)) {
        wp_die('Термин не найден.');
    }

    $base_name = $term->name . ' (копия)';
    $new_name = $base_name;
    $i = 2;

    while (term_exists($new_name, $taxonomy, $term->parent)) {
        $new_name = $base_name . ' ' . $i;
        $i++;
    }

    $slug_base = $term->slug . '-copy';
    $new_slug = $slug_base;
    $j = 2;

    while (get_term_by('slug', $new_slug, $taxonomy)) {
        $new_slug = $slug_base . '-' . $j;
        $j++;
    }

    $result = wp_insert_term($new_name, $taxonomy, [
        'slug'        => $new_slug,
        'parent'      => $term->parent,
        'description' => $term->description,
    ]);

    if (is_wp_error($result)) {
        wp_die('Ошибка создания копии: ' . $result->get_error_message());
    }

    $new_term_id = (int) $result['term_id'];

    $meta = get_term_meta($term_id);
    if (!empty($meta)) {
        foreach ($meta as $meta_key => $values) {
            foreach ($values as $value) {
                add_term_meta($new_term_id, $meta_key, maybe_unserialize($value));
            }
        }
    }

    wp_safe_redirect(admin_url('term.php?taxonomy=services&tag_ID=' . $new_term_id . '&post_type=service'));
    exit;
});

add_action('init', function() {
    // delete_option('events_archive_updated');
    $last_run = get_option('events_archive_updated');
    // текущее время
    $now = current_time('timestamp');
    // сегодня 05:00
    $today_5am = strtotime(date('Y-m-d 05:00:00'));
    // если еще нет 05:00 — ничего не делаем
    if ($now < $today_5am) {
        return;
    }
    // если уже запускали сегодня после 05:00
    if ($last_run && date('Y-m-d', $last_run) === date('Y-m-d')) {
        return;
    }
    // $last_run = get_option('events_archive_updated');
    // // 24 часа
    // if ($last_run && (time() - $last_run) < DAY_IN_SECONDS) {
    //     return;
    // }
    $schedule_query = new WP_Query(array( 'post_type' => 'events', 'posts_per_page' => -1, ));

    if ($schedule_query->have_posts()):
        while ($schedule_query->have_posts()):
            $schedule_query->the_post();
            $post_id = get_the_ID();
            $rb_date = carbon_get_post_meta($post_id, 'rb_date');
            if ($rb_date) {
                $rb_timestamp = strtotime($rb_date);
                if (date('Y-m-d', $rb_timestamp) < date('Y-m-d')) { wp_set_object_terms($post_id, ['arhiv-meropriyatij'], 'events-type'); }
            }
        endwhile;
    endif;
    wp_reset_postdata();

    // $promo_query = new WP_Query(array( 'post_type' => 'promo', 'posts_per_page' => -1, ));
    // if ($promo_query->have_posts()):
    //     while ($promo_query->have_posts()):
    //         $promo_query->the_post();
    //         $post_id = get_the_ID();
    //         $rb_date = get_post_meta(get_the_ID(), 'auto_delete_post_time_key', true);
    //         if ($rb_date) {
    //             $rb_timestamp = strtotime($rb_date);
    //             if (date('Y-m-d', $rb_timestamp) < date('Y-m-d') && get_post_status($post_id) === 'publish') { wp_update_post(['ID' => $post_id, 'post_status' => 'draft',]); }
    //         }
    //     endwhile;
    // endif;
    // wp_reset_postdata();

    update_option('events_archive_updated', $now);
});