<?php
/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');
?>

<script type="text/javascript">
{literal}
$Behavior.ynfDetailSlider = function(){
	$(function(){
		var startSlide = 1;
		$('#ynfr_gallery_slides').slides({
			preload: true,
			effect: 'slide',
			crossfade: true,
			slideSpeed: 350,
			fadeSpeed: 500,
			generatePagination: false,
		});
	});
}

$Behavior.ynfundraisingOverrideLoadInit = function() {
	$Core.loadInit = function() {};
}
{/literal}
</script>


<div id="ynfr_gallery_slides" >
	<div class="slides_container ynfr_gallery_slides_container">
			{if $aGalleryVideo}
			<div class="panel" >
				<div class="wrapper">
					{$aGalleryVideo.embed_code}
				</div>
			</div>	
			{/if}

			{foreach from=$aGalleryImages item=aImage} 
			<div class="panel" >
				{*<div class="wrapper" style="background:url({img return_url=true server_id=$aImage.server_id path='core.url_pic' file=$aImage.image_path suffix='_500'}) no-repeat top center;display:block;height:299px;width:500px;"> </div>	*}
					<div class="wrapper js_fundraising_click_image" href="{img return_url=true server_id=$aImage.server_id path='core.url_pic' file=$aImage.image_path suffix=''}" style="cursor:pointer">
						{img server_id=$aImage.server_id path='core.url_pic' file=$aImage.image_path suffix='_500' max_width='500' max_height='299' }
					</div>
				
			</div>
			{/foreach}

	</div>

	<ul class="pagination">
	{if $aGalleryVideo}
		<li><a href="#">	
		{img server_id=$aGalleryVideo.server_id path='core.url_pic' file=$aGalleryVideo.image_path suffix='_120' max_width='60' max_height='32' class='nav_thumb'}
		</a></li>
		{/if}
		
		{foreach from=$aGalleryImages key=iIndex item=aImage} 
			<li><a href="#">
				{img server_id=$aImage.server_id path='core.url_pic' file=$aImage.image_path suffix='_50' max_width='60' max_height='32' class='nav_thumb'}
			</a></li>
		{/foreach}
	</ul>

	<a href="#" class="prev">Previous</a>
	<a href="#" class="next">Next</a>

</div>

<div class="clear"></div>

