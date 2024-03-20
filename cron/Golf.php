<?
include_once("dbinsert.php");

include_once("function.php");

date_default_timezone_set('Asia/Manila');

// if(!isset($_GET['a']) or empty($_GET['a'])){

	// echo '<h1>Sport not found: Please come back later<br/>Error Code: 10001</h1>';
	
	// exit;
	
// }

$sportReq = 'Golf';

all_func_xml($sportReq);

func_xml($sportReq);