<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php /* Cached: February 9, 2014, 5:51 am */ ?>
<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package 		Phpfox
 * @version 		$Id: country-child.html.php 982 2009-09-16 08:11:36Z Raymond_Benc $
 */
 
 

 if (! PHPFOX_IS_AJAX || $this->_aVars['bForceDiv']):  if ($this->_aVars['mCountryChildFilter'] !== null): ?>
<div><input type="hidden" name="null" id="js_country_child_is_search" value="1" /></div>
<?php endif; ?>
<div style="padding: 5px 0px 0px;" id="js_country_child_id">
<?php endif;  if (count ( $this->_aVars['aCountryChildren'] )): ?>
	<select name="<?php if ($this->_aVars['mCountryChildFilter'] === null): ?>val<?php else: ?>search<?php endif; ?>[country_child_id]" id="js_country_child_id_value">
		<option value="0"><?php echo Phpfox::getPhrase('core.state_province'); ?>:</option>
<?php if (count((array)$this->_aVars['aCountryChildren'])):  foreach ((array) $this->_aVars['aCountryChildren'] as $this->_aVars['iChildId'] => $this->_aVars['sChildValue']): ?>
		<option value="<?php echo $this->_aVars['iChildId']; ?>"<?php if ($this->_aVars['iCountryChildId'] == $this->_aVars['iChildId']): ?> selected="selected"<?php endif; ?>><?php echo $this->_aVars['sChildValue']; ?></option>
<?php endforeach; endif; ?>
	</select>
<?php else:  if (PHPFOX_IS_AJAX && $this->_aVars['iCountryChildId'] > 0): ?>
<div><input type="hidden" name="val[country_child_id]" id="js_country_child_id_value" value="<?php echo $this->_aVars['iCountryChildId']; ?>" /></div>
<?php endif;  endif;  if (! PHPFOX_IS_AJAX || $this->_aVars['bForceDiv']): ?>
</div>
<?php endif; ?>
