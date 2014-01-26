<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
{literal}
<style type="text/css">
	{/literal}{if count($aImages) > 1}{literal}
	#right {
		margin-top: 40px;
	}
	{/literal}{/if}{literal}
	
	.slideblock {
		clear: both;
		text-align: center;
	}
	
	.slider {
		height: 85px;
		padding-top: 30px;
		padding-left: 30px;
		background-color: #F7F7F7;
	}
	
	.slider ul {
		list-style: none;
		list-style: none outside none;
		position: absolute;
		top: 0;
		width: 1234px;
		display: block;
	}
	
	.slider ul li {
		float: left;
		margin-right: 10px;
	}
	
	div.rightnav {
		display: inline-block;
		float: left;
		margin-top: 6px;
	}
	
	div.leftnav {
		display: inline-block;
		float: left;
		margin-top: 6px;
	}
	
	div.slider2 {
		float: left;
		height: 50px;
		margin: 0 35px;
		overflow: hidden;
		position: relative;
		width: 355px;
	}
	
</style>
<script language="javasript" type="text/javascript">
	$Behavior.detailSlide = (function(){
		$("img.buff").hide();
		$(".slideclick").click(function(evt){
			evt.preventDefault();
			$img = $("<img>").attr({
				"src": $(this).attr("ref"),
				"style": "max-width:520px;max-height:322px;"
			});
			$(".bigimg").html($img);
			$img.removeAttr('width');
			$img.removeAttr('height');
			return false;
		});
		var pmcc = $(".slider2").find("li").size();
		if(pmcc <= 6) {
			$(".leftnav").css({
				"visibility": "hidden"
			});
			$(".rightnav").css({
				"visibility": "hidden"
			});
			$(".slider2").find("ul").css({
				"display": "inline-block",
				/* "width": "auto", */
				"position": "relative"
			});
			$(".slider2").css({
				"text-align": "center"
			});
		}
		var pncc = 0;
		var flg = false;
		$(".leftnav").find("a").css({
			"opacity": "0.3"
		}).mouseover(function(){
			$(this).css({
				"opacity": 1
			});
		}).mouseout(function(){
			$(this).css({
				"opacity": "0.3"
			});
		});
		$(".rightnav").find("a").css({
			"opacity": "0.3"
		}).mouseover(function(){
			$(this).css({
				"opacity": 1
			});
		}).mouseout(function(){
			$(this).css({
				"opacity": "0.3"
			});
		});
		$(".leftnav").find("a").click(function(evt){
			evt.preventDefault();
			if(pncc >= pmcc - 6 || flg)return false;
			pncc++;
			flg = true;
			$(".slider2").find("ul").stop(false, false).animate({
				"left": ("-=" + (60) + "px")
			}, function(){flg = false;});
			
			return false;
		});
		$(".rightnav").find("a").click(function(evt){
			evt.preventDefault();
			if(pncc <= 0 || flg)return false;
			pncc--;
			flg = true;
			$(".slider2").find("ul").stop(false, false).animate({
				"left": ("+=" + (60) + "px")
			}, function(){flg = false;});
			
			return false;
		});
	});
</script>
{/literal}

{if (Phpfox::getParam('advancedmarketplace.days_to_expire_listing') > 0) && ( $aListing.time_stamp < (PHPFOX_TIME - (Phpfox::getParam('advancedmarketplace.days_to_expire_listing') * 86400)) )}
<div class="error_message">
	{phrase var='advancedmarketplace.listing_expired_and_not_available_main_section'}
</div>
{/if}

{if ($aListing.user_id == Phpfox::getUserId() && Phpfox::getUserParam('advancedmarketplace.can_edit_own_listing')) || Phpfox::getUserParam('advancedmarketplace.can_edit_other_listing')
	|| ($aListing.user_id == Phpfox::getUserId() && Phpfox::getUserParam('advancedmarketplace.can_delete_own_listing')) || Phpfox::getUserParam('advancedmarketplace.can_delete_other_listings')
	|| (Phpfox::getUserParam('advancedmarketplace.can_feature_listings'))
}
<div class="item_bar">
	{if $aListing.view_id == '1'}
	<div class="message js_moderation_off">
		{phrase var='advancedmarketplace.listing_is_pending_approval'}
	</div>
	{/if}
	<div class="item_bar_action_holder">
	{if (Phpfox::getUserParam('advancedmarketplace.can_approve_listings') && $aListing.view_id == '1')}
		<a href="#" class="item_bar_approve item_bar_approve_image" onclick="return false;" style="display:none;" id="js_item_bar_approve_image">{img theme='ajax/add.gif'}</a>			
		<a href="#" class="item_bar_approve" onclick="$(this).hide(); $('#js_item_bar_approve_image').show(); $.ajaxCall('advancedmarketplace.approve', 'inline=true&amp;listing_id={$aListing.listing_id}'); return false;">{phrase var='advancedmarketplace.approve'}</a>
	{/if}
		<a href="#" class="item_bar_action"><span>{phrase var='advancedmarketplace.actions'}</span></a>	
		<ul>
			{template file='advancedmarketplace.block.menu'}
		</ul>			
	</div>
</div>
{/if}
{if phpfox::isMobile()}
	{module name="advancedmarketplace.detailview"}
	{module name="advancedmarketplace.price"}
{/if}
{if !phpfox::isMobile()}
{if $aListing.image_path != NULL}
	<div class="slideblock" >
		
		<div style="width: 520px; height: 322px;" class="bigimg">
			{if $aListing.image_path != NULL}
                {img server_id=$aListing.server_id title=$aListing.title path='core.url_pic' file='advancedmarketplace/'.$aListing.image_path suffix='' max_width='520' max_height='322'}
            {else}
                <img title="{$aListing.title}" src="<?php echo Phpfox::getLib('template')->getStyle('image', 'noimage/item.png'); ?>" style="max-width:520px;max-height:322px" />
            {/if}
		</div>
		
		{if count($aImages) > 1}
		<div class="thumbnail">
			<div class="window">
				<div class="slider">
					<div class="leftnav">
						<a href="">
							<img src="{$corepath}module/advancedmarketplace/static/image/default/aleft.png" />
						</a>
					</div>
					<div class="slider2">
					{foreach from=$aImages name=images item=aImage}
						<img style="visibility: hidden;" class="buff" src="{img server_id=$aImage.server_id path='core.url_pic' file='advancedmarketplace/'.$aImage.image_path suffix='' return_url=true}" />
					{/foreach}
						<ul>
							{foreach from=$aImages name=images item=aImage}
								<li>
                                    <a ref="{img server_id=$aImage.server_id path='core.url_pic' file='advancedmarketplace/'.$aImage.image_path suffix='' return_url=true}" class="slideclick js_marketplace_click_image no_ajax_link" href="{img server_id=$aImage.server_id path='core.url_pic' file='advancedmarketplace/'.$aImage.image_path suffix='_50_square' return_url=true}">
										{img server_id=$aImage.server_id path='core.url_pic' file='advancedmarketplace/'.$aImage.image_path suffix='_50_square' max_width='50' max_height='50'}
									</a>
								</li>
							{/foreach}
						</ul>
					</div>
					<div class="rightnav">
						<a href="">
							<img src="{$corepath}module/advancedmarketplace/static/image/default/aright.png" />
						</a>
					</div>
				</div>
			</div>
			<div class="btholder prev">
			</div>
			<div class="btholder next">
			</div>
		</div>
		{/if}
	</div>
	{/if}
{/if}