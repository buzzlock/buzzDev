<?php 
 
defined('PHPFOX') or exit('NO DICE!'); 

?>

<div id="mt_status_share">
	<div class="ym-sub-header">
	    <table>
	        <tr>
	            <td class="ym-left-head">
	                <button class="btn btn-head" onclick="ynmtMobileTemplate.cancelStatusShareFromHomepage(); return false;">{phrase var='mobiletemplate.cancel'}</button>
	            </td>
	            <td class="ym-center-head">
	                <p>{phrase var='share.social_bookmarks'}</p>
	            </td>
	            <td class="ym-right-head">
	                <div class="ym-main-header-right" style="padding-top:0">
	               </div>
	            </td>
	        </tr>
	    </table>
	</div>
	<div class="ym-sub-content">
		<div class="ym-socialshare">
			{if count($aPostBookmarks)}
			{foreach from=$aPostBookmarks item=aBookmark name=bookmark}
			<div class="go_left p_4" style="width:45%;">			    
				<a href="{$aBookmark.url}" target="_blank">
				    <img src="{$sUrlStaticImage}{$aBookmark.icon}" alt="" style="vertical-align:middle;" />
				    <span>{$aBookmark.title}</span>
				</a> 
			</div>
			{if is_int($phpfox.iteration.bookmark/2)}
			<div class="clear"></div>
			{/if}
			{/foreach}
			<div class="clear"></div>	
			{/if}
			{if count($aBookmarks)}
			{foreach from=$aBookmarks item=aBookmark name=bookmark}
			<div class="go_left p_4" style="width:45%;">
				<a href="{$aBookmark.url}" target="_blank">
				    <img src="{$sUrlStaticImage}{$aBookmark.icon}" alt="" style="vertical-align:middle;" />
				   <span>{$aBookmark.title}</span>
				 </a>
			</div>
			{if is_int($phpfox.iteration.bookmark/2)}
			<div class="clear"></div>
			{/if}
			{/foreach}
			<div class="clear"></div>
			{/if}
		</div>			
	</div>
</div>