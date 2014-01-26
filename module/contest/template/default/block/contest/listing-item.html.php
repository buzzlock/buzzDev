<div class="yc large_item large_item_closed" id="js_contest_item_{$aContest.contest_id}">
	<div class="large_item_image ele_relative image_hover_holder" style="background-image:url({img server_id=$aContest.server_id return_url=true path='core.url_pic' file='contest/'.$aContest.image_path suffix='_240'})">
		<span class="entype {$aContest.style_type}"></span> <!-- enblog // enphoto // envideo // enmusic -->
        
        <ul class="list_itype">
        {if $aContest.contest_status == 1}
            <li class="itype endraft">{phrase var='contest.draft'}</li>
        {elseif $aContest.contest_status == 2}
			<li class="itype enpending">{phrase var='contest.pending'}</li>
        {elseif $aContest.contest_status == 3}
			<li class="itype endenied">{phrase var='contest.denied'}</li>
        {elseif $aContest.contest_status == 5}
			<li class="itype enclosed">{phrase var='contest.closed'}</li>
        {else}
			{if $aContest.is_feature}<li class="itype enfeatured">{phrase var='contest.featured'}</li>{/if}
			{if $aContest.is_premium}<li class="itype enpremium">{phrase var='contest.premium'}</li>{/if}
			{if $aContest.is_ending_soon}<li class="itype endinsoon">{phrase var='contest.ending_soon'}</li>{/if}
		{/if}
        </ul>
		
        <div class="large_item_info">
			<a class="small_title" href="{permalink module='contest' id=$aContest.contest_id title=$aContest.contest_name}" title="{$aContest.contest_name|clean}">
				{$aContest.contest_name|clean|shorten:15:'...'|split:15}
			</a>
			<div class="extra_info">
                {if isset($sView) && $sView=='ending_soon'}
				<p class="time_left">{$aContest.contest_countdown}</p>
                {/if}
			</div>
		</div>
		
        <div class="large_item_info large_hover" onclick="window.location.href='{permalink module='contest' id=$aContest.contest_id title=$aContest.contest_name}'">
			<a class="small_title" href="{permalink module='contest' id=$aContest.contest_id title=$aContest.contest_name}" title="{$aContest.contest_name|clean}">
				{$aContest.contest_name|clean|shorten:25:'...'|split:25}
			</a>
			<div class="extra_info">
				<p>{phrase var='contest.end'}: {$aContest.end_time_parsed}</p><br />
				<p>{phrase var='contest.created_by'}: <strong>{$aContest|user}</strong></p>
			</div>
		</div>
		<!-- option -->
		{if $sView == 'closed' && Phpfox::isAdmin()}
		<div class="yc_moderate_link">
			<a href="#{$aContest.contest_id}" class="moderate_link" rel="contest">{phrase var='contest.moderate'}</a>					  
		</div>
		{/if}
		 
		{if !isset($bIsProfile) || ($bIsProfile==true && $accessprofile==true) || Phpfox::isAdmin()}
		<a href="#" class="image_hover_menu_link">{phrase var='contest.link'}</a>
		<div class="image_hover_menu">
			<ul>
				{template file='contest.block.contest.action-link'}
			</ul>			
		</div>
		{/if}
	</div>
    
	<div class="large_item_action">
		<div>
			<p>{phrase var='contest.participants'}</p>
			<p class="f_14">{$aContest.total_participant}</p>
		</div>
		<div>
			<p>{phrase var='contest.entries'}</p> 
			<p class="f_14">{$aContest.total_entry}</p>
		</div>
	</div>
</div>

