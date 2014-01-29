<?php

defined('PHPFOX') or exit('NO DICE!');

function remove_YouNetFeedHooking()
{
    Phpfox::getLib('phpfox.database')->delete(Phpfox::getT('plugin'), 'module_id = "core" AND product_id = "phpfox" AND call_name = "feed.component_block_display_process" AND title = "YouNet Feed Hooking"');
}

remove_YouNetFeedHooking();

function update_blocks_order()
{
    $sTable = Phpfox::getT('block');
    $aBlock1 = Phpfox::getLib('phpfox.database')->select('ordering,location')
            ->from($sTable)
            ->where('m_connection = "core.index-member" AND module_id = "feed" AND product_id="phpfox" AND component="display"')
            ->execute('getRow');

    if (!empty($aBlock1))
    {
        Phpfox::getLib('phpfox.database')->query("UPDATE `" . $sTable . "` SET ordering = ordering + 2 WHERE location = '" . $aBlock1['location'] . "' AND m_connection ='core.index-member'");
        Phpfox::getLib('phpfox.database')->update($sTable, array('location' => (int) $aBlock1['location'], 'ordering' => ((int) 1)), 'm_connection = "core.index-member" AND module_id = "socialstream" AND product_id="socialstream"');
        Phpfox::getLib('phpfox.database')->update($sTable, array('location' => (int) $aBlock1['location'], 'ordering' => ((int) 0)), 'm_connection = "core.index-member" AND module_id = "contactimporter" AND product_id="contactimporter"');
    }

    $aBlock2 = Phpfox::getLib('phpfox.database')->select('ordering,location')
            ->from($sTable)
            ->where('m_connection = "profile.index" AND module_id = "socialstream" AND product_id="socialstream"')
            ->execute('getRow');

    if (!empty($aBlock2))
    {
        Phpfox::getLib('phpfox.database')->query("UPDATE `" . $sTable . "` SET ordering = ordering + 1 WHERE location = '" . $aBlock2['location'] . "' AND m_connection ='profile.index'");
        Phpfox::getLib('phpfox.database')->update($sTable, array('location' => (int) $aBlock2['location'], 'ordering' => ((int) 0)), 'm_connection = "profile.index" AND module_id = "socialstream" AND product_id="socialstream"');
    }
}

update_blocks_order();
?>