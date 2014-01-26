<?php 
 
defined('PHPFOX') or exit('NO DICE!'); 

?>

<div class="table_header">
	{phrase var='advancedmarketplace.listing_statistics'}
</div>
<div class="table">
	<div class="table_left">
		{phrase var='advancedmarketplace.total_listings'}:
	</div>
	<div class="table_right">
		{$aListingStatistics.total_listings}
	</div>
	<div class="table_clear"></div>
</div>
<div class="table">
	<div class="table_left">
		{phrase var='advancedmarketplace.total_available_listings'}:
	</div>
	<div class="table_right">
		{$aListingStatistics.available_listings}
	</div>
	<div class="table_clear"></div>
</div>
<div class="table">
	<div class="table_left">
		{phrase var='advancedmarketplace.total_closed_listings'}:
	</div>
	<div class="table_right">
		{$aListingStatistics.closed_listings}
	</div>
	<div class="table_clear"></div>
</div>
<div class="table">
	<div class="table_left">
		{phrase var='advancedmarketplace.total_draft_listings'}:
	</div>
	<div class="table_right">
		{$aListingStatistics.draft_listings}
	</div>
	<div class="table_clear"></div>
</div>
<div class="table">
	<div class="table_left">
		{phrase var='advancedmarketplace.total_approved_listings'}:
	</div>
	<div class="table_right">
		{$aListingStatistics.approved_listings}
	</div>
	<div class="table_clear"></div>
</div>
<div class="table">
	<div class="table_left">
		{phrase var='advancedmarketplace.total_featured_listings'}:
	</div>
	<div class="table_right">
		{$aListingStatistics.featured_listings}
	</div>
	<div class="table_clear"></div>
</div>
<div class="table">
	<div class="table_left">
		{phrase var='advancedmarketplace.total_sponsored_listings'}:
	</div>
	<div class="table_right">
		{$aListingStatistics.sponsored_listings}
	</div>
	<div class="table_clear"></div>
</div>
<div class="table">
	<div class="table_left">
		{phrase var='advancedmarketplace.total_reviews'}:
	</div>
	<div class="table_right">
		{$aListingStatistics.total_reviews}
	</div>
	<div class="table_clear"></div>
</div>
<div class="table">
	<div class="table_left">
		{phrase var='advancedmarketplace.total_reviewed_listings'}:
	</div>
	<div class="table_right">
		{$aListingStatistics.total_reviewed_listings}
	</div>
	<div class="table_clear"></div>
</div>
