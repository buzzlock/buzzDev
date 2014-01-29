<div class="sub_section_menu">
<ul>
	<li {if $sService==''}class="active"{/if}><a href="{url link='socialmediaimporter'}">{phrase var='socialmediaimporter.social_media_connect'}</a></li>
	<li {if $sService=='facebook'}class="active"{/if}><a href="{url link='socialmediaimporter.facebook'}">{phrase var='socialmediaimporter.import_from_facebook'}</a></li>
	<li {if $sService=='flickr'}class="active"{/if}><a href="{url link='socialmediaimporter.flickr'}">{phrase var='socialmediaimporter.import_from_flickr'}</a></li>
	<li {if $sService=='instagram'}class="active"{/if}><a href="{url link='socialmediaimporter.instagram'}">{phrase var='socialmediaimporter.import_from_instagram'}</a></li>
	<li {if $sService=='picasa'}class="active"{/if}><a href="{url link='socialmediaimporter.picasa'}">{phrase var='socialmediaimporter.import_from_picasa'}</a></li>
</ul>
</div>