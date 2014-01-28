<?php


defined('PHPFOX') or exit('NO DICE!');

class MobileTemplate_Component_Block_Shareframepost extends Phpfox_Component
{
    
    public function process()
    {
		static $aBookmarks = array();
		if (empty($aBookmarks))
		{
			$aBookmarks = Phpfox::getService('share')->getType();
		}
		if (!is_array($aBookmarks))
		{
			$aBookmarks = array();
		}
		
		$this->template()->assign(array(
				'sBookmarkType' => $this->getParam('type'),
				'sBookmarkUrl' => $this->getParam('url'),
				'sBookmarkTitle' => $this->getParam('title'),
				'bShowSocialBookmarks' => count($aBookmarks) > 0,
				'iFeedId' => ((Phpfox::hasCallback($this->request()->get('sharemodule'), 'canShareItemOnFeed')) ? $this->request()->getInt('feed_id') : 0),
				'sShareModule' => $this->request()->get('sharemodule'), 
				'frame' => $this->request()->get('frame')
			)
		);		
    }
    
    /**
     * Garbage collector. Is executed after this class has completed
     * its job and the template has also been displayed.
     */
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('mobiletemplate.component_block_shareframepost_clean')) ? eval($sPlugin) : false);
    }
}

?>