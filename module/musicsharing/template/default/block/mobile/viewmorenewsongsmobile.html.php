{if count($top_songs)}
<table cellpading="0" cellspacing="0" border="0" width="100%">
    {foreach from=$top_songs  item=iSong}
       {template file='musicsharing.block.song_info_mobile_home'}
    {/foreach}
</table>
{/if}