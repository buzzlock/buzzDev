<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

?>
<div class="viewed_listing">
{foreach from=$aPopular key=iKey item=aPetition name=Popular}
<div class="view_content_listing{if $iKey == count($aPopular)-1} last {/if}">
        <div class="row_listing_image">
            <a href="{permalink module='petition' id=$aPetition.petition_id title=$aPetition.title}" title="{$aPetition.title|clean}">{img server_id=$aPetition.server_id path='core.url_pic' file=$aPetition.image_path suffix='_120' max_width=90 max_height=90}</a>            
        </div>
        <div class="row_title_info">            
            <a class="row_sub_link"  href="{permalink module='petition' id=$aPetition.petition_id title=$aPetition.title}" class="row_sub_link" title="{$aPetition.title|clean}">{$aPetition.title|clean|shorten:20:'...'|split:20}</a>
            <div>
                {$aPetition.short_description|clean|shorten:75:'...'}
                <br/>
                <div class="extra_info stats">
                    {if $aPetition.is_directsign == 1}<span class="total_sign">{$aPetition.total_sign}</span>{phrase var='petition.total_sign_signatures' total_sign=''}{else}{phrase var='petition.total_sign_signatures' total_sign=$aPetition.total_sign}{/if} <br/>{phrase var='petition.total_like_likes' total_like=$aPetition.total_like} - {phrase var='petition.total_view_views' total_view=$aPetition.total_view}
                </div>
            </div>
        </div>
</div>
<div class="clear"></div>
{/foreach}
<div class="clear"></div>
{if $iTotal > 4}
<div style="padding-top: 10px; text-align:right;"><a href="{url link='petition' status='0' view='listing' sort='most-popular'}">{phrase var='petition.view_all'}</a></div>
{/if}
</div>
