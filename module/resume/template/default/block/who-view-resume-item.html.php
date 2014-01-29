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
				{if $aResume.user_image!=""}
					{img server_id=$aResume.server_id path='core.url_pic' file='user/'.$aResume.user_image suffix='_120' max_width='120' max_height='120'}
				{else}
				<img class="default_resume_image" src="{$sCorePath}module/resume/static/image/profile.png" style="max-width:120px;max-height:120px;"/>
				{/if}
			{/if}
		</a>	
	</div>
	<!-- Resume content summary -->
	<div class="resume_item_right">
		<!-- title -->
		<h4>
			<a href="{url link=''}{$aResume.user_name}">
				<strong>
					{if $aResume.full_name}
						{$aResume.full_name|shorten:50:"...":false}
					{else}
						{phrase var="resume.resume_headline"}
					{/if}
				</strong>
			</a>
		</h4>
		<h4>
			<strong style="color:black;">{$aResume.headline}</strong>
		</h4>
		<!-- creation/updated date - views - favorites -->
		<div class="yns-res-info">
			<p>{phrase var='resume.viewed'}: {$aResume.viewed_timestamp|date:'core.global_update_time'}</p>
		</div>		
		
		<a class="yns-viewall" href="#" onclick="$Core.box('resume.sendMessagePupUp',400,'user_id={$aResume.user_id}&resume_id={$aResume.resume_id}&type=1');return false;">{phrase var='resume.send_message'}</a>
	</div>
	<div class="clear"></div>
</div>
