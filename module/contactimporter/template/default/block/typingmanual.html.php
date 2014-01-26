{literal}
<style type="text/css">
	.formpopup .global_form div.form-elements {
		margin-left: 50px;
	}
	#get_contact_email table .td_0{padding-right: 10px;}
</style>
{/literal}
{literal}
<script type="text/javascript">
    function submitViaEnter(myfield, e) {
        var keycode;
        if (window.event)
            keycode = window.event.keyCode;
        else if (e)
            keycode = e.which;
        else
            return true;
        if (keycode == 13) {
            myfield.form.submit();
            return false;
        } else
            return true;
    }
</script>
{/literal}
<div class="formpopup">
	<form id="get_contact_email" name="get_contact_email" onsubmit="" enctype="" class="global_form yncontact_manual_form" action="{url link='contactimporter.typingmanual'}" method="post" autocomplete="off">
		<input type="hidden" name="typingmanual" value="typingmanual"/>
		<div class="form-elements toggle_container">
			<table>
				<tr>
					<td class="td_0">
						<label for="email_box" class="required" >{phrase var='invite.to'}</label>
					</td>
					<td>
						<textarea cols="40" rows="3" id="emails" name="typing_emails" style="width:90%; height:20px;" onkeydown="$Core.resizeTextarea($(this));" onkeyup="$Core.resizeTextarea($(this));"></textarea>
						<div class="extra_info">
							{phrase var='invite.separate_multiple_emails_with_a_comma'}
						</div>
					</td>
				</tr>
				<tr>
					<td class="td_0">&nbsp;</td>
					<td><input class="button" name="import" id="import" type="submit" onclick="" value="{phrase var='contactimporter.import_contact'}" /></td>
				</tr>
			</table>
		</div>
		
	</form>
</div>