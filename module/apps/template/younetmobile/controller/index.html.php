<div class="p_10">
{if count($aApps)}

	{foreach from=$aApps name=apps item=aApp}
	<div id="js_apps_{$aApp.app_id}" class="{if is_int($phpfox.iteration.apps/2)}row1{else}row2{/if}{if $phpfox.iteration.apps == 1 && !PHPFOX_IS_AJAX} row_first{/if}">		
		<div class="row_title">	
			<div class="row_title_image">
				<a href="{permalink module='apps' id=$aApp.app_id title=$aApp.app_title}">{img server_id=0 path='app.url_image' file=$aApp.image_path suffix='_square' max_width=50 max_height=50 title=$aApp.app_title}</a>
				
			</div>
			<div class="row_title_info">
				<a href="{permalink module='apps' id=$aApp.app_id title=$aApp.app_title}" class="link">{$aApp.app_title|clean|shorten:55:'...'|split:40}</a>			
				<div class="extra_info">
					<ul class="extra_info_middot">{if isset($aApp.category_name)}<li>{$aApp.category_name|convert}</li>{/if}</ul>
				</div>					
				<div class="item_content">
					{$aApp.app_description|clean}
				</div>
				{module name='feed.comment' aFeed=$aApp.aFeed}
			</div>							
		</div>
	</div>
	{/foreach}
	
	{pager}
{else}
<div class="extra_info">
	{phrase var='apps.no_apps_found'}
</div>
{/if}
</div>