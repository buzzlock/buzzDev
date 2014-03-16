<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php /* Cached: March 16, 2014, 7:24 pm */ ?>
<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond_Benc
 * @package 		Phpfox
 * @version 		$Id: latest.html.php 5155 2013-01-17 12:55:36Z Miguel_Espinoza $
 */
 
 

 if (count ( $this->_aVars['aMessages'] )): ?>
<ul id="js_new_message_holder_drop">
<?php if (count((array)$this->_aVars['aMessages'])):  $this->_aPhpfoxVars['iteration']['messages'] = 0;  foreach ((array) $this->_aVars['aMessages'] as $this->_aVars['aMessage']):  $this->_aPhpfoxVars['iteration']['messages']++; ?>

	<li id="js_mail_read_<?php if (Phpfox ::getParam('mail.threaded_mail_conversation')):  echo $this->_aVars['aMessage']['thread_id'];  else:  echo $this->_aVars['aMessage']['mail_id'];  endif; ?>" class="holder_notify_drop_data<?php if ($this->_aPhpfoxVars['iteration']['messages'] == 1): ?> first<?php endif; ?>"><a href="<?php if (Phpfox ::getParam('mail.threaded_mail_conversation')):  echo Phpfox::getLib('phpfox.url')->makeUrl('mail.thread', array('id' => $this->_aVars['aMessage']['thread_id']));  else:  echo Phpfox::getLib('phpfox.url')->makeUrl('mail.view.'.$this->_aVars['aMessage']['mail_id'].'');  endif; ?>" title="<?php echo Phpfox::getLib('phpfox.parse.output')->clean($this->_aVars['aMessage']['preview']); ?>" class="main_link<?php if ($this->_aVars['aMessage']['viewer_is_new']): ?> is_new<?php endif; ?>">
			<div class="drop_data_image">
<?php if (! empty ( $this->_aVars['aMessage']['user_id'] )): ?>
<?php echo Phpfox::getLib('phpfox.image.helper')->display(array('user' => $this->_aVars['aMessage'],'max_width' => '50','max_height' => '50','suffix' => '_50_square','no_link' => true)); ?>
<?php endif; ?>
			</div>
			<div class="drop_data_content">
				<div class="drop_data_user">
<?php if (empty ( $this->_aVars['aMessage']['user_id'] )): ?>
<?php echo Phpfox::getParam('core.site_title'); ?>
<?php else: ?>
<?php echo Phpfox::getLib('phpfox.parse.output')->clean($this->_aVars['aMessage']['full_name']); ?>
<?php endif; ?>
				</div>
<?php if (Phpfox ::getParam('mail.threaded_mail_conversation')): ?>
<?php echo Phpfox::getLib('phpfox.parse.bbcode')->cleanCode(Phpfox::getLib('phpfox.parse.output')->shorten(Phpfox::getLib('phpfox.parse.output')->clean($this->_aVars['aMessage']['preview']), 40, '...')); ?>
<?php else: ?>
<?php echo Phpfox::getLib('phpfox.parse.output')->clean($this->_aVars['aMessage']['subject']); ?>
<?php endif; ?>
				<div class="drop_data_time">
<?php echo Phpfox::getLib('date')->convertTime($this->_aVars['aMessage']['time_stamp']); ?>
				</div>
			</div>
			<div class="clear"></div>
		</a>
	</li>
<?php endforeach; endif; ?>
</ul>
<?php if (Phpfox ::getParam('mail.update_message_notification_preview')):  echo '
<script type="text/javascript">	
	var $iTotalMessages = parseInt($(\'#js_total_new_messages\').html());
	var $iNewTotalMessages = 0;
	$(\'#js_new_message_holder_drop li\').each(function()
	{
		$iNewTotalMessages++;
		$aMailOldHistory[$(this).attr(\'id\').replace(\'js_mail_read_\', \'\')] = true;		
	});
	
	$iTotalMessages = parseInt(($iTotalMessages - $iNewTotalMessages));
	if ($iTotalMessages < 0)
	{
		$iTotalMessages = 0;
	}
	
	if ($iTotalMessages === 0)
	{
		$(\'#js_total_new_messages\').html(\'\').hide();	
	}
	else
	{
		$(\'#js_total_new_messages\').html($iTotalMessages);
	}	
</script>
'; ?>

<?php endif;  else: ?>
<div class="drop_data_empty">
<?php echo Phpfox::getPhrase('mail.no_new_messages'); ?>
</div>
<?php endif; ?>
<a href="<?php echo Phpfox::getLib('phpfox.url')->makeUrl('mail'); ?>" class="holder_notify_drop_link"><?php echo Phpfox::getPhrase('mail.see_all_messages'); ?></a>
