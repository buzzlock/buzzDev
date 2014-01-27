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
        background-image:url({/literal}{$corepath}{literal}module/fundraising/static/image/pagination.png);
        background-position:0 0;
        float:left;
        overflow:hidden;
    }
    #slides .prev {
	background: url({/literal}{$corepath}{literal}module/fundraising/static/image/arrow-prev.png) top left no-repeat;
    }
    
    #slides .prev:hover {
         background: url({/literal}{$corepath}{literal}module/fundraising/static/image/arrow-next.png) top left no-repeat;
    }
    
    #slides .next {
         left:471px;
         background: url({/literal}{$corepath}{literal}module/fundraising/static/image/arrow-next.png) top right no-repeat;
    }
    
    #slides .next:hover {
         background: url({/literal}{$corepath}{literal}module/fundraising/static/image/arrow-prev.png) top right no-repeat;
    }
    .pet_img_tit > img {max-width: 145px;max-height: 145px;float:left;}
    #js_block_border_fundraising_featured{background: #ececec;padding-left: 10px; margin-bottom: 25px !important;}
    #js_block_border_fundraising_featured div.row_title_info{margin-left: 170px;}    
</style>
{/literal}
<link rel="stylesheet" href="{$corepath}module/fundraising/static/css/default/default/global.css" type="text/css"/>
<div class="block" id="js_block_border_fundraising_featured">
    <div class="content">
        <div id="example">
            <h3><strong>{phrase var='fundraising.featured_fundraisings'}</strong></h3>
            <div id="slides">
                <div class="slides_container">
                {foreach from=$aFeatured item=aFundraising name=Featured}
                    <div class="slide">
                        {*
                        {if $aFundraising.status == 3}
                           <div class="row_sponsored_link">
                                  {phrase var='fundraising.victory'}
                           </div>
                        {else}
                           <div class="js_featured_fundraising row_featured_link"{if !$aFundraising.is_featured} style="display:none;"{/if}>
                              {phrase var='fundraising.featured'}
                           </div>
                        {/if}
                        *}
                        <a target="_self" href="{permalink module='fundraising' id=$aFundraising.campaign_id title=$aFundraising.title}" class="row_sub_link" title="{$aFundraising.title|clean}">
                        <div class="pet_img_tit">                            
                                {img server_id=$aFundraising.server_id path='core.url_pic' file=$aFundraising.image_path suffix='_300' max_width=150 class='photo_holder'}                            
                        </div>
                        </a>		
                        <div class="row_title_info">		
                            <a class="link1" target="_self" href="{permalink module='fundraising' id=$aFundraising.campaign_id title=$aFundraising.title}" class="row_sub_link" title="{$aFundraising.title|clean}">{$aFundraising.title|clean|shorten:50:'...'|split:20}</a>		
                            <div class="extra_info">{phrase var='fundraising.created_by'} {$aFundraising|user} {phrase var='fundraising.in'} <a href="{$aFundraising.category.link}">{$aFundraising.category.name}</a>
                                </br> {if $aFundraising.is_directsign == 1}<span class="total_sign">{$aFundraising.total_sign}</span>{phrase var='fundraising.total_sign_signatures' total_sign=''}{else}{phrase var='fundraising.total_sign_signatures' total_sign=$aFundraising.total_sign}{/if} - {phrase var='fundraising.total_like_likes' total_like=$aFundraising.total_like} - {phrase var='fundraising.total_view_views' total_view=$aFundraising.total_view}
                            </div>
                            <div class="item_content">
                                {$aFundraising.short_description|shorten:75:'...'|split:55}
                            </div>	
                            <div class="clear"></div>
                        </div>
                    </div>
                {/foreach}
                </div>
                    <a href="JavaScript:void(0);" class="prev"></a>
                    <a href="JavaScript:void(0);" class="next"></a>
                
            </div>
        </div>
    </div>
</div>
{literal}
<script>
   $Behavior.initNextButton = (function(){
      $.getScript("{/literal}{$corepath}module/fundraising/static/jscript/slides.min.jquery.js{literal}", function() {
        var startSlide = 1;
        $('#slides').slides({
            preload: true,
            preloadImage: '{/literal}{$corepath}{literal}module/fundraising/static/image/loading.gif',
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

