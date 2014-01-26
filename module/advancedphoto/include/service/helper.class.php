<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * Photo process class. Used to INSERT, UPDATE & DELETE photos.
 *
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package  		Module_Photo
 * @version 		$Id: process.class.php 4574 2012-07-31 09:35:22Z Miguel_Espinoza $
 */
class Advancedphoto_Service_Helper extends Phpfox_Service
{
	public function getLinkViewAll($sType)
	{
		$sLink = '';
		switch($sType)
		{
			case 'most-viewed':
					$sLink = Phpfox::getLib('url')->makeUrl('advancedphoto',array('sort' => 'most-viewed'));		
				break;
			case 'most-commented':
					$sLink = Phpfox::getLib('url')->makeUrl('advancedphoto',array('sort' => 'most-talked'));		
				break;
			case 'most-liked':
					$sLink = Phpfox::getLib('url')->makeUrl('advancedphoto',array('sort' => 'most-liked'));		
				break;
			case 'recent':
					$sLink = Phpfox::getLib('url')->makeUrl('advancedphoto',array('sort' => 'latest'));		
				break;
			case 'today':
					$sLink = Phpfox::getLib('url')->makeUrl('advancedphoto',array('sort' => 'latest', 'when' => 'today'));
				break;
			case 'this-week':
					$sLink = Phpfox::getLib('url')->makeUrl('advancedphoto',array('sort' => 'latest', 'when' => 'this-week'));
				break;
			case 'this-month':
					$sLink = Phpfox::getLib('url')->makeUrl('advancedphoto',array('sort' => 'latest', 'when' => 'this-month'));
				break;
		}

		return $sLink;
	}


	public function isTimeline($iId)
    {
        if(Phpfox::getParam('feed.force_timeline'))
        {
            return true;
        }

		if(!$iId)
		{
			return false;
		}
        
        $bIsTimeline = false;

		if(!$this->database()->isField(Phpfox::getT('user_field'), 'use_timeline'))
		{
			return false;
		}
        $bIsTimeline= $this->database()->select('use_timeline')
                                    ->from(Phpfox::getT('user_field'), 'uf')
                                    ->where('uf.user_id = ' . $iId)
                                    ->execute('getSlaveRow');
        if(isset($bIsTimeline['use_timeline']) && $bIsTimeline['use_timeline'])
        {
            return true;
        }
        else
        {
            return false;
        }
        
    }
	
}

?>