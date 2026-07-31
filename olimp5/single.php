<?php
get_header();

$post_id   = get_the_ID();
$pub_date  = get_the_date( 'd.m.Y' );
$mod_date  = get_the_modified_date( 'd.m.Y' );
$read_time = rb_get_read_time( $post_id );
$views     = rb_get_post_views( $post_id );

/**
 * Настройки отображения блоков статьи:
 * - карточка автора
 * - похожие статьи
 * 
 * Управляется из:
 * Настройки сайта → Статьи
 */
$post_category_ids = array_map( 'strval', wp_get_post_categories( $post_id ) );

$rb_theme_option = function( $key, $default = '' ) {
    if ( function_exists( 'carbon_get_theme_option' ) ) {
        $value = carbon_get_theme_option( $key );

        if ( $value !== null && $value !== '' ) {
            return $value;
        }
    }

    return $default;
};

$rb_to_string_array = function( $value ) {
    if ( empty( $value ) ) {
        return array();
    }

    if ( ! is_array( $value ) ) {
        $value = explode( ',', (string) $value );
    }

    $value = array_map( 'strval', $value );
    $value = array_map( 'trim', $value );
    $value = array_filter( $value );

    return array_values( $value );
};

$rb_has_selected_category = function( $selected_categories ) use ( $post_category_ids, $rb_to_string_array ) {
    $selected_categories = $rb_to_string_array( $selected_categories );

    if ( empty( $selected_categories ) || empty( $post_category_ids ) ) {
        return false;
    }

    return ! empty( array_intersect( $post_category_ids, $selected_categories ) );
};

/**
 * Карточка автора
 */
$show_author_card = $rb_theme_option( 'rb_blog_show_author_card', 'yes' ) !== 'no';

$author_show_categories = $rb_theme_option( 'rb_blog_author_card_show_categories', array() );
$author_hide_categories = $rb_theme_option( 'rb_blog_author_card_hide_categories', array() );

if ( $rb_has_selected_category( $author_show_categories ) ) {
    $show_author_card = true;
}

// Скрытие имеет приоритет над показом
if ( $rb_has_selected_category( $author_hide_categories ) ) {
    $show_author_card = false;
}

/**
 * Похожие статьи
 */
$show_related_articles = $rb_theme_option( 'rb_blog_show_related_articles', 'yes' ) !== 'no';

$related_show_categories = $rb_theme_option( 'rb_blog_related_show_categories', array() );
$related_hide_categories = $rb_theme_option( 'rb_blog_related_hide_categories', array() );

if ( $rb_has_selected_category( $related_show_categories ) ) {
    $show_related_articles = true;
}

// Скрытие имеет приоритет над показом
if ( $rb_has_selected_category( $related_hide_categories ) ) {
    $show_related_articles = false;
}

$author = null;

if ( $show_author_card && function_exists( 'rb_get_post_author_data' ) ) {
    $author = rb_get_post_author_data( $post_id );
}

$builder = function_exists('carbon_get_post_meta') ? (array) carbon_get_post_meta($post_id, 'rb_post_builder') : [];
// Оглавление — собираем H2 из контента
$content_raw = get_the_content();
preg_match_all( '/<h2[^>]*>(.*?)<\/h2>/is', $content_raw, $toc_matches );
$toc_items = $toc_matches[1] ?? [];

// Добавляем id к h2 в контенте для якорей
$content_with_anchors = preg_replace_callback(
    '/<h2([^>]*)>(.*?)<\/h2>/is',
    function( $m ) {
        $text   = strip_tags( $m[2] );
        $anchor = 'toc-' . sanitize_title( $text );
        return '<h2' . $m[1] . ' id="' . esc_attr( $anchor ) . '">' . $m[2] . '</h2>';
    },
    $content_raw
);

// Похожие статьи — из тех же категорий, исключая текущую
$related_query = null;

$manual_related_ids = array();

if ( $show_related_articles && function_exists( 'carbon_get_post_meta' ) ) {
    $manual_related_items = carbon_get_post_meta( $post_id, 'rb_post_related_articles' );

    if ( ! empty( $manual_related_items ) && is_array( $manual_related_items ) ) {
        foreach ( $manual_related_items as $item ) {
            $manual_id = 0;

            if ( is_array( $item ) ) {
                if ( ! empty( $item['id'] ) ) {
                    $manual_id = (int) $item['id'];
                } elseif ( ! empty( $item['value'] ) && preg_match( '~(\d+)$~', (string) $item['value'], $m ) ) {
                    $manual_id = (int) $m[1];
                }
            } else {
                $manual_id = (int) $item;
            }

            if ( $manual_id && $manual_id !== (int) $post_id ) {
                $manual_related_ids[] = $manual_id;
            }
        }
    }

    $manual_related_ids = array_values( array_unique( array_filter( $manual_related_ids ) ) );
}

if ( $show_related_articles && ! empty( $manual_related_ids ) ) {

    // Ручной режим — порядок как выбрали в админке
    $related_query = new WP_Query( array(
        'post_type'           => 'post',
        'posts_per_page'      => 8,
        'post__in'            => $manual_related_ids,
        'orderby'             => 'post__in',
        'ignore_sticky_posts' => 1,
    ) );

} elseif ( $show_related_articles && ! empty( $post_category_ids ) ) {

    // Автоматический режим — как было раньше
    $related_query = new WP_Query( array(
        'post_type'           => 'post',
        'posts_per_page'      => 8,
        'post__not_in'        => array( $post_id ),
        'category__in'        => array_map( 'intval', $post_category_ids ),
        'orderby'             => 'date',
        'order'               => 'DESC',
        'ignore_sticky_posts' => 1,
    ) );

}
?>

<div class="rb-article rb-page">
    <div class="rb-container">

        <div class="rb-article__layout">

            <!-- ══ ЛЕВАЯ КОЛОНКА: контент ══ -->
            <div class="rb-article__content">

                <h1 class="rb-article__title rb-page-header"><?php the_title(); ?></h1>
