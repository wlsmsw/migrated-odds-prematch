<?
include("cron/connection.php");
global $sv_conn;
//exit;

function slugify($text) {
  // replace non letter or digits by -
  $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

  // trim
  $text = trim($text, '-');

  // transliterate
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

  // lowercase
  $text = strtolower($text);

  // remove unwanted characters
  $text = preg_replace('~[^-\w]+~', '', $text);

  if (empty($text))
  {
    return 'n-a';
  }

  return $text;
}

function filter_string($value) {

	$newVal = trim($value);

	$newVal = htmlspecialchars($newVal);

	//$newVal = mysql_real_escape_string($newVal);

	return $newVal;
}

date_default_timezone_set("Asia/Manila");

function get_active_sports() {
    global $sv_conn;
    
    $_q = "SELECT 
                a.sport 
            FROM `match_list` AS a 
                INNER JOIN `market_odds_list` AS b 
                ON a.match_id = b.match_id 
            WHERE a.country_name NOT LIKE '%electronic%' 
                AND ((b.market_name = 'Head to Head' AND (a.sport = 'Tennis' OR a.sport = 'Volleyball' OR a.sport = 'AmericanFootball' OR a.sport = 'TableTennis' OR a.sport = 'MMA/Boxing' OR a.sport = 'Rugby' OR a.sport = 'RugbyLeague' OR a.sport = 'RugbyUnion' OR a.sport = 'Cricket') AND Date(a.match_date)='" . date("Y-m-d") . "') 
                OR (b.market_name = 'Win/Draw/Win' AND (a.sport = 'Soccer' OR a.sport = 'Football') AND Date(a.match_date)='" . date("Y-m-d") . "') 
                OR ((b.market_name = 'Head to Head' OR b.market_name = 'Asian Handicap' OR b.market_name = 'Over / Under') AND a.sport = 'Basketball' AND Date(a.match_date)='" . date("Y-m-d") . "') 
                 OR (b.market_name = 'Win/Draw/Win' AND a.sport = 'IceHockey' AND Date(a.match_date)='" . date("Y-m-d") . "') 
                 OR (b.market_name = 'Outright' AND (a.sport = 'MotorSports' OR a.sport = 'Formula1')) 
                 OR (b.market_name = 'To Win the Fight' AND (a.sport = 'Boxing/MMA' OR a.sport = 'MMA/Boxing'))) 
            GROUP BY a.sport 
            ORDER BY a.sport ASC, 
                a.match_date ASC, 
                b.team_price ASC, 
                a.league_id ASC";
    
    $_res = $sv_conn->query($_q);
    if(!is_object($_res)) die('mysqli error: check tables');
    
    $sports = array();
    if ($_res->num_rows > 0) {
        while ($row = $_res->fetch_array(MYSQLI_ASSOC)) {
            $sports[] = $row['sport'];
        }
    }
    
    return $sports;
}

