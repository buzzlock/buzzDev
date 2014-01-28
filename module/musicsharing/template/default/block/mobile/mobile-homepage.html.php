
<div id="pettabs" class="indentmenu top-menu-homepage">
	<ul>
		<li><a href="" rel="yn_album"  class="selected">{phrase var='musicsharing.top_albums'}</a></li>
		<li><a href="" rel="yn_song" class="">{phrase var='musicsharing.top_songs'}</a></li>		
		<li><a href="" rel="yn_playlist"  class="">{phrase var='musicsharing.top_playlists'}</a></li>
	</ul>
	<div class="space-line"></div>
</div>

{literal}
<script type="text/javascript">        
    $Behavior.MusicSharingMobileHomePage = function() {
        $(document).ready(function(){
			var mypets=new ddtabcontent("pettabs");
			mypets.setpersist(false);
			mypets.setselectedClassTarget("link");
			mypets.init(20000000);
            
            var iPageAlbum = 0;
            jQuery('.add-view-more-top-albums-mobile').click(function(e){
                if (!jQuery('.add-view-more-top-albums-mobile').hasClass('disable'))
                {
                    jQuery('.add-view-more-top-albums-mobile-text').hide();
                    jQuery('.add-view-more-top-albums-mobile-loading').show();

                    iPageAlbum++;
                    $.ajaxCall('musicsharing.viewMoreTopAlbumsMobile', 'iPage=' + iPageAlbum);
                    jQuery('.add-view-more-top-albums-mobile').addClass('disable');
                }
            });
            
            var iPagePlaylist = 0;
            jQuery('.add-view-more-top-playlists-mobile').click(function(e){
                if (!jQuery('.add-view-more-top-playlists-mobile').hasClass('disable'))
                {
                    jQuery('.add-view-more-top-playlists-mobile-text').hide();
                    jQuery('.add-view-more-top-playlists-mobile-loading').show();

                    iPagePlaylist++;
                    $.ajaxCall('musicsharing.viewMoreTopPlaylistsMobile', 'iPage=' + iPagePlaylist);
                    jQuery('.add-view-more-top-playlists-mobile').addClass('disable');
                }
            });
            
            var iPageSong = 0;
            jQuery('.add-view-more-top-songs-mobile').click(function(e){
                if (!jQuery('.add-view-more-top-songs-mobile').hasClass('disable'))
                {
                    jQuery('.add-view-more-top-songs-mobile-text').hide();
                    jQuery('.add-view-more-top-songs-mobile-loading').show();

                    iPageSong++;
                    $.ajaxCall('musicsharing.viewMoreTopSongsMobile', 'iPage=' + iPageSong);
                    jQuery('.add-view-more-top-songs-mobile').addClass('disable');
                }
            });
		}); 
    }
</script>
{/literal}

<div id="yn_album">
	<div class="yn_container">
		{module name="musicsharing.mobile.topalbumsmobile"}
	</div> 
	<a href="javascript:void(0);" class="mb-viewmore add-view-more-top-albums-mobile">
        <span class="add-view-more-top-albums-mobile-text">{phrase var='musicsharing.view_more'}</span>
        <span class="add-view-more-top-albums-mobile-loading" style="display: none;">{img theme='ajax/add.gif' class='v_middle'}</span>
    </a>
</div>
<div id="yn_playlist">
    <div class="yn_container top-playlists-mobile">
        {module name="musicsharing.topplaylists"}
    </div>
    <a href="javascript:void(0);" class="mb-viewmore add-view-more-top-playlists-mobile">
        <span class="add-view-more-top-playlists-mobile-text">{phrase var='musicsharing.view_more'}</span>
        <span class="add-view-more-top-playlists-mobile-loading" style="display: none;">{img theme='ajax/add.gif' class='v_middle'}</span>
    </a>
</div>
<div id="yn_song">
	<div class="yn_container top-songs-mobile">		
		{module name="musicsharing.topsongs-front-end"}
	</div>
	<a href="javascript:void(0);" class="mb-viewmore add-view-more-top-songs-mobile">
        <span class="add-view-more-top-songs-mobile-text">{phrase var='musicsharing.view_more'}</span>
        <span class="add-view-more-top-songs-mobile-loading" style="display: none;">{img theme='ajax/add.gif' class='v_middle'}</span>
    </a>
</div>



