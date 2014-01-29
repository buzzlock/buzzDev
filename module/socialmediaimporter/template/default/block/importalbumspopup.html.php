<div id="ynmssg_count" style="display: none;"></div>
<input type="hidden" id="queue_id" />
<input type="hidden" id="albumIds" />
<input type="hidden" id="albumNames" />
<div id="import_step1" style="display:none;padding:10px;">
	<div id="step_download">
		<ul>
			<li style="font-weight:bold;">{phrase var='socialmediaimporter.downloading_photos_in_albums'}<img style="float:right;" src="{$sCorePath}module/socialmediaimporter/static/image/add.gif"/></li>
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
			<li id="recommend">	
				<div style="margin:15px 2px;" class="message">{phrase var='socialmediaimporter.recommend_for_import' max=$iMaxImport}</div>			
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

<form id="submit-form" action="" method="post">
	<input name="service" type="hidden" value="{$sService}" />
	<div id="js_custom_privacy_input_holder_album"></div>
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
	<div style="float:right;">
		<input type="button" class="button" value="{phrase var='socialmediaimporter.import'}" id="import-button" />
		<input type="button" class="button" value="{phrase var='socialmediaimporter.close'}" id="close-button" />
	</div>
	<div class="clear"></div>
</form>
{literal}<script language="javascript" type="text/javascript">
var error_step1 = 0;
var error_step2 = 0;
var service = "{/literal}{$sService}{literal}";	
$Behavior.importInitPopup = function() {			
	var $form = $("#submit-form");
	var $importButton = $("#import-button");
	var $closeButton = $("#close-button");
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
		var albumIds = $("#albumIds").val();		
		var albumNames = $("#albumNames").val();		
		importAlbum(2, albumIds, albumNames, queue);
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
		var albumIds = '';		
		var albumNames = '';		
		$(".js_box_close").hide();		
		$("#list_albums .moderate_link_active").each(function() {			
			albumIds = albumIds + $(this).attr('alt') + '","';
			albumNames = albumNames + $(this).attr('title') + '","';
			$("#context_" + $(this).attr('alt')).find("div.status").html("<span style=\"color: #FF0000; font-weight: bold;\">{/literal}{phrase var='socialmediaimporter.imported'}{literal}</span>");
		});
		importAlbum(1, albumIds, albumNames, 0);		
		$form.hide();		
		return false;
	});	
	
	importAlbum = function(step, albumIds, albumNames, queue) {		
		var privacy = $('#privacy').val();
		var privacy_comment = $('#privacy_comment').val();
		if (step == 1) {
			$("#import_step1").show();
			$("#import_step2").hide();			
		}
		if (step == 2) {
			$("#import_step1").hide();
			$("#import_step2").show();			
		}
		$Core.ajax('socialmediaimporter.importAlbums',
		{
			params:
			{				
				step: step,
				privacy: privacy,
				privacy_comment: privacy_comment,
				service: service,
				albumIds: albumIds,
				albumNames: albumNames,
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
						$("#albumIds").val(albumIds);					
						$("#albumNames").val(albumNames);					
						$("#step_download").hide();					
						$("#step_download_success").show();					
						$("#total_media").html(''+count);
						if (count < 10) {
							importAlbum(2, albumIds, albumNames, queue);
						}
					} else if (error) {
						error_step1++;
						if (error_step1 <= 1) importAlbum(1, albumIds, albumNames, 0);
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
					$("#total_imported").html(''+total_imported);					
					if (total_current > 0) { 
						$("#total_percent").css('width', total_percent);	
						$("#total_percent").html(''+total_percent);	
						importAlbum(2, albumIds, albumNames, queue);
					} else {
						$(".deSelectAllBtn").click();
						$("#finish_total").html(''+total_photo);
						$("#finish_success").html(''+total_success);
						$("#finish_fail").html(''+total_fail);						
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