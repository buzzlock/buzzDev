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
	}
	$Behavior.initAlbumBadge = function() {
		var trace = function() {
			args = arguments;
			if(console) {
				for(index in args) {
					console.log(args[index]);
				}
			}
		}
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
	}
</script>
{/literal}