<?php
if (!empty($builder)) {
  foreach ($builder as $b) {
    $pos = $b['pos'] ?? '';
    if ($pos !== 'before_content') continue;

    $type = $b['_type'] ?? '';
    if ($type === 'heading') {
      $tag  = in_array(($b['tag'] ?? 'h2'), array('h2','h3','h4'), true) ? $b['tag'] : 'h2';
      $text = trim((string)($b['text'] ?? ''));
      if ($text !== '') echo '<' . $tag . ' class="rb-article__builder-title">' . esc_html($text) . '</' . $tag . '>';
    } elseif ($type === 'text') {
      $html = (string)($b['content'] ?? '');
      if ($html !== '') echo '<div class="rb-article__builder-text">' . apply_filters('the_content', $html) . '</div>';
    } elseif ($type === 'shortcode') {
      $code = trim((string)($b['code'] ?? ''));
      if ($code !== '') echo '<div class="rb-article__builder-shortcode">' . do_shortcode($code) . '</div>';
    } elseif ($type === 'image') {
      $src = trim((string)($b['image'] ?? ''));
      if ($src !== '') {
        $alt = (string)($b['alt'] ?? '');
        $cap = (string)($b['caption'] ?? '');
        $al  = (string)($b['align'] ?? '');
        $cls = 'rb-article__builder-image' . ($al ? ' is-' . sanitize_html_class($al) : '');
        echo '<figure class="' . esc_attr($cls) . '"><img src="' . esc_url($src) . '" alt="' . esc_attr($alt) . '" loading="lazy">';
        if ($cap !== '') echo '<figcaption>' . esc_html($cap) . '</figcaption>';
        echo '</figure>';
      }
    }
  }
}
?>
                <div class="rb-article__body">
                    <?php echo apply_filters( 'the_content', $content_with_anchors ); ?>
                </div>

            </div>

            <!-- ══ ПРАВАЯ КОЛОНКА: сайдбар ══ -->
            <aside class="rb-article__sidebar">
  				<div class="rb-article__sidebar-sticky">
                <!-- Карточка автора -->
                <?php if ( $show_author_card && $author ) :
                    $a_name  = esc_html( $author['name'] );
                    $a_photo = esc_url( $author['photo'] );
                    $a_area  = esc_html( $author['area'] );
                    $a_bio   = esc_html( $author['bio'] );
                    $a_link  = esc_url( $author['link'] );
                ?>
                    <div class="rb-article__author-card">

                        <?php if ( $a_photo ) : ?>
                            <div class="rb-article__author-photo">
                                <?php if ( $a_link ) : ?>
                                    <a href="<?php echo $a_link; ?>" target="_blank" rel="noopener noreferrer">
                                        <img src="<?php echo $a_photo; ?>" alt="<?php echo $a_name; ?>" loading="lazy">
                                    </a>
                                <?php else : ?>
                                    <img src="<?php echo $a_photo; ?>" alt="<?php echo $a_name; ?>" loading="lazy">
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <p class="rb-article__author-label">Автор статьи</p>

                        <p class="rb-article__author-name">
                            <?php if ( $a_link ) : ?>
                                <a href="<?php echo $a_link; ?>" target="_blank" rel="noopener noreferrer"><?php echo $a_name; ?></a>
                            <?php else : ?>
                                <?php echo $a_name; ?>
                            <?php endif; ?>
                        </p>

                        <?php if ( $a_area ) : ?>
                            <p class="rb-article__author-area"><?php echo $a_area; ?></p>
                        <?php endif; ?>

                        <?php if ( $a_bio ) : ?>
                            <p class="rb-article__author-bio"><?php echo $a_bio; ?></p>
                        <?php endif; ?>

                        <!-- Мета-данные -->
                        <div class="rb-article__meta">
                            <div class="rb-article__meta-row">
                                <span class="rb-article__meta-item">
<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M2.20777 11.8162C1.80652 12.2175 1.51402 12.72 1.36027 13.2675L0.0327661 18.06C-0.0534839 18.3712 0.032766 18.7087 0.265266 18.9375C0.497766 19.1662 0.831516 19.2562 1.14277 19.17L5.93527 17.8387C6.48277 17.685 6.98152 17.3962 7.38652 16.9912L18.4115 5.96625C18.914 5.46 19.199 4.77 19.199 4.05C19.199 3.33 18.914 2.64 18.404 2.13375L17.0653 0.795C16.559 0.285 15.869 0 15.149 0C14.429 0 13.739 0.285 13.2328 0.795L2.20777 11.82V11.8162ZM15.149 1.8C15.389 1.8 15.6215 1.8975 15.794 2.06625L17.1328 3.405C17.3053 3.5775 17.399 3.80625 17.399 4.05C17.399 4.29375 17.3015 4.5225 17.1328 4.695L15.149 6.67875L12.5203 4.05L14.504 2.06625C14.6765 1.89375 14.9053 1.8 15.149 1.8ZM4.12027 12.45L11.249 5.32125L13.8778 7.95L6.74902 15.0787L4.12027 12.45ZM3.04777 13.9237L5.27527 16.1512L2.19277 17.0062L3.04777 13.9237Z" fill="#F3951D"/>
</svg>

                                    <time datetime="<?php echo $pub_date; ?>"><?php echo $pub_date; ?></time>
                                </span>
                                <?php if ( $mod_date && $mod_date !== $pub_date ) : ?>
                                    <span class="rb-article__meta-item">
<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M4.0875 4.08375C7.1325 1.03875 12.0713 1.03875 15.12 4.08375L16.1362 5.1H13.2038C12.705 5.1 12.3038 5.50125 12.3038 6C12.3038 6.49875 12.705 6.9 13.2038 6.9H18.3075C18.8063 6.9 19.2075 6.49875 19.2075 6V0.9C19.2075 0.40125 18.8063 0 18.3075 0C17.8088 0 17.4075 0.40125 17.4075 0.9V3.82875L16.3913 2.8125C12.6413 -0.9375 6.5625 -0.9375 2.81625 2.8125C1.19625 4.4325 0.27375 6.49125 0.05625 8.60625C0.00375 9.10125 0.36375 9.54375 0.85875 9.5925C1.35375 9.64125 1.79625 9.285 1.845 8.79C2.025 7.0725 2.77125 5.40375 4.0875 4.08375ZM19.1513 10.5938C19.2038 10.0988 18.8438 9.65625 18.3488 9.6075C17.8538 9.55875 17.4113 9.915 17.3625 10.41C17.1863 12.1275 16.4363 13.8 15.12 15.1163C12.075 18.1613 7.13625 18.1613 4.0875 15.1163L3.07125 14.1H6.00375C6.5025 14.1 6.90375 13.6988 6.90375 13.2C6.90375 12.7013 6.5025 12.3 6.00375 12.3H0.9C0.40125 12.3 0 12.7013 0 13.2V18.3C0 18.7987 0.40125 19.2 0.9 19.2C1.39875 19.2 1.8 18.7987 1.8 18.3V15.3713L2.81625 16.3875C6.56625 20.1375 12.645 20.1375 16.3913 16.3875C18.0113 14.7675 18.9338 12.7088 19.1513 10.5938Z" fill="#F3951D"/>
</svg>

                                        <time datetime="<?php echo $mod_date; ?>"><?php echo $mod_date; ?></time>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="rb-article__meta-row" style="
    gap: 36px;
">
                                <span class="rb-article__meta-item">
