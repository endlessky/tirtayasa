var map;
var input = document.getElementById("startInput");
var output = document.getElementById("finishInput");
var markersLayer = new OpenLayers.Layer.Markers("Markers");
var resultOverlays = [];
	  
$(document).ready(function() {
	var protocol = new CicaheumLedengProtocol("E5D9904F0A8B4F99", function(message) {
		clearSecondaryAlerts();
		showAlert(messageConnectionError, 'alert');
	});
	
	map = new OpenLayers.Map("map");
	var road = new OpenLayers.Layer.Google(
			"Google Streets", // the default
            {numZoomLevels: 20}
    );
	var lineLayer = new OpenLayers.Layer.Vector("LineLayer"); 
	map.addLayer(lineLayer);  
	map.addLayer(road);
	map.addLayer(markersLayer);
	var myProjection = new OpenLayers.Projection("EPSG:4326");
	var mapProjection = map.getProjectionObject();
	var sizeBaloon = new OpenLayers.Size(50,31);
	var size = new OpenLayers.Size(35,30);
	var offset = new OpenLayers.Pixel(0, -size.h);
	var offsetStart = new OpenLayers.Pixel(-31, -size.h);

	var markers = {start: null, finish: null};
	markers['start'] = new OpenLayers.Marker(new OpenLayers.Map());
	markers['finish'] = new OpenLayers.Marker(new OpenLayers.Map());
	markersLayer.addMarker(markers['start']);
	markersLayer.addMarker(markers['finish']);
	updateRegion(region, false);
	
	$.each(['start', 'finish'], function(sfIndex, sfValue) {
		var placeInput = $('#' + sfValue + 'Input');
		var placeSelect = $('#' + sfValue + 'Select');

		if (input_text[sfValue] != null) {
			placeInput.val(input_text[sfValue]);
			if (coordinates[sfValue] != null) {
				placeInput.prop('disabled', true);
				var lonlat = stringToLonLat(coordinates[sfValue]);
				mapCenter = lonlat;
			}
		}
		$('#' + sfValue + 'Select').addClass('hidden');
		
		placeInput.change(function() {
			coordinates[sfValue] = null;
		});
		placeSelect.change(function() {
			clearAlerts();
			showAlert(messagePleaseWait, 'secondary');
			coordinates[sfValue] = $(this).val();
			checkCoordinatesThenRoute(coordinates);
		});
		
	});
	
	// Event handlers
	var localeSelect = $('#localeselect');
	localeSelect.change(function() {
		// IE fix: when window.location.origin is not available 
		if (!window.location.origin) {
			window.location.origin = window.location.protocol + "//" + window.location.hostname + (window.location.port ? ':' + window.location.port: '');
		}
		window.location.replace(window.location.origin + "?locale=" + localeSelect.val());
	});
	var regionSelect = $('#regionselect');
	regionSelect.change(function() {
		updateRegion(regionSelect.val(), true);
	});
	$('#findbutton').click(findRouteClicked);
	$('input').keyup(function(e) {
	    if ( e.keyCode === 13 ) {
	    	findRouteClicked();
	    }
	});
	$('#resetbutton').click(resetScreen);
	$('#swapbutton').click(swapInput);

	// Lastly, execute search if both start and finish are ready
	if ($('#startInput').val() != '' && $('#finishInput').val() != '' ) {
		findRouteClicked();
	}
	
	/**
	 * Check if coordinates are complete. If yes, then start routing.
	 * @param coordinates the coordinates to check.
	 */
	function checkCoordinatesThenRoute(coordinates) {
		if (coordinates['start'] != null && coordinates['finish'] != null) {
			protocol.findRoute(
					coordinates['start'],
					coordinates['finish'],
					locale,
					function(results) {
						if (results.status === 'ok') {
							showRoutingResults(results);
						} else {
							clearSecondaryAlerts();
							showAlert(messageConnectionError, 'alert');
						}
					});
		}
	}	
	
	function clearRoutingResultsOnMap() {
		lineLayer.removeAllFeatures();
		map.removeLayer(markersLayer);
		markersLayer.destroy();
		markersLayer = new OpenLayers.Layer.Markers("Markers");
		map.addLayer(markersLayer);
		updateRegion(region, false);
		resultOverlays = [];
	}
	
	function clearRoutingResultsOnTable() {
		$('.tabs').remove();
		$('.tabs-content').remove();
	}
	
	function clearAlerts() {
		$('.alert-box').remove();
	}
	
	function clearSecondaryAlerts() {
		$('.alert-box.secondary').fadeOut();
	}
	
	function clearStartFinishMarker() {
		markers['start'].erase();
	    markers['finish'].erase();		
		
	}
	
	/**
	 * A function that will be called when find route button is clicked
	 * (or triggered by another means)
	 */
	function findRouteClicked() {
		// Validate
		var cancel = false;
		$.each(['start', 'finish'], function(sfIndex, sfValue) {
			if ($('#' + sfValue + 'Input').val() === '') {
				cancel = true;		
				return;
			}
		});
		if (cancel) {
			showAlert(messageFillBoth, 'alert');			
			return;
		}
		
		clearAlerts();
		clearRoutingResultsOnTable();
		showAlert(messagePleaseWait, 'secondary');
		
		var completedLatLon = 0;
		$.each(['start', 'finish'], function(sfIndex, sfValue) {
			var placeInput = $('#' + sfValue + 'Input');
			var placeSelect = $('#' + sfValue + 'Select');
			if (isLatLng(placeInput.val())) {
				coordinates[sfValue] = placeInput.val();
				completedLatLon++;
			} else {
				if (coordinates[sfValue] == null) {
					// Coordinates not yet ready, we do a search place
					protocol.searchPlace(
							placeInput.val(),
							region,
							function(result) {
								placeSelect.empty();
								placeSelect.addClass('hidden');
								if (result.status != 'error') {
									if (result.searchresult.length > 0) {
										$.each(result.searchresult, function(index, value) {
											var placeSelect = $('#' + sfValue + 'Select');
											placeSelect
										         .append($('<option></option>')
										         .attr('value',value['location'])
										         .text(value['placename']));
											placeSelect.removeClass('hidden');
										});
										coordinates[sfValue] = result.searchresult[0]['location'];
										checkCoordinatesThenRoute(coordinates);
									} else {
										clearSecondaryAlerts();
										clearRoutingResultsOnMap();
										showAlert(placeInput.val() + messageNotFound, 'alert');
									}
								} else {
									clearSecondaryAlerts();
									clearRoutingResultsOnMap();
									showAlert(messageConnectionError, 'alert');
								}
							});
				} else {
					// Coordinates are already available, skip searching
					checkCoordinatesThenRoute(coordinates);
				}
			}
		});
		if (completedLatLon == 2) {
			checkCoordinatesThenRoute(coordinates);
		}
	}
	
	/**
	 * Convert lon/lng into text representation
	 * @return the lon/lng in string, 5 digits after '.'
	 */
	function latLngToString(lonLat) {
		return lonLat.lat().toFixed(5) + ',' + lonLat.lng().toFixed(5);
	}
	
	/**
	 * Checks if the text provided is in a lat/lng format.
	 * @return true if it is, false otherwise.
	 */
	function isLatLng(text) {
		return text.match('-?[0-9.]+,-?[0-9.]+');
	}
	
	function resetScreen() {
		mapProjection = map.getProjectionObject();
		clearRoutingResultsOnTable();
		clearRoutingResultsOnMap();
		clearAlerts();
		$.each(['start', 'finish'], function(sfIndex, sfValue) {
			var placeInput = $('#' + sfValue + 'Input');
			placeInput.val('');	
			placeInput.prop('disabled', false);
			$('#' + sfValue + 'Select').addClass('hidden');
		});
	}
	
	/**
	 * Sets a cookie in browser, adapted from http://www.w3schools.com/js/js_cookies.asp
	 */
	function setCookie(cname,cvalue)	{
		var d = new Date();
		d.setTime(d.getTime()+(365*24*60*60*1000));
		var expires = "expires="+d.toGMTString();
		document.cookie = cname + "=" + cvalue + "; " + expires;
	} 
	
	/**
	 * Show alert message
	 * @param message the message
	 * @param cssClass the foundation css class ('success', 'alert', 'secondary')
	 */
	function showAlert(message, cssClass) {
		var alert = $('<div data-alert class="alert-box ' + cssClass + ' round">' + message + '<a href="#" class="close">&times;</a></div>');
		$('#routingresults').prepend(alert);
		$(document).foundation();    
	}

	/**
	 * Shows the routing result on panel an map
	 * @param results the routing result JSON response
	 */
	function showRoutingResults(results) {
		clearStartFinishMarker();
		clearRoutingResultsOnTable();
		clearSecondaryAlerts();
		var kiriURL = encodeURIComponent('http://kiri.travel?start=' + $('#startInput').val() + '&finish=' + $('#finishInput').val() + '&region=' + region);
		var kiriMessage = encodeURIComponent(messageITake.replace('%finish%', $('#finishInput').val()).replace('%start%', $('#startInput').val()));
		var sectionContainer = $('<div></div>');
		var temp1 = $('<dl class="tabs" data-tab=""></dl>');
		var temp2 = $('<div class="tabs-content"></div>');
		$('#routingresults').append(sectionContainer);
		$.each(results.routingresults, function(resultIndex, result) {
			var resultHTML1 = resultIndex === 0 ? '<dd class="active">' : '<dd class="">';
			resultHTML1 += '<a href="#panel1-' + (resultIndex + 1) + '">' + (result.traveltime === null ? messageOops : result.traveltime) + '</a></dd>';
			var resultHTML2 = '<div id="panel1-' + (resultIndex + 1)+'"';
			resultHTML2 += resultIndex === 0 ? ' class="content active"><table>' : ' class="content"><table>';
			$.each(result.steps, function (stepIndex, step) {
				resultHTML2 += '<tr><td><img src="../images/means/' + step[0]+ '/' + step[1] + '.png" alt="' + step[1] + '"/></td><td>';
				if (step[4] != null) {
					resultHTML2 += step[3] + ' (<a href="' + step[4] + '" target="_blank">' + messageOrderTicketHere + '</a>)</td></tr>';
				} else{
					resultHTML2 += step[3] + '</td></tr>';
				}
			});
			resultHTML2 += "<tr><td class=\"center\" colspan=\"2\">";
			resultHTML2 += "<a target=\"_blank\" href=\"https://www.facebook.com/sharer/sharer.php?u=" + kiriURL + "\"><img alt=\"Share to Facebook\" src=\"images/fb-large.png\"/></a> &nbsp; &nbsp; ";
			resultHTML2 += "<a target=\"_blank\" href=\"https://twitter.com/intent/tweet?via=kiriupdate&url=" + kiriURL + "&text=" + kiriMessage + "\"><img alt=\"Tweet\" src=\"images/twitter-large.png\"/></a>";
			resultHTML2 += "</td></tr>\n";
			resultHTML2 += '</table></div>';
			temp1.append(resultHTML1);
			temp2.append(resultHTML2);

		});
		sectionContainer.append(temp1);
		sectionContainer.append(temp2);

		$.each(results.routingresults, function(resultIndex, result) {
			$('a[href="#panel1-' + (resultIndex + 1) + '"]').click(function() {
				showSingleRoutingResultOnMap(result);
 			});
		});
	    $(document).foundation();    
		showSingleRoutingResultOnMap(results.routingresults[0]);
	}
	
	/**
	 * Shows a single routing result on map
	 * @param result the JSON array for one result
	 */
	function showSingleRoutingResultOnMap(result) {
		clearRoutingResultsOnMap();
		map.addControl(new OpenLayers.Control.DrawFeature(lineLayer, OpenLayers.Handler.Path));      
		var angkotStyle = { 
		  strokeColor: '#339933', 
		  strokeWidth: 5
		};
		
		var walkStyle = { 
		  strokeColor: '#CC3333', 
		  strokeWidth: 5
		};

		var noneStyle = { 
		  strokeColor: '#FFFFFF', 
		  strokeWidth: 0
		};
		
		$.each(result.steps, function (stepIndex, step) {
			if (step[0] === 'none') {
				var line = new OpenLayers.Geometry.LineString(stringArrayToPointArray(step[2]));
				var lineFeature = new OpenLayers.Feature.Vector(
					line, null, noneStyle
				);
				lineLayer.addFeatures([lineFeature]);
			} else if (step[0] === 'walk') {
				var line = new OpenLayers.Geometry.LineString(stringArrayToPointArray(step[2]));
				var lineFeature = new OpenLayers.Feature.Vector(
					line, null, walkStyle
				);
				lineLayer.addFeatures([lineFeature]);
			} else {
				var line = new OpenLayers.Geometry.LineString(stringArrayToPointArray(step[2]));
				var lineFeature = new OpenLayers.Feature.Vector(
					line, null, angkotStyle
				);
				lineLayer.addFeatures([lineFeature]);
			}
			
			if (stepIndex === 0) {
				var lonlat = stringToLonLat(step[2][0]);
				resultOverlays.push(new OpenLayers.Marker(
					lonlat.transform(myProjection, mapProjection), new OpenLayers.Icon('images/start.png', size, offsetStart)
				));
			} else {
				var lonlat = stringToLonLat(step[2][0]);
				if(step[0] != "walk"){
					resultOverlays.push(new OpenLayers.Marker(
						lonlat.transform(myProjection, mapProjection), new OpenLayers.Icon('../images/means/' + step[0] + '/baloon/' + step[1] + '.png', sizeBaloon, offset)
					));
				} else{
					resultOverlays.push(new OpenLayers.Marker(
						lonlat.transform(myProjection, mapProjection), new OpenLayers.Icon('../images/means/' + step[0] + '/baloon/' + step[1] + '.png', size, offsetStart)
					));
				}
			}
			
			if (stepIndex === result.steps.length - 1) {
				var lonlat = stringToLonLat(step[2][step[2].length - 1]);
				resultOverlays.push(new OpenLayers.Marker(
					lonlat.transform(myProjection, mapProjection), new OpenLayers.Icon('images/finish.png', size, offset)
				));
			}
		});
		$.each(resultOverlays, function (overlayIndex, overlay) {
			markersLayer.addMarker(overlay);
		});
		map.zoomToExtent(markersLayer.getDataExtent());
	}	
	
	/**
	 * Converts "lat,lon" array into Point object array.
	 * @return the converted Point array object
	 */
	function stringArrayToPointArray(textArray) {
		var lonlatArray = new Array();
		$.each(textArray, function(index, value) {
			var tampungLonlat = stringToLonLat(value);
			var lon = tampungLonlat.lon;
			var lat = tampungLonlat.lat;
			lonlatArray[index] = new OpenLayers.Geometry.Point(lon,lat).transform(myProjection, mapProjection);
		});
		
		return lonlatArray;
	}	
	
	/**
	 * Converts "lat,lng" into LatLng object.
	 * @return the converted LatLng object
	 */
	function stringToLonLat(text) {
		var latlon = text.split(/,\s*/);
		return new OpenLayers.LonLat(parseFloat(latlon[1]),parseFloat(latlon[0]));
	}
	
	/**
	 * Swap the inputs
	 */
	function swapInput() {
		var startInput = $('#startInput');
		var finishInput = $('#finishInput');
		var temp = startInput.val();
		startInput.val(finishInput.val());
		finishInput.val(temp);
		if (startInput.val() != '' && finishInput.val() != '') {
			findRouteClicked();
		}
	}
	
	/**
	 * Updates the region information in this page.
	 */
	function updateRegion(newRegion, updateCookie) {
		region = newRegion;
		setCookie('region', region);
		var point = stringToLonLat(regions[region].center);
		map.setCenter(point.transform(myProjection, mapProjection), regions[region].zoom);
	}
});

