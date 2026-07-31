<?php 

get_header();

/*
Template Name: Страница со ссылками
*/


?>
<style>
.rb-links a {
    text-decoration: none;
    transition: all 0.4s 
ease;
    font-weight: 500;
    font-size: 20px;
    text-align: center;
    color: #fff;
}
</style>
	<div class="rb-links rb-page">

		<?php 

				$rb_links_title = get_post_meta( get_the_ID(), '_rb_links_title', true );
				$rb_links_first_title = get_post_meta( get_the_ID(), '_rb_links_first_title', true );
				$rb_links_first = get_post_meta( get_the_ID(), '_rb_links_first', true );
				$rb_links_last_title = get_post_meta( get_the_ID(), '_rb_links_last_title', true );
				$rb_links_last = get_post_meta( get_the_ID(), '_rb_links_last', true );
				if ( function_exists( 'carbon_get_post_meta' ) ) {
					$rb_links_list = carbon_get_post_meta( get_the_ID(), 'rb_links_list' );
				}

		?>

			<section class="rb-links--content">
                <div class="rb-page-header">
                	<h1 class="rb-links--header"><?php echo $rb_links_title; ?></h1>
                </div>
                <ul class="rb-links--wrapper">
                	<?php if( $rb_links_first_title && $rb_links_last ) : ?>
                		<li class="rb-links--first">
                			<a class="rb-button__orange-full rb-button__bordo-full" href="<?php echo $rb_links_first; ?>"><?php echo $rb_links_first_title; ?></a>
                		</li>
                	<?php 
                		endif;
                		foreach( $rb_links_list as $rb_link ) : 
                	?>
                		<li>
                			<a class="rb-button__orange-full rb-button__bordo-full" href="<?php echo $rb_link['link']; ?>">
                				<?php echo $rb_link['title']; ?>
                				<span><?php echo $rb_link['desc']; ?></span>
                			</a>
                		</li>
                	<?php 
                		endforeach; 
                		if( $rb_links_last_title && $rb_links_last ) : 
                	?>
                		<li class="rb-links--last">
                			<a class="rb-button__orange-full rb-button__grey-full" href="<?php echo $rb_links_last; ?>"><?php echo $rb_links_last_title; ?></a>
                		</li>
                	<?php endif; ?>
                </ul>
		    </section>

	</div>
<?php get_footer(); ?>