<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M17.4 9.6C17.4 13.9088 13.9088 17.4 9.6 17.4C5.29125 17.4 1.8 13.9088 1.8 9.6C1.8 5.29125 5.29125 1.8 9.6 1.8C13.9088 1.8 17.4 5.29125 17.4 9.6ZM0 9.6C0 14.9025 4.2975 19.2 9.6 19.2C14.9025 19.2 19.2 14.9025 19.2 9.6C19.2 4.2975 14.9025 0 9.6 0C4.2975 0 0 4.2975 0 9.6ZM8.7 4.5V9.6C8.7 9.9 8.85 10.1813 9.10125 10.35L12.7013 12.75C13.1138 13.0275 13.6725 12.915 13.95 12.4987C14.2275 12.0825 14.115 11.5275 13.6988 11.25L10.5 9.12V4.5C10.5 4.00125 10.0988 3.6 9.6 3.6C9.10125 3.6 8.7 4.00125 8.7 4.5Z" fill="#F3951D"/>
</svg>
                                    <?php echo $read_time; ?> минут
                                </span>
                                <?php if ( $views > 0 ) : ?>
                                    <span class="rb-article__meta-item">
                                                                           <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M4.0875 4.08375C7.1325 1.03875 12.0713 1.03875 15.12 4.08375L16.1362 5.1H13.2038C12.705 5.1 12.3038 5.50125 12.3038 6C12.3038 6.49875 12.705 6.9 13.2038 6.9H18.3075C18.8063 6.9 19.2075 6.49875 19.2075 6V0.9C19.2075 0.40125 18.8063 0 18.3075 0C17.8088 0 17.4075 0.40125 17.4075 0.9V3.82875L16.3913 2.8125C12.6413 -0.9375 6.5625 -0.9375 2.81625 2.8125C1.19625 4.4325 0.27375 6.49125 0.05625 8.60625C0.00375 9.10125 0.36375 9.54375 0.85875 9.5925C1.35375 9.64125 1.79625 9.285 1.845 8.79C2.025 7.0725 2.77125 5.40375 4.0875 4.08375ZM19.1513 10.5938C19.2038 10.0988 18.8438 9.65625 18.3488 9.6075C17.8538 9.55875 17.4113 9.915 17.3625 10.41C17.1863 12.1275 16.4363 13.8 15.12 15.1163C12.075 18.1613 7.13625 18.1613 4.0875 15.1163L3.07125 14.1H6.00375C6.5025 14.1 6.90375 13.6988 6.90375 13.2C6.90375 12.7013 6.5025 12.3 6.00375 12.3H0.9C0.40125 12.3 0 12.7013 0 13.2V18.3C0 18.7987 0.40125 19.2 0.9 19.2C1.39875 19.2 1.8 18.7987 1.8 18.3V15.3713L2.81625 16.3875C6.56625 20.1375 12.645 20.1375 16.3913 16.3875C18.0113 14.7675 18.9338 12.7088 19.1513 10.5938Z" fill="#F3951D"/>
</svg>
                                        <?php echo rb_format_views( $views ); ?>
                                    </span>
                                <?php endif; ?>
								                                <span class="rb-article__meta-item" style="	position: relative;
	left: 6px;">
<svg width="20" height="20" viewBox="0 0 17 13" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M8.10211 1.35C6.26836 1.35 4.76086 2.1825 3.60492 3.25406C2.52211 4.26094 1.77398 5.45625 1.39148 6.3C1.77398 7.14375 2.52211 8.33906 3.60492 9.34594C4.76086 10.4175 6.26836 11.25 8.10211 11.25C9.93586 11.25 11.4434 10.4175 12.5993 9.34594C13.6821 8.33906 14.4302 7.14375 14.8127 6.3C14.4302 5.45625 13.6821 4.26094 12.5993 3.25406C11.4434 2.1825 9.93586 1.35 8.10211 1.35ZM2.68523 2.26688C4.00992 1.035 5.82961 0 8.10211 0C10.3746 0 12.1943 1.035 13.519 2.26688C14.8352 3.49031 15.7155 4.95 16.1346 5.95406C16.2274 6.17625 16.2274 6.42375 16.1346 6.64594C15.7155 7.65 14.8352 9.1125 13.519 10.3331C12.1943 11.5622 10.3746 12.6 8.10211 12.6C5.82961 12.6 4.00992 11.565 2.68523 10.3331C1.36898 9.10969 0.488672 7.65 0.0696094 6.64594C-0.0232031 6.42375 -0.0232031 6.17625 0.0696094 5.95406C0.488672 4.95 1.36898 3.4875 2.68523 2.26688ZM8.10211 8.55C9.34523 8.55 10.3521 7.54312 10.3521 6.3C10.3521 5.4675 9.8993 4.73906 9.22711 4.35094C9.18774 6.03 7.83211 7.38562 6.15305 7.425C6.54117 8.09719 7.26961 8.55 8.10211 8.55ZM5.86336 6.06375C5.93367 6.07219 6.00398 6.075 6.07711 6.075C7.06992 6.075 7.87711 5.26781 7.87711 4.275C7.87711 4.20187 7.87149 4.13156 7.86586 4.06125C6.81399 4.17094 5.97586 5.00906 5.86617 6.06094L5.86336 6.06375ZM7.14586 2.82938C7.44961 2.745 7.77024 2.70281 8.0993 2.70281C8.3468 2.70281 8.59149 2.72812 8.82492 2.77594C8.83336 2.77875 8.83899 2.77875 8.84742 2.78156C10.4759 3.12469 11.6993 4.57312 11.6993 6.30281C11.6993 8.29125 10.0877 9.90281 8.0993 9.90281C6.3668 9.90281 4.92117 8.67937 4.57805 7.05094C4.52742 6.80906 4.4993 6.55875 4.4993 6.30281C4.4993 5.99344 4.53867 5.68969 4.6118 5.40281C4.61742 5.38312 4.62023 5.36625 4.62586 5.34938C4.96055 4.12875 5.92242 3.16687 7.14305 2.83219L7.14586 2.82938Z" fill="#F3951D"/>
</svg>


                                    100
                                </span>
                                <?php if ( $views > 0 ) : ?>
                                    <span class="rb-article__meta-item">
                                        <svg width="16" height="12" viewBox="0 0 16 12" fill="none"><path d="M1 6s2.5-5 7-5 7 5 7 5-2.5 5-7 5-7-5-7-5z" stroke="#aaa" stroke-width="1.2"/><circle cx="8" cy="6" r="2" stroke="#aaa" stroke-width="1.2"/></svg>
                                        <?php echo rb_format_views( $views ); ?>
                                    </span>
                                <?php endif; ?>
                            </div>



                        </div>

                    </div>
                <?php elseif ( $show_author_card ) : ?>

                    <!-- Нет автора — только мета -->
                    <div class="rb-article__author-card rb-article__author-card--no-author">
                        <div class="rb-article__meta">
                            <div class="rb-article__meta-row">
                                <span class="rb-article__meta-item">
