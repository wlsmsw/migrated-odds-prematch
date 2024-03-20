 
$(document).ready(function(){

	$(window).on("resize", function() {  

		var screenHeight = $(window).height();
		
		var screenWidth = $(window).width();
		
		var oddsWrapperHeight = $('#market-info').height();
		
		var clockWidth = $('#clock-ticker').width();

		if($(window).height() <= screenHeight) {

			$('.league-title').css('height', oddsWrapperHeight * 0.1);
			
			$('.market-odds').css('height', oddsWrapperHeight * 0.1);
			
			$('.smarticker5').css('width', screenWidth - clockWidth);

		}

	}).resize();

	var options = {
	
		format:'<span class=\"dt\">%I:%M:%S %P</span>',
		
		timeNotation: '12h',
		
		am_pm: true,
		
		utc:true,
		
		utc_offset: 8
		
	}
	
	$('#clock-ticker .clock-digit').jclock(options);

	var optionsDate = {
	
		format:'<span class=\"dt\">%a, %b %d</span>',
		
		timeNotation: '12h',
		
		am_pm: true,utc:true,
		
		utc_offset: 8
		
	}
	
	$('#clock-ticker .clock-date').jclock(optionsDate);		

	$('#clock-ticker .clock-date').hide();	

	$('#js-news').ticker({
		speed: 0.10,           // The speed of the reveal
		ajaxFeed: false,       // Populate jQuery News Ticker via a feed
		feedUrl: false,        // The URL of the feed
						   // MUST BE ON THE SAME DOMAIN AS THE TICKER
		feedType: 'xml',       // Currently only XML
		htmlFeed: true,        // Populate jQuery News Ticker via HTML
		debugMode: true,       // Show some helpful errors in the console or as alerts
						   // SHOULD BE SET TO FALSE FOR PRODUCTION SITES!
		controls: false,        // Whether or not to show the jQuery News Ticker controls
		titleText: '',   // To remove the title set this to an empty String
		displayType: 'reveal', // Animation type - current options are 'reveal' or 'fade'
		direction: 'ltr',       // Ticker direction - current options are 'ltr' or 'rtl'
		pauseOnItems: 2000,    // The pause on a news item before being replaced
		fadeInSpeed: 600,      // Speed of fade in animation
		fadeOutSpeed: 300      // Speed of fade out animation
		
	});	

	/* $('.smarticker5').smarticker({
	
		theme:'2',
		
		imagesPath:'http://xyren.pc/mswodds-lvs/v2/odds-web-flex/includes/images/',
		
		rssFeed:'http://sports.espn.go.com/espn/rss/news,'+
		
		'http://sports.espn.go.com/espn/rss/nfl/news,'+
		
		'http://sports.espn.go.com/espn/rss/nba/news,'+
		
		'http://sports.espn.go.com/espn/rss/mlb/news,'+
		
		'http://sports.espn.go.com/espn/rss/nhl/news,'+
		
		'http://www.foxsportsasia.com/football-rss,'+
		
		'http://www.foxsportsasia.com/f1-rss,'+
		
		'http://sports.espn.go.com/espn/rss/rpm/news,'+
		
		'http://api.foxsports.com/v1/rss?partnerKey=zBaFxRyGKCfxBagJG9b8pqLyndmvo7UU&tag=soccer,'+
		
		'http://sports.espn.go.com/espn/rss/ncf/news,'+
		
		'http://api.foxsports.com/v1/rss?partnerKey=zBaFxRyGKCfxBagJG9b8pqLyndmvo7UU&tag=ufc,'+
		
		'http://www.foxsportsasia.com/tennis-rss,'+				
		
		'http://www.FOXSPORTSASIA.com/golf-rss,'+
		
		'http://sports.espn.go.com/espn/rss/ncb/news',		
		
		rssCats: 'HEADLINES,NFL,NBA,MLB,NHL,Football,Formula 1,Motorsports,Soccer,College Football,UFC,Tennis,Golf, College Basketball',
		
		rssSources:'msw-ticker-logo-mini.png',
		
		rssColors:'121212,121212'
	
	}); */
	
});