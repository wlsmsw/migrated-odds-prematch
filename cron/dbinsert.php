<?
include_once("connection.php");

date_default_timezone_set('Asia/Manila');

function save_match($_dataArray, $_table){

	global $sv_conn;
	$date_added = date('Y-m-d H:i:s');

	/*echo '<pre>';
	print_r($_dataArray);
	echo '</pre>';
	exit;*/
	
	foreach($_dataArray AS $_countryKey => $_countryVal){

		foreach($_countryVal AS $_leagueKey => $_leagueVal){

			if (is_array($_leagueVal)) {

				foreach($_leagueVal AS $_matchKey => $_matchVal){

					if (is_array($_matchVal)) {

						$_match = "SELECT COUNT(match_id) FROM ".$_table." WHERE match_id = '".$_matchVal['match_id']."';";
						//$_res = mysql_query($_match); $_row = mysql_fetch_row($_res); $_cnt = $_row[0]; // old
						$_res = $sv_conn->query($_match);
						$_row = $_res->fetch_row();
						$_cnt = $_row[0];

						if($_cnt == 0){

							$_matchKey = $sv_conn->real_escape_string($_matchKey);
							
							$leagueID = (!empty($_leagueVal['league_id'])) ? $_leagueVal['league_id'] : $_matchVal['match_id'];

							if(isset($_matchVal['brMatchId'])){
								$_q = 'INSERT INTO '.$_table.' (sport, country_id, country_name, league_id, league_name, match_id, brMatchId, match_name, match_date, date_added)
									VALUES ("'.$_countryVal['sport'].'", "'.$_countryVal['country_id'].'", "'.$_countryKey.'", "'.$leagueID.'", "'.$_leagueKey.'", "'.$_matchVal['match_id'].'",
									"'.$_matchVal['brMatchId'].'", "'.$_matchKey.'", "'.$_matchVal['match_date'].'", "'.$date_added.'")';
							}
							else{
								$_q = 'INSERT INTO '.$_table.' (sport, country_id, country_name, league_id, league_name, match_id, match_name, match_date, date_added)
								VALUES ("'.$_countryVal['sport'].'", "'.$_countryVal['country_id'].'", "'.$_countryKey.'", "'.$leagueID.'", "'.$_leagueKey.'", "'.$_matchVal['match_id'].'",
								"'.$_matchKey.'", "'.$_matchVal['match_date'].'", "'.$date_added.'")';
							}

							//$insert = mysql_query($_q); // old
							$insert = $sv_conn->query($_q);

							if(!$insert) {
								//die('Invalid query: ' . $sv_conn->error);
								//die('Invalid query on dbinsert: query: ' . $_q .  ' error:' . $sv_conn->error . '<br/>');
							}else {
								//echo 'success data insert in: '. $_table . ' for sport: ' . $_countryVal['sport'] . ' <br/>';
							}
						}
					}

				}

			}

		}

	}

	// if(!$insert)
		// die('Invalid query: ' . mysql_error());
	// else
		// echo 'Match has been inserted: ' . $date_added . '; ';

}

function save_odds($_dataArray, $_table){

	global $sv_conn;
	$date_added = date('Y-m-d H:i:s');

	foreach($_dataArray AS $_marketKey => $_marketVal){

		foreach($_marketVal['odds_detail'] AS $_oddsKey => $_oddsVal){

			$_match = "SELECT COUNT(team_id) FROM ".$_table." WHERE match_id = '".$_marketVal['match_id']."' AND team_id ='".$_oddsKey."'";
			//$_res = mysql_query($_match); $_row = mysql_fetch_row($_res); $_cnt = $_row[0]; // old
			$_res = $sv_conn->query($_match);
			$_row = $_res->fetch_row();
			$_cnt = $_row[0];

			if($_cnt == 0){

				$_q = 'INSERT INTO '.$_table.' (market_id, market_name, market_desc, match_id, team_id, team_name, team_price, date_added) VALUES ("'.$_marketKey.'",
					"'.$_marketVal['market_name'].'", "'.$_marketVal['market_desc'].'", "'.$_marketVal['match_id'].'", "'.$_oddsKey.'",
					"'.$_oddsVal['team_name'].'", "'.$_oddsVal['team_price'].'","'.$date_added.'" )';

				//$insert = mysql_query($_q); // old
				$insert = $sv_conn->query($_q);

				if(!$insert) {
					//die('Invalid query: ' . mysql_error()); // old
					//die('Invalid query: ' . $sv_conn->error);
					//die('Invalid query on dbinsert line 86 query: ' . $_q .  ' error:' . $sv_conn->error . '<br />');
				}else {
					//echo 'odds inserted in ' . $_table . ' on market name: ' . $_marketVal['market_name'] . ' datatime:' . $date_added . '<br>';
				}
			}
			else if($_cnt > 0){

				$_q = "UPDATE ".$_table." SET team_price  = '" . $_oddsVal['team_price'] . "' WHERE match_id = '".$_marketVal['match_id']."' AND team_id ='".$_oddsKey."'";

				//$update = mysql_query($_q); // old
				$update = $sv_conn->query($_q);

				if(!$update) {
					//die('Invalid query: ' . mysql_error()); // old
					//die('Invalid query: ' . $sv_conn->error);
					//die('Invalid query on dbinsert line 100 query: ' . $_q .  ' error:' . $sv_conn->error . '<br />');
				}else {
					//echo 'Updated odds of ' . $_oddsVal['team_name'] . ' has been inserted: ' . $date_added . '<br>';
				}
			}

		}

	}

	// if(!$insert)
		// die('Invalid query: ' . mysql_error());
	// else
		// echo 'Odds has been inserted: ' . $date_added . '; ';

}

/* function truncate_sport($sport){

	$_q = "DELETE `match_list`,`market_odds_list` FROM `match_list` JOIN `market_odds_list` ON match_list.match_id=market_odds_list.match_id WHERE match_list.sport = '".$sport."'";

	$delete = mysql_query($_q);

	if(!$delete)
		die('Invalid query: ' . mysql_error());
	else
		echo 'Truncated: ' . $sport . '; ';

} */
