<?php
defined('PHPFOX') or exit('NO DICE!');

?>
{literal}
<style type="text/css">
#tb_msf_s tr td
{
    vertical-align: middle;
}
div#content div.menu ul li a, div#content div.menu ul li a:hover,
div#content div.menu li.active a, div#content div.menu li.active a:hover
{
    background-image: url({/literal}{$core_path}{literal}module/musicsharing/static/image/headphone.png);
     background-position: 12% 50%;
    background-repeat: no-repeat;
    font-size: 15px;
    font-weight: bold;
}
.item_song_info
{
   background: url({/literal}{$core_path}{literal}module/musicsharing/static/image/listen.jpg) no-repeat left; 
   border-bottom: 1px solid #ccc;
   padding: 10px 10px 10px 45px;
}
</style>
{/literal}
{literal}
<style type="text/css">
	a.add-icon {
		background-image: url("{/literal}{$core_path}{literal}module/musicsharing/static/image/music/buttons.png");
		display: block;
		height: 18px;
		width: 18px;
		text-indent: 999em;
		background-repeat: no-repeat;
		margin-right: 5px;
		background-position: -86px -38px;
		overflow: hidden;
	}
	a.add-icon:hover {
		background-position: -86px -67px;
	}
	a.download-icon {
		background-image: url("{/literal}{$core_path}{literal}module/musicsharing/static/image/music/buttons.png");
		display: block;
		height: 18px;
		width: 18px;
		text-indent: 999em;
		background-repeat: no-repeat;
		margin-right: 5px;
		background-position: -47px -38px;
		overflow: hidden;
	}
	a.download-icon:hover {
		background-position: -47px -67px;
	}
	a.addcart-icon {
		background-image: url("{/literal}{$core_path}{literal}module/musicsharing/static/image/music/buttons.png");
		display: block;
		height: 19px;
		width: 20px;
		text-indent: 999em;
		background-repeat: no-repeat;
		margin-right: 5px;
		display: none;
		background-position: -125px -38px;
		overflow: hidden;
	}
	a.addcart-icon:hover {
		background-position: -125px -67px;
	}
	a.remove-icon {
		background-image: url("{/literal}{$core_path}{literal}module/musicsharing/static/image/music/buttons.png");
		display: block;
		height: 19px;
		width: 20px;
		text-indent: 999em;
		background-repeat: no-repeat;
		margin-right: 5px;
		display: none;
		overflow: hidden;
		background-position: -7px -38px;
	}
	a.remove-icon:hover {
		background-position: -7px -67px;
	}
</style>
{/literal}
<!-- Top song -->
<div class="box_ys2" {if !phpfox::isMobile()}style="width: 525px;" {/if} id="song_list_frame">     
	<table cellpading="0" cellspacing="0" border="0" width="100%">
        {if phpfox::isMobile()}
            {foreach from=$top_songs  item=iSong}
                {template file='musicsharing.block.song_info_mobile_home'}
			{/foreach}
		{else}
            {foreach from=$top_songs  item=iSong}
                {template file='musicsharing.block.song_info_home'}
            {/foreach}
        {/if}
	</table>
</div>