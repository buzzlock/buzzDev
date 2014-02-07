<?php
	$TMPLTEMP = array();
	$ADDON_DIR_ROOT = dirname(__FILE__);
	require_once($ADDON_DIR_ROOT."/addon_latestmembers.cfg.php");
	$TMPLTEMP['addon_latestmembers'] = '';
	if ($addon_latestmembers_show < 1) $addon_latestmembers_show = 5;
	
	// members status
	// 0 = free or inactive
	// 1 = active
	// 2 = blocked
	// 3 = expired
	// 4 = pending
	if ($addon_latestmembers_show_status == 8) {
		$addon_qry_lm_active = " AND {$CONF['sql_prefix']}_usersplan.active != '1'";
	} else if ($addon_latestmembers_show_status >= 0 && $addon_latestmembers_show_status <= 4) {
		$addon_qry_lm_active = " AND {$CONF['sql_prefix']}_usersplan.active='".$addon_latestmembers_show_status."'";
	} else {
		$addon_qry_lm_active = "";
	}
	
	$result = $DB->select_limit("SELECT {$CONF['sql_prefix']}_users.id, {$CONF['sql_prefix']}_users.fullname, {$CONF['sql_prefix']}_users.username, {$CONF['sql_prefix']}_users.email, {$CONF['sql_prefix']}_users.state, {$CONF['sql_prefix']}_users.country FROM {$CONF['sql_prefix']}_users, {$CONF['sql_prefix']}_usersplan WHERE {$CONF['sql_prefix']}_users.isconfirm='1'$addon_qry_lm_active AND {$CONF['sql_prefix']}_users.id = {$CONF['sql_prefix']}_usersplan.idmbr AND {$CONF['sql_prefix']}_usersplan.paymid = '1' ORDER BY {$CONF['sql_prefix']}_usersplan.join_date DESC, {$CONF['sql_prefix']}_users.id DESC", $addon_latestmembers_show, 0, __FILE__, __LINE__);
	$addon_qry_count = 1;
	while (list($id, $fullname, $username, $email, $state, $country) = $DB->fetch_array($result)) {
		$TMPLTEMP['addon_latestmembers'] .= $addon_qry_count.'. '.$fullname.' .:: '.$state.' '.$country;
		$TMPLTEMP['addon_latestmembers'] .= "<br />";
		$addon_qry_count++;
	}
	$TMPL = array_merge($TMPL, $TMPLTEMP);	
?>