<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

?>
{literal}
<style type="text/css">
    .petition_large_image img{max-width: 96%;max-height: 300px;}
    .petition_small_image ul li{
        display: inline-block;
        padding: 3px;
    }
    
    .detail_link ul li{padding:4px;}
    .detail_link ul li a
    {
        background: url({/literal}{$corepath}{literal}module/petition/static/image/view_more.png) scroll 0 50% transparent no-repeat;        
        padding-left: 14px;
        font-size: 14px;
    }
    /* PETITION STATUS */
    .petition_large_image{
        position: relative;
        padding-left: 2px;
        min-width: 115px;
        min-height: 115px;
		text-align:center;
    }
    .petition_victory{
        background: url({/literal}{$corepath}{literal}module/petition/static/image/victory.png) no-repeat top left;
        width: 115px; height: 115px;
        position: absolute;
        top: -2px;
        left: 0;
    }
    .petition_closed{
        background: url({/literal}{$corepath}{literal}module/petition/static/image/closed.png) no-repeat top left;
        width: 115px; height: 115px;
        position: absolute;
        top: -2px;
        left: 0;
    }
</style>
<script type="text/javascript">  
   $Behavior.marketplaceShowImage = function(){
         $('.js_petition_click_image').click(function(){
               var oNewImage = new Image();
               oNewImage.onload = function(){
                     $('#js_marketplace_click_image_viewer').show();
                     $('#js_marketplace_click_image_viewer_inner').html('<img src="' + this.src + '" style="max-width: 580px; max-height: 580px" alt="" />');			
                     $('#js_marketplace_click_image_viewer_close').show();
               };
               oNewImage.src = $(this).attr('href');
               
               return false;
         });
         
         $('#js_marketplace_click_image_viewer_close a').click(function(){
               $('#js_marketplace_click_image_viewer').hide();
               return false;
         });
   }
</script>
{/literal}
<div id="js_marketplace_click_image_viewer" style="width: 600px;">
	<div id="js_marketplace_click_image_viewer_inner">
		{phrase var='petition.loading'}
	</div>
	<div id="js_marketplace_click_image_viewer_close">
		<a href="#">{phrase var='petition.close'}</a>
	</div>
</div>
{if $aItem.petition_status != 2}
<div class="petition_large_image">
    {if $aItem.petition_status == 3}
        <div class="petition_victory"></div>
    {else if $aItem.petition_status == 1}
        <div class="petition_closed"></div>
    {/if}
{else}
<div class="petition_large_image">    
{/if}
    <a class="js_petition_click_image no_ajax_link" href="{img return_url=true server_id=$aItem.server_id title=$aItem.title path='core.url_pic' file=$aItem.image_path suffix=''}">
    {img server_id=$aItem.server_id title=$aItem.title path='core.url_pic' file=$aItem.image_path suffix='_300'}</a>
</div>
{if count($aImages) > 1}
<div class="petition_small_image">
    <ul>
        {foreach from=$aImages name=images item=aImage}
            <li><a class="js_petition_click_image no_ajax_link" href="{img return_url=true server_id=$aItem.server_id title=$aItem.title path='core.url_pic' file=$aImage.image_path suffix=''}">
            {img server_id=$aImage.server_id title=$aItem.title path='core.url_pic' file=$aImage.image_path suffix='_50' max_width='50' max_height='50'}</a></li>
        {/foreach}
    </ul>
    <div class="clear"></div>
</div>
{/if}
{if Phpfox::isAdmin() || $aItem.user_id == Phpfox::getUserId() || ($aItem.module_id == 'pages' && Phpfox::getService('pages')->isAdmin('' . $aItem.item_id . ''))}
<div class="detail_link">
    <ul>
        <li><a href="#" onclick="$Core.box('petition.inviteBlock',800,'&id={$aItem.petition_id}'); return false;">{phrase var='petition.invite_friends'}</a></li>
        {if !empty($aItem.target_email)}
        <li><a href="#" onclick="if( confirm('{phrase var='petition.are_you_sure_you_want_to_sent_petition_letter_to_target'}')) $(this).ajaxCall('petition.sentToTarget','id={$aItem.petition_id}'); return false;">{phrase var='petition.send_petition_to_target'}</a></li>
        {/if}
    </ul>
</div>
{/if}
