{item name='VideoGallery'}
{plugin call='video.template_block_entry_1'}
	<div class="js_video_parent main_video_div_container {if isset($aVideo.is_sponsor) && $aVideo.is_sponsor}row_sponsored_image{/if} {if isset($aVideo.is_featured) && $aVideo.is_featured}row_featured_image{/if}" id="js_video_id_{$aVideo.video_id}">	
		<div class="video_width_holder">
			<div class="video_height_holder">
				<div class="js_outer_video_div js_mp_fix_holder image_hover_holder">
				{if !empty($aVideo.duration)}
					<div class="video_duration">
						{$aVideo.duration}
					</div>
				{/if}					
			
					{plugin call='video.template_block_entry_2'}
					{if isset($sPublicPhotoView) && $sPublicPhotoView == 'featured'}
					{else}
					<div class="js_featured_video row_featured_link"{if !$aVideo.is_featured} style="display:none;"{/if}>
						{phrase var='video.featured'}
					</div>					
					{/if}
					<div class="row_pending_link"{if $aVideo.view_id != 2} style="display:none;"{/if}>
						{phrase var='video.pending'}
					</div>
					<div class="js_sponsor_video row_sponsored_link"{if !$aVideo.is_sponsor} style="display:none;"{/if}>
						{phrase var='video.sponsored'}
					</div>					
										
					<a href="{$aVideo.link}" class="js_video_title_{$aVideo.video_id}">
						{if file_exists(sprintf($aVideo.image_path, '_12090'))}
							{img server_id=$aVideo.image_server_id path='video.url_image' file=$aVideo.image_path suffix='_12090' max_width=120 max_height=90 class='js_mp_fix_width video_image_border' title=$aVideo.title itemprop='image'}
						{else}
							{img server_id=$aVideo.image_server_id path='video.url_image' file=$aVideo.image_path suffix='_120' max_width=120 max_height=90 class='js_mp_fix_width video_image_border' title=$aVideo.title itemprop='image'}
						{/if}
					</a>				
				</div>
			</div>			
			{plugin call='video.template_block_entry_4'}
			<header>
				<h1 itemprop="name"><a href="{$aVideo.link}" class="row_sub_link js_video_title_{$aVideo.video_id}" id="js_video_title_{$aVideo.video_id}" itemprop="url">{$aVideo.title|clean|shorten:30:'...'|split:20}</a></h1>
			</header>
			<div class="extra_info_link">
				{if isset($sPublicPhotoView) && $sPublicPhotoView == 'most-discussed'}
					{phrase var='video.comments_total_comment' total_comment=$aVideo.total_comment}<br />
				{elseif isset($sPublicPhotoView) && $sPublicPhotoView == 'popular'}				
					{phrase var='video.total_score_out_of_10' total_score=$aVideo.total_score|round} <br />
				{else}
				{if !empty($aVideo.total_view) && $aVideo.total_view > 0}
				<span itemprop="interactionCount">
				{if $aVideo.total_view == 1}
				{phrase var='video.1_view'}<br />
				{else}
				{phrase var='video.total_views' total=$aVideo.total_view}<br />
				{/if}
				</span>
				{/if}
				{/if}
				{if !defined('PHPFOX_IS_USER_PROFILEs')}			
					{phrase var='video.by_full_name' full_name=$aVideo|user:'':'':20:'':'author'}			
				{/if}
				{plugin call='video.template_block_entry_5'}
			</div>			
		</div>
	</div>
	{if Phpfox::isMobile() || is_int($phpfox.iteration.videos/3)}
	<div class="clear"></div>
	{/if}
	{plugin call='video.template_block_entry_6'}
{/item}