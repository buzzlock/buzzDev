<?php
defined('PHPFOX') or exit('NO DICE!');
?>
{if isset($mexpect)}
{literal}
<script language="javascript" type="text/javascript">
$Behavior.MusicSharingMExpect = function() {
	$(document).ready(function(){
		$(".sub_section_menu").first().find(".active").removeClass("active");
	});
}
</script>
{/literal}
{/if}