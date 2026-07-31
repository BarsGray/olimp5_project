<div class="card swiper-slide rb-news__item filter_all">
	<div class="rb-news__item--wrap">
		<div class="rb-news__item--info">
			<div class="rb-news__item--top">
				<span class="rb-news__item--date">
					<?php echo get_the_date('d.m.Y'); ?>
				</span>
				<a class="rb-news__item--title-link" href="<?php the_permalink(); ?>">
					<h3 class="rb-news__item--title">
						<?php the_title(); ?>
					</h3>
				</a>
				<div class="rb-news__item--desc">
					<?php the_excerpt(); ?>
				</div>
			</div>
			<div class="rb-news__item--bot">
				<a class="rb-news__item--link" href="<?php the_permalink(); ?>">
					Читать
					<svg width="8" height="15" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M1.25 1.5L8.75 9L1.25 16.5" stroke="#121212" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				</a>
			</div>
		</div>
		<picture class="rb-news__item--image">
				<!-- <source type="image/webp" srcset=".webp 1x, .webp 2x "> -->
				<img src="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'rb_blog' ); ?>" srcset="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'rb_blog' ); ?> 1x, <?php echo get_the_post_thumbnail_url( get_the_ID(), 'rb_blog_2x' ); ?> 2x" >
		</picture>
	</div>
</div>