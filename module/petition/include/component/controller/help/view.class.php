<?php

defined('PHPFOX') or exit('NO DICE!');

class Petition_Component_Controller_Help_View extends Phpfox_Component
{
	public function process()
	{		
		$iId = $this->request()->getInt('req3');

		$aItem = Phpfox::getService('petition.help')->getHelpForEdit($iId);
		
		if (!isset($aItem['help_id']))
		{			
			return Phpfox_Error::display(Phpfox::getPhrase('petition.the_petition_help_you_are_looking_for_cannot_be_found'));
		}
		$this->setParam(array('aHelp' => $aItem));
            
		$this->template()->setTitle($aItem['title'])
			->setBreadCrumb(Phpfox::getPhrase('petition.petitions_title'), $this->url()->makeUrl('petition.help'))			
			->setBreadCrumb($aItem['title'], $this->url()->permalink('petition.help', $aItem['help_id'], $aItem['title']), true)
			->setMeta('description', $aItem['title'] . '.')
			->setMeta('description', $aItem['content'] . '.')
			->setMeta('keywords', $this->template()->getKeywords($aItem['title']))	
			->assign(array(
					'aItem' => $aItem,
					'bIsViewHelp' => true
				)
			)
                  ->setHeader('cache', array(
                              'global.css' => 'module_petition',
				)
                  );		
	}

	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('petition.component_controller_help_view_clean')) ? eval($sPlugin) : false);
	}
}

?>