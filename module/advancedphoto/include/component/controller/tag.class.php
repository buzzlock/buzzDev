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
 * @version 		$Id: controller.class.php 103 2009-01-27 11:32:36Z Raymond_Benc $
 */
class Advancedphoto_Component_Controller_Tag extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		Phpfox::getUserParam('advancedphoto.can_view_photos', true);
		
		if ($sTag = $this->request()->get('req3'))
		{			
			return Phpfox::getLib('module')->setController('advancedphoto.index');
		}		
		
		$this->template()->setTitle(Phpfox::getPhrase('advancedphoto.photo_tags'))
			->setBreadcrumb(Phpfox::getPhrase('advancedphoto.photo'), $this->url()->makeUrl('advancedphoto'))
			->setBreadcrumb(Phpfox::getPhrase('advancedphoto.tags'), $this->url()->makeUrl('advancedphoto.tag'), true);		
		
		$this->setParam('iTagDisplayLimit', 75);
		$this->setParam('bNoTagBlock', true);
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('advancedphoto.component_controller_tag_clean')) ? eval($sPlugin) : false);
	}
}

?>