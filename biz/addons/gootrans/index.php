<?php
	$TMPLTEMP = array();
	$ADDON_DIR_ROOT = dirname(__FILE__);
	require_once($ADDON_DIR_ROOT."/addon_gootrans.cfg.php");
	
	$TMPLTEMP['addon_gootrans_text'] = '<!-- start: language selector content loading throbber --><div id="throbber-container"><div id="throbber">'.$addon_gootrans_processtext.'</div></div><!-- end: language selector content loading throbber -->';
	$TMPLTEMP['addon_gootrans_flags'] = '<!-- start: language selector flags --><div id="flags"><ul>';

	$addon_gootrans_ciso = str_replace(' ', '', strtolower($addon_gootrans_ciso));
	$addon_gootrans_lang = str_replace(', ', ',', trim($addon_gootrans_lang));
	$addon_gootrans_ciso_arr = explode(',', $addon_gootrans_ciso);
	$addon_gootrans_lang_arr = explode(',', $addon_gootrans_lang);
	
	$addon_gootrans_arr = count($addon_gootrans_ciso_arr);
	//for ($addon_gootrans_arr_i = 0; $addon_gootrans_arr_i < $addon_gootrans_arr; $addon_gootrans_arr_i++) {
	for ($addon_gootrans_arr_i = $addon_gootrans_arr-1; $addon_gootrans_arr_i >= 0; $addon_gootrans_arr_i--) {
		if ($addon_gootrans_ciso_arr[$addon_gootrans_arr_i] != '' && $addon_gootrans_lang_arr[$addon_gootrans_arr_i] != '') {
			$TMPLTEMP['addon_gootrans_flags'] .= '<li><a href="javascript:;" id="'.$addon_gootrans_ciso_arr[$addon_gootrans_arr_i].'" title="'.$addon_gootrans_lang_arr[$addon_gootrans_arr_i].'"><img src="'.$CONF['site_url'].'/addons/gootrans/images/'.$addon_gootrans_ciso_arr[$addon_gootrans_arr_i].'.png" border="0" alt="'.strtoupper($addon_gootrans_ciso_arr[$addon_gootrans_arr_i]).'"></a></li>';
		}
	}
	$TMPLTEMP['addon_gootrans_flags'] .= '</ul></div><!-- end: language selector flags -->';
	
	$TMPL['include_addonsjscripts_user'] .= $TMPL['include_addonsjscripts'] .= <<<JSADD_CNT
        <link rel="stylesheet" href="{$CONF['site_url']}/etcs/language-selector.css" type="text/css" media="screen" />
        <script type="text/javascript" src="{$CONF['site_url']}/etcs/jquery.cookie.js"></script>
        <script type="text/javascript" src="{$CONF['site_url']}/etcs/jquery.translate-1.4.6-debug-all.js"></script>
        <script type="text/javascript" src="{$CONF['site_url']}/etcs/language-selector-handler.js"></script>
JSADD_CNT;

	$TMPL = array_merge($TMPL, $TMPLTEMP);	
?>