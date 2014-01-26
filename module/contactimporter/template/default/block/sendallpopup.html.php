<form id="submit-form" action="" method="post" class="yncontact_form_sendall">
    <input id="provider" name="provider" type="hidden" value="{$provider}" />	
    <input id="friends_count" name="friends_count" type="hidden" value="{$friends_count}" />	
    <div style="padding:10px;">		
        {phrase var='contactimporter.message_sendall'}		
    </div>
    <div class="yncontact_close_sending">
        <input type="button" class="button" value="{phrase var='contactimporter.agree'}" id="agree-button" />
        <input type="button" class="button" value="{phrase var='contactimporter.close'}" id="close-button" />
    </div>
    <div class="clear"></div>
</form>
{literal}<script language="javascript" type="text/javascript">
    var url = "{/literal}{$sMainUrl}{literal}";
    $Behavior.initJs = function() {	
        var provider = "{/literal}{$provider}{literal}";
        var friends_count = "{/literal}{$friends_count}{literal}";
        var message = $('#message').val();    
        var popup = $('#submit-form').closest(".js_box");
        var btDiv = popup.find(".js_box_close");	
        setTimeout(function(){btDiv.hide();}, 1);
        $('#close-button').click(function(evt) {   
            tb_remove();
            return false;
        });
        $('#agree-button').click(function(evt) {   
            $Core.ajax('contactimporter.addQueue',
            {
                params:
                    {				
                    provider: provider,
                    friends_count: friends_count,
                    message: message
                },
                type: 'POST',
                success: function(response)
                {			
                    location.href = url;
                    tb_remove();                
                }
            });
        });
    }	
</script>{/literal}