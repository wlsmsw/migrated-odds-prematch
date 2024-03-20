<?

class reqAPI{
	var $ch,$url,$result,$status,$token,$error = array();
	
	function __construct(){
		
	}
	
	
	function reqToken(){
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$this->restURL );
		
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false); 
	
		curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
	
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		
		

		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_NOBODY,  false);


		$this->result=curl_exec($ch);
		
	 if(curl_exec($ch) === false)
		{
			echo 'Curl error: ' . curl_error($ch);
		}
		else
		{
			echo 'Operation completed without any errors';
		} 
		
	  //print_r($this->result);
		// print_r(curl_getinfo($ch));
		
		$_res=explode("\n", str_replace("\r","\n",$this->result));

		foreach($_res as $line) {
			if(stristr($line,'X-LVS-HSToken')){
				$_tokenAccess = str_replace('X-LVS-HSToken: ','',$line);
				//setcookie("authToken",$_tokenAccess);
				//echo 'xxxxxxxxxxx='. $_tokenAccess;
				break;
			}
		}
		
		$filename="token.txt";
		//$file = file_get_contents($filename);
		file_put_contents($filename,$_tokenAccess);
		$this->token =$_tokenAccess;
		return $_tokenAccess;
	}
	function getToken(){
		$filename="token.txt";
		$file = file_get_contents($filename);
		$this->token = $file;
		return $file;
	}
	

	function url($url='',$method = 'GET',$_login=false){
	
		timer_start();
		$this->url = !empty($url) ? $url : $this->url;
		
		//$this->url = 'https://bo.lvsmsw.prd/abp/rest/marketTypes?code=BASK';
		
		// for debugging
		//echo "curl init the URL: " . $this->url;
		//echo "<br />";

		$this->ch = curl_init();
		curl_setopt($this->ch, CURLOPT_URL,$this->url);
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
		//$_login=true; 
		if($_login == true || isset($_GET['token'])){
			/* curl_setopt($this->ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
			curl_setopt($this->ch, CURLOPT_USERPWD, "lvs-vps-odds:pass123$$");
			curl_setopt($this->ch, CURLOPT_HTTPHEADER,array('Content-Type: application/json')); */
			$this->reqToken();
			echo 'success token : ';
			exit;
		}
		
		$this->getToken();
		
		curl_setopt($this->ch, CURLOPT_HTTPHEADER,array(
			'Content-type: application/json',
			'X-LVS-HSToken: '. $this->token
		));
		 
		// curl_setopt($this->ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT ,0); 
		curl_setopt($this->ch, CURLOPT_TIMEOUT, 400); //timeout in seconds
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER,1);

		curl_setopt($this->ch, CURLOPT_HEADER, 0);
		curl_setopt($this->ch, CURLOPT_NOBODY,  false);
		curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST ,$method);
		
		$this->result=curl_exec($this->ch);
		
		//for debugging
		/*echo "and the result is: ";
		echo '<br/>';
		echo '<pre>';
		print_r (json_decode($this->result));
		echo '</pre>';
		echo "<br />";
		exit;*/
		
		$this->status = $this->status();
	}
	
	protected function status(){
	
		$stat = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
		if($stat == 200 || $stat == 1){
			return true;
		}else{
			$this->error = array_merge(array('status'=> $stat));
			return false;
		}
	}
	
	public function allSports(){
		$_sportsAll = '[ {
			  "code" : "FOOT",
			  "description" : "Football",
			  "defaultEventPathId" : 240
			}, {
			  "code" : "FOOT",
			  "description" : "Soccer",
			  "defaultEventPathId" : 240
			}, {
			  "code" : "HORS",
			  "description" : "Horse Racing",
			  "defaultEventPathId" : 364
			}, {
			  "code" : "MOSP",
			  "description" : "Motor Sports",
			  "defaultEventPathId" : 22881
			}, {
			  "code" : "USHO",
			  "description" : "US Horse Racing",
			  "defaultEventPathId" : 365
			}, {
			  "code" : "USGR",
			  "description" : "US Greyhound Racing",
			  "defaultEventPathId" : 366
			}, {
			  "code" : "GOLF",
			  "description" : "Golf",
			  "defaultEventPathId" : 237
			}, {
			  "code" : "TENN",
			  "description" : "Tennis",
			  "defaultEventPathId" : 239
			}, {
			  "code" : "AMFB",
			  "description" : "American Football",
			  "defaultEventPathId" : 1
			}, {
			  "code" : "CRIC",
			  "description" : "Cricket",
			  "defaultEventPathId" : 215
			}, {
			  "code" : "MISC",
			  "description" : "Misc",
			  "defaultEventPathId" : 22883
			}, {
			  "code" : "RUGL",
			  "description" : "Rugby League",
			  "defaultEventPathId" : 22878
			}, {
			  "code" : "SNOO",
			  "description" : "Snooker",
			  "defaultEventPathId" : 22884
			}, {
			  "code" : "BOXI",
			  "description" : "Boxing",
			  "defaultEventPathId" : 238
			}, {
			  "code" : "BOXI",
			  "description" : "MMA/Boxing",
			  "defaultEventPathId" : 238
			}, {
			  "code" : "BOXI",
			  "description" : "Boxing/MMA",
			  "defaultEventPathId" : 238
			}, {
			  "code" : "BASE",
			  "description" : "Baseball",
			  "defaultEventPathId" : 226
			}, {
			  "code" : "GREY",
			  "description" : "Greyhound Racing",
			  "defaultEventPathId" : 1000
			}, {
			  "code" : "BASK",
			  "description" : "Basketball",
			  "defaultEventPathId" : 227
			}, {
			  "code" : "RUGU",
			  "description" : "Rugby Union",
			  "defaultEventPathId" : 22877
			}, {
			  "code" : "ATHL",
			  "description" : "Athletics",
			  "defaultEventPathId" : 22885
			}, {
			  "code" : "IHUS",
			  "description" : "Ice Hockey - US",
			  "defaultEventPathId" : 228
			}, {
			  "code" : "DART",
			  "description" : "Darts",
			  "defaultEventPathId" : 22886
			}, {
			  "code" : "POKE",
			  "description" : "Poker",
			  "defaultEventPathId" : 22887
			}, {
			  "code" : "POLI",
			  "description" : "Politics",
			  "defaultEventPathId" : 22888
			}, {
			  "code" : "ENTE",
			  "description" : "Entertainment",
			  "defaultEventPathId" : 22889
			}, {
			  "code" : "SPEE",
			  "description" : "Speedway",
			  "defaultEventPathId" : 22890
			}, {
			  "code" : "CAFB",
			  "description" : "Canadian Football",
			  "defaultEventPathId" : 231
			}, {
			  "code" : "YACH",
			  "description" : "Yachting",
			  "defaultEventPathId" : 900
			}, {
			  "code" : "FORM",
			  "description" : "Formula1",
			  "defaultEventPathId" : 1300
			}, {
			  "code" : "HAND",
			  "description" : "Handball",
			  "defaultEventPathId" : 1100
			}, {
			  "code" : "BEVO",
			  "description" : "Beach Volleyball",
			  "defaultEventPathId" : 1250
			}, {
			  "code" : "VOLL",
			  "description" : "Volleyball",
			  "defaultEventPathId" : 1200
			}, {
			  "code" : "WATE",
			  "description" : "Water Polo",
			  "defaultEventPathId" : 1400
			}, {
			  "code" : "BAND",
			  "description" : "Bandy",
			  "defaultEventPathId" : 1700
			}, {
			  "code" : "CHES",
			  "description" : "Chess",
			  "defaultEventPathId" : 1750
			}, {
			  "code" : "BIAT",
			  "description" : "Biathlon",
			  "defaultEventPathId" : 1800
			}, {
			  "code" : "FUTS",
			  "description" : "Futsal",
			  "defaultEventPathId" : 1600
			}, {
			  "code" : "BEAC",
			  "description" : "Beach Soccer",
			  "defaultEventPathId" : 1500
			}, {
			  "code" : "TABL",
			  "description" : "Table Tennis",
			  "defaultEventPathId" : 1900
			}, {
			  "code" : "LOTT",
			  "description" : "Lottery",
			  "defaultEventPathId" : 2000
			}, {
			  "code" : "ICEH",
			  "description" : "Ice Hockey",
			  "defaultEventPathId" : 2100
			}, {
			  "code" : "MOTO",
			  "description" : "MotoGP",
			  "defaultEventPathId" : 2200
			}, {
			  "code" : "RALL",
			  "description" : "Rally WRC",
			  "defaultEventPathId" : 2300
			}, {
			  "code" : "SWIM",
			  "description" : "Swimming",
			  "defaultEventPathId" : 2400
			}, {
			  "code" : "RUG7",
			  "description" : "Rugby 7s",
			  "defaultEventPathId" : 2500
			}, {
			  "code" : "CURL",
			  "description" : "Curling",
			  "defaultEventPathId" : 3300
			}, {
			  "code" : "BOWL",
			  "description" : "Bowls",
			  "defaultEventPathId" : 3400
			}, {
			  "code" : "FIEL",
			  "description" : "Field Hockey",
			  "defaultEventPathId" : 3500
			}, {
			  "code" : "BADM",
			  "description" : "Badminton",
			  "defaultEventPathId" : 5000
			}, {
			  "code" : "MOCY",
			  "description" : "Motorcycling",
			  "defaultEventPathId" : 2600
			}, {
			  "code" : "CYCL",
			  "description" : "Cycling",
			  "defaultEventPathId" : 2700
			}, {
			  "code" : "NINE",
			  "description" : "Nine-Ball Pool",
			  "defaultEventPathId" : 2800
			}, {
			  "code" : "WINT",
			  "description" : "Winter Sports",
			  "defaultEventPathId" : 2900
			}, {
			  "code" : "GAEL",
			  "description" : "Gaelic Games",
			  "defaultEventPathId" : 3000
			}, {
			  "code" : "OLYM",
			  "description" : "Olympic Games",
			  "defaultEventPathId" : 3100
			}, {
			  "code" : "FBSP",
			  "description" : "Football Specials",
			  "defaultEventPathId" : 3200
			}, {
			  "code" : "AURL",
			  "description" : "Aussie Rules",
			  "defaultEventPathId" : 3700
			}, {
			  "code" : "FLOO",
			  "description" : "Floorball",
			  "defaultEventPathId" : 3600
			}, {
			  "code" : "SKII",
			  "description" : "Skiing",
			  "defaultEventPathId" : 3800
			} ]';
			
			
			$json = json_decode($_sportsAll);
			
			$_sports = array();
			foreach($json as $key => $val){
				$_sports[$val->code] = array(
					'id' => $val->defaultEventPathId,
					'desc' => $val->description,
					'code' => $val->code
				);
			}
			return $_sports;
	}
	
}


function timer_start() {
	global $timestart;
	$timestart = microtime( true );
		return true;
}

function timer_stop( $display = 0, $precision = 3 ) {
	global $timestart, $timeend;
	$timeend = microtime( true );
	$timetotal = $timeend - $timestart;
	$r =  number_format( $timetotal, $precision );
	if ( $display )
		echo $r;
	return $r;
}
