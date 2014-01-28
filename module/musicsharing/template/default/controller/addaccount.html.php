<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>

{template file='musicsharing.block.mexpect'}

<img src='{$core_path}module/musicsharing/static/image/music/account.jpg' width="48px" height="48px" border='0' class='icon_big' style="margin-bottom: 15px;">
<div class='page_header'>{phrase var='musicsharing.add_account'}</div>

{if $result gt 0}
<div class="success">
    <img src='{$core_path}module/musicsharing/static/image/music/success.gif' border='0' class='icon'> {phrase var='musicsharing.your_account_have_been_created'}.
</div>
  <div class="space-line"></div>
  <div><a href="{url link='musicsharing.myaccounts'}">{phrase var='musicsharing.return_to_my_accounts'}</a></div>
{else}

<table cellpadding="0" cellspacing="5" width="100%">

<tr><td valign="top" width="50%">
    <form name="addalbum" class="" action="{url link='current'}" method="post">
          <div class="table">
              <div class="table_left"><span class="required">*</span>{phrase var='musicsharing.finance_account'}:</div>
              <div class="table_right"><input type="text" name="val[account_username]" id="title" style="width: 40%;"value="{$info.account_username}"/> </div>
          </div>

          <div class="table">
              <div class="table_left">{phrase var='musicsharing.password'}:</div>
              <div class="table_right">  <input id="retype_password" type="password" style="width: 40%;" name="val[password]" value="{$info.password}"></div>
          </div>

           <div class="table">
              <div class="table_left">{phrase var='musicsharing.re_type_password'}:</div>
              <div class="table_right">  <input id="retype_password" type="password" style="width: 40%;" name="val[retype_password]" value="{$info.retype_password}"></div>
          </div>
        
        <div class="table_clear">
        <input type="submit" name="submit" value="{phrase var='musicsharing.add_account'}" class="button" />
        </div>
    </form>
        </td>
    </tr>
</table>
 {/if}