<?php 

get_header();

/*
Template Name: Био маркет
*/


$product_query = new WP_Query(
	array(
		'post_type' => 'bio-market',
		'posts_per_page' => -1
	)
);


?>


	<div class="rb-market">
		<div class="rb-container no_padding rb-page-header">
			<h1><?php the_title(); ?></h1>
			<section class="rb-market__list">
				<div class="rb-market__list-desc rb-text">
					<?php the_content(); ?>
				</div>

				<?php if( $product_query->have_posts() ) : ?>
					<ul class="flex rb-market__list-items">


						<?php 
							while( $product_query->have_posts() ) {
								$product_query->the_post();

								get_template_part( 'template-part/market', 'item' );

							} 
						?>
					</ul>
				<?php endif; wp_reset_postdata(); ?>
			</section>
			
		</div>
	</div>
	<?php get_template_part( 'template-part/forms/form', 'market' ); ?>

<?php get_footer(); ?>
