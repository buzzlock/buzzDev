<?php


defined('PHPFOX') or exit('NO DICE!');

?>
<div class="user_browse_image">
    <div class="quickFlipPanel">
        {img user=$view_path suffix='_120' max_width=100 max_height=100}
    </div>
    <a style="font-weight:bold;" href="{url link=$view_path.user_name}">
        {$view_path.full_name|clean|shorten:100:'...'}
    </a>
</div>
{template file='userconnect.block.minimenu'}
