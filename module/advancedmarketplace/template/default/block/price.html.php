<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond_Benc
 * @package 		Phpfox
 * @version 		$Id: price.html.php 3533 2011-11-21 14:07:21Z Raymond_Benc $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
<div class="advancedmarketplace_price_holder" style="margin-bottom: 20px;">
	{$sListingPrice}
	{if $aListing.user_id != Phpfox::getUserId()}
	<a href="#" class="advancedmarketplace_contact_seller" onclick="$Core.composeMessage({l}user_id: {$aListing.user_id}{r}); return false;">{phrase var='advancedmarketplace.contact_seller'}</a>
	
	{if $aListing.is_sell && $aListing.view_id != '2' && $aListing.price != '0.00'}
	<div class="advancedmarketplace_price_holder_button">
		<form method="post" action="{url link='advancedmarketplace.purchase'}">
			<div><input type="hidden" name="id" value="{$aListing.listing_id}" /></div>
			{if Phpfox::isUser()}
			<input type="submit" value="{phrase var='advancedmarketplace.buy_it_now'}" class="button" />
            {else}
            <input type="button" class="button" onclick="window.location.href='{url link='user.login'}'" value="{phrase var='advancedmarketplace.login_to_buy'}" />
            {/if}
		</form>
	</div>
	{/if}	
	{/if}
</div>