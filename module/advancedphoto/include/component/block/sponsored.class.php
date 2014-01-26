<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * Used to display a featured image and is setup to refresh X number of milliseconds.
 *
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package  		Module_Photo
 * @version 		$Id: sponsored.class.php 3214 2011-09-30 12:05:14Z Raymond_Benc $
 */
class Advancedphoto_Component_Block_Sponsored extends Phpfox_Component
{
    /**
     * Class process method wnich is used to execute this component.
     */
    public function process()
    {
		if (!Phpfox::isModule('ad'))
		{
			return false;
		}
    	
    	if (defined('PHPFOX_IS_GROUP_VIEW') || defined('PHPFOX_IS_PAGES_VIEW') || defined('PHPFOX_IS_USER_PROFILE'))
		{
		    return false;
		}
		
		$aSponsorPhoto = Phpfox::getService('advancedphoto')->getRandomSponsored();
		
		if (empty($aSponsorPhoto))
		{
		    return false;
		}
		
		Phpfox::getService('ad.process')->addSponsorViewsCount($aSponsorPhoto['sponsor_id'], 'photo');
		
		$aSponsorPhoto['details'] = array(
			Phpfox::getPhrase('advancedphoto.submitted') => Phpfox::getTime(Phpfox::getParam('advancedphoto.photo_image_details_time_stamp'), $aSponsorPhoto['time_stamp']),
			Phpfox::getPhrase('advancedphoto.file_size') => Phpfox::getLib('file')->filesize($aSponsorPhoto['file_size']),
			Phpfox::getPhrase('advancedphoto.resolution') => $aSponsorPhoto['width'] . '×' . $aSponsorPhoto['height'],
			Phpfox::getPhrase('advancedphoto.views') => $aSponsorPhoto['total_view']
		);
		
		$this->template()->assign(array(
				'aSponsorPhoto' => $aSponsorPhoto,
				'sHeader' => Phpfox::getPhrase('advancedphoto.sponsored_photo'),
				'aFooter' => array(Phpfox::getPhrase('advancedphoto.encourage_sponsor') => $this->url()->makeUrl('profile.photo', array('sponsor' => '1')))
			)
		);
	
		return 'block';
    }

    /**
     * Garbage collector. Is executed after this class has completed
     * its job and the template has also been displayed.
     */
    public function clean()
    {
	(($sPlugin = Phpfox_Plugin::get('advancedphoto.component_block_featured_clean')) ? eval($sPlugin) : false);
    }
}

?>