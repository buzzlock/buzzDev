<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');
/**
 * 
 * 
 * @copyright       [YOUNET_COPYRIGHT]
 * @author          YouNet Company
 * @package         YouNet_Event
 */
?>

<div style="padding-bottom: 2px;">
	<input id="location" type="text" style="width:220px;" value="{phrase var="fevent.location"}..." onfocus="if(this.value=='{phrase var="fevent.location"}...'){l}this.value=''{r}" onblur="if($.trim(this.value)==''){l}this.value='{phrase var="fevent.location"}...'{r}" />
	<input id="city" type="text" style="width:200px;" value="{phrase var="fevent.city"}..." onfocus="if(this.value=='{phrase var="fevent.city"}...'){l}this.value=''{r}" onblur="if($.trim(this.value)==''){l}this.value='{phrase var="fevent.city"}...'{r}" />
	<input id="radius" type="text" style="width:152px;" value="{phrase var="fevent.radius"}..." onfocus="if(this.value=='{phrase var="fevent.radius"}...'){l}this.value=''{r}" onblur="if($.trim(this.value)==''){l}this.value='{phrase var="fevent.radius"}...'{r}" />
	<select id="distance">
		<option value="0">{phrase var='fevent.miles'}</option>
		<option value="1">{phrase var='fevent.km'}</option>
	</select>
</div>

<div id="gmap" style="width:700px; height:300px;">
	GMap holder
</div>
{literal}

