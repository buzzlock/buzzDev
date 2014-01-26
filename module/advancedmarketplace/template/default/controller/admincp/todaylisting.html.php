<?php


defined('PHPFOX') or exit('NO DICE!');

?>
{literal}
<script language="JavaScript" type="text/javascript">
	$Behavior.initTodaylisting = function() {
		$("#js_from_date_listing").datepicker({
			// dateFormat: "@", // Unix timestamp
			dateFormat: 'mm/dd/yy',
			onSelect: function(dateText, inst) {
				var $dateTo = $("#js_to_date_listing").datepicker("getDate");
				var $dateFrom = $("#js_from_date_listing").datepicker("getDate");
				if($dateTo)
				{
					$dateTo.setHours(0);
					$dateTo.setMilliseconds(0);
					$dateTo.setMinutes(0);
					$dateTo.setSeconds(0);
				}
				
				if($dateFrom)
				{
					$dateFrom.setHours(0);
					$dateFrom.setMilliseconds(0);
					$dateFrom.setMinutes(0);
					$dateFrom.setSeconds(0);
				}
				
				if($dateTo && $dateFrom && $dateTo < $dateFrom) {
					tmp = $("#js_to_date_listing").val();
					$("#js_to_date_listing").val($("#js_from_date_listing").val());
					$("#js_from_date_listing").val(tmp);
				}
				return false;
			}
		});
        
		$("#js_to_date_listing").datepicker({
			// dateFormat: "@", // Unix timestamp
			dateFormat: 'mm/dd/yy',
			onSelect: function(dateText, inst) {
				var $dateTo = $("#js_to_date_listing").datepicker("getDate");
				var $dateFrom = $("#js_from_date_listing").datepicker("getDate");
				
				//$dateTo = $dateTo?$dateTo:(new Date());
				//$dateFrom = $dateFrom?$dateFrom:(new Date());
				if($dateTo)
				{
					$dateTo.setHours(0);
					$dateTo.setMilliseconds(0);
					$dateTo.setMinutes(0);
					$dateTo.setSeconds(0);
				}
				
				if($dateFrom)
				{
					$dateFrom.setHours(0);
					$dateFrom.setMilliseconds(0);
					$dateFrom.setMinutes(0);
					$dateFrom.setSeconds(0);
				}
				
				if($dateTo && $dateFrom && $dateTo < $dateFrom) {
					tmp = $("#js_to_date_listing").val();
					$("#js_to_date_listing").val($("#js_from_date_listing").val());
					$("#js_from_date_listing").val(tmp);
				}
				return false;
			}
		});
			
		$("#js_from_date_listing_anchor").click(function() {
			$("#js_from_date_listing").focus();
			return false;
		});
		
		$("#js_to_date_listing_anchor").click(function() {
			$("#js_to_date_listing").focus();
			return false;
		});
        
        $(".jsaction").find("a").each(function(){
			var $this = $(this);
			
			$this.click(function(evt) {
				evt.preventDefault();
				
				var $_this = $(this);
				var path = $_this.attr("href").split("/");
				var id = path[path.length - 3]
				
				$("select").val("");
				$("#search-category").val(id);
				setTimeout(function(){
					$("#frm_submitbtn").click();
				}, 1);
				
				return false;
			});
		});
        
        $("#js_mp_category_item_{/literal}{$iCategoryId}{literal}").attr({
			"selected": "selected"
		});
	}
</script>
{/literal}
<form style="margin-bottom:10px;" method="post" action="{url link='admincp.advancedmarketplace.todaylisting'}">
	<div class="table_header">{phrase var='advancedmarketplace.listing_filter'}
	</div>
	<div class="table">
		<div class="table_left">
			{phrase var='advancedmarketplace.listing_name'}:
		</div>
		<div class="table_right">
			{$aFilters.listing}
		</div>
		<div class="clear"></div>
	</div>
	<div class="table">
		<div class="table_left">
			{phrase var='advancedmarketplace.owner_name'}:
		</div>
		<div class="table_right">
			{$aFilters.owner}
		</div>
		<div class="clear"></div>
	</div>
	<div class="table">
		<div class="table_left">
			{phrase var='advancedmarketplace.category'}:
		</div>
		<div class="table_right">
			<select name="search[category]" style="width:300px;" id="search-category">
				<option value="">{phrase var='advancedmarketplace.select'}:</option>
				{$sCategories}
			</select>
		</div>
	</div>
	<div class="table">
		<div class="table_left">
			{phrase var='advancedmarketplace.from_date'}:
		</div>
		<div class="table_right">
			<input name="search[fromdate]" id="js_from_date_listing" type="text" value="{if $sFromDate}{$sFromDate}{/if}" />
			<a href="#" id="js_from_date_listing_anchor">
				<img src="<?php echo Phpfox::getLib('template')->getStyle('image', 'jquery/calendar.gif'); ?>" />
			</a>
		</div>
		<div class="clear"></div>
	</div>
	<div class="table">
		<div class="table_left">
			{phrase var='advancedmarketplace.to_date'}:
		</div>
		<div class="table_right">
			<input name="search[todate]" id="js_to_date_listing" type="text" value="{if $sToDate}{$sToDate}{/if}" />
			<a href="#" id="js_to_date_listing_anchor">
				<img src="<?php echo Phpfox::getLib('template')->getStyle('image', 'jquery/calendar.gif'); ?>" />
			</a>
		</div>
		<div class="clear"></div>
	</div>
	<div class="table_clear">
		<input type="submit" id="frm_submitbtn" name="search[submit]" value="{phrase var='core.submit'}" class="button" />
		<input type="submit" name="search[reset]" value="{phrase var='core.reset'}" class="button" />
		<div class="clear"></div>
	</div>
