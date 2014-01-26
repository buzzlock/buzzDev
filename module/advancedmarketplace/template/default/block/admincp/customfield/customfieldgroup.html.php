<?php
/**
 * [PHPFOX_HEADER]
 *
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond_Benc
 * @package 		Phpfox
 * @version 		$Id: price.html.php 3533 2011-11-21 14:07:21Z Raymond_Benc $
 */
defined('PHPFOX') or exit('NO DICE!');
?>
<li class="customrow active pref_{$sKeyVar}">
	<div class="yn_jh_manidiv">
		<a href="#" title="{if $is_active.is_active == "1"}{phrase var='advancedmarketplace.switch_off'}{else}{phrase var='advancedmarketplace.switch_on'}{/if}" class="bullet {if $is_active.is_active == "1"}on{else}off{/if} onoffswitch" ref="{$sKeyVar}">&nbsp;</a>
		<a href="#" title="{phrase var='advancedmarketplace.move_up'}" class="btn up">&nbsp;</a>
		<a href="#" title="{phrase var='advancedmarketplace.move_down'}" class="btn down">&nbsp;</a>
		<a href="#" title="{phrase var='advancedmarketplace.edit'}" class="btn edit yn_jh_cusgroup_edit" ref="{$sKeyVar}">&nbsp;</a>
		<a href="#" title="{phrase var='advancedmarketplace.save'}" class="btn save yn_jh_cusgroup_save" ref="{$sKeyVar}">&nbsp;</a>
		<a href="#" title="{phrase var='advancedmarketplace.delete'}" class="btn delete yn_jh_cusgroup_delete" ref="{$sKeyVar}">&nbsp;</a>
		<img class="ajxloader" src="{$corepath}module/advancedmarketplace/static/image/default/ajxloader.gif" />
	</div>
	<div>
		<input {*disabled="disabled" *}type="text" class="value ref_{$sKeyVar}" name="customfieldgroup[{$sKeyVar}]" value="{$sText}" ref="{$sKeyVar}"/>
	</div>
</li>