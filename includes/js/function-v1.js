
var slug = function(str) {

	var $slug = '';
	
	var trimmed = $.trim(str);
	
	$slug = trimmed.replace(/[^a-z0-9-]/gi, '-').
	
	replace(/-+/g, '-').
	
	replace(/^-|-$/g, '');
	
	return $slug.toLowerCase();
	
}

function formatAMPM(date) {

	var hours = date.getHours();
	
	var minutes = date.getMinutes();
	
	var ampm = hours >= 12 ? 'PM' : 'AM';
	
	hours = hours % 12;
	
	hours = hours ? hours : 12; // the hour '0' should be '12'
	
	hours = hours < 10 ? '0'+hours : hours; // the hour '0' should be '12'
	
	minutes = minutes < 10 ? '0'+minutes : minutes;
	
	var strTime = hours + ':' + minutes + ' ' + ampm;
	
	return strTime;
	
}
	
function odds_updater(data){
		
	var oddsWrapperHeight = $('#market-info').height();

	var height = oddsWrapperHeight * 0.1 + 'px';
		
	$.each(data, function(leagueid, league_details){
	
		$.each(league_details, function(market_name, market_details){
		
			$.each(market_details['match'], function(match_group, match_details){
			
				$.each(match_details, function(match_id, team_details){
				
					//checker for outright or not because they are differ in array structure
					if(market_details['league_details']['market_name'] != 'Outright'){
					
						//checker match if exist
						if($('.list-batch-ul #'+match_id).length == 1){
					
							$.each(team_details['team_details'], function(team, details){
							
								var price = $('.odds-selection #'+details['team_id']+' .odds-price').html();
										
								if(price != details['team_price']){
								
									$('.odds-selection #'+details['team_id']+' .odds-price').fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100).html( details['team_price'] );
									
									console.log('Odds of ' + details['team_name'] + 'has been updated');
								
								}
							
							});
						
						}
					
					}
					else{
						
						$.each(team_details, function(team, details){								
									
							if($('.'+slug(market_details['league_details']['market_name'])+' .market-odds #'+details['team_id']).length == 1){
							
								var price = $('.odds-selection #'+details['team_id']+' .odds-price').html();
								
								if(price != details['team_price']){
								
									$('.odds-selection #'+details['team_id']+' .odds-price').fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100).html( details['team_price'] );
							
									console.log('Odds of ' + details['team_name'] + 'has been updated');
								
								}
								
							}
					
						});
						
					
					}
				
				});
			
			});
		
		});
	
	});

}

function match_appender(data){

	var oddsWrapperHeight = $('#market-info').height();

	var height = oddsWrapperHeight * 0.1 + 'px';
	
	$.each(data, function(leagueid, league_details){
	
		$.each(league_details, function(market_name, market_details){
		
			//Outright market is not allowed to append mathces. Only Head to head and other markets are allowed
			if(market_details['league_details']['market_name'] != 'Outright'){
			
				// console.log();
		
				$.each(market_details['match'], function(match_group, match_details){
				
					$.each(match_details, function(match_id, team_details){
						
						//Check if league id and match id exists
						if($('.market #'+leagueid).length != 0 && $('.list-batch-ul #'+match_id).length === 0){	
						
							var matchdate = team_details['match_details']['match_date'].substr(0, 10);					
							
							var matchtime = formatAMPM(new Date(team_details['match_details']['match_date']));
						
							$('.market #' + leagueid + ' .' + slug(market_details['league_details']['real_name'])).append(' <li class="market-odds" id="' + match_id + '" style="height:'+height+';">'+
														
								'<div class="odds-selection">');
								
							$.each(team_details['team_details'], function(team, teamdetails){
							
								if(market_details['league_details']['real_name'] == 'Head To Head' || market_details['league_details']['real_name'] == 'To Win the Bout'  || market_details['league_details']['real_name'] == '2 Way' || market_details['league_details']['real_name'].indexOf('Handicap') == -1 || market_details['league_details']['real_name'].indexOf('Total Points') == -1){
												
									$('#'+match_id+' .odds-selection').append('<div class="odds-container" id="' + teamdetails['team_id'] + '">'+
									
										'<div class="team-container">'+
										
										  '<span class="odds-team">' + teamdetails['team_name'] + '</span>'+
										  
										'</div>'+
										
										'<div class="price-container">'+
										
										  '<span class="odds-price">' + teamdetails['team_price'] + '</span>'+
										  
										'</div>'+
										
									'</div>');
								
								}
								
								else{
								
									$('#'+match_id+' .odds-selection').append('<div class="odds-container-windrawwin" id="' + teamdetails['team_id'] + '">'+
									
										'<div class="team-container">'+
										
										  '<span class="odds-team">' + teamdetails['team_name'] + '</span>'+
										  
										'</div>'+
										
										'<div class="price-container">'+
										
										  '<span class="odds-price">' + teamdetails['team_price'] + '</span>'+
										  
										'</div>'+
										
									'</div>');													
								
								}
							
							});
										
							$('#'+match_id+' .odds-selection').append('<div class="date-container">'+
									 
								'<span class="market-date">' + matchdate + '<span class="clock-time"> '+matchtime+'</span></span>'+
										
								'</div>');
							
						
							console.log('Match has been appended to ' + market_details['league_details']['league_name'] + '. match id: ' + match_id);
						
						}
				
					});
					
				});
			
			}

		});
			
	});
	
}

