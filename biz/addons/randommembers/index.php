<?php
	$TMPLTEMP = array();
	$ADDON_DIR_ROOT = dirname(__FILE__);
	require_once($ADDON_DIR_ROOT."/addon_randommembers.cfg.php");
	$TMPLTEMP['addon_randommembers'] = '';
	$addon_sponsorlist_show = intval($addon_sponsorlist_show);
	$addon_randommembers_with_photo = intval($addon_randommembers_with_photo);
	if ($addon_randommembers_show_status < 1) $addon_randommembers_show_status = 'all';
	if ($addon_randommembers_show < 1) $addon_randommembers_show = 5;
	
	$addon_qry_stspth = "{$CONF['sql_prefix']}_usersplan.active = '1'";
	if ($addon_randommembers_show_status == 'all') $addon_qry_stspth = "{$CONF['sql_prefix']}_users.isconfirm = '1'";
	if ($addon_randommembers_with_photo != 0) $addon_qry_stspth .= " AND {$CONF['sql_prefix']}_users.user_photo != '' AND {$CONF['sql_prefix']}_users.user_photo != '{$CONF['default_banner']}'";
	
	$TMPLTEMP['addon_randommembers'] .= '<table width="300" border="0" cellspacing="4" cellpadding="2">';
	$result = $DB->select_limit("SELECT {$CONF['sql_prefix']}_users.id, {$CONF['sql_prefix']}_users.fullname, {$CONF['sql_prefix']}_users.username, {$CONF['sql_prefix']}_users.email, {$CONF['sql_prefix']}_users.state, {$CONF['sql_prefix']}_users.country, user_photo FROM {$CONF['sql_prefix']}_users, {$CONF['sql_prefix']}_usersplan WHERE $addon_qry_stspth AND {$CONF['sql_prefix']}_users.id = {$CONF['sql_prefix']}_usersplan.idmbr ORDER BY RAND()", $addon_randommembers_show, 0, __FILE__, __LINE__);
	$addon_qry_count = 1;
	while (list($id, $fullname, $username, $email, $state, $country, $user_photo) = $DB->fetch_array($result)) {
		$TMPLTEMP['addon_randommembers'] .= '<tr>';
		if ($addon_randommembers_with_photo == 1) {
			$TMPLTEMP['addon_randommembers'] .= '<td align="center"><img src="'.$user_photo.'" border=0></td>';
		}
		$TMPLTEMP['addon_randommembers'] .= '    <td nowrap="nowrap" align="right">'.$addon_qry_count.'.</td><td nowrap="nowrap">'.$fullname.' .:: '.$state.' '.$country.'</td>';
		$TMPLTEMP['addon_randommembers'] .= '</tr>';

		$addon_qry_count++;
	}
	$TMPLTEMP['addon_randommembers'] .= '</table>';
	$TMPL = array_merge($TMPL, $TMPLTEMP);	
?>