<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>

{template file='musicsharing.block.mexpect'}

<img src='{$core_path}module/musicsharing/static/image/music/account.jpg' width="48px" height="48px" border='0' class='icon_big' style="margin-bottom: 15px;">
<div class='page_header'>{phrase var='musicsharing.edit_persional_information'}</div>


{if $result != 0}
  <div class='success'><img src='{$core_path}module/musicsharing/static/image/music/success.gif' border='0' class='icon'> {phrase var='musicsharing.your_changes_have_been_saved'}.</div>
{/if}
<table cellpadding="0" cellspacing="5" width="100%">

<tr><td valign="top" width="50%">
    <form name="addalbum" class="" action="{url link='current'}" method="post">
          <div class="table">
              <div class="table_left"><span class="required">*</span>{phrase var='musicsharing.full_name'}:</div>
              <div class="table_right"><input type="text" name="val[full_name]" id="title" value="{$info.full_name}"/> </div>
          </div>

          <div class="table">
              <div class="table_left">{phrase var='musicsharing.email'}:</div>
              <div class="table_right">  <input id="price" type="text" name="val[email]" value="{$info.email}"></div>
          </div>

        <div class="table">
            <div class="table_left">{phrase var='musicsharing.gender'}:</div>
            <div class="table_right">
            <select name="val[gender]">
                <option value="1"  {if $info.gender eq 1}selected{/if}>Male</option>
                <option value="2" {if $info.gender eq 2}selected{/if}>Fermale</option>
            </select>
             </div>
        </div>

         <div class="table">
              <div class="table_left">{phrase var='musicsharing.finance_account'}:</div>
              {if $info.account_username eq ''}
              <div class="table_right"><div class="message">You have not finance account.<a href="{url link='musicsharing.addaccount'}">Click here</a>  to add your account.</div></div>
              {else}
                    <div class="table_right"><input id="account_username" type="text" style="width: 50%;" name="val[account_username]" value="{$info.account_username}"></div>
              {/if}
          </div>
        
          <div class="table">
              <div class="table_left">{phrase var='musicsharing.status'}:</div>
              <div class="table_right"><textarea id="description" name="val[status]" cols="45" rows="6">{$info.status}</textarea></div>
          </div>
          <div class="table_clear">
        <input type="submit" name="submit" value="{phrase var='musicsharing.save_change'}" class="button" />
        </div>
</form>
        </td>
    </tr>
</table>
  <div class="space-line"></div>
<table cellpadding='0' cellspacing='3' width='150'>
      <tr>
          <td class='button' nowrap='nowrap'><img src='{$core_path}module/musicsharing/static/image/music/back16.gif' border='0' align="absmiddle" ><a href='{url link = "musicsharing.myaccounts"}'> {phrase var='musicsharing.back_to_my_accounts'}</a></td>
      </tr>
</table>