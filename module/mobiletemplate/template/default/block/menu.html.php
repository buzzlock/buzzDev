<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright       [PHPFOX_COPYRIGHT]
 * @author          Raymond_Benc
 * @package         Phpfox
 * @version         $Id: index.html.php 4031 2012-03-20 15:08:25Z Raymond_Benc $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
{if Phpfox::getParam('core.site_is_offline') && !Phpfox::getUserParam('core.can_view_site_offline')}
{else}
{if Phpfox::isUser()}
<div id="mobile_search"{if isset($bIsMobileIndex)} style="display:block;"{/if}>
    <div id="header_search">    
        <div id="header_menu_space">
            <div id="header_sub_menu_search">
                <form method="post" id='header_search_form' action="{url link='search'}">                                                                                                                   
                    <input type="text" name="q" placeholder="{phrase var='core.search'}" value="{phrase var='core.mobile_search'}" id="header_sub_menu_search_input" autocomplete="off" class="js_temp_friend_search_input" />                                           
                    <div id="div_header_sub_menu_search_input"></div>
                    <a href="#" onclick='$("#header_search_form").submit(); return false;' id="header_search_button"></a>
                </form>
            </div>
        </div><!-- // header_menu_space -->
    </div>  
</div>          
{/if}
{/if}
<div class="ym-menu-text">
    <div class="ym-user">
        {$sUserProfileImage}
        <div class="user_display_name"><a href="{$sUserProfileUrl}">{$sCurrentUserName}</a></div>
    </div>
    <p>{phrase var='mobiletemplate.u_menu'}</p>
</div>
<ul id="mobile_main_menu">     
    {foreach from=$aMobileMenus key=iKey item=ynmtaMenu name=menu}
    <li class="mobile_main_menu">
        <a href="{$ynmtaMenu.link}" style="background-image:url({$ynmtaMenu.icon})">
            {if isset($ynmtaMenu.total) && $ynmtaMenu.total > 0}
            <span class="new">{$ynmtaMenu.total}</span>
            {/if}
            
            <i>{$ynmtaMenu.phrase}</i>
        </a>
    </li> 
    {/foreach}
    <li class="mobile_main_menu"><a class="ym-full-size" href="{url link='go-to-full-site'}" class="first">{phrase var='mobile.full_site'}</a></li> 
    <li class="mobile_main_menu"><a class="ym-language" href="{url link='user.setting'}">{phrase var='mobiletemplate.acc_sett'}</a></li> 
    {if Phpfox::isUser()}
    <li class="mobile_main_menu"><a class="ym-logout" href="{url link='user.logout'}">{phrase var='mobile.logout'}</a></li>
    {/if}  
</ul>
<div class="clear"></div>