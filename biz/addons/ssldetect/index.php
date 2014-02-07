<?php
	$ssldetect_host  = $_SERVER['HTTP_HOST'];
	$ssldetect_self  = $_SERVER['PHP_SELF'];
	$ssldetect_query = !empty($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : null;
	$ssldetect_url   = !empty($ssldetect_query) ? "$ssldetect_host$ssldetect_self?$ssldetect_query" : "$ssldetect_host$ssldetect_self";

	if (($_SERVER['SERVER_PORT'] == 443) || (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on")) {
		$TMPLTEMP['addon_ssldetect'] = "<a href='http://$ssldetect_url' title='Click here to switch the connection'><img src='{$CONF['site_url']}/images/sslarea_yes.gif' border=0 alt='SSL Connection'></a>";
	} else {
		$TMPLTEMP['addon_ssldetect'] = "<a href='https://$ssldetect_url' title='Click here to switch the connection'><img src='{$CONF['site_url']}/images/sslarea_no.gif' border=0 alt='Non SSL Connection'></a>";
	}
	$TMPL = array_merge($TMPL, $TMPLTEMP);	
?>