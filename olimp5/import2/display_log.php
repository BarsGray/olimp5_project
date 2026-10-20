<?php 
	define('DOCUMENT_ROOT','/home/c/ch29335/olimp5.ru/public_html');
	require_once(DOCUMENT_ROOT.'/wp-load.php');

//=================================================================================
  function add_log($text){
		$logfile=fopen(DOCUMENT_ROOT.'/import2/logs2/log2_'.date('Y-m-d').'.txt','a');
		$mytext=date('H:i:s',strtotime('+3 hours'))." ".$text."\r";
		fwrite($logfile,$mytext);
		fclose($logfile);
	}
  add_log('+++++++++++++++++++++++++++++++++++++++++ Start +++++++++++++++++++++++++++++++++++++++++');
//=================================================================================
  // $products = get_posts([
  //   'post_type'      => 'center-services',
  //   'post_status'    => 'any',
  //   'posts_per_page' => -1,
  //   'meta_query'     => [
  //     [
  //       'key'     => '_rb_serv_code',
  //       'value'   => 'ДМСЭНД1072',
  //       'compare' => '=',
  //     ],
  //   ],
  // ]);

  // foreach ($products as $product) {
  //   echo $product->ID . ' — ' . esc_html($product->post_title) . '<br>';
  // }
//=================================================================================

$products = get_posts([
    'post_type'      => 'center-services',
    'post_status'    => 'any',
    'posts_per_page' => -1,
    'meta_key'       => '_rb_serv_code',
    'orderby'        => 'meta_value',
    'order'          => 'ASC',
]);

$grouped = [];  

foreach ($products as $product) {
  $code = get_post_meta($product->ID, '_rb_serv_code', true);

  // Пропускаем пустые коды
  if ($code === '') continue;

  $grouped[$code][] = [
    'ID'    => $product->ID,
    'title' => $product->post_title,
  ];
}

// Выводим только коды, которые встречаются больше одного раза
foreach ($grouped as $code => $items) {
  
  if (count($items) > 1) {
    // echo '<h3>Код: ' . esc_html($code) . '</h3>';
    add_log('//=================================================================================');
    add_log("Код: $code");
    
    foreach ($items as $item) {
      // echo 'ID: ' . $item['ID'] . ' — ' . esc_html($item['title']) . '<br>';
      add_log("ID: {$item['ID']} — {$item['title']}");
    }
    
    // echo '<hr>';
  }
}


//=================================================================================
// echo '<pre>';
// print_r(get_post_meta(874));
// echo '</pre>';