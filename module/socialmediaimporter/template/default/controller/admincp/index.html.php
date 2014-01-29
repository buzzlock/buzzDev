<?php
  defined('PHPFOX') or exit('NO DICE!');
?>

{literal}
<script type="text/javascript">	
	$("#breadcrumb_holder").hide();
	$("#breadcrumb_content_holder").css("margin-left","0");	
	function viewTutorial(ele)
	{
		if(ele)
		{
			if($(ele).html() == oTranslations['socialmediaimporter.view'])
			{
				$('#'+ele.rel).stop(true,true).slideDown();
				$(ele).html(oTranslations['socialmediaimporter.hide']);
			}
			else
			{
				$('#'+ele.rel).stop(true,true).slideUp();
				$(ele).html(oTranslations['socialmediaimporter.view']);
			}
				
		}
		
	}

	$Behavior.checkYNSSNumber = (function(){
		$('input.yn_ss_time').keydown(function (e) {
                  if (e.altKey || e.ctrlKey) { 
			    e.preventDefault();         
			}
			else if (e.shiftKey && !(e.keyCode >= 35 && e.keyCode <= 40)){ 			    
				  e.preventDefault();     
			} else {
			    var n = e.keyCode;
			    if (!((n == 8)             
			    || (n == 46)               
			    || (n >= 35 && n <= 40)    
			    || (n >= 48 && n <= 57)    
			    || (n >= 96 && n <= 105))  
			    ) {
				  e.preventDefault();     
			    }
			}
		});   
	});
</script>
<style type="text/css">
	.tip{
		margin-top: 0px;
	}
	div.tip_tutorial
	{
		padding-bottom:4px;
		border-bottom: 1px solid #CFCFCF;
	}
	div.tip_tutorial ul 
	{
    	counter-reset: li;
	}
	div.tip_tutorial ul li 
	{
    	list-style: decimal-leading-zero outside none;
	    margin: 0 0 0 26px;
	    padding: 4px 0;
	    position: relative;
	}
	
	.table {
		border-bottom: none;
	}