<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M2.20777 11.8162C1.80652 12.2175 1.51402 12.72 1.36027 13.2675L0.0327661 18.06C-0.0534839 18.3712 0.032766 18.7087 0.265266 18.9375C0.497766 19.1662 0.831516 19.2562 1.14277 19.17L5.93527 17.8387C6.48277 17.685 6.98152 17.3962 7.38652 16.9912L18.4115 5.96625C18.914 5.46 19.199 4.77 19.199 4.05C19.199 3.33 18.914 2.64 18.404 2.13375L17.0653 0.795C16.559 0.285 15.869 0 15.149 0C14.429 0 13.739 0.285 13.2328 0.795L2.20777 11.82V11.8162ZM15.149 1.8C15.389 1.8 15.6215 1.8975 15.794 2.06625L17.1328 3.405C17.3053 3.5775 17.399 3.80625 17.399 4.05C17.399 4.29375 17.3015 4.5225 17.1328 4.695L15.149 6.67875L12.5203 4.05L14.504 2.06625C14.6765 1.89375 14.9053 1.8 15.149 1.8ZM4.12027 12.45L11.249 5.32125L13.8778 7.95L6.74902 15.0787L4.12027 12.45ZM3.04777 13.9237L5.27527 16.1512L2.19277 17.0062L3.04777 13.9237Z" fill="#F3951D"/>
</svg>


                                    <time datetime="<?php echo $pub_date; ?>"><?php echo $pub_date; ?></time>
                                </span>
                                <?php if ( $mod_date && $mod_date !== $pub_date ) : ?>
                                    <span class="rb-article__meta-item">
<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M17.4 9.6C17.4 13.9088 13.9088 17.4 9.6 17.4C5.29125 17.4 1.8 13.9088 1.8 9.6C1.8 5.29125 5.29125 1.8 9.6 1.8C13.9088 1.8 17.4 5.29125 17.4 9.6ZM0 9.6C0 14.9025 4.2975 19.2 9.6 19.2C14.9025 19.2 19.2 14.9025 19.2 9.6C19.2 4.2975 14.9025 0 9.6 0C4.2975 0 0 4.2975 0 9.6ZM8.7 4.5V9.6C8.7 9.9 8.85 10.1813 9.10125 10.35L12.7013 12.75C13.1138 13.0275 13.6725 12.915 13.95 12.4987C14.2275 12.0825 14.115 11.5275 13.6988 11.25L10.5 9.12V4.5C10.5 4.00125 10.0988 3.6 9.6 3.6C9.10125 3.6 8.7 4.00125 8.7 4.5Z" fill="#F3951D"/>
</svg>


                                        <time datetime="<?php echo $mod_date; ?>"><?php echo $mod_date; ?></time>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="rb-article__meta-row" style="
    gap: 41px;
">
                                <span class="rb-article__meta-item">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M4.0875 4.08375C7.1325 1.03875 12.0713 1.03875 15.12 4.08375L16.1362 5.1H13.2038C12.705 5.1 12.3038 5.50125 12.3038 6C12.3038 6.49875 12.705 6.9 13.2038 6.9H18.3075C18.8063 6.9 19.2075 6.49875 19.2075 6V0.9C19.2075 0.40125 18.8063 0 18.3075 0C17.8088 0 17.4075 0.40125 17.4075 0.9V3.82875L16.3913 2.8125C12.6413 -0.9375 6.5625 -0.9375 2.81625 2.8125C1.19625 4.4325 0.27375 6.49125 0.05625 8.60625C0.00375 9.10125 0.36375 9.54375 0.85875 9.5925C1.35375 9.64125 1.79625 9.285 1.845 8.79C2.025 7.0725 2.77125 5.40375 4.0875 4.08375ZM19.1513 10.5938C19.2038 10.0988 18.8438 9.65625 18.3488 9.6075C17.8538 9.55875 17.4113 9.915 17.3625 10.41C17.1863 12.1275 16.4363 13.8 15.12 15.1163C12.075 18.1613 7.13625 18.1613 4.0875 15.1163L3.07125 14.1H6.00375C6.5025 14.1 6.90375 13.6988 6.90375 13.2C6.90375 12.7013 6.5025 12.3 6.00375 12.3H0.9C0.40125 12.3 0 12.7013 0 13.2V18.3C0 18.7987 0.40125 19.2 0.9 19.2C1.39875 19.2 1.8 18.7987 1.8 18.3V15.3713L2.81625 16.3875C6.56625 20.1375 12.645 20.1375 16.3913 16.3875C18.0113 14.7675 18.9338 12.7088 19.1513 10.5938Z" fill="#F3951D"/>
</svg>

                                    <?php echo $read_time; ?> минут
                                </span>
                                <?php if ( $views > 0 ) : ?>
                                    <span class="rb-article__meta-item">
                                                                                                                   <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M4.0875 4.08375C7.1325 1.03875 12.0713 1.03875 15.12 4.08375L16.1362 5.1H13.2038C12.705 5.1 12.3038 5.50125 12.3038 6C12.3038 6.49875 12.705 6.9 13.2038 6.9H18.3075C18.8063 6.9 19.2075 6.49875 19.2075 6V0.9C19.2075 0.40125 18.8063 0 18.3075 0C17.8088 0 17.4075 0.40125 17.4075 0.9V3.82875L16.3913 2.8125C12.6413 -0.9375 6.5625 -0.9375 2.81625 2.8125C1.19625 4.4325 0.27375 6.49125 0.05625 8.60625C0.00375 9.10125 0.36375 9.54375 0.85875 9.5925C1.35375 9.64125 1.79625 9.285 1.845 8.79C2.025 7.0725 2.77125 5.40375 4.0875 4.08375ZM19.1513 10.5938C19.2038 10.0988 18.8438 9.65625 18.3488 9.6075C17.8538 9.55875 17.4113 9.915 17.3625 10.41C17.1863 12.1275 16.4363 13.8 15.12 15.1163C12.075 18.1613 7.13625 18.1613 4.0875 15.1163L3.07125 14.1H6.00375C6.5025 14.1 6.90375 13.6988 6.90375 13.2C6.90375 12.7013 6.5025 12.3 6.00375 12.3H0.9C0.40125 12.3 0 12.7013 0 13.2V18.3C0 18.7987 0.40125 19.2 0.9 19.2C1.39875 19.2 1.8 18.7987 1.8 18.3V15.3713L2.81625 16.3875C6.56625 20.1375 12.645 20.1375 16.3913 16.3875C18.0113 14.7675 18.9338 12.7088 19.1513 10.5938Z" fill="#F3951D"/>
</svg>
                                        <?php echo rb_format_views( $views ); ?>
                                    </span>
                                <?php endif; ?>
																                                <span class="rb-article__meta-item" style="	position: relative;
	left: 6px;">
