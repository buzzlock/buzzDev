<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php /* Cached: March 8, 2014, 5:09 pm */ ?>
<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package  		Module_Admincp
 * @version 		$Id: cache.html.php 5332 2013-02-11 08:27:54Z Raymond_Benc $
 */
 
 

 if ($this->_aVars['bCacheLocked']): ?>
<div class="error_message">
<?php echo Phpfox::getPhrase('admincp.cache_system_is_locked'); ?>
</div>
<div class="extra_info">
<?php echo Phpfox::getPhrase('admincp.the_cache_system_is_locked_during_an_operation_that_requires_all_cache_files_to_be_kept_in_place', array('link' => $this->_aVars['sUnlockCache'])); ?>
</div>
<?php else:  if ($this->_aVars['iCacheCnt'] > 0):  if (! defined ( 'PHPFOX_IS_HOSTED_SCRIPT' )): ?>
<div class="table_header">
<?php echo Phpfox::getPhrase('admincp.cache_stats'); ?>
</div>
<div class="table">
	<div class="table_left">
<?php echo Phpfox::getPhrase('admincp.total_files'); ?>:
	</div>
	<div class="table_right">
<?php echo $this->_aVars['aStats']['total']; ?>
	</div>
	<div class="clear"></div>
</div>
<div class="table">
	<div class="table_left">
<?php echo Phpfox::getPhrase('admincp.cache_size'); ?>:
	</div>
	<div class="table_right">
<?php echo Phpfox::getLib('phpfox.file')->filesize($this->_aVars['aStats']['size']); ?>
	</div>
	<div class="clear"></div>
</div>
<?php if (isset ( $this->_aVars['aStats']['last'] )): ?>
<div class="table">
	<div class="table_left">
<?php echo Phpfox::getPhrase('admincp.last_cached_file'); ?>:
	</div>
	<div class="table_right">
<?php echo Phpfox::getTime(Phpfox::getParam('admincp.cache_time_stamp'), $this->_aVars['aStats']['last']); ?>
	</div>
	<div class="clear"></div>
</div>
<?php endif;  endif; ?>
<div class="<?php if (! defined ( 'PHPFOX_IS_HOSTED_SCRIPT' )): ?>table_clear<?php else: ?>t_center<?php endif; ?>">
	<input type="button" value="<?php echo Phpfox::getPhrase('admincp.clear_all'); ?>" class="button" onclick="window.location.href = '<?php echo Phpfox::getLib('phpfox.url')->makeUrl('admincp.maintain.cache', array('all' => true)); ?>';" />
</div>
<?php else: ?>
<div class="message">
<?php echo Phpfox::getPhrase('admincp.no_cache_date_found'); ?>
</div>
<?php endif;  endif; ?>
