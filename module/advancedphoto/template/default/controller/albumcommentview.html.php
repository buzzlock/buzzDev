<div class="t_right" id="ynadvphoto_paging_temp_holder" style="display:none">
	{pager}
</div>
<ul id="js_album_content" class="view-gird {if phpfox::isMobile()}view-comment-mobile{/if}">
	{foreach from=$aPhotos item=aPhoto name=photos}
		{module name='advancedphoto.albumcommentviewentry' aPhoto=$aPhoto} 
	{/foreach}
</ul>

{module name='advancedphoto.albumcommentviewafterloadentries'}  
