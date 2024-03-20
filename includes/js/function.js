
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

function diff(A, B) {

	return A.filter(function (a) {
	
	  return B.indexOf(a) == -1;
	  
	});
	
};

function odds_updater(data){
		
	var oddsWrapperHeight = $('#market-info').height();

	var height = oddsWrapperHeight * 0.1 + 'px';
		
	$.each(data, function(leagueid, league_details){
	
		$.each(league_details, function(market_name, market_details){
		
			$.each(market_details['match'], function(match_group, match_details){
			
				$.each(match_details, function(match_id, team_details){
				
					//checker for outright or not because they are differ in array structure
					if(market_details['league_details']['market_name'] != 'Outright EN'){
					
						//checker match if exist
						if($('.list-batch-ul #'+leagueid+'_'+match_id).length == 1){
					
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

							// console.log($('.'+slug(leagueid)).length);
									
							if($('#'+slug(leagueid)+' .market-odds .odds-selection #'+details['team_id']).length == 1){
							
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

function remover(data){

	var leagueIDs = [];
	var matchIDs = [];
	var html_leagueIDs = [];
	var html_matchIDs = [];
	
	$('.market-odds').each(function(i, obj) {
		html_matchIDs.push(obj.id);
	});

	$.each(data, function(leagueID, firstLayer){
		
		$.each(firstLayer, function(marketName, secLayer){
			
			$.each(secLayer['match'], function(thirdLayerKey, thirdLayerVal){
				
				var realMarketName = secLayer['league_details']['market_name'];
				
				if(realMarketName == 'Outright EN'){
				
					leagueIDs.push(slug(leagueID));
				
				}
				else{
					
					leagueIDs.push(leagueID);
				
					$.each(thirdLayerVal, function(matchID, fourthLayerVal){
					
						matchIDs.push(leagueID + '_' + matchID);
					
					});
				
				}
			
			});
		
		});
	
	});
	
	$('.list-batch').each(function(i, obj) {
		html_leagueIDs.push(obj.id);
	});
	
	$('.market-odds').each(function(i, obj) {
		html_matchIDs.push(obj.id);
	});
	
	var inactive_league = diff(html_leagueIDs, leagueIDs);
	
	var inactive_match = diff(html_matchIDs, matchIDs);
	
	if(inactive_league != ''){
	
		$.each(inactive_league, function(index, ids){
		
			$("#"+ ids).remove();
			
			console.log('League has been removed. League id: '+ids);
		
		});
		
	}

	if(inactive_match != ''){
	
		$.each(inactive_match, function(index, ids){
			
			if(ids != ''){
			
				$("#"+ ids).remove();
				
				console.log('Match has been removed. Match id: '+ids);
				
			}
		
		});
	
	}
	

}

function appender(sport, data){

	var oddsWrapperHeight = $('#market-info').height();

	var height = oddsWrapperHeight * 0.1 + 'px';

	$.each(data, function(leagueID, firstLayer){
	
		$.each(firstLayer, function(marketName, secLayer){
			
			$.each(secLayer['match'], function(thirdLayerKey, thirdLayerVal){
				
				var realMarketName = secLayer['league_details']['market_name'];
				
				if(realMarketName == 'Outright EN'){
				
					if($('.market #' + slug(leagueID)).length === 0){
					
						var last_key = thirdLayerKey;
				
						$(".market").append('<li class="list-batch" id="' + slug(leagueID) + '">'+
						
							'<ul class="list-batch-ul '+slug(secLayer['league_details']['market_name'])+'">'+
							
							'<li class="league-title" style="margin: 0px; display: list-item;height:'+height+';">');
							
							// '<div class="event-container"><span class="event-name">' + secLayer['league_details']['country_name'] + ': ' + secLayer['league_details']['league_name'] + ' </span> </div>');
			
							// if(thirdLayerKey == 0){
							
								$('.market #' + slug(leagueID) + ' .'+slug(secLayer['league_details']['market_name']) +' .league-title').append('<div class="event-container"><span class="event-name">' + secLayer['league_details']['country_name'] + ': ' + secLayer['league_details']['league_name'] + ' &mdash; '+secLayer['league_details']['market_name']+'('+marketName+')</span></div>');
							
							// }
							// else{
							
								// $('.market #' + slug(leagueID) + ' .'+slug(secLayer['league_details']['market_name']) +' .league-title').append('<div class="market-title-container"><span class="market-title">&mdash; '+secLayer['league_details']['market_name']+'('+market_name+') / '+matchkey+' OF ' + (parseInt(last_key)+1) + '</span></div>');
							
							// }
						// console.log(thirdLayerVal);
						//teams appender for Outright	
						$.each(thirdLayerVal, function(match_id, team_details){			
							var matchtime = formatAMPM(new Date(fourthLayerVal['match_details']['match_date']));
							var matchdate = fourthLayerVal['match_details']['match_date'].substr(0, 10);
								
							if(sport == 'Boxing/MMA' || sport == 'MMA/Boxing' || sport == 'Formula1') {
						        $('.market #' + leagueID + ' .' + slug(secLayer['league_details']['real_name'])).append(' <li class="market-odds" id="' + leagueID + '_' + matchID + '" style="height:'+height+';">'+
														
								'<div class="match-title">'+ secLayer['league_details']['real_name'] +' ('+ matchdate + ' ' + matchtime +')</div><div class="odds-selection">');
						    } else {
							    $('.market #' + leagueID + ' .' + slug(secLayer['league_details']['real_name'])).append(' <li class="market-odds" id="' + leagueID + '_' + matchID + '" style="height:'+height+';">'+
														
								'<div class="match-title">'+ secLayer['league_details']['real_name'] +' ('+matchtime+')</div><div class="odds-selection">');	
						    }
								
							$.each(team_details, function(team, teamdetails){
							
								if($(' .' + slug(secLayer['league_details']['market_name']) + ' .odds-selection' + ' #' + teamdetails['team_id']).length == 0){
							
										$('#' + slug(leagueID) + ' .' + slug(secLayer['league_details']['market_name']) + ' .odds-selection').append('<div class="odds-container-outright" id="' + teamdetails['team_id'] + '">'+
											
												'<div class="team-container">' +
												
												  '<span class="odds-team">' + teamdetails['team_name'] + '</span>' +
												  
												'</div>' +
												
												'<div class="price-container">' +
												
												  '<span class="odds-price">' + teamdetails['team_price'] + '</span>' +
												  
												'</div>' +
												
											'</div>');
									
								}
							
							});
							
						});
				
						console.log('League has been created ' + secLayer['league_details']['country_name'] + ': ' + secLayer['league_details']['league_name']);
					
					
					}
				
				}
				else{
				
					var last_key = thirdLayerKey;
				
					if($('.market #' + leagueID + ' .'+slug(secLayer['league_details']['real_name'])).length === 0){
							
						$(".market").append('<li class="list-batch" id="' + leagueID + '">'+
					
						'<ul class="list-batch-ul ' + slug(secLayer['league_details']['real_name']) + '">'+
						
						'<li class="league-title" style="margin: 0px; display: list-item;height:'+height+';">');
				
						if(secLayer['league_details']['real_name'] == 'Head To Head'){
						
							// if(thirdLayerKey == 0){
							
								// $('.market #' + leagueID + ' .'+slug(secLayer['league_details']['real_name'])+' .league-title').append('<div class="market-title-container"><span class="market-title">&mdash; Head To Head @ Match</span></div>');
								$('.market #' + leagueID + ' .'+slug(secLayer['league_details']['real_name'])+' .league-title').append('<div class="event-container"><span class="event-name">' + secLayer['league_details']['country_name'] + ': ' + secLayer['league_details']['league_name'] + ' &mdash; Head To Head @ Match</span></div>');
							
							// }
							// else{
							
								// $('.market #' + leagueID + ' .'+slug(secLayer['league_details']['real_name'])+' .league-title').append('<div class="market-title-container"><span class="market-title">&mdash; Head To Head @ Match ('+thirdLayerKey+' OF ' + (parseInt(last_key)+1) + ')</span></div>');
							
							// }
							
						}
						else{
						
							// if(thirdLayerKey == 0){
							
								// $('.market #' + leagueID + ' .'+slug(secLayer['league_details']['real_name'])+' .league-title').append('<div class="market-title-container"><span class="market-title">&mdash; ' + marketName + '@ Match</span></div>');
								$('.market #' + leagueID + ' .'+slug(secLayer['league_details']['real_name'])+' .league-title').append('<div class="event-container"><span class="event-name">' + secLayer['league_details']['country_name'] + ': ' + secLayer['league_details']['league_name'] + ' &mdash; ' + marketName + ' @ ' + secLayer['league_details']['market_name'] + '</span></div>');
							
							// }
							// else{
							
								// $('.market #' + leagueID + ' .'+slug(secLayer['league_details']['real_name'])+' .league-title').append('<div class="market-title-container"><span class="market-title">&mdash; ' + marketName + '@ Match ('+thirdLayerKey+' OF ' + (parseInt(last_key)+1) + ')</span></div>');
							
							// }
						
						}
						
						console.log('League has been created ' + secLayer['league_details']['country_name'] + ': ' + secLayer['league_details']['league_name']);
					
					}
				
					$.each(thirdLayerVal, function(matchID, fourthLayerVal){
					
						if($('#'+leagueID + ' .list-batch-ul #' + leagueID + '_' + matchID).length == 0){				
							
							var matchtime = formatAMPM(new Date(fourthLayerVal['match_details']['match_date']));
								
							var matchdate = fourthLayerVal['match_details']['match_date'].substr(0, 10);
						    
						    if(sport == 'Boxing/MMA' || sport == 'MMA/Boxing' || sport == 'Formula1') {
						        $('.market #' + leagueID + ' .' + slug(secLayer['league_details']['real_name'])).append(' <li class="market-odds" id="' + leagueID + '_' + matchID + '" style="height:'+height+';">'+
														
								'<div class="match-title">'+ secLayer['league_details']['real_name'] +' ('+ matchdate + ' ' + matchtime +')</div><div class="odds-selection">');
						    } else {
							    $('.market #' + leagueID + ' .' + slug(secLayer['league_details']['real_name'])).append(' <li class="market-odds" id="' + leagueID + '_' + matchID + '" style="height:'+height+';">'+
														
								'<div class="match-title">'+ secLayer['league_details']['real_name'] +' ('+matchtime+')</div><div class="odds-selection">');	
						    }
						
							$.each(fourthLayerVal['team_details'], function(match_id, teamdetails){
									
										// if(secLayer['league_details']['real_name'] == 'Head To Head' || secLayer['league_details']['real_name'] == 'To Win the Bout'  || secLayer['league_details']['real_name'] == '2 Way' || secLayer['league_details']['real_name'].indexOf('Handicap') == -1 || secLayer['league_details']['real_name'].indexOf('Total Points') == -1){
										if(fourthLayerVal['team_details'].length == 2){
														
											$('#'+ leagueID + '_' +matchID+' .odds-selection').append('<div class="odds-container" id="' + teamdetails['team_id'] + '">'+
											
												'<div class="team-container">'+
												
												  '<span class="odds-team">' + teamdetails['team_name'] + '</span>'+
												  
												'</div>'+
												
												'<div class="price-container">'+
												
												  '<span class="odds-price">' + teamdetails['team_price'] + '</span>'+
												  
												'</div>'+
												
											'</div>');
										
										}
										
										else if(fourthLayerVal['team_details'].length == 3){
										// else if(secLayer['league_details']['real_name'] == 'Win/Draw/Win'){
										// else{
										
											$('#'+ leagueID + '_' +matchID+' .odds-selection').append('<div class="odds-container-windrawwin" id="' + teamdetails['team_id'] + '">'+
											
												'<div class="team-container">'+
												
												  '<span class="odds-team">' + teamdetails['team_name'] + '</span>'+
												  
												'</div>'+
												
												'<div class="price-container">'+
												
												  '<span class="odds-price">' + teamdetails['team_price'] + '</span>'+
												  
												'</div>'+
												
											'</div>');													
										
										}
									
								
									// console.log('Match has been appended to ' + secLayer['league_details']['league_name'] + '. match id: ' + matchID);
								
								
							
							});		
												
							/*$('#'+ leagueID + '_' +matchID+' .odds-selection').append('<div class="date-container">'+
									 
								'<span class="market-date">' + matchdate + '<span class="clock-time"> '+matchtime+'</span></span>'+
										
								'</div>');*/				
						
						}
					
					});					
				
				}
			
			});
		
		});
	
	});
	

}