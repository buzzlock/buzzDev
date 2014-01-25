<?php
defined('PHPFOX') or exit('NO DICE!');
function younetcore_install()
{
	$sTable = Phpfox::getT('younetcore_install');
    Phpfox::log("Start create table $sTable");    
	$sql = "CREATE TABLE IF NOT EXISTS `$sTable` (
        `token` text NOT NULL,
        `params` text NOT NULL,
        `id` int(11) NOT NULL AUTO_INCREMENT,
        PRIMARY KEY (`id`)		
	) ENGINE=InnoDB AUTO_INCREMENT=1;";
	Phpfox::getLib('phpfox.database')->query($sql);
    Phpfox::log("End create table $sTable");    
}

function younetcore_license()
{
	$sTable = Phpfox::getT('younetcore_license');
	Phpfox::log("Start create table $sTable");    
    $sql = "CREATE TABLE IF NOT EXISTS `$sTable` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(100) NOT NULL,
        `title` text NOT NULL,
        `descriptions` text,
        `type` varchar(50) NOT NULL,
        `current_version` varchar(50) NOT NULL,
        `lasted_version` varchar(50) NOT NULL,
        `is_active` int(1) DEFAULT '0',
        `date_active` int(11) DEFAULT NULL,
        `params` text,
        `download_link` varchar(500) DEFAULT NULL,
        `demo_link` varchar(500) DEFAULT NULL,
        PRIMARY KEY (`id`)
	) ENGINE=InnoDB AUTO_INCREMENT=1;";
	Phpfox::getLib('phpfox.database')->query($sql);
    Phpfox::log("End create table $sTable");
}

function younetcore_other()
{	
    
}

younetcore_install();
younetcore_license();
younetcore_other();
?>