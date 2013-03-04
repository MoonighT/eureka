<?php
require_once __DIR__ . '/../util.php';
require_once __DIR__.'/facebook_api.php';
require_once __DIR__.'/db_query.php';

	$glo_rank = get_global_top();

	$result = array();

	foreach ($glo_rank as $key => $value) {
		$temp = array('name' => $value['name'],'credit'=>$value['credit'] );
		$result[]=$temp;
	}

	foreach ($result as $key => $val) {
		echo '<li><span2>' . ($key+1) .'<a href = "#">' . ' ' . $val['name'] .'</a><div style = "float:right">'
		 . $val['credit'] .'</div></span2></li>';
	}
?>