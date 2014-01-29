<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 *
 * @copyright      YouNet Company
 * @author         VuDP, TienNPL, TrucPTM
 * @package        Module_Resume
 * @version        3.01
 * 
 */?>
<!-- Basic information layout here -->
{if !$aOptions.no_resume }
<h1 class="yns-basic-header">
		<a title="{$aResume.headline|clean|split:50}" href="{permalink module='resume.view' id=$aResume.resume_id title=$aResume.headline}">
			{$aResume.headline|clean|shorten:35:'...'|split:50}
		</a>
	<div>
		{if $aOptions.can_favorite}
			<a class="yns-item yns-fav" href="javascript:void(0);" onclick="FavoriteAction('favorite',{$aResume.resume_id});return false;" id="js_favorite_link_like_{$aResume.resume_id}" {if $aOptions.favorited }style="display:none;"{/if}>{phrase var='resume.favorite'}</a>
			<a class="yns-item yns-un-fav" href="javascript:void(0);" onclick="FavoriteAction('unfavorite',{$aResume.resume_id});return false;" id="js_favorite_link_unlike_{$aResume.resume_id}" {if !$aOptions.favorited }style="display:none;"{/if}>{phrase var='resume.unfavorite'}</a>
		{/if}
		{if $aOptions.can_note}
			<a class="yns-item yns-note" href="javascript:void(0);" onclick="NoteAction('note',{$aResume.resume_id});return false;" id="js_favorite_link_note_{$aResume.resume_id}" {if $aOptions.noted} style="display:none;"{/if}>{phrase var='resume.note'}</a>
			<a class="yns-item yns-note" href="javascript:void(0);" onclick="NoteAction('unnote',{$aResume.resume_id});return false;" id="js_favorite_link_unnote_{$aResume.resume_id}" {if !$aOptions.noted} style="display:none;"{/if}>{phrase var='resume.unnote'}</a>
		{/if}
		{if $aOptions.can_send_message}
			<a class="yns-item yns-mail" href="javascript:void(0);" onclick="$Core.box('resume.sendMessagePupUp',400,'user_id={$aResume.user_id}&resume_id={$aResume.resume_id}&type=2');">{phrase var='resume.send_message'}</a>
		{/if}
                {if $aOptions.can_export_resume}
                    <a class="yns-item-pdf" href="{$linkdownloadpdf}">{phrase var='resume.pdf_printer'}</a>
                {/if}
	</div>
