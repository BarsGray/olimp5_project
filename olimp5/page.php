<?php get_header(); ?>

	<div class="rb-market">
		<div class="rb-container no_padding rb-page-header">
			<h1><?php the_title(); ?></h1>
			<section class="rb-market__list">
				<div class="rb-market__list-desc rb-text">
					<?php the_content(); ?>
				</div>
			</section>
		</div>
	</div>

<?php get_footer(); ?>
