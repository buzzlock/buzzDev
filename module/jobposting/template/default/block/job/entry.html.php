<div id="js_jp_job_entry_{$aJob.job_id}" class="ync-item-content ync_titleMiddle_content {if $list_show=='List'}ynjp_middleContent_listView_holder{/if}">
	<div class="ynjp_middleContent_holder {if $list_show=='List'}photo_row_height image_hover_holder{/if}">
		<!-- Image content -->
		{if $list_show!="List"}
		<div class="ynjp-image-blockCol photo_row_height image_hover_holder">
		{/if}
			<a href="#" class="image_hover_menu_link">Link</a>	
			{if $aJob.action}			
			<div class="image_hover_menu">
				<ul>
					{template file='jobposting.block.job.action-link'}
				</ul>
			</div>	
			{/if}		
			{if $list_show!="List"}			
			<a href="{permalink module='jobposting' id=$aJob.job_id title=$aJob.title}">
				{img server_id=$aJob.image_server_id path='core.url_pic' file="jobposting/".$aJob.image_path suffix='_150' max_width='120' max_height='115' class='js_mp_fix_width'}		
			</a>
			{/if}		
			{if $aJob.canDeleteJob}
			<div class="video_moderate_link"><a href="#{$aJob.job_id}" class="moderate_link" rel="">Moderate</a></div>				
			{/if}			
		{if $list_show!="List"}		
		</div>
		{/if}
		
		{if $aJob.is_featured == 1} 
			<div class="{if $list_show!='List'}small_feature_icon{else}small_feature_icon_listView{/if} small_ynjp_icon_holder">
				<span>{phrase var='jobposting.feature'}</span>
			</div>		
		{/if}		
		<!-- Information content -->
		<div class="ync_titleMiddle_info">
			<p class="ync-title">
				<strong>
					<a class="link ajax_link" href="{permalink module='jobposting' id=$aJob.job_id title=$aJob.title}">
						{$aJob.title}
					</a>
				</strong>
			</p>
			<div class="extra_info">
				<p class="ynjp_featureContent_coName">
					<a href="{permalink module='jobposting.company' id=$aJob.company_id title=$aJob.name}">{$aJob.name}</a>
				</p>
				<p>{$aJob.location}</p>
				<p class="ynjp_featureContent_industry"> {if isset($aJob.industrial_phrase) && $aJob.industrial_phrase!=""}{$aJob.industrial_phrase}{else}{phrase var='jobposting.n_a'} {phrase var='jobposting.industry'}{/if} </p>
				<p>{phrase var='jobposting.expire_on'}: {$aJob.time_expire_phrase}</p>
			</div>
            <!-- Follow/Favorite -->
            <div>
                {if isset($sView) && $sView=='favorite'}
                <a href="#" onclick="$.ajaxCall('jobposting.unfavorite', 'type=job&id={$aJob.job_id}'); return false;">{phrase var='jobposting.unfavorite'}</a>
                {/if}
                
                {if isset($sView) && $sView=='following'}
                <a href="#" onclick="$.ajaxCall('jobposting.unfollow', 'type=job&id={$aJob.job_id}'); return false;">{phrase var='jobposting.unfollow'}</a>
                {/if}
            </div>
		</div>
	</div>
	<div class="clear"></div>
</div>