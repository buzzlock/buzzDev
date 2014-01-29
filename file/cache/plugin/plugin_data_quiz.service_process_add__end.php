<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = 'if (isset($iQuizId) && Phpfox::isModule(\'suggestion\') && Phpfox::isUser()){
    $_SESSION[\'suggestion\'][\'quiz\'][\'quiz_id\'] = $iQuizId;    
} '; ?>