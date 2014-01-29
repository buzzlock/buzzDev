<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<input id="ucidcz" type="hidden" value="sze" />
{literal}
<style>
    .uscn_view_seach
    {
        float:right;
    }
	#js_userconnect_content .left {
		float: left;
		margin: 8px;
    }
</style>
<script type="text/javascript">
    $Behavior.userconnectViewInit = function() {
        addSearch();
        if($('link[href*="music.css"]').size() > 0 && !document.ucidcz) {
            document.ucidcz = $('link[href*="music.css"]');
        }
        if($("#ucidcz").size() > 0) {
            try{
                document.ucidcz.remove();
            }catch(ex)
            {
                            
            }
        } else {
            $("head").append(document.ucidcz);
        }
    }
</script>
{/literal}