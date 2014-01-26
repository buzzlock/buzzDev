<ul class="clear-float advancedphoto-topmember">
	{foreach from=$aUsers item=aUser}
	<li class="ynadvphoto_top_member_list">
		<div class="block_listing_image">
			{img user=$aUser suffix='_50_square' max_width=50 max_height=50}
		</div>
		<div class="block_listing_title" style="padding-left:4px;">
			{$aUser|user:'':'':'':12:true}
		</div>
		<div class="clear"></div>
	</li>
	{/foreach}
</ul>