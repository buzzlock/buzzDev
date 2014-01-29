<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = 'defined(\'PHPFOX\') or exit(\'NO DICE!\');

if (Phpfox::isModule(\'socialpublishers\'))
{
    $sType = \'event\';
    $sIdCache = Phpfox::getLib(\'cache\')->set("socialpublishers_feed_" . Phpfox::getUserId());
    $aShareFeedInsert = array(
        \'sType\' => $sType,
        \'iItemId\' => $iId,
        \'bIsCallback\' => false,
        \'aCallback\' => null,
        \'iPrivacy\' => (int) $aVals[\'privacy\'],
        \'iPrivacyComment\' => (int) (isset($aVals[\'privacy_comment\']) ? (int) $aVals[\'privacy_comment\'] : 0),
    );
    Phpfox::getLib(\'cache\')->save($sIdCache, $aShareFeedInsert);
    
    $iUserId = Phpfox::getService(\'socialpublishers\')->getRealUser(Phpfox::getUserId());

    $aData = array();
    $aData[\'url\'] = Phpfox::getLib(\'url\')->permalink(\'event\', $iId, $aVals[\'title\']);
    $aData[\'text\'] = (empty($aVals[\'description\']) ? null : $oParseInput->clean($aVals[\'description\']));
    $aData[\'content\'] = (empty($aVals[\'description\']) ? null : $oParseInput->clean($aVals[\'description\']));
    $aData[\'title\'] = $oParseInput->clean($aVals[\'title\']);
    
    Phpfox::getService(\'socialpublishers\')->showPublisher($sType, $iUserId, $aData);
} '; ?>