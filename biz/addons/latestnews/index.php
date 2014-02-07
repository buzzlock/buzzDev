<?php
	$TMPLTEMP = array();
	$ADDON_DIR_ROOT = dirname(__FILE__);
	require_once($ADDON_DIR_ROOT."/addon_latestnews.cfg.php");
	$TMPLTEMP['addon_latestnews'] = '';
	if ($addon_latestnews_show < 1) $addon_latestnews_show = 5;
	
	$result = $DB->select_limit("SELECT id, ntitle, ncontent, ndate_entry, ndate_update FROM {$CONF['sql_prefix']}_news WHERE ntype < 2 AND nstatus != 0 ORDER BY ndate_update DESC, id DESC", $addon_latestnews_show, 0, __FILE__, __LINE__);
	while (list($id, $ntitle, $ncontent, $ndate_entry, $ndate_update) = $DB->fetch_array($result)) {
		$addon_latestnews_cntshow = "<br />".substr(strip_tags($ncontent), 0, 149)."...";
		$TMPLTEMP['addon_latestnews'] .= "<p><em>".$ndate_entry."</em><br /><a href='".se_url('news', '', '', $TMPL['sefurl'])."#".base64_encode($id)."' target='_blank'><strong>".$ntitle."</strong></a>$addon_latestnews_cntshow</p><br />";
	}
	$TMPL = array_merge($TMPL, $TMPLTEMP);	
?>