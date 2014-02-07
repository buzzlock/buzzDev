<?php
	$TMPLTEMP = array();
	$ADDON_DIR_ROOT = dirname(__FILE__);
	require_once($ADDON_DIR_ROOT."/addon_randomsites.cfg.php");
	$TMPLTEMP['addon_randomsites'] = '';
	$addon_randomsites_thumbnail = intval($addon_randomsites_thumbnail);
	if ($addon_randomsites_show_status < 1) $addon_randomsites_show_status = 'all';
	if ($addon_randomsites_show < 1) $addon_randomsites_show = 5;
	
	$addon_qry_stspth = "active = '1' AND ";
	if ($addon_randomsites_show_status > 1) $addon_qry_stspth = "active != '1' AND ";
	if ($addon_randomsites_show_status == 'all') $addon_qry_stspth = "";
	
	$tbl_bgcolor = '';
	$tr_classcolor = '';
	if ($addon_randomsites_thumbnail == 1) {
		$tbl_bgcolor = ' bgcolor="#DDDDDD"';
		$tr_classcolor = ' class="lightbg"';
	}

	$TMPLTEMP['addon_randomsites'] .= '<table'.$tbl_bgcolor.' width="80%" border="0" cellspacing="2" cellpadding="4">';
	$result = $DB->select_limit("SELECT id, fullname, username, email, url, title, description, category, site_image FROM {$CONF['sql_prefix']}_users, {$CONF['sql_prefix']}_usersplan WHERE ".$addon_qry_stspth."id = idmbr AND url != '' ORDER BY RAND()", $addon_randomsites_show, 0, __FILE__, __LINE__);
	$addon_qry_count = 1;
	while (list($id, $fullname, $username, $email, $url, $title, $description, $category, $site_image) = $DB->fetch_array($result)) {
		$url_title = "<b><a href='$url' target='_blank' title='$title'>$title</a></b>";
		$TMPLTEMP['addon_randomsites'] .= '<tr'.$tr_classcolor.'>';
		if ($addon_randomsites_thumbnail == 1) {
			if ($site_image == '') $site_image = "http://open.thumbshots.org/image.pxf?url=$url";
			$TMPLTEMP['addon_randomsites'] .= '<td width="120" align="center" valign="top"><img src="'.$site_image.'" border=0 alt="'.$title.'"></td>';
		}
		$TMPLTEMP['addon_randomsites'] .= '    <td valign="top">'.$url_title.'<br /><font size="1" color="#999999"><em>Site Category: '.$category.'</em></font><br />'.$description.'</td>';
		$TMPLTEMP['addon_randomsites'] .= '</tr>';

		$addon_qry_count++;
	}
	$TMPLTEMP['addon_randomsites'] .= '</table>';
	$TMPL = array_merge($TMPL, $TMPLTEMP);	
?>