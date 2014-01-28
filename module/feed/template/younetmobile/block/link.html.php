{if PHPFOX_IS_AJAX && Phpfox::getLib('request')->get('theater') == 'true'}


{elseif isset($sFeedType) &&  $sFeedType == 'view'}
<div class="feed_share_custom">	
	{if Phpfox::isModule('share') && Phpfox::getParam('share.share_twitter_link')}
		<div class="feed_share_custom_block"><a href="http://twitter.com/share" class="twitter-share-button" data-url="{$aFeed.feed_link}" data-count="horizontal" data-via="{param var='feed.twitter_share_via'}">{phrase var='feed.tweet'}</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></div>
	{/if}
	{if Phpfox::isModule('share') && Phpfox::getParam('share.share_google_plus_one')}
	<div class="feed_share_custom_block">
		<g:plusone href="{$aFeed.feed_link}" size="medium"></g:plusone>
		{literal}
			<script type="text/javascript">
			  (function() {
				var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
				po.src = 'https://apis.google.com/js/plusone.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
			  })();
			</script>
		{/literal}
	</div>
	{/if}
	{if Phpfox::isModule('share') && Phpfox::getParam('share.share_facebook_like')}
		<div class="feed_share_custom_block">
			<iframe src="http://www.facebook.com/plugins/like.php?app_id=156226084453194&amp;href={if !empty($aFeed.feed_link)}{$aFeed.feed_link}{else}{url link='current'}{/if}&amp;send=false&amp;layout=button_count&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;width=90&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:140px; height:21px;" allowTransparency="true"></iframe>					
		</div>
	{/if}				
	<div class="clear"></div>
</div>
{/if}

<ul class="ym-feed-link">
	{if Phpfox::isUser() && Phpfox::isModule('like') && isset($aFeed.like_type_id)}
		{if isset($aFeed.like_item_id)}
			{module name='like.link' like_type_id=$aFeed.like_type_id like_item_id=$aFeed.like_item_id like_is_liked=$aFeed.feed_is_liked}
		{else}
			{module name='like.link' like_type_id=$aFeed.like_type_id like_item_id=$aFeed.item_id like_is_liked=$aFeed.feed_is_liked}
		{/if}	
	{/if}
	
	{if Phpfox::isUser() 
		&& Phpfox::isModule('comment') 
		&& Phpfox::getUserParam('feed.can_post_comment_on_feed')
		&& Phpfox::getUserParam('comment.can_post_comments')
		&& (isset($aFeed.comment_type_id) && $aFeed.can_post_comment) 
		|| (!isset($aFeed.comment_type_id) && isset($aFeed.total_comment))
		}				
	<li>
		{plugin call='mobiletemplate.get_var_to_hide_profile_header'}
		{if Phpfox::getLib('module')->getFullControllerName() != 'mobile.index' 
			&& Phpfox::getLib('module')->getFullControllerName() != 'core.index' 
			&& Phpfox::getLib('module')->getFullControllerName() != 'feed.index' 
			&& Phpfox::getLib('module')->getFullControllerName() != 'pages.index' 
			&& Phpfox::getLib('module')->getFullControllerName() != 'profile.index' 
			&& Phpfox::getLib('module')->getFullControllerName() != 'apps.index'
			&& Phpfox::getLib('module')->getFullControllerName() != 'poll.index'
			&& (Phpfox::getLib('module')->getFullControllerName() != 'pages.view' || (isset($isHideProfileHeader) && $isHideProfileHeader == 'yes'))
			&& (Phpfox::getLib('module')->getFullControllerName() != 'event.view' || (isset($isHideProfileHeader) && $isHideProfileHeader == 'yes'))
			}
		<a href="#" onclick="$('.js_comment_feed_textarea').focus(); return false;" class="">
		{else}
		<a href="{$aFeed.feed_link}add-comment/" class="">
		{/if}
		   <i class="icon-comment"></i> {phrase var='feed.comment'}
		</a>
	</li>				
	
	{/if}				
	{if Phpfox::isModule('share') && !isset($aFeed.no_share)}
		
		{if $aFeed.privacy == '0'}
			{module name='share.link' type='feed' display='menu' url=$aFeed.feed_link title=$aFeed.feed_title sharefeedid=$aFeed.item_id sharemodule=$aFeed.type_id}
		{else}
			{module name='share.link' type='feed' display='menu' url=$aFeed.feed_link title=$aFeed.feed_title}
		{/if}
	{/if}		
</ul>

<div class="clear"></div>