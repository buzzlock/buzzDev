<?php


defined('PHPFOX') or exit('NO DICE!');

class MobileTemplate_Component_Block_Profile_Top extends Phpfox_Component
{
    
    public function process()
    {
        if (!defined('PHPFOX_IS_USER_PROFILE'))
        {
            return false;
        }
        
        $aUser = $this->getParam('aUser');

        $aUserInfo = array(
            'title' => $aUser['full_name'],
            'path' => 'core.url_user',
            'file' => $aUser['user_image'],
            'suffix' => '_100_square',
            'max_width' => 100,
            'max_height' => 100,
            'no_default' => (Phpfox::getUserId() == $aUser['user_id'] ? false : true),
            'thickbox' => true,
            'class' => 'profile_user_image'
        );          
        
        $sImage = Phpfox::getLib('image.helper')->display(array_merge(array('user' => Phpfox::getService('user')->getUserFields(true, $aUser)), $aUserInfo));

        $bIsInfo = false;
        if((isset($aUser['landing_page']) && ($aUser['landing_page'] == 'info') && Phpfox::getLib('request')->get('req2') != 'wall')
            || (Phpfox::getLib('request')->get('req2') == 'info' )
        )
        {
            $bIsInfo = true;
        }
		
		//	get user's information
		 $userInfo = Phpfox::getService('mobiletemplate')->getUserInfoByUserID($aUser['user_id'], $aUser);

        $this->template()->assign(array(
                'sProfileImage' => $sImage,
                'bIsInfo' => $bIsInfo,                 
                'sRelationship' => $userInfo['sRelationship'],
                'aUserDetails' => $userInfo['aUserDetails']
            )
        );
    }
    
    /**
     * Garbage collector. Is executed after this class has completed
     * its job and the template has also been displayed.
     */
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('mobiletemplate.component_block_filter_clean')) ? eval($sPlugin) : false);
    }
}

?>