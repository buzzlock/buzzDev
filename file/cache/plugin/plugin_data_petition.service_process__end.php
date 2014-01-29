<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = 'defined(\'PHPFOX\') or exit(\'NO DICE!\');

if (Phpfox::isModule(\'socialpublishers\'))
{
    $sType = \'petition\';
    $sIdCache = Phpfox::getLib(\'cache\')->set("socialpublishers_feed_" . Phpfox::getUserId());
    $aShareFeedInsert = array(
        \'sType\' => $sType,
        \'iItemId\' => $iId,
        \'bIsCallback\' => isset($aCallback),
        \'aCallback\' => $aCallback,
        \'iPrivacy\' => (int) $aVals[\'privacy\'],
        \'iPrivacyComment\' => (int) (isset($aVals[\'privacy_comment\']) ? (int) $aVals[\'privacy_comment\'] : 0),
    );
    Phpfox::getLib(\'cache\')->save($sIdCache, $aShareFeedInsert);
    
    $iUserId = Phpfox::getService(\'socialpublishers\')->getRealUser(Phpfox::getUserId());

    $aData = array();
    $aData[\'url\'] = Phpfox::getLib(\'url\')->permalink(\'petition\', $iId, $sTitle);
    $aData[\'text\'] = $oFilter->clean($aVals[\'short_description\']);
    $aData[\'content\'] = $oFilter->clean($aVals[\'description\']);
    $aData[\'title\'] = $sTitle;
    
    Phpfox::getService(\'socialpublishers\')->showPublisher($sType, $iUserId, $aData, 4);
} '; ?>