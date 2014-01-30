<?php

defined('PHPFOX') or exit('NO DICE!');

if (Phpfox::isModule('profilepopup') && !Phpfox::isMobile() && Phpfox::getUserParam('profilepopup.can_view_profile_popup'))
{
        PhpFox::getLib('template')->setPhrase(array(
                "profilepopup.loading"
                , "profilepopup.loading_error"
        ));
        
        PhpFox::getLib('template')->setHeader(array(
            'redefineuserinfo.js' => 'module_profilepopup',
            'profilepopup.js' => 'module_profilepopup',
            'profilepopup.css' => 'module_profilepopup'
                )
        );
}
?>



