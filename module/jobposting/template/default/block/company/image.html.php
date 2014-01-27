{if count($aImages) > 1}
<div class="js_box_thumbs_holder2">
{/if}
    <div class="jobposting_image_holder">
        <div class="jobposting_image">
            <a class="js_jobposting_click_image no_ajax_link" href="{img return_url=true server_id=$aCompany.server_id title=$aCompany.name path='core.url_pic' file="jobposting/".$aCompany.image_path suffix=''}">
            {img thickbox=true server_id=$aCompany.server_id title=$aCompany.name path='core.url_pic' file="jobposting/".$aCompany.image_path suffix='' max_width='173' max_height='200'}</a>
        </div>
        {if count($aImages) > 1}
        <div class="jobposting_view_image_extra js_box_image_holder_thumbs">
            <ul>
            	{foreach from=$aImages name=images item=aImage}
            		<li>
            			{img thickbox=true server_id=$aImage.server_id title=$aCompany.name path='core.url_pic' file="jobposting/".$aImage.image_path suffix='' width='50' height='50'}
            		</li>
            	{/foreach}
            </ul>
            <div class="clear"></div>
        </div>
        {/if}
    </div>
{if count($aImages) > 1}
</div>
{/if}


{if $ControllerName=="jobposting.view"}
<div class="ynjp_detail_links">
	<ul>
            {if PHpfox::getUserId()>0}
		<li><a href="javascript:void(0);" onclick="tb_show(\"{phrase var='jobposting.invite_friends'}\", $.ajaxBox('jobposting.blockInvite', 'width=800&height=350&type=job&id={$aJob.job_id}'));">{phrase var='jobposting.invite_friends'}</a></li>
		<li id="js_jp_follow_link"><a href="#" onclick="$.ajaxCall('jobposting.changeFollow', 'type=job&id={$aJob.job_id}&current={$iIsFollowed}'); return false;">{if $iIsFollowed}{phrase var='jobposting.unfollow'}{else}{phrase var='jobposting.follow'}{/if}</a></li>
		<li id="js_jp_favorite_link"><a href="#" onclick="$.ajaxCall('jobposting.changeFavorite', 'type=job&id={$aJob.job_id}&current={$iIsFavorited}'); return false;">{if $iIsFavorited}{phrase var='jobposting.unfavorite'}{else}{phrase var='jobposting.favorite'}{/if}</a></li>
            {/if}
		<li><a href="javascript:void(0);" onclick="tb_show('{phrase var='jobposting.promote_job'}', $.ajaxBox('jobposting.blockPromoteJob', 'width=550&height=350&id={$aJob.job_id}'));">{phrase var='jobposting.promote_job'}</a></li>
	</ul>
</div>
{else}
<div class="ynjp_detail_links">
	<ul>
            {if PHpfox::getUserId()>0}
		<li><a href="javascript:void(0);" onclick="tb_show('Invite Friends', $.ajaxBox('jobposting.blockInvite', 'width=800&height=350&type=company&id={$aCompany.company_id}'));">{phrase var='jobposting.invite_friends'}</a></li>
		<li id="js_jp_follow_link"><a href="#" onclick="$.ajaxCall('jobposting.changeFollow', 'type=company&id={$aCompany.company_id}&current={$iIsFollowed}'); return false;">{if $iIsFollowed}{phrase var='jobposting.unfollow'}{else}{phrase var='jobposting.follow'}{/if}</a>
		<li id="js_jp_favorite_link"><a href="#" onclick="$.ajaxCall('jobposting.changeFavorite', 'type=company&id={$aCompany.company_id}&current={$iIsFavorited}'); return false;">{if $iIsFavorited}{phrase var='jobposting.unfavorite'}{else}{phrase var='jobposting.favorite'}{/if}</a></li>
           {/if}
	</ul>
</div>
{if PHpfox::getUserId()>0}
<div id="join_leave_company">
	{if $iCompany==0}
		<input type="button" class="button" onclick="$.ajaxCall('jobposting.workingcompany','company_id={$aCompany.company_id}&working=1')" value="{phrase var='jobposting.working_at_this_company'}"/>
	{else}
		<input type="button" class="button" onclick="$.ajaxCall('jobposting.workingcompany','company_id={$aCompany.company_id}&working=0')" value="{phrase var='jobposting.leave_this_company'}"/>
	{/if}	
</div>
{/if}
{/if}
