<div class="socialmediaimporter_provider">
	<a href="javascript:void(openauthsocialmediaimporter('{$sConnectUrl}');">
		<img class="icon_{$aProvider.name}" src="{$sCoreUrl}module/socialmediaimporter/static/image/{$aProvider.name}.jpg" alt="{$aProvider.title}" class="socialmediaimporter_provider_img"/>
	</a>
	<div class="text">
		<div class="socialmediaimporter_connect_link" id="socialmediaimporter_connect_link_{$aProvider.name}">
			<a href="javascript:void(openauthsocialmediaimporter('{$sConnectUrl}'));">{phrase var='socialmediaimporter.click_here'}</a> {phrase var='socialmediaimporter.to'} {phrase var='socialmediaimporter.connect'}.
		</div>
	</div>
</div>