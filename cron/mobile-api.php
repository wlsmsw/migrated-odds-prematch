<?


require_once('curl.init.php');

class mobileAPI extends reqAPI{
	
	var $restURL;
	var $mmURL;
	var $debug;
	
	const TIMEZONE_PLUS = '+8 hours';

	function __construct(){
		// $this->restURL = 'https://122.52.178.144:8443/abp/m';
		// $this->restURL = 'https://172.19.32.138/m';
		// $this->restURL = 'https://slipstream.msw.ph/m';
		//$this->restURL = 'https://msw.ph/m';
		//$this->restURL = 'http://121.97.82.18:6060/m';
		//$this->restURL = 'https://tvshow.msw.ph/m';
		//$this->restURL = 'https://bo.lvsmsw.prd/abp/m';
		//$this->restURL = 'https://slipstream-megasportsworld-stg.elysium-dfbt.com/xapi/m';
		$this->restURL = 'https://slipstream.msw.ph/xapi/m';
		$this->debug = false;
	}
	
	
	function getSports(){
	 	$url = $this->restURL . '/ept?lineId=2&originId=3';
		$_sports = $this->allSports();
				
		$this->url($url);
		$this->m_debug();
		if($this->status){
			 
			$list = array();
			$json = json_decode($this->result);
			
			foreach($json->ept as $cont => $val){
				$list[] = array(
					'id' =>	$_sports[$val->code]['id'],
					'name' =>	$val->desc,
					'sportCode' =>	$val->code
				);
			}
			echo json_encode($list,JSON_PRETTY_PRINT);
			return $list;
		}
	}
	function getEvents($sportCode){
		//$url = $this->restURL . '/ff/sports/'.$sportCode;
		$url = $this->restURL . '/ff/p'.$sportCode.'?lineId=2&originId=3';
		$this->url($url);
		
		$this->m_debug();
		if($this->status){
			
			$list = array();
			$json = json_decode($this->result);
			
			$items = $json->items;
			$_p = array();
			foreach($items as $key => $val){
				if( isset($val->parent) && ($val->parent != 'p0') ){
					$_parent = $val->parent;
					$pos = strpos($key, 'p');
					$_expCountry = array("Country","Category");
					if ( $pos !== false && !in_array($val->tag ,$_expCountry)){
						$_p[$key] = $val;
					}
				}
			}
			
			if(!empty($_p)){
				foreach($_p as $key => $val){
					if(isset($val->path->Country)){
						$list[] = array(
							'id'=> $key,
							'league'=> $val->desc,
							'country'=> $val->path->Country,
						);
					}elseif(isset($val->path->Category)){
						$list[] = array(
							'id'=> $key,
							'league'=> $val->desc,
							'country'=> $val->path->Category,
						);
					}else{
						$list[] = array(
							'id'=> $key,
							'league'=> $val->desc,
							'country'=> '',
						);
					}
				}
			}
			echo json_encode($list,JSON_PRETTY_PRINT);
			return $list;
		}
	}
	function getMatches($pathID){
		//$_cleanPath = preg_replace("/[^0-9,.]/", "", $pathID);
		//$pathID = $_cleanPath;
		
		$url = $this->restURL . '/ff/'.$pathID.'?lineId=2&originId=3';
		$this->url($url);
		$this->m_debug();
		if($this->status){
			$list = array();
			$json = json_decode($this->result);
		
		
			$items = $json->items;
			$_p = array();
			foreach($items as $key => $val){
				if( isset($val->parent) && ($val->parent == $pathID) ){
					$_parent = $val->parent;
					$pos = strpos($key, 'e');
					if ( $pos !== false ){
						$_p[$key] = $val;
					}
				}
			}
			
			if(!empty($_p)){
				foreach($_p as $key => $val){
					$eventDate = (string)$val->start;

					$smp = $eventDate;
					$_mY = substr($smp,0,2);
					$_mM = substr($smp,2,2);
					$_mD = substr($smp,4,2);
					$_mH = substr($smp,6,2);
					$_mMn = substr($smp,8,2);

					$match_date = date('Y-m-d H:i',mktime($_mH, $_mMn, 0, $_mM, $_mD, $_mY));
					/* $match_date_x = strtotime($match_date . '+8hours');
					$match_date = date('M-d-Y h:iA',$match_date_x);
					 */
					
					$emp_date = (string)$match_date;
					$list[] = array(
						'id' => $key,
						'match' => $val->desc,
						'date' => date('Y-m-d H:i', strtotime($emp_date . constant('sport::'.TIMEZONE_PLUS))),
						'date_est' => date('Y-m-d H:i', $eventDate)
					);
				}
			}
		
			echo json_encode($list,JSON_PRETTY_PRINT);
			return $list;
		}
	}
	function getMarkets($pathID){
		$url = $this->restURL . '/ff/'.$pathID .'?ext=1&lineId=2&originId=3';
		$this->url($url);
		$this->m_debug();
		if($this->status){
			$list = array();
			$json = json_decode($this->result);
			
			
			
			$items = $json->items;
			$_p = array();
			foreach($items as $key => $val){
				if( isset($val->parent) && ($val->parent == $pathID) ){
					$_parent = $val->parent;
					$pos = strpos($key, 'm');
					if ( $pos !== false ){
						$_p[$key] = $val;
					}
				}
			}
			
			if(!empty($_p)){
				foreach($_p as $key => $val){
					$list[] = array(
						'id' => $key,
						'description' => $val->desc,
						'market' => $val->period
					);
				}
			}
		
		
			echo json_encode($list,JSON_PRETTY_PRINT);
			return $list;
		}
	}
	function getOdds($pathID){
		$url = $this->restURL . '/ff/'.$pathID.'?lineId=2&originId=3';
		$this->url($url);
		$this->m_debug();
		if($this->status){
			$list = array();
			$json = json_decode($this->result);
			
			$items = $json->items;
			$_p = array();
			foreach($items as $key => $val){
				if( isset($val->parent) && ($val->parent == $pathID) ){
					$_parent = $val->parent;
					$pos = strpos($key, 'o');
					if ( $pos !== false ){
						$_p[$key] = $val;
					}
				}
			}
			
			if(!empty($_p)){
				foreach($_p as $key => $val){
					$list[] = array(
						'id' => $key,
						'odds' => $val->desc,
						'price' => $val->price
					);
				}
			}
			
			echo json_encode($list,JSON_PRETTY_PRINT);
			return $list;
		}
	}

