<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = 'if (isset($_SESSION[\'signup_plugin\']) && $_SESSION[\'signup_plugin\'] == 1)
    {
        unset($_SESSION[\'signup_plugin\']);
        $mess = Phpfox::getPhrase(\'contactimporter.you_have_already_been_a_friend_of_people\');
        phpfox::getLib(\'url\')->send(\'contactimporter.invitionknow\', null, $mess);
    } //	This file has been DEPRECATED as of v3.03 '; ?>