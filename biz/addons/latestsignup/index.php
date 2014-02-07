<?php
	$TMPLTEMP = array();
	$ADDON_DIR_ROOT = dirname(__FILE__);
	require_once($ADDON_DIR_ROOT."/addon_latestsignup.cfg.php");
	
	$addon_latestsignup_show = intval($addon_latestsignup_show);
	$addon_latestsignup_show_status = intval($addon_latestsignup_show_status);
	$addon_latestsignup_speed = intval($addon_latestsignup_speed);
	$addon_latestsignup_pause = intval($addon_latestsignup_pause);
	$addon_latestsignup_anim  = $addon_latestsignup_anim;
	$addon_latestsignup_mousepause = $addon_latestsignup_mousepause;
	$addon_latestsignup_height = intval($addon_latestsignup_height);
	$addon_latestsignup_direction = $addon_latestsignup_direction;
		
	$TMPLTEMP['addon_latestsignup'] = '';
	if ($addon_latestsignup_show < 1) $addon_latestsignup_show = 5;
	
	// members status
	// 0 = free or inactive
	// 1 = active
	// 2 = blocked
	// 3 = expired
	// 4 = pending
	if ($addon_latestsignup_show_status == 8) {
		$addon_qry_lm_active = " AND {$CONF['sql_prefix']}_usersplan.active != '1'";
	} else if ($addon_latestsignup_show_status >= 0 && $addon_latestsignup_show_status <= 4) {
		$addon_qry_lm_active = " AND {$CONF['sql_prefix']}_usersplan.active='".$addon_latestsignup_show_status."'";
	} else {
		$addon_qry_lm_active = "";
	}
	
	require_once($ADDON_DIR_ROOT."/../../languages/countries.php");

	$TMPLTEMP['addon_latestsignup'] = $addon_latestsignup_style."<div id='latestsignup-container'><ul>";

	$result = $DB->select_limit("SELECT {$CONF['sql_prefix']}_users.id, {$CONF['sql_prefix']}_usersplan.join_date, {$CONF['sql_prefix']}_users.fullname, {$CONF['sql_prefix']}_users.username, {$CONF['sql_prefix']}_users.email, {$CONF['sql_prefix']}_users.state, {$CONF['sql_prefix']}_users.country FROM {$CONF['sql_prefix']}_users, {$CONF['sql_prefix']}_usersplan WHERE {$CONF['sql_prefix']}_users.isconfirm='1'$addon_qry_lm_active AND {$CONF['sql_prefix']}_users.id = {$CONF['sql_prefix']}_usersplan.idmbr AND {$CONF['sql_prefix']}_usersplan.paymid = '1' ORDER BY {$CONF['sql_prefix']}_usersplan.join_date DESC, {$CONF['sql_prefix']}_users.id DESC", $addon_latestsignup_show, 0, __FILE__, __LINE__);

	$addon_qry_count = 1;
	while (list($id, $join_date, $fullname, $username, $email, $state, $country) = $DB->fetch_array($result)) {
		//$TMPLTEMP['addon_latestsignup'] .= '<li>'.'- '.$fullname.' .:: '.$state.' '.$country.'</li>';
		$TMPLTEMP['addon_latestsignup'] .= '<li><img src="images/flags/'.strtolower($country).'.png" border=0 title="'.ucwords(strtolower($country_array[$country])).'"> '.$fullname.' (<em><font size="1">'.$join_date.'</font></em>)</li>';
		$addon_qry_count++;
	}

	$TMPLTEMP['addon_latestsignup'] .= '</ul></div>';

	$TMPL['include_addonsjscripts_user'] .= $TMPL['include_addonsjscripts'] .= <<<JSADD_CNT
		<script type="text/javascript" src="{$CONF['site_url']}/addons/latestsignup/imgjs/vticker14.js"></script> 
		<script type="text/javascript"> 
		$(function(){
			$('#latestsignup-container').vTicker({ 
				speed: {$addon_latestsignup_speed},
				pause: {$addon_latestsignup_pause},
				animation: '{$addon_latestsignup_anim}',
				mousePause: {$addon_latestsignup_mousepause},
				showItems: {$addon_latestsignup_show},
				height: {$addon_latestsignup_height},
				direction: '{$addon_latestsignup_direction}'
			});
		});
		</script> 
JSADD_CNT;

	$TMPL = array_merge($TMPL, $TMPLTEMP);	
?>