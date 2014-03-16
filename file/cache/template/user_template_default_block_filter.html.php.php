<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php /* Cached: March 16, 2014, 7:30 pm */ ?>
<?php
/**
 * [PHPFOX_HEADER]
 *
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package 		Phpfox
 * @version 		$Id: filter.html.php 6860 2013-11-06 20:17:19Z Fern $
 */



?>
<form method="post" action="<?php if (isset ( $this->_aVars['aCallback']['url_home'] )):  echo Phpfox::getLib('phpfox.url')->makeUrl($this->_aVars['aCallback']['url_home'], array('view' => $this->_aVars['sView']));  else:  echo Phpfox::getLib('phpfox.url')->makeUrl('user.browse', array('view' => $this->_aVars['sView']));  endif; ?>">
<?php echo '<div><input type="hidden" name="' . Phpfox::getTokenName() . '[security_token]" value="' . Phpfox::getService('log.session')->getToken() . '" /></div>';  if (isset ( $this->_aVars['aCallback']['url_home'] )): ?>
	<div><input type="hidden" name="url_home" value="<?php echo $this->_aVars['aCallback']['url_home']; ?>" /></div>
<?php endif;  if (Phpfox ::getUserParam('user.can_search_user_gender')): ?>
	<div class="p_top_4">
		<span class="user_browse_title"><?php echo Phpfox::getPhrase('user.browse_for'); ?></span>:
		<div class="p_4">
<?php echo $this->_aVars['aFilters']['gender']; ?>
		</div>
	</div>
<?php endif;  if (Phpfox ::getUserParam('user.can_search_user_age')): ?>
	<div class="p_top_4">
		<span class="user_browse_title"><?php echo Phpfox::getPhrase('user.between_ages'); ?></span>:
		<div class="p_4">
<?php echo $this->_aVars['aFilters']['from']; ?> - <?php echo $this->_aVars['aFilters']['to']; ?>
		</div>
	</div>
<?php endif; ?>
	<div class="p_top_4">
		<span class="user_browse_title"><?php echo Phpfox::getPhrase('user.located_within'); ?></span>:
		<div class="p_4">
<?php echo $this->_aVars['aFilters']['country']; ?>
<?php Phpfox::getBlock('core.country-child', array('country_child_filter' => true,'country_child_type' => 'browse')); ?>
		</div>
	</div>

	<div class="p_top_4">
		<span class="user_browse_title"><?php echo Phpfox::getPhrase('user.city'); ?></span>:
		<div class="p_4">
<?php echo $this->_aVars['aFilters']['city']; ?>
		</div>
	</div>
	
<?php if (Phpfox ::getUserParam('user.can_search_by_zip')): ?>
		<div class="p_top_4">
			<span class="user_browse_title"><?php echo Phpfox::getPhrase('user.zip_postal_code'); ?></span>:
			<div class="p_4">
<?php echo $this->_aVars['aFilters']['zip']; ?>
			</div>
		</div>
<?php endif; ?>
	
	<div class="p_top_4">
		<span class="user_browse_title"><?php echo Phpfox::getPhrase('user.keywords'); ?></span>:
		<div class="p_4">
<?php echo $this->_aVars['aFilters']['keyword']; ?>
			<div class="extra_info">
<?php echo Phpfox::getPhrase('user.within'); ?>: <?php echo $this->_aVars['aFilters']['type']; ?>
			</div>
		</div>
	</div>
		
	<div class="p_top_8">
		<input type="submit" value="<?php echo Phpfox::getPhrase('user.browse_filter_submit'); ?>" class="button" name="search[submit]" />
	</div>
	
	<ul id="js_user_browse_advanced_link">
		<li><a href="#" onclick="$('#js_user_browse_advanced').show(); return false;" id="user_browse_advanced_link"><?php echo Phpfox::getPhrase('user.advanced_filters'); ?></a></li>
<?php if (isset ( $this->_aVars['bIsInSearchMode'] ) && $this->_aVars['bIsInSearchMode']): ?>
		<li><a href="#"><a href="<?php echo Phpfox::getLib('phpfox.url')->makeUrl('user.browse'); ?>"><?php echo Phpfox::getPhrase('user.reset_browse_criteria'); ?></a></a></li>
<?php endif; ?>
	</ul>
	
	<div id="js_user_browse_advanced">
		<div class="user_browse_content">
		    
		    
	<div id="browse_custom_fields_popup_holder">
<?php if (count((array)$this->_aVars['aCustomFields'])):  $this->_aPhpfoxVars['iteration']['customfield'] = 0;  foreach ((array) $this->_aVars['aCustomFields'] as $this->_aVars['aCustomField']):  $this->_aPhpfoxVars['iteration']['customfield']++; ?>

	    <div class="go_left">

<?php if (isset ( $this->_aVars['aCustomField']['fields'] )): ?>
		<br />
		<div class="title">
<?php echo Phpfox::getPhrase($this->_aVars['aCustomField']['phrase_var_name']); ?>
		</div>
		<br />
			<?php
						Phpfox::getLib('template')->getBuiltFile('custom.block.foreachcustom');						
						?>
<?php endif; ?>
	    </div>
<?php if (is_int ( $this->_aPhpfoxVars['iteration']['customfield'] / 3 )): ?>
		<div class="clear"> </div>
<?php endif; ?>
<?php endforeach; endif; ?>
	    
	    <div class="clear"></div>
	</div>
<?php if (count ( $this->_aVars['aForms'] )): ?>
	<?php echo '
	<script type="text/javascript">
		$Behavior.user_filter_1 = function()
		{
			var iBrowseCnt = 0;
			$(\'#js_block_border_user_filter .menu li\').each(function()
			{
				iBrowseCnt++;
				if (iBrowseCnt == 1)
				{
					$(this).removeClass(\'active\');
				}
				else
				{
					$(this).addClass(\'active\');
				}
			});
		};
	</script>
	'; ?>

<?php endif; ?>
	
	<div class="p_top_4" style="border-top: 1px #DFDFDF solid;">
	    <span class="user_browse_title"><?php echo Phpfox::getPhrase('user.sort_results_by'); ?></span>:
	    <div class="p_top_4">
<?php echo $this->_aVars['aFilters']['sort']; ?> <?php echo $this->_aVars['aFilters']['sort_by']; ?>
	    </div>
	</div>	
		
	<div class="p_top_15">
	    <a href="#" id="js_user_browse_advanced_link_close" onclick="$('#js_user_browse_advanced').hide(); return false;"><?php echo Phpfox::getPhrase('user.close_advanced_filters'); ?></a>
	    <input type="submit" value="<?php echo Phpfox::getPhrase('user.browse_filter_submit'); ?>" class="button" name="search[submit]" />
	</div>	
		    
		</div>
	</div>
	
<?php if (isset ( $this->_aVars['sCountryISO'] )): ?>
		<script type="text/javascript">
			$Behavior.loadStatesAfterBrowse = function()
			{
				sCountryISO = "<?php echo $this->_aVars['sCountryISO']; ?>";
				if(sCountryISO != "")
				{
					sCountryChildId = <?php echo $this->_aVars['sCountryChildId']; ?>;
					$.ajaxCall('core.getChildren', 'country_child_filter=true&country_child_type=browse&country_iso=' + sCountryISO + '&country_child_id=' + sCountryChildId);
				}
			}
		</script>
<?php endif; ?>
	

</form>


