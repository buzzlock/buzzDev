<div class="cter_left_popup">
	<div class="cter_cter_popup">
		<div id="pettabs" class="indentmenu">
			<ul>
				<li style="float: left;"><a href="" rel="description" class="selected">{phrase var='musicsharing.description'}</a></li>
				<li style="float: left;"><a href="" rel="Lyric"  class="">{phrase var="musicsharing.lyric"}</a></li>
				<li style="float: left;"><a href="" rel="Embed"  class="">{phrase var="musicsharing.embed"}</a></li>
			</ul>
			<div class="space-line"></div>
		</div>

		<div class="khung_f">
			<div id="description" class="tabcontent">
				<div id="albumDescription">
					{if isset($album_info.description)}
						{$album_info.description|shorten:500:'...':true}
					{else}
						{phrase var='musicsharing.no_description_found'}.
					{/if}
				 </div>
			</div>
			<div id="Lyric" class="tabcontent block_content">
				<div style="overflow: auto;height:300px;word-wrap: break-word;" id="lyric_music_song">
					{if $music_info.lyric != ""}
						{$music_info.lyric}
					{else}
						{phrase var='musicsharing.no_lyric'}
					{/if}
				</div>
			</div>
			<div id="Embed" class="tabcontent block_content">
				<div id="embed_music_song">
					{template file='musicsharing.block.embed}
				</div>
			</div>
		</div>
	</div>
</div>