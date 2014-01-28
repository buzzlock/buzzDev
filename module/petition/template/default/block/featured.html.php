<?php 
/**
 * [PHPFOX_HEADER]
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
{literal}
<style typr="text/css">
    .pagination li a {
        display:block;
        width:12px;
        height:0;
        padding-top:12px;
        background-image:url({/literal}{$corepath}{literal}module/petition/static/image/pagination.png);
        background-position:0 0;
        float:left;
        overflow:hidden;
    }
    #slides .prev {
	background: url({/literal}{$corepath}{literal}module/petition/static/image/arrow-prev.png) top left no-repeat;
    }
    
    #slides .prev:hover {
         background: url({/literal}{$corepath}{literal}module/petition/static/image/arrow-next.png) top left no-repeat;
    }
    
    #slides .next {
         left:471px;
         background: url({/literal}{$corepath}{literal}module/petition/static/image/arrow-next.png) top right no-repeat;
    }
    
    #slides .next:hover {
         background: url({/literal}{$corepath}{literal}module/petition/static/image/arrow-prev.png) top right no-repeat;
    }
    .pet_img_tit > img {max-width: 145px;max-height: 145px;float:left;}
    #js_block_border_petition_featured{background: #ececec;padding-left: 10px; margin-bottom: 25px !important;}
    #js_block_border_petition_featured div.row_title_info{margin-left: 170px;}    
</style>
{/literal}

<link rel="stylesheet" href="{$corepath}module/petition/static/css/default/default/global.css" type="text/css"/>
<div class="block" id="js_block_border_petition_featured">
    <div class="content">
        <div id="example" {if phpfox::isMobile()}class="ynp-content-featured"{/if}>
            <h3><strong>{phrase var='petition.featured_petitions'}</strong></h3>
			{if !phpfox::isMobile()}
            <div id="slides">
                <div class="slides_container">
                {foreach from=$aFeatured item=aPetition name=Featured}
                    <div class="slide">
                        {*
                        {if $aPetition.petition_status == 3}
                           <div class="row_sponsored_link">
                                  {phrase var='petition.victory'}
                           </div>
                        {else}
                           <div class="js_featured_petition row_featured_link"{if !$aPetition.is_featured} style="display:none;"{/if}>
                              {phrase var='petition.featured'}
                           </div>
                        {/if}
                        *}
                        <a target="_self" href="{permalink module='petition' id=$aPetition.petition_id title=$aPetition.title}" class="row_sub_link" title="{$aPetition.title|clean}">
                        <div class="pet_img_tit">                            
                                {img server_id=$aPetition.server_id path='core.url_pic' file=$aPetition.image_path suffix='_300' max_width=150 class='photo_holder'}                            
                        </div>
                        </a>		
                        <div class="row_title_info">		
                            <a class="link1" target="_self" href="{permalink module='petition' id=$aPetition.petition_id title=$aPetition.title}" class="row_sub_link" title="{$aPetition.title|clean}">{$aPetition.title|clean|shorten:50:'...'|split:20}</a>		
                            <div class="extra_info">{phrase var='petition.created_by'} {$aPetition|user} {phrase var='petition.in'} <a href="{$aPetition.category.link}">{$aPetition.category.name}</a>
                                </br> {if $aPetition.is_directsign == 1}<span class="total_sign">{$aPetition.total_sign}</span>{phrase var='petition.total_sign_signatures' total_sign=''}{else}{phrase var='petition.total_sign_signatures' total_sign=$aPetition.total_sign}{/if} - {phrase var='petition.total_like_likes' total_like=$aPetition.total_like} - {phrase var='petition.total_view_views' total_view=$aPetition.total_view}
                            </div>
                            <div class="item_content">
                                {$aPetition.short_description|shorten:75:'...'|split:55}
                            </div>	
                            <div class="clear"></div>
                        </div>
                    </div>
                {/foreach}
                </div>
                    <a href="JavaScript:void(0);" class="prev"></a>
                    <a href="JavaScript:void(0);" class="next"></a>
                
            </div>
			{literal}
			<script>
			   $Behavior.initNextButton = (function(){
				  $.getScript("{/literal}{$corepath}module/petition/static/jscript/slides.min.jquery.js{literal}", function() {
					var startSlide = 1;
					$('#slides').slides({
						preload: true,
						preloadImage: '{/literal}{$corepath}{literal}module/petition/static/image/loading.gif',
						generatePagination: true,
						play: 5000,
						pause: 2500,
						hoverPause: true,
						start: startSlide,
						animationComplete: function(current){
						}
					});
					$(".prev").css('right', $(".pagination").width()+5);
				  });
						
			   });
			</script>
			{/literal}
			
			{else}
				{foreach from=$aFeatured item=aPetition name=Featured}
					{template file='petition.block.mobile-entry'}
				{/foreach}
			{/if}
        </div>
    </div>
</div>

	


