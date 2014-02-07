<?php
	// detect members status
	$TMPLTEMP = array();
	$TMPLTEMP['client_activestatus_show'] = "style='display: none;'";
	list($type, $data) = $session->get($_SESSION['ezygold_sid_client']);
	$client_activestatus_show_un = $DB->escape($data);
	list($TMPLTEMP_active) = $DB->fetch("SELECT {$CONF['sql_prefix']}_usersplan.active FROM {$CONF['sql_prefix']}_users, {$CONF['sql_prefix']}_usersplan WHERE {$CONF['sql_prefix']}_users.username = '$client_activestatus_show_un' AND {$CONF['sql_prefix']}_users.id = {$CONF['sql_prefix']}_usersplan.idmbr", __FILE__, __LINE__);
	if ($TMPLTEMP_active == '1') $TMPLTEMP['client_activestatus_show'] = "";
	$TMPL = array_merge($TMPL, $TMPLTEMP);

	// demo add-on
	$TMPL['test_addon'] = 'Test Addon';
?>