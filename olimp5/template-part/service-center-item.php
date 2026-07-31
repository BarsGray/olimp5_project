<?php 

	$id = $args['item'] ?: '';
	$doctors = $args['doctors'] ?: '';
	$programms = $args['programms'] ?: '';
	$service = $args['service'] ?: '';
	$servicestax = $args['servicestax'] ?: '';
	$services_or_service = $args['serv_type'];
	if ( $id ) :

?>

	<li class="rb-pricelist__item f-jcsb-center flex-wrap" id="<?php echo $id;?>">
		<div class="col-10 col-s-12 flex-wrap">
			<p class="rb-pricelist__item-name col-s-12 col-10"><?php echo get_the_title( $id ); ?></p>
			<span class="rb-pricelist__item-price col-s-12 col-2"><?php echo get_post_meta( $id, $services_or_service, true ); ?>&nbsp₽</span>
		</div>
		<div class="col-2 col-s-12 rb-pricelist__item-btn--wrap">
			<span class="rb-pricelist__item-btn rb-button__orange-full rb-doctors-page__btn js-open-modal doctor2" data-doctors="<?php echo $doctors; ?>" data-programms="<?php echo $programms; ?>" data-service="<?php echo $service; ?>" data-servicestax="<?php echo $servicestax; ?>">Записаться</span>
		</div>
	</li>
<?php endif; ?>