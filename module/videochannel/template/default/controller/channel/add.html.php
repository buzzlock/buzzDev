<?php 

defined('PHPFOX') or exit('NO DICE!'); 

?>
{if !isset($bIsLimited)}
<div id="TB_ajaxContent"></div>
{template file='videochannel.block.channel.url'}
<form id="js_form" name="js_form" method="post" action="{$sSubmitUrl}" onsubmit="return findChannels();">
{if isset($currIndex)}
<input type="hidden" name="val[currIndex]" value="{$currIndex}"/>
{/if}
<!-- Search form -->
{literal}
<script type="text/javascript">
$Behavior.VideoChannelAddChannel = function() {
    $(document).ready(function(){
        $('input#keyword').keydown(function(event) {
            if (event.keyCode == '13') {
                event.preventDefault();
                $('#find_channels').click();
            }
        });
    });
}
</script>
{/literal}
<div class="table">
   <div id="search_channel">
	<div class="table_left">{phrase var='videochannel.keywords'}: </div>
	<div class="table_right">	   
		<input id="keyword" name="val[keyword]" type="text" style="width:60%; vertical-align: middle" size="40" value="{$sKeyword}" onfocus="$('#channel_error').hide()"/>&nbsp; &nbsp;        
		<input id="find_channels" name="find_channels" style="vertical-align: middle" class="button" type="submit" value="{phrase var='videochannel.find_channels'}"/>	   
		<div id="channel_error" class="error_message" style="width: 60%; display: none">{phrase var='videochannel.enter_keywords_to_search_channels'}</div>	   
	</div>
   </div>
   <div id="search_channel_loading" style="display: none">
	{phrase var='videochannel.searching_channels'} &nbsp; &nbsp;
	{img theme='ajax/add.gif' id='channel_loading'}
   </div>
</div><!-- End Search form -->

<!-- Search Result -->
<div id='search_channel_results'>
{if isset($aChannels)}   
	<div id="channel_entry_block">
	   <h1>{phrase var='core.search_results_for'} '{$sKeyword|clean}'</h1>
	   {if !count($aChannels)}
		{phrase var='videochannel.no_channels_found'}
	   {else}
	   {foreach from=$aChannels key=count item=channel}
		{if !phpfox::isMobile()}
			{template file='videochannel.block.channel.entry'}   
		{else}
			{template file='videochannel.block.channel.entry-mobile'}   
		{/if}
	   {/foreach}
	   <div class="pager_outer">
		<ul class="pager">
		   {if !empty($bIsPrev) }
		   <input type="submit" id="prev_channels" name="prev_channels" value="{phrase var='core.previous'}"/>
		   <li class="first" ><a href="javascript:void(0);" onclick="$('#prev_channels').click();" >{phrase var='core.previous'}</a></li>
		   {/if}
		   {if !empty($bIsNext) }
		   <input type="submit" id="next_channels" name="next_channels" value="{phrase var='core.next'}"/>
		   <li class="first" ><a href="javascript:void(0);" onclick="$('#next_channels').click();" >{phrase var='core.next'}</a></li>
		   {/if}
		</ul>
	   </div>
	   {/if}	   
	</div>   
{/if}<!-- End Search Result -->
</div>
</form>
{/if}
