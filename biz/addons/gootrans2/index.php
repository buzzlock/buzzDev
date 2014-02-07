<?php
	$TMPLTEMP = array();
	$ADDON_DIR_ROOT = dirname(__FILE__);
	require_once($ADDON_DIR_ROOT."/addon_gootrans2.cfg.php");
	
	$TMPLTEMP['addon_gootrans_flags'] = $TMPLTEMP['addon_gootrans_code'] = '<!-- start: google language selector -->'.$addon_gootrans2_snippet.'<!-- end: google language selector -->';
	
	$TMPL = array_merge($TMPL, $TMPLTEMP);	
?>