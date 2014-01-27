<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

?>
<div class="viewed_listing">
{foreach from=$aRecent key=iKey item=aFundraising name=Recent}
<div class="view_content_listing{if $iKey == count($aRecent)-1} last {/if}">
        <div class="row_listing_image">
            <a href="{permalink module='fundraising' id=$aFundraising.campaign_id title=$aFundraising.title}" title="{$aFundraising.title|clean}">{img server_id=$aFundraising.server_id path='core.url_pic' file=$aFundraising.image_path suffix='_120' max_width=90 max_height=90}</a>            
        </div>
        <div class="row_title_info">            
            <a class="row_sub_link"  href="{permalink module='fundraising' id=$aFundraising.campaign_id title=$aFundraising.title}" class="row_sub_link" title="{$aFundraising.title|clean}">{$aFundraising.title|clean|shorten:20:'...'|split:20}</a>
           <div>
                {$aFundraising.short_description|clean|shorten:75:'...'}
                <br/>
                <div class="extra_info stats">
                    {if $aFundraising.is_directsign == 1}<span class="total_sign">{$aFundraising.total_sign}</span>{phrase var='fundraising.total_sign_signatures' total_sign=''}{else}{phrase var='fundraising.total_sign_signatures' total_sign=$aFundraising.total_sign}{/if}<br/>{phrase var='fundraising.total_like_likes' total_like=$aFundraising.total_like} - {phrase var='fundraising.total_view_views' total_view=$aFundraising.total_view}
                </div>
            </div>
        </div>	
</div>
<div class="clear"></div>
{/foreach}
<div class="clear"></div>
{if $iTotal > 4}
<div style="padding-top: 10px; text-align:right;"><a href="{url link='fundraising' status='0' view='listing' sort='latest'}">{phrase var='fundraising.view_all'}</a></div>
{/if}
</div>
