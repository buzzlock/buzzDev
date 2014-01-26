
    function feed_filter(type, value, element){
        switch(type){
            case "type":
                $("#feed_type_id").val(value);
                $("#feed_type_id_label").html(element.innerHTML);
                break;
            case "limit":
                $("#feed_limit").val(value);
                $("#feed_limit_label").html(element.innerHTML);
                break;
        }
        var viewId = $("#feed_type_id").val();
        wallFillterViewId  = viewId;
        $('#js_feed_content').html('<div id="feed_filtering_animation">' + addImg + '</div>');
        setTimeout("doFilter('"+viewId+"')", 100);
    }
    
    function doFilter(viewId) {
        $('#activity_feed_updates_link_holder').hide();
        $iReloadIteration = 0;
        $.ajaxCall('wall.filterFeed', 'resettimeline=1&profile_user_id='+iProfileUserId+'&is_filter=1&viewId=' + wallFillterViewId+ '&userId=' + $('#userId').val(), 'GET');
    };
    
    function callDoFilter(viewId) {
    	if(undefined != isDoFilterAdvWall && null != isDoFilterAdvWall && isDoFilterAdvWall == false){
    		isDoFilterAdvWall = true;
    		doFilter(viewId);
    	}    	
    }

    function callReloadActivityFeed() {
    	if(undefined != isReloadActivityFeedAdvWall && null != isReloadActivityFeedAdvWall && isReloadActivityFeedAdvWall == false){
    		isReloadActivityFeedAdvWall = true;
    		//$Core.reloadActivityFeed();
    		setTimeout("$.ajaxCall('wall.reloadActivityFeed', 'reload-ids=' + $Core.getCurrentFeedIds() + '&viewId=' + $('#feed_type_id').val(), 'GET');", 2000);
    	}    	
    }
