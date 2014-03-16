<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php /* Cached: March 16, 2014, 7:30 pm */ ?>
<?php
/**
 * [PHPFOX_HEADER]
 *
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package 		Phpfox
 * @version 		$Id: entry.html.php 382 2009-04-08 15:51:16Z Raymond_Benc $
 */

?>

<?php if (isset ( $this->_aVars['aCustomField']['fields'] )): ?>
<?php if (count((array)$this->_aVars['aCustomField']['fields'])):  foreach ((array) $this->_aVars['aCustomField']['fields'] as $this->_aVars['aField']): ?>
		<div class="table">
			<div class="table_left">
<?php echo Phpfox::getPhrase($this->_aVars['aField']['phrase_var_name']); ?>:
			</div>
			<div class="table_right">
<?php if ($this->_aVars['aField']['var_type'] == 'textarea' || $this->_aVars['aField']['var_type'] == 'text'): ?>
						<input type="text" class="js_custom_search" name="custom[<?php echo $this->_aVars['aField']['field_id']; ?>]" value="<?php $aParams = (isset($aParams) ? $aParams : Phpfox::getLib('phpfox.request')->getArray('val')); echo (isset($aParams[''.$this->_aVars['aField']['field_id'].'']) ? Phpfox::getLib('phpfox.parse.output')->clean($aParams[''.$this->_aVars['aField']['field_id'].'']) : (isset($this->_aVars['aForms'][''.$this->_aVars['aField']['field_id'].'']) ? Phpfox::getLib('phpfox.parse.output')->clean($this->_aVars['aForms'][''.$this->_aVars['aField']['field_id'].'']) : '')); ?>
" size="25" />
<?php elseif ($this->_aVars['aField']['var_type'] == 'select'): ?>
					 <!-- custom input type select -->
						<select name="custom[<?php echo $this->_aVars['aField']['field_id']; ?>]" class="js_custom_search">
							<option value=""><?php echo Phpfox::getPhrase('user.any'); ?></option>
<?php if (count((array)$this->_aVars['aField']['options'])):  foreach ((array) $this->_aVars['aField']['options'] as $this->_aVars['aOption']): ?>
							<option value="<?php echo $this->_aVars['aOption']['option_id']; ?>"<?php $aParams = (isset($aParams) ? $aParams : Phpfox::getLib('phpfox.request')->getArray('val'));


if (isset($this->_aVars['aField']) && isset($this->_aVars['aForms'][$this->_aVars['aField']['field_id']]) && !is_array($this->_aVars['aForms'][$this->_aVars['aField']['field_id']]))
							{
								$this->_aVars['aForms'][$this->_aVars['aField']['field_id']] = array($this->_aVars['aForms'][$this->_aVars['aField']['field_id']]);
							}

if (isset($this->_aVars['aForms'][''.$this->_aVars['aField']['field_id'].''])
 && is_numeric(''.$this->_aVars['aOption']['option_id'].'') && in_array(''.$this->_aVars['aOption']['option_id'].'', $this->_aVars['aForms'][''.$this->_aVars['aField']['field_id'].'']))
							
{
								echo ' selected="selected" ';
							}

							if (isset($aParams[''.$this->_aVars['aOption']['option_id'].''])
								&& $aParams[''.$this->_aVars['aOption']['option_id'].''] == $this->_aVars['aOption']['option_id'])

							{

								echo ' selected="selected" ';

							}

							else

							{

								if (isset($this->_aVars['aForms'][''.$this->_aVars['aOption']['option_id'].''])
									&& !isset($aParams[''.$this->_aVars['aOption']['option_id'].''])
									&& $this->_aVars['aForms'][''.$this->_aVars['aOption']['option_id'].''] == $this->_aVars['aOption']['option_id'])
								{
								 echo ' selected="selected" ';
								}
								else
								{
									echo "";
								}
							}
							?>
><?php echo Phpfox::getPhrase($this->_aVars['aOption']['phrase_var_name']); ?></option>
<?php endforeach; endif; ?>
						</select>
<?php elseif ($this->_aVars['aField']['var_type'] == 'multiselect'): ?>
						<!-- custom input type multi select -->
						<select name="custom[<?php echo $this->_aVars['aField']['field_id']; ?>][]" multiple class="js_custom_search" >
							<option value=""><?php echo Phpfox::getPhrase('user.any'); ?></option>
<?php if (count((array)$this->_aVars['aField']['options'])):  foreach ((array) $this->_aVars['aField']['options'] as $this->_aVars['aOption']): ?>
									<option value="<?php echo $this->_aVars['aOption']['option_id']; ?>"<?php $aParams = (isset($aParams) ? $aParams : Phpfox::getLib('phpfox.request')->getArray('val'));


if (isset($this->_aVars['aField']) && isset($this->_aVars['aForms'][$this->_aVars['aField']['field_id']]) && !is_array($this->_aVars['aForms'][$this->_aVars['aField']['field_id']]))
							{
								$this->_aVars['aForms'][$this->_aVars['aField']['field_id']] = array($this->_aVars['aForms'][$this->_aVars['aField']['field_id']]);
							}

