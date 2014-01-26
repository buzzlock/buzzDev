<?php 
/**
 * [PHPFOX_HEADER]
 *
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package 		Phpfox
 * @version 		$Id: sponsored.html.php 1559 2010-05-04 13:06:56Z Miguel_Espinoza $
 */

defined('PHPFOX') or exit('NO DICE!'); 

?>
{literal}
<style type="text/css">
	.detail_listing_info {
		padding-left: 5px;
	}
</style>
{/literal}
<ul class="block_listing">
{foreach from=$aSponsorListings name=listings item=aListing}
<li>
    <div class="block_listing_image" style="width: 65px;">
		<a href="{url link='ad.sponsor' view=$aListing.sponsor_id}" title="{$aListing.title|parse|clean}">{*
			*}{if $aListing.image_path != NULL}{*
				*}<img title="{$aListing.title}" src="<?php echo $this->_aVars['advancedmarketplace_url_image'] . PHPFOX::getService("advancedmarketplace")->proccessImageName($this->_aVars["aListing"]["image_path"], "_120"); ?>" style="max-height: 120px; max-width: 60px; margin-right: 5px;" />{*
			*}{else}{*
				*}<img title="{$aListing.title}" src="{$corepath}module/advancedmarketplace/static/image/default/noimage.png" style="max-height: 60px; max-width: 60px; margin-right: 5px;" />{*
			*}{/if}{*
		*}</a>
    </div>
    <div class="row_title_info">
        <a class="advlink" href="{url link='ad.sponsor' view=$aListing.sponsor_id}" title="{$aListing.title|parse|clean}">{$aListing.title|shorten:50:'...'|split:25}</a>
        <div class="advancedmarketplace_price_tag" style="padding-left: 5px;">
			{if $aListing.price == '0.00'}
			{phrase var='advancedmarketplace.free'}
			{else}
			{$aListing.currency_id|currency_symbol}{$aListing.price|number_format:2}
			{/if}
		</div>
        <div class="detail_listing_info">{$aListing.time_stamp|convert_time} <span>&middot;</span> {$aListing|user} <span>&middot;</span> <a class="js_hover_title" href="{url link='advancedmarketplace' location=$aListing.country_iso}">{$aListing.country_iso|location}<span class="js_hover_info">{if !empty($aListing.city)} {$aListing.city|clean} &raquo; {/if}{if !empty($aListing.country_child_id)} {$aListing.country_child_id|location_child} &raquo; {/if} {$aListing.country_iso|location}</span></a></div>
    </div>
    <div class="clear"></div>
</li>
{/foreach}
</ul>
