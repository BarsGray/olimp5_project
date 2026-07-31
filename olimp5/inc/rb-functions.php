<?php

/*Usefull functions*/


/*RB title*/

function rb_title( $title_text = '' ) {

  if ( ! $title_text ) {
    $title_text = get_the_title();
  }

  $title = explode( ' ', $title_text );
  $new_title = array_shift( $title );
  echo '<span>' . $new_title . '</span> ' . implode( ' ', $title );

}

/* Breadcrumbs */

function rb_breadcrumbs() {

    if ( ! is_front_page() ) {

    echo '<ul class="page-breadcrumbs f-center" itemscope itemtype="https://schema.org/BreadcrumbList">
            <li class="page-breadcrumbs__item f-center" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a href="' . site_url() . '" class="page-breadcrumbs__link" itemprop="item">
                    <span itemprop="name">Готовая еда</span>
                </a>
                <meta itemprop="position" content="0">
                <span class="page-breadcrumbs__point">
                    |
                </span>
            </li>';

    if ( is_home() ) {

      echo '<li class="page-breadcrumbs__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <span class="page-breadcrumbs__link" itemprop="item">
                    Новости
                </span>
                <meta itemprop="position" content="1">
            </li>';

    } else if ( is_post_type_archive() ) {

      echo '<li class="page-breadcrumbs__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <span class="page-breadcrumbs__link" itemprop="item">' . str_replace( array( 'Архивы:', '<span>', '</span>' ), '', get_the_archive_title() ) . '</span>
                <meta itemprop="position" content="1">
            </li>';

    } else if ( is_singular( 'review' ) ) {

      $review_pages = get_pages(
        array(
          'meta_key' => '_wp_page_template',
          'meta_value' => 'template-page/page-review.php'
        )
      );

      echo '<li class="page-breadcrumbs__item f-center" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a href="' . get_the_permalink( $review_pages[0]->ID ) . '" class="page-breadcrumbs__link" itemprop="item">
                    <span itemprop="name">Все отзывы</span>
                </a>
                <meta itemprop="position" content="1">
                <span class="page-breadcrumbs__point">
                    |
                </span>
            </li>
            <li class="page-breadcrumbs__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <span class="page-breadcrumbs__link" itemprop="item">' . get_the_title() . '</span>
                <meta itemprop="position" content="2">
            </li>';

    }  else if ( is_singular( 'ration' ) ) {

      $ration_pages = get_pages(
        array(
          'meta_key' => '_wp_page_template',
          'meta_value' => 'template-page/page-ration.php'
        )
      );

      echo '<li class="page-breadcrumbs__item f-center" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a href="' . get_the_permalink( $ration_pages[0]->ID ) . '" class="page-breadcrumbs__link" itemprop="item">
                    <span itemprop="name">Все рационы</span>
                </a>
                <meta itemprop="position" content="1">
                <span class="page-breadcrumbs__point">
                    |
                </span>
            </li>
            <li class="page-breadcrumbs__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <span class="page-breadcrumbs__link" itemprop="item">' . get_the_title() . '</span>
                <meta itemprop="position" content="2">
            </li>';

    }  else if ( is_tax( 'city' ) || is_tax( 'brand' ) || is_tax( 'ration_cat' ) ) {

        // $city_pages = get_pages(
        //   array(
        //     'meta_key' => '_wp_page_template',
        //     'meta_value' => 'template-page/page-ration.php'
        //   )
        // );
        $curr_object = get_queried_object();

        // echo '<li class="page-breadcrumbs__item f-center" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        //           <a href="' . get_the_permalink( $city_pages[0]->ID ) . '" class="page-breadcrumbs__link" itemprop="item">
        //               <span itemprop="name">' . get_the_title( $city_pages[0]->ID ) . '</span>
        //           </a>
        //           <meta itemprop="position" content="1">
        //           <span class="page-breadcrumbs__point">
        //               |
        //           </span>
        //       </li>
        //       <li class="page-breadcrumbs__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        //           <span class="page-breadcrumbs__link" itemprop="item">' . $curr_object->name . '</span>
        //           <meta itemprop="position" content="2">
        //       </li>';
         echo '<li class="page-breadcrumbs__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                  <span class="page-breadcrumbs__link" itemprop="item">' . $curr_object->name . '</span>
                  <meta itemprop="position" content="2">
              </li>';

    }  else if ( is_tax( 'promo' ) ) {

        $action_pages = get_pages(
          array(
            'meta_key' => '_wp_page_template',
            'meta_value' => 'template-page/page-action.php'
          )
        );
        $curr_object = get_queried_object();

        echo '<li class="page-breadcrumbs__item f-center" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                  <a href="' . get_the_permalink( $action_pages[0]->ID ) . '" class="page-breadcrumbs__link" itemprop="item">
                      <span itemprop="name">' . get_the_title( $action_pages[0]->ID ) . '</span>
                  </a>
                  <meta itemprop="position" content="1">
                  <span class="page-breadcrumbs__point">
                      |
                  </span>
              </li>
              <li class="page-breadcrumbs__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                  <span class="page-breadcrumbs__link" itemprop="item">' . $curr_object->name . '</span>
                  <meta itemprop="position" content="2">
              </li>';

    }
      else if ( is_404() ) {

      echo '<li class="page-breadcrumbs__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <span class="page-breadcrumbs__link" itemprop="item">Страница 404</span>
                <meta itemprop="position" content="1">
            </li>';

    } else if ( is_author() ) {

      $author_id = get_the_author_meta('ID');

      echo '<li class="page-breadcrumbs__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <span class="page-breadcrumbs__link" itemprop="item">Автор: ' . get_the_author_meta( 'display_name', $author_id ) . '</span>
                <meta itemprop="position" content="1">
            </li>';

    } else if ( is_search() ) {

      echo '<li class="page-breadcrumbs__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <span class="page-breadcrumbs__link" itemprop="item">Поиск: ' . get_search_query() . '</span>
                <meta itemprop="position" content="1">
            </li>';

    } else  {

      echo '<li class="page-breadcrumbs__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <span class="page-breadcrumbs__link" itemprop="item">' . get_the_title() . '</span>
                <meta itemprop="position" content="1">
            </li>';

        }

    echo '</ul>';

    }
    return false;

}

