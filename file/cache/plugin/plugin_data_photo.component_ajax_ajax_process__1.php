<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = 'if($aImages)
	{
		$aYnContestLastImage = end($aImages);
		if($aYnContestLastImage[\'photo_id\'] == $aPhoto[\'photo_id\'] )
		{
			Phpfox::getService(\'contest.contest\')->handlerAfterAddingEntry($sType = \'photo\', 
		$iPhotoItemId = $aPhoto[\'photo_id\'] );
		}
	} if (Phpfox::isModule(\'suggestion\') && Phpfox::isUser())
{
    if (Phpfox::getService(\'suggestion\')->isSupportModule(\'photo\') && Phpfox::getUserParam(\'suggestion.enable_friend_suggestion\'))
    {
        $_SESSION[\'suggestion\'][\'ajax\'] = true;
    }
} '; ?>