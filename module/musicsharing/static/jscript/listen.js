$Behavior.loadListen = function()
{
    var cmdiv = $('.feed_share_custom');
}
function _download(songId)
{
    $.ajaxCall('musicsharing.downloadSong','musicid='+songId);
}
function _addPlaylist(songId, userId)
{
    $Core.box('musicsharing.addplaylist', 400,'idsong='+songId);
}
function _add2cart(songId, userId)
{
    //alert("_add2cart " + songId + " " + userId);
    $.ajaxCall('musicsharing.cart.addtocart','item_id='+songId+'&type=song');
}
function _rate(songId, userId, star)
{
    $.ajaxCall('musicsharing.ratingSong','item_id='+songId+'&uid='+userId+'&vote='+star);
    //alert("_rate " + songId + " " + userId + " " + star);
}

function _onItemChanged(songId) {
	try {
		// console.log(songId);
		type = "";
		if($("#_idplaylist").size() > 0) {
			type = "2";
		} else {
			type = "1";
		}
		$.ajaxCall('musicsharing.changeSong', 'musicid='+songId + "&typ=" + type);
	} catch(err) {
		log(err);
	}
}

var _playerResize = function(height){
	// $("#flashContent").css({
		// "height": (height + "px"),
		// "overflow": "hidden"
	// });
	// $("#currentAlbumImg").css({
		// "height": ((height < 300/* px */?300:height) + "px")
	// });
}

function wakeup(){
    var objs = document.getElementsByTagName("OBJECT");
    for(var i=0; i<objs.length; i++){
        if(objs[i].wakeup){
            objs[i].wakeup();
            return;
        }
    }
}

var log = function(obj){
}

window.onerror=function(){
	log('An error has occurred!')
}

window["objectid"] = new Object();