<?php

include "../lib/php/functions.php";

$output = [];

$data = json_decode(file_get_contents("php://input"));


if(!isset($data->type)) {

	$output['error'] = "No Type Provided";

} else switch($data->type) {

	case "product_all":
		$output['result'] = getRows(makeConn(),"SELECT
			*
			FROM `products`
			ORDER BY `price` ASC
			LIMIT 16
			");
	break;

	case "product_from_id":
		$output['result'] = getRows(makeConn(),"SELECT
			*
			FROM `products`
			WHERE id = '$data->id'
			");
	break;

	case "product_search":
		$output['result'] = getRows(makeConn(),"SELECT
			*
			FROM `products`
			WHERE 
				`product_name` LIKE '%{$data->search}%' OR
				`brand` LIKE '%{$data->search}%' OR
				`material` LIKE '%{$data->search}%' OR
				`color` LIKE '%{$data->search}%' OR
				`style` LIKE '%{$data->search}%' OR
				`category` LIKE '%{$data->search}%'
			ORDER BY `price` ASC
			LIMIT 16
			");
	break;

	case "product_filter":
	
		$output['result'] = getRows(makeConn(),"SELECT
			*
			FROM `products`
			WHERE `$data->column` = '$data->value'
			ORDER BY `price` ASC
			LIMIT 16
			");
	break;

	case "product_sort":
		$output['result'] = getRows(makeConn(),"SELECT
			*
			FROM `products`
			ORDER BY `$data->column` $data->dir
			LIMIT 16
			");
	break;

	default:
		$output['error'] = "No Matched Type";
}



echo json_encode(
	$output,
	JSON_UNESCAPED_UNICODE|
	JSON_NUMERIC_CHECK
);