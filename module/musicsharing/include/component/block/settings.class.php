<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');
class musicsharing_Component_Block_Settings extends Phpfox_Component 
{
    /**
     * Class process method wnich is used to execute this component.
     */
    
    public function process()
    {
        
            $user_group_id = $this->getParam('user_group_id');
            $settings = phpFox::getService('musicsharing.music')->getSettings($user_group_id);
            //print_r($settings);die();
            $settings = phpFox::getService('musicsharing.music')->setDefaultValue($settings,$user_group_id);
            
            $this->template()->assign(array(
                        'settings' => $settings,
                       
                        )
                );
       
        
    }
}

?>