<?php
$oDb = Phpfox::getLib('database');
if (!$oDb->isField(Phpfox::getT('m2bmusic_album'), 'server_id'))
{
    $oDb->query('ALTER TABLE `' . Phpfox::getT('m2bmusic_album') . '` ADD COLUMN `server_id` INT(11) UNSIGNED NOT NULL AFTER `album_id`;');
}
if (!$oDb->isField(Phpfox::getT('m2bmusic_playlist'), 'server_id'))
{
    $oDb->query('ALTER TABLE `' . Phpfox::getT('m2bmusic_playlist') . '` ADD COLUMN `server_id` INT(11) UNSIGNED NOT NULL AFTER `playlist_id`;');
}
if (!$oDb->isField(Phpfox::getT('m2bmusic_singer'), 'server_id'))
{
    $oDb->query('ALTER TABLE `' . Phpfox::getT('m2bmusic_singer') . '` ADD COLUMN `server_id` INT(11) UNSIGNED NOT NULL AFTER `singer_id`;');
}
if (!$oDb->isField(Phpfox::getT('m2bmusic_album_song'), 'server_id'))
{
    $oDb->query('ALTER TABLE `' . Phpfox::getT('m2bmusic_album_song') . '` ADD COLUMN `server_id` INT(11) UNSIGNED NOT NULL AFTER `song_id`;');
}
?>
