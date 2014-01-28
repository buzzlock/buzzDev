{if count($aPages)}
{if $sView == 'my' && Phpfox::getUserBy('profile_page_id')}
<div class="message">
	{phrase var='pages.note_that_pages_displayed_here_are_pages_created_by_the_page' global_full_name=$sGlobalUserFullName|clean profile_full_name=$aGlobalProfilePageLogin.full_name|clean}
</div>
{/if}
{foreach from=$aPages name=pages item=aPage}
<div id="js_pages_{$aPage.page_id}" class="js_pages_parent {if is_int($phpfox.iteration.pages/2)}row1{else}row2{/if}{if $phpfox.iteration.pages == 1 && !PHPFOX_IS_AJAX} row_first{/if}">		
		<div class="row_title">	
			<div class="row_title_image">
				<a href="{$aPage.link}">{img server_id=$aPage.profile_server_id title=$aPage.title path='core.url_user' file=$aPage.profile_user_image suffix='_50_square' max_width='50' max_height='50' is_page_image=true}</a>
			</div>
			<div class="row_title_info">
				<a href="{$aPage.link}" class="link">{$aPage.title|clean|shorten:55:'...'|split:40}</a>			
				<div class="extra_info">
					<ul class="extra_info_middot"><li>{$aPage.category_name|convert}</li>{if $aPage.page_type == '1'}<li><span>&middot;</span></li><li>{if $aPage.total_like > 1}{phrase var='pages.total_members' total=$aPage.total_like|number_format}{elseif $aPage.total_like == 1}{phrase var='pages.1_member'}{else}{phrase var='pages.no_members'}{/if}</li>{/if}</ul>
				</div>
			</div>					
		</div>	
</div>
{/foreach}
{if Phpfox::getUserParam('pages.can_moderate_pages')}
{moderation}
{/if}

{pager}
{else}
<div class="extra_info">
	{phrase var='pages.no_pages_found'}
</div>
{/if}