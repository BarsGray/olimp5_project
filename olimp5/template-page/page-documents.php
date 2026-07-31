<?php 

get_header();

/*
Template Name: Документы
*/


if ( function_exists( 'carbon_get_post_meta' ) ){

	$rb_documents_list = carbon_get_post_meta( get_the_ID(), 'rb_documents_list' );

}


?>

<div class="rb-market">
	<div class="rb-container no_padding rb-page-header">
		<h1><?php the_title(); ?></h1>

		<div class="rb-documents">
		    <div class="rb-container">
		    	<?php if ( $rb_documents_list ) : ?>
			        <ul class="rb-documents-list flex-wrap">
			            <?php 
			                foreach( $rb_documents_list as $rb_document ): 
			                    ?>
			                        <li class="rb-documents-item">
			                            <div class="rb-documents-item-wrap flex">
			                                <a class="rb-documents-item-name" target="_blank" href="<?php echo wp_get_attachment_url( $rb_document["file"] ); ?>">
			                                    <?php echo $rb_document["title"]; ?>
			                                </a>
			                                <a class="rb-documents-item-img" target="_blank" href="<?php echo wp_get_attachment_url( $rb_document["file"] ); ?>">
			                                    <svg width="30" height="40" viewBox="0 0 30 40" fill="none" xmlns="http://www.w3.org/2000/svg">
			                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M5 0H18.2325C18.8955 0.000141594 19.5313 0.263627 20 0.7325L29.2675 10C29.7364 10.4687 29.9999 11.1045 30 11.7675V35C30 36.3261 29.4732 37.5979 28.5355 38.5355C27.5979 39.4732 26.3261 40 25 40H5C3.67392 40 2.40215 39.4732 1.46447 38.5355C0.526784 37.5979 0 36.3261 0 35V5C0 3.67392 0.526784 2.40215 1.46447 1.46447C2.40215 0.526784 3.67392 0 5 0ZM18.75 3.75V8.75C18.75 9.41304 19.0134 10.0489 19.4822 10.5178C19.9511 10.9866 20.587 11.25 21.25 11.25H26.25L18.75 3.75ZM5.4125 34.17C5.6375 34.62 5.9875 35.0275 6.5075 35.2175C7.025 35.405 7.5375 35.3175 7.9575 35.1425C8.7525 34.8175 9.545 34.0525 10.2725 33.1775C11.105 32.175 11.98 30.86 12.825 29.4025C14.4563 28.919 16.1269 28.5794 17.8175 28.3875C18.5675 29.345 19.3425 30.17 20.0925 30.7625C20.7925 31.3125 21.6 31.77 22.4275 31.805C22.8782 31.8273 23.3245 31.7066 23.7025 31.46C24.09 31.2075 24.3775 30.8425 24.5875 30.42C24.8125 29.9675 24.95 29.495 24.9325 29.0125C24.9167 28.5368 24.7405 28.0804 24.4325 27.7175C23.8675 27.0425 22.9425 26.7175 22.0325 26.555C20.9286 26.384 19.8086 26.3421 18.695 26.43C17.7546 25.1006 16.9347 23.69 16.245 22.215C16.87 20.565 17.3375 19.005 17.545 17.73C17.635 17.185 17.6825 16.665 17.665 16.195C17.6619 15.7285 17.5534 15.2687 17.3475 14.85C17.2289 14.6189 17.0604 14.4172 16.8541 14.2593C16.6478 14.1015 16.409 13.9916 16.155 13.9375C15.65 13.83 15.13 13.9375 14.6525 14.13C13.71 14.505 13.2125 15.305 13.025 16.1875C12.8425 17.0375 12.925 18.0275 13.14 19.0275C13.36 20.0425 13.735 21.1475 14.215 22.265C13.4472 24.1745 12.5605 26.034 11.56 27.8325C10.2716 28.2379 9.0297 28.7784 7.855 29.445C6.93 29.995 6.1075 30.645 5.6125 31.4125C5.0875 32.2275 4.925 33.1975 5.4125 34.17Z" fill="#465477"/>
			                                    </svg>
			                                </a>
			                            </div>
			                        </li>
			                    <?php 
			                endforeach; 
			            ?>
			        </ul>
			    <?php endif; ?>
			    <ul class="rb-doc-additional-links">
			    	<li>Официальный интернет-портал правовой информации <a target="_blank"  href="http://pravo.gov.ru/">www.pravo.gov.ru</a> </li>
			    	<li>Рубрикатор клинических рекомендаций на сайте Минздрава России <a target="_blank"  href="https://cr.minzdrav.gov.ru/">https://cr.minzdrav.gov.ru/</a> </li>
			    </ul>
		    </div>
		</div>
	</div>
</div>

<?php get_footer(); ?>
