$Behavior.initLargePlayer = function() {
    CONTROLLER_PLAYER.initialize();
}

$Behavior.downloadMusic = function(){
    var aDownloadMusic = $('.younet-download-music-html5');

    aDownloadMusic.each(function(i){
        var oDownloadMusic = $(this);

        oDownloadMusic.bind('click', function(){
            var iSongId = parseInt(oDownloadMusic.parent().find('.song_id').text());

            $.ajaxCall('musicsharing.downloadSong', 'musicid=' + iSongId, 'POST');

            return false;
        });
    });
}

$Behavior.addToPlaylistHTML5 = function(){
    var aAddToPlaylist = $('.younet-add-to-playlist-html5');

    aAddToPlaylist.each(function(i){
        var oAddToPlaylist = $(this);

        oAddToPlaylist.bind('click', function(){
            var iSongId = parseInt(oAddToPlaylist.parent().find('.song_id').text());

            var path = $('#core_path').val();
            var user_id = $('#user_id').val();

            var sAjaxLink = $.ajaxBox(
                'musicsharing.addplaylist',
                'height=200&width=300&' + "idsong=" + iSongId + "&userid=" + user_id + "&pathmodule=musicsharing.addplaylist&pathurl=" + path
                );

            tb_show(oTranslations['musicsharing.add_song_to_playlist'], sAjaxLink);

            var oButtonCloseAjaxWindow = document.getElementById('TB_closeAjaxWindow');

            if (oButtonCloseAjaxWindow)
                oButtonCloseAjaxWindow.innerHTML = '<a id="TB_closeWindowButton" href ="#" onclick="self.parent.tb_remove();"><img alt="" src="' + path + '/theme/frontend/default/style/default/image/misc/close.gif"></a>';

            return false;
        });
    });
}