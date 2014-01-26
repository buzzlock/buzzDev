<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package 		Phpfox
 * @version 		$Id: mini.html.php 2592 2011-05-05 18:51:50Z Raymond_Benc $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
	<li>
		<div class="block_listing_image" style="width: 65px;">
			<a href="{permalink module='advancedmarketplace.detail' id=$aMiniListing.listing_id title=$aMiniListing.title}">
				{if $aMiniListing.image_path != NULL}
                    {img title=$aMiniListing.title server_id=$aMiniListing.server_id path='core.url_pic' file='advancedmarketplace/'.$aMiniListing.image_path suffix='_120' style="max-height: 60px; max-width: 60px; margin-right: 5px;"}
                    <!--img title="{$aMiniListing.title}" src="<?php echo $this->_aVars['advancedmarketplace_url_image'] . PHPFOX::getService("advancedmarketplace")->proccessImageName($this->_aVars["aMiniListing"]["image_path"], "_120"); ?>" style="max-height: 120px; max-width: 60px; margin-right: 5px;" /-->
				{else}
					<img title="{$aMiniListing.title}" src="{$corepath}module/advancedmarketplace/static/image/default/noimage.png" max-width='60' max-height='60' style="max-height: 60px; max-width: 60px; margin-right: 5px;" />
				{/if}
			</a>
		</div>
		<div class="row_title_info">
            <a class="advlink" href="{$aMiniListing.url}" title="{$aMiniListing.title|parse|clean}">{$aMiniListing.title|shorten:50:'...'|split:25}</a>
            <div class="advancedmarketplace_price_tag" style="padding-left: 5px;">
				{if $aMiniListing.price == '0.00'}
				{phrase var='advancedmarketplace.free'}
				{else}
				{$aMiniListing.currency_id|currency_symbol}{$aMiniListing.price}
				{/if}
			</div>
            <div class="detail_listing_info">{$aMiniListing.time_stamp|convert_time} <span>&middot;</span> {$aMiniListing|user} <span>&middot;</span> <a class="js_hover_title" href="{url link='advancedmarketplace' location=$aMiniListing.country_iso}">{$aMiniListing.country_iso|location}<span class="js_hover_info">{if !empty($aMiniListing.city)} {$aMiniListing.city|clean} &raquo; {/if}{if !empty($aMiniListing.country_child_id)} {$aMiniListing.country_child_id|location_child} &raquo; {/if} {$aMiniListing.country_iso|location}</span></a></div>
        </div>
		<div class="clear"></div>
	</li>