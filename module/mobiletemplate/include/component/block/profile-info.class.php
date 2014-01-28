<?php


defined('PHPFOX') or exit('NO DICE!');
class MobileTemplate_Component_Block_Profile_Info extends Phpfox_Component
{
    
    public function process()
    {
		$mUser = $this->request()->get('req1');
		$sSection = $this->request()->get('req2');
		if (!$mUser)
		{			
			if (Phpfox::isUser())
			{				
				$this->url()->send('profile');
			}
			else 
			{
				Phpfox::isUser(true);
			}
		}
		$aUser = Phpfox::getService('user')->get($mUser, false);
		if ((isset($aUser['user_id']) && $aUser['profile_page_id']  == 0))
		{
			//	this is user
			//	get ALL friends of this user
			list($iCnt, $aFriends) = Phpfox::getService('friend')->get(' friend.is_page = 0 AND friend.user_id = ' . (int)$aUser['user_id'], '', '', '', true, true, false, null, false);
			$listOfFriendImage = array();
			if((int)$iCnt > 0){
				shuffle($aFriends);
				
				for($i = 0; $i <= 6; $i++){
					if(isset($aFriends[$i])){
						$pathSmall = Phpfox::getLib('phpfox.image.helper')->display(array(
							'id' => 'sJsUserImage_1', 
							'user' => $aFriends[$i], 
							'suffix' => '_50_square',
							'max_width' => '50',
							'max_height' => '50',
							'return_url' => true
						));
						$pathLarge = Phpfox::getLib('phpfox.image.helper')->display(array(
							'id' => 'sJsUserImage_1', 
							'user' => $aFriends[$i], 
							'suffix' => '_100_square',
							'max_width' => '100',
							'max_height' => '100',
							'return_url' => true
						));
						$listOfFriendImage[] = array('small' => $pathSmall, 'large' => $pathLarge);
					} else {
						break;
					}
				}				
			}
		}
		 //	get latest photo of viewed user
		 $latestPhoto = Phpfox::getService('mobiletemplate')->getLatestPublicPhotosByUserID($aUser['user_id'], $limit = 1);
		 if(isset($latestPhoto) && isset($latestPhoto[0])){
		$latestPhotoPathLarge = Phpfox::getLib('phpfox.image.helper')->display(array(
			'server_id' => $latestPhoto[0]['server_id'], 
			'path' => 'photo.url_photo',
			'file' => $latestPhoto[0]['destination'],
			'suffix' => '_150',
			'max_width' => '150',
			'max_height' => '150',
			'title' => $latestPhoto[0]['title'], 
			'return_url' => true 
		));
		 	
        $this->template()->assign(array(
				'latestPhotoPathLarge' => $latestPhotoPathLarge
            )
        );
		 }
		
        $this->template()->assign(array(
                'corepath' => phpfox::getParam('core.url_module'),                
                'countFriends' => count($listOfFriendImage),
				'listOfFriendImage' => $listOfFriendImage, 
				'aUser' => $aUser
            )
        );
    }
}
?>