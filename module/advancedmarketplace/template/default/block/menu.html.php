<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package 		Phpfox
 * @version 		$Id: menu.html.php 3346 2011-10-24 15:20:05Z Raymond_Benc $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
	{if ($aListing.user_id == Phpfox::getUserId() && Phpfox::getUserParam('advancedmarketplace.can_edit_own_listing')) || Phpfox::getUserParam('advancedmarketplace.can_edit_other_listing')}
		<li><a href="{url link='advancedmarketplace.add' id=$aListing.listing_id}" title="{phrase var='advancedmarketplace.edit_listing'}">{phrase var='advancedmarketplace.edit_listing'}</a></li>	
		<li><a href="{url link='advancedmarketplace.add.customize' id=$aListing.listing_id}" title="{phrase var='advancedmarketplace.manage_photos'}">{phrase var='advancedmarketplace.manage_photos'}</a></li>	
		<li><a href="{url link='advancedmarketplace.add.invite' id=$aListing.listing_id}" title="{phrase var='advancedmarketplace.send_invitations'}">{phrase var='advancedmarketplace.send_invitations'}</a></li>	
		<li><a href="{url link='advancedmarketplace.add.manage' id=$aListing.listing_id}" title="{phrase var='advancedmarketplace.manage_invites'}">{phrase var='advancedmarketplace.manage_invites'}</a></li>	
	{/if}
	{if Phpfox::getUserParam('advancedmarketplace.can_feature_listings') && $aListing.post_status != 2}
		<li class="js_advancedmarketplace_is_feature" {if $aListing.is_featured} style="display:none;"{/if}><a href="#" onclick="$('#js_featured_phrase_{$aListing.listing_id}').show(); $.ajaxCall('advancedmarketplace.feature', 'listing_id={$aListing.listing_id}&amp;type=1', 'GET'); $(this).parent().hide(); $(this).parents('ul:first').find('.js_advancedmarketplace_is_un_feature').show(); return false;">{phrase var='advancedmarketplace.feature'}</a></li>
		<li class="js_advancedmarketplace_is_un_feature" {if !$aListing.is_featured} style="display:none;"{/if}><a href="#" onclick="$('#js_featured_phrase_{$aListing.listing_id}').hide(); $.ajaxCall('advancedmarketplace.feature', 'listing_id={$aListing.listing_id}&amp;type=0', 'GET'); $(this).parent().hide(); $(this).parents('ul:first').find('.js_advancedmarketplace_is_feature').show(); return false;">{phrase var='advancedmarketplace.un_feature'}</a></li>
	{/if}
	{if Phpfox::getUserParam('advancedmarketplace.can_sponsor_advancedmarketplace') && $aListing.post_status != 2}
	<li>
	    <span id="js_sponsor_{$aListing.listing_id}">
			    {if $aListing.is_sponsor}
		<a href="#" onclick="$('#js_sponsor_phrase_{$aListing.listing_id}').hide(); $.ajaxCall('advancedmarketplace.sponsor','listing_id={$aListing.listing_id}&type=0', 'GET'); return false;">
			    {phrase var='advancedmarketplace.unsponsor_this_listing'}
		</a>
			    {else}
		<a href="#" onclick="$('#js_sponsor_phrase_{$aListing.listing_id}').show(); $.ajaxCall('advancedmarketplace.sponsor','listing_id={$aListing.listing_id}&type=1', 'GET'); return false;">
				    {phrase var='advancedmarketplace.sponsor_this_listing'}
		</a>
			    {/if}
	    </span>
	</li>
	{elseif Phpfox::getUserParam('advancedmarketplace.can_purchase_sponsor') 
	&& $aListing.user_id == Phpfox::getUserId()
	&& $aListing.is_sponsor != 1  && $aListing.post_status != 2}
	<li>
	    <a href="{permalink module='ad.sponsor' id=$aListing.listing_id}section_advancedmarketplace/">
			    {phrase var='advancedmarketplace.sponsor_this_listing'}
	    </a>
	</li>
	{/if}
	{if ($aListing.user_id == Phpfox::getUserId() && Phpfox::getUserParam('advancedmarketplace.can_delete_own_listing')) || Phpfox::getUserParam('advancedmarketplace.can_delete_other_listings')}
		<li class="item_delete"><a href="{url link='advancedmarketplace' delete=$aListing.listing_id}" class="sJsConfirm">{phrase var='advancedmarketplace.delete_listing'}</a></li>
	{/if}	