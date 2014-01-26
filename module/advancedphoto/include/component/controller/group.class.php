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
 * @version 		$Id: group.class.php 1306 2009-12-09 05:05:18Z Raymond_Benc $
 */
class Advancedphoto_Component_Controller_Group extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		Phpfox::getUserParam('advancedphoto.can_view_photos', true);
		
		$aGroup = $this->getParam('aGroup');		
		
		if (!Phpfox::getService('group')->hasAccess($aGroup['group_id'], 'can_use_photo'))
		{
			return Phpfox_Error::display(Phpfox::getPhrase('advancedphoto.photo_section_is_closed'));
		}		
		
		$this->setParam('aCallback', array(
				'group_id' => $aGroup['group_id'],
				'url_home' => 'group.' . $aGroup['title_url'] . '.photo',
				'url_home_array' => array(
					'group',
					array(
						$aGroup['title_url']							
					)
				),
				'title' => $aGroup['title']	
			)
		);		
		
		if ($this->request()->get('req4') == 'view')
		{			
			return Phpfox::getLib('module')->setController('advancedphoto.view');	
		}		
		elseif ($this->request()->get('req4') == 'upload')
		{
			$this->url()->send('advancedphoto.upload', array('module' => 'group', 'item' => $aGroup['group_id']));
		}
		
		$this->template()->removeUrl('advancedphoto.index', 'advancedphoto.battle');
		$this->template()->removeUrl('advancedphoto.index', 'advancedphoto.rate');
		$this->template()->removeUrl('advancedphoto.index', 'profile.photo');
		$this->template()->removeUrl('advancedphoto.index', 'advancedphoto.public-album');
		$this->template()->rebuildMenu('advancedphoto.index', array(
					'group',
					array(
						$aGroup['title_url']							
					)
				)
			);
		
		return Phpfox::getLib('module')->setController('advancedphoto.index');
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('advancedphoto.component_controller_group_clean')) ? eval($sPlugin) : false);
	}
}

?>