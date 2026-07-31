<?php
$service_id = isset($args['post_id']) ? $args['post_id'] : get_the_ID();
$context = isset($args['context']) ? $args['context'] : '';
$tax = isset($args['tax']) ? $args['tax'] : '';

$rb_services_checkbox = get_post_meta($service_id, '_rb_services_checkbox', true);
$rb_services_price_hide = get_post_meta($service_id, '_rb_services_price_hide', true);
$price = get_post_meta($service_id, '_rb_services_price', true);
$title = get_the_title($service_id);
$permalink = get_permalink($service_id);
$content = apply_filters('the_content', get_post_field('post_content', $service_id));
?>
    <a href="<?php echo esc_url($permalink); ?>" class="rb-service__item-title">
<li class="rb-service__item">
  <picture class="col-12 rb-service__item-image">
    <?php echo get_the_post_thumbnail($service_id, 'medium', array('class' => 'rb-img-cover')); ?>
  </picture>

  <div class="rb-service__item-info">
    <span href="<?php echo esc_url($permalink); ?>" class="rb-service__item-title"><?php echo esc_html($title); ?></span>
    <p class="rb-service__item-desc"><?php echo $content; ?></p>

    <div class="rb-service__item-bot">
      <?php if ($price && !$rb_services_price_hide): ?>
        <span class="rb-service__item-price"><?php echo esc_html($price); ?> ₽</span>
      <?php endif; ?>

      <?php if ($rb_services_checkbox): ?>
        <a href="<?php echo esc_url($permalink); ?>" class="rb-service__item-btn rb-button__orange">Перейти</a>
      <?php else: ?>
        <span class="rb-service__item-btn rb-button__orange js-open-modal"
              data-doctors=""
              data-programms=""
              data-service="<?php echo esc_attr($title); ?>"
              data-servicestax="<?php echo esc_attr($tax); ?>">
          Записаться на прием
        </span>
      <?php endif; ?>
    </div>
  </div>
</li>
</a>
<?php
if ($context !== 'popular') :
  $price_title = carbon_get_post_meta($service_id, 'rb_price_title');
  $price_list = carbon_get_post_meta($service_id, 'rb_price_list');

  if (!empty($price_list)) :
?>
<div class="rb-container" id="y-price">
  <?php if ($price_title): ?>
    <h2 class="rb-title"><?php echo esc_html($price_title); ?></h2>
  <?php endif; ?>

  <div class="col-6 col-m-12">
    <div class="rb-service__mid-text"></div>
  </div>

  <ul class="rb-pricelist__wrap">
    <?php foreach ($price_list as $index => $item): ?>
      <?php
        $is_hidden = $index >= 5 ? ' hidden-by-default' : '';
        $name = $item['name'];
        $price = $item['price'];
        $doctor = $item['doctor'];
      ?>
      <li class="rb-pricelist__item f-jcsb-center flex-wrap<?php echo $is_hidden; ?>" id="price-<?php echo $index; ?>">
        <div class="col-10 col-s-12 flex-wrap">
          <p class="rb-pricelist__item-name col-s-12 col-10"><?php echo esc_html($name); ?></p>
          <span class="rb-pricelist__item-price col-s-12 col-2"><?php echo esc_html($price); ?></span>
        </div>
        <div class="col-2 col-s-12 rb-pricelist__item-btn--wrap">
          <span class="rb-pricelist__item-btn rb-button__orange-full rb-doctors-page__btn js-open-modal doctor2"
                data-doctors=""
                data-programms=""
                data-service="<?php echo esc_attr($name); ?>"
                data-servicestax="">
            Записаться
          </span>
        </div>
      </li>
    <?php endforeach; ?>
  </ul>

  <?php if (count($price_list) > 5): ?>
    <div class="table-button-width">
      <a href="#" class="rb-service__top-link rb-button__orange-bg" id="show-more-prices">Показать еще</a>
    </div>
  <?php endif; ?>
</div>
<?php endif; endif; ?>