<svg width="20" height="20" viewBox="0 0 17 13" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M8.10211 1.35C6.26836 1.35 4.76086 2.1825 3.60492 3.25406C2.52211 4.26094 1.77398 5.45625 1.39148 6.3C1.77398 7.14375 2.52211 8.33906 3.60492 9.34594C4.76086 10.4175 6.26836 11.25 8.10211 11.25C9.93586 11.25 11.4434 10.4175 12.5993 9.34594C13.6821 8.33906 14.4302 7.14375 14.8127 6.3C14.4302 5.45625 13.6821 4.26094 12.5993 3.25406C11.4434 2.1825 9.93586 1.35 8.10211 1.35ZM2.68523 2.26688C4.00992 1.035 5.82961 0 8.10211 0C10.3746 0 12.1943 1.035 13.519 2.26688C14.8352 3.49031 15.7155 4.95 16.1346 5.95406C16.2274 6.17625 16.2274 6.42375 16.1346 6.64594C15.7155 7.65 14.8352 9.1125 13.519 10.3331C12.1943 11.5622 10.3746 12.6 8.10211 12.6C5.82961 12.6 4.00992 11.565 2.68523 10.3331C1.36898 9.10969 0.488672 7.65 0.0696094 6.64594C-0.0232031 6.42375 -0.0232031 6.17625 0.0696094 5.95406C0.488672 4.95 1.36898 3.4875 2.68523 2.26688ZM8.10211 8.55C9.34523 8.55 10.3521 7.54312 10.3521 6.3C10.3521 5.4675 9.8993 4.73906 9.22711 4.35094C9.18774 6.03 7.83211 7.38562 6.15305 7.425C6.54117 8.09719 7.26961 8.55 8.10211 8.55ZM5.86336 6.06375C5.93367 6.07219 6.00398 6.075 6.07711 6.075C7.06992 6.075 7.87711 5.26781 7.87711 4.275C7.87711 4.20187 7.87149 4.13156 7.86586 4.06125C6.81399 4.17094 5.97586 5.00906 5.86617 6.06094L5.86336 6.06375ZM7.14586 2.82938C7.44961 2.745 7.77024 2.70281 8.0993 2.70281C8.3468 2.70281 8.59149 2.72812 8.82492 2.77594C8.83336 2.77875 8.83899 2.77875 8.84742 2.78156C10.4759 3.12469 11.6993 4.57312 11.6993 6.30281C11.6993 8.29125 10.0877 9.90281 8.0993 9.90281C6.3668 9.90281 4.92117 8.67937 4.57805 7.05094C4.52742 6.80906 4.4993 6.55875 4.4993 6.30281C4.4993 5.99344 4.53867 5.68969 4.6118 5.40281C4.61742 5.38312 4.62023 5.36625 4.62586 5.34938C4.96055 4.12875 5.92242 3.16687 7.14305 2.83219L7.14586 2.82938Z" fill="#F3951D"/>
</svg>


                                    100
                                </span>
                            </div>
                        </div>
                    </div>

                <?php endif; ?>

                <!-- Оглавление -->
                <?php if ( ! empty( $toc_items ) ) : ?>
                    <div class="rb-article__toc" id="rb-toc">
                        <p class="rb-article__toc-title">Содержание</p>
                        <ol class="rb-article__toc-list">
                            <?php foreach ( $toc_items as $i => $item ) :
                                $text   = strip_tags( $item );
                                $anchor = 'toc-' . sanitize_title( $text );
                            ?>
                                <li>
                                    <a href="#<?php echo esc_attr( $anchor ); ?>"><?php echo esc_html( $text ); ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ol>
                    </div>
                <?php endif; ?>
</div>
            </aside>

        </div>
        <!-- /layout -->
<?php
if (!empty($builder)) {
  foreach ($builder as $b) {
    $pos = $b['pos'] ?? '';
    if ($pos !== 'after_content') continue;

    $type = $b['_type'] ?? '';
    if ($type === 'heading') {
      $tag  = in_array(($b['tag'] ?? 'h2'), array('h2','h3','h4'), true) ? $b['tag'] : 'h2';
      $text = trim((string)($b['text'] ?? ''));
      if ($text !== '') echo '<' . $tag . ' class="rb-article__builder-title">' . esc_html($text) . '</' . $tag . '>';
    } elseif ($type === 'text') {
      $html = (string)($b['content'] ?? '');
      if ($html !== '') echo '<div class="rb-article__builder-text">' . apply_filters('the_content', $html) . '</div>';
    } elseif ($type === 'shortcode') {
      $code = trim((string)($b['code'] ?? ''));
      if ($code !== '') echo '<div class="rb-article__builder-shortcode">' . do_shortcode($code) . '</div>';
    } elseif ($type === 'image') {
      $src = trim((string)($b['image'] ?? ''));
      if ($src !== '') {
        $alt = (string)($b['alt'] ?? '');
        $cap = (string)($b['caption'] ?? '');
        $al  = (string)($b['align'] ?? '');
        $cls = 'rb-article__builder-image' . ($al ? ' is-' . sanitize_html_class($al) : '');
        echo '<figure class="' . esc_attr($cls) . '"><img src="' . esc_url($src) . '" alt="' . esc_attr($alt) . '" loading="lazy">';
        if ($cap !== '') echo '<figcaption>' . esc_html($cap) . '</figcaption>';
        echo '</figure>';
      }
    }
  }
}
?>
    </div>
    <!-- /container -->

    <!-- ══ БЛОК: Запишитесь на приём ══ -->


    <!-- ══ БЛОК: Похожие статьи ══ -->
    <?php if ( $show_related_articles && $related_query instanceof WP_Query && $related_query->have_posts() ) : ?>
        <div class="rb-article__related">
            <div class="rb-container">
                <div class="rb-article__related-header rb-page-header">
                    <h2 class="rb-article__related-title ">
						
						Похожие статьи по теме</h2>
                    <div class="rb-article__related-arrows">
                        <a href="#" class="related-arrow-prev">
                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                                <rect width="32" height="32" rx="4" fill="#F3951D"/>
                                <path d="M19 22L12.5 16L18.5 10" stroke="white"/>
                            </svg>
                        </a>
                        <a href="#" class="related-arrow-next">
                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                                <rect width="32" height="32" rx="4" transform="matrix(-1 0 0 1 32 0)" fill="#F3951D"/>
                                <path d="M13 22L19.5 16L13.5 10" stroke="white"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="swiper sl-swiper-related">
                    <div class="swiper-wrapper">
                        <?php while ( $related_query->have_posts() ) : $related_query->the_post();
                            $r_id   = get_the_ID();
                            $r_date = get_the_date( 'Y-m-d' );
                        ?>
                            <div class="swiper-slide">
                                <a class="rb-related-card" href="<?php the_permalink(); ?>">
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <div class="rb-related-card__img">
                                            <?php the_post_thumbnail( 'medium' ); ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="rb-related-card__info">
                                        <span class="rb-related-card__date"><?php echo rb_format_date_ru( $r_date ); ?></span>
                                        <p class="rb-related-card__title"><?php the_title(); ?></p>
                                        <p class="rb-related-card__excerpt"><?php echo wp_trim_words( get_the_excerpt(), 12, '…' ); ?></p>
                                    </div>
                                </a>
                            </div>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </div>
                </div>

            </div>
        </div>
    <?php endif; ?>

</div>

