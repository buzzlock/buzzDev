<?php


defined('PHPFOX') or exit('NO DICE!');


class MobileTemplate_Component_Block_Sharebookmark extends Phpfox_Component
{
    
    public function process()
    {
        static $aBookmarks = array();
        
        if (!($sType = $this->getParam('type')))
        {
            
        }
        
        if (!$aBookmarks)
        {
            $aBookmarks = Phpfox::getService('share')->getType('bookmark');
        }
        if (!is_array($aBookmarks))
        {
            $aBookmarks = array();
        }
        $sTitle = html_entity_decode($this->getParam('title'), null, 'UTF-8');
        
        foreach ($aBookmarks as $iKey => $aBookmark)
        {
            $aBookmarks[$iKey]['url'] = str_replace(array(
                '{URL}',
                '{TITLE}'
            ),
            array(
                urlencode($this->getParam('url')),
                urlencode($sTitle)
            ), $aBookmark['url']);
        }
        
        $aPostBookmarks = Phpfox::getService('share')->getType('post');
        
        foreach ($aPostBookmarks as $iKey => $aBookmark)
        {
            $aPostBookmarks[$iKey]['url'] = str_replace(array(
                '{URL}',
                '{TITLE}'
            ),
            array(
                urlencode($this->getParam('url')),
                urlencode($sTitle)
            ), $aBookmark['url']);
        }       

        $this->template()->assign(array(
                'sType' => $sType,
                'aBookmarks' => $aBookmarks,
                'aPostBookmarks' => $aPostBookmarks,
                'sUrlStaticImage' => Phpfox::getParam('share.url_image')
            )
        );          
    }
    
    /**
     * Garbage collector. Is executed after this class has completed
     * its job and the template has also been displayed.
     */
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('mobiletemplate.component_block_sharebookmark_clean')) ? eval($sPlugin) : false);
    }
}

?>