<?php 

get_header();

$curr_obj = get_queried_object();

$departments = get_terms( array( 'taxonomy' => 'departments', 'parent' => 0, ) );

?>

<style>

#doctors_search{
  position: relative;
}


#doctors_search .rb-search{
  position: relative;
  z-index: 2;
}


#doctors_search .search-doctors-wrap{
  position: absolute;
  left: 0;
  top: 100%;
  transform: translateY(8px);
  width: 100%;

  z-index: 9999;
  margin: 0;
  padding: 8px 0;
  list-style: none;

  background: #fff;
  border: 1px solid rgba(0,0,0,.08);
  border-radius: 12px;
  box-shadow: 0 10px 30px rgba(0,0,0,.08);

  max-height: 60vh;
  overflow-y: auto;
  overflow-x: hidden;

  box-sizing: border-box;
}

#doctors_search .search-doctors-wrap a{
  display: block;
  padding: 10px 14px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

#doctors_search .rb-search__input{ min-width: 0; width: 100%; }
#doctors_search .rb-search__btn{ flex: 0 0 auto; }
	.search-doctors-wrap{
  display: none;
}
.search-doctors-wrap.is-open{
  display: block;
}

</style>
<script>
document.addEventListener('DOMContentLoaded', function () {

  const wrap  = document.getElementById('doctors_search');
  if (!wrap) return;

  const input = wrap.querySelector('#doctors_search_input');
  const list  = wrap.querySelector('.search-doctors-wrap');
  if (!input || !list) return;

  const closeSearch = () => {
    list.classList.remove('show-search');   // ← ВАЖНО
    list.innerHTML = '';
  };

  closeSearch();

  document.addEventListener('click', function (e) {
    if (!wrap.contains(e.target)) {
      closeSearch();
    }
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      closeSearch();
    }
  });

  input.addEventListener('input', function () {
    if (!this.value.trim()) {
      closeSearch();
    }
  });

  window.showDoctorsSearch = function (html) {
    list.innerHTML = html || '';

    if (list.querySelector('li')) {
      list.classList.add('show-search');
    } else {
      closeSearch();
    }
  };

});
</script>

    <!-- doctors start -->
<div class="rb-doctors rb-page">
    <div class="rb-container ">
		
        <?php
		if (is_tax('departments')) { 
			$term = get_queried_object();
			$custom_field_value = get_term_meta($term->term_id, 'custom_field_key', true);

			if (!empty($custom_field_value)) {
				echo '<h1 class="rb-page-header">' . esc_html($custom_field_value) . '</h1>';
			} else {
				echo '<h1 class="rb-page-header">' . esc_html($term->name) . '</h1>';
			}
		}
		?>

        <div class="flex-wrap rb-doctors__wrap">
            <div class="col-3 col-m-12 rb-doctors__cat">
                <?php if( $departments ) : ?>
                    <span class="rb-doctors__cat-title">Направления</span>
                    <ul class="rb-doctors__cat-list">
                        <?php 
                            $rb_doc_class = ' ';
                            $rb_doc_parent_class = '';
                            foreach( $departments as $arItem ): 

                                $children_terms = get_terms( array( 'taxonomy' => 'departments', 'parent' => $arItem->term_id, 'hide_empty' => 1 ) );
                                
                                if ( $children_terms  && ! is_wp_error( $children_terms )  ) :

                                    $rb_doc_class = '';
                                    $rb_doc_parent_class = '';

                                    foreach( $children_terms as $children_term ) {
                                        if ( strpos( $_SERVER['REQUEST_URI'], $children_term->slug ) ) {
                                            $rb_doc_class = 'open';
                                            $rb_doc_parent_class = 'active';
                                        }
                                    }
                                    

                                ?>
                                    <li id="<?php echo $arItem->term_id; ?>">
                                        <a class="<?php echo $rb_doc_parent_class; ?> rb-doctors__cat-sublist--parent" href="<?php echo get_term_link( $arItem->term_id ); ?>"><?php echo $arItem->name; ?></a>
                                    </li>
                                    <ul class="rb-doctors__cat-sublist <?php echo $rb_doc_class; ?>">
                                        <?php foreach( $children_terms as $children_term ) : ?>
                                            <li>
                                                <a class="sublist-item <?php if ( strpos( $_SERVER['REQUEST_URI'], $children_term->slug ) ) : ?> sublist-active<?endif?>" href="<?php echo get_term_link( $children_term->term_id ); ?>"><?php echo $children_term->name; ?></a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>

                                <?php else : ?>
                                    <li id="<?php echo $arItem->term_id; ?>">
                                        <a <?php if ( strpos( $_SERVER['REQUEST_URI'], $arItem->slug ) ) : ?>class="active"<?endif?> href="<?php echo get_term_link( $arItem->term_id ); ?>"><?php echo $arItem->name; ?></a>
                                    </li>
                                <?php 
                                endif;
                            endforeach; 
                        ?>
                    </ul>
                <?php endif; ?>
            </div>
            <div class="col-9 col-m-12 rb-doctors__spec">
                <div class="rb-doctors__spec-search">
                    <div id="doctors_search" class="rb-search__wrap flex">
                        <form action="" class="rb-search flex">
                            <input placeholder="Поиск по специалистам" id="doctors_search_input" type="text" name="q" value="" autocomplete="off" class="rb-search__input"/>
                            <button disabled class="rb-search__btn" type="submit" name="s">Найти</button>
                        </form>
                        <ul class="search-doctors-wrap">
                            
                        </ul>
                    </div>
                </div>
                <ul class="rb-doctors__spec-list flex-wrap">
                <?php

                    //тут выводятся только врачи
