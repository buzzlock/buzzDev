<div id="ynmssg_count" style="display: none;"></div>
<input type="hidden" id="queue_id" />
<input type="hidden" id="photoIds" />
<div id="import_step1" style="display:none;padding:10px;">
	<div id="step_download">
		<ul>
			<li style="font-weight:bold;">{phrase var='socialmediaimporter.downloading_photos'}<img style="float:right;" src="{$sCorePath}module/socialmediaimporter/static/image/add.gif"/></li>
		</ul>
	</div>
	<div id="step_download_success" style="display:none;">
		<ul>
			<li style="font-weight:bold;">{phrase var='socialmediaimporter.photos_successfully_downloaded'}</li>
			<li>
				<ul id="option_import" style="padding:10px 10px 0px 10px;">
					<li><input type="radio" id="import_now" name="option_imports" checked="checked" />{phrase var='socialmediaimporter.import_now'}</li>			
					<li><input type="radio" id="auto_import" name="option_imports" />{phrase var='socialmediaimporter.auto_import_and_inform_me_when_finish'}</li>
				</ul>
			</li>
			<li id="recommend" style="padding:5px 5px;">	
				<div class="message">{phrase var='socialmediaimporter.recommend_for_import' max=$iMaxImport}</div>			
			</li>
		</ul>
		<div style="float:right;">
			<input style="display:none;" type="button" class="button" value="{phrase var='socialmediaimporter.finish'}" id="finish-button" />
			<input type="button" class="button" value="{phrase var='socialmediaimporter.next'}" id="next-button" />
			<input type="button" class="button" value="{phrase var='socialmediaimporter.cancel'}" id="cancel-button" />
		</div>
	</div>
	<div class="clear"></div>
</div>

<div id="import_step2" style="display:none;padding:10px;">
	<div id="step_importing">
		<ul>
			<li style="font-weight:bold;">{phrase var='socialmediaimporter.importing_photos'} (<span id="total_imported">1</span>/<span id="total_media"></span>) <img style="float:right;" src="{$sCorePath}module/socialmediaimporter/static/image/add.gif"/></li>
			<li>
				<div id="progressbar">
					<div id="total_percent"></div>
				</div>
			</li>
		</ul>
	</div>
	<div id="step_import_finish" style="display:none;">
		<ul> 
			<li style="font-weight:bold;"><div id="msg_import_result">{phrase var='socialmediaimporter.photos_successfully_imported'}</div></li>
		</ul>
		<ul style="margin-top:15px;">
			<li>{phrase var='socialmediaimporter.total'}: <span id="finish_total"></span></li>
			<li>{phrase var='socialmediaimporter.success'}: <span id="finish_success"></span></li>
			<li>{phrase var='socialmediaimporter.fail'}: <span id="finish_fail"></span></li>
		</ul>
		<div style="margin:10px;0px;">
			<div style="float:left;">		
				<a id="link_view_photo" style="cursor:pointer;">{phrase var='socialmediaimporter.view_uploaded_albums_photos'}</a>
			</div>
			<div style="float:right;">		
				<input type="button" class="button" value="{phrase var='socialmediaimporter.close'}" onclick="tb_remove();" />
			</div>
		</div>
	</div>
	<div class="clear"></div>
</div>

