<?php

if ('publish' === get_post_status(get_the_ID())):
    $occup = get_post_meta(get_the_ID(), '_rb_doc_occup', true);

    ?>
    <li class="col-3 col-s-12 rb-doctors__spec-item">

        <a href="<?php echo get_the_permalink(get_the_ID()); ?>" class="rb-doctors__spec-item--wrap">
            <picture class="rb-doctors__spec-picture">
                <?php echo get_the_post_thumbnail(get_the_ID(), 'medium', array('class' => 'rb-img-contain')); ?>
            </picture>
            <div class="rb-doctors__spec-info">
                <span class="rb-doctors__spec-fio">
                    <?php
                    $title = get_the_title();
                    preg_match_all('/\([^)]*\)/u', $title, $m);
                    $paren = trim(implode(' ', $m[0] ?? []));

                    $clean = preg_replace('/\([^)]*\)/u', '', $title);
                    $clean = preg_replace('/\s+/u', ' ', trim($clean));

                    $parts = $clean === '' ? [] : explode(' ', $clean);

                    $surname = $parts[0] ?? '';
                    $rest = trim(implode(' ', array_slice($parts, 1)));

                    $line1 = trim($surname . ($paren ? ' ' . $paren : ''));
                    $line2 = $rest;

                    echo esc_html($line1);
                    if ($line2 !== '') {
                        echo '<br>' . esc_html($line2);
                    } ?>

                </span>
                <span class="rb-doctors__spec-occup"><?php echo $occup; ?></span>
                <?php
                $stage = get_post_meta(get_the_ID(), '_rb_doc_stage', true);
                if ($stage):
                    $d1 = new DateTime('now');
                    $d2 = new DateTime(date('Y-m-d', strtotime($stage)));
                    $diff = $d2->diff($d1);
                    ?>
                    <p class="rb-doctors__spec-standing">
                        <span>Стаж</span>
                        <?php echo $diff->y . ' ' . rb_years_declension($diff->y); ?>
                    </p>
                <?php endif; ?>

                <?php $doc_id_lk = carbon_get_post_meta(get_the_ID(), 'rb_doc_id_lk');
                $order_btn_params = 'data-doctors="' . get_the_title() . '" data-programms="" data-service="" data-servicestax=""';
                if ($doc_id_lk) {
                    $order_btn_params = "onclick=\"event.preventDefault(); ym(84731377, 'reachGoal', 'doctor_booking_click'); ONDOC.showModal('/booking-appointment?doctor=" . $doc_id_lk . "')\"";
                }
                ?>
                <span class="<?php echo $doc_id_lk ? '' : 'js-open-modal' ?> rb-doctors__spec-btn rb-button__orange" <?php echo $order_btn_params; ?>>Запись на прием</span>
            </div>
        </a>
    </li>
<?php endif; ?>