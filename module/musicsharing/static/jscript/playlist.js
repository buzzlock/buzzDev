
//Create a new flash audio - large player
 $(function(){
        $('video,audio#mejs').mediaelementplayer({
            success: function (mediaElement, domObject) {
                mediaElement.addEventListener('ended', function (e) {
                    mejsPlayNext(e.target);
                }, false);
            },
			features: ['prev','playpause','next','progress','duration','shuffle','loop','col','volume'],
            keyActions: []
        });     

    });
	
 $(function(){
        $('audio#mejs-small').mediaelementplayer({
            success: function (mediaElement, domObject) {
                mediaElement.addEventListener('ended', function (e) {
                    mejsPlayNext(e.target);
                }, false);
            },
			features: ['prev','playpause','next','progress'],
            keyActions: []
        });     

    });
	
// Select a song
$(function(){
	 $('.mejs-list li > span.song-title').click(function() {
        $(this).parent().addClass('current').siblings().removeClass('current');
        var strType = $('#_idalbum').length > 0 ? 1 : 0;
        var strSongTitle = $(this).text();
        var audio_src = $(this).parent().children(".link").text();			
        var iSongId = $(this).parent().children(".song_id").text();
        
        $('audio:first').each(function(){            
            if($('#mejs').length > 0){
                $.ajaxCall('musicsharing.changSongHTML5', 'musicid=' + iSongId + '&typ=' + strType, 'POST');
            }else{
                $.ajaxCall('musicsharing.changSongHTML5Small', 'musicid=' + iSongId + '&typ=' + strType, 'POST');
            }
            this.player.pause();
            this.player.setSrc(audio_src);
            
            // Change the move text.
            $('#song-title-head').html(strSongTitle);
            
            this.player.play();
        });
    });
});

