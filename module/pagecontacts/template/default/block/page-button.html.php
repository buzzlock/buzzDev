<?php
/**
 * @copyright		YouNet Company
 * @author  		MinhNTK
 * @package  		Module_PageContact
 * @version 		3.01
 */
defined('PHPFOX') or exit('NO DICE!');

?>
{literal}
<script>
	function showContactPopup()
	{
		$Core.ajaxBox('pagecontacts.popup');
	}
</script>
<style>
	.yn_pagecontact_button
	{
		background: #6BBA70;
		color:white;
		font-size:14px;
		font-weight:bold;
		border:1px #508F54 solid;
		padding:5px 10px;
	}
</style>
{/literal}
<div style="text-align: center;padding: 5px 0px;">
	{if !$bIsSetting}
		<input type="button" value="{phrase var='pagecontacts.contact_us'}" class="yn_pagecontact_button" onclick="tb_show('{phrase var='pagecontacts.contact_us'}', $.ajaxBox('pagecontacts.popup', 'height=300&amp;width=550&amp;iPageId={$iPageId}')); return false;" />
	{else}
		<input type="button" value="{phrase var='pagecontacts.contact_us'}" class="yn_pagecontact_button" onclick="window.location.href ='{$sLink}'; return false;" />
	{/if}
	<div class="clear"></div>
</div>