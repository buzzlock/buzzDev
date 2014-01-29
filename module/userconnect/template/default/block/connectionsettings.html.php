<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>

<div style="padding-bottom: 10px;" class="uscn_settings">
    <span class="connectionsettings">{phrase var='userconnect.visibility_in_connection_paths'}</span>
    <div class="options">
        <input type="radio" value="1" {if $settings.showconnectionpath==1}checked{/if} name="connection_radio"/>{phrase var='userconnect.yes_show_my_connection_path'}
        <br/><input type="radio" value="0" {if $settings.showconnectionpath==0}checked{/if}  name="connection_radio"/>{phrase var='userconnect.no_hide_my_connection_path'}
    </div>
    <div style="float: right;margin-right: 10px;">
        <input type="button" class="button" value="{phrase var="userconnect.submit"}" onclick="submit_connectionsettings();"/>
    </div>
</div>

{literal}
<script type="text/javascript">
    function submit_connectionsettings()
    {
        var a=document.getElementsByName('connection_radio');
        for(var i=0;i<a.length;i++)
        {
            if(a[i].checked==true)
            {
                  var value=a[i].value;
                  $.ajaxCall("userconnect.updateconnectionsettings",'value='+value);
                  
            }
        }
        alert($('<div/>').html("{/literal}{phrase var='userconnect.you_have_updated_my_connection_settings_successfully'}{literal}").text());
    }
</script>
{/literal}