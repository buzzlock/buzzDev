{foreach from=$aParticipant item=aParticipant}
<li class="ynjp_row_employee_loop">
	<div class="ynjp_employee_image">
		{img user=$aParticipant suffix='_50_square' max_width=50 max_height=50}
	</div>
	<div class="ynjp_row_employee_name">
		<a href="{url link=''}{$aParticipant.user_name}/">{$aParticipant.full_name}</a>
	</div>
</li>		
{/foreach}