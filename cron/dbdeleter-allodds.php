<?
include_once("connection.php");

date_default_timezone_set('Asia/Manila');

$date_added = date('Y-m-d G:i:s');


$_q = "DELETE `all_match_list`,`all_market_odds_list` FROM `all_match_list` JOIN `all_market_odds_list` ON all_match_list.match_id=all_market_odds_list.match_id WHERE all_match_list.match_date < '".$date_added."'";

//$delete = mysql_query($_q); //old
$delete = $sv_conn->query($_q);

if(!$delete)
	//die('Invalid query: ' . mysql_error());//old
	die('Invalid query on dbdeleter-allodds: query: ' . $_q .  ' error:' . $sv_conn->error . '<br/>');
//else
//	echo 'Old matches has been deleted (db deleter allodds). '.$date_added;
