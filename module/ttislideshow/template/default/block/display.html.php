<?php
/*
 * Teamwurkz Technologies Inc.
 * package tti_components
 */

defined('PHPFOX') or exit('NO DICE!');

?>
{$sStyle}

{if count($aSlides)}
<input type="hidden" id="slideTotal" value="{$iTotal}" >

<div class="slideholder">
	
	<div class="slidepanel">
	{foreach from=$aSlides name=iSlide item=aSlide}
		<div id="ttislide{$phpfox.iteration.iSlide}" style="display:none;position:absolute;">
			
			<div id="ttislidedesc{$phpfox.iteration.iSlide}" class="slidedesc" style="top:350px;">						
			<div style="padding-left:5px;font-size:18pt;font-weight:bold;">{$aSlide.title}</div>
			<div style="padding-left:5px;font-size:9pt;">{$aSlide.description}</div>	
			</div>
				<a href="{$aSlide.title_link}">
					{img server_id=$aSlide.server_id title=$aSlide.title path='ttislideshow.url_image' file=$aSlide.image_path suffix='_980' max_height='980' max_height='980' id='simg'$phpfox.iteration.iSlide}
				</a>
			
		</div>
	{/foreach}
	</div>

</div>

{/if}