<div class="url">
	<?php
		// var_dump($this->_aVars["music_info"]);
	?>
	{if !isset($music_info.module_id)}
		{if $music_info.module_id = false}{/if}
	{/if}
	<label>{phrase var="musicsharing.link_url"}: {if $music_info.module_id}abc{/if}</label>
    <input type="text" class="link" readonly="readonly" onclick="url_select_text(this)" value="{if $music_info.module_id}{url link=$music_info.module_id.'.'.$music_info.item_id.'.musicsharing.listen.music_'.$music_info.song_id}{else}{url link='musicsharing.listen.music_'.$music_info.song_id}{/if}"/>
    <div class="clear"></div>
</div>

<div class="url">
    <label>{phrase var="musicsharing.html_code"}:</label>
    <input  id="html_code_inactive" readonly="readonly" onclick="url_select_text(this)" type="text" class="link" value='&lt;object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="180" height="154" id="player" align="middle"&gt;&lt;param name="movie" value="{$core_path}module/musicsharing/static/swf/mini_player.swf" /&gt;&lt;param name="quality" value="high" /&gt;&lt;param name="bgcolor" value="#cccccc" /&gt;&lt;param name="play" value="true" /&gt;&lt;param name="loop" value="false" /&gt;&lt;param name="wmode" value="transparent" /&gt;&lt;param name="scale" value="noscale" /&gt;&lt;param name="menu" value="true" /&gt;&lt;param name="devicefont" value="false" /&gt;&lt;param name="salign" value="tl" /&gt;&lt;param name="allowScriptAccess" value="always" /&gt;&lt;param name="FlashVars" value="xmlPath=<?php echo urlencode($this->_aVars['urldata']);?>&autoplay=true&defaultSongId={$music_info.song_id}" /&gt;&lt;!--[if !IE]&gt;--&gt;&lt;object type="application/x-shockwave-flash" data="{$core_path}module/musicsharing/static/swf/mini_player.swf" width="180" height="154"&gt;&lt;param name="movie" value="{$core_path}module/musicsharing/static/swf/mini_player.swf" /&gt;&lt;param name="quality" value="high" /&gt;&lt;param name="bgcolor" value="#cccccc" /&gt;&lt;param name="play" value="true" /&gt;&lt;param name="loop" value="false" /&gt;&lt;param name="wmode" value="transparent" /&gt;&lt;param name="scale" value="noscale" /&gt;&lt;param name="menu" value="true" /&gt;&lt;param name="devicefont" value="false" /&gt;&lt;param name="salign" value="tl" /&gt;&lt;param name="allowScriptAccess" value="always" /&gt;&lt;param name="FlashVars" value="xmlPath=<?php echo urlencode($this->_aVars['urldata']);?>&autoplay=true&defaultSongId={$music_info.song_id}" /&gt;&lt;!--&lt;![endif]--&gt;&lt;a href="http://www.adobe.com/go/getflash"&gt;&lt;img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /&gt;&lt;/a&gt;&lt;!--[if !IE]&gt;--&gt;&lt;/object&gt;&lt;!--&lt;![endif]--&gt;&lt;/object&gt;'/>
    <div class="clear"></div>
</div>

<div class="url">
    <label>{phrase var="musicsharing.forum_code"}:</label>
    <input type="text" class="link" readonly="readonly" onclick="url_select_text(this)" value="[URL={if $music_info.module_id}{url link=$music_info.module_id.'.'.$music_info.item_id.'.musicsharing.listen.music_'.$music_info.song_id}{else}{url link='musicsharing.listen.music_'.$music_info.song_id}{/if}]{$music_info.title}[/URL]"/>
    <div class="clear"></div>
</div>
<script language="javascript">
	$Behavior.loadEmbed = function(){l}
		return true;
		html = trim($('#songContainer').html());
		html = html.replace("/player.swf", "/mini_player.swf");
		html = html.replace("/player.swf", "/mini_player.swf");
		html = html.replace("/player.swf", "/mini_player.swf");
		html = html.replace(/\<param name=\"FlashVars\".*?\>/g, "<param name=\"FlashVars\" value=\"xmlPath=<?php echo urlencode($this->_aVars['urldata']);?>&autoplay=true&defaultSongId={$music_info.song_id}\" />");
		$('#html_code_inactive').val(html);
	{r}
</script>