<?php 
/**
 * [PHPFOX_HEADER]
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
{literal}
<style type="text/css">
    div#content #js_block_border_petition_detail div.menu
    {
        height:34px;
        background: #ececec;
        border-bottom: #dfdfdf;
    }
    div#content #js_block_border_petition_detail div.menu ul
    {
        padding-left: 10px;
    }
    div#content #js_block_border_petition_detail div.menu ul li a
    {
        line-height: 33px;
        font-size: 14px;
        color: #000;
    }
    div#content #js_block_border_petition_detail div.menu ul li.active
    {
        background: url({/literal}{$corepath}{literal}module/petition/static/image/menu-l.png) no-repeat;
        padding-left: 14px;
        margin-top: -4px;
    }
    div#content #js_block_border_petition_detail div.menu ul li.active a
    {
        background: url({/literal}{$corepath}{literal}module/petition/static/image/menu-r.png) no-repeat 100% 0;
        display: block;
        line-height: 38px;
        padding-right: 22px;
    }
    div#content #js_block_border_petition_detail div.menu ul li a
    {
        border:none;
        border-radius:0;
        background: none;
    }
    .short_description
    {       
        font-size: 12px;
    }
    
    .short_description p
    {
        font-size: 12px;
    }
    
    /*LETTER*/
    .pet_let_tit{font-size: 18px;font-weight: bold;padding:10px;}
    .pet_let_cont{white-space: pre-line;padding-left: 10px;font-size: 12px;}
    
    /*SIGNATURES*/
    .pet_sign > table 
     {
         background: #F1F1F1;
         border-bottom: 1px solid #DFDFDF;
         margin-bottom: 10px;
         width: 100%;
         margin-top: 10px;
     }
     .pet_sign > table .tr{background: #fff;}
     .pet_sign > table tr:hover{background: #ffffdf;}
     .pet_sign tr.pet_sign_tit td
     {
         background: #000;
         color: #FFFFFF;
         font-size: 14px;
         font-weight: bold;
         height: 35px;
         padding-left: 10px;
         text-align: left;
         vertical-align: middle;         
     }
     .pet_sign tr.pet_sign_tit td:nth-child(1)
     {
        width: 40%;
     }
     .pet_sign > table td{padding:4px;}
     .pet_sign > table td.pet_res_tit{width: 60%;vertical-align: top;}
     .pet_sign > table td.pet_img_tit img{float:left;}
     .pet_sign div.row_title_info a.link{font-size: 12px;font-weight: normal;}
     .pet_sign div.row_title_info div.extra_info{margin-top: 0px;}
        /*NEWS*/
    .discussion_id{padding:5px;}
    .pet_dis_tit{color:#333;font-weight: bold;font-size: 14px;}    
    .discussion_id{border-bottom: 1px solid #dfdfdf; position: relative;}
    .discussion_id .actions{position: absolute; top: 0; right: 10px;}
    .discussion_id .actions li{display: inline;}
</style>    
{/literal}
<div id="js_details_container">
    {if $sType == 'description'}
		{if phpfox::isMobile()}
	<h3>{phrase var='petition.description'}</h3>
	{/if}
    <div class="listing_detail">
        <div class="short_description">
            {if !empty($aPetition.description)}
               {$aPetition.description|parse}
            {else}
               {$aPetition.short_description}
            {/if}
        </div>
    </div>
    {elseif $sType == 'letter'}
{if phpfox::isMobile()}
	<h3>{phrase var='petition.petition_letter'}</h3>
	{/if}
	<div class="pet_let_tit">{$aPetition.letter_subject}</div>
	<div class="pet_let_cont">
	    {$aPetition.letter}
	</div>
    {elseif $sType == 'signatures'}
{if phpfox::isMobile()}
	<h3>{phrase var='petition.signatures'}</h3>
	{/if}
	<div class="pet_sign">
	<table>
	    <tr class="pet_sign_tit"><td>{phrase var='petition.members'}</td><td>{phrase var='petition.why_they_are_signing'}</td></tr>
	    {if count($aPetition.signatures) > 0}
		{foreach from=$aPetition.signatures name=iKey item=aSignature}
		<tr class="checkRow{*{if is_int($iKey/2)}*} tr {*{else}{/if}*}">
		    <td class="pet_img_tit">
			 {img user=$aSignature suffix='_50_square' max_width=50 max_height=50}
			 <div class="row_title_info">		
			    {$aSignature|user}
			    <div class="extra_info">{phrase var='petition.on'} {$aSignature.time_stamp|date:'petition.petition_time_stamp'}
				</br>   {phrase var='petition.at'} {$aSignature.location|clean'}
			    </div>							
			</div>
		    </td> 
		    <td class="pet_res_tit">{$aSignature.signature}</td>
		</tr>
		{/foreach}		
	    {else}
		<tr>
		    <td colspan="2" style="text-align: center; padding: 10px">
			{phrase var='petition.there_are_no_petition_signatures'}
		    </td>
		</tr>
	    {/if}	    
	</table>
	{if count($aPetition.signatures) > 0}
		<div class="clear"></div>
		{pager}
	{/if}
	</div>
    {elseif $sType == 'news'}
{if phpfox::isMobile()}
	<h3>{phrase var='petition.news'}</h3>
		{/if}
	{if $aPetition.user_id == Phpfox::getUserId()}
	{$sCreateJs}
      {$sCheckFormNewsLink}
	{literal}
	<script type="text/javascript">            
		function valid_form_news()
		{
			$('#js_form_news_msg').html("");
			if(checkFormNewsLink() && Validation_js_form_news())
			{
				$("#js_form_news").ajaxCall('petition.postNews'); 
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
			$('#js_petition_detail').scrollTop(0);
		}
	</script>
	{/literal}
	<div class="info_holder news_detail">
		<div class="news">
			<div class="table">
				<div class="table_left" style="color: #333333;font-size: 14px;font-weight: bold">{phrase var='petition.post_a_news_update'}</div>
				<div class="table_right"></div>
			</div>
			<form id="js_form_news" method="post" action="#" onsubmit="return valid_form_news();" onreset="$('#update_news').hide(); $('#post_news').show(); $('#news_id').val(''); ">
			<input type="hidden" name="val[petition_id]" value="{$aPetition.petition_id}"/>
			<input type="hidden" name="val[news_id]" id="news_id" value=""/>
			<div class="table">
				<div class="table_left">{required} {phrase var='petition.news_headline'}</div>
				<div class="table_right"><input type="text" name="val[news_headline]" id="news_headline" style="width: 90%"/></div>
			</div>
			<div class="table">
				<div class="table_left"> {phrase var='petition.link'}</div>
				<div class="table_right">
                           <input type="text" name="val[news_link]" id="news_link" style="width: 90%"/>
                           <div class="extra_info">
                              {phrase var='petition.example_http_www_yourwebsite_com'}
                           </div>
                        </div>
			</div>
			<div class="table">
				<div class="table_left">{required} {phrase var='petition.content'}</div>
				<div class="table_right"><textarea name="val[news_content]" id="news_content" style="width: 90%; height: 80px;"></textarea></div>
			</div>
			{if Phpfox::getParam('core.display_required')}
			<div class="table_clear">
				{required} {phrase var='petition.required_fields'}
			</div>
			{/if}
			<div class="clear">
				<input type="submit" name="val[post_news]" id="post_news" class="button" value="{phrase var='petition.post'}"/>
				<div id="update_news" style="display: none">
					<input type="submit" name="val[update_news]" class="button" value="{phrase var='petition.update'}"/>
					&nbsp;&nbsp;
					<input type="reset" class="button" value="{phrase var='petition.cancel'}" onclick="$('#news_id').val('');"/>
				</div>						
			</div>			
			</form>
		</div>
	</div>
	{/if}
	{if count($aPetition.news) > 0}
	<div class="petition_discuss">
	    {foreach from=$aPetition.news item=aNews}
	    <div class="discussion_id" id="news_{$aNews.news_id}">
		    <div class="pet_dis_tit" id="headline_{$aNews.news_id}" style="width: 80%">{$aNews.headline}</div>
		    <div class="extra_info">{$aNews.time_stamp|date:'petition.petition_time_stamp'}</div>		    
		    <div class="short_description" id="content_{$aNews.news_id}">
			{$aNews.content|parse}
		    </div>
		    </br>
		    {if !empty($aNews.link)}
		    <div class="short_description">{phrase var='petition.more_at'} <a href="{$aNews.link}" id="link_{$aNews.news_id}" target="_blank">{$aNews.link}</a></div>
		    {/if}
                {if $aPetition.user_id == Phpfox::getUserId()}
		    <ul class="actions">
			<li><a href="JavaScript:void(0);" onclick="editNews({$aNews.news_id})">{phrase var='petition.edit'}</a> </li>
			<li> / </li>
			<li><a href="JavaScript:void(0);" onclick="if(confirm('{phrase var='petition.are_you_sure_you_want_to_delete_this_news' phpfox_squote=true}')) $.ajaxCall('petition.deleteNews', 'news_id={$aNews.news_id}'); if( $('#news_id').val() == {$aNews.news_id}) $('#js_form_news')[0].reset(); return false;">{phrase var='petition.delete'}</a></li>				
		     </ul>
                 {/if}
	    </div>
	    {/foreach}
	    <div class="clear"></div>
	    {pager}
	</div>
	{else}
		{phrase var='petition.there_are_no_news_update'}
	{/if}
    {/if}
</div>
