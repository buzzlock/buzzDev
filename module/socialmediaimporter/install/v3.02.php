<?php
defined('PHPFOX') or exit('NO DICE!');
function socialmediaimporter_agents()
{
	$sTable = Phpfox::getT('socialmediaimporter_agents');
	$sql = "CREATE TABLE IF NOT EXISTS `$sTable` (
		`agent_id` int(11) NOT NULL AUTO_INCREMENT,
		`user_id` int(11) unsigned NOT NULL,
		`identity` varchar(128) NOT NULL,
		`service_id` int(11) unsigned NOT NULL,
		`ordering` int(11) unsigned NOT NULL,
		`token` text,
		`params` text,
		`full_name` varchar(255) DEFAULT NULL,
		`user_name` varchar(75) NOT NULL,
		`img_url` varchar(255) NOT NULL,
		`privacy` int(1) unsigned NOT NULL DEFAULT '3',
		`cron_id` int(10) unsigned NOT NULL DEFAULT '0',
		`last_feed` varchar(100) NOT NULL DEFAULT '0',
		PRIMARY KEY (`agent_id`)
	);";
	Phpfox::getLib('phpfox.database')->query($sql);
}

function socialmediaimporter_queue()
{
	$sTable = Phpfox::getT('socialmediaimporter_queue');
	$sql = "CREATE TABLE IF NOT EXISTS `$sTable` (
		`queue_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		`album_ids` varchar(255) DEFAULT NULL,
		`temp_album_ids` varchar(255) DEFAULT NULL,
		`service_name` varchar(50) DEFAULT NULL,
		`user_id` int(11) unsigned NOT NULL,
		`status` int(11) NOT NULL DEFAULT '0',
		`total_media` int(11) DEFAULT '0',
		`total_success` int(11) DEFAULT '0',
		`total_fail` int(11) DEFAULT '0',
		`total_like` int(11) DEFAULT '0',
		`privacy` int(11) DEFAULT '0',
		`privacy_comment` int(11) DEFAULT '0',
		`privacy_list` text,
		`time_stamp` int(11) unsigned NOT NULL,
		`error_code` int(11) DEFAULT '0',
		`feed_photo_ids` varchar(255) DEFAULT NULL,
		PRIMARY KEY (`queue_id`)
	);";
	Phpfox::getLib('phpfox.database')->query($sql);
}

function socialmediaimporter_queue_media()
{
	$sTable = Phpfox::getT('socialmediaimporter_queue_media');
	$sql = "CREATE TABLE IF NOT EXISTS `$sTable` (
		`queue_media_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		`album_id` varchar(100) DEFAULT NULL,
		`media_id` varchar(100) DEFAULT NULL COMMENT 'photo_id, mucsic_id, blog_id form FB, ...',
		`is_cover` int(11) DEFAULT '0',
		`media_path` varchar(255) NOT NULL,
		`media_type` varchar(50) DEFAULT NULL,
		`status` varchar(128) DEFAULT NULL,
		`time_stamp` int(11) DEFAULT NULL,
		`queue_id` int(11) unsigned NOT NULL,
		PRIMARY KEY (`queue_media_id`)
	);";
	Phpfox::getLib('phpfox.database')->query($sql);
}