//Get an random number
function getRandomInt (min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function getNextSong(current_item){//get next li to get song
	var nextItem ='';
	var ul = $('#song-list');
	var li= ul.children('li').size();
	if($('.mejs-shuffle-on').length > 0){		
		var nextItemGet = getRandomInt(0,li - 1);
		nextItem =  $(".mejs-list li").eq(nextItemGet);
	}
	else if($('.mejs-loop-on').length > 0){
		if(li == 1)
		{
			nextItem = $(current_item);
		}
	}
	else{
		nextItem = $(current_item).parent().next();
	}
	return nextItem;
}

function scrollBar(){
	scroll = $('.scroll-pane').scrollTop();
	$(".slimScrollBar").css("top", 0);
	$('#song-list').slimScroll({ "scroll": (scroll + "px") });
	$("#song-list").trigger(jQuery.Event("updatescroller"));
}
//get next song's link 
function nextSong(current_item){
	
	var nextItem = '';
	nextItem = getNextSong(current_item);//get next song
	
	/*Scroll */
	$("#song-list").scrollTop(0);
	$("#song-list").scrollTo(current_item.closest("li"));
	$("#song-list").scrollTo($(".current"));
	//scrollBar();
	/*End*/
	var audio_src = nextItem.children(".link").text();
	var song_title = nextItem.children(".song-title").text();
	if($('.song-title-head').size() > 0){
		document.getElementById('song-title-head').innerHTML = song_title;
	}
	
	nextItem.addClass('current').siblings().removeClass('current');
	
	return audio_src;
}
//play new playlist
function newPlay(current_item){
	var nextItem = '';
	nextItem = getNextSong(current_item);//get next song
	var audio_src = nextItem.next().text();
	$(current_item).next().addClass('current').siblings().removeClass('current');
	return audio_src;
}	
//Choose next song
function mejsPlayNext(currentPlayer) {
	var current_item ='';
	var audio_src='';
	if ($('.mejs-list li.current').length > 0){ // get the .current song	
		current_item = $('.mejs-list li.current:first span.link'); // :first is added if we have few .current classes
		audio_src = nextSong(current_item);
		
	}else{ // if there is no .current class
		current_item = $('.mejs-list li:first .link'); // get :first if we don't have .current class
		audio_src = newPlay(current_item);
	}

	if( $(current_item.parent()).is(':last-child') ) { // if it is last - stop playing
		if($('.mejs-loop-on').size() > 0){
			var ul = $('#song-list');
			var li= ul.children('li').size();
			var song_title = '';
			if(li == 1)
			{
				audio_src = current_item.text();
				if($('.song-title-head').size() > 0){
					song_title = current_item.parent().children(".song-title").text();
				}
				
			}
			else{
				current_item = $('.mejs-list li:first span.link');
				$('.mejs-list .current').removeClass("current");
				$('.mejs-list li:first').addClass("current");
				audio_src = current_item.text();
				if($('.song-title-head').size() > 0){
					song_title = current_item.parent().children(".song-title").text()
				}
				;				
			}
			if($('.song-title-head').size() > 0){
				document.getElementById('song-title-head').innerHTML = song_title;
			}			
			currentPlayer.setSrc(audio_src);
			currentPlayer.play();
			/*Scroll*/
			$("#song-list").scrollTop(0);
			//scrollBar();
			/*End*/		
				
		}
		else if($('.mejs-shuffle-on').size() > 0){
			current_item = nextSong(current_item);
			audio_src = current_item.text();
			currentPlayer.setSrc(audio_src);
			currentPlayer.play();
			$("#song-list").scrollTo(current_item);
			//scrollBar();
		}
		else{
			$(current_item).removeClass('current');
		}
		
	}else{
		currentPlayer.setSrc(audio_src);
		currentPlayer.play();
	}
}

//Build Next Button
(function($) {
    // next button
    MediaElementPlayer.prototype.buildnext = function(player, controls, layers, media) {
        var
            // create the next button
		next =  
		$('<div class="mejs-button mejs-next-button ' + ((player.options.next) ? 'mejs-next-on' : 'mejs-next-off') + '">' +
		 '<button type="button"></button>' +
		'</div>')
		// append it to the toolbar
		.appendTo(controls)
		//Click on next button
		.click(function (evt) {
			var nextsong = '';
			var audio_src= '';
			var song_title= '';
			var current_item = $('#song-list').find('li.current');
			if ($('.mejs-shuffle-on').size() > 0) { //check shuffle mode
				var ul = $('#song-list');
				var li= ul.children('li').size();
				if(li <= 1) {
					nextItemGet = 0;
				} else {
					while((nextItemGet = getRandomInt(0,li - 1)) == $('#song-list').find('li.current').index()) {
					}
				}
				nextsong =  $(".mejs-list li").eq(nextItemGet);
				
				$('#song-list').find('li.current').removeClass('current');
				audio_src = nextsong.children(".link").text();
				if($('.song-title-head').size() > 0){
					song_title = nextsong.children(".song-title").text();
				}
				
				nextsong.addClass('current');
			}else if($(current_item).is(':last-child')){
				//if($('.mejs-loop-on').size() > 0){
					nextsong = $('.mejs-list li:first span.link');
					audio_src = nextsong.text();
					if($('.song-title-head').size() > 0){
						song_title = nextsong.parent().children(".song-title").text(); 
					}
					
					$('#song-list').find('li.current').removeClass('current');
					nextsong.parent().addClass('current');
				//}
			}
			else {
				nextsong = $('#song-list').find('li.current').next();
				if(!nextsong.size()) {
					return true;
				}
				
				$('#song-list').find('li.current').removeClass('current');
				audio_src = nextsong.children(".link").text();
				if($('.song-title-head').size() > 0){
					song_title = nextsong.children(".song-title").text();
				}
				
				nextsong.addClass('current');
			}
			
			//$('audio#mejs:first').each(function () {
				player.pause();
				player.setSrc(audio_src);
				if($('.song-title-head').size() > 0){
					document.getElementById('song-title-head').innerHTML = song_title;
				}
				
				player.play();
				$("#song-list").scrollTo(nextsong);
				//scrollBar();
			//});
		});
            // add a click toggle event
               
    }
})(jQuery);

// $(document).ready(function(){
	// $('.mejs-next-button')
// });


//Build Previous button
(function($) {
    // prev button
    MediaElementPlayer.prototype.buildprev = function(player, controls, layers, media) {
        var
            // create the prev button
            prev =  
            $('<div class="mejs-button mejs-prev-button ' + ((player.options.prev) ? 'mejs-prev-on' : 'mejs-prev-off') + '">' +
                '<button type="button"></button>' +
            '</div>')
            // append it to the toolbar
            .appendTo(controls)
            // add a click toggle event
            .click(function (evt) {
				var prevsong= '';
				var audio_src ='';
				var song_title = '';
				var current_item = $('#song-list').find('li.current');
				if ($('.mejs-shuffle-on').size() > 0) { //check shuffle mode
					var ul = $('#song-list');
					var li= ul.children('li').size();
					if(li <= 1) {
						nextItemGet = 0;
					} else {
						while((nextItemGet = getRandomInt(0,li - 1)) == $('#song-list').find('li.current').index()) {
						}
					}
					prevsong =  $(".mejs-list li").eq(nextItemGet);
					
					$('#song-list').find('li.current').removeClass('current');
					audio_src = prevsong.children(".link").text();
					if($('.song-title-head').size() > 0){
						song_title = prevsong.children(".song-title").text();
					}
					
					prevsong.addClass('current');
				} else {
					prevsong = $('#song-list').find('li.current').prev();
					if(!prevsong.size()) {
						return true;
					}
					
					$('#song-list').find('li.current').removeClass('current');
					audio_src = prevsong.children(".link").text();
					if($('.song-title-head').size() > 0){
						song_title = prevsong.children(".song-title").text()
					}
					;
					prevsong.addClass('current');
				}
				
				//$('audio#mejs:first').each(function () {
					player.pause();
					player.setSrc(audio_src);
					if($('.song-title-head').size() > 0){
						document.getElementById('song-title-head').innerHTML = song_title;
					}
					
					player.play();
					$("#song-list").scrollTo(prevsong);
					//scrollBar();
				//});
		});  
    }
})(jQuery);

//Build repeat button
(function($) {
    // prev button
    MediaElementPlayer.prototype.buildloop = function(player, controls, layers, media) {
        var
            // create the prev button
            loop =  
            $('<div class="mejs-button mejs-loop-button ' + ((player.options.loop) ? 'mejs-loop-on' : 'mejs-loop-off') + '">' +
                '<button type="button"></button>' +
            '</div>')
            // append it to the toolbar
            .appendTo(controls)
            // add a click toggle event
            .click(function() {
				// if($('.mejs-shuffle-on').length > 0){
					 // $('.mejs-shuffle-button').removeClass('mejs-shuffle-on').addClass('mejs-shuffle-off');
				// }
                player.options.loop = !player.options.loop;
                if (player.options.loop) {
                    loop.removeClass('mejs-loop-off').addClass('mejs-loop-on');
					player.options.shuffle = false;
				$('.mejs-shuffle-button').removeClass('mejs-shuffle-on').addClass('mejs-shuffle-off');
                } else {
                    loop.removeClass('mejs-loop-on').addClass('mejs-loop-off');
                }
            });     
    }
})(jQuery);

//Build Shuffle button
(function($) {
    // prev button
    MediaElementPlayer.prototype.buildshuffle = function(player, controls, layers, media) {
        var
            // create the prev button
            shuffle =  
            $('<div class="mejs-button mejs-shuffle-button ' + ((player.options.shuffle) ? 'mejs-shuffle-on' : 'mejs-shuffle-off') + '">' +
                '<button type="button"></button>' +
            '</div>')
            // append it to the toolbar
            .appendTo(controls)
            // add a click toggle event
            .click(function() {
				// if($('.mejs-loop-on').length > 0){
					 // $('.mejs-loop-button').removeClass('mejs-loop-on').addClass('mejs-loop-off');
				// }
                player.options.shuffle = !player.options.shuffle;
                if (player.options.shuffle) {
                    shuffle.removeClass('mejs-shuffle-off').addClass('mejs-shuffle-on');
				player.options.loop = false;
				$('.mejs-loop-button').removeClass('mejs-loop-on').addClass('mejs-loop-off');
                } else {
                    shuffle.removeClass('mejs-shuffle-on').addClass('mejs-shuffle-off');
                }
            });     
    }
})(jQuery);

// 
(function($) {
    // prev button
    MediaElementPlayer.prototype.buildcol = function(player, controls, layers, media) {
        var
            // create the prev button
            col =  
            $('<div class="col-vol">' +
                '<span></span>' +
            '</div>')
            // append it to the toolbar
            .appendTo(controls)
            // add a click toggle event            
    }
})(jQuery);