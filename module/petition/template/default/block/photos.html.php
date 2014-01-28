<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

?>
<h3>{phrase var='petition.photos'}</h3>
{foreach from=$aImages name=images item=aImage}
<div id="js_photo_holder_{$aImage.image_id}" class="js_mp_photo go_left{if $aForms.image_path == $aImage.image_path} row_focus{/if}" style="text-align:center; margin-bottom:10px; margin-right:2px; padding:10px; border: 1px #CCC solid; margin-right: 10px;">
    <div class="js_mp_fix_holder" style="width:120px; margin:auto; position:relative;">
        <div style="position:absolute; right:0; margin:-2px -2px 0px 0px;">
            <a href="#" title="{phrase var='petition.delete_this_image'}" onclick="if (confirm('{phrase var='petition.are_you_sure' phpfox_squote=true}')) {literal}{{/literal} $('#js_photo_holder_{$aImage.image_id}').remove(); $.ajaxCall('petition.deleteImage', 'id={$aImage.image_id}'); $('#js_mp_image_{$aImage.image_id}').remove(); {literal}}{/literal} return false;">{img theme='misc/delete_hover.gif' alt=''}</a>
        </div>
        <a href="#" title="{phrase var='petition.click_to_set_as_default_image'}" onclick="$('.js_mp_photo').removeClass('row_focus'); $(this).parents('.js_mp_photo:first').addClass('row_focus'); $.ajaxCall('petition.setDefault', 'id={$aImage.image_id}'); return false;">
        {img server_id=$aImage.server_id path='core.url_pic' file=$aImage.image_path suffix='_120' max_width='120' max_height='120' class='js_mp_fix_width'}
        </a>
    </div>
</div>
{if is_int($phpfox.iteration.images/4)}
    <div class="clear"></div>
{/if}
{/foreach}
<div class="clear"></div>
<div class="extra_info">
    {phrase var='petition.click_on_image_to_set_it_as_default'}
</div>