<div class="selectAlbumBox">
<form id="submit-form" action="" method="post">
	{if Phpfox::getUserParam('photo.can_create_photo_album')}
		<div class="table" id="album_table">
			<div class="table_left">
				{phrase var='photo.photo_album'}
			</div>
			<div class="table_right_text">
				<span id="js_photo_albums"{if !count($aAlbums)} style="display:none;"{/if}>
					<select name="val[album_id]" id="js_photo_album_select" style="width:200px;" onchange="if (empty(this.value)) {l} $('#js_photo_privacy_holder').slideDown(); {r} else {l} $('#js_photo_privacy_holder').slideUp(); {r}">
						<option value="">{phrase var='photo.select_an_album'}:</option>
						{foreach from=$aAlbums item=aAlbum}
							<option value="{$aAlbum.album_id}">{$aAlbum.name|clean}</option>
						{/foreach}
					</select>
				</span>&nbsp;(<a href="#" id="form_create_album">{phrase var='photo.create_a_new_photo_album'}</a>)
			</div>
		</div>
	{/if}
	{if Phpfox::getParam('photo.allow_photo_category_selection')}
		<div class="table">
			<div class="table_left">
				<label for="category">{phrase var='photo.category'}:</label>
			</div>
			<div class="table_right">
				{module name='photo.drop-down'}
			</div>
		</div>
	{/if}
	<input name="service" type="hidden" value="{$sService}" />
	<div id="js_custom_privacy_input_holder_album"></div>

	<div id="createAlbumBox" class="createAlbumBox" style="display:none;">
		<div class="table">
			<div class="table_left">
			{required}{phrase var='photo.name'}:
			</div>
			<div class="table_right">
				<input type="text" name="val[name]" id="name" value="" size="30" maxlength="150" />
			</div>
			<div class="clear"></div>
		</div>
		<div class="table">
			<div class="table_left">
				{phrase var='photo.description'}:
			</div>
			<div class="table_right">
				<textarea name="val[description]" id="description" cols="40" rows="5"></textarea>
			</div>
			<div class="clear"></div>
		</div>

		<div class="table">
			<div class="table_left">
				{phrase var='photo.album_s_privacy'}:
			</div>
			<div class="table_right">
				{module name='privacy.form' privacy_name='privacy' privacy_info='photo.control_who_can_see_this_photo_album_and_any_photos_associated_with_it' privacy_custom_id='js_custom_privacy_input_holder_album'}
			</div>
		</div>
		<div class="table">
			<div class="table_left">
				{phrase var='photo.comment_privacy'}:
			</div>
			<div class="table_right">
				{module name='privacy.form' privacy_name='privacy_comment' privacy_info='photo.control_who_can_comment_on_this_photo_album_and_any_photos_associated_with_it' privacy_no_custom=true}
			</div>
		</div>
	</div>

	<div style="float:right;">
		<input type="button" class="button" value="{phrase var='socialmediaimporter.submit'}" id="submit-button" style="display:none;margin-top:10px,margin-right:10px;"/>
		<input type="button" class="button" value="{phrase var='socialmediaimporter.import'}" id="import-button" style="margin-top:10px,margin-right:10px;"/>
		<input type="button" class="button" value="{phrase var='socialmediaimporter.close'}" id="close-button" style="margin-top:10px,margin-right:10px;"/>
	</div>
	<div class="clear"></div>
</form>
</div>

