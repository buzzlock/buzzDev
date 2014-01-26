<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * Rate public photos controller.
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package 		Phpfox_Component
 * @version 		$Id: rate.class.php 2633 2011-05-30 13:57:44Z Raymond_Benc $
 */
class Advancedphoto_Component_Controller_Rate extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		Phpfox::getUserParam('advancedphoto.can_view_photos', true);
		
		if (!Phpfox::getParam('advancedphoto.can_rate_on_photos'))
		{
			return Phpfox_Error::display(Phpfox::getPhrase('advancedphoto.photo_rating_is_disabled'));
		}
		
		Phpfox::getUserParam('advancedphoto.can_rate_on_photos', true);
		
		if (($iPhotoId = $this->request()->getInt('photo-id')))
		{
			Phpfox::getService('advancedphoto.rate.process')->add($this->request()->getInt('photo-id'), $this->request()->getInt('rating'));			
		}
		
		$sCategory = null;		
		if ($this->request()->get('req3') == 'category')
		{
			$sCategory = $this->request()->getInt('req4');
		}
		
		(($sPlugin = Phpfox_Plugin::get('advancedphoto.component_controller_rate_process_start')) ? eval($sPlugin) : false);
		
		$aPhoto = Phpfox::getService('advancedphoto.rate')->getForRating($sCategory, $this->request()->get('id', null));		
	
		$sBar = '';
		for ($i = 1; $i <= 10; $i++)
		{			
			$sBar .= '<li><a href="' . ($sCategory === null ? $this->url()->makeUrl('advancedphoto.rate', array('photo-id' => $aPhoto['photo_id'], 'rating' => $i)) : $this->url()->permalink('advancedphoto.rate.category', $this->request()->getInt('req4'), $this->request()->get('req5'), false, null, array('photo-id' => $aPhoto['photo_id'], 'rating' => $i))) . '" class="js_rating_bar">' . $i . '</a></li>';
		}
		$sBar .= '<li><a href="' . ($sCategory === null ? $this->url()->makeUrl('advancedphoto.rate') : $this->url()->permalink('advancedphoto.rate.category', $this->request()->getInt('req4'), $this->request()->get('req5'))) . '">' . Phpfox::getPhrase('advancedphoto.skip') . '</a></li>';
		
		$this->setParam('sPhotoCategorySubSystem', 'rate');
		$this->setParam('aPhoto', $aPhoto);
		$this->setParam('sCurrentCategory', $sCategory);
		
		Phpfox::getService('advancedphoto')->buildMenu();
		
		$this->template()->setTitle(Phpfox::getPhrase('advancedphoto.rate_photos'))
				->setBreadcrumb(Phpfox::getPhrase('advancedphoto.photos'), $this->url()->makeUrl('advancedphoto'))
				// ->setBreadcrumb(Phpfox::getPhrase('advancedphoto.rate'), $this->url()->makeUrl('advancedphoto.rate'), true)			
				->setHeader('cache', array(		
						'rate_bar.css' => 'style_css'											
					)
			)			
			->assign(array(
				'sRatingBar' => $sBar,
				'aPhoto' => $aPhoto,
				'aCallback' => null
			)
		);
		
		(($sPlugin = Phpfox_Plugin::get('advancedphoto.component_controller_rate_process_end')) ? eval($sPlugin) : false);
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('advancedphoto.component_controller_rate_clean')) ? eval($sPlugin) : false);
	}
}

?>