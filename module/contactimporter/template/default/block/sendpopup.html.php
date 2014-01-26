<form id="submit-form" action="" method="post" class="yncontact_form_send">    
	{if $sNoticeQuota}
	<div class="extra_info" >
		{$sNoticeQuota}
	</div>
	{/if}
    <div style="padding:10px;" id="sending">
        <ul>
            <li style="font-weight:bold;">{phrase var='contactimporter.sending'} (<span id="totalsent">0</span>/<span id="total">{$iTotal}</span>) 
            	<img class="yncontact_sending" src="{$sCorePath}module/contactimporter/static/image/add.gif"/>
            </li>
			 <li style="font-weight:bold;">{phrase var='contactimporter.successed'}  <span id="successed">0</span></li>
			 <li style="font-weight:bold;">{phrase var='contactimporter.failed'}  <span id="failed">0</span></li>
            <li>
                <div id="progressbar">
                    <div id="percent"></div>
                </div>
            </li>
        </ul>
    </div>
    <div class="yncontact_close_sending">
        <input type="button" class="button" value="{phrase var='contactimporter.close'}" id="close-button" />
    </div>
    <div class="clear"></div>
</form>
{literal}<script language="javascript" type="text/javascript">
    var url = "{/literal}{$sMainUrl}{literal}";
    var total = "{/literal}{$iTotal}{literal}";
    var provider = "{/literal}{$sProvider}{literal}";
    var message = $('#message').val();    
	var fail = 0;
    if($.trim(message).length > 0)
    {
        $Behavior.initJs = function() {	    
            var popup = $('#submit-form').closest(".js_box");
            var btDiv = popup.find(".js_box_close");	
            var message = $('#message').val();
            var contacts = yn_GetSelectedContacts();
            if(provider == "csv"){
                if (contacts instanceof Array){
                    
                }
                else
                {
                    contacts = contacts.split(',');
                }
            }
            
            contacts = contacts.slice(0, total);
          
            $('#close-button').click(function(evt) {   
                tb_remove();
                location.href = url;
                return false;
            });
            setTimeout(function(){btDiv.hide();}, 1);
            if(total == 0)    
            {
                setTimeout(function(){location.href = url ;}, 3000);
            }
            else
            {
                sendInvite(contacts, fail);
            }
        };
        sendInvite = function(contacts, fail) {
            $Core.ajax('contactimporter.sendInvite',
            {
                params:
                    {            
                    provider: provider,                
                    message: message,                
                    total: total,                
                    contacts: contacts,
                    fail: fail
                },
                type: 'POST',
                success: function(response)
                {
                    var response = $.parseJSON(response);
                    var percent = response['percent'];
                    var success = response['success'];
                    var contacts = response['contacts'];
                    var totalsent = response['totalsent'];
                    var fail = response['fail'];
                    var error = response['error'];
                    $("#totalsent").html(totalsent);
                    $("#successed").html(success);
                    $("#failed").html(fail);
                    $("#percent").css('width', percent);
                    if (success + fail < total) {
                        sendInvite(contacts, fail);
                    } else {
                        setTimeout(function(){location.href = url + 'success_' + success + '/fail_' + fail ;}, 3000);    
                    }            
                }
            });
        }
    }
    else{
        $('#close-button').remove();
        $("#sending").html('<div class="error_message">{/literal}{$sEmptyMsg}{literal}</div>');        
    }
</script>{/literal}