function data_odds($sport,$add_sql){

  global $sv_conn;


	$_current_date = date("Y-m-d");
	
	
	if($sport == 'Soccer') {
	    $_q = "SELECT * FROM `match_list` AS a INNER JOIN `market_odds_list` AS b ON a.match_id = b.match_id WHERE a.sport = '".$sport."' AND b.market_name = 'Win/Draw/Win' AND a.country_name NOT LIKE '%electronic%' AND Date(a.match_date)='" . $_current_date . "' ORDER BY a.match_date ASC, b.team_price ASC, a.league_id ASC";
	} elseif($sport == 'Basketball') {
	    $_q = "SELECT * FROM `match_list` AS a INNER JOIN `market_odds_list` AS b ON a.match_id = b.match_id WHERE a.sport = '".$sport."' AND (b.market_name = 'Head to Head' OR b.market_name = 'Asian Handicap' OR b.market_name = 'Over / Under') AND a.country_name NOT LIKE '%electronic%' AND Date(a.match_date)='" . $_current_date . "' ORDER BY a.match_date ASC, b.team_price ASC, a.league_id ASC";
	} elseif($sport == 'IceHockey') {
	    $_q = "SELECT * FROM `match_list` AS a INNER JOIN `market_odds_list` AS b ON a.match_id = b.match_id WHERE a.sport = '".$sport."' AND b.market_name = 'Win/Draw/Win' AND a.country_name NOT LIKE '%electronic%' AND Date(a.match_date)='" . $_current_date . "' ORDER BY a.match_date ASC, b.team_price ASC, a.league_id ASC";
	} elseif($sport == 'MotorSports') {
	    $_q = "SELECT * FROM `match_list` AS a INNER JOIN `market_odds_list` AS b ON a.match_id = b.match_id WHERE a.sport = '".$sport."' AND b.market_name = 'Outright' AND a.country_name NOT LIKE '%electronic%' AND Date(a.match_date)='" . $_current_date . "' ORDER BY a.match_date ASC, b.team_price ASC, a.league_id ASC";
	} elseif($sport == 'Formula1') {
	    $_q = "SELECT * FROM `match_list` AS a INNER JOIN `market_odds_list` AS b ON a.match_id = b.match_id WHERE a.sport = '".$sport."' AND b.market_name = 'Outright' AND a.country_name NOT LIKE '%electronic%' ORDER BY a.match_date ASC, b.team_price ASC, a.league_id ASC";
	} elseif($sport == 'MMA/Boxing' || $sport == 'Boxing/MMA') {
	    $_q = "SELECT * FROM `match_list` AS a INNER JOIN `market_odds_list` AS b ON a.match_id = b.match_id WHERE (a.sport = 'Boxing/MMA' OR a.sport = 'MMA/Boxing') AND b.market_name = 'To Win the Fight' AND a.country_name NOT LIKE '%electronic%' ORDER BY a.match_date ASC, b.team_price ASC, a.league_id ASC";
	} else {
	    $_q = "SELECT * FROM `match_list` AS a INNER JOIN `market_odds_list` AS b ON a.match_id = b.match_id WHERE a.sport = '".$sport."' AND a.country_name NOT LIKE '%electronic%' AND b.market_name = 'Head To Head' ORDER BY a.match_date ASC, b.team_price ASC, a.league_id ASC";
	}
	

	//panakip butas lang
	/*if($sport == 'Soccer' && empty($add_sql)){

		//$_q = "SELECT * FROM `match_list` AS a INNER JOIN `market_odds_list` AS b ON a.match_id = b.match_id WHERE a.sport = '".$sport."' AND b.market_name = 'Win/Draw/Win' ORDER BY a.match_date ASC, b.team_price ASC, a.league_id ASC";
		$_q = "SELECT * FROM `match_list` AS a INNER JOIN `market_odds_list` AS b ON a.match_id = b.match_id WHERE a.sport = '".$sport."' AND b.market_name = 'Win/Draw/Win' AND a.country_name NOT LIKE '%electronic%' AND Date(a.match_date)='" . $_current_date . "' ORDER BY a.match_date ASC, b.team_price ASC, a.league_id ASC";

	}
	else{

		switch($add_sql){

			case 'all':
				$_q = "SELECT * FROM `match_list` AS a INNER JOIN `market_odds_list` AS b ON a.match_id = b.match_id WHERE a.sport = '".$sport."' AND a.country_name NOT LIKE '%electronic%' ORDER BY a.match_date ASC, b.team_price ASC, a.league_id ASC";
				break;

			case 'head-to-head':
				$_q = "SELECT * FROM `match_list` AS a INNER JOIN `market_odds_list` AS b ON a.match_id = b.match_id WHERE a.sport = '".$sport."' AND a.country_name NOT LIKE '%electronic%' AND b.market_name = 'Head To Head' ORDER BY a.match_date ASC, b.team_price ASC, a.league_id ASC";
				break;

			case 'outright':
				$_q = "SELECT * FROM `match_list` AS a INNER JOIN `market_odds_list` AS b ON a.match_id = b.match_id WHERE a.sport = '".$sport."' AND a.country_name NOT LIKE '%electronic%' AND (b.market_name = 'Outright' OR b.market_name = 'Outright EN') ORDER BY a.match_date ASC, b.team_price ASC, a.league_id ASC";
				break;

			case 'win-draw-win':
				$_q = "SELECT * FROM `match_list` AS a INNER JOIN `market_odds_list` AS b ON a.match_id = b.match_id WHERE a.sport = '".$sport."' AND a.country_name NOT LIKE '%electronic%' AND b.market_name = 'Win/Draw/Win' ORDER BY a.match_date ASC, b.team_price ASC, a.league_id ASC";
				break;

			case 'today':
				$_q = "SELECT * FROM `match_list` AS a INNER JOIN `market_odds_list` AS b ON a.match_id = b.match_id WHERE a.sport = '".$sport."' AND a.country_name NOT LIKE '%electronic%' AND Date(a.match_date)='" . $_current_date . "' ORDER BY a.match_date ASC, b.team_price ASC, a.league_id ASC";
				break;

			default:
				//$_q = "SELECT * FROM `match_list` AS a INNER JOIN `market_odds_list` AS b ON a.match_id = b.match_id WHERE a.sport = '".$sport."' ORDER BY a.match_date ASC, b.team_price ASC, a.league_id ASC";
				$_q = "SELECT * FROM `match_list` AS a INNER JOIN `market_odds_list` AS b ON a.match_id = b.match_id WHERE a.sport = '".$sport."' AND a.country_name NOT LIKE '%electronic%' AND Date(a.match_date)='" . $_current_date . "' ORDER BY a.match_date ASC, b.team_price ASC, a.league_id ASC";
				break;

		}

	}*/


  $_res = $sv_conn->query($_q);
  if (!is_object($_res))
    die('mysqli error: check tables');

	$_outright = array();

	$_headTohead = array();

	//if (mysql_num_rows($_res) == 0){ // old
  if ($_res->num_rows == 0) {

		$_data = '';

	}
  else{

		//while ($row = mysql_fetch_array($_res, MYSQL_ASSOC)) { // old
    while ($row = $_res->fetch_array(MYSQLI_ASSOC)) {

			if($row['market_name'] == 'Outright EN'){

				$_outright[$row['country_id']][$row['league_id']][$row['match_name']]['league_details'] = array('country_name' => $row['country_name'], 'league_name' => $row['league_name'], 'market_name' => $row['market_name']);

				$_outright[$row['country_id']][$row['league_id']][$row['match_name']]['match'][$row['match_id']]['match_details'] = array('match_date' => $row['match_date']);

				$_outright[$row['country_id']][$row['league_id']][$row['match_name']]['match'][$row['match_id']]['team_details'][] = array('team_id' => $row['team_id'], 'team_name' => $row['team_name'], 'team_price' => $row['team_price']);

			}

			else{

				$_headTohead[$row['country_id']][$row['league_id']][$row['market_name']]['league_details'] = array('country_name' => $row['country_name'], 'league_name' => $row['league_name'], 'market_name' => $row['match_name'], 'real_name' => $row['market_name']);

				$_headTohead[$row['country_id']][$row['league_id']][$row['market_name']]['match'][$row['match_id']]['match_details'] = array('match_date' => $row['match_date']);

				$_headTohead[$row['country_id']][$row['league_id']][$row['market_name']]['match'][$row['match_id']]['team_details'][] = array('team_id' => $row['team_id'], 'team_name' => $row['team_name'], 'team_price' => $row['team_price']);


			}

		}

		foreach($_headTohead AS $_kCountry => $_vCountry){

			foreach($_vCountry AS $_kLeague => $_vLeague){

				foreach($_vLeague AS $_kMarket => $_vMarket){

						$_data[$_kCountry][$_kLeague][$_kMarket] = array('league_details' => $_vMarket['league_details'], 'match' => array_chunk($_vMarket['match'],8, true));

				}

			}

		}

		foreach($_outright AS $_kCountry => $_vCountry){

			foreach($_vCountry AS $_kLeague => $_vLeague){

				foreach($_vLeague AS $_kMarket => $_vMarket){

					foreach($_vMarket['match'] AS $_kMatch => $_vMatch){

							$_data[$_kCountry][$_kLeague][$_kMarket] = array('league_details' => $_vMarket['league_details'], 'match' => (array_chunk(array_chunk($_vMatch['team_details'],3, true),8, true)));

					}

				}

			}

		}

	}

	return $_data;
}