<?php get_footer(); ?>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Оглавление: подсветка активного пункта ──
    var toc = document.getElementById('rb-toc');
    if (toc) {
        var headings = Array.from(document.querySelectorAll('.rb-article__body h2[id]'));
        var tocLinks = Array.from(toc.querySelectorAll('a'));

        function updateToc() {
            var scrollY  = window.scrollY + 120;
            var current  = headings[0];
            headings.forEach(function (h) {
                if (h.getBoundingClientRect().top + window.scrollY <= scrollY) current = h;
            });
            tocLinks.forEach(function (a) {
                a.parentElement.classList.toggle('is-active', a.getAttribute('href') === '#' + current.id);
            });
        }

        window.addEventListener('scroll', updateToc, { passive: true });
        updateToc();
    }

    if (document.querySelector('.sl-swiper-related')) {
        new Swiper('.sl-swiper-related', {
            slidesPerView: 1.2,
            spaceBetween: 16,
            navigation: {
                prevEl: '.related-arrow-prev',
                nextEl: '.related-arrow-next',
            },
            breakpoints: {
                600: { slidesPerView: 2, spaceBetween: 20 },
                900: { slidesPerView: 3, spaceBetween: 20 },
                1200: { slidesPerView: 4, spaceBetween: 24 },
            }
        });
    }

});
</script>
<style>

.rb-article__title {
    font-size: 32px;
    font-weight: 800;
    line-height: 1.2;
    text-transform: uppercase;

    margin-bottom: 24px;
}

.rb-article__body {
    font-size: 15px;
    line-height: 1.7;
    color: #333;
}
.rb-article__body h2 {
    font-size: 20px;
    font-weight: 700;
    color: #1a1a2e;
    margin: 32px 0 12px;
}
.rb-article__body h3 {
    font-size: 17px;
    font-weight: 600;
    margin: 24px 0 10px;
}
.rb-article__body p { margin-bottom: 14px; }
.rb-article__body ul,
.rb-article__body ol { padding-left: 20px; margin-bottom: 14px; }
.rb-article__body li { margin-bottom: 6px; }
.rb-article__body img { max-width: 100%; height: auto; border-radius: 8px; }

