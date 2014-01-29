<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 *
 * @copyright      YouNet Company
 * @author         VuDP, TienNPL
 * @package        Module_Resume
 * @version        3.01
 * 
 */
?>
{literal}
<style type="text/css">

</style>
{/literal}
<!-- Insert layout from here -->
<div class="resume_item">
	<!-- Resume thumbnail image -->
	<div class="resume_item_left">
		<a href="{permalink module='resume.view' id=$aResume.resume_id title=$aResume.headline}">
			{if $aResume.image_path!=""}
				{img server_id=$aResume.server_id path='core.url_pic' file='resume/'.$aResume.image_path suffix='_120' max_width='120' max_height='120'}
			{else}
				<img class="default_resume_image" src="{$sCorePath}module/resume/static/image/profile.png" style="max-width:120px;max-height:120px;"/>
			{/if}
		</a>	
	</div>
	<!-- Resume content summary -->
	<div class="resume_item_right">
		<!-- title -->
		<h4>
			<a href="{permalink module='resume.view' id=$aResume.resume_id title=$aResume.headline}">
				<strong>
					{if $aResume.full_name}
						{$aResume.full_name|shorten:50:"...":false}
					{else}
						{phrase var="resume.resume_headline"}
					{/if}
				</strong>
			</a>
			{if $aResume.is_viewed and $aResume.time_update > $aResume.time_view}
				<a href="javascript:void(0);" class="yns-item yns-reload" title="{phrase var='resume.updated'}">{phrase var="resume.updated"}</a>
			{else}
				{if $aResume.is_viewed==1}
				<a href="javascript:void(0);" class="yns-item yns-search" title="{phrase var='resume.viewed'}">{phrase var="resume.viewed"}</a>
				{/if}
			{/if}
			{if $aResume.sent_messages > 0 }
				<a href="javascript:void(0);" class="yns-item yns-mail" title="{phrase var='resume.contacted'}">{phrase var="resume.contacted"}</a>
			{/if}
			{if $aResume.noted != "" }
				{literal}
					<script style="text/javascript">
                                           
					
							$Behavior.loadNote{/literal}{$aResume.resume_id}{literal} = function(){
							var abc="{/literal}<?php echo str_replace(array("\n", "\r",'"'),array(" ", " ",'\"'), $this->_aVars['aResume']['noted']); ?>{literal}";
							$('#note_{/literal}{$aResume.resume_id}{literal}').aToolTip({
								clickIt: true,	
								tipContent: abc
							});
							};
						
                                          
					</script>
				{/literal}
				<a href="javascript:void(0);" class="yns-item yns-note" id="note_{$aResume.resume_id}">{phrase var="resume.note"}</a>
			{/if}
		</h4>
		<h4>
			<strong style="color:black;">{$aResume.headline}</strong>
		</h4>
		<!-- creation/updated date - views - favorites -->
		<div class="yns-res-info">
			<p>{phrase var ="resume.updated"}: {$aResume.time_update|date:'core.global_update_time'} - {$aResume.total_favorite} {phrase var="resume.favorites"}</p>
		</div>
		<!-- Categories -->
		<div class="yns-res-info">
			<p>{phrase var ="resume.categories"}: 
				{if $aResume.categories}
					{$aResume.categories}
				{else}
					{phrase var="resume.not_selected"}
				{/if}
			</p>
		</div>
		<!-- Note Information -->
		{if $sView == "noted"}
		<div class="yns-res-info" style="text-align: justify;">
			<div>
				<strong style="font-size: 12px;">
					{phrase var="resume.note"}:
				</strong>
				<a style="float:right;" href="javascript:void(0);" onclick="$Core.box('resume.editNote',500,'resume_id={$aResume.resume_id}&user_id={$aResume.user_id}')">{phrase var="resume.edit"}</a>
			</div>
			<div class="yns-note-content">
				{$aResume.note|split:30}
			</div>	
		</div>
		{/if}
		<a class="yns-viewall" href="{permalink module='resume.view' id=$aResume.resume_id title=$aResume.headline}">{phrase var="resume.view_detail"}</a>
	</div>
	<div class="clear"></div>
</div>

