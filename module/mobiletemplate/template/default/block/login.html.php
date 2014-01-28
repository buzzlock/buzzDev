<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright       [PHPFOX_COPYRIGHT]
 * @author          Raymond Benc
 * @package         Module_User
 * @version         $Id: login-block.html.php 5318 2013-02-04 10:38:35Z Miguel_Espinoza $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
<div class="ym-login-content">
{plugin call='user.template_controller_login_block__start'}
<form method="post" action="{url link="user.login"}">
    <div class="p_top_4">
        <div class="p_4 ym-input-bg">
            <input type="text" name="val[login]" id="js_email" value="" size="30" placeholder="{if Phpfox::getParam('user.login_type') == 'user_name'}{phrase var='user.user_name'}{elseif Phpfox::getParam('user.login_type') == 'email'}{phrase var='user.email'}{else}{phrase var='user.login'}{/if}" style="width:96%;" />
        </div>
    </div>
    
    <div class="p_top_4">
        <div class="p_4 ym-input-bg">
            <input type="password" name="val[password]" id="js_password" value="" size="30" placeholder="{phrase var='user.password'}" style="width:96%;" />
        </div>
    </div>
    
    <div class="ym-login-submit">
        <span>
            <input type="submit" value="{phrase var='user.login_button'}" class="button" />
        </span>
        
    </div>
</form>
{if (Phpfox::isModule('facebook') && Phpfox::getParam('facebook.enable_facebook_connect')) || (Phpfox::isModule('janrain') && Phpfox::getParam('janrain.enable_janrain_login'))}
<div class="p_top_8">   
    {phrase var='user.or_login_with'}:
    {if Phpfox::isModule('facebook') && Phpfox::getParam('facebook.enable_facebook_connect')}
    <div class="header_login_block"><div class="fbconnect_button"><fb:login-button scope="publish_stream,email,user_birthday" v="2"></fb:login-button></div></div>
    {/if}
    {if Phpfox::isModule('janrain') && Phpfox::getParam('janrain.enable_janrain_login')}
    <div class="header_login_block">
        <a class="rpxnow" href="{$sJanrainUrl}">{img theme='layout/janrain-icons.png'}</a>
    </div>
    {/if}
</div>
{/if}

<div class="ym-menu-login">
    <p class="p_4 ym-signup-section">OR</p>
    <p>
        <a class="ym-button-sign-up" href="{url link='user.register'}">{phrase var='user.sign'}</a>
    </p>
    <p class="p_4 ym-forgot-pass">
        <a href="{url link='user.password/request'}">{phrase var='user.forgot_password'}</a>
    </p>
</div>
</div>