function leagueHeader_creater(data){
		
	var oddsWrapperHeight = $('#market-info').height();

	var height = oddsWrapperHeight * 0.1 + 'px';
		
	$.each(data, function(leagueid, league_details){
	
		$.each(league_details, function(market_name, market_details){
		
			$.each(market_details['match'], function(matchkey, matchval){
			
				var last_key = matchkey;
		
				//checker for outright or not because they are differ in array structure
				if(market_details['league_details']['market_name'] != 'Outright'){
					
					//checker if league exists
					if($('.market #' + leagueid + ' .'+slug(market_details['league_details']['real_name'])).length === 0){
										
						$(".market").append('<li class="list-batch" id="' + leagueid + '">'+
					
						'<ul class="list-batch-ul ' + slug(market_details['league_details']['real_name']) + '">'+
						
						'<li class="league-title" style="margin: 0px; display: list-item;height:'+height+';">'+
						
						'<div class="event-container"><span class="event-name">' + market_details['league_details']['country_name'] + ': ' + market_details['league_details']['league_name'] + ' </span> </div>');
						
						if(market_details['league_details']['real_name'] == 'Head To Head'){
						
							if(matchkey == 0){
							
								$('.market #' + leagueid + ' .'+slug(market_details['league_details']['real_name'])+' .league-title').append('<div class="market-title-container"><span class="market-title">&mdash; Head To Head @ Match</span></div>');
							
							}
							else{
							
								$('.market #' + leagueid + ' .'+slug(market_details['league_details']['real_name'])+' .league-title').append('<div class="market-title-container"><span class="market-title">&mdash; Head To Head @ Match ('+matchkey+' OF ' + (parseInt(last_key)+1) + ')</span></div>');
							
							}
							
						}
						else{
						
							if(matchkey == 0){
							
								$('.market #' + leagueid + ' .'+slug(market_details['league_details']['real_name'])+' .league-title').append('<div class="market-title-container"><span class="market-title">&mdash; ' + market_name + '@ Match</span></div>');
							
							}
							else{
							
								$('.market #' + leagueid + ' .'+slug(market_details['league_details']['real_name'])+' .league-title').append('<div class="market-title-container"><span class="market-title">&mdash; ' + market_name + '@ Match ('+matchkey+' OF ' + (parseInt(last_key)+1) + ')</span></div>');
							
							}
						
						}
						
						console.log('League has been created ' + market_details['league_details']['country_name'] + ': ' + market_details['league_details']['league_name']);
					
					}
				
				}
				else{
				
					//checker if league exists
					if($('.market #' + leagueid + ' .'+slug(market_details['league_details']['market_name'])).length === 0){
						
						var last_key = matchkey;
				
						$(".market").append('<li class="list-batch" id="' + leagueid + '">'+
						
							'<ul class="list-batch-ul '+slug(market_details['league_details']['market_name'])+'">'+
							
							'<li class="league-title" style="margin: 0px; display: list-item;height:'+height+';">'+
							
							'<div class="event-container"><span class="event-name">' + market_details['league_details']['country_name'] + ': ' + market_details['league_details']['league_name'] + ' </span> </div>');
			
							if(matchkey == 0){
							
								$('.market #' + leagueid + ' .'+slug(market_details['league_details']['market_name']) +' .league-title').append('<div class="market-title-container"><span class="market-title">&mdash; '+market_details['league_details']['market_name']+'('+market_name+')</span></div>');
							
							}
							else{
							
								$('.market #' + leagueid + ' .'+slug(market_details['league_details']['market_name']) +' .league-title').append('<div class="market-title-container"><span class="market-title">&mdash; '+market_details['league_details']['market_name']+'('+market_name+') / '+matchkey+' OF ' + (parseInt(last_key)+1) + '</span></div>');
							
							}
							
						//teams appender for Outright	
						$.each(matchval, function(match_id, team_details){
								
							$('.market #' + leagueid + ' .' + slug(market_details['league_details']['market_name'])).append(' <li class="market-odds" style="height:'+height+';">'+
														
								'<div class="odds-selection">');
								
							$.each(team_details, function(team, teamdetails){
							
								$('#' + leagueid + ' .' + slug(market_details['league_details']['market_name']) + ' .odds-selection').append('<div class="odds-container-outright" id="' + teamdetails['team_id'] + '">'+
									
										'<div class="team-container">' +
										
										  '<span class="odds-team">' + teamdetails['team_name'] + '</span>' +
										  
										'</div>' +
										
										'<div class="price-container">' +
										
										  '<span class="odds-price">' + teamdetails['team_price'] + '</span>' +
										  
										'</div>' +
										
									'</div>');
							
							});
							
						});
				
						console.log('League has been created ' + market_details['league_details']['country_name'] + ': ' + market_details['league_details']['league_name']);
					
					}
				
				}
			
			});
		
		});
			
	});

}

function diff(A, B) {

	return A.filter(function (a) {
	
	  return B.indexOf(a) == -1;
	  
	});
	
};


function content_remover(data){

	var html_leagueID = [];
	var html_matchID = [];
	var leagueIDs = [];
	var matchIDs = [];
	
	$.each(data, function(leagueid, league_details){
		leagueIDs.push(leagueid);
	});
	
	
	
	$.each(data, function(leagueid, league_details){
	
		$.each(league_details, function(market_name, market_details){
		
			//Outright market is not allowed to append mathces. Only Head to head and other markets are allowed
			if(market_details['league_details']['market_name'] != 'Outright'){
		
				$.each(market_details['match'], function(match_group, match_details){
				
					$.each(match_details, function(match_id, team_details){
						matchIDs.push(match_id);
					});
					
				});

			}
			
		});
		
	});
	
	$('.list-batch').each(function(i, obj) {
		html_leagueID.push(obj.id);
	});
	
	$('.market-odds').each(function(i, obj) {
		html_matchID.push(obj.id);
	});
	
	var inactive_league = diff(html_leagueID, leagueIDs);
	var inactive_match = diff(html_matchID, matchIDs);

}