<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>

{template file='musicsharing.block.mexpect'}

<img src='{$core_path}module/musicsharing/static/image/music/account.jpg' width="48px" height="48px" border='0' class='icon_big margin-bottom-15'">
<div class='page_header'>{phrase var='musicsharing.my_accounts'}</div>
<div>
      {phrase var='musicsharing.persional_finance_account_management'}.<span><a href="{url link = 'musicsharing.cart.transaction.'.$user_id}"> {phrase var='musicsharing.view_my_transaction_history'}</a></span><br />
</div>

<div class="space-line"></div>

<div class="margin-bottom-10 myaccount">
	<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td style="width: 50%;margin-top: 8px;">
				<div align="left">
					<form method="post" action="{url link='musicsharing.editpersionalinfo'}">
						<table cellpadding="0" cellspacing="0" border="0" width="100%">
							<tr style="background:#2C2C2C none repeat scroll 0 0;">
								<th align="center" style="padding-top: 5px;padding-bottom: 5px;color:#FFF;">{phrase var='musicsharing.user_information'}</th>
							</tr>
							<tr>
								<td class="account" >
									<span class="account_bold">{phrase var='musicsharing.username'}</span> : {$info_user.user_name}
								</td>
							</tr>
							<tr>
								<td class="account">
									<span class="account_bold">{phrase var='musicsharing.full_name'}</span> : {$info_user.full_name}
								</td>
							</tr>
							<tr>
								<td class="account">
									<span class="account_bold">{phrase var='musicsharing.email'}</span> : {$info_user.email}
								</td>
							</tr>
							<tr>
								<td class="account">
									<span class="account_bold">{phrase var='musicsharing.gender'}</span> : {if $info_user.gender eq 1}Male{else}Fermale{/if}
								</td>
							</tr>
							<tr>
								<td class="account">
									<span class="account_bold">{phrase var='musicsharing.status'}</span> : {if $info_user.status<>''}{$info_user.status}{else}Not update{/if}
								</td>
							</tr>
							<tr>
								<td class="account">

									<span class="account_bold">{phrase var='musicsharing.please_read_carefully_all_policy_of_music_selling'}</span>
									<div class="p_4"></div>
									<div style="float: left; text-align: center;margin-right: 10px;">
									<a href="#?call=musicsharing.cart.viewpolicy&amp;height=400&amp;width=600&amp;policy=policy_message" title="Policy" class="inlinePopup">{phrase var='musicsharing.general_policy'}</a>
									</div>
									<div style="float: right; text-align: center;margin-right:10px;">
									<a href="#?call=musicsharing.cart.viewpolicy&amp;height=400&amp;width=600&amp;policy=policy_message_request" title="Policy" class="inlinePopup">{phrase var='musicsharing.request_policy'}</a>
									</div>
									<div style = "clear:both"></div>
								</td>
							</tr>

							<tr>
								<td align="right" class="account">
									<div class="p_4">
										<input type="submit" value="Edit" class="button" name="editperionalinfo"/>
									</div>
								</td>
							</tr>
						</table>
					</form>
				</div>
			</td>
			<td style="width: 50%;margin-top: 8px;vertical-align: top;">
				<div  align="right" style="padding:0;">
					<table cellpadding="0" cellspacing="0" border="0" width="90%">
						<tr style="background:#2C2C2C none repeat scroll 0 0;">
							<th align="center" style="padding-top: 5px;padding-bottom: 5px;color:#FFF;">{phrase var='musicsharing.summary'}</th>
						</tr>
						<tr>
							<td class="account" align="left">
								<span class="account_bold">{phrase var='musicsharing.account'}</span>:<a href="#?call=musicsharing.cart.viewpopupTransaction&amp;height=500&amp;width=800&amp;id={$user_id}" class="inlinePopup" title="Transaction Hitory"> {$info_account.account_username}</a>
							</td>
						</tr>
						<tr>
							<td class="account" align="left">
								<span class="account_bold">{phrase var='musicsharing.accumulated'} </span> : <span id="current_money" style="color: red;font-weight: bold;" >{$info_account.total_amount}</span> {$currency}
							</td>
						</tr>
						<tr>
							<td class="account" align="left">
								<span class="account_bold">{phrase var='musicsharing.waiting'} </span> : <span id="current_request_money" style="color: blue;font-weight: bold;">{$requested_amount}</span> {$currency}
							</td>
						</tr>
						<tr>
							<td class="account" align="left">
								<span class="account_bold">{phrase var='musicsharing.current_amount'}</span> : <span id="current_money_money" style="color: red;font-weight: bold;">{$current_amount}</span> {$currency}
							</td>
						</tr>
						 <tr>
							<td class="account" align="left">
								<span class="account_bold">{phrase var='musicsharing.minimum_to_request'}</span>: {$min_payout} {$currency}
							</td>
						</tr>
						<tr>
							<td class="account" align="left">
								<span class="account_bold">{phrase var='musicsharing.maximum_to_request'}</span> :<span style="color: red;font-weight: bold;"> {if $max_payout eq -1}{phrase var='musicsharing.unlimited'}  {else} {$max_payout} {$currency}{/if}</span>
							</td>
						</tr>

						<tr>
							<td align="right" class="account">
								<div class="p_4">
									<form method="post" action="{url link='musicsharing.addaccount'}">
										{if $allow_request eq 0}
											{if $info_account.payment_type != 1}
												<a href="#?call=musicsharing.cart.payment_threshold&amp;height=170&amp;width=400&amp;user_id=1" title="{phrase var='musicsharing.request_amount'}" class="inlinePopup"><input type="button" disabled value="{phrase var='musicsharing.request'}"  onclick="" class="button" name="request"/></a>
											{/if}
										{else}
											{if $info_account.payment_type != 1}
											<a href="#?call=musicsharing.cart.payment_threshold&amp;height=170&amp;width=400&amp;user_id=1" title="{phrase var='musicsharing.request_amount'}" class="inlinePopup"><input type="button" value="{phrase var='musicsharing.request'}"  onclick="" class="button" name="request"/></a>
											{else}
												You're admin.You cannot request money
											{/if}
										{/if}
										{if count($info_account) eq 0}
											{if $info_account.payment_type != 1}
												<input type="submit" value="{phrase var='musicsharing.add_account'}"  class="button" name="addaccount"/>
											{/if}
										{/if}
									</form>
								</div>
							</td>
						</tr>
					</table>
				</div>
			</td>
		</tr>
	</table>
