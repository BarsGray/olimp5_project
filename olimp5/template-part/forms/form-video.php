<form action="" method="POST" id="rb-video-form" class="col-6 col-s-12 rb-main__form">
	<!-- form start -->
	<input name="rb_video_nonce" value="<?php echo wp_create_nonce( "rbVideoNonce" ); ?>" type="hidden">
    <input type="hidden" name="action" value="rb_video_form">

	<div class="form__section">
		<div class="rb-main__form-bottom--inputs flex-wrap">
			<div class="rb-main__form--item col-12">
				<label for="rb-top-form-name">Ваше ФИО</label>
				<input id="rb-top-form-name" type="text" name="user_name" value="" placeholder="Ваше имя, фамилия и отчество" autocomplete="off" required>
				<input type="text" name="message" value="" placeholder="Сообщение" autocomplete="off">
			</div>

			<div class="rb-main__form--item col-12">
				<label for="rb-top-form-birth">Дата рождения</label>
				<input id="rb-top-form-birth" type="date" name="user_birth" value="" placeholder="Дата рождения" autocomplete="off" required>
			</div>

			<div class="rb-main__form--item col-12">
				<label for="rb-top-form-phone">Номер телефона</label>
				<input id="rb-top-form-phone" type="text" name="user_phone" class="PHONE_MASK" value="" placeholder="+7" autocomplete="off" required>
			</div>

			<div class="rb-main__form--item col-12">
				<label for="rb-top-form-email">Email</label>
				<input id="rb-top-form-email" type="email" name="user_email" value="" placeholder="E-mail" autocomplete="off" required>
			</div>

			<div class="rb-main__form--item col-12">
				<label for="rb-top-form-lpu">ЛПУ</label>
				<input id="rb-top-form-lpu" type="text" name="user_lpu" value="" placeholder="Название учреждения" autocomplete="off" required>
			</div>

			<div class="rb-main__form--item col-12">
				<label for="rb-top-form-occup">Специальность</label>
				<input id="rb-top-form-occup" type="text" name="user_spacialnost" value="" placeholder="Укажите специальность" autocomplete="off" required>
			</div>

			<div class="rb-main__form--item col-12">
				<label>Предпочитаемые виды получения информации (выбирая вид получения информации, вы соглашаетесь на предоставление рекламных материалов):</label>
				<div class="rb-main__form--item-label-wrap">
					<label class="rb-main__form--item-label">
						<input type="checkbox" name="user_info_tele" value="">
						Telegram
					</label>
					<label class="rb-main__form--item-label">
						<input type="checkbox" name="user_info_wa" value="">
						WhatsApp
					</label>
					<label class="rb-main__form--item-label">
						<input type="checkbox" name="user_info_email" value="">
						Email
					</label>
					<label class="rb-main__form--item-label">
						<input type="checkbox" name="user_info_sms" value="">
						SMS
					</label>
				</div>
			</div>
			
			<input type="hidden" name="video" value="1" />

			<div class="form__footer">
				<div class="form__footer--button rb-main__form--button">
					<!-- <input type="submit" name="submit" value="Отправить" class="rb-video-form__btn"> -->
					<input type="submit" class="rb-btn-white" name="submit" id="subscribe_form_button" value="Отправить заявку">
				</div>
								                     <div class="rb-video-form__text form--privacy">

<p><input type="checkbox" required="">Установите флажок здесь в соответствии с Федеральным законом № 152-ФЗ «О персональных данных» от 27.07.2006, отправляя форму на этом сайте,
вы подтверждаете свое <a target="_blank" href="/wp-content/uploads/2026/01/soglasie-polzovatelej-sajta.pdf<?//php echo wp_get_attachment_url( $policy ); ?>">согласие на обработку персональных данных</a> в соответствии с 
<a target="_blank" href="/wp-content/uploads/2025/10/politika-obrabotki-personalnyh-dannyh-ooo-czkz.pdf">Политикой обработки персональных данных ООО "ЦКЗ"</a> .</p>    
						 <p><input type="checkbox" >Установите флажок здесь для согласия на получение <a target="_blank" href="/wp-content/uploads/2025/09/soglasie-na-poluchenie-reklamnyh-i-informaczionnyh-rassylok.pdf">рекламных и информационных рассылок</a>.</p> 
					  
					  </div>
			</div>

		</div>
		
		
	</div>

</form>