/*Сколнение слов*/

function rb_days_declension( $number ){

  $word = 'день';


  if ( intval( $number ) ) {

    if( $number > 1 && $number < 5 ) {

      $word = 'дня';

    } else if ( $number >= 5 ) {

      $word = 'дней';

    }

    if ( $number % 7 == 0 ) {

      $word = 'недели';
      $number = $number/7;

      if ( $number == 1 ) {

        $word = 'неделя';

      } else if ( $number > 5 ){

        $word = 'недель';

      }

    }

  } else {

    $word = "Укажите параметры функции - число и слово в именительном падеже.";

  }

  return $number . ' ' . $word;

}


function rb_comment_declension( $number ){

  $word = 'комментарий';


  if ( $number ) {

    if( $number > 1 && $number < 5 ) {

      $word = 'комментария';

    } else if ( $number >= 5 ) {

      $word = 'комментариев';

    }

  } else {

    $word = "Укажите параметры функции - число и слово в именительном падеже.";

  }

  return $word;

}

/*Сколнение слов*/

function rb_word_declension( $number, $word ){

  if( $number > 10 && $number < 100 ) {

    $number = substr( $number, 1 );

  }


  if ( $word && $number ) {

    if( $number > 1 && $number < 5 ) {

      $word = $word . 'a';

    } else if ( $number >= 5 ) {

      $word = $word . 'ов';

    }

  } else {

    // $word = "Укажите параметры функции - число и слово в именительном падеже.";
    $word = $word . 'ов';

  }

  return $word;

}

