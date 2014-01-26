<div class="slider-wrapper theme-default">
	<div id="slider" class="nivoSlider" eff="{$aForms.yn_slide_type}">	
		{if $count = 0}{/if}
		{foreach from=$aPhotos item=aPhoto name=photos}
			{if ($count = $count + 1)}{/if}
			<img vx="vx" src="{*
				*}{if $count == 1}{*
					*}{img return_url=true server_id=$aPhoto.server_id path='photo.url_photo' file=$aPhoto.destination suffix='_slide1024'}{*
				*}{else}{*
					*}buff{*
				*}{/if}{*
				*}" ref="{*
				*}{img return_url=true server_id=$aPhoto.server_id path='photo.url_photo' file=$aPhoto.destination suffix='_slide1024'}{*
			*}" data-thumb="{*
				*}{img return_url=true server_id=$aPhoto.server_id path='photo.url_photo' file=$aPhoto.destination suffix='_75'}{*
			*}" alt="" />
		{/foreach}
	</div>
	<div class="floatingbt">
		<!--a href="#" title="{phrase var="advancedphoto.view_album_full_sreen"}" onclick="open('{permalink module='advancedphoto.album-badge' id=$aAlbum.album_id title=$aAlbum.name}', '_blank', 'fullscreen=1');return false;"-->
		<a href="#" title="{phrase var="advancedphoto.view_album_full_sreen"}" onclick="tb_show('', $.ajaxBox('advancedphoto.ynalbum.popupSlider', 'aid={$aAlbum.album_id}&width=' + $('body').width() + '&height=' + $('body').height()));return false;">
			<img src="{$corepath}module/advancedphoto/static/image/fullscreen.png" />
		</a>
	</div>
</div>
<!--div class="js_moderation_on">
	<a href="#" onclick="tb_show('{phrase var="advancedphoto.badge_code"}', $.ajaxBox('advancedphoto.ynalbum.badgeCode', 'aid={$aAlbum.album_id}&width=450&height=300'));return false;">{phrase var="advancedphoto.get_badge_code"}</a>
</div-->
	<div class="extra_info" id="ynadvphoto_album_in_this_album_holder"  {if !$sJsAlbumTagContent} style="display:none" {/if}>
		<b>{phrase var='advancedphoto.in_this_album'}: </b> <span id="ynadvphoto_album_in_this_album"> {$sJsAlbumTagContent} </span>
	</div>	
<div {if $aForms.view_id != 0}style="display:none;" class="js_moderation_on"{/if}>
	{module name='advancedphoto.yncomment'}
</div>
{literal}
<script language="javascript" type="text/javascript">
	var trace = function() {
		args = arguments;
		if(console) {
			for(index in args) {
				console.log(args[index]);
			}
		}
	};
	$Behavior.ynadvphotoInitializeSlideshowViewingAnAlbum = function() {
		var trace = function() {
			args = arguments;
			if(console) {
				for(index in args) {
					console.log(args[index]);
				}
			}
		};
		$('#slider').nivoSlider({
			"beforeChange": function() {
			},
			effect : $('#slider').attr("eff"),
			slices : 10,
			boxCols : 12,
			boxRows : 8,
			animSpeed : 1000,
			pauseTime : 3000,
			directionNav: true,
			controlNav: true,
			controlNavThumbs: true,
			pauseOnHover: true,
			preloadImageURL: "buff",
			manualAdvance: false
		});
		
		$("div.effects").children().click(function(evt){
			evt.preventDefault();
			trace ($(this).attr("ref"));
			
			$('#slider').data('nivoslider').setEffect($(this).attr("ref"));
			
			return false;
		});
	};
</script>
{/literal}