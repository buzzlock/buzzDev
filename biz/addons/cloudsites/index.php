<?php
	$TMPLTEMP = array();
	$ADDON_DIR_ROOT = dirname(__FILE__);
	require_once($ADDON_DIR_ROOT."/addon_cloudsites.cfg.php");
	$TMPLTEMP['addon_cloudsites'] = '';
	$addon_cloudsites_thumbnail = intval($addon_cloudsites_thumbnail);
	if ($addon_cloudsites_show_status < 1) $addon_cloudsites_show_status = 'all';
	if ($addon_cloudsites_show < 1) $addon_cloudsites_show = 5;
	
	$addon_qry_stspth = "active = '1'";
	if ($addon_cloudsites_show_status == 'all') $addon_qry_stspth = "isconfirm = '1'";
	
	$hintboxZ = array();
	$TMPLTEMP['addon_cloudsites'] .= '<table width="80%" border="0" cellspacing="2" cellpadding="4"><tr><td>';
	$result = $DB->select_limit("SELECT id, fullname, username, email, url, title, description, category, site_image FROM {$CONF['sql_prefix']}_users, {$CONF['sql_prefix']}_usersplan WHERE $addon_qry_stspth AND id = idmbr AND url != '' ORDER BY RAND()", $addon_cloudsites_show, 0, __FILE__, __LINE__);
	$addon_qry_count = 1;
	while (list($id, $fullname, $username, $email, $url, $title, $description, $category, $site_image) = $DB->fetch_array($result)) {
		$titlex = str2hint($title);
		$titlex = str_replace(" ", "&nbsp;", $title);
		$descriptionx = str_replace(chr(13), '<br />', $description);
		$hint_cloudsites = "<table width='100%' margin=0 cellpadding='2'><tr><td colspan='2' style='background-color:#CCC; font-weight: bold;'>$titlex</td></tr><tr>";
		if ($addon_cloudsites_thumbnail == 1) {
			if ($site_image == '') $site_image = "http://open.thumbshots.org/image.pxf?url=$url";
			$hint_cloudsites .= '<td width=120 align=center valign=top><img src='.$site_image.' border=0 alt='.$titlex.'></td>';
		}
		$hint_cloudsites .= "<td valign=top>$descriptionx</td>";
		$hint_cloudsites .= "</tr></table>";
		
		$is_b = 0;
		$is_i = 0;
		$is_u = 0;
		
		mt_srand(); $i = mt_rand(0, 12);
		if ($i < 4) $is_b = 1;
		mt_srand(); $i = mt_rand(10, 22);
		if ($i < 14) $is_i = 1;
		mt_srand(); $i = mt_rand(20, 32);
		if ($i < 24) $is_u = 1;
		
		// get font
		if (isset($addon_cloudsites_font) && $addon_cloudsites_font != '') {
			$addon_cloudsites_fontx = str_replace(" ", "", $addon_cloudsites_font);
			$addon_cloudsites_fontx = explode(",", $addon_cloudsites_fontx);
			$addon_cloudsites_fontx = array_unique($addon_cloudsites_fontx);
			srand((float) microtime() * 10000000);
			$i = array_rand($addon_cloudsites_fontx);
			$addon_cloudsites_fontx = $addon_cloudsites_fontx[$i];
		} else {
			$addon_cloudsites_fontx = 'Verdana';
		}

		// get size
		if (isset($addon_cloudsites_size) && $addon_cloudsites_size != '') {
			$addon_cloudsites_sizex = str_replace(" ", "", $addon_cloudsites_size);
			$addon_cloudsites_sizex = explode(",", $addon_cloudsites_sizex);
			$addon_cloudsites_sizex = array_unique($addon_cloudsites_sizex);
			srand((float) microtime() * 10000000);
			$i = array_rand($addon_cloudsites_sizex);
			$addon_cloudsites_sizex = $addon_cloudsites_sizex[$i];
		} else {
			$addon_cloudsites_sizex = '2';
		}

		// get color
		if (isset($addon_cloudsites_color) && $addon_cloudsites_color != '') {
			$addon_cloudsites_colorx = str_replace(" ", "", $addon_cloudsites_color);
			$addon_cloudsites_colorx = explode(",", $addon_cloudsites_colorx);
			$addon_cloudsites_colorx = array_unique($addon_cloudsites_colorx);
			srand((float) microtime() * 10000000);
			$i = array_rand($addon_cloudsites_colorx);
			$addon_cloudsites_colorx = $addon_cloudsites_colorx[$i];
		} else {
			$addon_cloudsites_colorx = '#666666';
		}

		
		$title_n_font = "<font face='$addon_cloudsites_fontx' size='$addon_cloudsites_sizex' color='$addon_cloudsites_colorx'>$title</font>";
		if ($is_b == 1) $title_n_font = "<strong>$title_n_font</strong>";
		if ($is_i == 1) $title_n_font = "<em>$title_n_font</em>";
		if ($is_u == 1) $title_n_font = "<u>$title_n_font</u>";
		
		$hintboxZ[$id] = $hint_cloudsites;

		$TMPLTEMP['addon_cloudsites'] .= <<<EndHTML
<a href="{$url}" target="_blank" title="{$title}" data-helphint="stickyhintboxZ{$id}">{$title_n_font}</a>, &nbsp;
EndHTML;

		$addon_qry_count++;
	}
	
    $addhintboxZ = '';
    foreach ($hintboxZ as $key => $val) {
        $addhintboxZ .= '<div id="stickyhintboxZ'.$key.'" class="hintbox"  style="width:300px">'.$val.'</div>';
    }

	$TMPLTEMP['addon_cloudsites'] .= ';-)</td></tr></table>';
	$TMPLTEMP['addon_cloudsites'] .= <<<EndHTML

		<div id="divhelphintku" class="helphintku">
		<div style="padding:3px">
		
		{$addhintboxZ}
		
		</div>
		
		<div class="stickystatus"></div>
		</div>
EndHTML;

	$TMPL = array_merge($TMPL, $TMPLTEMP);	
?>