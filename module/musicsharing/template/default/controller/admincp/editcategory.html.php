<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');
?>




<div style="padding-top: 18px;"></div>
<div class="table_header">Edit Catagory</div>
<table cellpadding='0' cellspacing='0' width='100%'><tr><td class='page'>
{* SHOW RESULT MESSAGE *}
{if $result != 0}
  <div class='success'><img src='{$core_path}module/musicsharing/static/image/music/success.gif' border='0' class='icon'> {phrase var='musicsharing.your_changes_have_been_saved'}.</div>
{/if}


<table cellpadding="0" cellspacing="5" width="100%">
<tr><td valign="top" width="50%">
        <form name="editcategory"  action="{url link='current'}" method="post" onsubmit="return checkValidate();">
          <div class="table">
              <div class="table_left"><span class="required">*</span>Category name:</div>
              <div class="table_right"><input type="text" name="val[title]" id="title" value="{$title}"/></div>
          </div>
               <div class="table_clear">
        <input type="submit" name="submit" value="Save Change" />
        </div>
</form>
        </td>
    </tr>
</table>

</td></tr></table>


<!--{literal}
<script type="text/javascript">

    function checkValidate()
    {
       if ( document.getElementById('price') == null)
       {
           return false;
       }
        return true;
    }
     function roundNumber(num, dec) {
        var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
        return result;
    }
    function isNumber(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
     }
</script>
{/literal}-->
           