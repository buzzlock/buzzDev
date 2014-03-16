<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php /* Cached: March 16, 2014, 3:53 pm */ ?>
<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package  		Module_Admincp
 * @version 		$Id: index.html.php 2831 2011-08-12 19:44:19Z Raymond_Benc $
 */
 
 

?>

<h3><?php echo Phpfox::getPhrase('admincp.global_settings'); ?></h3>
<?php if (count((array)$this->_aVars['aGroups'])):  $this->_aPhpfoxVars['iteration']['group'] = 0;  foreach ((array) $this->_aVars['aGroups'] as $this->_aVars['aGroup']):  $this->_aPhpfoxVars['iteration']['group']++; ?>

<div class="go_left p_4" style="width:30%;">
	<a href="<?php echo Phpfox::getLib('phpfox.url')->makeUrl("admincp.setting.edit", array('group-id' => "".$this->_aVars['aGroup']['group_id']."")); ?>"><?php echo $this->_aVars['aGroup']['var_name']; ?></a><?php if (PHPFOX_DEBUG): ?> (<?php echo $this->_aVars['aGroup']['total_settings']; ?>)<?php endif; ?>
	
</div>
<?php if (is_int ( $this->_aPhpfoxVars['iteration']['group'] / 3 )): ?>
<br class="clear" />
<?php endif;  endforeach; endif; ?>
<div class="clear"></div>

<h3><?php echo Phpfox::getPhrase('admincp.module_settings'); ?></h3>
<?php if (count((array)$this->_aVars['aModules'])):  $this->_aPhpfoxVars['iteration']['module'] = 0;  foreach ((array) $this->_aVars['aModules'] as $this->_aVars['aModule']):  $this->_aPhpfoxVars['iteration']['module']++; ?>

<div class="go_left p_4" style="width:30%;">
	<a href="<?php echo Phpfox::getLib('phpfox.url')->makeUrl("admincp.setting.edit", array('module-id' => "".$this->_aVars['aModule']['module_id']."")); ?>"><?php echo Phpfox::getLib('phpfox.locale')->translate($this->_aVars['aModule']['module_id'], 'module'); ?></a><?php if (PHPFOX_DEBUG): ?> (<?php echo $this->_aVars['aModule']['total_settings']; ?>)<?php endif; ?>
	
</div>
<?php if (is_int ( $this->_aPhpfoxVars['iteration']['module'] / 3 )): ?>
<br class="clear" />
<?php endif;  endforeach; endif; ?>

<div class="clear"></div>
<?php if (count ( $this->_aVars['aProductGroups'] )): ?>
<h3><?php echo Phpfox::getPhrase('admincp.product_settings'); ?></h3>
<?php if (count((array)$this->_aVars['aProductGroups'])):  $this->_aPhpfoxVars['iteration']['product'] = 0;  foreach ((array) $this->_aVars['aProductGroups'] as $this->_aVars['aProductGroup']):  $this->_aPhpfoxVars['iteration']['product']++; ?>

<div class="go_left p_4" style="width:30%;">
	<a href="<?php echo Phpfox::getLib('phpfox.url')->makeUrl("admincp.setting.edit", array('product-id' => "".$this->_aVars['aProductGroup']['product_id']."")); ?>"><?php echo $this->_aVars['aProductGroup']['var_name']; ?></a><?php if (PHPFOX_DEBUG): ?> (<?php echo $this->_aVars['aProductGroup']['total_settings']; ?>)<?php endif; ?>
</div>
<?php if (is_int ( $this->_aPhpfoxVars['iteration']['product'] / 3 )): ?>
<br class="clear" />
<?php endif;  endforeach; endif; ?>
<div class="clear"></div>
<?php endif; ?>
