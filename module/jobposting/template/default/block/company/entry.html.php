<div id="js_jp_company_entry_{$aCompany.company_id}" class="ync-item-content ync_titleMiddle_content {if $list_show=='List'}ynjp_middleContent_listView_holder{/if}">
	<div class="ynjp_middleContent_holder {if $list_show=='List'}photo_row_height image_hover_holder{/if}">
		<!-- Image content -->
		{if $list_show!="List"}
		<div class="ynjp-image-blockCol photo_row_height image_hover_holder">
		{/if}
			<a href="#" class="image_hover_menu_link">{phrase var='jobposting.link'}</a>	
			{if $aCompany.action}			
			<div class="image_hover_menu">
				<ul>
					{template file='jobposting.block.company.action-link'}
				</ul>
			</div>
			{/if}
			{if $list_show!="List"}		
			<a href="{permalink module='jobposting.company' id=$aCompany.company_id title=$aCompany.name}">
				{img server_id=$aCompany.server_id path='core.url_pic' file="jobposting/".$aCompany.image_path suffix='_150' max_width='120' max_height='115' class='js_mp_fix_width'}       
			</a>
			{/if}
			{if $aCompany.canDeleteCompany}
			<div class="video_moderate_link"><a href="#{$aCompany.company_id}" class="moderate_link" rel="">{phrase var='jobposting.moderate'}</a></div>			
			{/if}			
		{if $list_show!="List"}		
		</div>
		{/if}

		{if $aCompany.is_sponsor == 1}
		<div class="{if $list_show!='List'}small_sponsored_icon{else}small_sponsored_icon_listView{/if} small_ynjp_icon_holder">
			<span>{phrase var='jobposting.sponsored'}</span>
		</div>
		{/if}		
		<!-- Information content -->
		<div class="ync_titleMiddle_info" >
			<p class="ync-title">
				<strong>
					<a class="link ajax_link" href="{permalink module='jobposting.company' id=$aCompany.company_id title=$aCompany.name}">
						{$aCompany.name}
					</a>
				</strong>
			</p>
			<div class="extra_info">
				<p class="ynjp_featureContent_industry">
					{if isset($aCompany.industrial_phrase) && $aCompany.industrial_phrase!=""}{$aCompany.industrial_phrase}{else}{phrase var='jobposting.n_a_industry'}{/if}
					</p>
				<p> {$aCompany.size_from}-{$aCompany.size_to} {phrase var='jobposting.employees'} | {$aCompany.total_follow} {phrase var='jobposting.followers'} </p>
				<p>{$aCompany.location}</p>	        
			</div>
            <!-- Follow/Favorite -->
            <div>
                {if isset($sView) && $sView=='favoritecompany'}
                <a href="#" onclick="$.ajaxCall('jobposting.unfavorite', 'type=company&id={$aCompany.company_id}'); return false;">{phrase var='jobposting.unfavorite'}</a>
                {/if}
                
                {if isset($sView) && $sView=='followingcompany'}
                <a href="#" onclick="$.ajaxCall('jobposting.unfollow', 'type=company&id={$aCompany.company_id}'); return false;">{phrase var='jobposting.unfollow'}</a>
                {/if}
            </div>
		</div>
	</div>
	<div class="clear"></div>
</div>