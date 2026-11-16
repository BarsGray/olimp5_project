<?php 
    get_header();
    /* Template Name: Общий */
?>

	<div class="rb-market">
		<div class="rb-container rb-page-header">
			<h1><?php the_title(); ?></h1>
			<section class="rb-market__list">
				<div class="rb-text">
					<?php the_content(); ?>
				</div>
			</section>
		</div>
	</div>


<?php get_footer(); ?>