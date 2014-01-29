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

{literal}
<script type="text/javascript">
     $Behavior.loadSectionMenuResume = function(){
	$(function(){
		$("div.sub_section_menu ul li").removeClass('active');
	});
        }
</script>

<style>
	#right{
	display:none;
	}
	.content3{
		width: 100% !important;
	}
	#content_holder{
		overflow:hidden;
	}
</style>
{/literal}

{if $bCanView}
	{module name="resume.basic"}
	{module name="resume.experience"}
	{module name="resume.skill"}
	{module name="resume.education"}
	{module name="resume.certification"}
	{module name="resume.language"}
	{module name="resume.publication"}
	{module name="resume.addition"}
{/if}
