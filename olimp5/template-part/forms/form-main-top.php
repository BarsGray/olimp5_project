<section class="rb-main__form-top rb-container">	
	<form action="" method="POST" class="rb-main__form rb-mainpage--form">
		<input name="rb_popup_nonce" value="<?php echo wp_create_nonce( "rbPopupNonce" ); ?>" type="hidden">
	    <input type="hidden" name="action" value="rb_popup_form">
	    <input type="hidden" name="curr_url" value="<?php echo site_url() . $_SERVER['REQUEST_URI']; ?>">
		<span class="rb-main__form--title">Форма записи на прием</span>

		<div class="rb-main__form-top--wrapper flex">
			<div class="col-4 col-m-6 col-s-12 rb-main__form--item">
				<label for="rb-bot-form-name">Ваше ФИО</label>
				<input id="rb-bot-form-name" type="text" name="user_name" value="<?php //echo $arResult["AUTHOR_NAME"]?>" placeholder="Иванов Иван Иванович" autocomplete="off" required>
			</div>

			<div class="col-4 col-m-6 col-s-12 rb-main__form--item">
				<label for="rb-bot-form-phone">Номер телефона</label>
				<input id="rb-bot-form-phone" type="text" name="user_phone" class="PHONE_MASK" value="<?php //echo $arResult["AUTHOR_PHONE"]?>" placeholder="+7" autocomplete="off" required>
				<input type="text" name="message" value="" placeholder="Сообщение" autocomplete="off">
			</div>
			<div class="col-3 col-m-12 col-s-12 rb-main__form--button">
				<input type="submit" name="submit" class="rb-mainpage--form-btn" value="Отправить заявку">
			</div>
		</div>
				                     <div class="form--privacy">
<p><input type="checkbox" required="">Установите флажок здесь в соответствии с Федеральным законом № 152-ФЗ «О персональных данных» от 27.07.2006, отправляя форму на этом сайте,
вы подтверждаете свое <a target="_blank" href="/wp-content/uploads/2026/01/soglasie-polzovatelej-sajta.pdf<?//php echo wp_get_attachment_url( $policy ); ?>">согласие на обработку персональных данных</a> в соответствии с 
<a target="_blank" href="/wp-content/uploads/2025/10/politika-obrabotki-personalnyh-dannyh-ooo-czkz.pdf">Политикой обработки персональных данных ООО "ЦКЗ"</a> .</p>    
						 <p><input type="checkbox" >Установите флажок здесь для согласия на получение <a target="_blank" href="/wp-content/uploads/2025/09/soglasie-na-poluchenie-reklamnyh-i-informaczionnyh-rassylok.pdf">рекламных и информационных рассылок</a>.</p> 
					  
					  </div>

	</form>
</section>