function rb_years_declension($years) {
    $years = abs((int)$years);

    $n100 = $years % 100;
    if ($n100 >= 11 && $n100 <= 14) {
        return 'лет';
    }

    $n10 = $years % 10;

    if ($n10 === 1) return 'год';
    if ($n10 >= 2 && $n10 <= 4) return 'года';

    return 'лет';
}


function formatPhone( $phone ){

  return str_replace( array( '(', ')', '-', ' ' ), '', $phone );

}

/**
 * Форматирует дату в русский формат: 12 января 2025
 */
function rb_format_date_ru( string $date ): string {
    $months = array(
        1  => 'января',   2  => 'февраля', 3  => 'марта',
        4  => 'апреля',   5  => 'мая',     6  => 'июня',
        7  => 'июля',     8  => 'августа', 9  => 'сентября',
        10 => 'октября',  11 => 'ноября',  12 => 'декабря',
    );
    $ts    = strtotime( $date );
    $day   = (int) date( 'j', $ts );
    $month = $months[ (int) date( 'n', $ts ) ];
    $year  = date( 'Y', $ts );
    return $day . ' ' . $month . ' ' . $year;
}


/**
 * Возвращает приблизительное время чтения в минутах.
 * Сначала берёт ручное значение, иначе считает по тексту (~200 слов/мин).
 */
function rb_get_read_time( int $post_id = 0 ): int {
    if ( ! $post_id ) {
        $post_id = (int) get_the_ID();
    }

    // Ручное значение из Carbon Fields
    if ( function_exists( 'carbon_get_post_meta' ) ) {
        $manual = (int) carbon_get_post_meta( $post_id, 'rb_post_read_time' );
        if ( $manual > 0 ) {
            return $manual;
        }
    }

    // Авто-расчёт по тексту
    $post    = get_post( $post_id );
    $content = $post ? wp_strip_all_tags( $post->post_content ) : '';
    $words   = str_word_count( $content );
    $minutes = (int) ceil( $words / 200 );

    return max( 1, $minutes );
}


/**
 * Возвращает количество просмотров.
 */
function rb_get_post_views( int $post_id = 0 ): int {
    if ( ! $post_id ) {
        $post_id = (int) get_the_ID();
    }

    if ( ! function_exists( 'carbon_get_post_meta' ) ) {
        return 0;
    }

    return (int) carbon_get_post_meta( $post_id, 'rb_post_views' );
}


/**
 * Форматирует число просмотров: 1200 → 1 200
 */
function rb_format_views( int $views ): string {
    return number_format( $views, 0, '', ' ' );
}

function rb_get_post_author_data( int $post_id = 0 ): ?array {

    if ( ! $post_id ) {
        $post_id = (int) get_the_ID();
    }

    if ( ! function_exists( 'carbon_get_post_meta' ) ) {
        return null;
    }

    $items = carbon_get_post_meta( $post_id, 'rb_post_author' );

    if ( empty( $items ) || ! is_array( $items ) ) {
        return null;
    }

    $author_id = (int) ( $items[0]['id'] ?? 0 );
    if ( ! $author_id ) {
        return null;
    }

    $author = get_post( $author_id );
    if ( ! $author || $author->post_status !== 'publish' ) {
        return null;
    }

    // Фото: сначала кастомное поле Carbon Fields, затем стандартная миниатюра
    $photo_cf_id = (int) carbon_get_post_meta( $author_id, 'rb_author_photo' );
    if ( $photo_cf_id ) {
        $photo_url = wp_get_attachment_image_url( $photo_cf_id, 'rb_medium_2x' ) ?: '';
    } else {
        $photo_url = get_the_post_thumbnail_url( $author_id, 'rb_medium_2x' ) ?: '';
    }

    return array(
        'id'    => $author_id,
        'name'  => get_the_title( $author_id ),
        'photo' => $photo_url,
        'area'  => (string) carbon_get_post_meta( $author_id, 'rb_author_area' ),
        'bio'   => (string) carbon_get_post_meta( $author_id, 'rb_author_bio' ),
        'link'  => (string) carbon_get_post_meta( $author_id, 'rb_author_link' ),
    );
}

