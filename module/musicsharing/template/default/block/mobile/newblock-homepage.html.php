
<div id="pettabs1" class="indentmenu top-menu-homepage">
	<ul>
		<li><a href="" rel="yn_newalbum"  class="selected">{phrase var='musicsharing.new_albums'}</a></li>
		<li><a href="" rel="yn_newsong" class="">{phrase var='musicsharing.new_songs'}</a></li>		
		<li><a href="" rel="yn_newplaylist"  class="">{phrase var='musicsharing.new_playlist'}</a></li>
	</ul>
	<div class="space-line"></div>
</div>

{literal}
    <script type="text/javascript">    
        $Behavior.MusicSharingNewBlockHomePage = function() {
            $(document).ready(function(){
                var mypets=new ddtabcontent("pettabs1");
                mypets.setpersist(false);
                mypets.setselectedClassTarget("link");
                mypets.init(20000000);

                var iPageAlbum = 0;
                jQuery('.add-view-more-new-albums-mobile').click(function(e){
                    if (!jQuery('.add-view-more-new-albums-mobile').hasClass('disable'))
                    {
                        jQuery('.add-view-more-new-albums-mobile-text').hide();
                        jQuery('.add-view-more-new-albums-mobile-loading').show();

                        iPageAlbum++;
                        $.ajaxCall('musicsharing.viewMoreNewAlbumsMobile', 'iPage=' + iPageAlbum);
                        jQuery('.add-view-more-new-albums-mobile').addClass('disable');
                    }
                });

                var iPagePlaylist = 0;
                jQuery('.add-view-more-new-playlists-mobile').click(function(e){
                    if (!jQuery('.add-view-more-new-playlists-mobile').hasClass('disable'))
                    {
                        jQuery('.add-view-more-new-playlists-mobile-text').hide();
                        jQuery('.add-view-more-new-playlists-mobile-loading').show();

                        iPagePlaylist++;
                        $.ajaxCall('musicsharing.viewMoreNewPlaylistsMobile', 'iPage=' + iPagePlaylist);
                        jQuery('.add-view-more-new-playlists-mobile').addClass('disable');
                    }
                });

                var iPageSong = 0;
                jQuery('.add-view-more-new-songs-mobile').click(function(e){
                    if (!jQuery('.add-view-more-new-songs-mobile').hasClass('disable'))
                    {
                        jQuery('.add-view-more-new-songs-mobile-text').hide();
                        jQuery('.add-view-more-new-songs-mobile-loading').show();

                        iPageSong++;
                        $.ajaxCall('musicsharing.viewMoreNewSongsMobile', 'iPage=' + iPageSong);
                        jQuery('.add-view-more-new-songs-mobile').addClass('disable');
                    }
                });
            }); 
        }
    </script>
{/literal}
<div id="yn_newsong">
    {module name="musicsharing.mobile.newsongs-mobile"}
    <a href="javascript:void(0);" class="mb-viewmore add-view-more-new-songs-mobile">
        <span class="add-view-more-new-songs-mobile-text">{phrase var='musicsharing.view_more'}</span>
        <span class="add-view-more-new-songs-mobile-loading" style="display: none;">{img theme='ajax/add.gif' class='v_middle'}</span>
    </a>
</div>
<div id="yn_newalbum">
    {module name="musicsharing.newalbums"}
    <a href="javascript:void(0);" class="mb-viewmore add-view-more-new-albums-mobile">
        <span class="add-view-more-new-albums-mobile-text">{phrase var='musicsharing.view_more'}</span>
        <span class="add-view-more-new-albums-mobile-loading" style="display: none;">{img theme='ajax/add.gif' class='v_middle'}</span>
    </a>
</div>
<div id="yn_newplaylist">
    {module name="musicsharing.mobile.newplaylists"}
    <a href="javascript:void(0);" class="mb-viewmore add-view-more-new-playlists-mobile">
        <span class="add-view-more-new-playlists-mobile-text">{phrase var='musicsharing.view_more'}</span>
        <span class="add-view-more-new-playlists-mobile-loading" style="display: none;">{img theme='ajax/add.gif' class='v_middle'}</span>
    </a>
</div>