.rb-article__author-card {
    background: #F9F9F9;
    border: 1px solid #eee;
    border-radius: 12px;
    padding: 20px;
}
.rb-article__author-photo {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    overflow: hidden;
    margin-bottom: 10px;
}
.rb-article__author-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.rb-article__author-label {
    font-size: 16px;
    color: #999;
    margin-bottom: 4px;
}
.rb-article__author-name {
    font-size: 15px;
    font-weight: 700;
    color: #1a1a2e;
    margin-bottom: 4px;
    line-height: 1.3;
}
.rb-article__author-name a { color: inherit; text-decoration: none; }
.rb-article__author-name a:hover { color: #F3951D; }
.rb-article__author-area {
    font-size: 16px;
    color: #666;
    margin-bottom: 8px;
    line-height: 1.4;
}
.rb-article__author-bio {
    font-size: 12px;
    color: #888;
    line-height: 1.5;
    margin-bottom: 4px;
}

.rb-article__meta {
    display: flex;
    flex-direction: column;
    gap: 12px;
    padding-top: 12px;
    border-top: 1px solid #f0f0f0;
    margin-top: 12px;
}
.rb-article__meta-row {
    display: flex;
    align-items: center;
    gap: 20px;
}
.rb-article__meta-item {

    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 14px;
    color: #888;
    white-space: nowrap;
}
.rb-article__meta-item svg { flex-shrink: 0; }

.rb-article__toc {
    background: #fff;
    border: 1px solid #eee;
    border-radius: 12px;
    padding: 20px;
}
.rb-article__toc-title {
    font-size: 15px;
    font-weight: 700;
    color: #1a1a2e;
    margin-bottom: 0px;
}
.rb-article__toc-list {
    list-style: none;
    padding: 0;
    margin: 0;
    counter-reset: toc-counter;
}
.rb-article__toc-list li {
    counter-increment: toc-counter;
    margin-bottom: 8px;
    display: flex;
    gap: 6px;
    align-items: flex-start;
}
.rb-article__toc-list li::before {
    content: counter(toc-counter) ".";
    font-size: 13px;
    color: #aaa;
    flex-shrink: 0;
    padding-top: 1px;
}
.rb-article__toc-list a {
    font-size: 13px;
    color: #444;
    text-decoration: none;
    line-height: 1.4;
    transition: color 0.2s;
}
.rb-article__toc-list a:hover { color: #F3951D; }
.rb-article__toc-list li.is-active a {
    color: #F3951D;
    font-weight: 600;
}

.olimp-appoint {
    position: relative;
    height: 400px;
    background: url(https://olimp5.devmoab.ru/wp-content/themes/olimp5/assets/img/form.png);
    border-radius: 16px;
    overflow: hidden;
    font-family: 'Manrope', sans-serif;
	background-size: contain;
	background-repeat: no-repeat;
}


.olimp-appoint__title{
  position: absolute;
  width: 630px;
  height: 60px;
  left: 40px;
  top: 51px;

  font-family: 'Bebas Neue', sans-serif;
  font-style: normal;
  font-weight: 700;
  font-size: 60px;
  line-height: 60px;
  display: flex;
  align-items: center;
  text-transform: uppercase;
  color: #F3951D;
  margin: 0;
}

.olimp-appoint__text{
  position: absolute;
  width: 466px;
  height: 50px;
  left: 40px;
  top: 150px;

  font-family: 'Manrope', sans-serif;
  font-style: normal;
  font-weight: 400;
  font-size: 18px;
  line-height: 140%;
  color: #333333;
  margin: 0;
}

.olimp-appoint__btn{
  box-sizing: border-box;

  display: flex;
  flex-direction: row;
  justify-content: center;
  align-items: center;
  padding: 16px 8px;
  gap: 10px;

  position: absolute;
  width: 145px;
  height: 56px;
  left: 40px;
  top: 284px;

  border: 1px solid #495B80;
  border-radius: 9px;

  font-family: 'Manrope', sans-serif;
  font-style: normal;
  font-weight: 400;
  font-size: 14px;
  line-height: 18px;
  text-align: center;
  color: #495B80;

  text-decoration: none;
}

.olimp-appoint__mask {
    position: absolute;
    width: 808.99px;
    height: 808.99px;
    left: 796.66px;
    top: -83.99px;
    pointer-events: none;
}

.olimp-appoint__frame{
    position: absolute;
    width: 572.27px;
    height: 482.82px;
    left: -1px;
    top: 80px;
    border-radius: 12px;
    transform: rotate(-45deg);
    overflow: hidden;
}

.olimp-appoint__frame--border{
  border: 20px solid rgba(243, 149, 29, 0.4);
  box-sizing: border-box;
  z-index: 1;
  background: transparent;
}

.olimp-appoint__frame--inner{
  border: 0;
  z-index: 2;
}

.olimp-appoint__frame--border {
    border: 20px solid rgb(243 149 29);
    box-sizing: border-box;
    z-index: 6;
}

.olimp-appoint__frame--inner{
  z-index: 2;
}

.olimp-appoint__img{
  width: 100%;
  height: 100%;

  object-fit: cover;
  object-position: center;

  transform: rotate(45deg) scale(1.05);
}

.rb-article__related {
    margin-top: 60px;
    padding-bottom: 60px;
}
.rb-article__related-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
}
.rb-article__related-title {
    font-size: 22px;
    font-weight: 900;
    text-transform: uppercase;
    margin: 0;
}
.rb-article__related-arrows {
    display: flex;
    gap: 8px;
}
.rb-article__related-arrows a {
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 1;
    transition: opacity 0.2s;
}
.rb-article__related-arrows a:hover { opacity: 0.8; }

.rb-related-card {
    display: block;
    text-decoration: none;
    color: inherit;
}
.rb-related-card__img {
    border-radius: 8px;
    overflow: hidden;
    aspect-ratio: 4 / 3;
    margin-bottom: 12px;
}
.rb-related-card__img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.3s;
}
.rb-related-card:hover .rb-related-card__img img {
    transform: scale(1.04);
}
.rb-related-card__date {
    font-size: 12px;
    color: #999;
    display: block;
    margin-bottom: 6px;
}
.rb-related-card__title {
    font-size: 14px;
    font-weight: 700;
    color: #1a1a2e;
    line-height: 1.4;
    margin-bottom: 6px;
    transition: color 0.2s;
}
.rb-related-card:hover .rb-related-card__title { color: #F3951D; }
.rb-related-card__excerpt {
    font-size: 13px;
    color: #777;
    line-height: 1.4;
    margin: 0;
}

@media (max-width: 1024px) {
    .rb-article__layout {
        grid-template-columns: 1fr 260px;
        gap: 28px;
    }
}
@media (max-width: 900px) {
	.olimp-appoint {
    background: url(https://olimp5.devmoab.ru/wp-content/themes/olimp5/assets/img/form_mob.png);
	background-size: cover;
	background-repeat: no-repeat;
}
    .rb-article__layout {
        grid-template-columns: 1fr;
    }
    .rb-article__sidebar {
        position: static;
        order: -1;
    }
    .rb-article__author-card {
        display: grid;
        grid-template-columns: 72px 1fr;
        column-gap: 14px;
    }
    .rb-article__author-photo { grid-row: 1 / 4; margin-bottom: 0; }
    .rb-article__author-label,
    .rb-article__author-name,
    .rb-article__author-area  { grid-column: 2; }
    .rb-article__author-bio,
    .rb-article__meta         { grid-column: 1 / -1; }

    .rb-article__title { font-size: 24px; }

    .rb-article__cta-image { display: none; }
    .rb-article__cta-text  { flex: 1; padding: 30px 20px; }
    .rb-article__cta-title { font-size: 22px; }
}

@media (max-width: 600px) {
    .rb-article__related-title { font-size: 18px; }
}
.rb-article__layout {
    display: grid;
    grid-template-columns: 1fr 280px;
    gap: 40px;
    align-items: start;
}

.rb-article__title {
    font-size: 32px;
    font-weight: 800;
    line-height: 1.2;
    text-transform: uppercase;
    margin-bottom: 24px;
}
	.article-content {
		margin-bottom: 100px;
	}
.rb-article__body {
    font-size: 15px;
    line-height: 1.7;
    color: #333;
}

.rb-article__body h2 {
    font-size: 20px;
    font-weight: 700;
    color: #42567d;
    margin: 32px 0 12px;
}

.rb-article__body h3 {
    font-size: 17px;
    font-weight: 600;
    margin: 24px 0 10px;
}

.rb-article__body p {
    margin-bottom: 14px;
}

.rb-article__body ul,
.rb-article__body ol {
    padding-left: 20px;
    margin-bottom: 14px;
}

.rb-article__body li {
    margin-bottom: 6px;
}

.rb-article__body img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
}



.rb-article__author-card {
    background: #F9F9F9;
    border: 1px solid #eee;
    border-radius: 12px;
    padding: 20px;
}

.rb-article__author-photo {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    overflow: hidden;
    margin-bottom: 10px;
	background: #FFFFFF;
}

.rb-article__author-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.rb-article__author-label {
    font-size: 16px;
    color: #999;
    margin-bottom: 4px;
}

.rb-article__author-name {
    font-size: 15px;
    font-weight: 700;
    color: #1a1a2e;
    margin-bottom: 4px;
    line-height: 1.3;
}

.rb-article__author-name a {
    color: inherit;
    text-decoration: none;
}

.rb-article__author-name a:hover {
    color: #F3951D;
}

.rb-article__author-area {
    font-size: 16px;
    color: #666;
    margin-bottom: 8px;
    line-height: 1.4;
}

.rb-article__author-bio {
    font-size: 12px;
    color: #888;
    line-height: 1.5;
    margin-bottom: 12px;
}
.rb-article__meta-item {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 14px;
    color: #888;
    white-space: nowrap;
}

.rb-article__meta-item svg {
    flex-shrink: 0;
}

.rb-article__toc {
    background: #fff;
    border: 1px solid #eee;
    border-radius: 12px;
    padding: 20px;
}

.rb-article__toc-title {
    font-size: 15px;
    font-weight: 700;
    color: #1a1a2e;
    margin-bottom: 0px;
}

.rb-article__toc-list {
    list-style: none;
    padding: 0;
    margin: 0;
    counter-reset: toc-counter;
}

.rb-article__toc-list li {
    counter-increment: toc-counter;
    margin-bottom: 8px;
    padding-left: 0;
    display: flex;
    gap: 6px;
    align-items: flex-start;
}

.rb-article__toc-list li::before {
    content: counter(toc-counter) ".";
    font-size: 13px;
    color: #aaa;
    flex-shrink: 0;
    padding-top: 1px;
}

.rb-article__toc-list a {
    font-size: 13px;
    color: #444;
    text-decoration: none;
    line-height: 1.4;
    transition: color 0.2s;
}

.rb-article__toc-list a:hover {
    color: #F3951D;
}

.rb-article__toc-list li.is-active a {
    color: #F3951D;
    font-weight: 600;
}

@media (max-width: 900px) {
    .rb-article__layout {
        grid-template-columns: 1fr;
    }

    .rb-article__sidebar {
        position: static;
        order: -1;
    }

    .rb-article__author-card {
        display: flex;
        grid-template-columns: 72px 1fr;
        grid-template-rows: auto auto auto auto;
        column-gap: 14px;
		        flex-direction: column;
    }

    .rb-article__author-photo {
        grid-row: 1 / 4;
        margin-bottom: 0;
        align-self: start;
    }

    .rb-article__author-label  { grid-column: 2; }
    .rb-article__author-name   { grid-column: 2; }
    .rb-article__author-area   { grid-column: 2; }

    .rb-article__author-bio,
    .rb-article__meta {
        grid-column: 1 / -1;
    }

    .rb-article__title {
        font-size: 24px;
    }
}

@media (max-width: 600px) {
    .rb-article__layout {
        gap: 20px;
    }
}

@media (max-width: 600px){

  .olimp-appoint{
    height: 520px;
    border-radius: 16px;
  }

  .olimp-appoint__title,
  .olimp-appoint__text,
  .olimp-appoint__btn{
    position: static !important;
    width: auto !important;
    height: auto !important;
    left: auto !important;
    top: auto !important;
  }

  .olimp-appoint{
    padding: 26px 22px;
  }

  .olimp-appoint__title{
    font-size: 44px;
    line-height: 44px;
    margin: 0 0 14px 0;
  }

  .olimp-appoint__text{
    font-size: 16px;
    line-height: 140%;
    margin: 0 0 18px 0;
    max-width: 260px;
  }

  .olimp-appoint__btn{
    width: 170px !important;
    height: 56px !important;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .olimp-appoint__mask{
    left: auto !important;
    top: auto !important;

    right: -80px;
    bottom: -90px;

    width: 420px;
    height: 420px;
  }

  .olimp-appoint__frame{
    width: 290px;
    height: 290px;
    left: 95px;
    top: 70px;
    border-radius: 12px;
    transform: rotate(-45deg);
    overflow: hidden;
  }

  .olimp-appoint__img{
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transform: rotate(45deg) scale(1.08);
  }
}
.olimp-appoint__img{
  position: absolute;
    left: 32%;
    top: 47%;

  width: 142%;
  height: 142%;

  object-fit: cover;
  object-position: center;

  transform: translate(-50%, -50%) rotate(45deg);
  transform-origin: center;
}

.olimp-appoint__frame{
  overflow: hidden;
  position: absolute;
}
	@media (max-width: 600px) {
    .olimp-appoint__frame {
        width: 290px;
        height: 290px;
        left: 95px;
        top: 70px;
        border-radius: 12px;
        transform: rotate(-45deg);
        overflow: hidden;
    }
}
	@media (max-width: 900px) {
	    .rb-page-header h1, .rb-page-header {
        font-size: 40px!important;
        line-height: 40px!important;
        width: auto;
    }
	}
	ol, ul {
    margin: 0;
    padding: 0;
    list-style-type: unset;
}
	.rb-page-header h1, .rb-page-header, h1.rb-page-header {
    font-family: 'Bebas Neue';
    font-style: normal;
    font-weight: 700;
    font-size: 62px;
    line-height: 66px;
}
	.olimp-cta-wrap{
		    padding-top: 50px;
    padding-bottom: 50px;
	}
	.olimp-cta-wrap .rb-container {
		padding: 0;
	}
	.rb-article__layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 300px;
    gap: 40px;
    align-items: start;
}

	.rb-page,
.rb-container,
.rb-article,
.rb-article__layout {
    overflow: visible;
}
	.rb-article__layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 300px;
    gap: 40px;
    align-items: start;
}

.rb-article__content {
    min-width: 0;
}


.rb-page,
.rb-container,
.rb-article,
.rb-article__layout {
    overflow: visible;
}

@media (max-width: 900px) {
    .rb-article__layout {
        grid-template-columns: 1fr;
    }

    .rb-article__sidebar-sticky {
        position: static;
    }
}
	.rb-article__layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 300px;
    gap: 40px;
    align-items: start;
}

