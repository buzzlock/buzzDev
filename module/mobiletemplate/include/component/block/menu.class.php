<?php


defined('PHPFOX') or exit('NO DICE!');

class MobileTemplate_Component_Block_Menu extends Phpfox_Component
{
    
    public function process()
    {
        $oService = Phpfox::getService('mobiletemplate');

        $sUserProfileImage = Phpfox::getLib('image.helper')->display(array_merge(array('user' => Phpfox::getService('user')->getUserFields(true)), array(               
                    'path' => 'core.url_user',
                    'file' => Phpfox::getUserBy('user_image'),
                    'suffix' => '_50_square',
                    'max_width' => 32,
                    'max_height' => 32
                )
            )
        );  

        // get data
        $sCacheId = Phpfox::getLib('cache')->set('mt_leftnavi' . (Phpfox::isUser() ? Phpfox::getUserId() : 0));
        if (!($aRows = Phpfox::getLib('cache')->get($sCacheId)))
        {
            $mobileMenus = Phpfox::getService('mobile')->getMenu();
            $menuNavigation = $oService->getAllMenuNavigation();

            //  merge data: exist in navigation and menu 
            $refreshMobileMenus = array();
            foreach($menuNavigation as $navKey => $navVal){
                // if enable
                if($navVal['is_active'] == 1){
                    //  check exist in site
                    $isExistInSite = false;
                    foreach($mobileMenus as $menuKey => $menuVal){
                        if($menuVal['url'] == $navVal['url']){
                            $isExistInSite = true;
                            break;
                        }
                    }

                    //  add to refresh menu
                    if($isExistInSite == true){
                        $refreshMobileMenus[] = $menuVal;
                    }                
                }
            }
            //  merge data: exist in menu but not in navigation
            foreach($mobileMenus as $menuKey => $menuVal){
                $isInMenuAndNotInNav = true;

                foreach($menuNavigation as $navKey => $navVal){
                    if($menuVal['url'] == $navVal['url']){
                        $isInMenuAndNotInNav = false;
                        break;
                    }
                }

                if($isInMenuAndNotInNav == true){
                    $refreshMobileMenus[] = $menuVal;
                }
            }

            Phpfox::getLib('cache')->save($sCacheId, $refreshMobileMenus);
        } else {
            if (!is_array($aRows))
            {
                return array();
            } else {
                $refreshMobileMenus = $aRows;
            }
        }

       $this->template()->assign(array(
                //'aMobileMenus' => Phpfox::getService('mobile')->getMenu(),
                'aMobileMenus' => $refreshMobileMenus,
                'sUserProfileImage' => $sUserProfileImage,
                'sUserProfileUrl' => $this->url()->makeUrl('profile', Phpfox::getUserBy('user_name')), // Create the users profile URL
                'sCurrentUserName' => Phpfox::getLib('parse.output')->shorten(Phpfox::getLib('parse.output')->clean(Phpfox::getUserBy('full_name')), Phpfox::getParam('user.max_length_for_username'), '...'), // Get the users display name
                'bIsMobileIndex' => true
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