<?php
/**
 * [PHPFOX_HEADER]
 *
 * @copyright		YouNet Company
 * @author  		MinhNTK
 * @package  		Module_PageContacts
 * @version 		3.01
 */
defined('PHPFOX') or exit('NO DICE!');

?>
{literal}
	<style>
		.yn_pagecontact_topic
		{
			float:left;
		}
		.yn_pagecontact_email
		{
			float:right;
			margin-right:150px;
		}
		
		.yn_pagecontact_table #removeQuestion
		{
			position:absolute;
			right:0;
		}
		.yn_pagecontact_table
		{
			position:relative;
			width:660px;
		}
	</style>
{/literal}
<div class="table full_question_holder row1 yn_pagecontact_table">
	<div class="yn_pagecontact_topic">
		<div class="table_left question_number_title">
				{if isset($phpfox.iteration.topic) && $phpfox.iteration.topic <= 1}
					{required}
				{/if}
				{phrase var='pagecontacts.topic_name'}
		</div>
		<div class="table_right" style="float:left;">	
				<input type="text" class="topic_title" name="val[q][{if isset($Topic.topic_id)}{$Topic.topic_id}{elseif isset($phpfox.iteration.topic)}{$phpfox.iteration.topic}{else}0{/if}][question]" value="{if isset($Topic.topic)}{$Topic.topic}{/if}" maxlength="255" size="30" />	
		</div>
		<div class="clear"></div>
	</div>
	<div class="yn_pagecontact_email">
		<div class="table_left">
			{phrase var='pagecontacts.email'}: 
		</div>
		<div class="table_right" >
			<input type="text" class="email" name="val[q][{if isset($Topic.topic_id)}{$Topic.topic_id}{elseif isset($phpfox.iteration.topic)}{$phpfox.iteration.topic}{else}0{/if}][email]" value="{if isset($Topic.email)}{$Topic.email}{/if}" maxlength="255" size="30" />
		</div>	
		<div class="clear"></div>
	</div>
	{if (isset($phpfox.iteration.topic) && $phpfox.iteration.topic <= 1) ||
	(isset($Topic.iQuestionIndex) && $Topic.iQuestionIndex <= 1) ||
	(!isset($phpfox.iteration.topic) && !isset($Topic.iQuestionIndex))}
		<div id="removeQuestion" style="display:none;">
	{else}
		<div id="removeQuestion" >
	{/if}
		<a href="#" onclick="return $Core.pagecontacts.removeQuestion(this);">{img theme='misc/delete.png' alt=''}</a>			
		
	</div>
	<div class="clear"></div>
</div>