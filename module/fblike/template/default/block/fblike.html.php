<?php 
defined('PHPFOX') or exit('NO DICE!'); 
?>

{if empty($sUrl) }
   <div class="error_message">{phrase var='fblike.must_set_the_facebook_page_url_in_admincp'}</div>
{else}
   <script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
   
   <fb:like-box class="fblike" data-href="{$sUrl}" colorscheme="{$sColor}" show_faces="{$sFace}" stream="{$sStream}" header="{$sShowHeader}" force_wall="{$sForceWall}" {if $iWidth > 0} width="{$iWidth}" {/if} {if $iHeight > 0} height="{$iHeight}" {/if}></fb:like-box>
   
   <script>
	   $Behavior.ynfblInit = function()
	   {l}
	   		setTimeout(function()
	   			{l}
				      var w = $('#js_block_border_fblike_fblike').width();
				      $('.fblike').attr('width',w + 'px');
					  $('.pluginSkinLight .pts.plm').css('font-size','10px');
					  $('.pluginSkinLight .pam').css('padding','6px 9px');
	   			{r}
   			,5000); 


	   {r}
   </script>   
{/if}
