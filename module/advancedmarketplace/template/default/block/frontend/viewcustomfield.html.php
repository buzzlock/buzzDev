{foreach from=$aCustomFields key=sKey item=aCustomField}
<div class="listing_detail">
	<div class="short_description">
		<div class="short_description_title">
			<span class="description_title">{phrase var=$sKey}</span>
		</div>
		<div class="short_description_content">
			<table>
				{foreach from=$aCustomField key=iKey item=aField}
					<!--VID: {$aField.view_id}-->
					{if $aField.view_id}
						{module
							name="advancedmarketplace.frontend.view.".($aField.view_id)
							aField=$aField
							cfInfors = $cfInfors
						}
					{/if}
				{/foreach}
			</table>
		</div>
	</div>
</div>
{/foreach}