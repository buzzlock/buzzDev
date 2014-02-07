<?php
	$TMPLTEMP = array();
	
	// ----------- if login --------------
	list($temp_val['type'], $temp_val['data']) = $session->get($_SESSION['ezygold_sid_client']);
	$temp_val['username'] = $DB->escape($temp_val['data']);
	if ($temp_val['username'] != '') {
		list($temp_val['idusr']) = $DB->fetch("SELECT id FROM {$CONF['sql_prefix']}_users WHERE username = '{$temp_val['username']}'", __FILE__, __LINE__);
		
		// my referrals
		list($TMPLTEMP['usercptags_myreferrals']) = $DB->fetch("SELECT COUNT(*) FROM {$CONF['sql_prefix']}_usersplan WHERE idref = '{$temp_val['idusr']}' AND paymid = '{$PAYM['id']}'", __FILE__, __LINE__);
		// all referrals
		list($TMPLTEMP['usercptags_referrals']) = $DB->fetch("SELECT COUNT(*) FROM {$CONF['sql_prefix']}_usersplan WHERE dlist LIKE '%:{$temp_val['idusr']}|%' AND paymid = '{$PAYM['id']}'", __FILE__, __LINE__);
		// messages
		list($TMPLTEMP['usercptags_messages']) = $DB->fetch("SELECT COUNT(*) FROM {$CONF['sql_prefix']}_messenger WHERE (mfrom = '{$temp_val['idusr']}' && mto != '0') OR (mfrom != '0' && mto = '{$temp_val['idusr']}')", __FILE__, __LINE__);
		// unread messages
		list($TMPLTEMP['usercptags_unreadmessages']) = $DB->fetch("SELECT COUNT(*) FROM {$CONF['sql_prefix']}_messenger WHERE ((mfrom = '{$temp_val['idusr']}' && mto != '0') OR (mfrom != '0' && mto = '{$temp_val['idusr']}')) AND mread = '0'", __FILE__, __LINE__);
	}
	// ----------- end if ----------------
	
	$temp_val = '';
	$TMPL = array_merge($TMPL, $TMPLTEMP);
?>