/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
(function( window, undefined ) {
var ynphoto = {
	loadMorePhotos : function (iYear, iPage, iLimit){
//		$('#yn_loadmore_phrase_' + iYear).remove();
		$('#yn_loadmore_phrase_waiting_icon_' + iYear).show();
		$.ajaxCall('advancedphoto.ynphoto.loadMorePhotos','iYear=' + iYear + '&iPage=' + iPage + '&iLimit='  + iLimit); 
	},
	togglePhotoYear : function(iYear, iPage, iLimit) {
		if($('#yn_loadmore_space_holder_' + iYear).is(':hidden'))
		{
			$('#yn_loadmore_space_holder_' + iYear).show();
		}
		else
		{
			$('#yn_loadmore_space_holder_' + iYear).hide();
		}

		if($('#yn_timeline_photo_header_is_load_more_' + iYear).val() == 1)
		{
			$('#yn_timeline_photo_header_is_load_more_' + iYear).val(0);
			ynphoto.loadMorePhotos(iYear, iPage, iLimit);
		}
	},
	saveAlbumOrder : function() {
		data = $('.ynadvphoto_drag_item_holder').map(function() {
			 return $(this).attr('ynadvphoto_drag_item_id');
		}).get().join(',');

		$.ajaxCall('advancedphoto.ynalbum.saveOrder','&data=' + data); 

	},
	saveAlbumPhotoOrder : function() {
		data = $('.ynadvphoto_album_photo_drag_item_holder').map(function() {
			 return $(this).attr('ynadvphoto_album_photo_drag_item_id');
		}).get().join(',');

		$.ajaxCall('advancedphoto.ynphoto.saveAlbumPhotoOrder','&data=' + data); 

	},
	removeExtraCommentItem: function() {
		$('.comment_mini_link_like li a').each(function(i) { 
			var href = $(this).attr('href');
			if(href.search('/photo/') != -1)
				{
					$(this).parent().hide();
				}
		});
	},
	movePagingToRealHolder: function() {
		var html = $('#ynadvphoto_paging_temp_holder').html();
		$('#ynadvphoto_paging_temp_holder').remove()
		$('#ynadvphoto_paging_real_holder').html(html);
	}
	
};

(function($) {

	/**
	 * Spoofs placeholders in browsers that don't support them (eg Firefox 3)
	 * 
	 * Copyright 2011 Dan Bentley
	 * Licensed under the Apache License 2.0
	 *
	 * Author: Dan Bentley [github.com/danbentley]
	 */

	// Return if native support is available.
	if ("placeholder" in document.createElement("input")) return;

	$(document).ready(function(){
		$(':input[placeholder]').not(':password').each(function() {
			setupPlaceholder($(this));
		});

		$(':password[placeholder]').each(function() {
			setupPasswords($(this));
		});
	   
		$('form').submit(function(e) {
			clearPlaceholdersBeforeSubmit($(this));
		});
	});

	function setupPlaceholder(input) {

		var placeholderText = input.attr('placeholder');

		setPlaceholderOrFlagChanged(input, placeholderText);
		input.focus(function(e) {
			if (input.data('changed') === true) return;
			if (input.val() === placeholderText) input.val('');
		}).blur(function(e) {
			if (input.val() === '') input.val(placeholderText); 
		}).change(function(e) {
			input.data('changed', input.val() !== '');
		});
	}

	function setPlaceholderOrFlagChanged(input, text) {
		(input.val() === '') ? input.val(text) : input.data('changed', true);
	}

	function setupPasswords(input) {
		var passwordPlaceholder = createPasswordPlaceholder(input);
		input.after(passwordPlaceholder);

		(input.val() === '') ? input.hide() : passwordPlaceholder.hide();

		$(input).blur(function(e) {
			if (input.val() !== '') return;
			input.hide();
			passwordPlaceholder.show();
		});
			
		$(passwordPlaceholder).focus(function(e) {
			input.show().focus();
			passwordPlaceholder.hide();
		});
	}

	function createPasswordPlaceholder(input) {
		return $('<input>').attr({
			placeholder: input.attr('placeholder'),
			value: input.attr('placeholder'),
			id: input.attr('id'),
			readonly: true
		}).addClass(input.attr('class'));
	}

	function clearPlaceholdersBeforeSubmit(form) {
		form.find(':input[placeholder]').each(function() {
			if ($(this).data('changed') === true) return;
			if ($(this).val() === $(this).attr('placeholder')) $(this).val('');
		});
	}
})(jQuery);


window.ynphoto = ynphoto;
}(window))