	function cronOdds($pathID){
		$url = $this->restURL . '/ff/'.$pathID.'?lineId=2&originId=3';
		$this->url($url);
		$this->m_debug();
		
		if($this->status){
			$list = array();
			$json = json_decode($this->result);
			
			$items = $json->items;
			$_p = array();
			foreach($items as $key => $val){
				if( isset($val->parent)){
					$_parent = $val->parent;
					$pos = strpos($key, 'o');
					if ( $pos !== false ){
						$_p[$_parent][] = $val;
					}
				}
			}
			
			/* $_p['toBasket'] = $json->toBasket; */
		
			echo json_encode($_p,JSON_PRETTY_PRINT);
			return $list;
		}
	}
	
	
	function m_debug(){
		if($this->debug == true){
			echo '<pre>'.$this->url . "\n\n";
			
			echo "Status : ";
			
			$json = json_decode($this->result);
			print_r($json);
			
			//$this->info();
			
			echo "\n";
		}
	}

	function complete(){
		$url = $this->restURL . '/ept?lineId=2&originId=3';
		$this->url($url);
		$json = json_decode($this->result);
		echo json_encode($json->ept,128);
	}
	
	function mxodds($pathID){
	
		$url = $this->restURL . '/ff/p'.$pathID.'?lineId=2&originId=3';
		$this->url($url);
		$this->m_debug();
		
		if($this->status){
			$list = array();
			$json = json_decode($this->result);
			$items = $json->items;
			
			
			$_np = $_c = $_p = $_e = $_m = $_o = array();
			foreach($items as $key => $val){
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
			
			
			foreach($_e as $key => $val){
				 if( isset($val->parent)){
					$_parent = $val->parent;
					
					
					$eventDate = (string)$val->start;

					$smp = $eventDate;
					$_mY = substr($smp,0,2);
					$_mM = substr($smp,2,2);
					$_mD = substr($smp,4,2);
					$_mH = substr($smp,6,2);
					$_mMn = substr($smp,8,2);

					$match_date = date('Y-m-d H:i',mktime($_mH + 8, $_mMn, 0, $_mM, $_mD, $_mY));
					
					$_e[$key]->matchdate = $match_date;
					
					
					$_e[$key]->items = '';
					
				} 
			}
	
			foreach($_m as $key => $val){

				if( isset($val->parent)){
					$_parent = $val->parent;
					$_e[$_parent]->items[$key] = $val;
					
				}
			}

			foreach($_o as $key => $val){

				if( isset($val->parent)){
					$_parent = $val->parent;
					$_m[$_parent]->items[$key] = $val;
					
				}
			}
	
		header( 'Access-Control-Allow-Origin: *');
		header( 'Access-Control-Allow-Credentials: true' );


			foreach($_e as $key => $val){
				?>
			
					<li id="<?=$key;?>" class="market-info" sub_event_date="<?=$val->matchdate;?>" ename="<?=$val->desc;?>">
						<span class="sub-event-name" sub_event_id="<?=$key;?>"><?=$val->desc;?></span>
						
						<ul class="market">
						<? foreach($val->items as $mkey => $mval){ ?>
							<li class="market-odds" id="<?=$mkey;?>" ename="<?=$mval->desc;?>">
							<span class="market-title" sub_event_id="<?=$mkey;?>"><?=$mval->desc;?></span>
							<span class="market-date" sub_event_id="<?=$mkey;?>"><?=$val->matchdate;?></span>
							
							<div class="odds-selection">
							<? foreach($mval->items as $okey => $oval){ ?>
								<span odds_id="<?=$okey;?>" class="odds-price" ><?=$oval->price;?></span>
								<span id="<?=$okey;?>" class="odds-team" ename="<?=$oval->desc;?>"> <?=$oval->desc;?></span>
							<? } ?>
							</div>
							
							</li>
							
						
						<? } ?>
						</ul>
					</li>
				<?
			}
			
			// var_dump($_e);
		}
	}
	
	
	function mxmarket($pathID){
	
		$url = $this->restURL . '/ff/'.$pathID . '?ext=1&lineId=2&originId=3';
		$this->url($url);
		$this->m_debug();
		if($this->status){
			$list = array();
			$json = json_decode($this->result);
			$items = $json->items;
			
			$_e = $_m = $_o = array();
			foreach($items as $key => $val){
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
			
			
			foreach($_e as $key => $val){
				 if( isset($val->parent)){
					$_parent = $val->parent;
					
					
					$eventDate = (string)$val->start;

					$smp = $eventDate;
					$_mY = substr($smp,0,2);
					$_mM = substr($smp,2,2);
					$_mD = substr($smp,4,2);
					$_mH = substr($smp,6,2);
					$_mMn = substr($smp,8,2);

					$match_date = date('Y-m-d H:i',mktime($_mH, $_mMn, 0, $_mM, $_mD, $_mY));
					
					$_e[$key]->matchdate = $match_date;
					
					$_e[$key]->items = '';
					
				} 
			}
	
			foreach($_m as $key => $val){

				if( isset($val->parent)){
					$_parent = $val->parent;
					$_e[$_parent]->items[$key] = $val;
					
				}
			}

			foreach($_o as $key => $val){

				if( isset($val->parent)){
					$_parent = $val->parent;
					$_m[$_parent]->items[$key] = $val;
					
				}
			}
			
			
				
			header( 'Access-Control-Allow-Origin: *');
			header( 'Access-Control-Allow-Credentials: true' );
			
			
			foreach($_e as $key => $val){
				?>
						<ul class="market">
						<? foreach($val->items as $mkey => $mval){ ?>
							<li class="market-odds" id="<?=$mkey;?>" ename="<?=$mval->desc;?>">
							<span class="market-title" sub_event_id="<?=$mkey;?>"><?=$mval->desc;?> @ <?=$mval->period;?></span>
							<span class="market-date" sub_event_id="<?=$mkey;?>"><?=$val->matchdate;?></span>
							
							
							
							<div class="odds-selection">
							<? foreach($mval->items as $okey => $oval){ ?>
								<span odds_id="<?=$okey;?>" class="odds-price" ><?=$oval->price;?></span>
								<span id="<?=$okey;?>" class="odds-team" ename="<?=$oval->desc;?>"> <?=$oval->desc;?></span>
							<? } ?>
							</div>
							
							</li>
							
						
						<? } ?>
						</ul>
				<?
			}
			
		}
		//e25937451
		
		
	}
	
	function country(){
		//$this->url('https://p8.8-bucks.co.uk:8443/p8/rest/marketTypes?code=BASK');
		//print_r($this->result[0]);
		$test = json_decode($this->result);
		
		//print_r($test[1]);
		//print_r($test);
	}

	function info(){
	 	if($this->status){
			echo $this->result;
		}else{
			print_r($this->error);
		}
	}
	
	}
