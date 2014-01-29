backToAlbums = function() {
	$('#list_photos').hide();
	$('#list_albums').show();	
	var display = $('#feed_view_more_loader').css('display');
	if (display != 'none') return;	
	hasViewMorePhoto = $('#global_view_more_photo').css('display') != 'none' ? 1 : 0;
	if (hasViewMorePhoto == 1) {
		$('#global_view_more_photo').hide();
	}
	$('.backAL').hide();
}

loadMoreAlbums = function(auto) {
	$('.backAL').hide();
	if (yn_albums_page == 1) {
		$('#action_buttons').hide();
	}
	$('#global_view_more_photo').hide();
	$('#list_photos').hide();
	$('#list_albums').show();	
	if (auto == -1) {	
		$('#feed_view_more_loader').hide();
		$('#global_view_more_album').hide();
		return;
	}
	$('#feed_view_more_loader').show();
	if (auto && yn_albums_page > 2) {
		$('#feed_view_more_loader').hide();
		$('#global_view_more_album').show();
	} else {		
		$.ajaxCall("socialmediaimporter.loadAlbums",'service='+sService+'&page='+yn_albums_page+'&auto='+auto);
	}
}

loadMorePhotos = function(auto) {		
	if (sType == 'photo') {
		$('.backAL').hide();	
	}
	if (yn_photos_page == 1) {
		$('#action_buttons').hide();
	}	
	$('#global_view_more_album').hide();
	$('#list_albums').hide();
	$('#list_photos').show();
	if (auto == -1) {	
		$('#feed_view_more_loader').hide();
		$('#global_view_more_photo').hide();
		if (sType != 'photo') $('.backAL').show();
		$('#action_buttons').show();		
		return;
	}
	$(".backAL").hide();
	$('#feed_view_more_loader').show();
	if (auto && yn_photos_page > 2) {
		$('#feed_view_more_loader').hide();			
		$('#global_view_more_photo').show();	
		if (sType != 'photo') $('.backAL').show();
		$('#action_buttons').show();
	} else {
		/*
		$Core.ajax('socialmediaimporter.loadPhotos',
		{
			params:
			{				
				service: sService,
				page: yn_photos_page,
				album_id: yn_album_id,
				auto: auto
			},
			type: 'POST',
			success: function(response)
			{	
				if (sType == 'album') $(".backAL").show();
			}
		});
		*/
		$.ajaxCall("socialmediaimporter.loadPhotos",'service='+sService+'&page='+yn_photos_page+'&album_id='+yn_album_id+'&auto='+auto);
	}	
}

$Behavior.loadAlbums = function() {
	if (yn_load_albums_init == false && sType == 'album') {
		loadMoreAlbums(1); 		
	}
}

$Behavior.loadPhotos = function() {
	if (yn_load_photos_init == false && sType == 'photo') {
		loadMorePhotos(1);
	}
}

$Behavior.loadJSSimporter = function() {	
	$('.context').show();	
	$(".show-photoalbums,.context").mouseenter(function(evt) {
		var id = $(this).attr('ref');
		$('#context_'+id).show();
	});	
	$(".show-photoalbums,.context").mouseleave(function(evt) {
		var id = $(this).attr('ref');
		$check = $('#moderate_link_'+id);
		if ($check.hasClass("moderate_link_active")) {
			return false;
		}
		//$('#context_'+id).hide();
	});
	$(".show-photoalbums").click(function(evt) {
		if (yn_album_id != $(this).attr('ref'))	{
			hasViewMorePhoto = 0;
			yn_photos_page = 1;
			$('#list_photos').html('');			
		} else {
			if (hasViewMorePhoto == 1) {
				$('#global_view_more_photo').show();
			}
		}		
		yn_album_id = $(this).attr('ref');	
		var title_album = oTranslations['socialmediaimporter.album'] + ': ' + $('#moderate_link_'+yn_album_id).attr('title');		
		$('#title_album').html(title_album);		
		loadMorePhotos(1);
	});
	$(".show-photos").click(function(evt) {
		var id = $(this).attr('ref');
		$("#moderate_link_"+id).click();		
	});
	
	/*
	$(".show-photos,.context").mouseenter(function(evt) {
		var id = $(this).attr('ref');
		$('#context_'+id).show();
	});	
	$(".show-photos,.context").mouseleave(function(evt) {		
		var id = $(this).attr('ref');
		$check = $('#moderate_link_'+id);
		if ($check.hasClass("moderate_link_active")) {
			return false;
		}
		$('#context_'+id).hide();
	});
	*/
	$(".moderate_link").unbind().click(function(evt) {
		evt.preventDefault();
		var $this = $(this);
		if ($this.hasClass("moderate_link_active")) {
			$this.removeClass("moderate_link_active");
		} else {
			$this.addClass("moderate_link_active");
			$(".row_edit_bar_action").parent().find(".row_edit_bar_holder").hide();
		}
		return false;
	});	
	
	$(".row_edit_bar_action").click(function() {		
		var $this = $(this);
		if ($this.hasClass("row_edit_bar_action_clicked")) {
			$this.parent().find(".row_edit_bar_holder").show();
		} else {
			$this.parent().find(".row_edit_bar_holder").hide();
		}
	});
	
	$(".selectAllBtn").click(function(evt) {	
		$(".moderate_link").addClass("moderate_link_active");
		$(".context").show();
		$(".deSelectAllBtn").removeClass("disabled");		
	});
	
	$(".deSelectAllBtn").click(function(evt) {	
		$(".moderate_link").removeClass("moderate_link_active");
		//$(".context").hide();
	});
	
	$(".imporSelected").click(function(evt) {		
		var tab = $('#list_albums').css('display') != 'none' ? 'album' : 'photo';		
		evt.preventDefault();
		if (tab == 'album') {
			if ($(".moderate_link_active").size() <= 0 ) {
				alert(oTranslations['socialmediaimporter.please_select_photo_s_to_import']);
				return false;
			}
			$(".moderate_link_active").click().addClass("moderate_link_active");
			tb_show("", $.ajaxBox('socialmediaimporter.importAlbumsPopup', "width=500" + "&service=" + sService));
		}
		if (tab == 'photo') {			
			if ($(".moderate_link_active").size() <= 0 ) {
				alert(oTranslations['socialmediaimporter.please_select_photo_s_to_import']);
				return false;
			}
			$(".moderate_link_active").click().addClass("moderate_link_active");
			tb_show("", $.ajaxBox('socialmediaimporter.importPhotosPopup', "width=500" + "&service=" + sService + '&service_album_id='+yn_album_id));
		}
		return false;
	});
	
	$(".refreshBtn").click(function(evt) {		
		doRefresh();
	});
	
	doRefresh = function() {
		$.ajaxCall("socialmediaimporter.refresh", "service="+sService); 
		return false;
	}	
}
