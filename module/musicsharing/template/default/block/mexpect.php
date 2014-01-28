<?php
defined('PHPFOX') or exit('NO DICE!');
?>
{if isset($mexpect)}
<script language="javascript" type="text/javascript">
    $Behavior.MusicSharingMExpect2 = function() {
		$(".sub_section_menu").find(".active").removeClass("active");
		$(".sub_section_menu").find(":contains(My Albums)").parent().addClass("active");   
    }
</script>
{/if}