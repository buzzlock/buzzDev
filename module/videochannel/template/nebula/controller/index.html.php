<?php 

defined('PHPFOX') or exit('NO DICE!'); 
?>
{$sJs}

{if $bIsUserTimeLine}
{literal}
<script language="javascript" type="text/javascript">
    $Behavior.addCustomSubMenus = function(){
        $('#section_menu ul').last().append($('.buton_on_top_right_for_timeline').html());
        $('.buton_on_top_right_for_timeline').html('');
    }
</script>
{/literal}
<ul class="buton_on_top_right_for_timeline" style="display: none;">
    {foreach from=$aCustomSubMenus key=iKey name=submenu item=aSubMenu}
    <li>
        <a href="{url link=$aSubMenu.url)}" class="ajax_link">
            {if $aSubMenu.showAddButton}
                {img theme='layout/section_menu_add.png' class='v_middle'}
            {/if}
            {$aSubMenu.phrase}
        </a>
    </li>
    {/foreach}
</ul>
{/if}

{if isset($aSlideShowVideos) && isset($aVideos)}
<style type="text/css">
.slide_info{l}
    background:url({$sCorePath}module/videochannel/static/image/black50.png);
{r}
</style>
<div class="block" id="yn_slide_show_block">
    <div class="title">{phrase var='videochannel.featured_videos'}</div>
    <div class="border">
        <div class="content">
			{if !phpfox::isMobile()}
            <div style="opacity: 0;filter: alpha(opacity = 0);" id="jhslider" class="jhslider">
				<ul>
				 {foreach from=$aSlideShowVideos item=aVideo name=af}
				 {*<a href="{permalink module='videochannel' id=$aVideo.video_id title=$aVideo.title}">*}
					<li>
						<div class="jhslider-info-detail">
							<a href="{permalink module='videochannel' id=$aVideo.video_id title=$aVideo.title}"><strong style="text-transform:uppercase;">{$aVideo.title|clean|shorten:50:"...":false}</strong></a>
							{*<div class="highlight"> {$aVideo.text|clean|shorten:150:"...":false}</div>*}
							<div> {$aVideo.total_view} {phrase var='videochannel.views'} - {phrase var='videochannel.by_lowercase'}: {$aVideo|user|shorten:20:'...'|split:20}</div>
						
						</div>
						<div class="jhslider-info-quick">
							
							{img server_id=$aVideo.server_id title='' path='video.url_image' file=$aVideo.image_path suffix='_120' onerror=$sImageOnError}
						
						</div>
						{img class="big-image" thickbox=true server_id=$aVideo.server_id title=$aVideo.title path='video.url_image' file=$aVideo.image_path suffix='_480' onerror=$sImageOnError}
					</li>
				{*</a>*}
				{/foreach}	
				</ul>
				{literal}
				<script type="text/javascript" language="javascript">
                    
                    $Behavior.setupSlideShow = function() {
						setTimeout(function(){
                            if($(".jhslider").hasClass("init-ed")) {
                                return false;
                            }
                            $(".jhslider").unbind();
                            $(".jhslider").children().unbind();

                            var x = setTimeout("null", 0);
                            for (var i = 0 ; i < x ; i++) {
                                clearTimeout(i);
                            }
                            $(".jhslider").css({
                                "opacity": "1"
                            });

                            $(".jhslider").JHSlide(5000, 400);
                            $(".jhslider").addClass("init-ed");
                        }, 100);
                    };
                    $Behavior.VideoChannelLoadingSlideShow = function(){
                        $(window).load(function() {
							var my_regex = /static\/image\/noimage/;
							$('.big-image').each(
								function(index, dom){
									if($(dom).width() == 100 || $(dom).width() == 120)
									{
										$(dom).attr('src', $(dom).attr('onerror'));
									}
								}
							);
						});
                    }
				</script>
				{/literal}
			</div>
			{else}
				{foreach from=$aSlideShowVideos item=aVideo name=af}
					{if $phpfox.iteration.af < 5}
						{template file='videochannel.block.mobile-featured'}
					{/if}
				{/foreach}	
				<div class="clear"></div>
			{/if}
		</div>
    </div>
</div>



{/if}

{if $sSortTitle}
	<div class='block'>
		<div class="title">{$sSortTitle}</div>
	</div>
{/if}


<div id="TB_ajaxContent"></div>
{if isset($aChannels)}
	  {if !count($aChannels)}
	  <div class="extra_info">
		  {phrase var='videochannel.no_channels_found'}
	  </div>
	  {else}
	        {foreach from=$aChannels key=count item=channel}
			  {if !phpfox::isMobile()}
					{template file='videochannel.block.channel.entry'}   
				{else}
					{template file='videochannel.block.channel.entry-mobile'}   
				{/if}	  
		  {/foreach}		    
		  <div class="clear"></div>
		  {if (Phpfox::getUserParam('videochannel.can_add_channels')) || ($bCanAddChannelInPage)}
		  {moderation}
		  {/if}
		  {pager}	  
	  {/if}	  
{/if}
{if isset($aVideos)}
	  {if !count($aVideos)}
	  <div class="extra_info">
		  {phrase var='videochannel.no_videos_found'}
	  </div>
	  {else}
	  <div id="js_video_edit_form_outer" style="display:none;">
		  <form method="post" action="#" onsubmit="$(this).ajaxCall('videochannel.viewUpdate'); return false;">
			  <div id="js_video_edit_form"></div>
			  <div class="table_clear">
				  <ul class="table_clear_button">
					  <li><input type="submit" value="{phrase var='videochannel.update'}" class="button" /></li>
					  <li><a href="#" id="js_video_go_advanced" class="button_off_link">{phrase var='videochannel.go_advanced_uppercase'}</a></li>
					  <li><a href="#" onclick="$('#js_video_edit_form_outer').hide(); $('#js_video_outer_body').show(); return false;" class="button_off_link">{phrase var='videochannel.cancel_uppercase'}</a></li>
				  </ul>
				  <div class="clear"></div>
			  </div>
		  </form>
	  </div>
	  
	  <div id="js_video_outer_body">
		  {foreach from=$aVideos name=videos item=aVideo}
			  {template file='videochannel.block.entry'}
		  {/foreach}
		  <div class="clear"></div>
		  {if Phpfox::getUserParam('videochannel.can_approve_videos') || Phpfox::getUserParam('videochannel.can_delete_other_video') }
		  {moderation}
		  {/if}
		  {pager}	
	  </div>
	  {/if}
{/if}

