<?
ini_set('memory_limit', '128M');
require_once('mobile-api.php');

function func_xml($sportReq){

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

	$_constantTime = 'T00:00:00+08:00';
	$_startDate = date('Y-m-d');
	$_endDate = date('Y-m-d',strtotime($_startDate . '+8 day'));

	for ($date = strtotime($_startDate. '+1 day'); $date < strtotime($_endDate); $date = strtotime("+1 day", $date)) {
		
		//$url = $API->restURL.'/ff/'.$_reqSport.'?fromDate='.$_startDate.'T00:00:00+08:00&toDate='.date("Y-m-d", $date).'T00:00:00+08:00&lineId=12&originId=31';
		//$url = $API->restURL.'/ff/'.$_reqSport.'?fromDate='.$_startDate.'T00:00:00+08:00&toDate='.date("Y-m-d", $date).'T00:00:00+08:00';
		
		if($sportReq == 'MMA/Boxing' || $sportReq == 'Boxing/MMA' || $sportReq == 'Formula1') {
		    $url = $API->restURL.'/ff/'.$_reqSport.'?fromDate='.$_startDate.'T00:00:00+08:00';
		} else {
		    $url = $API->restURL.'/ff/'.$_reqSport.'?fromDate='.$_startDate.'T00:00:00+08:00&toDate='.date("Y-m-d", $date).'T00:00:00+08:00';
		}
		
		$API->url($url);
		
		$_results = json_decode($API->result);
		
		if (!is_object($_results->items)) {
			exit();
		}

		$array = get_object_vars($_results->items);
		if(empty($array)){
			exit();
		}

		$_ids = array_keys($array);

		$API->m_debug();
		
		if($API->status){

			$jsonE = json_decode($API->result);

			if(!empty($_ids)){
			
				if($_ids[0] != preg_replace("/[^0-9,.]/", "", $_reqSport)){

					// $url = 'https://202.151.35.222/m/ff/'.$_reqSport.'?fromDate='.$_startDate.'T00:00:00+08:00&toDate='.date("Y-m-d", $date).'T00:00:00+08:00';

					//$url =  $API->restURL.'/ff/'.$_reqSport.'?fromDate='.$_startDate.'T00:00:00+08:00&toDate='.date("Y-m-d", $date).'T00:00:00+08:00&lineId=12&originId=31';
		
		            if($sportReq == 'MMA/Boxing' || $sportReq == 'Boxing/MMA' || $sportReq == 'Formula1') {
		                $url = $API->restURL.'/ff/'.$_reqSport.'?fromDate='.$_startDate.'T00:00:00+08:00';
		            } else {
					    $url = $API->restURL.'/ff/'.$_reqSport.'?fromDate='.$_startDate.'T00:00:00+08:00&toDate='.date("Y-m-d", $date).'T00:00:00+08:00';
		            }
					
					$API->url($url);

					$API->m_debug();

					if($API->status){

						$jsonE = json_decode($API->result);

						if(!empty($_ids)){
						
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
								
								//$url = $API->restURL . '/ff/'.$_impEvents .'?ext=1&lineId=12&originId=31';
								
								$url = $API->restURL.'/ff/'.$_impEvents;
								
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
								
								// print_r($val);
								
								// echo '</pre>';
							
								$_matchArray[$val->desc] = array("country_id" => $key, "sport" => $sportReq);
							
							}
							
							foreach($_p as $key => $val){
							
							
								if( isset($val->path->Category)){
								
									$_parent = $val->path->Category;
							
									$_matchArray[$_parent][$val->desc] = array("league_id" => $key);
								
								}
								else if(isset($val->path->{'Country'})){
								
									$_parent = $val->path->{'Country'};
							
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
										$_matchArray[$_parent][$_subparent][$val->desc] = array("match_id" => $key, "match_date" => $match_date);
								
								}
								else if(isset($val->path->{'Country'})){
								
									$_parent = $val->path->{'Country'};
									
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
							
							// truncate_sport($sportReq);

							// echo $url;
							
							save_match($_matchArray,'match_list');
			
							save_odds($_marketArray,'market_odds_list');
									
							exit;
						
						}
						

						
					}
				
				}
				
			}
		
		}
		
	}
}

function all_func_xml($sportReq){

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

	//$url = $API->restURL . '/ff/'.$_reqSport.'?lineId=12&originId=31';
	
	$url = $API->restURL.'/ff/'.$_reqSport;

	$API->url($url);
		
	$_results = json_decode($API->result);
	
	if (is_object($_results->items)) {
		$array = get_object_vars($_results->items);
		$_ids = array_keys($array);

		$API->m_debug();

		if($API->status){

			if (is_string($API->result)) {
				$jsonE = json_decode($API->result);

				if(!empty($_ids)){
				
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
						
						//$url = $API->restURL . '/ff/'.$_impEvents .'?ext=1&lineId=12&originId=31';
						
						$url = $API->restURL.'/ff/'.$_impEvents;
						
						$API->url($url);
						
						if (is_string($API->result)) {
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
						else if(isset($val->path->{'Country'})){
						
							$_parent = $val->path->{'Country'};
					
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
								$_matchArray[$_parent][$_subparent][$val->desc] = array("match_id" => $key, "match_date" => $match_date);
						
						}
						else if(isset($val->path->{'Country'})){
						
							$_parent = $val->path->{'Country'};
							
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
					
					// truncate_sport($sportReq);
					
					save_match($_matchArray,'all_match_list');
					
					save_odds($_marketArray,'all_market_odds_list');
				
				}
			}
		}
	}
}