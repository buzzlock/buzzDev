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

{literal}
<style type="text/css">

#location_left
{
    
    margin-left: 100px;
    width: 42%;
}

#mapHolder
{
    
    width: 404px;
    height: 300px;
    clear: right;
    margin-left: 80px;
    margin-top: 15px;
    border:1px #999 solid;
}

#location_left .table_left
{
    
}

</style>
{/literal}

<div style="height: 420px;">
<form method="post" action="{url link='admincp.fevent.location'}">
<div class="table_header">
    {phrase var='fevent.admin_menu_manage_location'}
</div>

<div style="padding-top: 0px;">

<div style="padding-bottom: 2px;">
    <input id="location" name="val[location]" type="text" style="width:760px;" value="{if isset($aRow.default_value) && $aRow.default_value!=''}{$aRow.default_value}{else}{phrase var="fevent.location"}...{/if}" onfocus="if(this.value=='{phrase var="fevent.location"}...'){l}this.value=''{r}" onblur="if($.trim(this.value)==''){l}this.value='{phrase var="fevent.location"}...'{r}" />
</div>

<div id="gmap" style="width:770px; height:300px;">
    {phrase var='fevent.gmap_holder'}
</div>
{literal}
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&language=en"></script>
<script type="text/javascript">
var oLatLng;
var oMap;
var oMarker;
var lat={/literal}{$lat}{literal};
var lng={/literal}{$lng}{literal};
var zoom={/literal}{$zoom}{literal};
var aEventCoords = new Array();
var aCurrentEvents = new Array();
var displayMarkers = function(){};
var showOnMap = function(){};
var panGmapTo = function(){};
var gmapRequests = new Array();
var infowindow = null;

$Behavior.feventAdminLocation = function() {
    $("body").addClass("js_stopscript");
     
    if(!$("body").hasClass("js_newscript")) 
    {
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
    }
    
    google.maps.event.addDomListener(window, 'load', showOnMap);
    
    displayMarkers = function(json)
    {
        infowindow = new google.maps.InfoWindow({
            content: ''
        });
        var aEvents = $.parseJSON(json);
        
        for(var i in aEvents)
        {
            var eventId = aEvents[i]['event_id'];
            if(typeof(aCurrentEvents[eventId]) == 'undefined')
            {
                var info = '<div style="line-height:20px;">';
                info += '<strong>'+oTranslations['fevent.event']+': ' + aEvents[i]['title'] + '</strong><br/>';
                info += ''+oTranslations['fevent.time']+': ' + aEvents[i]['start_time'] + '<br/>';
                info += ''+oTranslations['fevent.location']+': ' + aEvents[i]['location'] + (aEvents[i]['address']!='' ? ', ' + aEvents[i]['address'] : '') + (aEvents[i]['city']!='' ? ', ' + aEvents[i]['city'] : '') + '<br/>';
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
    
    panGmapTo = function(lat, lng, iradius)
    {

        var newLatLng = new google.maps.LatLng(lat, lng);
                
        oMap.panTo(newLatLng);
        oMap.setZoom(13);
    }
    
    $("#location").keyup(function(){
        var city="";
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
}
</script>
{/literal}
                
<span style="float:right;padding-top: 5px;">
    <input type="submit" id="submit" name="submit" value="{phrase var='fevent.save_changes'}" class="button" />
</span>
</div>    

</form>
</div>