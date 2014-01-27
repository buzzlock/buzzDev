<?php 
/**
 * [PHPFOX_HEADER]
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>

<div id="js_details_container" class="ynfr-detail-info">
    {if $sType == 'description'}
    <div class="listing_detail">
		<h2 class="ynfr-title-block">
			<span>{phrase var='fundraising.description'}</span>
		</h2>
        <div class="short_description">
            {if !empty($aCampaign.description)}
                {if Phpfox::getParam('core.allow_html')}
                    {$aCampaign.description_parsed|parse}
                {else}
                    {$aCampaign.description|parse}
                {/if}
            {else}
               {$aCampaign.short_description}
            {/if}
        </div>
		<h2 class="ynfr-title-block">
			<span>{phrase var='fundraising.location_upper'}: <b>{$aCampaign.location_venue}{if $aCampaign.city}, {$aCampaign.city} {/if}{if $aCampaign.country_iso}, {$aCampaign.country_iso|location}{/if} </b></span>
		</h2>
		{if !phpfox::isMobile()}
        <iframe width="510" height="430" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?f=q&amp;source=s_q&amp;geocode=&amp;q={$aCampaign.location_venue}+{$aCampaign.country_iso|location}+{$aCampaign.city}&amp;aq=&amp;sll={$aCampaign.latitude},{$aCampaign.longitude}&amp;sspn=0,0&amp;vpsrc=6&amp;doflg=ptk&amp;ie=UTF8&amp;hq={$aCampaign.location_venue}+{$aCampaign.country_iso|location}+{$aCampaign.city}&amp;ll={$aCampaign.latitude},{$aCampaign.longitude}&amp;spn=0,0&amp;t=m&amp;z=12&amp;output=embed"></iframe>
		{else}
		 <iframe width="100%" height="200" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?f=q&amp;source=s_q&amp;geocode=&amp;q={$aCampaign.location_venue}+{$aCampaign.country_iso|location}+{$aCampaign.city}&amp;aq=&amp;sll={$aCampaign.latitude},{$aCampaign.longitude}&amp;sspn=0,0&amp;vpsrc=6&amp;doflg=ptk&amp;ie=UTF8&amp;hq={$aCampaign.location_venue}+{$aCampaign.country_iso|location}+{$aCampaign.city}&amp;ll={$aCampaign.latitude},{$aCampaign.longitude}&amp;spn=0,0&amp;t=m&amp;z=12&amp;output=embed"></iframe>
		{/if}
	</div>
    {elseif $sType == 'donations'}
	{if phpfox::isMobile()}
		<h2 class="ynfr-title-block">
			<span>{phrase var='fundraising.donors_upper'}</span>
		</h2>
	{/if}
	<div class="pet_sign">
	<table>
	    {if count($aCampaign.donations) > 0}
		{foreach from=$aCampaign.donations name=iKey item=aDonation}
		<tr class="checkRow">
		    <td class="pet_img_tit">
				{if $aUser = $aDonation}{/if}
                {module name='fundraising.campaign.user-image-entry'}
		    </td>
            <td class="">
                <div style="margin-left: 7px">
                    {if $aDonation.is_anonymous }
                        {phrase var='fundraising.anonymous_donate' amount=$aDonation.amount_text}
                    {else}
                        {if $aDonation.is_guest}
                            {phrase var='fundraising.user_donate' name=$aDonation.donor_name amount=$aDonation.amount_text}
                        {else}
                            {phrase var='fundraising.user_donate' name=$aDonation|user amount=$aDonation.amount_text}
                        {/if}
                    {/if}
                    <div class="extra_info" ">
                        {$aDonation.message}
                    </div>
                </div>
            </td>
		    <td class="">{$aDonation.time_stamp|convert_time:'feed.feed_display_time_stamp'}</td>
		</tr>
		{/foreach}
	    {else}
		<tr>
		    <td colspan="2" style="text-align: center; padding: 10px">
			{phrase var='fundraising.there_are_no_fundraising_donation'}
		    </td>
		</tr>
	    {/if}	    
	</table>
	{if count($aCampaign.donations) > 0}
		<div class="clear"></div>
		{pager}
	{/if}
	</div>
    {elseif $sType == 'news'}
	{if phpfox::isMobile()}
		<h2 class="ynfr-title-block">
			<span>{phrase var='fundraising.news'}</span>
		</h2>
	{/if}
	{if $aCampaign.user_id == Phpfox::getUserId()}
	{$sCreateJs}
        {$sCheckFormNewsLink}
	{literal}
	<script type="text/javascript">            
		function valid_form_news()
		{
			if(checkFormNewsLink() && Validation_js_form_news())
			{
				$("#js_form_news").ajaxCall('fundraising.postNews'); 
			}
			return false;
		}
		
		function editNews(id){
			$('#news_headline').val($('#headline_'+id).html());
			$('#news_link').val($('#link_'+id).html());
			$('#news_content').val($('#content_'+id).html());
			$('#news_id').val(id);
			$('#post_news').hide();
			$('#update_news').show();
			$('#js_fundraising_detail').scrollTop(0);
		}
	</script>
	{/literal}
	<div class="info_holder news_detail">
		<div class="news">
			<div class="table">
				<div class="table_left" style="color: #333333;font-size: 14px;font-weight: bold">{phrase var='fundraising.post_a_news_update'}</div>
				<div class="table_right"></div>
			</div>
			<form id="js_form_news" method="post" action="#" onsubmit="return valid_form_news();" onreset="$('#update_news').hide(); $('#post_news').show(); $('#news_id').val(''); ">
			<input type="hidden" name="val[campaign_id]" value="{$aCampaign.campaign_id}"/>
			<input type="hidden" name="val[news_id]" id="news_id" value=""/>
			<div class="table">
				<div class="table_left">{required} {phrase var='fundraising.news_headline'}</div>
				<div class="table_right"><input type="text" name="val[news_headline]" id="news_headline" style="width: 90%"/></div>
			</div>
			<div class="table">
				<div class="table_left"> {phrase var='fundraising.link'}</div>
				<div class="table_right">
                           <input type="text" name="val[news_link]" id="news_link" style="width: 90%"/>
                           <div class="extra_info">
                              {phrase var='fundraising.example_http_www_yourwebsite_com'}
                           </div>
                        </div>
			</div>
			<div class="table">
				<div class="table_left">{required} {phrase var='fundraising.content'}</div>
				<div class="table_right"><textarea name="val[news_content]" id="news_content" style="width: 90%; height: 80px;"></textarea></div>
			</div>
			{if Phpfox::getParam('core.display_required')}
			<div class="table_clear">
				{required} {phrase var='fundraising.required_fields'}
			</div>
			{/if}
			<div class="clear">
				<input type="submit" name="val[post_news]" id="post_news" class="button" value="{phrase var='fundraising.post'}"/>
				<div id="update_news" style="display: none">
					<input type="submit" name="val[update_news]" class="button" value="{phrase var='fundraising.update'}"/>
					&nbsp;&nbsp;
					<input type="reset" class="button" value="{phrase var='fundraising.cancel'}" onclick="$('#news_id').val('');"/>
				</div>						
			</div>			
			</form>
		</div>
	</div>
	{/if}
	{if count($aCampaign.news) > 0}
	<div class="fundraising_discuss">
	    {foreach from=$aCampaign.news item=aNews}
	    <div class="discussion_id" id="news_{$aNews.news_id}">
		    <div class="pet_dis_tit" id="headline_{$aNews.news_id}" style="width: 80%">{$aNews.headline}</div>
		    <div class="extra_info">{$aNews.time_stamp|date:'fundraising.fundraising_time_stamp'}</div>		    
		    <div class="short_description" id="content_{$aNews.news_id}">
			{$aNews.content|parse}
		    </div>
		    </br>
		    {if !empty($aNews.link)}
		    <div class="short_description">{phrase var='fundraising.more_at'} <a href="{$aNews.link}" id="link_{$aNews.news_id}" target="_blank">{$aNews.link}</a></div>
		    {/if}
                {if $aCampaign.user_id == Phpfox::getUserId()}
		    <ul class="actions">
			<li><a href="JavaScript:void(0);" onclick="editNews({$aNews.news_id})">{phrase var='fundraising.edit'}</a> </li>
			<li> / </li>
			<li><a href="JavaScript:void(0);" onclick="if(confirm('{phrase var='fundraising.are_you_sure_you_want_to_delete_this_news' phpfox_squote=true}')) $.ajaxCall('fundraising.deleteNews', 'news_id={$aNews.news_id}'); if( $('#news_id').val() == {$aNews.news_id}) $('#js_form_news')[0].reset(); return false;">{phrase var='fundraising.delete'}</a></li>				
		     </ul>
                 {/if}
	    </div>
	    {/foreach}
	    <div class="clear"></div>
	    {pager}
	</div>
	{else}
		{phrase var='fundraising.there_are_no_news_update'}
	{/if}
    {elseif $sType == 'about'}
	{if phpfox::isMobile()}
		<h2 class="ynfr-title-block">
			<span>{phrase var='fundraising.about_us'}</span>
		</h2>
	{/if}
	<div class="ynfr-about">
        <table>
            {if !empty($aCampaign.contact_full_name)}<tr><td>{phrase var='fundraising.full_name'}:</td><td>{$aCampaign.contact_full_name}</td></tr>{/if}
            {if !empty($aCampaign.contact_phone)}<tr><td>{phrase var='fundraising.phone'}:</td><td>{$aCampaign.contact_phone}</td></tr>{/if}
            {if !empty($aCampaign.contact_email_address)}<tr><td>{phrase var='fundraising.email'}:</td><td>{$aCampaign.contact_email_address}</td></tr>{/if}
            {if !empty($aCampaign.contact_country_iso)}<tr><td>{phrase var='fundraising.country'}:</td><td>{$aCampaign.contact_country_iso|location}</td></tr>{/if}
            {if !empty($aCampaign.contact_state)}<tr><td>{phrase var='fundraising.state'}:</td><td>{$aCampaign.contact_state}</td></tr>{/if}
            {if !empty($aCampaign.contact_city)}<tr><td>{phrase var='fundraising.city'}:</td><td>{$aCampaign.contact_city}</td></tr>{/if}
            {if !empty($aCampaign.contact_street)}<tr><td>{phrase var='fundraising.street'}:</td><td>{$aCampaign.contact_street}</td></tr>{/if}
        </table>
        {if !empty($aCampaign.contact_about_me)}
        <h2 class="ynfr-title-block">
            <span>{phrase var='fundraising.about_us'}</span>
        </h2>
		<div class="ynfr-about-us">
			{$aCampaign.contact_about_me}
		</div>
        {/if}
	</div>
    {/if}
</div>
