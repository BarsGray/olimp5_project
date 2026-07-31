<section class="rb-vychet__wrap flex-wrap  rb-vychet__wrap-open" data-tab="two">
    <input type="hidden" name="forwhom" id="forwhom" value="two">

  <div class="col-6 col-s-12 rb-vychet__form-block--left">

    <div class="col-12 rb-vychet__form-item">
      <label for="rb-vychet-name2">ФИО Налогоплательщика</label>
      <input
        type="text"
        name="rb_vychet_name2"
        id="rb-vychet-name2"
        value=""
        placeholder="Иванов Иван Иванович"
        autocomplete="off"
        required
        class="rb-vychet__required">
    </div>

    <div class="col-12 rb-vychet__form-item">
      <label for="rb-vychet-taxpayer-phone2">Номер телефона</label>
      <input
        id="rb-vychet-taxpayer-phone2"
        autocomplete="tel"
        maxlength="16"
        name="rb_vychet_phone2"
        placeholder="+7 999 888-77-66"
        type="text"
        required
        class="rb-vychet__required">
    </div>

    <div class="col-12 rb-vychet__form-item">
      <label for="rb-vychet-birthday2">Дата рождения Налогоплательщика</label>
      <input
        id="rb-vychet-birthday2"
        name="rb_vychet_birthday2"
        placeholder="Выберите дату"
        required
        class="rb-vychet__required"
        type="text">
    </div>

    <div class="col-12 rb-vychet__form-item">
      <label for="rb-vychet-inn2">ИНН Налогоплательщика</label>
      <input
        id="rb-vychet-inn2"
        required
        class="rb-vychet__required"
        maxlength="12"
        name="rb_vychet_inn2"
        pattern="\d{12}"
        placeholder="123456789012"
        type="text">
    </div>

    <div class="col-12 rb-vychet__form-item">
      <label for="rb-vychet-period2">Период</label>
      <input
        type="text"
        name="rb_vychet_period2"
        id="rb-vychet-period2"
        value=""
        placeholder="2023-2025"
        pattern="\d{4}.*?"
        autocomplete="off"
        required
        class="rb-vychet__required">
    </div>

  </div>

  <div class="col-6 col-s-12 rb-vychet__form-block--right">

    <div class="col-12 rb-vychet__form-item">
      <label for="rb-vychet-patient-name2">ФИО Пациента</label>
      <input
        type="text"
        name="rb_vychet_patient_name2"
        id="rb-vychet-patient-name2"
        required
        class="rb-vychet__required"
        value=""
        placeholder="Иванов Иван Иванович"
        autocomplete="off">
      <input type="text" name="message" value="" autocomplete="off" tabindex="-1">
    </div>

    <div class="col-12 rb-vychet__form-item">
      <label for="rb-vychet-patient-birthday2">Дата рождения пациента</label>
      <input
        id="rb-vychet-patient-birthday2"
        name="rb_vychet_birthday"
        placeholder="Выберите дату"
        required
        class="rb-vychet__required"
        type="text">
    </div>

    <div class="col-12 rb-vychet__form-item">
      <label for="rb-vychet-sibling2">Степень родства</label>
      <input id="rb-vychet-sibling2" name="rb_vychet_sibling2" value="Я" type="hidden">
      <span class="rb-vychet__form-input">Я</span>
      <ul class="rb-vychet__form-list">
        <li class="choosen">Я</li>
        <li>Супруг/супруга</li>
        <li>Сын/дочь</li>
        <li>Мать/отец</li>
      </ul>
    </div>

    <div class="col-12 rb-vychet__form-item">
      <label>Отправить справку заказным письмом? (Почта России)</label>
      <input type="hidden" name="rb_vychet_post" value="">
      <div class="rb-vychet__choose" data-name="rb_vychet_post" data-where="address">
        <span class="rb-vychet__choose-tab" data-value="Да">Да</span>
        <span class="rb-vychet__choose-tab" data-value="Нет">Нет</span>
      </div>
    </div>

    <div class="col-12 rb-vychet__form-item" data-field="lk">
      <label>Отправить справку в электронном виде в личный кабинет налогоплательщика?</label>
      <input type="hidden" name="rb_vychet_lk" value="">
      <div class="rb-vychet__choose" data-name="rb_vychet_lk">
        <span class="rb-vychet__choose-tab" data-value="Да">Да</span>
        <span class="rb-vychet__choose-tab" data-value="Нет">Нет</span>
      </div>
    </div>

    <div class="col-12 rb-vychet__form-item" data-field="paper">
      <label>Выдать справку на бумажном носителе при личном обращении?</label>
      <input type="hidden" name="rb_vychet_paper" value="">
      <div class="rb-vychet__choose" data-name="rb_vychet_paper">
        <span class="rb-vychet__choose-tab" data-value="Да">Да</span>
        <span class="rb-vychet__choose-tab" data-value="Нет">Нет</span>
      </div>
    </div>

    <div class="col-12 rb-vychet__form-item rb-vychet__form-item-hide" data-where="address" data-field="post-address">
      <label for="rb-vychet-post-address2">Адрес электронной почты</label>
      <input
        id="rb-vychet-post-address2"
        name="rb_vychet_post_address"
        placeholder="Укажите полный адрес (с индексом)">
    </div>

    <div class="col-12 rb-vychet__form-item" data-field="copy">
      <label>Требуется ли копия договора?</label>
      <input type="hidden" name="rb_vychet_copy2" value="">
      <div class="rb-vychet__choose" data-name="rb_vychet_copy2">
        <span class="rb-vychet__choose-tab" data-value="Да">Да</span>
        <span class="rb-vychet__choose-tab" data-value="Нет">Нет</span>
      </div>
    </div>

				                     <div class="form--privacy">
<p><input type="checkbox" required="">Установите флажок здесь в соответствии с Федеральным законом № 152-ФЗ «О персональных данных» от 27.07.2006, отправляя форму на этом сайте,
вы подтверждаете свое <a target="_blank" href="/wp-content/uploads/2026/01/soglasie-polzovatelej-sajta.pdf<?//php echo wp_get_attachment_url( $policy ); ?>">согласие на обработку персональных данных</a> в соответствии с 
<a target="_blank" href="/wp-content/uploads/2025/10/politika-obrabotki-personalnyh-dannyh-ooo-czkz.pdf">Политикой обработки персональных данных ООО "ЦКЗ"</a> .</p>    
						 <p><input type="checkbox" >Установите флажок здесь для согласия на получение <a target="_blank" href="/wp-content/uploads/2025/09/soglasie-na-poluchenie-reklamnyh-i-informaczionnyh-rassylok.pdf">рекламных и информационных рассылок</a>.</p> 
					  
					  </div>

  </div>
</section>
