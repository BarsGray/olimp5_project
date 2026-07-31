<?php


if (function_exists('carbon_get_post_meta')) {

    $rb_date = carbon_get_post_meta(get_the_ID(), 'rb_date');
    $rb_speakers = carbon_get_post_meta(get_the_ID(), 'rb_speakers_list');
    $mts_link = carbon_get_post_meta(get_the_ID(), 'mts_link');
    $form_link = carbon_get_post_meta(get_the_ID(), 'form_link');

}

?>


<li class="rb-schedule__item">
    <span class="rb-schedule__item-date"><?php echo date('d-m-Y H:i', strtotime($rb_date)); ?></span>
    <h2 class="rb-schedule__item-title"><?php the_title(); ?></h2>
    <ul class="rb-schedule__item-speakers flex-wrap space-between">
        <?php foreach ($rb_speakers as $rb_speakers_item): ?>
            <li class="rb-schedule__speakers--item flex">
                <?php if ($rb_speakers_item['link']): ?>
                    <a href="<?php echo esc_url($rb_speakers_item['link']); ?>" class="flex">
                    <?php endif; ?>
                    <picture class="rb-schedule__item-pic">
                        <img src="<?php echo wp_get_attachment_image_url($rb_speakers_item['image'], 'medium'); ?>">
                    </picture>
                    <div class="rb-schedule__item-speakers--info">
                        <span class="rb-schedule__speakers-name"><?php echo $rb_speakers_item['title']; ?></span>
                        <span class="rb-schedule__speakers-occup"><?php echo $rb_speakers_item['desc']; ?></span>
                    </div>
                    <?php if ($rb_speakers_item['link']) {
                        echo '</a>';
                    } ?>
            </li>
        <?php endforeach; ?>
    </ul>
    <?php if ($mts_link): ?>
        <a href="<?php echo esc_url($mts_link); ?>" class="rb-schedule__item-link">Ссылка на онлайн трансляцию</a>
    <?php endif; ?>
    <!--    <span class="js-open-modal-schedule rb-schedule__speakers-btn rb-button__orange-full" data-name="<?php //echo get_the_title(); ?>">
        Записаться
    </span> -->
    <div class="rb-schedule__item-btns">
        <a href="https://clck.ru/39M7BT" class="rb-schedule__speakers-btn rb-button__orange-full">
            Записаться
        </a>
    </div>

</li>