{literal}<script language="javascript" type="text/javascript">
var service = "{/literal}{$sService}{literal}";
var service_album_id = "{/literal}{$sServiceAlbumId}{literal}";
var error_step1 = 0;
var error_step2 = 0;	
$Behavior.importInitPopup = function() {
	var $form = $("#submit-form");
	var $importButton = $("#import-button");
	var $closeButton = $("#close-button");
	var $submitButton = $("#submit-button");

	var popup = $form.closest(".js_box");
	var btDiv = popup.find(".js_box_close");

	$('#import_now').click(function(evt) {
		$('#finish-button').hide();
		$('#next-button').show();
	});
	$('#auto_import').click(function(evt) {
		$('#next-button').hide();
		$('#finish-button').show();
	});
	$('#next-button').click(function(evt) {
		var queue = $("#queue_id").val();
		var photoIds = $("#photoIds").val();
		importPhoto(2, photoIds, queue);
	});
	$('#finish-button').click(function(evt) {
		var queue = $("#queue_id").val();
		$Core.ajax('socialmediaimporter.setAutoQueue',
		{
			params:
			{
				queue: queue
			},
			type: 'POST',
			success: function(response)
			{
				tb_remove();
			}
		});
	});
	$('#cancel-button').click(function(evt) {
		var queue = $("#queue_id").val();
		$Core.ajax('socialmediaimporter.cancelQueue',
		{
			params:
			{
				queue: queue
			},
			type: 'POST',
			success: function(response)
			{
				tb_remove();
			}
		});
	});
	$importButton.click(function(evt) {
		evt.preventDefault();
		var photoIds = '';
		$(".js_box_close").hide();
		$("#list_photos .moderate_link_active").each(function() {
			photoIds = photoIds + $(this).attr('alt') + '","';
			$("#context_" + $(this).attr('alt')).find("div.status").html("<span style=\"color: #FF0000; font-weight: bold;\">{/literal}{phrase var='socialmediaimporter.imported'}{literal}</span>");
		});
		importPhoto(1, photoIds, 0);
		$form.hide();
		return false;
	});

	$submitButton.click(function(evt) {
		var name = $.trim($('#name').val());
		var description = $('#description').val();
		var privacy = $('#privacy').val();
		var privacy_comment = $('#privacy_comment').val();
		if (name == '') return;
		$Core.ajax('socialmediaimporter.addNewAlbum',
		{
			params:
			{
				name: name,
				description: description,
				privacy: privacy,
				privacy_comment: privacy_comment
			},
			type: 'POST',
			success: function(response) {
				if (response) {
					$('#js_photo_albums').show();
					$('#createAlbumBox').hide();
					$('#submit-button').hide();
					$('#import-button').show();
					$('#js_photo_album_select').append(response);
				}
			}
		});
	});

	$("#form_create_album").click(function(evt){
		evt.preventDefault();
		$submitButton.show();
		$importButton.hide();
		$("#createAlbumBox").show("fast");
		return false;
	});

	importPhoto = function(step, photoIds, queue) {
		var album_id = $('#js_photo_album_select').val();
		var privacy = $('#privacy').val();
		var privacy_comment = $('#privacy_comment').val();
		if (step == 1) {
			$("#import_step1").show();
			$("#import_step2").hide();
			$("#import_finish").hide();
		}
		if (step == 2) {
			$("#import_step1").hide();
			$("#import_step2").show();
			$("#import_finish").hide();
		}
		$Core.ajax('socialmediaimporter.importPhotos',
		{
			params:
			{
				step: step,
				album_id: album_id,
				photo_id: photoIds,
				privacy: privacy,
				privacy_comment: privacy_comment,
				service: service,
				service_album_id: service_album_id,
				queue: queue
			},
			type: 'POST',
			success: function(response)
			{
				if (step == 1) {					
					var response = $.parseJSON(response);
					var count = response['count'];
					var queue = response['queue'];
					var error = response['error'];
					if (count > 0) {
						$("#queue_id").val(queue);					
						$("#photoIds").val(photoIds);											
						$("#step_download").hide();					
						$("#step_download_success").show();					
						$("#total_media").html(count);
						if (count < 10) {
							importPhoto(2, photoIds, queue);
						}
					} else if (error) {
						error_step1++;
						if (error_step1 <= 1) importPhoto(1, photoIds, 0);
					}
				}				
				if (step == 2) {
					var response = $.parseJSON(response);
					var error = response['error'];
					var total_percent = response['total_percent'];
					var total_photo = response['total_photo'];
					var total_imported = response['total_imported'];
					var total_current = response['total_current'];
					var total_success = response['total_success'];
					var total_fail = response['total_fail'];
					var url_redirect = response['url_redirect'];
					var queue = response['queue'];					
					if (total_current > 0 && error == '') {
						$("#total_imported").html(total_imported);
						$("#total_percent").css('width', total_percent);
						$("#total_percent").html(total_percent);
						importPhoto(2, photoIds, queue);
					} else {						
						$(".deSelectAllBtn").click();
						$("#finish_total").html(total_photo);
						$("#finish_success").html(total_success);
						$("#finish_fail").html(total_fail);
						$("#step_importing").hide();
						$("#step_import_finish").show();
						$('#link_view_photo').click(function(evt) {
							location.href = url_redirect;
						});
						if (error != '') {
							$("#msg_import_result").addClass('error_message');
							$("#msg_import_result").html(''+error); 							
						}
					}
				}
			}
		});
	}

	$closeButton.click(function(evt) {
		evt.preventDefault();
		tb_remove();
		return false;
	});
	setTimeout(function(){btDiv.hide();}, 1);
}
</script>{/literal}