</form>
<div class="table_header">
	{phrase var='advancedmarketplace.today_listings'}
</div>
{pager}
{if count($aListings) > 0}
	<script lang="javascript" type="text/javascript">
		/* {literal} */
		$Behavior.advmarket_todaylistingaction = function(){
			$(".yn_popup___calendar_listing").click(function(evt){
				evt.preventDefault();
				$("#_submit-todaylisting-form").empty();
				tb_show("{/literal}{phrase var="advancedmarketplace.today_listing" phpfox_squote=true}{literal}", $.ajaxBox('advancedmarketplace.todaylistingPopup', 'height=230&width=262&id=' + $(this).parent().find(".yn_lid").val()));
				
				return false;
			});
			$("#checkmeall").removeAttr("checked");
			$(".X_checkbox").removeAttr("checked");
			$("#checkmeall").click(function(evt) {
				if($(this).is(":checked")){
					$("#js_control").find("input[type=checkbox]").attr({
						"checked": "checked"
					});
					$("#deletebtn").removeClass("disabled");
				} else {
					$("#js_control").find("input[type=checkbox]").removeAttr("checked");
					$("#deletebtn").addClass("disabled");
				}
			});
			
			$(".X_checkbox").click(function() {
				if($(".X_checkbox:checked").size() <=0 ) {
					$("#checkmeall").removeAttr("checked");
					$("#deletebtn").addClass("disabled");
				} else {
					$("#deletebtn").removeClass("disabled");
				}
			});
			$("#deletebtn").click(function(evt) {
				evt.preventDefault();
				
				var $this = $(this);
				if($this.hasClass("disabled")) return false;
				if(!confirm('{/literal}{phrase var='admincp.are_you_sure'}{literal}')) return false;
				
				var $form = $("<form>");
				$form.append($(".X_checkbox").clone());
				
				$form.ajaxCall("advancedmarketplace.deleteTodayListings");
				
				return false;
			});
			
		}
		// $Core.init();
		/* {/literal} */
	</script>
	<table class="js_drag_drop_" id="js_control" cellpadding="0" cellspacing="0">
		<tr>
			<th>
				<input type="checkbox" id="checkmeall" value=""/>
			</th>
			<th></th>
			<th>
				{phrase var="advancedmarketplace.listing_name"}
			</th>
			<th>
				{phrase var='user.user'}
			</th>
			<th>
				{phrase var='advancedmarketplace.category'}
			</th>
			<th>
				
			</th>
		</tr>
		{foreach from=$aListings key=iKey item=aBlock}
		<tr class="checkRow{if is_int($iKey/2)} tr{else}{/if}">
			<td class="t_center">
				<input name="deleteitem[]" class="X_checkbox" type="checkbox" value="{$aBlock.listing_id}"/>
			</td>
			<td class="t_center">
				<a href="#" class="js_drop_down_link" title="{phrase var='ad.manage'}">{img theme='misc/bullet_arrow_down.png' alt=''}</a>
				<div class="link_menu">
					<ul>
						<li><a href="{url link='admincp.advancedmarketplace.todaylisting' delete=$aBlock.listing_id}" onclick="return confirm('{phrase var='admincp.are_you_sure' phpfox_squote=true}');">{phrase var='ad.delete'}</a></li>
					</ul>
				</div>
			</td>
			<td>
				<a href="{url link='advancedmarketplace.detail.'.{$aBlock.listing_id}{$aBlock.title}">{$aBlock.title}</a>
			</td>
			<td>{$aBlock.full_name}</td>
			<td class="jsaction">{$aBlock.categories|category_display}</td>
			<td>
				<a href="#" class="yn_popup___calendar_listing">
					<input type="hidden" value="{$aBlock.listing_id}" name="yn_lid" class="yn_lid" />
					<img src="<?php echo Phpfox::getLib('template')->getStyle('image', 'jquery/calendar.gif'); ?>" />
				</a>
			</td>
			
		</tr>
		{/foreach}
	</table>
{else}
	{if $bIsSearch}
		<div class="extra_info">{phrase var='advancedmarketplace.no_listings_found'}</if>
	{else}
		<div class="extra_info">{phrase var='advancedmarketplace.no_listings_have_been_created'}</if>
	{/if}
{/if}
{pager}
<div class="table_clear">
	<input id="deletebtn" type="submit" class="button disabled" value="{phrase var='advancedmarketplace.delete_selected'}">
</div>