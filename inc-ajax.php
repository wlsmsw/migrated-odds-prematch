<?

include('includes/functions.php');

$action = filter_string($_POST['action']);
$sport = filter_string($_POST['sport']);
$add_sql = filter_string($_POST['q']);

switch ($action) {

    case "fetchData":
	
        echo getData($sport,$add_sql);
		
        break;
		
    default:
	
        echo "Error Occur";
		
}

function getData($sport,$add_sql){

	$datas = data_odds($sport,$_except);

	if(!empty($datas) == true){
		
		foreach(data_odds($sport,$add_sql) AS $_kCountry => $_vCountry){
		
			foreach($_vCountry AS $_kLeague => $_vLeague){
			
				foreach($_vLeague AS $_kMatch => $_vMatch){
				
					foreach($_vMatch['match'] AS $_kGroupTeam => $_vGroupTeam){
					
						foreach($_vGroupTeam AS $_kMatchID => $_vMatchDetails){
			
							// $_data[$_kLeague . '-' . $_kGroupTeam][$_kMatch] = array('league_details' => $_vMatch['league_details'], 'match' => $_vMatch['match']);
						
							if($_vMatch['league_details']['market_name'] != 'Outright EN')
								$_data[$_kLeague . '-' . $_kGroupTeam][$_kMatch] = array('league_id' => $_kLeague, 'league_details' => $_vMatch['league_details'], 'match' => array($_kGroupTeam => $_vGroupTeam));
							else
								$_data[$_kMatch . '-' . $_kGroupTeam][$_kMatch] = array('league_id' => $_kLeague, 'league_details' => $_vMatch['league_details'], 'match' => array($_kGroupTeam => $_vGroupTeam));
						
						}
					
					}
			
				}
			
			}
		
		}
		
		echo json_encode(array("success" => array("html" => $_data)));
		
	}
	
	else
		echo json_encode(array("error" => array("html"=>"<div id='no-match'>Currently No Available Market</div>")));
	
}