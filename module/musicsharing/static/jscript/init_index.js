$Behavior.initMusicSharingAlbum = function (){
    $('#lofslidecontent45').lofJSidernews( {
        interval:4000,
        direction:'opacity',
        duration:1000,
        isPreloaded : false, // for IE9
        easing:'easeInOutSine'
    } );		
    $(".lof-main-item-desc").click(function(){
        var $this = $(this);
        window.location.href = $this.find("a").attr("href");
        return false;
    }).css({
        "cursor": "pointer"
    });
};

