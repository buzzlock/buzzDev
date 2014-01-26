<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * 
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package 		Phpfox_Component
 * @version 		$Id: battle.class.php 3626 2011-12-01 06:07:55Z Raymond_Benc $
 */
class Advancedphoto_Component_Controller_Battle extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		Phpfox::getUserParam('advancedphoto.can_view_photos', true);	
		Phpfox::getParam('advancedphoto.enable_photo_battle', true);
		
		if (($iWinner = $this->request()->getInt('w')) && ($iLoser = $this->request()->getInt('l')))
		{
			Phpfox::getService('advancedphoto.battle.process')->add($iWinner, $iLoser);
		}
		
		$sCategory = null;		
		if ($this->request()->get('req3') == 'category')
		{
			$sCategory = $this->request()->getInt('req4');
		}	
		
		$bFullMode = ($this->request()->get('mode') == 'full' ? true : false);
		$this->setParam('sPhotoCategorySubSystem', 'battle');
		$this->setParam('sCurrentCategory', $sCategory);
		
		$aPhotos = Phpfox::getService('advancedphoto.battle')->get($sCategory);
		
		Phpfox::getService('advancedphoto')->buildMenu();
		
		$this->template()->setTitle(Phpfox::getPhrase('advancedphoto.photo_battle'))
			->setBreadcrumb(Phpfox::getPhrase('advancedphoto.photos'), $this->url()->makeUrl('advancedphoto'))			
			->assign(array(
					'aPhotos' => $aPhotos,
					'bFullMode' => $bFullMode,
					'sImageHeight' => ($bFullMode ? '500' : '240'),
					'sMaxImageHeight' => ($bFullMode ? '400' : '240'),
					'aCallback' => null
				)
			)
			->setHeader('cache', array(
					'battle.css' => 'module_photo'
				)
			);
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('advancedphoto.component_controller_battle_clean')) ? eval($sPlugin) : false);
	}
}

?>