<script type="text/javascript">
	var oLatLng;
	var oMap;
	var oMarker;
	var ajson;
	var lat={/literal}{$lat}{literal};
	var lng={/literal}{$lng}{literal};
	var zoom={/literal}{$zoom}{literal};
	var iradius=0;
	var aEventCoords = new Array();
	var aCurrentEvents = new Array();
	var displayMarkers = function(){};
	var showOnMap = function(){};
	var panGmapTo = function(){};
	var gmapRequests = new Array();
	var infowindow = null;
	var aEvents;
	$("body").addClass("js_stopscript");
     
       
	if(!$("body").hasClass("js_newscript")) 
	{
		var script = document.createElement('script');
		script.type= 'text/javascript';
		script.src = 'http://maps.google.com/maps/api/js?sensor=false&callback=showOnMap';
		document.body.appendChild(script);
		$("body").addClass("js_newscript");
		$("body").removeClass("js_stopscript");
	}

	showOnMap = function()
	{
		oLatLng = new google.maps.LatLng(lat, lng);
		oMap = new google.maps.Map(document.getElementById("gmap"), {
			zoom: zoom,
			center: oLatLng,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		});
		oMap.bounds_changed = function(){
			var ids = new Array();
			var bound = oMap.getBounds();
			for(var i in aEventCoords)
			{
				if(bound.contains(aEventCoords[i]['latlng']))
				{
					ids.push(aEventCoords[i]['event_id']);
				}
			}
			if(ids.length > 0)
			{
				$.ajaxCall('fevent.getEventsForGmap', 'ids=' + ids.join(','));
			}
		};
		{/literal}
		{foreach from=$aCoords item=aCoord}
		aEventCoords.push({l}"latlng":new google.maps.LatLng({$aCoord.lat}, {$aCoord.lng}), "event_id":"{$aCoord.event_id}"{r});
		{/foreach}
		{literal}
	}
        
        google.maps.event.addDomListener(window, 'load', showOnMap);
	
        
	displayMarkers = function(json)
	{
		
		infowindow = new google.maps.InfoWindow({
		    content: ''
		});
		var aEvents = $.parseJSON(json);
		debug(aEvents);
		for(var i in aEvents)
		{
			var eventId = aEvents[i]['event_id'];
			//if(typeof(aCurrentEvents[eventId]) == 'undefined')
			{
				var info = '<div style="line-height:20px;">';
				info += '<strong>'+oTranslations['fevent.event']+': ' + aEvents[i]['title'] + '</strong><br/>';
				info += ''+oTranslations['fevent.time']+': ' + aEvents[i]['start_time'] + '<br/>';
				info += ''+oTranslations['fevent.location']+': ' + aEvents[i]['location'] + (aEvents[i]['address']!=null && aEvents[i]['address']!='' ? ', ' + aEvents[i]['address'] : '') + (aEvents[i]['city']!=null && aEvents[i]['city']!='' ? ', ' + aEvents[i]['city'] : '') + '<br/>';
				info += '<a target="_blank" href="'+aEvents[i]['link']+'"><strong>'+oTranslations['fevent.view_this_event']+'</strong></a>';
				info += '</div>';
				aCurrentEvents[eventId] = new google.maps.Marker({
					"info": info,
					map: oMap,
					position: new google.maps.LatLng(aEvents[i]['lat'], aEvents[i]['lng'])
				});
				google.maps.event.addListener(aCurrentEvents[eventId], 'click', function() {
					infowindow.setContent(this.info)
					infowindow.open(oMap, this);
				});
				
				
				
			}
			

		}
	}
	
	panGmapTo = function(lat1, lng1, iradius1)
	{
		lat=lat1;
		lng=lng1;
		zoom=13;
		iradius=iradius1;
		showOnMap();
		var newLatLng = new google.maps.LatLng(lat, lng);
		var distance = $("#distance").val();
		distance = distance * 1000;
		if(distance==0)
			distance=1609;
		var eventid = new google.maps.Marker({
					map: oMap,
					position: new google.maps.LatLng(lat, lng)
				});
				
			 // Add a Circle overlay to the map.
				var circle = new google.maps.Circle({
				  map: oMap,
				  radius: iradius*distance, // 300 km
				  //fillColor: '#AA0000'
				});

				// Since Circle and Marker both extend MVCObject, you can bind them
				// together using MVCObject's bindTo() method.  Here, we're binding
				// the Circle's center to the Marker's position.
				// http://code.google.com/apis/maps/documentation/v3/reference.html#MVCObject
				circle.bindTo('center', eventid, 'position');
					
		oMap.panTo(newLatLng);
		oMap.setZoom(13);
		
	}
	
	$("#location").keyup(function(){
		var city=$('#city').val();
		var location=$('#location').val();
		var radius=0;
		if(parseInt($('#radius').val()))
		{
			radius=$('#radius').val();
		}
		for(var i in gmapRequests)
		{
			gmapRequests[i].abort();
		}
		
		gmapRequests.push(
			$.ajaxCall('fevent.reloadGmap', 'location=' + this.value+'&city='+city+'&radius='+radius)
		);
	});
	
	$("#city").keyup(function(){
		var city=$('#city').val();
		var location=$('#location').val();
		var radius=0;
		if(parseInt($('#radius').val()))
		{
			radius=$('#radius').val();
		}
		
		for(var i in gmapRequests)
		{
			gmapRequests[i].abort();
		}
		
		gmapRequests.push(
			$.ajaxCall('fevent.reloadGmap', 'location=' + location+'&city='+city+'&radius='+radius)
		);
	});
	
	$("#radius").keyup(function(){
		var city=$('#city').val();
		var location=$('#location').val();
		var radius=0;
		if(parseInt($('#radius').val()))
		{
			radius=$('#radius').val();
		}
		for(var i in gmapRequests)
		{
			gmapRequests[i].abort();
		}
		
		gmapRequests.push(
			$.ajaxCall('fevent.reloadGmap', 'location=' + location+'&city='+city+'&radius='+radius)
		);
	});
	
	$("#distance").change(function(){
		var city=$('#city').val();
		var location=$('#location').val();
		var radius=0;
		if(parseInt($('#radius').val()))
		{
			radius=$('#radius').val();
		}
		for(var i in gmapRequests)
		{
			gmapRequests[i].abort();
		}
		
		gmapRequests.push(
			$.ajaxCall('fevent.reloadGmap', 'location=' + location+'&city='+city+'&radius='+radius)
		);
	});
	
	if($("body").hasClass("js_stopscript"))
	{
		showOnMap();	
	}
</script>
{/literal}