{if empty($iJob)}
{literal}
<script type="text/javascript">
	function publishJob() {
		var packages = $('[name=radio_package]:checked').val();
		if(packages > 0) {
			$('#popup_packages').val(packages);
        } else {
            alert('Please select a package to publish this job.');
            return false;
        }
		var rel = $('[name=radio_package]:checked').attr('rel');
		$('#popup_paypal').val(rel);
		$('#popup_publish').val(1);
		var feature = $('[name=feature]').is(':checked');
		if(feature) {
			$('#popup_feature').val(1);	
		}
		//popup_feature
		$('#ync_edit_jobposting_form').submit();
	}
</script>
{/literal}
{else}
<script type="text/javascript">
	function publishJob() {l}
        var param = 'id={$iJob}';
        var packages = $('[name=radio_package]:checked').val();
		if (packages > 0) {l}
			param += '&package=' + packages;
        {r} else {l}
            alert('Please select a package to publish this job.');
            return false;
        {r}
		param += '&paypal=' + $('[name=radio_package]:checked').attr('rel');
		var feature = $('[name=feature]').is(':checked');
		if(feature) {l}
			param += '&feature=1';
		{r} else {l}
            param += '&feature=0';
        {r}
        $('#js_job_publish_btn').attr('disabled', true);
        $('#js_job_publish_loading').html($.ajaxProcess(oTranslations['jobposting.processing'])).show();
        $.ajaxCall('jobposting.publishJob', param);
        return false;
    {r}
</script>
{/if}

<div class="table">
	<div class="table_left">
		{phrase var='jobposting.select_you_existing_packages'}
	</div>
	<div class="table_right" style="margin-left: 20px;">
		{foreach from=$aPackages name=package item=aPackage}
		<label>
			<input rel="0" value="{$aPackage.data_id}" type="radio" name="radio_package" {if $phpfox.iteration.package==1}checked="true" {/if}/>
			{$aPackage.name} - {$aPackage.fee_text} - {if $aPackage.post_number==0}{phrase var='jobposting.unlimited'}{else}{phrase var='jobposting.remaining'} {$aPackage.remaining_post} {phrase var='jobposting.job_posts'}{/if} - {$aPackage.expire_text_2}
		</label><br />
		{foreachelse}
			{phrase var='jobposting.no_package_found'}
		{/foreach}
	</div>
</div>
<div class="table">
	<div class="table_left">
		{phrase var='jobposting.or_select_the_one_of_following_packages'}
	</div>
	<div class="table_right" style="margin-left: 20px;">
		{foreach from=$aTobuyPackages name=tbpackage item=aTBPackage}
		<label>
			<input rel="1" value="{$aTBPackage.package_id}" type="radio" name="radio_package"/>
			{$aTBPackage.name} - {$aTBPackage.fee_text} - {if $aTBPackage.post_number==0}{phrase var='jobposting.unlimited'}{else}{phrase var='jobposting.remaining'} {$aTBPackage.post_number} {phrase var='jobposting.job_posts'}{/if} - {$aTBPackage.expire_text} 
		</label><br />
		{foreachelse}
			{phrase var='jobposting.no_package_found'}
		{/foreach}
	</div>
</div>

{if $bCanFeature}
<div class="table_right">
	<label><input type="checkbox" name="feature" value="1"/> {phrase var='jobposting.feature_this_job_with' featurefee=$featurefee}</label>
</div>
{/if}

<div class="table_clear" style="padding-top: 20px;">
	<input type="button" class="button" value="Publish" onclick="publishJob();" id="js_job_publish_btn" /><span id="js_job_publish_loading"></span>
</div>
