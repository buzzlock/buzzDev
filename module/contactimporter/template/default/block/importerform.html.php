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
	<form id="get_contact_email" name="get_contact_email" onsubmit="" enctype="" class="global_form" action="{url link='contactimporter'}" method="post" autocomplete="off">
		<div class="form-elements toggle_container">
			<table cellpadding="2" style="border-spacing: 2px; border-collapse: separate;">
				<tr>
					<td class="td_0">
						<label for="email_box" class="required" >{phrase var='user.email'}</label>
					</td>
					<td>
						<input type="text" name="email_box" id="email_box" value="" style="width:205px;" onkeypress="return submitViaEnter(this,event)" />
					</td>
				</tr>
				<tr>
					<td class="td_0">
						<label for="password_box" class="required">{phrase var='user.password'}</label>
					</td>
					<td>
						<input type="password" style="width:205px" name="password_box" id="password_box" value="" onkeypress="return submitViaEnter(this,event)" />
					</td>
				</tr>
				<tr>
					<td class="td_0">&nbsp;</td>
					<td><input class="button" name="import" id="import" type="submit" onclick="" value="{phrase var='contactimporter.import_contact'}" /></td>
				</tr>
			</table>
		</div>
		<input type='hidden' value={$email.default_domain} name='tmp' id='provider_box_mail2' autocomplete ="OFF"/>
		<input type='hidden' value='' id='provider_box_input2' name='provider_box2'/>
		<input type='hidden' value="{$email.name}" id='provider_box_mail' name='provider_box'/>
	</form>
</div>