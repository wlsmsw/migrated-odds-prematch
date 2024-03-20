<?php
error_reporting(-1);

include_once("includes/functions.php");
//die('yeah');

if(!isset($_GET['sport'])){

	echo "<li>Please specify a sport</li>";

	exit;

}else{

	$sport = filter_string($_GET['sport']);

	switch($sport){

		case 'Rugby':
			$sport_alias = 'Rugby';
			$sport_alias_name = 'Rugby';
			$sport = 'RugbyUnion';
			break;

		case 'MMA/Boxing':
			$sport_alias = 'Boxing';
			$sport_alias_name = 'MMA/Boxing';
			$sport = 'MMA/Boxing';
			break;

		case 'Boxing/MMA':
			$sport_alias = 'Boxing';
			$sport_alias_name = 'Boxing/MMA';
			$sport = 'Boxing/MMA';
			break;

		case 'Soccer':
			$sport_alias = 'Soccer';
			$sport_alias_name = 'Football';
			$sport = 'Soccer';
			break;

		case 'AmericanFootball':
			$sport_alias = 'AmericanFootball';
			$sport_alias_name = 'American Football';
			$sport = 'AmericanFootball';
			break;

		case 'IceHockey':
			$sport_alias = 'IceHockey';
			$sport_alias_name = 'Ice Hockey';
			$sport = 'IceHockey';
			break;

		default:
			$sport_alias = $sport;
			$sport_alias_name = $sport;
			break;
	}

}


if(!isset($_GET['p']))
	$_except = '';

else
	$_except = filter_string($_GET['p']);
	
	
