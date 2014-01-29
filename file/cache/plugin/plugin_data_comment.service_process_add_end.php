<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = 'if($aVals[\'type\'] == \'socialstream\' && Phpfox::isModule(\'socialstream\') && Phpfox::isModule(\'socialbridge\'))
{
    $aFeed = $this->database()->select(\'f.service_feed_id, f.social_agent_id, s.name as service\')
        ->from(Phpfox::getT(\'socialstream_feeds\'), \'f\')
        ->join(Phpfox::getT(\'socialstream_services\'), \'s\', \'s.service_id = f.service_id\')
        ->where(\'f.feed_id = \'.$aVals[\'item_id\'])
        ->execute(\'getSlaveRow\');
    
    $sComment = Phpfox::getService(\'socialstream.services\')->replaceUsertag($aVals[\'text\']);
    
    if($aFeed[\'service\'] == \'facebook\')
    {
        Phpfox::getService(\'socialbridge.provider.facebook\')->comments($sComment, $aFeed[\'service_feed_id\']);
    }
    
    if($aFeed[\'service\'] == \'twitter\')
    {
        $sComment = \'@\'.$aFeed[\'social_agent_id\'].\' \'.$sComment;
        Phpfox::getService(\'socialbridge.provider.twitter\')->statusesUpdate($sComment, $aFeed[\'service_feed_id\']);
    }
} '; ?>