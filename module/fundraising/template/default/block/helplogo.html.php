<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

?>
{literal}
<style type="text/css">
    .fundraising_large_image img{max-width: 96%;max-height: 300px;}
    .fundraising_small_image ul li{float:left;padding:3px;}
    .detail_link ul li{padding:4px;}
    .detail_link ul li a
    {
        background: url({/literal}{$corepath}{literal}module/fundraising/static/image/view_more.png) no-repeat; 
        padding-left: 14px;
        font-size: 14px;
    }
</style>
{/literal}

<div class="fundraising_large_image">
    <a class="js_fundraising_click_image no_ajax_link" href="{img return_url=true server_id=$aHelp.server_id title=$aHelp.title path='core.url_pic' file=$aHelp.image_path suffix='_200'}">
    {img thickbox=true server_id=$aHelp.server_id title=$aHelp.title path='core.url_pic' file=$aHelp.image_path suffix='_200'}</a>
</div>
