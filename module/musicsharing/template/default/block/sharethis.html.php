<?php

?>
<div class="addthis_toolbox addthis_default_style addthis_32x32_style "
    addthis:url="{*
		*}{if !isset($aParentModule)}{*
			*}{url link='musicsharing.listen.music_'.$music_info.song_id}{*
		*}{else}{*
			*}{url link=$aParentModule.module_id.".".$aParentModule.item_id.'.musicsharing.listen.music_'.$music_info.song_id}{*
		*}{/if}{*
	*}"
    >
    <a class="addthis_button_preferred_1"></a>
    <a class="addthis_button_preferred_2"></a>
    <a class="addthis_button_preferred_3"></a>
    <a class="addthis_button_preferred_4"></a>
    <a class="addthis_button_compact"></a>
</div>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4e7be7602a83d379"></script>
{if PHPFOX_IS_AJAX || PHPFOX_IS_AJAX_PAGE}
	{literal}
	<script>
		try {
			$Behavior.init = function() {
				$.getScript('http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4e7be7602a83d379&domready=1', function() {
					addthis.init();
					addthis.toolbox(".addthis_toolbox");
				});
			}
		} catch(err) {
			log(err);
		}
	</script>
	{/literal}
{/if}