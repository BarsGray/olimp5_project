<?php
add_action('wp_ajax_rb_popup_form', 'rb_popup_form');
add_action('wp_ajax_nopriv_rb_popup_form', 'rb_popup_form');

function rb_popup_form(){

    check_ajax_referer( 'rbPopupNonce', 'rb_popup_nonce' );

    /* CAPTCHA DEBUG START */
    $captcha_token = isset($_POST['g-recaptcha-response']) ? sanitize_text_field(wp_unslash($_POST['g-recaptcha-response'])) : '';

    if (!$captcha_token) {
        wp_send_json([
            'result'  => 'error',
            'content' => 'CAPTCHA token отсутствует.'
        ]);
    }

    $captcha = wp_remote_post(
        'https://www.google.com/recaptcha/api/siteverify',
        [
            'timeout' => 10,
            'body' => [
                'secret'   => '6Le1OIotAAAAABZiDiblWC4II-p9yG86aL5k0alh',
                'response' => $captcha_token,
            ]
        ]
    );

    if (is_wp_error($captcha)) {
        wp_send_json([
            'result'  => 'error',
            'content' => 'Ошибка запроса Google: ' . $captcha->get_error_message()
        ]);
    }

    $captcha_result = json_decode(
        wp_remote_retrieve_body($captcha),
        true
    );

    if (empty($captcha_result['success'])) {
        wp_send_json([
            'result'  => 'error',
            'content' => 'Google отклонил CAPTCHA: ' .
                implode(', ', $captcha_result['error-codes'] ?? [])
        ]);
    }

    if (($captcha_result['action'] ?? '') !== 'rb_popup_form') {
        wp_send_json([
            'result'  => 'error',
            'content' => 'Неверный action CAPTCHA: ' .
                ($captcha_result['action'] ?? 'не указан')
        ]);
    }

    if (($captcha_result['score'] ?? 0) < 0.5) {
        wp_send_json([
            'result'  => 'error',
            'content' => 'Низкий score CAPTCHA: ' .
                ($captcha_result['score'] ?? 0)
        ]);
    }

    /* CAPTCHA DEBUG END */

    $name = isset( $_POST[ 'user_name' ] ) ? sanitize_text_field( $_POST[ 'user_name' ] ) : '';
    $phone = isset( $_POST[ 'user_phone' ] ) ? sanitize_text_field( $_POST[ 'user_phone' ] ) : '';
    $user_msg = isset( $_POST[ 'user_msg' ] ) ? sanitize_textarea_field( $_POST[ 'user_msg' ] ) : '';
    $curr_url = isset( $_POST[ 'curr_url' ] ) ? sanitize_text_field( $_POST[ 'curr_url' ] ) : '';

    $user_service = isset( $_POST[ 'user_service' ] ) ? sanitize_text_field( $_POST[ 'user_service' ] ) : '';
    $user_servicestax = isset( $_POST[ 'user_servicestax' ] ) ? sanitize_text_field( $_POST[ 'user_servicestax' ] ) : '';
    $user_doctors = isset( $_POST[ 'user_doctors' ] ) ? sanitize_text_field( $_POST[ 'user_doctors' ] ) : '';
    $user_programms = isset( $_POST[ 'user_programms' ] ) ? sanitize_text_field( $_POST[ 'user_programms' ] ) : '';

    $center_name = isset( $_POST[ 'center-name' ] ) ? sanitize_text_field( $_POST[ 'center-name' ] ) : '';

    $msg = $_POST[ 'message' ] ? $_POST[ 'message' ] : '';

    if ( $msg ) {wp_die( 'Недопустимые данные! Попробуйте снова' );}

    if ( ! $phone  )  {$error = 'Пожалуйста заполните поле с телефоном.';}
    if ( ! $name  )  {$error = 'Пожалуйста укажите имя.';}
    // if ( ! $user_msg  )  { $error = 'Пожалуйста укажите сообщение.'; }
    if ( ! $name && ! $phone )  {
        $error = 'Пожалуйста заполните поле имя и телефон.';
    }
    // if ( ! $name && ! $user_msg )  {$error = 'Пожалуйста заполните поле имя и сообщение.';}
    // if ( ! $name && ! $phone && ! $user_msg )  {$error = 'Пожалуйста заполните все поля.';}
    if ( $error ) {
        $form_array[ 'result' ] = 'error';
        $form_array[ 'content' ] = $error;
        echo json_encode( $form_array );
    } else {
        $msg = "
            <h2>Сообщение с сайта olimp5.ru</h2>
            <p><strong>Имя: </strong> {$name}</p>
            <p><strong>Телефон: </strong> {$phone}</p>
            <p><strong>Сообщение: </strong> {$user_msg}</p>
            <p><strong>Страница сайта, с которой отправили заявку: </strong> {$curr_url}</p>
            ";
        if ( $user_service ) {$msg .= "<p><strong>Услуга, на которую планируется запись: </strong> {$user_service}</p>";}
        if ( $user_servicestax ) {$msg .= "<p><strong>Направление, на которое планируется запись: </strong> {$user_servicestax}</p>";}
        if ( $user_doctors ) {$msg .= "<p><strong>Врач, к которому планируется запись: </strong> {$user_doctors}</p>";}
        if ( $user_programms ) {$msg .= "<p><strong>Программа, на которую планируется запись: </strong> {$user_programms}</p>";}
        if ( $center_name ) {$msg .= "<p><strong>Центр в который хотят записаться: </strong> {$center_name}</p>";}

        if( function_exists( 'carbon_get_theme_option' ) ){
          $emails = carbon_get_theme_option( 'emails' ) ? explode( ',', carbon_get_theme_option( 'emails' )  ) : get_option( 'admin_email' );
        }
        // $emails = get_option( 'admin_email' );
        // $emails = 'burtnek.roman@yandex.ru';
        $site_url = site_url();
        $protocols =  array( 'http://', 'https://' );
        $url = str_replace( $protocols, '', $site_url );
        $headers = array(
            'From: Сообщение с сайта <no-reply@'. $url .'>',
            'content-type: text/html',
            'Reply-To: Olimp5 <no-reply@'. $url .'>',
            'X-Sender: Сообщение с сайта <no-reply@'. $url .'>',
            'X-Mailer: PHP/' . phpversion().'',
            'X-Priority: 1',
            'Return-Path: <no-reply@'. $url .'>',
            'MIME-Version: 1.0',
        );
        $mail = wp_mail( $emails, "Новое сообщение с сайта", $msg, $headers );
        if( $mail ){
            $form_array[ 'result' ] = 'success';
            $form_array[ 'content' ] = 'Сообщение успешно отправлено.';
            echo json_encode( $form_array );
        } else {
            $error = 'Сообщение не удалось отправить.';
            $form_array[ 'result' ] = 'error';
            $form_array[ 'content' ] = $error;
            echo json_encode( $form_array );
        };
    }
    wp_die();
}
?>
<script src="https://www.google.com/recaptcha/api.js?render=6Le1OIotAAAAAEozeVaiXp1opEP1GKFaw-RqO0cS"></script>