function socialmediaimporter_services()
{
	$sTable = Phpfox::getT('socialmediaimporter_services');
	$sql = "CREATE TABLE IF NOT EXISTS `$sTable` (
		`service_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		`name` varchar(32) NOT NULL,
		`title` varchar(128) NOT NULL,
		`privacy` tinyint(1) NOT NULL DEFAULT '0',
		`connect` int(11) NOT NULL DEFAULT '0',
		`protocol` varchar(32) NOT NULL DEFAULT 'openid',
		`mode` varchar(32) NOT NULL DEFAULT 'popup',
		`w` int(11) NOT NULL DEFAULT '800',
		`h` int(11) NOT NULL DEFAULT '450',
		`ordering` int(11) NOT NULL DEFAULT '0',
		`is_active` int(11) NOT NULL DEFAULT '1',
		`params` text,
		PRIMARY KEY (`service_id`)
	);";
	Phpfox::getLib('phpfox.database')->query($sql);
	Phpfox::getLib('phpfox.database')->update($sTable, array('name' => 'instagram', 'title' => 'Instagram'), "name='instagr'");

	$aInserts['facebook'] = array ('name' => 'facebook',  'title' => 'Facebook', 'privacy' => 1, 'connect' => 1, 'mode' => 'auth', 'w' => 800, 'h' => 450, 'ordering' => 1, 'is_active' => 1, 'params' => '');
	$aInserts['flickr'] = array ('name' => 'flickr',  'title' => 'Flickr', 'privacy' => 1, 'connect' => 1, 'mode' => 'auth', 'w' => 800, 'h' => 450, 'ordering' => 1, 'is_active' => 1, 'params' => '');
	$aInserts['instagram'] = array ('name' => 'instagram',  'title' => 'Instagram', 'privacy' => 1, 'connect' => 1, 'mode' => 'auth', 'w' => 800, 'h' => 450, 'ordering' => 1, 'is_active' => 1, 'params' => '');
	$aInserts['picasa'] = array ('name' => 'picasa',  'title' => 'Picasa', 'privacy' => 1, 'connect' => 1, 'mode' => 'auth', 'w' => 800, 'h' => 450, 'ordering' => 1, 'is_active' => 1, 'params' => '');

	$aRows = Phpfox::getLib('phpfox.database')->select('*')
		->from($sTable)
		->execute('getRows');

	foreach ($aInserts as $sService => $aInsert)
	{
		$bIsExist = false;
		foreach($aRows as $i => $aRow)
		{
			if ($aRow['name'] == $sService)
			{
				$bIsExist = true;
				break;
			}
		}
		if ($bIsExist == false)
		{
			Phpfox::getLib('phpfox.database')->insert($sTable, $aInsert);
		}
	}
}

function socialmediaimporter_tracking()
{
	$sTable = Phpfox::getT('socialmediaimporter_tracking');
	$sql = "CREATE TABLE IF NOT EXISTS `$sTable` (
        `track_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `user_id` int(11) unsigned NOT NULL,
        `service_name` varchar(50) DEFAULT NULL,
        `type_id` varchar(128) NOT NULL,
        `fid` varchar(32) NOT NULL,
        `id` int(10) unsigned NOT NULL,
        PRIMARY KEY (`track_id`)
	)";
	Phpfox::getLib('phpfox.database')->query($sql);
	/** For 3.02 **/
    if (!Phpfox::getLib('phpfox.database')->isField($sTable, 'service_name'))
	{
		Phpfox::getLib('phpfox.database')->query("ALTER TABLE `$sTable` ADD COLUMN service_name varchar(50) DEFAULT NULL");
	}
    /** For 3.02p1 **/
    $sql = "ALTER TABLE `$sTable` MODIFY COLUMN	`service_name` VARCHAR(50) DEFAULT NULL;";
	Phpfox::getLib('phpfox.database')->query($sql);
}

