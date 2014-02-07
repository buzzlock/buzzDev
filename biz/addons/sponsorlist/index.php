<?php
	$TMPLTEMP = array();
	$ADDON_DIR_ROOT = dirname(__FILE__);
	require_once($ADDON_DIR_ROOT."/addon_sponsorlist.cfg.php");
	$TMPLTEMP['addon_sponsorlist'] = '';
	$addon_sponsorlist_show = intval($addon_sponsorlist_show);
	if ($addon_sponsorlist_show == 0) $addon_sponsorlist_show = $PAYM['maxdeep'];
	if ($addon_sponsorlist_show > $PAYM['maxdeep']) $addon_sponsorlist_show = $PAYM['maxdeep'];
	
	if (intval($addon_sponsorlist_tbl_width) < 10) $addon_sponsorlist_tbl_width = 300;
	
	// if sponsor exist
	if ($SPR['username']) {
		list($addon_sponsorlist_id, $addon_sponsorlist_dlist, $addon_sponsorlist_paymid) = $DB->fetch("SELECT {$CONF['sql_prefix']}_users.id, {$CONF['sql_prefix']}_usersplan.dlist, {$CONF['sql_prefix']}_usersplan.paymid FROM {$CONF['sql_prefix']}_users, {$CONF['sql_prefix']}_usersplan WHERE {$CONF['sql_prefix']}_users.username = '{$SPR['username']}' AND {$CONF['sql_prefix']}_users.id = {$CONF['sql_prefix']}_usersplan.idmbr ORDER BY {$CONF['sql_prefix']}_usersplan.idusrpp DESC LIMIT 1", __FILE__, __LINE__);
		$addon_sponsorlist_dlist = "|1:".$addon_sponsorlist_id."|, ".$addon_sponsorlist_dlist;

		$TMPLTEMP['addon_sponsorlist'] .= '<table bgcolor="#DDDDDD" width="$addon_sponsorlist_tbl_width" border="0" cellspacing="4" cellpadding="2">';
		$TMPLTEMP['addon_sponsorlist'] .= '<tr class="mediumbg"><td align="center" colspan="2"><strong>SPONSOR LIST</strong></td></tr>';
		$addon_qry_count = 1;
		$class_tr = 'lightbgalt';
		$addon_sponsorlist_dlist_x = explode(",", $addon_sponsorlist_dlist);
		if ($addon_sponsorlist_show > count($addon_sponsorlist_dlist_x)) $addon_sponsorlist_show = count($addon_sponsorlist_dlist_x);
		for ($i = 0; $i < $addon_sponsorlist_show; $i++) {
			$addon_sponsorlist_user = trim($addon_sponsorlist_dlist_x[$i]);
			$addon_sponsorlist_user = str_replace("|", "", $addon_sponsorlist_user);
			$addon_sponsorlist_id_x = explode(":", $addon_sponsorlist_user);
			$addon_sponsorlist_id   = $addon_sponsorlist_id_x[1];

			if ($addon_sponsorlist_id < 1) continue;
			
			list($id, $fullname, $username, $email, $url, $title, $state, $country) = $DB->fetch("SELECT {$CONF['sql_prefix']}_users.id, {$CONF['sql_prefix']}_users.fullname, {$CONF['sql_prefix']}_users.username, {$CONF['sql_prefix']}_users.email, {$CONF['sql_prefix']}_users.url, {$CONF['sql_prefix']}_users.title, {$CONF['sql_prefix']}_users.state, {$CONF['sql_prefix']}_users.country FROM {$CONF['sql_prefix']}_users, {$CONF['sql_prefix']}_usersplan WHERE {$CONF['sql_prefix']}_usersplan.active = '1' AND {$CONF['sql_prefix']}_users.id = '$addon_sponsorlist_id' AND {$CONF['sql_prefix']}_users.id = {$CONF['sql_prefix']}_usersplan.idmbr AND {$CONF['sql_prefix']}_usersplan.paymid = '$addon_sponsorlist_paymid'", __FILE__, __LINE__);
				
			//if ($username == '') continue;

			// get referrer custom fields
			list($cf_count) = $DB->fetch("SELECT count(*) FROM {$CONF['sql_prefix']}_userfields WHERE is4usr = '1'", __FILE__, __LINE__);
			if ($cf_count > 0) {
				$cfuser = $DB->fetch("SELECT * FROM {$CONF['sql_prefix']}_usersxtd WHERE username = '{$username}'", __FILE__, __LINE__);
				$result = $DB->query("SELECT frm FROM {$CONF['sql_prefix']}_userfields WHERE is4usr = '1' ORDER BY frmorder", __FILE__, __LINE__);
				while (list($frm) = $DB->fetch_array($result)) {
					$sprlist_.$frm = $cfuser[$frm];
				}
			}	

			$addon_spr_cnt  = '';
			$addon_spr_cnt .= "<b>$fullname</b> - $country<br />";
			if ($addon_sponsorlist_show_email == 1) $addon_spr_cnt .= "$email<br />";
			if ($addon_sponsorlist_show_site == 1) $addon_spr_cnt .= "<a href='$url' target='_blank' title='$title'>$url</a><br />";
			
			$TMPLTEMP['addon_sponsorlist'] .= '<tr class="'.$class_tr.'">';
			$TMPLTEMP['addon_sponsorlist'] .= '    <td width="80" align="center"><font color="#666666"><b>SPONSOR<br />'.$addon_qry_count.'</b></font></td><td width="220" valign="top">'.$addon_spr_cnt.'</td>';
			$TMPLTEMP['addon_sponsorlist'] .= '</tr>';
			
			$class_tr = 'lightbg';
			$addon_qry_count++;
		}
		$TMPLTEMP['addon_sponsorlist'] .= '</table>';
		$TMPL = array_merge($TMPL, $TMPLTEMP);	
	}
?>