</style>
{/literal}
{foreach from=$aProviders index=iKey item=aProvider}	
	{if $aProvider.name == 'flickr' }
		<div class="table_header">
			{$aProvider.title} {phrase var='socialmediaimporter.settings'}
		</div>
		<div class="table">
			<div class="tip" id="tip_flickr"><a href="javascript:void(0)" onclick="viewTutorial(this);" rel="tip_flickr_tutorial">{phrase var='socialmediaimporter.view'}</a> {phrase var='socialmediaimporter.tutorial_how_to_get_flickr_api_key'}</div>
			<div id="tip_flickr_tutorial" class="tip_tutorial" style="display: none">
				<ul>
					{phrase var='socialmediaimporter.to_get_your_flickr_api_id'}
					<li>{phrase var='socialmediaimporter.for_callback_url_you_should_use_this_url'} <strong>{$sCoreUrl}module/socialmediaimporter/static/php/static.php</strong> {phrase var='socialmediaimporter.edit_the_auth_flow'}</li>
					<li>{phrase var='socialmediaimporter.do_follow_flickr_steps_to_complete_create_new_application'}</li>
				<!--li>{phrase var='socialmediaimporter.go_to_the_following_url_select_your_app_and_edit'}</li>
				<li>{phrase var='socialmediaimporter.select_advanced_setting_and_edit_migrations_settings'}</li-->
				</ul>
			</div>
			<form action="{url link='admincp.socialmediaimporter'}" method="post">
				<div class="table">
					<div class="table_left" style="width: 200px;">{phrase var='socialmediaimporter.application_id'}</div>
					<div class="table_right" style="margin-left:200px"><input type="text" size="40" value="{if isset($aProvider.params.app_id)}{$aProvider.params.app_id}{/if}" name="flickr[app_id]"></div>
				</div>
				<div class="table">
					<div class="table_left" style="width: 200px;">{phrase var='socialmediaimporter.flickr_secret'}</div>
					<div class="table_right" style="margin-left:200px"><input type="text" size="60" value="{if isset($aProvider.params.secret)}{$aProvider.params.secret}{/if}" name="flickr[secret]"></div>
				</div>				
				<div class="table" style="display:none;">
					<div class="table_left" style="width: 200px;">
						{required}{phrase var='socialmediaimporter.enable_flickr_connect'}
					</div>
					<div class="table_right" style="margin-left:200px">
						<div class="item_can_be_closed_holder">
							<span class="item_is_active">
								<input type="radio" name="flickr[is_active]" value="1"  {if isset($aProvider.is_active) && $aProvider.is_active == 1}checked{/if}/> {phrase var='admincp.yes'}
							</span>
							<span class=" item_is_not_active">
								<input type="radio" name="flickr[is_active]" value="0" {if !isset($aProvider.is_active) || $aProvider.is_active == 0}checked{/if}/> {phrase var='admincp.no'}
							 </span>
						</div>
					</div>
					<div class="clear"></div>
				</div>
				<div class="table_clear" style="margin-bottom: 10px;">
					<input type="submit" value="{phrase var='core.submit'}" class="button" />
				</div>
				<div class="clear"></div>
				<div class="clear"></div>
			</form>
		{/if}
		{if $aProvider.name == 'facebook' }
			<div class="table_header">
				{$aProvider.title} {phrase var='socialmediaimporter.settings'}
			</div>
			<div class="tip" id="tip_facebook"><a href="javascript:void(0)" onclick="viewTutorial(this);" rel="tip_facebook_tutorial">{phrase var='socialmediaimporter.view'}</a> {phrase var='socialmediaimporter.tutorial_how_to_get_facebook_api_key'}</div>
			<div id="tip_facebook_tutorial" class="tip_tutorial" style="display: none">
				<ul>
					{phrase var='socialmediaimporter.to_get_your_facebook_api_id'}
					<li>{phrase var='socialmediaimporter.for_site_url_you_should_use_this_url'} <strong>{$sCoreUrl}</strong> </li>
					<li>{phrase var='socialmediaimporter.do_follow_facebook_steps_to_complete_create_new_application'}</li>
				<li>{phrase var='socialmediaimporter.go_to_the_following_url_select_your_app_and_edit'}</li>
				<!--li>{phrase var='socialmediaimporter.select_advanced_setting_and_edit_migrations_settings'}</li-->
				</ul>
			</div>
			<form action="{url link='admincp.socialmediaimporter'}" method="post">
				<div class="table">
					<div class="table_left" style="width: 200px;">{phrase var='socialmediaimporter.application_id'}</div>
					<div class="table_right" style="margin-left:200px"><input type="text" size="40" value="{if isset($aProvider.params.app_id)}{$aProvider.params.app_id}{/if}" name="facebook[app_id]"></div>
				</div>
				<div class="table">
					<div class="table_left" style="width: 200px;">{phrase var='socialmediaimporter.facebook_secret'}</div>
					<div class="table_right" style="margin-left:200px"><input type="text" size="60" value="{if isset($aProvider.params.secret)}{$aProvider.params.secret}{/if}" name="facebook[secret]"></div>
				</div>				
				<div class="table" style="display:none;">
					<div class="table_left" style="width: 200px;">
						{required}{phrase var='socialmediaimporter.enable_facebook_connect'}
					</div>
					<div class="table_right" style="margin-left:200px">
						<div class="item_can_be_closed_holder">
							<span class="item_is_active">
								<input type="radio" name="facebook[is_active]" value="1"  {if isset($aProvider.is_active) && $aProvider.is_active == 1}checked{/if}/> {phrase var='admincp.yes'}
							</span>
							<span class=" item_is_not_active">
								<input type="radio" name="facebook[is_active]" value="0" {if !isset($aProvider.is_active) || $aProvider.is_active == 0}checked{/if}/> {phrase var='admincp.no'}
							 </span>
						</div>
					</div>
					<div class="clear"></div>
				</div>
				<div class="table_clear" style="margin-bottom: 10px;">
					<input type="submit" value="{phrase var='core.submit'}" class="button" />
				</div>
				<div class="clear"></div>
				<div class="clear"></div>
			</form>
		{/if}
		{if $aProvider.name == 'twitter' }
			<div class="tip" id="tip_twitter"><a href="javascript:void(0)" onclick="viewTutorial(this);" rel="tip_twitter_tutorial">{phrase var='socialmediaimporter.view'}</a> {phrase var='socialmediaimporter.tutorial_how_to_get_twitter_api_key'}</div>
			<div id="tip_twitter_tutorial" class="tip_tutorial" style="display: none">
				<ul>
					{phrase var='socialmediaimporter.to_get_your_twitter_api'}
					<li>
						<div class="p_4">{phrase var='socialmediaimporter.for_website_you_should_use_this_url'} <strong>{$sCoreUrl}</strong></div>
						<div class="p_4">{phrase var='socialmediaimporter.for_callback_url_must_use'} <strong style="color:red;">{$sCallBackUrl}</strong></div>
						
					</li>
					{phrase var='socialmediaimporter.do_follow_twitter_steps_to_complete_create_new_application'}
				</ul>
			</div>
			<form action="{url link='admincp.socialmediaimporter'}" method="post">
				<div class="table">
					<div class="table_left" style="width: 200px;">{phrase var='socialmediaimporter.consumer_key'}</div>
					<div class="table_right" style="margin-left:200px"><input type="text" size="40" value="{if isset($aProvider.params.consumer_key)}{$aProvider.params.consumer_key}{/if}" name="twitter[consumer_key]"></div>
				</div>
				<div class="table">
					<div class="table_left" style="width: 200px;">{phrase var='socialmediaimporter.consumer_secret'}</div>
					<div class="table_right" style="margin-left:200px"><input type="text" size="60" value="{if isset($aProvider.params.consumer_secret)}{$aProvider.params.consumer_secret}{/if}" name="twitter[consumer_secret]"></div>
				</div>
				<!--div class="table">
					<div class="table_left" style="width: 200px;">{phrase var='socialmediaimporter.how_long_to_get_data'}</div>
					<div class="table_right" style="margin-left:200px">
					<input class='yn_ss_time' type="text" size="10" value="{if isset($aProvider.params.time_to_get)}{$aProvider.params.time_to_get}{else}1{/if}" name="twitter[time_to_get]">
					<select name="twitter[time_type]">
					  {foreach from=$aSupportedTimes key=iKey item=aSupportedTime}					  
						<option value='{$iKey}' {if isset($aProvider.params.time_type) && $aProvider.params.time_type == $iKey}selected='selected'{/if}>{$aSupportedTime}</option>
					  {/foreach}
					</select>
					</div>
				</div-->
				<div class="table">
					<div class="table_left" style="width: 200px;">
						{required}{phrase var='socialmediaimporter.enable_twitter_connect'}
					</div>
					<div class="table_right" style="margin-left:200px">
						<div class="item_can_be_closed_holder">
							<span class="item_is_active">
								<input type="radio" name="twitter[is_active]" value="1" {if isset($aProvider.is_active) && $aProvider.is_active == 1}checked{/if} /> {phrase var='admincp.yes'}
							</span>
							<span class=" item_is_not_active">
								<input type="radio" name="twitter[is_active]" value="0" {if !isset($aProvider.is_active) || $aProvider.is_active == 0}checked{/if}/> {phrase var='admincp.no'}
							 </span>
						</div>
					</div>
					<div class="clear"></div>
				</div>
				<div class="table_clear" style="margin-bottom: 10px;">
					<input type="submit" value="{phrase var='core.submit'}" class="button" />
				</div>
				<div class="clear"></div>
				<div class="clear"></div>
			</form>
		</div>
	{/if}     
{/foreach}
