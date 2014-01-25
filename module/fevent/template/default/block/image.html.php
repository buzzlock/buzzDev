<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');
/**
 * 
 * 
 * @copyright       [YOUNET_COPYRIGHT]
 * @author          YouNet Company
 * @package         YouNet_Event
 */
?>

{if count($aImages) > 1}
<div class="js_box_thumbs_holder2">
{/if}
    <div class="fevent_image_holder">
        <div class="fevent_image">
            <a class="js_fevent_click_image no_ajax_link" href="{img return_url=true server_id=$aEvent.server_id title=$aEvent.title path='event.url_image' file=$aEvent.image_path suffix=''}">
            {img thickbox=true server_id=$aEvent.server_id title=$aEvent.title path='event.url_image' file=$aEvent.image_path suffix='_200' max_width='200' max_height='200'}</a>
        </div>
        {if count($aImages) > 1}
        <div class="fevent_image_extra js_box_image_holder_thumbs">
            <ul>{foreach from=$aImages name=images item=aImage}<li>{img thickbox=true server_id=$aImage.server_id title=$aEvent.title path='event.url_image' file=$aImage.image_path suffix='' width='50' height='50'}</li>{/foreach}</ul>
            <div class="clear"></div>
        </div>
        {/if}
    </div>
{if count($aImages) > 1}
</div>
{/if}