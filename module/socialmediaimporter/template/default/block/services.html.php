<div id="social_media_connect">   
{foreach from=$aServices item=aService}   
	<div class="service">
		<ul style="float:left;">
			<li>
				<a href="{$aService.link_import}"><img src="{$sCoreUrl}module/socialmediaimporter/static/image/{$aService.name}_status_up.png" /></a>
			</li>
			<li style="line-height:50px;">
				<a href="{$aService.link_import}"><strong>{$aService.title}</strong></a>
			</li>
		</ul>
		
		{if isset($aService.agent) && $aService.agent}
		<ul style="float:right;">
			<li style="padding:10px;">				
				<table>
					<tr><td>{phrase var='socialmediaimporter.connected_as' full_name=''} {$aService.agent.full_name|clean|shorten:18...}</td></tr>
					<tr><td><a href="{$aService.link_disconnect}" onclick="return confirm('{phrase var='socialmediaimporter.are_you_sure_you_want_to_disconnect_this_account'}');">{phrase var='socialmediaimporter.click_here'}</a> {phrase var='socialmediaimporter.to'} {phrase var='socialmediaimporter.disconnect'}.</td></tr>
				</table>				
			</li>
			<li>
				<a href=""><img src="{$aService.agent.img_url}" alt="{$aService.agent.full_name}" align="left" height="32"/></a>
			</li>
		</ul>	
		{else}
		<ul style="float:right;">
			<li>
				<a href="javascript:void(openauthsocialmediaimporter('{$aService.link_connect}'));">{phrase var='socialmediaimporter.click_here'}</a> {phrase var='socialmediaimporter.to'} {phrase var='socialmediaimporter.connect'}.
			</li>
		</ul>
		{/if}		
	</div>	
{/foreach}
</div>