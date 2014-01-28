<?php

$this->setParam('aParentModule', array(
    'module_id' => 'pages',
    'item_id' => $aPage['page_id'],
    'url' => Phpfox::getService('pages')->getUrl($aPage['page_id'], $aPage['title'], $aPage['vanity_url']),
    'use_timeline' => (isset($aPage['use_timeline']) && $aPage['use_timeline'])
        )
);
?>
