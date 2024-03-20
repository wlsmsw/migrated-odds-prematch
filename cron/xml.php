<?
include_once("dbinsert.php");

date_default_timezone_set('Asia/Manila');

if(!isset($_GET['a']) or empty($_GET['a'])){

	echo '<h1>Sport not found: Please come back later<br/>Error Code: 10001</h1>';
	
	exit;
	
}

$sportReq = $_GET['a'];

require_once('mobile-api.php');

$API = new mobileAPI();

$_allMarket = false;

if(isset($_GET['all']))
	$_allMarket = true;

//hacks .. view all market
// $_allMarket = true;
	
$_sportsReq = $API->allSports();

$_allsport = array();

foreach($_sportsReq as $key => $val){

	$_allsport['p'. $val['id']] = str_replace(' ','',$val['desc']);
	
}

if(!in_array($sportReq,$_allsport)){

	echo '<h1>Sport not found: Please come back later<br/>Error Code: 10002</h1>';
	
	exit;
	
}

$_reqSport = array_search($sportReq,$_allsport);

if(empty($_reqSport)){

	echo '<h1>Sport not found: Please come back later<br/>Error Code: 10003</h1>';
	
	exit;
	
}


$url = $API->restURL . '/ff/'.$_reqSport;

$API->url($url);

$API->m_debug();

if($API->status){

	$jsonE = json_decode($API->result);

	if(!empty($jsonE->ids)){
	
		$items = $jsonE->items;
		
		$_np = $_c = $_p = $_e = $_m = $_o = $_es = array();
		
		foreach($items as $key => $val){
		
			if(isset($val->parent)){
			
				$_parent = $val->parent;
				
				$_group = array('p','e','m','o');
				
				foreach($_group as $grp){
				
					$pos = strpos($key, $grp);
					
					if ( $pos !== false){
					
						switch ($grp) {
						
							case 'p':
							
								if( isset($val->parent) && ($val->parent != 'p0')){
								
									if( $val->parent == $_reqSport){
									
										$_c[$key] = $val;
										
									}else{
									
										$_p[$key] = $val;
										
									}
									
								}
								
								break;
								
							case 'e':{
							
								$_e[$key] = $val; 
								
								foreach($_e as $key => $val){
								
									$date = date("Y-m-d");
									
									$todayDate = (string)$val->start;

		
									$smp = $todayDate;
									
									$_mY = substr($smp,0,2);
									
									$_mM = substr($smp,2,2);
									
									$_mD = substr($smp,4,2);
									
									$_mH = substr($smp,6,2);
									
									$_mMn = substr($smp,8,2);

									$today_date = date('Y-m-d',mktime($_mH + 8, $_mMn, 0, $_mM, $_mD, $_mY));
									
									if($today_date === $date){
									
										$_es[$key] = $val; 
									
									}
									
								}
								
								break;
								
							}case 'm':
							
								$_m[$key] = $val; break;
								
							case 'o':
							
								$_o[$key] = $val; break;
								
						}
						
					}
					
				}
				
			}
			
		}		
		
		if($_allMarket == true){
		
			//get all market of each event.. it takes time .. sloooowwwwwwwwwwwwwwwwwww
			$_impEvents = implode(',', array_keys($_es));
			
			// print_r($_impEvents);
			
			$url = $API->restURL . '/ff/'.$_impEvents .'?ext=1';
			
			$API->url($url);
			
			$jsonM = json_decode($API->result);
			
			$itemM = $jsonM->items;
			
			foreach($itemM as $key => $val){
				
				if(isset($val->parent)){
				
					$_parent = $val->parent;
					
					$_group = array('e','m','o');
					
					foreach($_group as $grp){
					
						$pos = strpos($key, $grp);
						
						if ( $pos !== false){
						
							switch ($grp) {
							
								case 'e':
								
									$_e[$key] = $val; break;
									
								case 'm':
								
									$_m[$key] = $val; break;
									
								case 'o':
								
									$_o[$key] = $val; break;
									
							}
							
						}
						
					}
					
				}
				
			}
			
		}
		
		$_matchArray = array();
		
		$_marketArray = array();
		
		foreach($_c as $key => $val){
		
			// echo '<pre>';
			
			// print_r($_p);
			
			// echo '</pre>';
		
			$_matchArray[$val->desc] = array("country_id" => $key, "sport" => $sportReq);
		
		}
		
		foreach($_p as $key => $val){
		
		
			if( isset($val->path->Category)){
			
				$_parent = $val->path->Category;
		
				$_matchArray[$_parent][$val->desc] = array("league_id" => $key);
			
			}
			else if(isset($val->path->{'Country/Region'})){
			
				$_parent = $val->path->{'Country/Region'};
		
				$_matchArray[$_parent][$val->desc] = array("league_id" => $key);
			
			}
		}
		
		foreach($_e as $key => $val){
		
			$eventDate = (string)$val->start;

			$smp = $eventDate;
			
			$_mY = substr($smp,0,2);
			
			$_mM = substr($smp,2,2);
			
			$_mD = substr($smp,4,2);
			
			$_mH = substr($smp,6,2);
			
			$_mMn = substr($smp,8,2);

			$match_date = date('Y-m-d H:i:s',mktime($_mH + 8, $_mMn, 0, $_mM, $_mD, $_mY));
		
			if( isset($val->path->Category)){
			
				$_parent = $val->path->Category;
				
				if(isset($val->path->League))
					$_subparent = $val->path->League;
					
				if(!isset($_matchArray[$_parent][$_subparent]['league_id']))
					$_matchArray[$_parent][$_subparent] = array("league_id" => $key);
				
				if(isset($val->brMatchId))
					$_matchArray[$_parent][$_subparent][$val->desc] = array("match_id" => $key, "match_date" => $match_date, "brMatchId" => $val->brMatchId );
				
				else
					$_matchArray[$_parent][$_subparent][$val->desc] = array("match_id" => $key, "match_date" => $match_date );
			
			}
			else if(isset($val->path->{'Country/Region'})){
			
				$_parent = $val->path->{'Country/Region'};
				
				if(isset($val->path->League))
					$_subparent = $val->path->League;
				
				if(isset($val->brMatchId))
					$_matchArray[$_parent][$_subparent][$val->desc] = array("match_id" => $key, "match_date" => $match_date, "brMatchId" => $val->brMatchId );
					
				else
					$_matchArray[$_parent][$_subparent][$val->desc] = array("match_id" => $key, "match_date" => $match_date);
				
			}
		
		}
		
		
		foreach($_m as $key => $val){
			
			$_parent = $val->parent;
			
			$_marketArray[$key] = array("market_name" => $val->desc, "match_id" => $_parent, "market_desc" => $val->desc .' @ '. $val->period); 
		
		}

		foreach($_o as $key => $val){
			
			if( isset($val->parent)){
			
				$_parent = $val->parent;
				
				$_marketArray[$_parent]["odds_detail"][$key] = array("team_name" => $val->desc, "team_price" => $val->price);
			
			}
		}
		
		truncate_sport($sportReq);
		
		save_match($_matchArray);
		
		save_odds($_marketArray);
	
	}
	
}
