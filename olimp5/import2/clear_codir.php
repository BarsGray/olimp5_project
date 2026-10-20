<?php 
	define('DOCUMENT_ROOT','/home/c/ch29335/olimp5.ru/public_html');
	require_once(DOCUMENT_ROOT.'/wp-load.php');

  function add_log($text){
		$logfile=fopen(DOCUMENT_ROOT.'/import2/logs2/log_'.date('Y-m-d').'.txt','a');
		$mytext=date('H:i:s',strtotime('+3 hours'))." ".$text."\r";
		fwrite($logfile,$mytext);
		fclose($logfile);
	}
  add_log('+++++++++++++++++++++++++++++++++++++++++ Start +++++++++++++++++++++++++++++++++++++++++');
  
  $count_to_reset = 0;

  $xml_pricelist=DOCUMENT_ROOT.'/import2/ckz_pricelist_export_15.10.2026.xml';
	if(file_exists($xml_pricelist)){

    $xml_pricelist_data = simplexml_load_file($xml_pricelist);
		$pricelist_data=$xml_pricelist_data->ROW;

		if(isset($xml_pricelist_data->ROW) && count($pricelist_data)>0){
			foreach($pricelist_data as $price_item){
        // if($count_to_reset >= 50) return;

        // echo '<br>';
        // echo $price_item->print_text . ' ';
        // echo $price_item->code_real;
        // echo '<br>';

        $post_ids = $wpdb->get_col(
          $wpdb->prepare('SELECT post_id FROM ' . $wpdb->prefix . 'postmeta WHERE meta_key = %s AND meta_value = %s', '_rb_serv_code', (string)$price_item->code_real)
        );

        if (!empty($wpdb->last_error)) {add_log('Ошибка SQL при поиске ' . $price_item->code_real . ': ' . $wpdb->last_error);}  ///
        add_log('Получины записи'); ///
        add_log($price_item->code_real);
        add_log(count($post_ids));

        if (count($post_ids) > 1) {
          add_log($price_item->code_real . ' больше одной записи');
          foreach($post_ids as $post_id_db) {
            $post_title = $wpdb->get_var(
              $wpdb->prepare('SELECT post_title FROM ' . $wpdb->prefix . 'posts WHERE ID = %d', (int)$post_id_db)
            );
            if ($post_title === $price_item->text) continue;
            $result = $wpdb->update(
              $wpdb->prefix.'postmeta',array('meta_value' => '900'),array('meta_key' => '_rb_serv_code','post_id' => (int)$post_id_db)
            );

            if ($result === false) {add_log('Ошибка обновления post_id=' . $post_id_db . ': ' . $wpdb->last_error);} ///
          }
        }

        if ($count_to_reset > 0 && $count_to_reset % 50 === 0) {usleep(500000);}
        $count_to_reset++;
      }
		}
	} else {
  add_log('Файл не найден');
}