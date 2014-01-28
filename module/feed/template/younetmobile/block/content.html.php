{if !Phpfox::getService('profile')->timeline()}
	<div class="activity_feed_content">							
{/if}
	<div class="activity_feed_content_text{if isset($aFeed.comment_type_id) && $aFeed.comment_type_id == 'poll'} js_parent_module_feed_{$aFeed.comment_type_id}{/if}">
        <?php
            if(strpos(Phpfox::getLib('url')->getFullUrl(), 'add-comment') !== false){l}
            	Phpfox::getLib('template' )->assign( array('noDeleteMainContent' => '1'));
            {r}
        ?>
		{if !isset($noDeleteMainContent) && Phpfox::isMobile() && ((defined('PHPFOX_FEED_CAN_DELETE')) || (Phpfox::getUserParam('feed.can_delete_own_feed') && $aFeed.user_id == Phpfox::getUserId()) || Phpfox::getUserParam('feed.can_delete_other_feeds'))}
			<div class="ynmt_feed_comment_delete_link">
				<a href="#" class="action_delete ynmt_more_space" onclick="if (confirm(getPhrase('core.are_you_sure'))){l}$.ajaxCall('feed.delete', 'id={$aFeed.feed_id}{if isset($aFeedCallback.module)}&amp;module={$aFeedCallback.module}&amp;item={$aFeedCallback.item_id}{/if}', 'GET');{r} $('#js_item_feed_{$aFeed.feed_id}').parent().css('border-top', 'none'); return false;">
					<span class="js_hover_info">
						{phrase var='comment.delete'}
					</span>
				</a>
			</div>
		{/if}						

		<div class="ym-item-feed-info">
    		{if !isset($aFeed.feed_mini) && !Phpfox::getService('profile')->timeline()}
    			<div class="activity_feed_content_info">
    				{$aFeed|user:'':'':50}{if !empty($aFeed.parent_module_id)} {phrase var='feed.shared'}{else}{if isset($aFeed.parent_user)} {img theme='layout/arrow.png' class='v_middle'} {$aFeed.parent_user|user:'parent_':'':50} {/if}{if !empty($aFeed.feed_info)} {$aFeed.feed_info}{/if}{/if}
    			</div>
    		{/if}			
    		{if !empty($aFeed.feed_mini_content)}
    			<div class="activity_feed_content_status">
    				<div class="activity_feed_content_status_left">
    					<img src="{$aFeed.feed_icon}" alt="" class="v_middle" /> {$aFeed.feed_mini_content} 
    				</div>
    				<div class="activity_feed_content_status_right">
    					{template file='feed.block.link'}
    				</div>
    				<div class="clear"></div>
    			</div>
    		{/if}
            <div class="ym-time-privacy">
                {if isset($aFeed.time_stamp)}
                    <span class="feed_entry_time_stamp">
                        {$aFeed.time_stamp|convert_time:'feed.feed_display_time_stamp'}{if !empty($aFeed.app_link)} via {$aFeed.app_link}{/if}
                    </span>
                    {/if}
                {if !Phpfox::getService('profile')->timeline()}
                    {if !isset($aFeed.feed_mini)}  
                        {if $aFeed.privacy == 0}
                            <i class="icon-public-small"></i>
                        {elseif $aFeed.privacy == 1}
                            <i class="icon-ff-small"></i>
                        {elseif $aFeed.privacy == 2}
                            <i class="icon-fof-small"></i>
                        {elseif $aFeed.privacy == 3}
                            <i class="icon-onlyme-small"></i>
                        {elseif $aFeed.privacy == 4}
                            <i class="icon-st-custom-small"></i>
                        {/if}      
                        
                    {/if}
                {/if}
            </div>
        </div>
		{if isset($aFeed.feed_status) && (!empty($aFeed.feed_status) || $aFeed.feed_status == '0')}
			<div class="activity_feed_content_status">
				{$aFeed.feed_status|feed_strip|shorten:200:'feed.view_more':true|split:55|max_line}	
				{if Phpfox::getParam('feed.enable_check_in') && Phpfox::getParam('core.google_api_key') != '' && isset($aFeed.location_name)} 
					<span class="js_location_name_hover" > - <a href="#" onclick="return false;">{phrase var='feed.at_location' location=$aFeed.location_name}</a>
					</span> 
					{if isset($aFeed.location_latlng) && isset($aFeed.location_latlng.latitude)}
					<div class="ym-map-content">
					    <div class="mt_map_static_in_feed" style="background-image:url('{if Phpfox::getParam('core.force_https_secure_pages')}https://{else}http://{/if}maps.googleapis.com/maps/api/staticmap?center={$aFeed.location_latlng.latitude},{$aFeed.location_latlng.longitude}&zoom=16&size=600x133&maptype=roadmap&markers=color:red|color:red|label:C|{$aFeed.location_latlng.latitude},{$aFeed.location_latlng.longitude}&sensor=false')">
                           <div class="ym-text-map">
                               <a href="{url link=''}">{param var='core.site_title'}</a>
                           </div>
                            <input type="hidden" name="mt_val_location_lat" id="mt_val_location_lat" value="{$aFeed.location_latlng.latitude}">
                            <input type="hidden" name="mt_val_location_lng" id="mt_val_location_lng" value="{$aFeed.location_latlng.longitude}">
                        </div> 
                        {*
                        <div class="ym-map-info-content">
                            <div class="ym-info-content">
                                <i class="ym-pic-location" style="background-image:url(https://fbcdn-profile-a.akamaihd.net/hprofile-ak-frc1/c14.0.47.47/s43x43/418333_10149999285994467_1920585607_t.png)"></i>
                                <div class="ym-info-location">
                                    <div class="ym-tit">{$aFeed.location_name}</div>
                                    <div class="ym-address">{phrase var='mobiletemplate.local_business'}</div>
                                </div>
                            </div>
                        </div>
                        *}
					</div>
					
					
					{/if}					
				{/if}
			</div>
		{/if}
		
		<div class="activity_feed_content_link">				
			{if $aFeed.type_id == 'friend' && isset($aFeed.more_feed_rows) && is_array($aFeed.more_feed_rows) && count($aFeed.more_feed_rows)}
				{foreach from=$aFeed.more_feed_rows item=aFriends}
					{$aFriends.feed_image}
				{/foreach}
				{$aFeed.feed_image}
			{else}
			{if !empty($aFeed.feed_image)}
			<div class="activity_feed_content_image"{if isset($aFeed.feed_custom_width)} style="width:{$aFeed.feed_custom_width};"{/if}>
				{if is_array($aFeed.feed_image)}
					<ul class="activity_feed_multiple_image">
						{foreach from=$aFeed.feed_image item=sFeedImage}
							<li>{$sFeedImage}</li>
						{/foreach}
					</ul>
					<div class="clear"></div>
				{else}
					<a href="{if isset($aFeed.feed_link_actual)}{$aFeed.feed_link_actual}{else}{$aFeed.feed_link}{/if}"{if !isset($aFeed.no_target_blank)} target="_blank"{/if} class="{if isset($aFeed.custom_css)} {$aFeed.custom_css} {/if}{if !empty($aFeed.feed_image_onclick)}{if !isset($aFeed.feed_image_onclick_no_image)}play_link {/if} no_ajax_link{/if}"{if !empty($aFeed.feed_image_onclick)} onclick="{$aFeed.feed_image_onclick}"{/if}{if !empty($aFeed.custom_rel)} rel="{$aFeed.custom_rel}"{/if}{if isset($aFeed.custom_js)} {$aFeed.custom_js} {/if}{if Phpfox::getParam('core.no_follow_on_external_links')} rel="nofollow"{/if}>{if !empty($aFeed.feed_image_onclick)}{if !isset($aFeed.feed_image_onclick_no_image)}<span class="play_link_img">{phrase var='feed.play'}</span>{/if}{/if}{$aFeed.feed_image}</a>						
				{/if}
			</div>
			{/if}
			<div class="{if (!empty($aFeed.feed_content) || !empty($aFeed.feed_custom_html)) && empty($aFeed.feed_image)} activity_feed_content_no_image{/if}{if !empty($aFeed.feed_image)} activity_feed_content_float{/if}"{if isset($aFeed.feed_custom_width)} style="margin-left:{$aFeed.feed_custom_width};"{/if}>
				{if !empty($aFeed.feed_title)}
					{if isset($aFeed.feed_title_sub)}
						<span class="user_profile_link_span" id="js_user_name_link_{$aFeed.feed_title_sub|clean}">
					{/if}
					<a href="{if isset($aFeed.feed_link_actual)}{$aFeed.feed_link_actual}{else}{$aFeed.feed_link}{/if}" class="activity_feed_content_link_title"{if isset($aFeed.feed_title_extra_link)} target="_blank"{/if}{if Phpfox::getParam('core.no_follow_on_external_links')} rel="nofollow"{/if}>{$aFeed.feed_title|clean|split:30}</a>
					{if isset($aFeed.feed_title_sub)}
						</span>
					{/if}
					{if !empty($aFeed.feed_title_extra)}
						<div class="activity_feed_content_link_title_link">
							<a href="{$aFeed.feed_title_extra_link}" target="_blank"{if Phpfox::getParam('core.no_follow_on_external_links')} rel="nofollow"{/if}>{$aFeed.feed_title_extra|clean}</a>
						</div>
					{/if}
				{/if}			
				{if !empty($aFeed.feed_content)}
					<div class="activity_feed_content_display">
						{$aFeed.feed_content|feed_strip|shorten:200:'...'|split:55|max_line}				
					</div>
				{/if}
				{if !empty($aFeed.feed_custom_html)}
					<div class="activity_feed_content_display_custom">
						{$aFeed.feed_custom_html}
					</div>
				{/if}
				
				{if !empty($aFeed.parent_module_id)}
					{module name='feed.mini' parent_feed_id=$aFeed.parent_feed_id parent_module_id=$aFeed.parent_module_id}
				{/if}
				
			</div>	
			{if !empty($aFeed.feed_image)}
			<div class="clear"></div>
			{/if}		
			{/if}
		</div>
		<!-- comment total -->
		<div class="ym-total-like-comment">
		     {if isset($aFeed.feed_total_like) && $aFeed.feed_total_like > 0}
              {$aFeed.feed_total_like}
              {if $aFeed.feed_total_like > 1}
                {phrase var='mobiletemplate.u_likes'}
               {else}
                {phrase var='mobiletemplate.u_like'}
               {/if}
               {if isset($aFeed.total_comment) && $aFeed.total_comment > 0}
              &middot
               {/if}
            {/if}
            
		    
		    {if isset($aFeed.total_comment) && $aFeed.total_comment > 0}
		      {$aFeed.total_comment}
		      {if $aFeed.total_comment > 1}
                {phrase var='mobiletemplate.u_comments'}
               {else}
                {phrase var='mobiletemplate.u_comment'}
               {/if}
            {/if}
            
           
		</div>
		
	</div><!-- // .activity_feed_content_text -->	

	{if isset($aFeed.feed_view_comment)}			
		{module name='feed.comment'}
	{else}
		{template file='feed.block.comment'}
	{/if}
	
	{if $aFeed.type_id != 'friend'}
		{if isset($aFeed.more_feed_rows) && is_array($aFeed.more_feed_rows) && count($aFeed.more_feed_rows)}
			{if $iTotalExtraFeedsToShow = count($aFeed.more_feed_rows)}{/if}
			<a href="#" class="activity_feed_content_view_more" onclick="$(this).parents('.js_feed_view_more_entry_holder:first').find('.js_feed_view_more_entry').show(); $(this).remove(); return false;">{phrase var='feed.see_total_more_posts_from_full_name' total=$iTotalExtraFeedsToShow full_name=$aFeed.full_name|shorten:40:'...'}</a>			
		{/if}			
	{/if}
{if !Phpfox::getService('profile')->timeline()}
	</div><!-- // .activity_feed_content -->
{/if}