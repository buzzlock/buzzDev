<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php /* Cached: March 16, 2014, 7:30 pm */ ?>
<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond_Benc
 * @package 		Phpfox
 * @version 		$Id: moderation.html.php 4086 2012-04-05 12:32:32Z Raymond_Benc $
 */
 
 

?>
<form method="post" action="<?php echo Phpfox::getLib('phpfox.url')->makeUrl('current'); ?>" id="js_global_multi_form_holder">
<?php echo '<div><input type="hidden" name="' . Phpfox::getTokenName() . '[security_token]" value="' . Phpfox::getService('log.session')->getToken() . '" /></div>'; ?>
<?php if (! empty ( $this->_aVars['sCustomModerationFields'] )): ?>
<?php echo $this->_aVars['sCustomModerationFields']; ?>
<?php endif; ?>
	<div id="js_global_multi_form_ids"><?php echo $this->_aVars['sInputFields']; ?></div>
	<div class="moderation_holder">
		<a href="#" class="moderation_action moderation_action_select" rel="select"><?php echo Phpfox::getPhrase('core.select_all'); ?></a>
		<a href="#" class="moderation_action moderation_action_unselect" rel="unselect"><?php echo Phpfox::getPhrase('core.un_select_all'); ?></a>
		<span class="moderation_process"><?php echo Phpfox::getLib('phpfox.image.helper')->display(array('theme' => 'ajax/add.gif')); ?></span>
		<a href="#" class="moderation_drop<?php if (! $this->_aVars['iTotalInputFields']): ?> not_active<?php endif; ?>"><span><?php echo Phpfox::getPhrase('core.with_selected'); ?> (<strong class="js_global_multi_total"><?php echo $this->_aVars['iTotalInputFields']; ?></strong>)</span></a>		
		<ul>
			<li><a href="#" class="moderation_clear_all"><?php echo Phpfox::getPhrase('core.clear_all_selected'); ?></a></li>
<?php if (count((array)$this->_aVars['aModerationParams']['menu'])):  foreach ((array) $this->_aVars['aModerationParams']['menu'] as $this->_aVars['aModerationMenu']): ?>
			<li><a href="#<?php echo $this->_aVars['aModerationMenu']['action']; ?>" class="moderation_process_action" rel="<?php echo $this->_aVars['aModerationParams']['ajax']; ?>"><?php echo $this->_aVars['aModerationMenu']['phrase']; ?></a></li>
<?php endforeach; endif; ?>
		</ul>
	</div>

</form>