function display_data($sport,$_except){
	$_html = '';

	$datas = data_odds($sport,$_except);

	if(!empty($datas) == true){

		foreach(data_odds($sport,$_except) AS $_kCountry => $_vCountry){

			foreach($_vCountry AS $_kLeague => $_vLeague){

				//may if else dito para sa market name (head to head or etc)
				foreach($_vLeague AS $_kMatch => $_vMatch){

					//head to head
					if($_vMatch['league_details']['market_name'] != 'Outright EN'){

						$last_key = (key( array_slice( $_vMatch['match'], -1, 1, TRUE ) ) + 1);

						$matchtitle = '';
						$sportstitle = '';
						foreach($_vMatch['match'] AS $_kGroupTeam => $_vGroupTeam){

							$_html .= '<li class="list-batch" id="' . $_kLeague . '-' . $_kGroupTeam . '">';

							$_html .= '<ul class="list-batch-ul '.slugify($_vMatch['league_details']['real_name']).'">';
							
							$sportstitle = (!empty($_vMatch['league_details']['league_name'])) ? ' - ' : '';

							$_html .= '<li class="league-title">

								<div class="event-container"><span class="event-name">'.$_vMatch['league_details']['country_name'] . $sportstitle . $_vMatch['league_details']['league_name'].'';

								if($_vMatch['league_details']['real_name'] == 'Head To Head' || $_vMatch['league_details']['real_name'] == 'Win/Draw/Win' || $_vMatch['league_details']['real_name'] == 'To Win the Bout'  || $_vMatch['league_details']['real_name'] == 'To Win the Fight'   || $_vMatch['league_details']['real_name'] == '2 Way' ){

									// if(sizeof($_vMatch['match']) == 1)
										//$_html .= ' &mdash; ' . $_vMatch['league_details']['real_name'] . ' @ Match';
										$matchtitle = $_vMatch['league_details']['real_name'];

									// else
										// $_html .= '<div class="market-title-container"><span class="market-title">&mdash; ' . $_vMatch['league_details']['real_name'] . ' @ Match ('.++$_kGroupTeam.' OF ' . $last_key . ')</span></div>';

								} else {

									// if(sizeof($_vMatch['match']) == 1)
										//$_html .= '&mdash; '. $_kMatch . ' @ ' . $_vMatch['league_details']['market_name'] .' Match' ;
										//$matchtitle = $_kMatch;
										$matchtitle = $_vMatch['league_details']['real_name'];

									// else
										// $_html .= '<div class="market-title-container"><span class="market-title">&mdash; '. $_kMatch . ' @ ' . $_vMatch['league_details']['market_name'] .'Match ('.++$_kGroupTeam.' OF ' . $last_key . ')</span></div>' ;

								}
								$matchtitle = $_vMatch['league_details']['real_name'];

							$_html .= '</span> </div></li>';

							foreach($_vGroupTeam AS $_kTeamDetails => $_vTeamDetails){
							    
								 $time = date('h:i A',strtotime($_vTeamDetails['match_details']['match_date']));

								if(($_kGroupTeam - 1) == -1){

									$_html .= ' <li class="market-odds" id="' . $_kLeague . '-' . $_kGroupTeam . '_' . $_kTeamDetails . '">';
									
									if($sport == 'MMA/Boxing' || $sport == 'Boxing/MMA' || $sport == 'Formula1') {
									    $_html .= '<div class="match-title">'. $matchtitle .' ('. date('Y-m-d h:i A',strtotime($_vTeamDetails['match_details']['match_date'])) .') </div>';
									} else {
									    $_html .= '<div class="match-title">'. $matchtitle .' ('.$time.')  </div>';
									}

									$_html .= '			<div class="odds-selection">';

								}
								else{

									$_html .= ' <li class="market-odds" id="' . $_kLeague . '-' . ($_kGroupTeam - 1) . '_' . $_kTeamDetails . '">';
									
									if($sport == 'MMA/Boxing' || $sport == 'Boxing/MMA' || $sport == 'Formula1') {
									    $_html .= '<div class="match-title">'. $matchtitle .' ('. date('Y-m-d h:i A',strtotime($_vTeamDetails['match_details']['match_date'])) .') </div>';
									} else {
									    $_html .= '<div class="match-title">'. $matchtitle .' ('.$time.')  </div>';
									}

									$_html .= ' <div class="odds-selection">';

								}

								foreach($_vTeamDetails['team_details'] AS $_kTeam => $_vTeam){

									// $date = date_format(date(),"Y-m-d");
									 /*$date = date('Y-m-d',strtotime($_vTeamDetails['match_details']['match_date']));
									 $time = date('h:i A',strtotime($_vTeamDetails['match_details']['match_date']));*/

									if($_vMatch['league_details']['real_name'] == 'Head To Head' || $_vMatch['league_details']['real_name'] == 'To Win the Bout'  || $_vMatch['league_details']['real_name'] == '2 Way' || strpos($_vMatch['league_details']['real_name'] , 'Handicap') !== false || strpos($_vMatch['league_details']['real_name'] , 'Total Points') !== false){

										$_html .= '

										<div class="odds-container" id="' . $_vTeam['team_id'] . '">

											<div class="team-container">

											  <span class="odds-team">' . $_vTeam['team_name'] . '</span>

											</div>

											<div class="price-container">

											  <span class="odds-price">' . $_vTeam['team_price'] . '</span>

											</div>

										</div>';

									}

									else{

										$_html .= '

										<div class="odds-container-windrawwin" id="' . $_vTeam['team_id'] . '">

											<div class="team-container">

											  <span class="odds-team">' . $_vTeam['team_name'] . '</span>

											</div>

											<div class="price-container">

											  <span class="odds-price">' . $_vTeam['team_price'] . '</span>

											</div>

										</div>';

									}

								}


									 /*$_html .= '

									 <div class="date-container">

										<span class="market-date"><span class="clock-time"> '.$time.'</span></span>
										<!-- <span class="market-date">' . $date . '<span class="clock-time"> '.$time.'</span></span> --!>

									  </div>';*/

									$_html .= '</div>

										  </li>';

							}

							$_html .= '</ul>';

							$_html .= '</li>';

						}

					}

					//outright
					if($_vMatch['league_details']['market_name'] == 'Outright EN'){

						$last_key = (key( array_slice( $_vMatch['match'], -1, 1, TRUE ) ) + 1);

						foreach($_vMatch['match'] AS $_kGroupTeam => $_vGroupTeam){

							$_html .= '<li class="list-batch" id="' . slugify($_kMatch) . '-' . $_kGroupTeam . '">';

							$_html .= '<ul class="list-batch-ul">';

							$_html .= '<li class="league-title">

								<div class="event-container"><span class="event-name">'.$_vMatch['league_details']['country_name'].' - '.$_vMatch['league_details']['league_name'].' ';

								// if(sizeof($_vMatch['match']) == 1)
									//$_html .= '&mdash; '. $_vMatch['league_details']['market_name'] .' (' . $_kMatch . ')' ;

								// else
									// $_html .= '<div class="market-title-container"><span class="market-title">&mdash; '. $_vMatch['league_details']['market_name'] .' (' . $_kMatch . ') / '.++$_kGroupTeam.' OF ' . $last_key . '</span></div>' ;

							$_html .= '</span> </div></li>';

							foreach($_vGroupTeam AS $_kTeamDetails => $_vTeamDetails){

								$_html .= ' <li class="market-odds">

											<div class="odds-selection">';

								foreach($_vTeamDetails AS $_kTeam => $_vTeam){

									$_html .= '

									<div class="odds-container-outright" id="' . $_vTeam['team_id'] . '">

										<div class="team-container">

										  <span class="odds-team">' . $_vTeam['team_name'] . '</span>

										</div>

										<div class="price-container">

										  <span class="odds-price">' . $_vTeam['team_price'] . '</span>

										</div>

									</div>';

								}

								$_html .= '</div>

										  </li>';

							}

							$_html .= '</ul>';

							$_html .= '</li>';

						}

					}

				}

			}

		}

	}
	// else{

		// $_html .= '<div id="no-match">CURRENTLY NO AVAILABLE MARKET</div>';

	// }

	return $_html;
}
