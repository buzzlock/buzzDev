<?php

defined('PHPFOX') or exit('NO DICE!');

/**
 * update DATABASE For version 3.02p1
 * @by AnNT
 *
 */
function db_install_302p1()
{
    $oDb = Phpfox::getLib('database');

    if (!$oDb->isField(Phpfox::getT('advancedmarketplace'), 'total_dislike'))
    {
        $oDb->query("ALTER TABLE `" . Phpfox::getT('advancedmarketplace') . "`
        ADD `total_dislike` int(10) unsigned NOT NULL DEFAULT '0' AFTER `total_like` ");
    }

    if (!$oDb->isField(Phpfox::getT('advancedmarketplace'), 'is_notified'))
    {
        $oDb->query("ALTER TABLE `" . Phpfox::getT('advancedmarketplace') . "`
        ADD `is_notified` tinyint(1) NOT NULL DEFAULT '0' AFTER `auto_sell` ");
    }
}

db_install_302p1();
