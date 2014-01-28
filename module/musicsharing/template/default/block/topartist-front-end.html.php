{literal}
<style type="text/css">
	.top_artist .artist_img 
	{
		background: url("{/literal}{$core_path}{literal}/module/musicsharing/static/image/m_size_avatar.png") no-repeat scroll 0 0 transparent;
	}
</style>
{/literal}                          
     <div  class="artist_list">
              {foreach from=$top_artists  item=aArtist}
                {template file='musicsharing.block.artist_info'}
             {/foreach}
              <div style="clear: both;"></div>
              {if $total_artists gt 6}
                  <div style="float:right;">
                    <a style="margin-right:10px" href="{url link = 'musicsharing.artist'}">{phrase var='musicsharing.view_more'} &#187;</a>
                </div>
              {/if}
        <div style="clear: both;"></div>
    </div>
                                       
               