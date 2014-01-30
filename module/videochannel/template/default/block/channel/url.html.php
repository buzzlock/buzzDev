<div>
   
        <div class="table_left" >
            {phrase var='videochannel.channel_url'}
        </div>
        
        <div class="table_right">
            <input type="text" onfocus="$('#channel_url_error').hide()" name="val[url]" id="channel_url" style="width: 40%; vertical-align: middle" size="40">
            <input type="button" class="button" value="{phrase var='videochannel.add'}" style="vertical-align: middle" onclick="return addChannelUrl('{$sModule}', {$iItem});" >
			<div id="channel_url_error" class="error_message" style="width: 60%; display: none">{phrase var='videochannel.enter_url_to_add_channel'}</div>	
        </div>
    
</div>