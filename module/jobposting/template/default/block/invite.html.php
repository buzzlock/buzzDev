<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * 
 * 
 * @copyright       [YOUNET_COPYRIGHT]
 * @author          AnNT
 * @package         Module_jobposting
 */
?>

<form method="post" action="{if $sType=='job'}{permalink module='jobposting' id=$aItem.job_id title=$aItem.title}{/if}{if $sType=='company'}{permalink module='jobposting.company' id=$aItem.company_id title=$aItem.name}{/if}" id="js_jp_invite_friend_form" enctype="multipart/form-data">
    <div id="js_jp_block_invite_friends" class="js_jobposting_block page_section_menu_holder">
		<div style="width:75%; float:left; position:relative;">				
            {if Phpfox::isModule('friend')}
            <h3 style="margin-top:0px; padding-top:0px;">{phrase var='jobposting.invite_friends'}</h3>
			<div style="height:370px;">	
                {if $sType=='job'}{module name='friend.search' input='invite' hide=true friend_item_id=$aItem.job_id friend_module_id='jobposting'}{/if}
                {if $sType=='company'}{module name='friend.search' input='invite' hide=true friend_item_id=$aItem.company_id friend_module_id='jobposting'}{/if}
			</div>
			{/if}
			
            <h3>{phrase var='jobposting.invite_people_via_email'}</h3>
			<div class="p_4">
				<textarea cols="40" rows="8" name="val[emails]" style="width:98%; height:60px;"></textarea>
				<div class="extra_info">
					{phrase var='jobposting.separate_multiple_emails_with_a_comma'}
				</div>
			</div>
            
            <h3>{phrase var='jobposting.add_a_personal_message'}</h3>
            <div class="table">
                <div class="table_left">
                    {phrase var='jobposting.subject'}:
                </div>
                <div class="table_right">
                    <input type="text" name="val[subject]" value="{$sSubject}" id="subject" maxlength="255" style="width: 98%;" />
                </div>
            </div>
            <div class="table">
                <div class="table_left">
                    {phrase var='jobposting.message'}:
                </div>
                <div class="table_right p_4">
                    <textarea cols="40" rows="8" name="val[personal_message]" style="width:98%; height:160px;">{$sMessage}</textarea>
                </div>
            </div>
			
			<div class="p_top_8">
				<input type="submit" name="val[submit_invite]" id="btn_invitations_submit" value="{phrase var='jobposting.send_invitations'}" class="button" />
			</div>
		</div>
        
		{if Phpfox::isModule('friend')}
		<div style="margin-left:77%; position:relative;">
			<div class="block">
				<div class="title">{phrase var='jobposting.new_guest_list'}</div>				
				<div class="content">
					<div class="label_flow" style="height:345px;">
						<div id="js_selected_friends"></div>
					</div>
				</div>
			</div>
		</div>
        <div class="clear"></div>
        {/if}
        	
    </div>
</form>

<script type="text/javascript">
$Behavior.setupInviteLayout = function() {l}
    $("#js_friend_loader").append('<div class="clear" style="padding:5px 0px 10px 0px;"><input type="button" style="cursor: pointer;" onclick="ynjobposting.invite.selectAll();" value="{phrase var='core.select_all'}" /> <input type="button" style="cursor: pointer;" onclick="ynjobposting.invite.unselectAll();" value="{phrase var='core.un_select_all'}"/></div>');
    $("#js_friend_loader").parent().css('height','');
{r}
</script>
