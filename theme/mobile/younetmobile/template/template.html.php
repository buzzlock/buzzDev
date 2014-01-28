<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author			Raymond Benc
 * @package 		Phpfox
 * @version 		$Id: template.html.php 1458 2010-01-29 19:28:49Z Raymond_Benc $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
{if !PHPFOX_IS_AJAX_PAGE}
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.2//EN"
"http://www.openmobilealliance.org/tech/DTD/xhtml-mobile12.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="{$sLocaleDirection}" lang="{$sLocaleCode}">
	<head>
		<title>{title}</title>	
		<meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no" />
		{header}
		<!-- <script type="text/javascript" src="{$core_url}theme/frontend/younetmobile/style/younetmobile/jscript/snap.js"></script> -->
		
		{if phpfox::isUser()}
		<script type="text/javascript" src="{$core_url}theme/frontend/younetmobile/style/younetmobile/jscript/menu.js"></script>
		{/if}
		
		<link href="{$core_url_module}mobiletemplate/mobile_custom_style.php" rel="stylesheet">
	</head>
	<body class="{php}echo Phpfox::getLib('module')->getFullControllerName(){/php} {if !phpfox::isUser()}ym-guestpage{/if}" >
	    <div style="width:100%;overflow:hidden;" class="main-wrapper">
		{plugin call='theme_template_body__start'}
		{if Phpfox::getParam('core.site_is_offline') && Phpfox::getUserParam('core.can_view_site_offline')}
			<div id="site_offline">
				{phrase var='core.the_site_is_currently_in_offline_mode'}
			</div>
		{/if}	
		<!-- left -->
		<div class="snap-drawers">
            <div class="snap-drawer snap-drawer-left">
        		<div class="ym-menu-slide">  
                    {module name="mobiletemplate.menu"}
                </div>	
            </div>
            <div class="snap-drawer snap-drawer-right">
                <div class="ym-menu-slide">
                   
                 {if Phpfox::isUser() && !defined('PHPFOX_IS_USER_PROFILE') && !PHPFOX_IS_AJAX && !defined('PHPFOX_IS_PAGES_VIEW')}
                    <div class="feed_sort_order">
           
                        <div class="feed_sort_holder">
                            <ul>
                                <li class=" mobile_main_menu"><a href="#" rel="0">{phrase var='feed.top_stories'}</a></li>
                                <li class=" mobile_main_menu"><a href="#" rel="1">{phrase var='feed.most_recent'}</a></li>
                            </ul>
                        </div>
                    </div>
                   
                {/if}
                {if isset($aFilterMenus) && is_array($aFilterMenus) && count($aFilterMenus)}
                    
                      {breadcrumb}
                      
                    {foreach from=$aFilterMenus name=filtermenu item=aFilterMenu}
                        {if !isset($aFilterMenu.name)}
                        {else}
                        <li class=" mobile_main_menu {if $aFilterMenu.active}active{/if}"><a href="{$aFilterMenu.link}">{$aFilterMenu.name}</a></li>
                        {/if}
                    {/foreach}
                 {/if}
                 {if isset($aPageSectionMenu) && count($aPageSectionMenu)}
                    
                        <div class="ynmb_row_title_image">
                            <div class="row_edit_bar_parent">
                                <div class="page_section_menu{if !isset($aPageExtraLink.no_header_border)} page_section_menu_header{/if}">
                                    <ul>
                                        {if $aPageExtraLink !== null}
                                        <li class=" mobile_main_menu">
                                            <a href="{$aPageExtraLink.link}" class="page_section_menu_link">{$aPageExtraLink.phrase}</a>
                                        </li>
                                        {/if}           
                                        {foreach from=$aPageSectionMenu key=sPageSectionKey item=sPageSectionMenu name=pagesectionmenu}
                                        <li class=" mobile_main_menu {if ($phpfox.iteration.pagesectionmenu == 1 && !$bPageIsFullLink) || ($bPageIsFullLink && $sPageSectionKey == $sPageCurrentUrl)} active{/if}">
                                            <a href="{if $bPageIsFullLink}{$sPageSectionKey}{else}#{/if}" {if !$bPageIsFullLink}rel="{$sPageSectionMenuName}_{$sPageSectionKey}"{/if}>{$sPageSectionMenu}</a>
                                        </li>
                                        {/foreach}
                                    </ul>
                                </div>
                                
                            </div>
                        </div>
                    
            </div>
        </div>


