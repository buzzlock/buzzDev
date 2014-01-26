{literal}
<style type="text/css">
	.formpopup .global_form div.form-elements {
		margin-left: 50px;
	}
	#get_contact_email table .td_0{padding-right: 10px;width: 100px;}
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
	<form id="get_contact_email" enctype="multipart/form-data" name="get_contact_email" class="global_form" action="{url link='contactimporter.csv'}" method="post">
		<div class="form-elements toggle_container">
			<table>
				<tr>
					<td class="td_0">
						<label for="email_box" class="required" >{phrase var='contactimporter.upload_file_csv'}</label>
					</td>
					<td>
						<input type="file" class="text" name="csvfile"/>
					</td>
				</tr>
				<tr>
					<td class="td_0">&nbsp;</td>
					<td>
						<input name="submit_button" type="submit" style="margin-left:5px;" class="button" value="{phrase var='contactimporter.read_contact'}" />
					</td>
				</tr>
			</table>
		</div>
		<input type="hidden" name="uploadcsv" value="uploadcsv"/>
	</form>
</div>