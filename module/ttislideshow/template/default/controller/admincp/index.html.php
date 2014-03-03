<?php 

defined('PHPFOX') or exit('NO DICE!'); 

/**
 * author		Teamwurkz Technologies Inc.
 * package		tti_components
 */
 
?>
{if count($aSlides)}
<div class="table_header">
	Slides
</div>
<table cellpadding="0" cellspacing="0">
<tr>
	<th style="width:20px;"></th>
	<th style="width:300px;">Title</th>
	<th style="width:200px;">Link</th>
	<th style="width:200px;">Description</th>
	<th>Image</th>
</tr>
{foreach from=$aSlides key=iKey item=aSlide}
<tr class="checkRow{if is_int($iKey/2)} tr{else}{/if}">
	<td class="t_center">
		<a href="#" class="js_drop_down_link" title="manage rank">{img theme='misc/bullet_arrow_down.png' alt=''}</a>
		<div class="link_menu">
			<ul>
				<li><a href="{url link='admincp.ttislideshow.addslide' id={$aSlide.slide_id}">Edit Slide</a></li>		
				<li><a href="{url link='admincp.ttislideshow' delete={$aSlide.slide_id}" onclick="return confirm('Are your sure?');">Delete Slide</a></li>
			</ul>
		</div>		
	</td>	
	<td>{$aSlide.title}</td>
	<td>{$aSlide.title_link}</td>
	<td>{$aSlide.description}</td>
	<td>
	{img server_id=$aSlide.server_id title=$aSlide.title path='ttislideshow.url_image' file=$aSlide.image_path suffix='_120' max_width='100' max_height='100'}
	</td>
</tr>
{/foreach}
</table>
{else}
<div class="extra_info">
	No slide has been added yet.
	<ul class="action">
		<li><a href="{url link='admincp.ttislideshow.addslide'}">Add New Slide</a></li>
	</ul>
</div>
{/if}