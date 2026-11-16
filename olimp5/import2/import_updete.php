<?php
  // Обновление цен в услугах центра
  define('DOCUMENT_ROOT', '/home/c/ch29335/olimp5.ru/public_html');
  require_once(DOCUMENT_ROOT . '/wp-load.php');
  global $wpdb;

  function add_log($text) {
    $logfile = fopen(DOCUMENT_ROOT . '/import2/logs/log_' . date('Y-m-d') . '.txt','a');

    if ($logfile) {
      fwrite($logfile,date('H:i:s', strtotime('+3 hours')) . ' ' . $text . "\r\n");
      fclose($logfile);
    }
  }

  add_log('start');

  $xml_pricelist = DOCUMENT_ROOT . '/import2/ckz_pricelist_export_' . date('d.m.Y') . '.xml';

  if (file_exists($xml_pricelist)) {
      add_log('Файл найден');
      $xml_pricelist_data = simplexml_load_file('compress.zlib://' . $xml_pricelist);
      if ($xml_pricelist_data !== false && isset($xml_pricelist_data->ROW)) {
          $updated = 0;
          foreach ($xml_pricelist_data->ROW as $price_item) {
              // Код используется ТОЛЬКО для поиска
              $code = (string) $price_item->code_real;
              // Получаем все ID услуг с данным кодом
              $post_ids_db = $wpdb->get_col($wpdb->prepare("SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = %s AND meta_value = %s",'_rb_serv_code',$code));
              if (empty($post_ids_db)) continue;
              // Получаем цену из XML
              $price = (string) $price_item->price_4078;
              // Обновляем ИСКЛЮЧИТЕЛЬНО цену
              foreach ($post_ids_db as $post_id_db) {
                $result = $wpdb->update(
                  $wpdb->postmeta,
                  array('meta_value' => $price),
                  array('post_id' => (int) $post_id_db, 'meta_key' => '_rb_serv_price'),
                  array('%s'),
                  array('%d', '%s')
                );
                if ($result !== false) { $updated++; }
              }
          }
          add_log('Обновлено цен: ' . $updated);
      } else {
          add_log('Ошибка чтения XML');
      }
  } else {
      add_log('Файл не найден');
  }
  add_log('end');