
<div class="table_header">
	{phrase var='jobposting.manage_packages'}
</div>
<table align='center'>
		<tr>
			
			<th class="table_row_header"></th>
			<th>{phrase var='jobposting.package_name'}</th>
			<th>{phrase var='jobposting.post_job_number_admincp'}</th>
			<th class="table_row_header">{phrase var='jobposting.valid_period'}</th>
			<th class="table_row_header">{phrase var='jobposting.package_fee'}</th>
			<th class="table_row_header">{phrase var='jobposting.action'}</th>

		</tr>
		
		{foreach from=$aPackages key=iKey item=aPackage}
		<tr id="jobposting_{$aPackage.package_id}" class="jobposting_row {if $iKey%2 == 0 } jobposting_row_even_background{else} jobposting_row_odd_background{/if}">
				<td class="t_center">
					<a href="#" class="js_drop_down_link" title="Options">{img theme='misc/bullet_arrow_down.png' alt=''}</a>
					<div class="link_menu">
						<ul>
							<li><a href="{url link='admincp.jobposting.package.add'}id_{$aPackage.package_id}/">{phrase var='jobposting.edit'}</a></li>		
							<li><a href="javascript:void(0);" onclick="$.ajaxCall('jobposting.deletepackage','id={$aPackage.package_id}')">{phrase var='jobposting.delete'}</a></li>					
						</ul>
					</div>		
				</td>

				<td>
					<a href="#">
						{$aPackage.name}
					</a>
				</td>

				<td>
					{$aPackage.post_number}
				</td> 
	
				<td class="table_row_column">
					{if $aPackage.expire_type!=0}{$aPackage.expire_number}{/if} {if $aPackage.expire_type==1}{phrase var='jobposting.day_s'}{elseif $aPackage.expire_type==2}{phrase var='jobposting.week_s'}{elseif $aPackage.expire_type==3}{phrase var='jobposting.month_s'}{else}{phrase var='jobposting.never_expired'}{/if}
				</td> 
		
				<td class="table_row_column">
					{$aPackage.fee}
				</td>
		
				<td class="table_row_column">
					
					<span id="showpackage_{$aPackage.package_id}" {if $aPackage.active == 0}style="display:none;"{/if}>
						<a href="#" onclick="$.ajaxCall('jobposting.activepackage','active=0&id={$aPackage.package_id}');return false;">{phrase var='jobposting.show'}</a>
					</span>
					<span id="hidepackage_{$aPackage.package_id}" {if $aPackage.active == 1}style="display:none;"{/if}>
						<a href="#" onclick="$.ajaxCall('jobposting.activepackage','active=1&id={$aPackage.package_id}');return false;">{phrase var='jobposting.hide'}</a>
					</span>	
				</td> 
	
			</tr>
		{/foreach}
	</table>
	
	{pager}
</form>
