<div class="rb-container">

	<form action="" method="POST" class="rb-contacts__form">
		<!-- form start -->

			<?php if( $_GET['success'] <> '') : ?>
				<!-- <div class="mf-ok-text"><?=$arResult["OK_MESSAGE"]?></div> -->

				<script>window.location.href = '/success/';</script>

			<?php endif; ?>

			<div class="rb-form__error">
			</div>
			<span class="rb-contacts__form-title">Связаться с нами</span>
			<div class="rb-contacts__form-inputs flex-wrap">

					<div class="col-6 col-s-12 rb-contacts__form-input">
						<label for="rb-contacts-user-name">Ваше ФИО</label>
						<input type="text" name="user_name" id="rb-contacts-user-name" value="<?php //echo $arResult["AUTHOR_NAME"]; ?>" placeholder="Ваше имя" autocomplete="off" required>
					</div>
					<div class="col-6 col-s-12 rb-contacts__form-input">
						<label for="rb-contacts-user-phone">Номер телефона</label>
						<input type="text" name="user_phone" id="rb-contacts-user-phone" class="PHONE_MASK" value="<?php //echo $arResult["AUTHOR_PHONE"]; ?>" placeholder="Телефон" autocomplete="off" required>
					</div>
					<div class="col-3 col-m-12 col-s-12 rb-contacts__form--button">
						<input type="submit" name="submit" value="Отправить заявку" class="rb-button__orange">
					</div>
				                     <div class="form--privacy">
<p><input type="checkbox" required="">Установите флажок здесь в соответствии с Федеральным законом № 152-ФЗ «О персональных данных» от 27.07.2006, отправляя форму на этом сайте,
вы подтверждаете свое <a target="_blank" href="/wp-content/uploads/2026/01/soglasie-polzovatelej-sajta.pdf<?//php echo wp_get_attachment_url( $policy ); ?>">согласие на обработку персональных данных</a> в соответствии с 
<a target="_blank" href="/wp-content/uploads/2025/10/politika-obrabotki-personalnyh-dannyh-ooo-czkz.pdf">Политикой обработки персональных данных ООО "ЦКЗ"</a> .</p>    
						 <p><input type="checkbox" >Установите флажок здесь для согласия на получение <a target="_blank" href="/wp-content/uploads/2025/09/soglasie-na-poluchenie-reklamnyh-i-informaczionnyh-rassylok.pdf">рекламных и информационных рассылок</a>.</p> 
					  
					  </div>
					<input type="hidden" name="PARAMS_HASH" value="<?php //echo $arResult["PARAMS_HASH"]; ?>">

			</div>
	</form>
			<!-- form end -->
</div>