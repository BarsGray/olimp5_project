<?php
define('DOCUMENT_ROOT', '/home/c/ch29335/olimp5.ru/public_html');
require_once(DOCUMENT_ROOT . '/wp-load.php');

global $wpdb;

add_log('++++++++++++ Start ++++++++++++');

// Получаем все кодировки и ID товаров
$results = $wpdb->get_results("
    SELECT 
        pm.meta_id,
        pm.post_id,
        pm.meta_value AS code,
        p.post_title AS title
    FROM {$wpdb->prefix}postmeta pm
    INNER JOIN {$wpdb->prefix}posts p 
        ON p.ID = pm.post_id
    WHERE 
        pm.meta_key = '_rb_serv_code'
        AND p.post_type = 'center-services'
    ORDER BY pm.meta_value ASC, pm.post_id ASC
", ARRAY_A);

// Формируем массив
$data = [];

foreach ($results as $item) {
    $data[] = [
        'meta_id' => (int) $item['meta_id'],
        'code'    => $item['code'],
        'post_id' => (int) $item['post_id'],
        'title'   => $item['title'],
    ];
}

// Сохраняем JSON
$json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

file_put_contents(DOCUMENT_ROOT . '/import2/codes.json', $json);

// Сохраняем TXT
$txt = '';

foreach ($data as $item) {
    $txt .= "Кодировка: {$item['code']}\r\n";
    $txt .= "ID товара: {$item['post_id']}\r\n";
    $txt .= "ID записи meta: {$item['meta_id']}\r\n";
    $txt .= "Название: {$item['title']}\r\n";
    $txt .= "------------------------------------\r\n";
}

file_put_contents(DOCUMENT_ROOT . '/import2/codes.txt', $txt);

echo 'Всего записей: ' . count($data);

add_log('Всего записей: ' . count($data));
add_log('++++++++++++ End ++++++++++++');


// Логирование
function add_log($text) {

    $logfile = fopen(DOCUMENT_ROOT . '/import2/logs2/log2_' . date('Y-m-d') . '.txt','a');

    if ($logfile) {
        fwrite($logfile, date('H:i:s', strtotime('+3 hours')) . ' ' . $text . "\r\n");
        fclose($logfile);
    }
}