<?php

defined('PHPFOX') or exit('NO DICE!');

function plugin_YouNet_Feed_Hooking_2() /* PLUGIN THAT FIX LOSING FEEDs */
{
    $sTable = Phpfox::getT('plugin');
    $aRow = Phpfox::getLib('phpfox.database')->select('*')
            ->from($sTable)
            ->where('module_id = "core" AND product_id = "phpfox" AND call_name = "init" AND title = "YouNet Feed Hooking"')
            ->execute('getRows');

    if (empty($aRow))
    {
        $sql = "INSERT INTO `".phpfox::getT('plugin')."` (`module_id`, `product_id`, `call_name`, `title`, `php_code`, `is_active`, `ordering`) VALUES
('core', 'phpfox', 'init', 'YouNet Feed Hooking', '<?php\r\n\$sCacheId = Phpfox::getLib(''cache'')->set(''373f8034a282365715a1d3a6f335bb31'');\r\n\r\nif (!Phpfox::getLib(''cache'')->get(\$sCacheId))\r\n{\r\n	\$aRow = Phpfox::getLib(''database'')->select(''value_actual'')->from(Phpfox::getT(''setting''))->where(''var_name LIKE \"checked_socialstream_feeds\"'')->execute(''getRow'');\r\n	\$isModuleSocialStream = Phpfox::isModule(''socialstream'');\r\n	\$oDb = Phpfox::getLib(''database'');\r\n	if(count(\$aRow) == 0)\r\n	{\r\n		\$aInsert = array(''module_id'' => ''admincp'',\r\n						 ''product_id'' => ''phpfox'',\r\n						 ''is_hidden'' => 1,\r\n						 ''version_id''=> ''2.0.0rc1'',\r\n						 ''type_id'' => ''boolean'',\r\n						 ''var_name'' => ''checked_socialstream_feeds'',\r\n						 ''phrase_var_name'' => \"Checked Social Stream Feeds\",\r\n						 ''value_actual'' => \$isModuleSocialStream ? 1 : 0,\r\n						 ''value_default'' => \$isModuleSocialStream ? 1 : 0 ,\r\n						 ''ordering'' => 1,\r\n		);		\r\n		\$oDb->insert(Phpfox::getT(''setting''),\$aInsert);\r\n		\$aRow = \$aInsert;\r\n	}\r\n	\r\n    \$isCheckedSocialStream = (int) \$aRow[''value_actual''];\r\n    \r\n    if (\$isModuleSocialStream && !\$isCheckedSocialStream) //enable && 0\r\n    {\r\n        \$sSQL1 = \"UPDATE `\" . Phpfox::getT(''feed'') . \"` SET `privacy` = `privacy` - 7,`feed_reference` = `feed_reference` -7  WHERE `type_id` LIKE ''socialstream_%'' AND `item_id` IS NOT NULL AND `feed_reference` IS NOT NULL AND `privacy` >= 7\";\r\n        Phpfox::getLib(''database'')->query(\$sSQL1);\r\n     \r\n		\$sSQL2 = \"UPDATE `\" . Phpfox::getT(''setting'') . \"` SET `value_actual` = ''1'',`value_default` = ''1'' WHERE `var_name` = ''checked_socialstream_feeds'' AND `module_id` = ''admincp''\";\r\n        Phpfox::getLib(''database'')->query(\$sSQL2);\r\n    }\r\n    else if (!\$isModuleSocialStream && \$isCheckedSocialStream) //disable && 1\r\n    {        \r\n        \$sSQL1 = \"UPDATE `\" . Phpfox::getT(''feed'') . \"` SET `privacy` = `privacy` + 7,`feed_reference` = `feed_reference` +7 WHERE `type_id` LIKE ''socialstream_%'' AND `item_id` IS NOT NULL AND `feed_reference` IS NOT NULL AND `privacy` < 7\";\r\n        Phpfox::getLib(''database'')->query(\$sSQL1);\r\n        \r\n		\$sSQL2 = \"UPDATE `\" . Phpfox::getT(''setting'') . \"` SET `value_actual` = ''0'',`value_default` = ''0'' WHERE `var_name` = ''checked_socialstream_feeds'' AND `module_id` = ''admincp''\";\r\n        Phpfox::getLib(''database'')->query(\$sSQL2);\r\n    }\r\n    Phpfox::getLib(''cache'')->save(\$sCacheId, true);\r\n}\r\n?>', 1, 0);";

        Phpfox::getLib('phpfox.database')->query($sql);
    }
}

plugin_YouNet_Feed_Hooking_2();
?>