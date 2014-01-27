<div>
<ul class="yc_view_participant">
	{template file="jobposting.block.company.mini_participant_company"}
	<span id="view_more_employee"></span>
</ul>
</div>
<div style="clear: both;"></div>
{if $ViewMore}
	<div id="href_view_more_employee">
		<a href="#" onclick="$.ajaxCall('jobposting.view_more_employee','iPage={$iPage}&company_id={$aCompany.company_id}');return false;">{phrase var='jobposting.view_more'}</a>
	</div>
{/if}
{if count($aParticipant)==0}
	{phrase var='jobposting.no_employees_found'}.
{/if}
