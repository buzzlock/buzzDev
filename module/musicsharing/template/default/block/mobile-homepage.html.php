
<div id="pettabs" class="indentmenu top-menu-homepage">
	<ul>
		<li><a href="" rel="yn_song" class="selected">{phrase var='musicsharing.top_songs'}</a></li>
		<li><a href="" rel="yn_album"  class="">{phrase var='musicsharing.top_albums'}</a></li>
		<li><a href="" rel="yn_playlist"  class="">{phrase var='musicsharing.top_playlists'}</a></li>
	</ul>
	<div class="space-line"></div>
</div>

<div id="yn_album">
	<div class="yn_container">
		{module name="musicsharing.mobile.topalbumsmobile"}
	</div> 
</div>
{literal}
<script type="text/javascript">        
    $Behavior.MusicSharingMobileHomeBlock = function() {
        $(document).ready(function(){
			var mypets=new ddtabcontent("pettabs");
			mypets.setpersist(false);
			mypets.setselectedClassTarget("link");
			mypets.init(20000000);
		}); 
    }
</script>
{/literal}
<div id="yn_playlist">
	<div class="yn_container">
		 {module name="musicsharing.topplaylists"}
	 </div>
</div>
<div id="yn_song">
	<div class="yn_container">
		{module name="musicsharing.topsongs-front-end"}
	</div>
</div>



