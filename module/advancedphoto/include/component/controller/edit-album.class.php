<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * 
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond_Benc
 * @package 		Phpfox_Component
 * @version 		$Id: edit-album.class.php 3395 2011-10-31 15:34:08Z Raymond_Benc $
 */
class Advancedphoto_Component_Controller_Edit_Album extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		Phpfox::isUser(true);
		
		if (Phpfox::getUserBy('profile_page_id'))
		{
			Phpfox::getService('pages')->setIsInPage();
		}
		
		if (!($aAlbum = Phpfox::getService('advancedphoto.album')->getForEdit($this->request()->getInt('id'))))
		{
			return Phpfox_Error::display(Phpfox::getPhrase('advancedphoto.photo_album_not_found'));
		}
		
		if (($aVals = $this->request()->getArray('val')))
		{
			if ($this->request()->get('req3') == 'photo')
			{
				if (Phpfox::getService('advancedphoto.process')->massProcess($aAlbum, $aVals))
				{
					$this->url()->send('advancedphoto.edit-album.photo', array('id' => $aAlbum['album_id']), Phpfox::getPhrase('advancedphoto.photo_s_successfully_updated'));
				}
			}
			else 
			{
				if (Phpfox::getService('advancedphoto.album.process')->update($aAlbum['album_id'], $aVals))
				{
					$this->url()->send('advancedphoto.edit-album', array('id' => $aAlbum['album_id']), Phpfox::getPhrase('advancedphoto.album_successfully_updated'));
				}
			}
		}
		
		$aMenus = array(
			'detail' => Phpfox::getPhrase('advancedphoto.album_info'),
			'photo' => Phpfox::getPhrase('advancedphoto.photos'),
			'slideshow' => Phpfox::getPhrase('advancedphoto.slide_show'),
		);
		
		$this->template()->buildPageMenu('js_photo_block', 
			$aMenus,
			array(
				'link' => $this->url()->permalink('advancedphoto.album', $aAlbum['album_id'], $aAlbum['name']),
				'phrase' => Phpfox::getPhrase('advancedphoto.view_this_album_uppercase')
			)
		);	
		
		list($iCnt, $aPhotos) = Phpfox::getService('advancedphoto')->get('p.album_id = ' . (int) $aAlbum['album_id']);
		list($iAlbumCnt, $aAlbums) = Phpfox::getService('advancedphoto.album')->get('pa.user_id = ' . Phpfox::getUserId());
		foreach ($aPhotos as $aPhoto)
		{
			Phpfox::getService("advancedphoto.process")->cropCenterlize($aPhoto["destination"], $aPhoto["width"], $aPhoto["height"]);
		}
		$this->template()->setTitle(Phpfox::getPhrase('advancedphoto.editing_album') . ': ' . $aAlbum['name'])
			->setFullSite()
			->setBreadcrumb(Phpfox::getPhrase('advancedphoto.photo'), $this->url()->makeUrl('advancedphoto'))
			->setBreadcrumb(Phpfox::getPhrase('advancedphoto.editing_album') . ': ' . $aAlbum['name'], $this->url()->makeUrl('advancedphoto.edit-album', array('id' => $aAlbum['album_id'])), true)
			->setHeader( array(
					'edit.css' => 'module_advancedphoto',
					'advancedphoto.js' => 'module_advancedphoto',
					'pager.css' => 'style_css',
					'nivo-slider.css' => 'module_advancedphoto',
					'jquery.nivo.slider.js' => 'module_advancedphoto',
					'advphoto_edit_ready.js' => 'module_advancedphoto',
					// nhanlt
					
					'themes/default/default.css' => 'module_advancedphoto',
					'themes/light/light.css' => 'module_advancedphoto',
					'themes/dark/dark.css' => 'module_advancedphoto',
					'themes/bar/bar.css' => 'module_advancedphoto',
					'nivo-slider-edit.css' => 'module_advancedphoto',
					'jquery.nivo.slider.js' => 'module_advancedphoto',
				)
			)
			->assign(array(
					'aForms' => $aAlbum,
					'aPhotos' => $aPhotos,
					'aAlbums' => $aAlbums
				)
			);
		// for an unnoticed reason, these below files shouldn't be cached
		$this->template()->setHeader(array(
			'advphoto.css' => 'module_advancedphoto',
		));
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('advancedphoto.component_controller_edit_album_clean')) ? eval($sPlugin) : false);
	}
}

?>