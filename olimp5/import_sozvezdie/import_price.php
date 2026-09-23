<?php
	
	define('DOCUMENT_ROOT','/home/a/aspectvrn/constellation/public_html');
	require_once(DOCUMENT_ROOT.'/wp-load.php');
	global $wpdb;
	
	$xml_pricelist=DOCUMENT_ROOT.'/import/pricelist_export_17.03.2026.xml';
	if(file_exists($xml_pricelist)){
		$xml_pricelist_data=simplexml_load_file('compress.zlib://'.$xml_pricelist);
		$pricelist_data=$xml_pricelist_data->ROW;
		if(isset($xml_pricelist_data->ROW) && count($pricelist_data)>0){
			$wpdb->update($wpdb->prefix.'price',array('status' => 0),array('status' => 1));
			foreach($pricelist_data as $price_item){
				$id_db=$wpdb->get_var('SELECT id FROM '.$wpdb->prefix.'price WHERE code_real="'.(string)$price_item->code_real.'"');
				if(empty($id_db))
					$wpdb->insert(
						$wpdb->prefix.'price',
						array(
							'status' => 1,
							'print_code' => (string)$price_item->print_code,
							'print_text' => !empty((string)$price_item->text) ? (string)$price_item->text : (string)$price_item->print_text,
							'code_real' => (string)$price_item->code_real,
							'price_4078' => (string)$price_item->price_4078,
							'duration' => (string)$price_item->duration
						)
					);
				if(!empty($id_db))
					$wpdb->update(
						$wpdb->prefix.'price',
						array(
							'status' => 1,
							'print_code' => (string)$price_item->print_code,
							'print_text' => !empty((string)$price_item->text) ? (string)$price_item->text :(string)$price_item->print_text,
							'price_4078' => (string)$price_item->price_4078,
							'duration' => (string)$price_item->duration
						),
						array('id' => $id_db)
					);
			}
		}
	}
	
	
?>