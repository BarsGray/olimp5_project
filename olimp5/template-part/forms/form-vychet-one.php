<section class="col-12 flex-wrap rb-vychet__wrap rb-vychet__wrap-open" data-tab="one">

    <div class="col-6 col-s-12 rb-vychet__form-block--left">

        <div class="col-12 rb-vychet__form-item">
            <label for="rb-vychet-name">ФИО Налогоплательщика</label>
            <input
                type="text"
                id="rb-vychet-name"
                name="rb_vychet_name"
                class="rb-vychet__required"
                placeholder="Иванов Иван Иванович"
                autocomplete="off"
                required>
        </div>

        <div class="col-12 rb-vychet__form-item">
            <label for="rb-vychet-phone">Номер телефона</label>
            <input
                type="text"
                id="rb-vychet-phone"
                name="rb_vychet_phone"
                class="rb-vychet__required"
                placeholder="+7 999 888-77-66"
                autocomplete="tel"
                maxlength="16"
                required>
            <!-- антиспам -->
            <input type="text" name="message" value="" autocomplete="off" tabindex="-1">
        </div>

        <div class="col-12 rb-vychet__form-item">
            <label for="rb-vychet-birthday">Дата рождения</label>
            <input
                type="text"
                id="rb-vychet-birthday"
                name="rb_vychet_birthday"
                class="rb-vychet__required"
                placeholder="Выберите дату"
                required>
        </div>

        <div class="col-12 rb-vychet__form-item">
            <label for="rb-vychet-period">Период</label>
            <input
                type="text"
                id="rb-vychet-period"
                name="rb_vychet_period"
                class="rb-vychet__required"
                placeholder="2023-2025"
                pattern="\d{4}.*?"
                autocomplete="off"
                required>
        </div>

    </div>

    <div class="col-6 col-s-12 rb-vychet__form-block--right">

        <div class="col-12 rb-vychet__form-item">
            <label for="rb-vychet-inn">ИНН</label>
            <input
                type="text"
                id="rb-vychet-inn"
                name="rb_vychet_inn"
                class="rb-vychet__required"
                placeholder="123456789012"
                maxlength="12"
                required>
            </div>
            <!-- pattern="\d{12}" -->
            
        <!-- КОПИЯ ДОГОВОРА -->
        <div class="col-12 rb-vychet__form-item" data-field="copy">
            <label>Требуется ли копия договора?</label>
            <input type="hidden" name="rb_vychet_copy" value="">
            <div class="rb-vychet__choose" data-name="rb_vychet_copy">
                <span class="rb-vychet__choose-tab" data-value="Да">Да</span>
                <span class="rb-vychet__choose-tab" data-value="Нет">Нет</span>
            </div>
        </div>

        <!-- ПОЧТА РОССИИ -->
        <div class="col-12 rb-vychet__form-item">
            <label>Отправить справку заказным письмом? (Почта России)</label>
            <input type="hidden" name="rb_vychet_post" value="">
            <div class="rb-vychet__choose"
                 data-name="rb_vychet_post"
                 data-where="address">
                <span class="rb-vychet__choose-tab" data-value="Да">Да</span>
                <span class="rb-vychet__choose-tab" data-value="Нет">Нет</span>
            </div>
        </div>

        <!-- ЛИЧНЫЙ КАБИНЕТ -->
        <div class="col-12 rb-vychet__form-item" data-field="lk">
            <label>Отправить справку в электронном виде в личный кабинет налогоплательщика?</label>
            <input type="hidden" name="rb_vychet_lk" value="">
            <div class="rb-vychet__choose" data-name="rb_vychet_lk">
                <span class="rb-vychet__choose-tab" data-value="Да">Да</span>
                <span class="rb-vychet__choose-tab" data-value="Нет">Нет</span>
            </div>
        </div>

        <!-- БУМАЖНАЯ -->
        <div class="col-12 rb-vychet__form-item" data-field="paper">
            <label>Выдать справку на бумажном носителе при личном обращении?</label>
            <input type="hidden" name="rb_vychet_paper" value="">
            <div class="rb-vychet__choose" data-name="rb_vychet_paper">
                <span class="rb-vychet__choose-tab" data-value="Да">Да</span>
                <span class="rb-vychet__choose-tab" data-value="Нет">Нет</span>
            </div>
        </div>

        <!-- АДРЕС -->
        <div class="col-12 rb-vychet__form-item rb-vychet__form-item-hide"
             data-field="post-address"
             data-where="address">
            <label for="rb-vychet-post-address">Адрес электронной почты</label>
            <input
                type="text"
                id="rb-vychet-post-address"
                name="rb_vychet_post_address"
                placeholder="Укажите полный адрес (с индексом)">
        </div>

        <!-- ПОЛИТИКИ -->
				                     <div class="form--privacy">
<p><input type="checkbox" required="">Установите флажок здесь в соответствии с Федеральным законом № 152-ФЗ «О персональных данных» от 27.07.2006, отправляя любую форму на этом сайте,
вы подтверждаете свое <a target="_blank" href="/wp-content/uploads/2026/01/soglasie-polzovatelej-sajta.pdf<?//php echo wp_get_attachment_url( $policy ); ?>">согласие на обработку персональных данных</a> в соответствии с 
<a target="_blank" href="/wp-content/uploads/2025/10/politika-obrabotki-personalnyh-dannyh-ooo-czkz.pdf">Политикой обработки персональных данных ООО "ЦКЗ"</a> .</p>    
						 <p><input type="checkbox" >Установите флажок здесь для согласия на получение <a target="_blank" href="/wp-content/uploads/2025/09/soglasie-na-poluchenie-reklamnyh-i-informaczionnyh-rassylok.pdf">рекламных и информационных рассылок</a>.</p> 
					  
					  </div>

    </div>
</section>