</h1>
<div class="yns resume_basic {if !$aOptions.can_edit}no-res-complete{/if}">
	{if $aOptions.noted}
	<div class="basic_note" id="note_resume_{$aResume.resume_id}">
		<span class="extra_info">(*) {phrase var="resume.note"}: {$aOptions.noted}</span>
	</div>
	<div class="clear"></div>
	{/if}
	<div class="basic_info_content">
		<div class="yns-bg">
			<!-- resume image -->
			<div class="resume_image">
				{if $aResume.image_path!=""}
					{img server_id=$aResume.server_id path='core.url_pic' file='resume/'.$aResume.image_path suffix='_120' max_width='120' max_height='120'}
					{if $aOptions.can_edit }
						<p><b><a href="{url link='resume.add.id_'.$aResume.resume_id}">{phrase var='resume.edit_photo'}</a></b></p>
					{/if}
				{else}
					<img class="default_resume_image" src="{$sCorePath}module/resume/static/image/profile.png" style="max-width:120px;max-height:120px;"/>
					{if $aOptions.can_edit }
						<p><b><a href="{url link='resume.add.id_'.$aResume.resume_id}">+ {phrase var='resume.add_a_photo'}</a></b></p>
					{/if}
				{/if}
			</div>
			<div class="basic_info">
				<div>
				<!-- full name - birthday - gender - marital status -->
					<p>
						<span class="name">{$aResume.full_name}</span>
						{if $aOptions.can_edit}
						<a href="{url link='resume.add.id_'.$aResume.resume_id}" class="edit">{phrase var="resume.edit"}</a>
						{/if}
					</p>
					<p class="extra_info">
						{if !empty($aResume.birthday_parsed) && $aResume.display_date_of_birth}
						  {$aResume.birthday_parsed}
						{/if}
						{if !empty($aResume.gender_parsed) && $aResume.display_gender}
						  {if !empty($aResume.birthday_parsed)} | {/if}
						  {$aResume.gender_parsed}
						{/if}
						{if !empty($aResume.marital_status) && $aResume.display_marital_status}
						  {if !empty($aResume.birthday_parsed) or !empty($aResume.gender_parsed)}	| {/if}
						  {phrase var="resume.".$aResume.marital_status}
						{/if}
					</p>
					<!-- Current position -->			
					<p>
					{if $aCurrentWork}
						{$aCurrentWork.title} {phrase var="resume.at"} {$aCurrentWork.company_name}
					{elseif $aOptions.can_edit}
						<a href="{url link='resume.experience.id_'$aResume.resume_id}" class="add"><b>+ {phrase var='resume.add_current_work'}</b></a>
					{/if}
					</p>
					<!-- Country + City -->
					<p class="extra_info">
						{$aResume.country_iso|location}
						{if !empty($aResume.location_child_id) }, {$aResume.country_child_id|location_child}{/if}
						{if !empty($aResume.city) } 
							{if !empty($aResume.country_iso) } > {/if} 
							{$aResume.city} 
                        {/if}
					</p>
				</div>
				<div class="person-info">
					{ if count($aCats) > 0 or $aOptions.can_edit }
					<div class="info">
					<!-- Category list -->
						<div class="info_left">{phrase var='resume.categories'}:</div>
						<div class="info_right">
							{if count($aCats) > 0}
								{foreach from = $aCats key = iKey item = aCat}
									{if $iKey == 0}  
										<a href="{permalink module='resume.category' id=$aCat.category_id title=$aCat.name_url}">{$aCat.name}</a>
									{else}
										| <a href="{permalink module='resume.category' id=$aCat.category_id title=$aCat.name_url}">{$aCat.name}</a>
									{/if}
									
								{/foreach}
							{else}
								<a href="{url link='resume.summary.id_'$aResume.resume_id}" class="add"><b>+ {phrase var='resume.add'}</b></a>
							{/if}
						</div>
					</div>
					{/if}
					<!-- Year of experience -->
					{if $aResume.year_exp != "0" or $aOptions.can_edit }
					<div class="info">
						<div class="info_left">{phrase var="resume.years_of_experience"}:</div>
						<div class="info_right">
							{if $aResume.year_exp > 1 }
								{$aResume.year_exp} {phrase var="resume.years"}
							{elseif $aResume.year_exp == 1}
								{$aResume.year_exp} {phrase var="resume.lowercase_year"}
							{else} 
								<a href="{url link='resume.summary.id_'$aResume.resume_id}" class="add"><b>+ {phrase var='resume.add'}</b></a>
							{/if}
						</div>
					</div>
					{/if}
					<!-- Highest level -->	
					{if $aResume.level_id > 0 or $aOptions.can_edit}
					<div class="info">
						<div class="info_left">{phrase var="resume.highest_level"}: </div>
						<div class="info_right">
							{if $aResume.level_id > 0 }
								{$aResume.level_name}
							{else}
								<a href="{url link='resume.summary.id_'$aResume.resume_id}" class="add"><b>+ {phrase var='resume.add'}</b></a>
							{/if}
						</div>
					</div>
					{/if}
					<!-- Education -->
					{if $aLatestEducation or $aOptions.can_edit}	
					<div class="info">
						<div class="info_left">{phrase var='resume.education'}: </div>
						<div class="info_right">
							{if $aLatestEducation}
								{$aLatestEducation.degree}, {$aLatestEducation.field} {phrase var="resume.at"} {$aLatestEducation.school_name}
							{else}
								<a href="{url link='resume.education.id_'$aResume.resume_id}" class="add"><b>+ {phrase var='resume.add'}</b></a>
							{/if}
						</div>
					</div>
					{/if}
					<!-- Authorized to work on -->
					{if $aResume.authorized}
                        <div class="info">
                            <div style="margin-bottom: 5px;">
                                <strong>{phrase var="resume.authorized_to_work_in"}</strong>
                            </div>
                        </div>

                        {foreach from=$aResume.authorized key=i item=aItem}
                            <div class="info">
                                {if $aItem.label_country_iso}
                                    <div class="info_left">{phrase var='resume.country'}: </div>
                                    <div class="info_right">
                                        {$aItem.country_iso|location}
                                        {if !empty($aItem.country_child) }, {$aItem.country_child|location_child}{/if}
                                    </div>
                                {/if}
                                {if $aItem.location}
                                    <div class="info_left">{phrase var='resume.location'}: </div>
                                    <div class="info_right">
                                        {$aItem.location}
                                    </div>
                                {/if}
                                {if $aItem.other_level }
                                    <div class="info_left">{phrase var='resume.position'}: </div>
                                    <div class="info_right">
                                        {$aItem.other_level}
                                    </div>
                                {elseif $aItem.level_id >0 }
                                    <div class="info_left">{phrase var='resume.position'}: </div>
                                    <div class="info_right">
                                        {$aItem.label_level_id}
                                    </div>	
                                {/if}
                            </div>
                        {/foreach}
					{/if}
					
					{if $turnonFields}
					<div class="info">
						<div style="margin-bottom: 5px;">
							<strong>{phrase var='resume.custom_fields'}</strong>
						</div>
						<div>
							{foreach from = $aViewCustomFields item=aCustomField}
							{if $aCustomField.value!=""}
								<div class="info_left"> {phrase var=$aCustomField.phrase_var_name}: </div>
								<div class="info_right">
									{$aCustomField.value}
								</div>
							{/if}
							{/foreach}
						</div>
					</div>
					{/if}
				</div>
			</div>
		</div>
		{if $aOptions.can_edit}
			<div class="res-complete">
				<div class="res-percent">
					<span>{$percentfinish}%</span>{phrase var='resume.of_resume_complete'}
				</div>
				<div class="meter-wrap-l">
					<div class="meter-wrap-r">
						<div class="meter-wrap">
							<div class="meter-value" style="width: {$percentfinish}%">
								{$percentfinish}%
							</div>
						</div>
					</div>
	            </div>
	            {if $percentfinish!=100}
				<div class="res-tip">
					<p class="tip-title">{phrase var='resume.resume_completion_tips'}</p>
					{foreach from=$aUncomplete item=Uncomplete}
						{$Uncomplete}	
					{/foreach}
				</div>
				{/if}
			</div>
		{/if}
	</div>
	<div class="clear"></div>
	{if $aOptions.can_edit }
	<!-- <div class="yns contact-info new-section extra_info">
		{phrase var='resume.add_sections_to_refelct_archivement_and_experience_on_your_profile'}
		<a href="#" class="add-new"><b>+ {phrase var='resume.add_section'}</b></a>
	</div> -->
	{/if}
	
	<!-- Contact Information -->
	<div class="yns contact-info extra_info">
			<h3>
				{phrase var="resume.contact_info"}
				{ if $aOptions.can_edit }
					<a href="{url link='resume.add.id_'.$aResume.resume_id}" class="add-new">{phrase var="resume.edit"}</a>
				{/if}
			</h3>
			<!-- Phone -->
			{if !empty($aResume.phone)}
			<div class="info">
				<div class="info_left">{phrase var="resume.phone_number"}:</div>
				<div class="info_right">
					{foreach from=$aResume.phone item=aPhone}
					<p>{$aPhone.text} ({phrase var="resume.".$aPhone.type})</p>
					{/foreach}
				</div>
			</div>
			{/if}
			<!-- IM -->
			{if !empty($aResume.imessage)}
			<div class="info">
				<div class="info_left">{phrase var="resume.im"}:</div>
				<div class="info_right">
					<p></p>
					{foreach from=$aResume.imessage item=aImessage}
					<p>{$aImessage.text} ({phrase var="resume.".$aImessage.type})</p>
					{/foreach}
				</div>
			</div>
			{/if}
			<!-- Email -->
			{if !empty($aResume.email)}
			<div class="info">
				<div class="info_left">{phrase var="resume.email"}:</div>
				<div class="info_right">
					<p></p>
					{foreach from=$aResume.email item=aEmail}
					<p>{$aEmail}</p>
					{/foreach}
				</div>
			</div>
			{/if}
	</div>
	<!-- Summary -->
	<div class="yns contact-info summary_info extra_info">
		{if $aOptions.can_edit or $aResume.summary_parsed}
		<h3>{phrase var="resume.summary"}
			{if $aOptions.can_edit}
			<a href="{url link='resume.summary.id_'.$aResume.resume_id}" class="add-new">{phrase var='resume.edit'}</a>
			{/if}
		</h3>
		<p>{$aResume.summary_parsed}</p>
		{/if}
	</div>
</div>
{/if}
