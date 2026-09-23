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
	
	$xml_pricelist=DOCUMENT_ROOT.'/import2/ckz_pricelist_export_09.03.2026.xml';
	if(file_exists($xml_pricelist)){
		$xml_pricelist_data=simplexml_load_file('compress.zlib://'.$xml_pricelist);
		$pricelist_data=$xml_pricelist_data->ROW;
		if(isset($xml_pricelist_data->ROW) && count($pricelist_data)>0){
			$ii=1;
			echo '<style>td{padding:10px;border:1px solid black}.no{background:#ffbfbf}</style><table><tr><td>№</td><td>Артикул</td><td>Цена файл</td><td>Цена сайт</td><td>Инфо</td></tr>';
			foreach($pricelist_data as $price_item){
				if($ii<99999){
					$info='';
					$post_id_db=$wpdb->get_var('SELECT post_id FROM '.$wpdb->prefix.'postmeta WHERE meta_key="_rb_serv_code" AND meta_value="'.(string)$price_item->code_real.'"');
					if(empty($post_id_db)){
						$info.='Не найден артикул';
						$post_id_db=$wpdb->get_var('SELECT ID FROM '.$wpdb->prefix.'posts WHERE post_title="'.(string)$price_item->text.'" AND post_type="center-services" AND post_status="publish"');
						if(empty($post_id_db)) $info.=' | Не найден по названию';
						else $info.=' | Найден по названию';
					}
					$price_db=0;
					if(!empty($post_id_db))
						$price_db=$wpdb->get_var('SELECT meta_value FROM '.$wpdb->prefix.'postmeta WHERE meta_key="_rb_serv_price" AND post_id="'.$post_id_db.'"');
					
					$no=$price_db!=(int)$price_item->price_4078 ? ' class="no"' : '';
					echo '<tr'.$no.'><td>'.$ii.'</td><td>'.$price_item->code_real.'</td><td>'.$price_item->price_4078.'</td><td>'.$price_db.'</td><td>'.$info.'</td></tr>';
					
					$ii++;
				}
			}
			echo '</table>';
		}
	}
	
	
?>