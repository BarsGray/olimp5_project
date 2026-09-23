<?php
	
	//обновление цен в услугах центра
	define('DOCUMENT_ROOT','/home/c/ch29335/olimp5.ru/public_html');
	require_once(DOCUMENT_ROOT.'/wp-load.php');
	global $wpdb;

	function add_log($text){
		$logfile=fopen(DOCUMENT_ROOT.'/import2/logs/log_'.date('Y-m-d').'.txt','a');
		$mytext=date('H:i:s',strtotime('+3 hours'))." ".$text."\r";
		fwrite($logfile,$mytext);
		fclose($logfile);
	}
	add_log('start');
	
	// exit;
	
	$xml_pricelist=DOCUMENT_ROOT.'/import2/ckz_pricelist_export_19.08.2026.xml';
	// $xml_pricelist=DOCUMENT_ROOT.'/import2/ckz_pricelist_export_'.date('d.m.Y').'.xml';
	if(file_exists($xml_pricelist)){
		add_log('Файл найден');
		$xml_pricelist_data=simplexml_load_file('compress.zlib://'.$xml_pricelist);
		$pricelist_data=$xml_pricelist_data->ROW;
		if(isset($xml_pricelist_data->ROW) && count($pricelist_data)>0){
			foreach($pricelist_data as $price_item){
				// $post_id_db=$wpdb->get_var('SELECT post_id FROM '.$wpdb->prefix.'postmeta WHERE meta_key="_rb_serv_code" AND meta_value="'.(string)$price_item->code_real.'"');



				// if((string)$price_item->code_real == 'ТРА1122') {
				// 	$count = $wpdb->get_var(
				// 		$wpdb->prepare(
				// 			"SELECT COUNT(*)
				// 			FROM {$wpdb->postmeta}
				// 			WHERE meta_key = '_rb_serv_code'
				// 			AND meta_value = %s",
				// 			(string)$price_item->code_real
				// 		)
				// 	);

				// add_log('code_real: ['.(string)$price_item->code_real.']');
				// add_log('Количество найденных записей: ['.$count.']');
				// }

				// if ((string)$price_item->code_real == 'ТРА1122') {
				// 	$rows = $wpdb->get_results(
				// 		$wpdb->prepare(
				// 			"SELECT post_id, meta_id, meta_value
				// 			FROM {$wpdb->postmeta}
				// 			WHERE meta_key = '_rb_serv_code'
				// 			AND meta_value = %s",
				// 			(string)$price_item->code_real
				// 		)
				// 	);
				// 	add_log('code_real: ['.(string)$price_item->code_real.']');
				// 	add_log('Количество найденных записей: ['.count($rows).']');
				// 	foreach ($rows as $row) {
				// 		$serv_price = carbon_get_post_meta(
				// 			$row->post_id,
				// 			'rb_serv_price'
				// 		);
				// 		add_log(
				// 			'post_id=['.$row->post_id.'] '.
				// 			'title=['.get_the_title($row->post_id).'] '.
				// 			'rb_serv_code=['.$row->meta_value.'] '.
				// 			'rb_serv_price=['.$serv_price.']'
				// 		);
				// 	}
				// }

				
				
				$post_id_db=$wpdb->get_var('SELECT post_id FROM '.$wpdb->prefix.'postmeta WHERE meta_key="_rb_serv_code" AND meta_value="'.(float)$price_item->price_4078.'"');
				if(empty($post_id_db))
					$post_id_db=$wpdb->get_var('SELECT ID FROM '.$wpdb->prefix.'posts WHERE post_title="'.(string)$price_item->text.'" AND post_type="center-services" AND post_status="publish"');
				if(!empty($post_id_db))
					$wpdb->update(
						$wpdb->prefix.'postmeta',
						array('meta_value' => (string)$price_item->code_real),
						array('meta_key' => '_rb_serv_code','post_id' => (int)$post_id_db)
					);
			}
		}
	} else {
		add_log('Файл не найден');
	}
	add_log('end');
?>