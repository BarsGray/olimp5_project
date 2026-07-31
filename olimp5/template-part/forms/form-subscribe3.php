<section class="rb-main__form-bottom rb-subscribe__form3">
    <div class="rb-container flex rb-main__form-bottom-text rb-psy__subscribe flex--middle">
        <div class="col-6 col-m-12 col-s-12 rb-psy__subscribe-first">
            <div class="rb-main__form-bottom--info">
                 <span class="rb-about__form-subtitle rb-about__form-subtitle-offset">
                    Подробности можно узнать по телефону <a href="tel:84732255555">225-55-55</a>.
                </span>
            </div>
        </div>
        <div class="col-6 col-m-12 col-s-12 rb-psy__subscribe-second">
            <div class="rb-main__form-bottom--wrap">
                <form action="" method="post" id="rb-subscribe-form" class="rb-main__form">
                    <!-- /local/ajax/sub.php -->
                    <span class="rb-main__form--title">Подписаться на рассылку</span>
                    <input name="rb_subscribe_nonce" value="<?php echo wp_create_nonce( "rbSubscribeNonce" ); ?>" type="hidden">
                    <input type="hidden" name="action" value="rb_subscribe_form">
                    <div class="rb-main__form-top--wrapper flex-wrap">
                        <div class="col-12 rb-main__form--item">
                            <label for="rb-bot-form-name">Email</label>
                            <input id="rb-bot-form-name" type="email" name="email" value="" placeholder="example@mail.ru" autocomplete="off" required>
                            <input type="text" name="message" value="" placeholder="Сообщение" autocomplete="off">
                        </div>

                        <div class="col-12 rb-main__form--button">
                            <input type="submit" class="rb-btn-white" name="submit" id="subscribe_form_button" value="Отправить заявку">
                        </div>
				                     <div class="form--privacy">
<p><input type="checkbox" required="">Установите флажок здесь в соответствии с Федеральным законом № 152-ФЗ «О персональных данных» от 27.07.2006, отправляя форму на этом сайте,
вы подтверждаете свое <a target="_blank" href="/wp-content/uploads/2026/01/soglasie-polzovatelej-sajta.pdf<?//php echo wp_get_attachment_url( $policy ); ?>">согласие на обработку персональных данных</a> в соответствии с 
<a target="_blank" href="/wp-content/uploads/2025/10/politika-obrabotki-personalnyh-dannyh-ooo-czkz.pdf">Политикой обработки персональных данных ООО "ЦКЗ"</a> .</p>    
						 <p><input type="checkbox" >Установите флажок здесь для согласия на получение <a target="_blank" href="/wp-content/uploads/2025/09/soglasie-na-poluchenie-reklamnyh-i-informaczionnyh-rassylok.pdf">рекламных и информационных рассылок</a>.</p> 
					  
					  </div>
                    </div>
                       
                 </form>
            </div>
        </div>
        
    </div>
</section> 