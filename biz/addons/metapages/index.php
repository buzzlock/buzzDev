<?php
	$TMPLTEMP = array();
	$ADDON_DIR_ROOT = dirname(__FILE__);
	require_once($ADDON_DIR_ROOT."/addon_metapages.cfg.php");
	
	$addon_metapages_id = strtolower($_REQUEST['a']);
	
	if (array_key_exists($addon_metapages_id, $addon_metapages_keywords)) {
		if ($addon_metapages_title[$addon_metapages_id] != '') $TMPLTEMP['site_title'] = $addon_metapages_title[$addon_metapages_id];
		if ($addon_metapages_description[$addon_metapages_id] != '') $ETCS['sitedescr'] = $addon_metapages_description[$addon_metapages_id];
		if ($addon_metapages_keywords[$addon_metapages_id] != '') $ETCS['sitekeywrd'] = $addon_metapages_keywords[$addon_metapages_id];
	}

	$TMPL = array_merge($TMPL, $TMPLTEMP);
	
	// if using custom page title, rename the "{$site_name} - {$header}" tag to "{$site_title}" in the template.html file.
?>