{/if}
{section_menu_js}
                 </div>
            </div>
        </div>
        
        <!--test sestion menu -->
        
        <!-- end -->
        <!-- end left -->
        <div class="sub-page" id="mobile_sub_holder"></div>	
		<div id="mobile_holder" class="snap-content{if (!phpfox::isUser())
                    } ym-guest-home"{/if}">
		    {if phpfox::isUser()}
			<div id="mobile_header">	
			    <table>
			        <tr>
			            <td class="ym-left-head">			                
			                {if Phpfox::getParam('core.site_is_offline') && !Phpfox::getUserParam('core.can_view_site_offline')}
                            {else}
                            {if Phpfox::isUser()}
                                <a href="javascript:void(0)" id="mobile_header_home" class="icon-home">{phrase var='mobiletemplate.u_home'}</a>                            
                            {/if}
                            {/if}
			            </td>
			            
			            <td class="ym-center-head">
			                <div id="holder_notify" class="ym-mobile-notify">                                                                    
                                    {notification}
                                    <div class="clear"></div>
                                </div>
                                
    			            </td>
    			            <td class="ym-right-head">
    			                <ul class="ym-main-header-right">
                                        {if Phpfox::isUser() && !defined('PHPFOX_IS_USER_PROFILE') && !PHPFOX_IS_AJAX && !defined('PHPFOX_IS_PAGES_VIEW')}
                                            <li>
                                                  <a href="#" id="ym-open-right" class="mobile_main_btn btn btn-head">{phrase var='mobiletemplate.u_sort'}</a>
                                            </li>
                                           
                                        {/if}
                                        {if isset($aFilterMenus) && is_array($aFilterMenus) && count($aFilterMenus)}
                                         <li>
                                             
                                            <a href="#" id="ym-open-menu-right" class="mobile_main_btn btn btn-head">{phrase var='mobiletemplate.u_menu'}</a>
                                            
                                         </li>                                        
                                        {/if}
                                         {if isset($aPageSectionMenu) && count($aPageSectionMenu)}
                                            <li>
                                             
                                                <a href="#" id="ym-open-edit-right" class="mobile_main_btn btn btn-head">{phrase var='mobiletemplate.u_menu'}</a>
                                                
                                             </li>  
                                         {/if}
    			                </ul>
    			                
			            </td>
			           
			        </tr>
			    </table>
				
				
                
				
			</div>
			{else}
			{if Phpfox::getLib('module')->getFullControllerName() == 'user.register'}
			<div id="mobile_header" class="ym-sub-header"> 
			    <table>
                    <tr>
                    <td class="ym-left-head">
                        <a href="{url link=''}" class="mobile_main_btn btn btn-head register-back">{phrase var='mobiletemplate.u_back'}</a>
                    </td>
                    <td class="ym-center-head">
                             
			             {phrase var='user.sign_up_for_ssitetitle' sSiteTitle=$sSiteTitle}
			        </td>
    			     <td class="ym-right-head">
    			     </td>
    			     </tr>
    			     </table>
			</div>
			{/if}
			{/if}
			<div id="holder">
				<div id="main_content_holder">				
				{/if}			
										
				
					
					{if isset($aBreadCrumbTitle) && count($aBreadCrumbTitle) && Phpfox::getLib('module')->getModuleName() != 'pages' }
					{if phpfox::isUser()}
    					<div id="mobile_h1_main">
    						<h1><a href="{$aBreadCrumbTitle[1]}">{$aBreadCrumbTitle[0]|clean}</a></h1>
    					</div>
					{/if}
					{/if}				
					<div id="content" {if Phpfox::getLib('module')->getModuleName() == 'search'} class="ym-search-content" {/if}>
					    {if phpfox::isUser()}
						    {if Phpfox::getLib('module')->getFullControllerName() != 'friend.index'
						    	&& Phpfox::getLib('module')->getFullControllerName() != 'mail.index'
						    }
								{search}
							{/if}
						{/if}
						<div id="mobile_content" {if Phpfox::getLib('module')->getFullControllerName() == 'user.register'} class="ym-register-content" {/if}>
							{error}		
							{if Phpfox::isUser()
							    || (!Phpfox::isUser() && Phpfox::getLib('module')->getFullControllerName() == 'user.password/request')
							    || (!Phpfox::isUser() && Phpfox::getLib('module')->getFullControllerName() == 'user.register') 
							 }		
							
							{if defined('PHPFOX_IS_USER_PROFILE')}
							     {module name='profile.mobile'}
							{/if}
    							{block location='2'}
    							{content}
    							{block location='4'}				
							{else}
							
							{logo}
							{module name='mobiletemplate.login'}
							
							{/if}
						</div>
						
					</div>
				{if !PHPFOX_IS_AJAX_PAGE}
				</div>
			</div>					
			
			    
			
		</div>
		{footer}	
		{plugin call='theme_template_body__end'}
		
		{if !isset($shouldShowSortFeed) || $shouldShowSortFeed != 1}
			{plugin call='mobiletemplate.remove_sort_feed'}
		{/if}
		
		<script type="text/javascript" charset="utf-8" src="{jscript file='mobiletemplate.js' module='mobiletemplate'}"></script>
		
		{if Phpfox::getLib('module')->getFullControllerName() == 'event.view' || Phpfox::getLib('module')->getFullControllerName() == 'feed.index' || Phpfox::getLib('module')->getFullControllerName() == 'pages.view' || Phpfox::getLib('module')->getFullControllerName() == 'mobile.index' || Phpfox::getLib('module')->getFullControllerName() == 'profile.index'}
			{plugin call='mobiletemplate.set_full_controller_name'}
		{/if}
		
		{if Phpfox::getParam('feed.enable_check_in') && Phpfox::getParam('core.google_api_key') != '' }
	    <div id="mt_js_static_map_in_feed" style="display: none;" >
	    {/if}
	    
	    {plugin call='mobiletemplate.set_value_on_js'}
		
		
	</div>
	</body>
</html>
{/if}