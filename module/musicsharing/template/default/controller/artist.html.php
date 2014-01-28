<?php defined('PHPFOX') or exit('NO DICE!'); ?>
{if Phpfox::isMobile()}
    {literal}
        <style type="text/css">
            .mobile_section_menu{display: none !important;}
            #section_menu{display:none !important;}
        </style>
    {/literal}
{/if}

{template file='musicsharing.block.mexpect'}

<div class="artist-list {if phpfox::isMobile()}artist-mobile{/if}">
    {if count($list_info)>0}
        {foreach from=$list_info  item=aArtist}
            {template file='musicsharing.block.artistinfor100x100'}
        {/foreach}
    {else}
        <div align="left" class="red margin-right-10 margin-bottom-10 margin-top-10" style="">{phrase var='musicsharing.there_is_no_artist_yet'}</div> 
    {/if}
    <br clear="all" />
    <div class="paginator">{pager}</div>
</div>