</div>
<div class="message">
    <a href="javascript:loadMessageFromRequest({$info_user.user_id},'{$core_path}')">{phrase var='musicsharing.click_here_to_view_message_from_admin_with_your_request_s'}</a>
</div>
<div id="message_request_{$info_user.user_id}">
</div>
<table cellpading="0" cellspacing="0" border="0" width="100%">
	<tr>
	<td>
		<div class="box_ys2" id="song_list_frame">
			<div class="top_right_box" >
				<div class="top_left_box" ></div>
				<div class="title_box" style="padding-top:7px; padding-left:2px">{phrase var='musicsharing.sumary_sold_items'}</div>
			</div>
			<div class="t">
				<div class="l">
					<div class="r" style="padding:1px">
						<div>
							<table cellpadding="0" cellspacing="0" width="100%">
								<tr style="background:#2C2C2C none repeat scroll 0 0;">
									<td height="25px" width="40%" style="font-weight:bold;color:#FFF;padding:2px 2px 2px 7px;">{phrase var='musicsharing.name'}</td>
									<td style="font-weight:bold;color:#FFF;padding:2px;text-align:center">{phrase var='musicsharing.type'}</td>
									<td style="font-weight:bold;color:#FFF;padding:2px;text-align:center">{phrase var='musicsharing.amount'}</td>
								</tr>
								{foreach from=$HistorySeller key=index item=iSong}
									{template file='musicsharing.block.historyitem_info'}
								{/foreach}
								<tr>
									<td style=" padding-left: 5px; text-align: right" colspan="3" align="right">
										  <div class="p_4"></div>
											{pager}
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="b">
				<div class="l">
					<div class="r">
						<div class="bl">
							<div class="br" style="height:7px">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</td>
	</tr>
</table>