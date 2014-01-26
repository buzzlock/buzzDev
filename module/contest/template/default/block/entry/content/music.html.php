<div class="clear"></div>
<div class="younet_html5_player init not-unbind">
    <div class="yncontest-music">
        <audio class="yncontest-audio-skin" class="mejs" width="493" src="{$aMusicEntry.song_path}" type="audio/mp3" controls="controls" autoplay="true" preload="none"></audio>
    </div>
</div>

{if $bIsPreview}
<script type="text/javascript">
YNCONTEST_CONTROLLER_PLAYER.initialize();
</script>
{/if}
