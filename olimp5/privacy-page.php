<?php
/**
 * Template Name: Privacy
 */
defined( 'ABSPATH' ) || exit;
get_header();
?>

<main class="rb-container privacy">
    <div class="title title__main"><?php the_title(); ?></div>

        <div class="delivery delivery_offset">
        		<?php
		while ( have_posts() ) : the_post();

			the_content();
		endwhile;
		?>
        </div>

  </div>
</main>

<?php get_footer(); ?>