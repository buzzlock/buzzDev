<?php

defined('PHPFOX') or exit('NO DICE!');

function plugin_YouNet_Feed_Hooking()
{
    $sTable = Phpfox::getT('plugin');
    $aRow = Phpfox::getLib('phpfox.database')->select('*')
            ->from($sTable)
            ->where('module_id = "core" AND product_id = "phpfox" AND call_name = "feed.component_block_display_process" AND title = "YouNet Feed Hooking"')
            ->execute('getRows');

    if (empty($aRow))
    {
        $sql = "INSERT IGNORE INTO `" . phpfox::getT('plugin') . "` (`module_id`, `product_id`, `call_name`, `title`, `php_code`, `is_active`, `ordering`) VALUES ('core', 'phpfox', 'feed.component_block_display_process', 'YouNet Feed Hooking', '<?php \r\n\r\nif(!Phpfox::isModule(''socialstream'') && !Phpfox::isModule(''wall''))\r\n{ \r \n \$iTotalFeeds = (int) Phpfox::getComponentSetting((\$bIsProfile > 0 ?  \$iUserId : Phpfox::getUserId() ), ''feed.feed_display_limit_'' . (\$bIsProfile > 0 ? ''profile'' : ''dashboard''), Phpfox::getParam(''feed.feed_display_limit''));			\r\n\$iCount = Phpfox::getLib(''database'')->select(''count(*)'')->from(Phpfox::getT(''feed''))->execute(''getSlaveField'');\r\n while(empty(\$aRows) && (\$iFeedPage*\$iTotalFeeds) < \$iCount - \$iTotalFeeds)\r\n		{\r\n			\$iFeedPage++;\r\n			\$aRows = Phpfox::getService(''feed'')->callback(\$aFeedCallback)->get((\$bIsProfile > 0 ? \$iUserId : null), (\$this->request()->get(''feed'') ? \$this->request()->get(''feed'') : null), \$iFeedPage);\r\n		}\r\n		\r\n		if ((\$this->request()->getInt(''status-id'') \r\n				|| \$this->request()->getInt(''comment-id'') \r\n				|| \$this->request()->getInt(''link-id'')\r\n				|| \$this->request()->getInt(''poke-id'')\r\n			) \r\n			&& isset(\$aRows[0]))\r\n		{\r\n			\$aRows[0][''feed_view_comment''] = true;\r\n			\$this->setParam(''aFeed'', array_merge(array(''feed_display'' => ''view'', ''total_like'' => \$aRows[0][''feed_total_like'']), \$aRows[0]));	\r\n		}	\r\n\r\n}\r\n?>', 1, 0);";
        Phpfox::getLib('phpfox.database')->query($sql);
    }
}

function alter_socialstream_feeds()
{
    $oDb = Phpfox::getLib('phpfox.database');
    $sTable = Phpfox::getT('socialstream_feeds');

    $sql = "ALTER TABLE `" . $sTable . "` CHANGE `image_url` `image_url` MEDIUMTEXT NULL";
    $oDb->query($sql);
}

plugin_YouNet_Feed_Hooking();
alter_socialstream_feeds();
?>
