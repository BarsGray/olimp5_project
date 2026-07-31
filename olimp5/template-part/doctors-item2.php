 <li class="col-3 col-m-6 col-s-12 rb-doctors__spec-item">

    <div class="rb-doctors__spec-item--wrap"> 
        <!-- <a href="<?php //echo $arItem["DETAIL_PAGE_URL"]; ?>"> -->
        <picture class="rb-doctors__spec-picture">
            
            <?php echo get_the_post_thumbnail( get_the_ID(), 'medium', array( 'class' => 'rb-img-contain' ) ); ?>
        </picture>
        <!-- </a> -->
        <div class="rb-doctors__spec-info">
            <a href="<?php the_permalink(); ?>" class="rb-doctors__spec-fio">
                <?php

                    $fio = explode( ' ', get_the_title() );
                    $rb_doc_surname = get_post_meta( get_the_ID(), '_rb_doc_surname', true ); 
                    $rb_doc_name = get_post_meta( get_the_ID(), '_rb_doc_name', true ); 
                    $rb_doc_patronimyc = get_post_meta( get_the_ID(), '_rb_doc_patronimyc', true ); 

                    $surname = $rb_doc_surname ?: $fio[0];
                    $name = $rb_doc_name ?: $fio[1];
                    $patronimyc = $rb_doc_patronimyc ?: $fio[2];

                    echo $surname . '<br>' . $name . ' ' . $patronimyc; 

                ?>
            </a>
            <span class="rb-doctors__spec-occup"><?php echo get_post_meta( get_the_ID(), '_rb_doc_occup', true ); ; ?></span>
            <?php
                $stage = get_post_meta( get_the_ID(), '_rb_doc_stage', true );
                if( $stage ) :
                    $d1 = new DateTime('now');
                    $d2 = new DateTime( date('Y-m-d', strtotime( $stage ) ) );
                    $diff = $d2->diff( $d1 );
            ?>
                <p class="rb-doctors__spec-standing">
                    <span>Стаж</span>
                    <?php echo $diff->y . ' ' . rb_years_declension( $diff->y ); ?>               
                </p>
            <?php endif; ?>
            <?php //if( $arItem['PROPERTIES']['ORDER']['VALUE'] == 'Y' ) : ?>
                <span class="js-open-modal rb-doctors__spec-btn rb-button__orange" data-doctors="<?php echo get_the_title(); ?>" data-programms="" data-service="" data-servicestax="" data-modal="1">
                    Запись на прием
                </span>
            <?php //endif; ?>
        </div>
    </div>
</li>