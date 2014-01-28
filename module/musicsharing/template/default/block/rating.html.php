<?php 
defined('PHPFOX') or exit('NO DICE!'); 
?>
<!-- Start rating -->
<div id="js_rating_holder_musicsharing">
    <form class="rating_form" method="post" action="#">

        <input type="hidden" id="user_id" name="user_id" value="{$user_id}" />
        <input type="hidden" id="song_id" name="song_id" value="{$music_info.song_id}" />

        <div>
            <div>
                {foreach from=$arStars key=sKey item=sPhrase}		
                    <input type="radio" class="js_rating_star" id="js_rating_star_{$sKey}" name="rating[star]" value="{$sKey}|{$sPhrase}" title="{$sKey}{if $sPhrase != $sKey} ({$sPhrase}){/if}"{if $default_rating >= $sKey} checked="checked"{/if} />
                {/foreach}

                <div class="clear"></div>
            </div>
        </div>
    </form>
</div>
<!-- End rating -->