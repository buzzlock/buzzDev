<li>
	<div style="float:left;margin-left:50px">
	<a href="{permalink module='advancedphoto.album' id=$aAlbum.album_id title=$aAlbum.name}" class="view-album album-photo-cover">
		<span style="background:url('{img return_url=true server_id=$aAlbum.server_id path='photo.url_photo' file=$aAlbum.destination suffix='_150'}') no-repeat center top;">
			{$aAlbum.name}
		</span>
		{*img server_id=$aAlbum.server_id path='photo.url_photo' file=$aAlbum.destination suffix='_150' max-width=145 max-height=110*}
	</a>
	</div>
	<div class="clear"> </div>
	<div style="float:left;text-align:center;width:100%">
		<p><a href="{permalink module='advancedphoto.album' id=$aAlbum.album_id title=$aAlbum.name}" class="album-title"><strong>{$aAlbum.name|shorten:50:'...'|split:30} </strong> </br> {phrase var='photo.by_lowercase'} <strong style="color:#444">{$aAlbum|user:'':'':20|split:10} </strong></a></p>
	</div>
	<div class="clear"> </div>
	
</li>