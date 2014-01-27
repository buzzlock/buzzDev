{literal}
<style>
	.feed_sort_order{
		display: none !important;
	}
	#js_main_feed_holder{
		display: none;
	}
	#js_feed_content{
		display: none;
	}
	#js_block_border_feed_display .title{
		display: none;
	}
</style>
{/literal}

<h1 class="ynjp_jobDetail_title"><a href="{permalink module='jobposting.company' id=$aCompany.company_id title=$aCompany.name}">{$aCompany.name}</a></h1>
{phrase var='jobposting.created_by'} <a href="{url link=''}{$aCompany.user_name}">{$aCompany.full_name}</a>
<div class="item_view ynjp_jobDetail_container">
	{if $aCompany.action}
	<div class="item_bar" style="padding-top: 10px">
		<div class="item_bar_action_holder">
			<a href="#" class="item_bar_action"><span>{phrase var='jobposting.actions'}</span></a>     
			<ul>
			   {template file='jobposting.block.company.action-link'}
		   </ul>
		</div>
	</div>
	{/if}
	<div class="yns job_detail_information">
		<h4><span> {phrase var='jobposting.company_description'} </span></h4>
		<p>
			{$aCompany.description_parsed|parse}
		</p>
		<div id="tabs_view" class = "yc_view_tab ync_code_txt ynjp_tabInformation">	
			<ul class="ync_tabs">
				<li id="tabs1"><a href="#tabs-1">{phrase var='jobposting.general_information'}</a></li>
				{if Phpfox::getUserParam('jobposting.can_post_comment_on_company')}
				<li id="tabs2"><a href="#tabs-2">{phrase var='jobposting.update'}</a></li>
				{/if}
					<li id="tabs3"><a href="#tabs-3">{phrase var='jobposting.jobs'}</a></li>
				
				<li id="tabs4"><a href="#tabs-4"><span style="text-transform: capitalize;">{phrase var='jobposting.employees'}</span></a></li>
			</ul>
			<ul class="ync_tabs_content">
				<li id="tabs-1">
					<ul>
						<li>
							<span> {phrase var='jobposting.headquaters'}: </span> <span> {$aCompany.location} </span>
						</li>
						<li>
							<span> {phrase var='jobposting.website'}: </span> <span> <a href="{$aCompany.website}"> {$aCompany.website} </a> </span>
						</li>
						<li>
							<span> {phrase var='jobposting.company_size'}: </span> <span> {$aCompany.size_from}-{$aCompany.size_to} </span>
						</li>
						<li>
							<span> {phrase var='jobposting.industry'}: </span> <span> {$aCompany.industrial_phrase} </span>
						</li>
					</ul>			
					<h4><span> {phrase var='jobposting.contact_information'} </span></h4>
					<ul>
						<li>
							<span> {phrase var='jobposting.name'}: </span> <span class="ynjp_companyNameCo"> {$aCompany.contact_name} </span>
						</li>
						<li>
							<span> {phrase var='jobposting.phone'}: </span> <span> {$aCompany.contact_phone} </span>
						</li>
						<li>
							<span> {phrase var='jobposting.email'}: </span> <span> {$aCompany.contact_email} </span>
						</li>
						<li>
							<span> {phrase var='jobposting.fax'}: </span> <span> {$aCompany.contact_fax} </span>
						</li>
					</ul>
					<h4 style="margin-bottom:20px;" class="ynjp_h4_location"><span>Location: <b> {$aCompany.city_country_phrase}</b></span></h4>
					<iframe width="510" height="430" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="//maps.google.com/maps?f=q&source=s_q&geocode=&q={$aCompany.encode_location_city_country_phrase}+&ll={$aCompany.latitude},{$aCompany.longitude}8&spn=0,0&t=m&z=12&output=embed"></iframe>
				</li>
				<li id="tabs-2"></li>
				<li id="tabs-3">
				
				</li>
				<li id="tabs-4">
					{module name='jobposting.company.participant_company'}
				</li>
			</ul>
		</div>		
	</div>	
</div>


<div id="tabs3_viewcompany" style="display:none;">
		{template file="jobposting.block.job.mini_job_viewmore"}
						<span id="view_more_jobs"></span>
						{if $ViewMoreJob}
							<div id="href_view_more">
								<a href="#" onclick="$.ajaxCall('jobposting.view_more_jobs','iPage={$iPage}&company_id={$aCompany.company_id}');return false;">{phrase var='jobposting.view_more'}</a>
							</div>
						{/if}
					{if count($aJobsSearch)==0}
						<div>{phrase var='jobposting.no_jobs_found'}</div>
					{/if}
</div>
<script type="text/javascript"> 
	$Behavior.jobpostingInitilizeTabView = function() {l}
		$( "#tabs_view" ).tabs();
        $Core.loadInit = ynjobposting.overridedLoadInitForTabView;
        
		$('#tabs1>a').click(function(){l}
	    	$('#js_main_feed_holder').hide();
	    	$('#js_feed_content').hide();
	    	$('.ync_tabs_content').show();
	    	$('#tabs3_viewcompany').hide();
	    {r});
	    $('#tabs2>a').click(function(){l}
	    	$('#js_main_feed_holder').show();
	    	$('#js_feed_content').show();
	    	$('.ync_tabs_content').hide();
	    	$('#tabs3_viewcompany').hide();
	    {r});
	    $('#tabs3>a').click(function(){l}
	    	$('#js_main_feed_holder').hide();
	    	$('#js_feed_content').hide();
	    	$('.ync_tabs_content').hide();
	    	$('#tabs3_viewcompany').show();
	    {r});
	    $('#tabs4>a').click(function(){l}
	    	$('#js_main_feed_holder').hide();
	    	$('#js_feed_content').hide();
	    	$('.ync_tabs_content').show();
	    	$('#tabs3_viewcompany').hide();
	    {r});        
	{r};
	

</script>