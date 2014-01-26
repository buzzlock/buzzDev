<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = '$aFeedTypes["friend"] = Phpfox::getPhrase(\'wall.friends\');
if (Phpfox::isModule(\'photo\'))
{
	$aFeedTypes["photo"] = Phpfox::getPhrase(\'wall.photos\');
}
if (Phpfox::isModule(\'link\'))
{
	$aFeedTypes["link"] = Phpfox::getPhrase(\'wall.links\');
}
if (Phpfox::isModule(\'event\'))
{
	$aFeedTypes["event"] = Phpfox::getPhrase(\'wall.events\');
}
if (Phpfox::isModule(\'fevent\'))
{
	$aFeedTypes["fevent"] = Phpfox::getPhrase(\'wall.events\');
}
$aFeedTypes["feed_comment"] = Phpfox::getPhrase(\'wall.comments\');
if (Phpfox::isModule(\'marketplace\'))
{
	$aFeedTypes["marketplace"] = Phpfox::getPhrase(\'wall.marketplace\');
}
if (Phpfox::isModule(\'marketplace\'))
{
	$aFeedTypes["marketplace"] = Phpfox::getPhrase(\'wall.marketplace\');
}
if (Phpfox::isModule(\'poll\'))
{
	$aFeedTypes["poll"] = Phpfox::getPhrase(\'wall.polls\');
}
if (Phpfox::isModule(\'blog\'))
{
	$aFeedTypes["blog"] = Phpfox::getPhrase(\'wall.blogs\');
}
if (Phpfox::isModule(\'video\'))
{
	$aFeedTypes["video"] = Phpfox::getPhrase(\'wall.videos\');
}
if (Phpfox::isModule(\'music\'))
{
	$aFeedTypes["music_song"] = Phpfox::getPhrase(\'wall.music_songs\');
}
if (Phpfox::hasCallback(\'document\', \'getNewsFeed\'))
{
	$aFeedTypes["document"] = Phpfox::getPhrase(\'wall.documents\');
}
if (Phpfox::hasCallback(\'musicstore\', \'getNewsFeed\'))
{
	$aFeedTypes["musicstore_album"] = Phpfox::getPhrase(\'wall.musicstore_albums\');
	$aFeedTypes["musicstore_playlist"] = Phpfox::getPhrase(\'wall.musicstore_playlists\');
}
if (Phpfox::hasCallback(\'musicsharing\', \'getNewsFeed\'))
{
	$aFeedTypes["musicsharing_album"] = Phpfox::getPhrase(\'wall.musicsharing_albums\');
	$aFeedTypes["musicsharing_playlist"] = Phpfox::getPhrase(\'wall.musicsharing_playlists\');
} '; ?>