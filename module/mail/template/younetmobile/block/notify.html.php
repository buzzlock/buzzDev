<li>
	<span class="holder_notify_count" id="js_total_new_messages">0</span>
	<a href="#" title="{phrase var='mail.messages_notify'}" class="message notify_drop_link" rel="mobiletemplate.getLatest">{phrase var='mail.messages_notify'}</a>
	<div class="holder_notify_drop">
		<div class="holder_notify_drop_content">
			<div class="holder_notify_drop_title">
				{if Phpfox::isModule('friend')}
				<div class="holder_notify_drop_title_link">
					<span onClick="location.href='{url link='mail.compose'}'"><div class="compose_message"></div></span>
				</div>
				{/if}
				{phrase var='mail.messages_notify'}			
			</div>
			<div class="holder_notify_drop_data">
				<div class="holder_notify_drop_loader">{img theme='ajax/add.gif'}</div>													
			</div>
		</div>											
	</div>
</li>