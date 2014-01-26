<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
{literal}
<style type="text/css">
    table.yn_review {
		width: 100%;
    }
    table.yn_review td {
        text-align: left;
        vertical-align: top;
        padding:5px;
        color: #7B7B7B;
        font-size: 12px;
		width: 100%;
    }
    table.yn_review tr{
		border-bottom: 1px solid #E4E4E4;
		margin-bottom: 3px;
	}
    table.yn_review tr td{
		padding-top: 20px;
		padding-bottom: 20px;
	}
    table.yn_review td.detail_content
    {
		position: relative;
		width: 100%;
		display: block;
    }
    table.yn_review td.image_user
    {
        text-align: center;
        width: 80px;
    }
    table.yn_review td.review{
		text-align: center;
	}
    table.yn_review td.review div.anchor{
		text-align: center;
		position: relative;
		padding-top: 10px;
	}
    .add_new_review a
    {
        float: right;
        font-size: 12px;
        padding: 10px;
    }

    .rwdelete {
        background-position: 0 100%;
		display: block;
		width: 12px;
		position: absolute;
		right: 15px;
		top: 5px;
		height: 12px;
        background-image: url("{/literal}{$corepath}{literal}module/advancedmarketplace/static/image/default/manibuton.png");
		opacity: 0.4;
		filter: alpha(opacity = 40);
    }

    .rwdelete:hover {
		opacity: 1;
		filter: alpha(opacity = 100);
    }
	
	.ssbt {
		padding-left: 20px!important;
		padding-right: 20px!important;
		margin-top: 20px!important;
	}
	
	.owner-detail .user-image {
		width: 50px;
		float: left;
	}
	
	.owner-detail .anchor {
		
		margin-left:60px;
	}
	
	.detail_content .review-content {
		clear: both;
		width: 100%;
		padding-top: 15px;
		color: #474747;
	}
</style>
<script type="text/javascript" language="javascript">
    $Behavior.advmarket_ratingJS = function(){
		$(".rwdelete").click(function(evt){
			evt.preventDefault();
			
			if(confirm('{/literal}{phrase var='advancedmarketplace.are_you_sure' phpfox_squote=true}{literal}')){
				$.ajaxCall("advancedmarketplace.deleteReview", "rid=" + $(this).attr("ref"));
			}
			
			return false;
		});
	}
</script>
{/literal}
{if ($iCurrentUserId !== ((int)$aListing.user_id)) }
	<div class="add_new_review yn_reviewrating" id="yn_advmarketplace_rating">
		<input class="button ssbt" type="button" value="{phrase var='advancedmarketplace.add_new_review'}" />
	</div>
{else}
	<div class="add_new_review yn_reviewrating" id="yn_advmarketplace_rating">
		&nbsp;
	</div>
{/if}
<div class="clear"></div>
{if count($aRating)}
	<table class="yn_review">
		{foreach from=$aRating key=iKey item=aRate}
			<tr id="rw_ref_{$aRate.rate_id}">
				<td class="detail_content">
					{if phpfox::getUserParam('advancedmarketplace.delete_other_reviews') || (phpfox::getUserParam('advancedmarketplace.can_delete_own_review') && $aRate.user_id == phpfox::getUserId())}
						<a href="#" ref="{$aRate.rate_id}" class="rwdelete">&nbsp;</a>
					{/if}
					<div class="owner-detail">
						<div class="user-image">
							{img user=$aRate suffix='_50_square' max_width='50' max_height='50'}
						</div>
						<div class="anchor">
							<div class="">
								{*<div style="background: none;width: 140px;">{phrase var='advancedmarketplace.review'}</div>*}
								<div class="listing_rate" style="background: none;height: 18px;">
									<?php for($i = 1; $i <= $this->_aVars["aRate"]["rating"] / 2; $i++) {ldelim} ?>
										<img src="{$corepath}module/advancedmarketplace/static/image/default/staronsm.png" />
									<?php {rdelim} ?>
									<?php for($i = 1; $i <= 5 - $this->_aVars["aRate"]["rating"] / 2; $i++) {ldelim} ?>
										<img src="{$corepath}module/advancedmarketplace/static/image/default/staroffsm.png" />
									<?php {rdelim} ?>
								</div>
							</div>
							<div>
								{$aRate.timestamp|date:'advancedmarketplace.advancedmarketplace_view_time_stamp'}&nbsp;|&nbsp;{phrase var="advancedmarketplace.by"} {$aRate|user}
							</div>
						</div>
					</div>
					{*<div class="review-content">
						{phrase var="advancedmarketplace.this_review_is_from"} <a href="{url link='advancedmarketplace.detail.'.{$aListing.listing_id}{$aListing.title}">{$aListing.title}</a>
					</div>*}
					{if $aRate.content}
						<div class="review-content">
							{$aRate.content}
						</div>
					{/if}
				</td>
			</tr>
		{/foreach}
	</table>
{/if}
{if count($aRating) > 0}
	{pager}
{else}
	<div class="extra_info">
		{phrase var='advancedmarketplace.no_reviews_found'}
	</div>
{/if}
<input type="hidden" id="xf_page" value="{$page}" />