function socialmediaimporter_album()
{
	$sTable = Phpfox::getT('socialmediaimporter_album');
	$sql = "CREATE TABLE IF NOT EXISTS `$sTable` (
        `album_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `user_id` int(11) DEFAULT NULL,
        `name` varchar(255) DEFAULT NULL,
        `description` varchar(255) DEFAULT NULL,
        `privacy` int(11) unsigned NOT NULL,
        `privacy_comment` int(11) NOT NULL DEFAULT '0',
        `privacy_list` text,
        `time_stamp` int(11) unsigned NOT NULL,
        PRIMARY KEY (`album_id`)
	);";
	Phpfox::getLib('phpfox.database')->query($sql);
}

function socialmediaimporter_other()
{
	Phpfox::getLib('phpfox.database')->delete(Phpfox::getT('component'), 'product_id = "socialmediaimporter" AND module_id = "socialmediaimporter" AND component NOT IN ("index", "services")');
	Phpfox::getLib('phpfox.database')->delete(Phpfox::getT('block'), 'product_id = "socialmediaimporter" AND module_id = "socialmediaimporter" AND component NOT IN ("services")');
	Phpfox::getLib('phpfox.database')->delete(Phpfox::getT('setting'), 'product_id = "socialmediaimporter" AND module_id = "socialmediaimporter" AND var_name NOT IN ("default_privacy", "display_limit", "max_import_per_time")');
	Phpfox::getLib('phpfox.database')->delete(Phpfox::getT('user_group_setting'), 'product_id = "socialmediaimporter" AND module_id = "socialmediaimporter" AND name NOT IN ("enable_facebook", "enable_flickr", "enable_picasa", "enable_instagram")');
}

function socialmediaimporter_update_v302p1() {
	$sDefaultCharSet = PHPFOX::getLib("database")
		->select("@@GLOBAL.character_set_database")
		->execute("getField");
	$sDefaultCollate = PHPFOX::getLib("database")
		->select("@@GLOBAL.collation_database")
		->execute("getField");
	
	$aAlterTableCols = array();
	$aAlterTableCols[] = array("table" => PHPFOX::getT("socialmediaimporter_agents"), "col" => "`identity`", "data" => "varchar(128)", "infor" => "NOT NULL");
	$aAlterTableCols[] = array("table" => PHPFOX::getT("socialmediaimporter_agents"), "col" => "`token`", "data" => "text", "infor" => "");
	$aAlterTableCols[] = array("table" => PHPFOX::getT("socialmediaimporter_agents"), "col" => "`params`", "data" => "text", "infor" => "");
	$aAlterTableCols[] = array("table" => PHPFOX::getT("socialmediaimporter_agents"), "col" => "`full_name`", "data" => "varchar(255)", "infor" => "DEFAULT NULL");
	$aAlterTableCols[] = array("table" => PHPFOX::getT("socialmediaimporter_agents"), "col" => "`user_name`", "data" => "varchar(75)", "infor" => "NOT NULL");
	$aAlterTableCols[] = array("table" => PHPFOX::getT("socialmediaimporter_agents"), "col" => "`img_url`", "data" => "varchar(255)", "infor" => "NOT NULL");
	$aAlterTableCols[] = array("table" => PHPFOX::getT("socialmediaimporter_agents"), "col" => "`last_feed`", "data" => "varchar(100)", "infor" => "NOT NULL DEFAULT '0'");
	
	$aAlterTableCols[] = array("table" => PHPFOX::getT("socialmediaimporter_queue"), "col" => "`album_ids`", "data" => "varchar(255)", "infor" => "DEFAULT NULL");
	$aAlterTableCols[] = array("table" => PHPFOX::getT("socialmediaimporter_queue"), "col" => "`temp_album_ids`", "data" => "varchar(255)", "infor" => "DEFAULT NULL");
	$aAlterTableCols[] = array("table" => PHPFOX::getT("socialmediaimporter_queue"), "col" => "`service_name`", "data" => "varchar(50)", "infor" => "DEFAULT NULL");
	// $aAlterTableCols[] = array("table" => PHPFOX::getT("socialmediaimporter_queue"), "col" => "`mode`", "data" => "varchar(32)", "infor" => " NOT NULL DEFAULT 'popup'");
	// $aAlterTableCols[] = array("table" => PHPFOX::getT("socialmediaimporter_queue"), "col" => "`params`", "data" => "text", "infor" => "");
	
	$aAlterTableCols[] = array("table" => PHPFOX::getT("socialmediaimporter_services"), "col" => "`protocol`", "data" => "varchar(32)", "infor" => "NOT NULL DEFAULT 'openid'");
	
	$aAlterTableCols[] = array("table" => PHPFOX::getT("socialmediaimporter_tracking"), "col" => "`service_name`", "data" => "varchar(50)", "infor" => "DEFAULT NULL");
	$aAlterTableCols[] = array("table" => PHPFOX::getT("socialmediaimporter_tracking"), "col" => "`type_id`", "data" => "varchar(128)", "infor" => "NOT NULL");
	$aAlterTableCols[] = array("table" => PHPFOX::getT("socialmediaimporter_tracking"), "col" => "`fid`", "data" => "varchar(32)", "infor" => "NOT NULL");
	
	$aAlterTableCols[] = array("table" => PHPFOX::getT("socialmediaimporter_album"), "col" => "`privacy_list`", "data" => "text", "infor" => "");
	$aAlterTableCols[] = array("table" => PHPFOX::getT("socialmediaimporter_album"), "col" => "`name`", "data" => "varchar(255)", "infor" => "DEFAULT NULL");
	$aAlterTableCols[] = array("table" => PHPFOX::getT("socialmediaimporter_album"), "col" => "`description`", "data" => "varchar(255)", "infor" => "DEFAULT NULL");
	
	$aAlterTables = array(
		PHPFOX::getT("socialmediaimporter_agents"),
		PHPFOX::getT("socialmediaimporter_services"),
		PHPFOX::getT("socialmediaimporter_queue"),
		PHPFOX::getT("socialmediaimporter_queue_media"),
		PHPFOX::getT("socialmediaimporter_tracking"),
		PHPFOX::getT("socialmediaimporter_album"),
	);
	
	foreach($aAlterTables as $table) {
		PHPFOX::getLib("database")->query("ALTER TABLE {$table} CHARACTER SET {$sDefaultCharSet} COLLATE {$sDefaultCollate}");
	}
	
	foreach($aAlterTableCols as $aColInf) {
		$sTable = $aColInf["table"];
		$sCol = $aColInf["col"];
		$sData = $aColInf["data"];
		$sInfor = $aColInf["infor"];

		echo "ALTER TABLE {$sTable} MODIFY COLUMN {$sCol} {$sData}  CHARACTER SET {$sDefaultCharSet} COLLATE {$sDefaultCollate} {$sInfor};" . "<br />";
		PHPFOX::getLib("database")->query("ALTER TABLE {$sTable} MODIFY COLUMN {$sCol} {$sData}  CHARACTER SET {$sDefaultCharSet} COLLATE {$sDefaultCollate} {$sInfor};");
	}

}

socialmediaimporter_agents();
socialmediaimporter_services();
socialmediaimporter_queue();
socialmediaimporter_queue_media();
socialmediaimporter_tracking();
socialmediaimporter_album();
socialmediaimporter_other();
socialmediaimporter_update_v302p1();
?>