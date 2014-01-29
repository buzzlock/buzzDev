<?php

function alter_table()
{
    $sTable = Phpfox::getT('socialstream_setting');
    $oDb = Phpfox::getLib('phpfox.database');

    $oDb->query("ALTER TABLE `".$sTable."` MODIFY `lastfeed_timestamp` varchar(100) DEFAULT '0';");
}

alter_table();
