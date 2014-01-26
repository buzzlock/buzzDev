<?php

defined('PHPFOX') or exit('NO DICE!');

/**
 * create DATABASE For version 3.01p4
 * @by datlv
 *
 */
function ynam_install301p4() {
    $oDatabase = Phpfox::getLib('database');

    if(!$oDatabase->isField(PHPFOX::getT("advancedmarketplace"),'gmap'))
    {
        $oDatabase->query("ALTER TABLE `" . Phpfox::getT("advancedmarketplace") . "` ADD COLUMN `gmap` varchar(255) DEFAULT NULL;");
    }
    if(!$oDatabase->isField(PHPFOX::getT("advancedmarketplace"),'address'))
    {
        $oDatabase->query("ALTER TABLE `" . Phpfox::getT("advancedmarketplace") . "` ADD COLUMN `address` varchar(255) DEFAULT NULL;");
    }
    if(!$oDatabase->isField(PHPFOX::getT("advancedmarketplace"),'lat'))
    {
        $oDatabase->query("ALTER TABLE `" . Phpfox::getT("advancedmarketplace") . "` ADD COLUMN `lat` double NOT NULL;");
    }
    if(!$oDatabase->isField(PHPFOX::getT("advancedmarketplace"),'lng'))
    {
        $oDatabase->query("ALTER TABLE `" . Phpfox::getT("advancedmarketplace") . "` ADD COLUMN `lng` double NOT NULL;");
    }
    if(!$oDatabase->isField(PHPFOX::getT("advancedmarketplace"),'gmap_address'))
    {
        $oDatabase->query("ALTER TABLE `" . Phpfox::getT("advancedmarketplace") . "` ADD COLUMN `gmap_address` varchar(255) DEFAULT NULL;");
    }
    if(!$oDatabase->isField(PHPFOX::getT("advancedmarketplace"),'location'))
    {
        $oDatabase->query("ALTER TABLE `" . Phpfox::getT("advancedmarketplace") . "` ADD COLUMN `location` varchar(255) DEFAULT NULL;");
    }

    $oDatabase->query("CREATE TABLE IF NOT EXISTS `" . Phpfox::getT("advancedmarketplace_setting") . "`
    (
        `var_name` varchar(255) NOT NULL,
        `value` varchar(250) NOT NULL
    ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ;");

}

ynam_install301p4();

?>