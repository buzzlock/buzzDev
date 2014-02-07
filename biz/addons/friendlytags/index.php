<?php
	$TMPLTEMP = array();
	if ($CONF && is_array($CONF)) {
		foreach ($CONF as $k => $v) {
			$TMPLTEMP['tagcfg1_'.$k] = $v;
		}
	}
	if ($PAYM && is_array($PAYM)) {
		foreach ($PAYM as $k => $v) {
			$TMPLTEMP['tagcfg2_'.$k] = $v;
		}
	}
	if ($SPR && is_array($SPR)) {
		foreach ($SPR as $k => $v) {
			$TMPLTEMP['tagspr_'.$k] = $v;
		}

		// get sponsor custom fields
		list($cf_count) = $DB->fetch("SELECT count(*) FROM {$CONF['sql_prefix']}_userfields WHERE is4usr = '1'", __FILE__, __LINE__);
		if ($cf_count > 0) {
			$cfuser = $DB->fetch("SELECT * FROM {$CONF['sql_prefix']}_usersxtd WHERE username = '{$TMPLTEMP['tagspr_username']}'", __FILE__, __LINE__);
			$result = $DB->query("SELECT frm FROM {$CONF['sql_prefix']}_userfields WHERE is4usr = '1' ORDER BY frmorder", __FILE__, __LINE__);
			while (list($frm) = $DB->fetch_array($result)) {
				$TMPLTEMP['tagspr_'.$frm] = $cfuser[$frm];
			}
		}	
	}
	if ($REFF && is_array($REFF)) {
		foreach ($REFF as $k => $v) {
			$TMPLTEMP['tagref_'.$k] = $v;
		}

		// get referrer custom fields
		list($cf_count) = $DB->fetch("SELECT count(*) FROM {$CONF['sql_prefix']}_userfields WHERE is4usr = '1'", __FILE__, __LINE__);
		if ($cf_count > 0) {
			$cfuser = $DB->fetch("SELECT * FROM {$CONF['sql_prefix']}_usersxtd WHERE username = '{$TMPLTEMP['tagref_username']}'", __FILE__, __LINE__);
			$result = $DB->query("SELECT frm FROM {$CONF['sql_prefix']}_userfields WHERE is4usr = '1' ORDER BY frmorder", __FILE__, __LINE__);
			while (list($frm) = $DB->fetch_array($result)) {
				$TMPLTEMP['tagref_'.$frm] = $cfuser[$frm];
			}
		}	
	}
	
	// ----------- if login --------------
	list($temp_val['type'], $temp_val['data']) = $session->get($_SESSION['ezygold_sid_client']);
	$temp_val['username'] = $DB->escape($temp_val['data']);
	$addon_PAYMid = $PAYM['id'];
	if ($_SESSION['ezygold_pid_client'] > 0) $addon_PAYMid = $_SESSION['ezygold_pid_client'];
	
	if ($temp_val['username'] != '') {
		list($temp_val['idusr'], $temp_val['idref'], $temp_val['idspr'], $temp_val['myxup']) = $DB->fetch("SELECT {$CONF['sql_prefix']}_users.id, {$CONF['sql_prefix']}_usersplan.idref, {$CONF['sql_prefix']}_usersplan.idspr, {$CONF['sql_prefix']}_usersplan.myxup FROM {$CONF['sql_prefix']}_users, {$CONF['sql_prefix']}_usersplan WHERE {$CONF['sql_prefix']}_users.username = '{$temp_val['username']}' AND {$CONF['sql_prefix']}_users.id = {$CONF['sql_prefix']}_usersplan.idmbr AND {$CONF['sql_prefix']}_usersplan.paymid = '{$addon_PAYMid}'", __FILE__, __LINE__);
		
		// referrer
		$cfuser = $DB->fetch("SELECT * FROM {$CONF['sql_prefix']}_users WHERE id = '{$temp_val['idref']}'", __FILE__, __LINE__);
		if ($cfuser && is_array($cfuser)) {
			foreach ($cfuser as $k => $v) {
				$TMPLTEMP['tagmyref_'.$k] = $v;
			}
	
			// get referrer custom fields
			list($cf_count) = $DB->fetch("SELECT count(*) FROM {$CONF['sql_prefix']}_userfields WHERE is4usr = '1'", __FILE__, __LINE__);
			if ($cf_count > 0) {
				$cfuser = $DB->fetch("SELECT * FROM {$CONF['sql_prefix']}_usersxtd WHERE idmbr = '{$temp_val['idref']}'", __FILE__, __LINE__);
				$result = $DB->query("SELECT frm FROM {$CONF['sql_prefix']}_userfields WHERE is4usr = '1' ORDER BY frmorder", __FILE__, __LINE__);
				while (list($frm) = $DB->fetch_array($result)) {
					$TMPLTEMP['tagmyref_'.$frm] = $cfuser[$frm];
				}
			}	
		}

		// sponsor
		$cfuser = $DB->fetch("SELECT * FROM {$CONF['sql_prefix']}_users WHERE id = '{$temp_val['idspr']}'", __FILE__, __LINE__);
		if ($cfuser && is_array($cfuser)) {
			foreach ($cfuser as $k => $v) {
				$TMPLTEMP['tagmyspr_'.$k] = $v;
			}
	
			// get sponsor custom fields
			list($cf_count) = $DB->fetch("SELECT count(*) FROM {$CONF['sql_prefix']}_userfields WHERE is4usr = '1'", __FILE__, __LINE__);
			if ($cf_count > 0) {
				$cfuser = $DB->fetch("SELECT * FROM {$CONF['sql_prefix']}_usersxtd WHERE idmbr = '{$temp_val['idspr']}'", __FILE__, __LINE__);
				$result = $DB->query("SELECT frm FROM {$CONF['sql_prefix']}_userfields WHERE is4usr = '1' ORDER BY frmorder", __FILE__, __LINE__);
				while (list($frm) = $DB->fetch_array($result)) {
					$TMPLTEMP['tagmyspr_'.$frm] = $cfuser[$frm];
				}
			}	
		}
	
		// showing sponsor and referrer images
		if ($TMPLTEMP['tagmyspr_user_photo'] != '') {
			$TMPLTEMP['tagmyspr_show_photo'] = $TMPLTEMP['tagmyspr_show_photoku'] = "<img src='{$TMPLTEMP['tagmyspr_user_photo']}' border=1 title=''>";
		} else {
			$TMPLTEMP['tagmyspr_show_photo'] = "";
			$TMPLTEMP['tagmyspr_show_photoku'] = "<img src='images/no_user_img.jpg' border=1 title=''>";
		}
		if ($TMPLTEMP['tagmyref_user_photo'] != '') {
			$TMPLTEMP['tagmyref_show_photo'] = $TMPLTEMP['tagmyref_show_photoku'] = "<img src='{$TMPLTEMP['tagmyref_user_photo']}' border=1 title=''>";
		} else {
			$TMPLTEMP['tagmyref_show_photo'] = "";
			$TMPLTEMP['tagmyref_show_photoku'] = "<img src='images/no_user_img.jpg' border=1 title=''>";
		}
	
		// my self ;)
		$cfuser = $DB->fetch("SELECT * FROM {$CONF['sql_prefix']}_users WHERE username = '{$temp_val['username']}'", __FILE__, __LINE__);
		if ($cfuser && is_array($cfuser)) {
			foreach ($cfuser as $k => $v) {
				$TMPLTEMP['tagusr_'.$k] = $v;
			}
	
			// get my custom fields
			list($cf_count) = $DB->fetch("SELECT count(*) FROM {$CONF['sql_prefix']}_userfields WHERE is4usr = '1'", __FILE__, __LINE__);
			if ($cf_count > 0) {
				$cfuser = $DB->fetch("SELECT * FROM {$CONF['sql_prefix']}_usersxtd WHERE idmbr = '{$temp_val['idusr']}'", __FILE__, __LINE__);
				$result = $DB->query("SELECT frm FROM {$CONF['sql_prefix']}_userfields WHERE is4usr = '1' ORDER BY frmorder", __FILE__, __LINE__);
				while (list($frm) = $DB->fetch_array($result)) {
					$TMPLTEMP['tagusr_'.$frm] = $cfuser[$frm];
				}
			}	
		}
		if ($TMPLTEMP['tagusr_user_photo'] != '') {
			$TMPLTEMP['tagusr_show_photo'] = $TMPLTEMP['tagusr_show_photoku'] = "<img src='{$TMPLTEMP['tagusr_user_photo']}' border=1 title=''>";
		} else {
			$TMPLTEMP['tagusr_show_photo'] = "";
			$TMPLTEMP['tagusr_show_photoku'] = "<img src='images/no_user_img.jpg' border=1 title=''>";
		}
			
		// count messages
    	//fetch($result) = $DB->fetch("SELECT COUNT(*) FROM {$CONF['sql_prefix']}_messenger WHERE ((mfrom = '{$temp_val['idusr']}' AND mto != '0') OR (mfrom != '0' AND mto = '{$temp_val['idusr']}')) AND mtype != '0' AND mread = '0'", __FILE__, __LINE__);
		$TMPLTEMP['tagusr_messages_unread'] = 0;
		$TMPLTEMP['tagusr_messages_total'] = 0;
		$TMPLTEMP['tagusr_feedback_unread'] = 0;
		$TMPLTEMP['tagusr_feedback_total'] = 0;
		list($TMPLTEMP['tagusr_messages_unread']) = $DB->fetch("SELECT COUNT(*) FROM {$CONF['sql_prefix']}_messenger WHERE (mfrom != '0' AND mto = '{$temp_val['idusr']}') AND mtype != '0' AND mread = '0'", __FILE__, __LINE__);
		list($TMPLTEMP['tagusr_messages_total']) = $DB->fetch("SELECT COUNT(*) FROM {$CONF['sql_prefix']}_messenger WHERE (mfrom != '0' AND mto = '{$temp_val['idusr']}') AND mtype != '0'", __FILE__, __LINE__);
		
		// count feedback response
		list($TMPLTEMP['tagusr_feedback_unread']) = $DB->fetch("SELECT COUNT(*) FROM {$CONF['sql_prefix']}_messenger WHERE ((mfrom = '0' AND mto = '{$temp_val['idusr']}')) AND mtype != 0 AND mread = '0'", __FILE__, __LINE__);
		list($TMPLTEMP['tagusr_feedback_total']) = $DB->fetch("SELECT COUNT(*) FROM {$CONF['sql_prefix']}_messenger WHERE ((mfrom = '0' AND mto = '{$temp_val['idusr']}')) AND mtype != 0", __FILE__, __LINE__);
	}
	// ----------- end if ----------------

	// showing sponsor and referrer images
	if ($TMPLTEMP['tagspr_user_photo'] != '') {
		$TMPLTEMP['tagspr_show_photo'] = $TMPLTEMP['tagspr_show_photoku'] = "<img src='{$TMPLTEMP['tagspr_user_photo']}' border=1 title=''>";
	} else {
		$TMPLTEMP['tagspr_show_photo'] = "";
		$TMPLTEMP['tagspr_show_photoku'] = "<img src='images/no_user_img.jpg' border=1 title=''>";
	}
	if ($TMPLTEMP['tagref_user_photo'] != '') {
		$TMPLTEMP['tagref_show_photo'] = $TMPLTEMP['tagref_show_photoku'] = "<img src='{$TMPLTEMP['tagref_user_photo']}' border=1 title=''>";
	} else {
		$TMPLTEMP['tagref_show_photo'] = "";
		$TMPLTEMP['tagref_show_photoku'] = "<img src='images/no_user_img.jpg' border=1 title=''>";
	}
	
	// encode the site title and get referral url
	$TMPLTEMP['site_name_enc'] = urlencode($CONF['site_name']);
	$TMPLTEMP['site_desc_enc'] = urlencode($ETCS['sitedescr']);
	$TMPLTEMP['site_ref_url'] = $TMPLTEMP['site_spr_url'] = $TMPLTEMP['site_usr_url'] = $CONF['site_url'];
	if ($TMPLTEMP['tagref_username']) $TMPLTEMP['site_ref_url'] = $CONF['site_url'].'/id/'.$TMPLTEMP['tagref_username'];
	if ($TMPLTEMP['tagspr_username']) $TMPLTEMP['site_spr_url'] = $CONF['site_url'].'/id/'.$TMPLTEMP['tagspr_username'];
	if ($TMPLTEMP['tagusr_username']) $TMPLTEMP['site_usr_url'] = $CONF['site_url'].'/id/'.$TMPLTEMP['tagusr_username'];
	$TMPLTEMP['site_ref_url_enc'] = urlencode($TMPLTEMP['site_ref_url']);
	$TMPLTEMP['site_spr_url_enc'] = urlencode($TMPLTEMP['site_spr_url']);
	$TMPLTEMP['site_usr_url_enc'] = urlencode($TMPLTEMP['site_usr_url']);

	// showing sponsor name and sample text
	$TMPLTEMP['tagspr_fullname_txt'] = '';
	if ($TMPLTEMP['tagspr_fullname'] != '') $TMPLTEMP['tagspr_fullname_txt'] = 'Your Sponsor is <strong>'.$TMPLTEMP['tagspr_fullname'].'</strong><br />';
	
	$temp_val = '';
	$TMPL = array_merge($TMPL, $TMPLTEMP);
?>