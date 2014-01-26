
<h3 class="yc_entry_summary yc_submit_entry"> {phrase var='contest.submit_an_entry'}</h3>
<div class="extra_info font_12">
<p class="m_4" id='yncontest_create_new_item'>{phrase var='contest.if_you_do_not_have_any_items_link_create_here' link=$aAddEntryTemplateData.sAddNewItemLink}</p>
<p>{phrase var='contest.or_choose_from_existing_photos_below'}</p>
</div>
<div class="header_bar_search">
	<form method="post" action="{$aYnContestItemSearchTool.search.action}" onbeforesubmit="$Core.Search.checkDefaultValue(this,\'{$aYnContestItemSearchTool.search.default_value}\');">
		<div>
			<input type="hidden" name="search[submit]" value="1" />
		</div>
		<div class="header_bar_search_holder">
			<div class="header_bar_search_default extra_info font_12" style='display:none'>{$aYnContestItemSearchTool.search.default_value}</div>
			<input type="text" class="txt_input{if isset($aYnContestItemSearchTool.search.actual_value)} input_focus{/if}" name="search[{$aYnContestItemSearchTool.search.name}]" value="{if isset($aYnContestItemSearchTool.search.actual_value)}{$aYnContestItemSearchTool.search.actual_value|clean}{else}{$aYnContestItemSearchTool.search.default_value}{/if}" />
			<div class="header_bar_search_input"></div>
		</div>
		<div id="js_search_input_holder">
			<div id="js_search_input_content">
				{if isset($sModuleForInput)}
				{module name='input.add' module=$sModuleForInput bAjaxSearch=true}
				{/if}
			</div>
		</div>
	</form>
</div>
<div class="yc_entry_submit">
{if (count($aAddEntryTemplateData.aItems))}

{foreach from=$aAddEntryTemplateData.aItems name=entryItem item=aItem}
{template file='contest.block.entry.entry-item'}
{/foreach}

<div class="clear"></div>

{pager}
{else}
{phrase var='contest.you_have_no_item'}

{/if}
<form method='POST' action='#' id='yncontest_add_entry'>

	<input type='hidden' id='yncontest_item_id' name="val[item_id]" value='{$aAddEntryTemplateData.iChosenItemId}'/>
	<input type='hidden' id='yncontest_item_type' name="val[item_type]" value='{$aAddEntryTemplateData.iItemType}'/>
	<input type='hidden' id='yncontest_contest_id' name="val[item_contest_id]" value='{$aAddEntryTemplateData.iContestId}'/>

	<div id="core_js_messages">
		<div class="error_message" style='display:none' id='yncontest_must_select_an_item'> {phrase var='contest.please_select_an_item'}</div>
		<div class="error_message" style='display:none' id='yncontest_title_summary_required'> {phrase var='contest.title_and_description_are_required'}</div>
		<div class="error_message" style='display:none' id='yncontest_title_max_length'> {phrase var='contest.maxium_number_of_characters_for_title_is'} 255</div>
	</div> 

	<div class="table">
		<div class="table_left">
			<label for="title">{required}{phrase var='contest.title'}: </label>
		</div>
		<div class="extra_info">
			{phrase var='contest.you_can_enter_maximum_number_characters', number=255}
		</div>
		<div class="table_right">
			<input type="text" name="val[title]"  id="yncontest_entry_title" size="60" 
			{if $aAddEntryTemplateData.sChosenItemTitle} 
				value = "{$aAddEntryTemplateData.sChosenItemTitle}"
			{/if}
			/>
		</div>
	</div>

	<div class="table">
		<div class="table_left">
			<label for="summary">{required}{phrase var='contest.description'}:</label>
		</div>

		<div class="table_right"> 
			<textarea cols="56" rows="10" name="val[summary]" id="yncontest_entry_summary" style="height:70px;"></textarea>
		</div>
	</div>

	


	<div class="table_clear">
		<ul class="table_clear_button">					
			<li><input type="button" name="val[preview]" value="{phrase var='contest.preview'}" class="button button_off" onclick="yncontest.addEntry.previewEntry('{phrase var='contest.entry_preview' phpfox_squote=true}'); return false;"/></li>

			<li><input type="button" name="val[submit]" value="{phrase var='contest.submit'}" class="button button_off" id='yncontest_submit_add_entry_button' onclick="yncontest.addEntry.submitAddEntry(); return false;"/></li>
		</ul>
		<div class="clear"></div>
	</div>		
</form>
</div>


<script type="text/javascript">
	$Behavior.yncontestInitialzeItemEntryOnclick = function() {l}
		yncontest.addEntry.initializeClickOnEntryItem();
		yncontest.addEntry.addAjaxForCreateNewItem({$aContest.contest_id}, {$aContest.type});		
	{r}
</script>

{if $aAddEntryTemplateData.iChosenItemId != 0}
	<script type="text/javascript">
		$Behavior.yncontestSetChosenItem = function() {l}
			yncontest.addEntry.setChosenItem({$aAddEntryTemplateData.iChosenItemId});		
		{r}
	</script>


{/if}

