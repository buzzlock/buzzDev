<?php
?>
<script type="text/javascript" src="{$core_url}static/jscript/jquery/plugin/star/jquery.rating.js"></script>
<link rel="stylesheet" type="text/css" href="{$core_url}static/jscript/jquery/plugin/star/jquery.rating.css" />

<div id="js_rating_holder_{$aRatingCallback.type}">
	<form id="form-rating" method="post" action="#">
		{*hidden area*}
			<input type="hidden" name="rating[type]" value="{$aRatingCallback.type}" />
			<input type="hidden" name="rating[item_id]" value="{$aRatingCallback.item_id}" />
			<input type="hidden" name="rating[listing_id]" value="{$item_id}" />
		{*end hidden area*}
		{*if isset($aRatingCallback.total_rating)}
		<div class="extra_info" style="padding:4px 0px 0px 4px;">
			<span class="js_rating_total">{$aRatingCallback.total_rating}</span>			
		</div>		
		{/if*}
		<div><strong>{phrase var="advancedmarketplace.comment"}: </strong></div>
		<div>
			<textarea style="width:99%" cols="61" name="rating[comment]"></textarea>
			<input type="hidden" name="page" value="{$page}" />
		</div>
		<br />
		<div class="clear"></div>
		<div style="float: left;"><strong>{phrase var="advancedmarketplace.rate"}: </strong></div>
		<div style="height:18px; position: relative; float: left;">
			<div style="position:absolute; width: 200px; margin-left: 10px;">		
				{foreach from=$aRatingCallback.stars key=sKey item=sPhrase}		
					<input type="radio" class="js_rating_star" id="js_rating_star_{$sKey}" name="rating[star]" value="{$sKey}" title="{$sKey}{if $sPhrase != $sKey} ({$sPhrase}){/if}"{if $aRatingCallback.default_rating >= $sKey} checked="checked"{/if} />
				{/foreach}	
				<div class="clear"></div>
			</div>
		</div>
		<div style="text-align: right">
			<input type="submit" id="rating" value="{phrase var="advancedmarketplace.review"}" class="button" />
		</div>
	</form>
</div>
 <script language="javascript" type="text/javascript">
 {literal}
 	$('.js_rating_star').rating();
	
 	$("#form-rating").submit(function(evt){
		evt.preventDefault();

		$(this).ajaxCall("advancedmarketplace.advMarketRating");
		tb_remove();
		return false;
 	 });
 {/literal}
 </script>
 
 
 