if (isset($this->_aVars['aForms'][''.$this->_aVars['aField']['field_id'].''])
 && is_numeric(''.$this->_aVars['aOption']['option_id'].'') && in_array(''.$this->_aVars['aOption']['option_id'].'', $this->_aVars['aForms'][''.$this->_aVars['aField']['field_id'].'']))
							
{
								echo ' selected="selected" ';
							}

							if (isset($aParams[''.$this->_aVars['aOption']['option_id'].''])
								&& $aParams[''.$this->_aVars['aOption']['option_id'].''] == $this->_aVars['aOption']['option_id'])

							{

								echo ' selected="selected" ';

							}

							else

							{

								if (isset($this->_aVars['aForms'][''.$this->_aVars['aOption']['option_id'].''])
									&& !isset($aParams[''.$this->_aVars['aOption']['option_id'].''])
									&& $this->_aVars['aForms'][''.$this->_aVars['aOption']['option_id'].''] == $this->_aVars['aOption']['option_id'])
								{
								 echo ' selected="selected" ';
								}
								else
								{
									echo "";
								}
							}
							?>
><?php echo Phpfox::getPhrase($this->_aVars['aOption']['phrase_var_name']); ?></option>
<?php endforeach; endif; ?>
						</select>
<?php elseif ($this->_aVars['aField']['var_type'] == 'radio'): ?>
					
<?php if (count((array)$this->_aVars['aField']['options'])):  foreach ((array) $this->_aVars['aField']['options'] as $this->_aVars['aOption']): ?>
				<input type="radio" name="custom[<?php echo $this->_aVars['aField']['field_id']; ?>]" value="<?php echo $this->_aVars['aOption']['option_id']; ?>"<?php $aParams = (isset($aParams) ? $aParams : Phpfox::getLib('phpfox.request')->getArray('val'));
if (isset($this->_aVars['aForms']) && is_numeric(''.$this->_aVars['aOption']['option_id'].'') && in_array(''.$this->_aVars['aOption']['option_id'].'', $this->_aVars['aForms']) ){echo ' checked="checked"';}
if ((isset($aParams[''.$this->_aVars['aOption']['option_id'].'']) && $aParams[''.$this->_aVars['aOption']['option_id'].''] == ''.$this->_aVars['aOption']['option_id'].''))
{echo ' checked="checked" ';}
else
{
 if (isset($this->_aVars['aForms']) && isset($this->_aVars['aForms'][''.$this->_aVars['aOption']['option_id'].'']) && !isset($aParams[''.$this->_aVars['aOption']['option_id'].'']) && $this->_aVars['aForms'][''.$this->_aVars['aOption']['option_id'].''] == ''.$this->_aVars['aOption']['option_id'].'')
 {
    echo ' checked="checked" ';}
 else
 {
 }
}
?> 
 class="js_custom_search"><?php echo Phpfox::getPhrase($this->_aVars['aOption']['phrase_var_name']); ?> <br />
<?php endforeach; endif; ?>
<?php elseif ($this->_aVars['aField']['var_type'] == 'checkbox'): ?>
<?php if (count((array)$this->_aVars['aField']['options'])):  foreach ((array) $this->_aVars['aField']['options'] as $this->_aVars['aOption']): ?>
				<input type="checkbox" name="custom[<?php echo $this->_aVars['aField']['field_id']; ?>][<?php echo $this->_aVars['aOption']['option_id']; ?>]" value="<?php echo $this->_aVars['aOption']['option_id']; ?>"<?php $aParams = (isset($aParams) ? $aParams : Phpfox::getLib('phpfox.request')->getArray('val'));


if (isset($this->_aVars['aField']) && isset($this->_aVars['aForms'][$this->_aVars['aField']['field_id']]) && !is_array($this->_aVars['aForms'][$this->_aVars['aField']['field_id']]))
							{
								$this->_aVars['aForms'][$this->_aVars['aField']['field_id']] = array($this->_aVars['aForms'][$this->_aVars['aField']['field_id']]);
							}

if (isset($this->_aVars['aForms'][''.$this->_aVars['aField']['field_id'].''])
 && is_numeric(''.$this->_aVars['aOption']['option_id'].'') && in_array(''.$this->_aVars['aOption']['option_id'].'', $this->_aVars['aForms'][''.$this->_aVars['aField']['field_id'].'']))
							
{
								echo ' checked="checked" ';
							}

							if (isset($aParams[''.$this->_aVars['aOption']['option_id'].''])
								&& $aParams[''.$this->_aVars['aOption']['option_id'].''] == $this->_aVars['aOption']['option_id'])

							{

								echo ' checked="checked" ';

							}

							else

							{

								if (isset($this->_aVars['aForms'][''.$this->_aVars['aOption']['option_id'].''])
									&& !isset($aParams[''.$this->_aVars['aOption']['option_id'].''])
									&& $this->_aVars['aForms'][''.$this->_aVars['aOption']['option_id'].''] == $this->_aVars['aOption']['option_id'])
								{
								 echo ' checked="checked" ';
								}
								else
								{
									echo "";
								}
							}
							?>
 class="js_custom_search v_middle"> <?php echo Phpfox::getPhrase($this->_aVars['aOption']['phrase_var_name']); ?> <br />
<?php endforeach; endif; ?>
<?php endif; ?>
			</div>
			<div class="clear"></div>
		</div>
<?php endforeach; endif;  endif; ?>