$not_doc_arr = [];

$director_id = 22259;

$director_check = new WP_Query([
    'post_type' => 'doctors',
    'posts_per_page' => 1,
    'post__in' => [$director_id],
    'tax_query' => [[
        'taxonomy' => 'departments',
        'field' => 'term_id',
        'terms' => $curr_obj->term_id,
    ]],
]);

if ($director_check->have_posts()) {
    $director_check->the_post();

    get_template_part('template-part/doctors', 'item');

    $not_doc_arr[] = $director_id;
}
wp_reset_postdata();

                    $doctors = new WP_Query(
                        array(
                            'post_type' => 'doctors',
                            'posts_per_page' => -1,
                            'order' => 'ASC',
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'departments',
                                    'field' => 'term_id',
                                    'terms' => $curr_obj->term_id,
                                )
                            ),
                            'meta_query' => array(
                                array(
                                    'key'     => '_rb_doc_checkbox',
                                    'value'   => 'yes',
                                )
                            )
                        )
                    );

                    if( $doctors->have_posts() ) : 
                ?>
                    
                        <?php
                            while( $doctors->have_posts() ){
                                $doctors->the_post();

                                get_template_part( 'template-part/doctors', 'item' );

                                $not_doc_arr[] = get_the_ID();
 
                            }
                        ?>

                <?php 
                    endif;
                    wp_reset_postdata();

                    $not_doctors = new WP_Query(
                        array(
                            'post_type' => 'doctors',
                            'posts_per_page' => -1,
                            'order' => 'DESC',
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'departments',
                                    'field' => 'term_id',
                                    'terms' => $curr_obj->term_id,
                                )
                            ),
                            'post__not_in' => $not_doc_arr,
                        )
                    );

                    if( $not_doctors->have_posts() ) : 

                ?>

                         <?php
                            while( $not_doctors->have_posts() ){
                                $not_doctors->the_post();

                                get_template_part( 'template-part/doctors', 'item' );

                            }
                        ?>
                    
                <?php 
                    endif;
                    wp_reset_postdata();
                ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php //get_template_part( 'template-part/forms/form', 'subscribe' ); ?>
<!-- doctors end -->

<?php get_footer(); ?>