.rb-article__content {
    min-width: 0;
}


@media (max-width: 900px) {
    .rb-article__layout {
        grid-template-columns: 1fr;
    }

    .rb-article__sidebar {
        order: -1;
    }

    .rb-article__sidebar-sticky {
        position: static;
    }
}
	.rb-article__layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 300px;
    gap: 40px;
    align-items: start;
}

.rb-article__content {
    min-width: 0;
}

.rb-article__sidebar {
    position: relative;
    min-width: 0;
    align-self: start;
}

.rb-article__sidebar-sticky {
    position: relative;
    top: 0;
    transform: translateY(0);
    transition: transform 0.08s linear;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.rb-page,
.rb-container,
.rb-article,
.rb-article__layout {
    overflow: visible !important;
}

.rb-article__author-card {
    background: #F9F9F9;
    border: 1px solid #eee;
    border-radius: 12px;
    padding: 20px;
}

@media (max-width: 900px) {
    .rb-article__layout {
        grid-template-columns: 1fr;
    }

    .rb-article__sidebar {
        order: -1;
    }

    .rb-article__sidebar-sticky {
        transform: none !important;
    }
}
</style>
<script>

(function () {
    var toc = document.getElementById('rb-toc');
    if (!toc) return;

    var headings = Array.from(document.querySelectorAll('.rb-article__body h2[id]'));
    var links    = Array.from(toc.querySelectorAll('a'));
    if (!headings.length || !links.length) return;

    function onScroll() {
        var scrollY  = window.scrollY + 100;
        var current  = headings[0];

        headings.forEach(function (h) {
            if (h.getBoundingClientRect().top + window.scrollY <= scrollY) {
                current = h;
            }
        });

        links.forEach(function (a) {
            var li = a.parentElement;
            if (a.getAttribute('href') === '#' + current.id) {
                li.classList.add('is-active');
            } else {
                li.classList.remove('is-active');
            }
        });
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
})();
document.addEventListener('DOMContentLoaded', function () {
    var sidebar = document.querySelector('.rb-article__sidebar');
    var inner = document.querySelector('.rb-article__sidebar-sticky');
    var layout = document.querySelector('.rb-article__layout');
    var content = document.querySelector('.rb-article__content');

    if (!sidebar || !inner || !layout || !content) return;
    if (window.innerWidth <= 900) return;

    function updateSidebarPosition() {
        if (window.innerWidth <= 900) {
            inner.style.transform = 'translateY(0)';
            return;
        }

        var layoutTop = layout.getBoundingClientRect().top + window.pageYOffset;
        var contentHeight = content.offsetHeight;
        var innerHeight = inner.offsetHeight;

        var startOffset = 120; // отступ сверху
        var scrollTop = window.pageYOffset;

        var maxTranslate = Math.max(0, contentHeight - innerHeight);
        var translate = scrollTop - layoutTop + startOffset;

        if (translate < 0) translate = 0;
        if (translate > maxTranslate) translate = maxTranslate;

        inner.style.transform = 'translateY(' + translate + 'px)';
    }

    window.addEventListener('scroll', updateSidebarPosition, { passive: true });
    window.addEventListener('resize', updateSidebarPosition);
    updateSidebarPosition();
});
</script>
<script>
(function () {
  var toc = document.getElementById('rb-toc');
  if (!toc) return;
  function getOffset() {
    var header = document.querySelector('.site-header, header, .header, .rb-header');
    var h = header ? header.getBoundingClientRect().height : 0;
    return Math.round(h + 25); // +12px зазор
  }

  toc.addEventListener('click', function (e) {
    var a = e.target.closest('a[href^="#"]');
    if (!a) return;

    var id = a.getAttribute('href').slice(1);
    var target = document.getElementById(id);
    if (!target) return;

    e.preventDefault();

    var offset = getOffset();
    var top = target.getBoundingClientRect().top + window.pageYOffset - offset;

    window.scrollTo({ top: top, behavior: 'smooth' });

    history.pushState(null, '', '#' + id);
  });

})();
</script>
<?php get_footer(); ?>