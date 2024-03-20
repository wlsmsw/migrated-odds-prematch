<?
include_once("connection.php");

date_default_timezone_set('Asia/Manila');

$date_added = date('Y-m-d G:i:s');


$_q = "DELETE `match_list`,`market_odds_list` FROM `match_list` JOIN `market_odds_list` ON match_list.match_id=market_odds_list.match_id WHERE match_list.match_date < '".$date_added."'";

//$delete = mysql_query($_q); // old
$delete = $sv_conn->query($_q);

if(!$delete)
	//die('Invalid query: ' . mysql_error()); // old
	//die('Invalid query: ' . $sv_conn->error);
	die('Invalid query on dbdeleter query: ' . $_q .  ' error:' . $sv_conn->error . '<br/>');
//else
//	echo 'Old matches has been deleted (dbdeleter). '.$date_added;
