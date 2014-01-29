function openauthsocialmediaimporter(pageURL){
        var w = 800;
        var h = 500;
        var title = false;
        var left = (screen.width/2)-(w/2);
        var top = (screen.height/2)-(h/2);
        var newwindow = window.open (pageURL, title, 'toolbar=no,location=no,directories=no,status=no,menubar=no, scrollbars=yes,resizable=yes,copyhistory=no,width='+w+',height='+h+',top='+top+',left='+left, false);
        if (window.focus) {newwindow.focus();}
        return newwindow;
};

$Behavior.disableModeAajax = function()
{	
	if (oCore['core.site_wide_ajax_browsing']) {		
		$('#import_album').unbind('click');
		$('#import_photo').unbind('click');
		return false;
	}
}

$Core.myAjax = function(sCall, $oParams)
{
	var dnow = new Date();
	var time = Math.floor(dnow.valueOf()/1000);
	
	var sParams = '&' + getParam('sGlobalTokenName') + '[ajax]=true&' + getParam('sGlobalTokenName') + '[call]=' + sCall;
	
	if (!sParams.match(/\[security_token\]/i))
	{
		sParams += '&' + getParam('sGlobalTokenName') + '[security_token]=' + oCore['log.security_token'];
	}
	
	if (isset($oParams['params']))
	{
		if (typeof($oParams['params']) == 'string')
		{
			sParams += $oParams['params'];
		}
		else		
		{
			$.each($oParams['params'], function($sKey, $sValue)
			{
				sParams += '&' + $sKey + '=' + encodeURIComponent($sValue) + '';
			});
		}		
	}
	
	sParams += '&time=' + time;
	var ajaxRequest = $.ajax(
	{
		type: (isset($oParams['type']) ? $oParams['type'] : 'GET'),
		url: getParam('sJsStatic') + "ajax.php",
		dataType: 'html',
		data: sParams,
		success: $oParams['success']
	});	
	return ajaxRequest;
}
