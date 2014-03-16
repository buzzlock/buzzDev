<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php /* Cached: March 9, 2014, 5:47 am */ ?>
<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package 		Phpfox
 * @version 		$Id: $
 */
 
 

?>
<div id="admincp_login">
	<form method="post" action="<?php echo Phpfox::getLib('phpfox.url')->makeUrl('current'); ?>">
<?php echo '<div><input type="hidden" name="' . Phpfox::getTokenName() . '[security_token]" value="' . Phpfox::getService('log.session')->getToken() . '" /></div>'; ?>
		<div class="adminp_login_body">
			<div class="table_header">
<?php echo Phpfox::getPhrase('admincp.admincp_login'); ?>
			</div>
<?php if (!$this->bIsSample):  $this->getLayout('error');  endif; ?>
			<div class="table">
				<div class="table_left">
<?php echo Phpfox::getPhrase('admincp.email'); ?>:
				</div>
				<div class="table_right">
					<input id="admincp_login_email" type="text" name="val[email]" value="<?php $aParams = (isset($aParams) ? $aParams : Phpfox::getLib('phpfox.request')->getArray('val')); echo (isset($aParams['email']) ? Phpfox::getLib('phpfox.parse.output')->clean($aParams['email']) : (isset($this->_aVars['aForms']['email']) ? Phpfox::getLib('phpfox.parse.output')->clean($this->_aVars['aForms']['email']) : '')); ?>
" size="40" />
				</div>
				<div class="clear"></div>
			</div>
			<div class="table">
				<div class="table_left">
<?php echo Phpfox::getPhrase('admincp.password'); ?>:
				</div>
				<div class="table_right">
					<input type="password" name="val[password]" value="<?php $aParams = (isset($aParams) ? $aParams : Phpfox::getLib('phpfox.request')->getArray('val')); echo (isset($aParams['password']) ? Phpfox::getLib('phpfox.parse.output')->clean($aParams['password']) : (isset($this->_aVars['aForms']['password']) ? Phpfox::getLib('phpfox.parse.output')->clean($this->_aVars['aForms']['password']) : '')); ?>
" size="40" />
				</div>
				<div class="clear"></div>
			</div>			
			<div class="table_clear">
				<input id="admincp_btn_login" type="submit" value="<?php echo Phpfox::getPhrase('admincp.login'); ?>" class="button" />
				<div id="admincp_site_link">
					<a href="<?php echo Phpfox::getLib('phpfox.url')->makeUrl(''); ?>"><?php echo Phpfox::getPhrase('admincp.back_to_site'); ?></a>
				</div>                                                                                            				
			</div>			
		</div>
	
</form>

</div>
