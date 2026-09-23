const rbPopupForm = $('#rb-popup-form'),
      rbPopupFormBtn = rbPopupForm.find('.rb-btn-submit');
    
if (rbPopupForm.length) {
    rbPopupForm.on('submit', function (e) {
        e.preventDefault();
        /* CAPTCHA START */
        grecaptcha.ready(function () {
        grecaptcha.execute('6Le1OIotAAAAAEozeVaiXp1opEP1GKFaw-RqO0cS', {
            action: 'rb_popup_form'
        }).then(function (token) {
        let formData = rbPopupForm.serialize();
        formData += '&g-recaptcha-response=' + encodeURIComponent(token);
                $.ajax({
                    type: 'POST',
                    url: ajax_url,
                    // Было:
                    // data: rbPopupForm.serialize(),
                    // Стало:
                    data: formData,
                    dataType: 'json',

                    beforeSend: function() {
                        // rbCommentFormBtn.find('span').text('Загружаем...');
                        // console.log(formData);
                    },
                    success: function (data) {
                        if (data.result == 'success') {
                            $('.popup-modal').removeClass('active');
                            $('.rb-thakyou-modal').addClass('active');
                            rbPopupForm.trigger("reset");
                            
                            if ($('#seo_ym').length > 0) {
                            const ymTag = $('#seo_ym').val();
                            ym(84731377,'reachGoal',ymTag);
                        }
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                        console.log(status);
                        console.log(error);
                        console.log(xhr);
                    }
                });
            }).catch(function (error) {
                console.log('reCAPTCHA error:', error);
                alert('Не удалось выполнить проверку CAPTCHA. Попробуйте ещё раз.');
            });
        });
        return false;
    });
}