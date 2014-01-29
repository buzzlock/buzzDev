

{foreach from=$aPrivacySuggestionNotifications key=ssuggestion item=suggestiontag}


<div class="table" {if (Phpfox::getUserParam('suggestion.enable_content_suggestion_popup')==0 && $ssuggestion=="suggestion.enable_content_suggestion_popup") || (Phpfox::getUserParam('suggestion.enable_friend_recommend')==0 && $ssuggestion=="suggestion.enable_system_recommendation") || (Phpfox::getUserParam('suggestion.enable_friend_suggestion_popup')==0 && $ssuggestion=="suggestion.enable_system_suggestion")}style="display:none"{/if}>
			<div class="table_left">
				{$suggestiontag.phrase}
			</div>
			<div class="table_right">			
				<div class="item_is_active_holder">	
					<span class="js_item_active item_is_active"><input type="radio" value="0" name="val[{$ssuggestion}]" {if $suggestiontag.default} checked="checked"{/if} class="checkbox" /> {phrase var='user.yes'}</span>
					<span class="js_item_active item_is_not_active"><input type="radio" value="1" name="val[{$ssuggestion}]" {if !$suggestiontag.default} checked="checked"{/if} class="checkbox" /> {phrase var='user.no'}</span>
				</div>
			</div>
		</div>
	{/foreach}
<div class="table_clear">
		<input type="button" class="button" onclick="savechangeclick()" value="{phrase var='suggestion.save_changes'}">		
</div>

{literal}
<script type="text/javascript">

function savechangeclick()
{
	var value1=$('input:radio[name="val[suggestion.enable_content_suggestion_popup]"]:checked').val();
	var value2=$('input:radio[name="val[suggestion.enable_system_recommendation]"]:checked').val();
	var value3=$('input:radio[name="val[suggestion.enable_system_suggestion]"]:checked').val();
	$.ajaxCall("suggestion.savechangeclick","value1="+value1+"&value2="+value2+"&value3="+value3);
}
	
</script>
{/literal}