<?php
/* CAPTCHA DEBUG START */

$captcha_token = isset($_POST['g-recaptcha-response'])
    ? sanitize_text_field(wp_unslash($_POST['g-recaptcha-response']))
    : '';

if (!$captcha_token) {
    wp_send_json([
        'result'  => 'error',
        'content' => 'CAPTCHA token отсутствует.'
    ]);
}

$captcha = wp_remote_post(
    'https://www.google.com/recaptcha/api/siteverify',
    [
        'timeout' => 10,
        'body' => [
            'secret'   => '6Le1OIotAAAAABZiDiblWC4II-p9yG86aL5k0alh',
            'response' => $captcha_token,
        ]
    ]
);

if (is_wp_error($captcha)) {
    wp_send_json([
        'result'  => 'error',
        'content' => 'Ошибка запроса Google: ' . $captcha->get_error_message()
    ]);
}

$captcha_result = json_decode(
    wp_remote_retrieve_body($captcha),
    true
);

if (empty($captcha_result['success'])) {
    wp_send_json([
        'result'  => 'error',
        'content' => 'Google отклонил CAPTCHA: ' .
            implode(', ', $captcha_result['error-codes'] ?? [])
    ]);
}

if (($captcha_result['action'] ?? '') !== 'rb_popup_form') {
    wp_send_json([
        'result'  => 'error',
        'content' => 'Неверный action CAPTCHA: ' .
            ($captcha_result['action'] ?? 'не указан')
    ]);
}

if (($captcha_result['score'] ?? 0) < 0.5) {
    wp_send_json([
        'result'  => 'error',
        'content' => 'Низкий score CAPTCHA: ' .
            ($captcha_result['score'] ?? 0)
    ]);
}

/* CAPTCHA DEBUG END */
?>