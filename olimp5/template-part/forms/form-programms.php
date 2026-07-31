<section class="rb-main__form-bottom rb-services__form">
    <div class="rb-container flex rb-main__form-bottom-text flex--middle">
        <div class="col-6 col-m-12 col-s-12">
            <div class="rb-main__form-bottom--info">
                <span class="rb-form__pretitle">Записаться на приём</span>
                <p class="rb-form__title">
                    Позаботьтесь о своем здоровье с Центром культуры здоровья "Олимп Пять"
                </p>
               <p class="rb-about__form-text">
Мы свяжемся с вами в ближайшее время для подтверждения времени и даты приёма.
                </p>
            </div>
        </div>
        <div class="col-6 col-m-12 col-s-12">
            <div class="rb-main__form-bottom--wrap">
                <form action="" method="POST" class="rb-main__form" id="rb-form-market">
                    <input name="rb_popup_nonce" value="<?php echo wp_create_nonce( "rbPopupNonce" ); ?>" type="hidden">
                    <input type="hidden" name="action" value="rb_popup_form">
                    <input type="hidden" name="curr_url" value="<?php echo site_url() . $_SERVER['REQUEST_URI']; ?>">
                    <span class="rb-main__form--title">Форма записи на приём</span>
                    <div class="rb-main__form-bottom--inputs flex">
                        <div class="rb-main__form--item col-6 col-m-6 col-s-12">
                            <label for="rb-top-form-name">Ваше ФИО</label>
                            <input id="rb-top-form-name" type="text" name="user_name" value="<?//=$arResult["AUTHOR_NAME"]?>" placeholder="Иванов Иван Иванович" autocomplete="off" required>
                        </div>

                        <div class="rb-main__form--item col-6 col-m-6 col-s-12">
                            <label for="rb-top-form-phone">Номер телефона</label>
                            <input id="rb-top-form-phone" type="text" name="user_phone" class="PHONE_MASK" value="<?=$arResult["AUTHOR_PHONE"]?>" placeholder="+7" autocomplete="off" required>
                            <input type="text" name="message" value="" placeholder="Сообщение" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-12 rb-main__form--item">
                        <label for="rb-top-form-message">Ваша проблема</label>
                        <textarea id="rb-top-form-message" type="text" rows="5" name="user_msg" placeholder="Здесь можно кратко описать, в чем именно вам нужна помощь" autocomplete="off" required></textarea>
                    </div>
                    
                    <div class="col-12 rb-main__form--button">
                            <input type="submit" name="submit" value="Отправить заявку">
                    </div>
				                     <div class="form--privacy">
<p><input type="checkbox" required="">Установите флажок здесь в соответствии с Федеральным законом № 152-ФЗ «О персональных данных» от 27.07.2006, отправляя форму на этом сайте,
вы подтверждаете свое <a target="_blank" href="/wp-content/uploads/2026/01/soglasie-polzovatelej-sajta.pdf<?//php echo wp_get_attachment_url( $policy ); ?>">согласие на обработку персональных данных</a> в соответствии с 
<a target="_blank" href="/wp-content/uploads/2025/10/politika-obrabotki-personalnyh-dannyh-ooo-czkz.pdf">Политикой обработки персональных данных ООО "ЦКЗ"</a> .</p>    
						 <p><input type="checkbox" >Установите флажок здесь для согласия на получение <a target="_blank" href="/wp-content/uploads/2025/09/soglasie-na-poluchenie-reklamnyh-i-informaczionnyh-rassylok.pdf">рекламных и информационных рассылок</a>.</p> 
					  
					  </div>
                </form>
            </div>
        </div>
        
    </div>
</section> 