$sportActive = get_active_sports();

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="refresh" content="1800">

    <meta name="description" content="">
    <meta name="author" content="">
    <!--<link rel="icon" href="favicon.ico"> -->
	
	<?
	if(isset($_GET['generic'])){
	?>
    <title>Generic Odds</title>
	<?
	}else{
	?>
	<title>MSW Odds</title>
    <?}?>
	
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script type="text/javascript" src="includes/js/jquery.easy-ticker.js"></script>
    <script type="text/javascript" src="includes/js/jquery.easing.min.js"></script>
	<script type="text/javascript" src="includes/js/jquery.jclock.js"></script>
	<!--<script type="text/javascript" src="includes/js/jquery.smarticker.min.js"></script>!-->
	<script type="text/javascript" src="includes/js/function.js"></script>
	<script type="text/javascript" src="includes/js/front-end.js"></script>
	<script type="text/javascript" src="includes/js/jquery.ticker.js"></script>
	<link rel="stylesheet" type="text/css" href="includes/css/jquery.smarticker.min.css" />
    <link href="includes/css/style.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="includes/js/html5shiv.min.js"></script>
      <script src="includes/js/respond.min.js"></script>
    <![endif]-->
	<style>
		@font-face {
			font-family: 'MyriadPro BoldCond';
			src: url('includes/css/fonts/MyriadPro-BoldCond.otf'); /* IE9 Compat Modes */
			src: url('includes/css/fonts/MyriadPro-BoldCond.otf?#iefix') format('embedded-opentype'), /* IE6-IE8 */
				 url('includes/css/fonts/MyriadPro-BoldCond.otf') format('otf'), /* Modern Browsers */
				 url('includes/css/fonts/MyriadPro-BoldCond.otf')  format('truetype'), /* Safari, Android, iOS */
		}


		@font-face {
		  font-family: 'Roboto';
		  font-style: normal;
		  font-weight: 400;
		  src: url('includes/css/fonts/roboto.woff') format('woff');
		}

		@font-face {
		  font-family: 'Roboto Light';
		  font-style: normal;
		  font-weight: 300;
		  src: url('includes/css/fonts/roboto-light.woff') format('woff');
		}

		@font-face {
		  font-family: 'Roboto Bold';
		  font-style: normal;
		  font-weight: 700;
		  src: url('includes/css/fonts/roboto-bold.woff') format('woff');
		}
		.tickercontainer { /* the outer div with the black border */

			border: 0px solid #000;

			background: transparent;

			height: 50px;

			overflow: hidden;

			padding-top: 10px;

		}
		.tickercontainer .mask { /* that serves as a mask. so you get a sort of padding both left and right */

			position: relative;

			left: 10px;

			top: 8px;

			overflow: hidden;

			}

			ul.newsticker { /* that's your list */

			position: relative;

			left: 50px;

			list-style-type: none;

			overflow:hidden;

			}

			ul.newsticker li {

			/* important: display inline gives incorrect results when you check for elem's width */

			font-family:Verdana , Arial,Tahoma,'MyriadPro BoldCond';

			font-size: 36px;

			font-weight:bold;

			color:#262525;

			padding-left: 80px;padding-right: 50px;

			display: block;

			float: left;

			}



			ul.newsticker li b,

			ul.newsticker li strong{color:#ed0000;}



			ul.newsticker span {

			margin: 0 10px 0 0;

			}

			ul.newsticker{top: 7px;}

			.foot-ticker{
				position:absolute;
				bottom: 0px;
				width:100%;
				height: 62px;
				background-color:#bbb;
				background: transparent;
			}
			/* .foot-container{background:transparent url(includes/images/news-bg.png) repeat-x top left ;position:relative;border:0px solid #f00;height:100px;}
			#news-ticker{margin: 0px 232px 10px 427px;border: 0px solid #0f0;height:62px;display:block;}
			#news-ticker #news-ticker-list li{font-family: "Roboto";text-transform:uppercase;color:#fff;font-size: 38px}

			#clock-ticker{z-index:1;position:absolute;top:0;right:0;border: 0px solid #0f0;height:55px;
				background: transparent url(includes/images/clock-bg.png) no-repeat top right;
				padding: 5px 0px 0px 31px;text-transform:uppercase;
			}
			.clock-digit,.clock-date{font-family: "Roboto Bold"; font-size: 39px;color:#111;margin-top: 5px;}

			.clock-date{width:220px;display:block;text-align:left;}
			.clock-digit{width:220px;display:block;text-align:right; float:right; } */

			.foot-container{background:transparent url(includes/images/news-bg.png) repeat-x top left ;position:relative;border:0px solid #f00;height:100px;}
			#news-ticker{margin: 0px 232px 10px 427px;border: 0px solid #0f0;height:62px;display:block;}
			#news-ticker #news-ticker-list li{font-family: "Roboto";text-transform:uppercase;color:#fff;font-size: 38px}
			#clock-ticker{z-index:1;position:absolute;top:0;right:0;border: 0px solid #0f0;height:55px;
				background: transparent url(includes/images/clock-bg.png) no-repeat top left;
				padding: 5px 0px 0px 31px;text-transform:uppercase;
			}
			.clock-digit,.clock-date{font-family: "Roboto Bold"; font-size: 39px;color:#111;margin-top: 16px;}

			.clock-date{width:220px;display:block;text-align:left;}
			.clock-digit{width:220px;display:block;text-align:left;}


			.foot-ticker-msw{
				position:absolute;
				top: 0;
				left:0px;
				width:349px;
				height: 66px;
				z-index:1;
				background: transparent url(includes/images/msw-ticker-logo-new.png) no-repeat top left;
				text-align:left!important;

			}

			.smarticker5{height:53px;float: left; border-top: 4px solid #222;border-bottom: 4px solid #222;}


     @media screen and (max-width: 1918px) {
        .foot-ticker-msw {
          width: 311px;
          overflow: hidden;
        }

        .theme2 .smarticker-cats, .smarticker-category.sec1-2 {
          background: #111;
        }

        .smarticker-category.sec1-2 {
          margin-left: -110px;
        }

        .foot-ticker-msw {
              background: transparent url(includes/images/msw-ticker-logo-mini.png) no-repeat top left;
        }

        .foot-ticker-msw img {
          display: none;
        }

      }
	  .ticker-wrapper{
		margin-left: 19%;
    font-size: 22px;
    margin-top: -3px;

	  }
	</style>

  </head>

  <body>

    <!-- Header -->
    <div class="header">
	  <div class="header-container">
	    <div class="header-main">
	        <div class="logo">
	            <img src="includes/images/msw-logo.png">
	        </div>
	        
	        <div class="date">
                <span><?=date('m/d/Y')?></span>
                <div id="clock-ticker">	<span class="clock-digit"> 00:00:00 PM</span>
					<span class="clock-date" style="display: none;">SUN,Oct 24</span>
				</div>	
	        </div>
	    </div>
	    
        <div class="header-inner head-<?=$sport_alias;?>">
            <div class="title">
                <?php if($sport == 'MMA/Boxing' || $sport == 'Boxing/MMA' || $sport == 'Formula1') : ?>
                    <span>PRE-MATCH</span> ODDS
                <?php else : ?>
                    <span>PRE-MATCH</span> ODDS TODAY
                <?php endif; ?>
            </div>
            <div class="sport-label">
            	<i class="icon <?=$sport_alias;?>"></i><?=$sport_alias_name;?>
            </div>
        </div>
	  </div>
	</div>

    <div class="odds-wrapper">
      <div class="market-info" id="market-info">
		<div id="no-match" style="visibility:hidden;">CURRENTLY NO AVAILABLE MARKET</div>
		<div class="market-container vticker" id="market-container">
          <ul class="market">
		  <?

			echo display_data($sport,$_except);

		   ?>
			</ul>
        </div>

      </div>

    </div>


  </body>

  <script>


	$(document).ready(function(){

		if ($('.market li ul').length == 0){

			$(".market .list-batch").remove();

			$("#no-match").css("visibility", "visible");
		}

		function load() {

			var dd = $('.vticker').easyTicker({
				  direction: 'up',
				  easing: 'swing',
				  speed: 'slow',
				  interval: 12000,
				  height: 'auto',
				  visible: 1,
				  mousePause: 0,
				  controls: {
					up: '.up',
					down: '.down',
					toggle: '.toggle',
					stopText: 'Stop !!!'
				  }
				}).data('easyTicker');

			cc = 1;

			dd.stop();
			dd.options['visible'] = 0 ;
			dd.start();

			if ($('.market-odds').length <= 5 && $('.league-title').length <= 9){

				dd.stop();

			}

			if ($('.market li ul').length == 0){

				$(".market .list-batch").remove();

				$("#no-match").css("visibility", "visible");
			}

			var sport = <?php echo "'".$sport."'"; ?>;
			var q = <?php echo "'".$_except."'"; ?>;

			var oddsWrapperHeight = $('#market-info').height();

			var height = oddsWrapperHeight * 0.1 + 'px';

			setInterval(function () {

				$.ajax({

					type: "POST",

					url: "inc-ajax.php",

					data: "sport=" + sport + '&q=' + q + '&action=fetchData',

					success: function (result) {

						var res = JSON.parse(result);

						console.log(res);

						var notif = Object.keys(res)[0];

						if(notif != 'error'){

							$("#no-match").css("visibility", "hidden");

							jQuery("#market-container").addClass('vticker');

							remover(res[Object.keys(res)[0]].html);

							appender(sport, res[Object.keys(res)[0]].html);

							odds_updater(res[Object.keys(res)[0]].html);

						}
						else{

							$("#no-match").css("visibility", "visible");

							$(".market").empty();

							jQuery("#market-container").removeClass('vticker');

						}

						console.log('Updater is running');

					},
					// complete: load
				});

			}, 45000);

		}

		load();
		
		
		
		var sports = "<?=$sport?>";
		var timer = 180000;
		
		if($('.market li ul').length == 0) timer = 100;
		
		var asports = [<?php echo '"'.implode('","', $sportActive).'"' ?>];
		if(asports.length > 1) {
    		for(var i = 0; i < asports.length; i++) {
        	    if(sports == asports[i]) {
        	        var ns = (i + 1 == asports.length) ? asports[0] : asports[i + 1];
        	        var link = "https://lvs-odds.mswodds.com/prematch/?sport=" + ns;
        	        setTimeout(function() {
        		        window.location = link;
        		    }, timer);
        	    }
    		}
		}

	});



</script>

</html>
