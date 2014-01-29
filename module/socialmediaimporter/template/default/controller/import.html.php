{if $aAgent}	
<div class="page_section_menu ynsimenu">	
	<ul>
	{if $bIsHaveGetAlbums}		
		<li class="{if $sType != "photo"}active{/if}"><a id="import_album" href="{$sLinkGetAlbums}">{phrase var='socialmediaimporter.import_albums'}</a></li>
	{/if}		
	{if $bIsHaveGetPhotos}		
		<li class="{if $sType == "photo"}active{/if}"><a id="import_photo" href="{$sLinkGetPhotos}">{phrase var='socialmediaimporter.import_photos'}</a></li>
	{/if}
	</ul>
	
	<div class="cslog socialmediaimporter_connect_link" id="socialmediaimporter_connect_link_{$aAgent.name}">
		 <img src="{$aAgent.img_url}" alt="{$aAgent.full_name}" align="left" height="32"/>
		 {phrase var='socialmediaimporter.connected_as' full_name=''} {$aAgent.full_name|clean|shorten:18...}<br/>
		 <a href="{$sLinkDisconnect}" onclick="return confirm('{phrase var='socialmediaimporter.are_you_sure_you_want_to_disconnect_this_account'}');">{phrase var='socialmediaimporter.click_here'}</a> {phrase var='socialmediaimporter.to'} {phrase var='socialmediaimporter.disconnect'}.
	</div>
		
	<div class="clear"></div>
</div>

<div class="simporter-wrapper" id="simporter-wrapper">
	<div>			
		<div class="uiHeader uiHeaderSection fbPhotosGridHeader">
			<div class="clearfix uiHeaderTop">
				<div>
					<h3 class="uiHeaderTitle" tabindex="0" id="title_album">{if $sType=='album'}{phrase var='socialmediaimporter.your_albums'}{else}{phrase var='socialmediaimporter.your_photos'}{/if}</h3>
				</div>
			</div>
		</div>		
		<div class="album-section">
			<div class="album-wrapper" id="list_albums"></div>
			<div class="album-wrapper" id="list_photos" style="display:none;"></div>
			<div class="clear"></div>
			<div id="feed_view_more">				
				<div id="feed_view_more_loader">{img theme='ajax/add.gif'}</div>
				<a id="global_view_more_album" style="display:none;" onclick="$(this).hide(); $('#feed_view_more_loader').show(); loadMoreAlbums(0); return false;" class="global_view_more no_ajax_link">{phrase var='socialmediaimporter.view_more'}</a>
				<a id="global_view_more_photo" style="display:none;" onclick="$(this).hide(); $('#feed_view_more_loader').show(); loadMorePhotos(0); return false;" class="global_view_more no_ajax_link">{phrase var='socialmediaimporter.view_more'}</a>
			</div>
			<div class="moderation_holder" id="action_buttons" style="display:none;">
				<input type="button" id="import-photos" class="button imporSelected" value="{phrase var='socialmediaimporter.import_selected'}" />
				<input style="display:none;" type="button" class="button backAL" value="{phrase var='socialmediaimporter.back'}" onclick="backToAlbums();"  />
				<div style="float: right;" class="hopbux">
					<input type="button" id="import-photos" class="button selectAllBtn" value="{phrase var='socialmediaimporter.select_all'}" />
					<input type="button" id="import-photos" class="button deSelectAllBtn" value="{phrase var='socialmediaimporter.deselect_all'}" />
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="button" class="button refreshBtn" value="{phrase var='socialmediaimporter.refresh'}" />
				</div>
			</div>	
			<div class="clear"></div>
		</div>
	</div>
</div>

<script language="javascript" type="text/javascript">
var sService = "{$sService}";
var sType = "{$sType}";
var yn_load_albums_init = false;
var yn_load_photos_init = false;
var yn_albums_page = 1;
var yn_photos_page = 1;
var yn_album_id = 0;
var hasViewMorePhoto = 0;
</script>
{/if}