<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * 
 * 
 * @copyright       [YOUNET_COPYRIGHT]
 * @author          AnNT
 * @package         Module_jobposting
 */
?>
<h3>{phrase var='jobposting.photos'}</h3>

{foreach from=$aImages name=images item=aImage}
<div id="js_photo_holder_{$aImage.image_id}" class="js_mp_photo go_left{if isset($aForms.image_path) && $aForms.image_path == $aImage.image_path} row_focus{/if}" style="text-align:center; margin-bottom:10px; margin-right:2px; padding:10px; border: 1px #CCC solid; margin-right: 10px;">
    <div class="js_mp_fix_holder" style="width:120px; margin:auto; position:relative;">
        <div style="position:absolute; right:0; margin:-2px -2px 0px 0px;">
            <a href="#" title="{phrase var='jobposting.delete_this_image'}" onclick="if (confirm('{phrase var='jobposting.are_you_sure' phpfox_squote=true}')) {l} $('#js_photo_holder_{$aImage.image_id}').remove(); $.ajaxCall('jobposting.deleteImage', 'id={$aImage.image_id}'); $('#js_mp_image_{$aImage.image_id}').remove(); {r} return false;">{img theme='misc/delete_hover.gif' alt=''}</a>
        </div>
        <a href="#" title="{phrase var='jobposting.click_to_set_as_default_image'}" onclick="$('.js_mp_photo').removeClass('row_focus'); $(this).parents('.js_mp_photo:first').addClass('row_focus'); $.ajaxCall('jobposting.setDefaultImage', 'id={$aImage.image_id}'); return false;">
        {$aImage.image}
        </a>
    </div>
</div>
{if is_int($phpfox.iteration.images/4)}
    <div class="clear"></div>
{/if}
{/foreach}
<div class="clear"></div>