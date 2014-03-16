<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php /* Cached: March 16, 2014, 3:54 pm */ ?>
<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package  		Module_Admincp
 * @version 		$Id: edit.html.php 4597 2012-08-17 07:27:46Z Raymond_Benc $
 */
 
 

 if (count ( $this->_aVars['aSettings'] )): ?>
<div class="p_4 t_right">
<?php echo Phpfox::getPhrase('admincp.quick_jump'); ?>:
<select name="jump" class="goJump">
		<option value="">Select</option>
		<optgroup label="<?php echo Phpfox::getPhrase('admincp.global_settings'); ?>">
<?php if (count((array)$this->_aVars['aGroups'])):  foreach ((array) $this->_aVars['aGroups'] as $this->_aVars['aGroup']): ?>
				<option value="<?php echo Phpfox::getLib('phpfox.url')->makeUrl("admincp.setting.edit", array('group-id' => "".$this->_aVars['aGroup']['group_id']."")); ?>"<?php if ($this->_aVars['sSettingTitle'] == $this->_aVars['aGroup']['var_name']): ?> selected="selected"<?php endif; ?>><?php echo $this->_aVars['aGroup']['var_name']; ?></option>
<?php endforeach; endif; ?>
		</optgroup>
		<optgroup label="<?php echo Phpfox::getPhrase('admincp.module_settings'); ?>">
<?php if (count((array)$this->_aVars['aModules'])):  foreach ((array) $this->_aVars['aModules'] as $this->_aVars['aModule']): ?>
				<option value="<?php echo Phpfox::getLib('phpfox.url')->makeUrl("admincp.setting.edit", array('module-id' => "".$this->_aVars['aModule']['module_id']."")); ?>"<?php if ($this->_aVars['sSettingTitle'] == $this->_aVars['aModule']['module_id']): ?> selected="selected"<?php endif; ?>><?php echo $this->_aVars['aModule']['module_id']; ?></option>
<?php endforeach; endif; ?>
		</optgroup>		
<?php if (count ( $this->_aVars['aProductGroups'] )): ?>
		<optgroup label="<?php echo Phpfox::getPhrase('admincp.product_settings'); ?>">
<?php if (count((array)$this->_aVars['aProductGroups'])):  foreach ((array) $this->_aVars['aProductGroups'] as $this->_aVars['aProductGroup']): ?>
				<option value="<?php echo Phpfox::getLib('phpfox.url')->makeUrl("admincp.setting.edit", array('product-id' => "".$this->_aVars['aProductGroup']['product_id']."")); ?>"<?php if (strtolower ( $this->_aVars['sSettingTitle'] ) == $this->_aVars['aProductGroup']['product_id']): ?> selected="selected"<?php endif; ?>><?php echo $this->_aVars['aProductGroup']['var_name']; ?></option>
<?php endforeach; endif; ?>
		</optgroup>		
<?php endif; ?>
</select>
</div>
<?php echo '
<script type="text/javascript">
<!--
function addInput(oObj, sVarName)
{
'; ?>

	var sValue = $(oObj).parents('.js_array_holder:first').find('.js_add_to_array').val();
	var iCnt = (parseInt($(oObj).parents('.js_array_holder:first').find('#js_array_count').html()) + 1);
	$(oObj).parents('.js_array_holder:first').find('.js_array_data').append('<div class="p_4" id="js_array' + iCnt + '"><input type="text" name="val[value][' + sVarName + '][]" value="' + sValue + '" size="30" /> - <a href="#" onclick="$(this).parent().remove(); return false;"><?php echo Phpfox::getPhrase('admincp.remove', array('phpfox_squote' => true)); ?></a></div>');
	$(oObj).parents('.js_array_holder:first').find('.js_array_count').html(iCnt);
	$(oObj).parents('.js_array_holder:first').find('.js_add_to_array').val('').focus();
	
	return false;
<?php echo '
}
-->
</script>
'; ?>

<form method="post" action="<?php echo Phpfox::getLib('phpfox.url')->makeUrl('current'); ?>" enctype="multipart/form-data">
<?php echo '<div><input type="hidden" name="' . Phpfox::getTokenName() . '[security_token]" value="' . Phpfox::getService('log.session')->getToken() . '" /></div>';  if (count((array)$this->_aVars['aSettings'])):  foreach ((array) $this->_aVars['aSettings'] as $this->_aVars['aSetting']): ?>
<div id="<?php echo $this->_aVars['aSetting']['var_name']; ?>"></div>
<div class="table_header2">
<?php if (PHPFOX_DEBUG): ?><div class="go_left"> <input type="text" name="val[order][<?php echo $this->_aVars['aSetting']['var_name']; ?>]" value="<?php echo $this->_aVars['aSetting']['ordering']; ?>" style="font-size:9pt; padding:0px; text-align:center;" onclick="this.select();" size="2" /> <?php endif; ?> <a name="#<?php echo $this->_aVars['aSetting']['var_name']; ?>" style="color:#333333"><?php echo $this->_aVars['aSetting']['setting_title']; ?></a><?php if (PHPFOX_DEBUG): ?></div><div class="t_right"><?php if (isset ( $this->_aVars['aSetting']['group_title'] )): ?> (<?php echo $this->_aVars['aSetting']['group_title']; ?>) <?php endif; ?><input type="text" name="param<?php echo $this->_aVars['aSetting']['var_name']; ?>" value="<?php echo $this->_aVars['aSetting']['module_id']; ?>.<?php echo $this->_aVars['aSetting']['var_name']; ?>" style="font-size:9pt; padding:0px;" onclick="this.select();" /></div><?php endif; ?>
</div>
<div class="table3">
	<div class="row_left">
<?php echo $this->_aVars['aSetting']['setting_info']; ?>
	</div>
	<div class="row_right" style="margin-bottom:20px;">
<?php if ($this->_aVars['aSetting']['type_id'] == 'multi_text'): ?>
<?php if (count((array)$this->_aVars['aSetting']['values'])):  foreach ((array) $this->_aVars['aSetting']['values'] as $this->_aVars['mKey'] => $this->_aVars['sDropValue']): ?>
		<div class="p_4">
<?php echo $this->_aVars['mKey']; ?>: <input type="text" name="val[value][<?php echo $this->_aVars['aSetting']['var_name']; ?>][<?php echo $this->_aVars['mKey']; ?>]" value="<?php echo Phpfox::getLib('phpfox.parse.output')->clean($this->_aVars['sDropValue']); ?>" size="8" />
		</div>
<?php endforeach; endif; ?>
<?php elseif ($this->_aVars['aSetting']['type_id'] == 'large_string'): ?>
		<textarea cols="60" rows="8" name="val[value][<?php echo $this->_aVars['aSetting']['var_name']; ?>]"><?php echo Phpfox::getLib('parse.output')->htmlspecialchars($this->_aVars['aSetting']['value_actual']); ?></textarea>
<?php elseif (( $this->_aVars['aSetting']['type_id'] == 'string' )): ?>
		<div><input type="text" name="val[value][<?php echo $this->_aVars['aSetting']['var_name']; ?>]" value="<?php echo Phpfox::getLib('phpfox.parse.output')->clean($this->_aVars['aSetting']['value_actual']); ?>" size="40" /></div>		
<?php elseif (( $this->_aVars['aSetting']['type_id'] == 'password' )): ?>
		<div><input type="password" name="val[value][<?php echo $this->_aVars['aSetting']['var_name']; ?>]" value="<?php echo $this->_aVars['aSetting']['value_actual']; ?>" size="40" /></div>				
<?php elseif (( $this->_aVars['aSetting']['type_id'] == 'drop' )): ?>
		<div><input type="hidden" name="val[value][<?php echo $this->_aVars['aSetting']['var_name']; ?>][real]" value="<?php echo $this->_aVars['aSetting']['value_actual']; ?>" size="40" /></div>
		<select name="val[value][<?php echo $this->_aVars['aSetting']['var_name']; ?>][value]">
<?php if (count((array)$this->_aVars['aSetting']['values']['values'])):  foreach ((array) $this->_aVars['aSetting']['values']['values'] as $this->_aVars['mKey'] => $this->_aVars['sDropValue']): ?>
			<option value="<?php echo $this->_aVars['sDropValue']; ?>" <?php if ($this->_aVars['aSetting']['values']['default'] == $this->_aVars['sDropValue']): ?>selected="selected"<?php endif; ?>><?php echo $this->_aVars['sDropValue']; ?></option>
<?php endforeach; endif; ?>
		</select>
<?php elseif (( $this->_aVars['aSetting']['type_id'] == 'drop_with_key' )): ?>
		<select name="val[value][<?php echo $this->_aVars['aSetting']['var_name']; ?>]">
<?php if (count((array)$this->_aVars['aSetting']['values'])):  foreach ((array) $this->_aVars['aSetting']['values'] as $this->_aVars['mKey'] => $this->_aVars['sDropValue']): ?>
			<option value="<?php echo $this->_aVars['mKey']; ?>"<?php if ($this->_aVars['aSetting']['value_actual'] == $this->_aVars['mKey']): ?> selected="selected"<?php endif; ?>><?php echo $this->_aVars['sDropValue']; ?></option>
<?php endforeach; endif; ?>
		</select>	
<?php elseif (( $this->_aVars['aSetting']['type_id'] == 'integer' )): ?>
		<input type="text" name="val[value][<?php echo $this->_aVars['aSetting']['var_name']; ?>]" value="<?php echo $this->_aVars['aSetting']['value_actual']; ?>" size="40" onclick="this.select();" />
<?php elseif (( $this->_aVars['aSetting']['type_id'] == 'boolean' )): ?>
		<select name="val[value][<?php echo $this->_aVars['aSetting']['var_name']; ?>]">
			<option value="1" <?php if ($this->_aVars['aSetting']['value_actual'] == 1): ?>selected="selected"<?php endif; ?>><?php echo Phpfox::getPhrase('admincp.true'); ?></option>
			<option value="0" <?php if ($this->_aVars['aSetting']['value_actual'] != 1): ?>selected="selected"<?php endif; ?>><?php echo Phpfox::getPhrase('admincp.false'); ?></option>
		</select>
<?php elseif (( $this->_aVars['aSetting']['type_id'] == 'array' )): ?>
		<div class="js_array_holder">
<?php if (is_array ( $this->_aVars['aSetting']['value_actual'] )): ?>
<?php if (count((array)$this->_aVars['aSetting']['value_actual'])):  foreach ((array) $this->_aVars['aSetting']['value_actual'] as $this->_aVars['iKey'] => $this->_aVars['sValue']): ?>
				<div class="p_4" class="js_array<?php echo $this->_aVars['iKey']; ?>"><input type="text" name="val[value][<?php echo $this->_aVars['aSetting']['var_name']; ?>][]" value="<?php echo $this->_aVars['sValue']; ?>" size="30" /> - <a href="#" onclick="if (confirm('<?php echo Phpfox::getPhrase('core.are_you_sure'); ?>')) { $.ajaxCall('admincp.removeSettingFromArray', 'setting=<?php echo $this->_aVars['aSetting']['var_name']; ?>&amp;value=<?php echo $this->_aVars['sValue']; ?>'); $(this).parent().remove(); } return false;"><?php echo Phpfox::getPhrase('admincp.remove'); ?></a></div>
<?php endforeach; endif; ?>
<?php endif; ?>
			<div class="js_array_data"></div>
			<div class="js_array_count" style="display:none;"><?php if (isset ( $this->_aVars['iKey'] )):  echo $this->_aVars['iKey'];  endif; ?></div>
			<br />
			<div class="p_4">
				<input type="text" name="" value="<?php echo Phpfox::getPhrase('admincp.add_a_new_value'); ?>" onclick="if(this.value=='<?php echo Phpfox::getPhrase('admincp.add_a_new_value', array('phpfox_squote' => true)); ?>')this.value='';" onblur="if(this.value=='')this.value='<?php echo Phpfox::getPhrase('admincp.add_a_new_value', array('phpfox_squote' => true)); ?>';" size="30" class="js_add_to_array" /> <input type="button" value="<?php echo Phpfox::getPhrase('admincp.add'); ?>" class="button" onclick="return addInput(this, '<?php echo $this->_aVars['aSetting']['var_name']; ?>');" />
			</div>
		</div>
<?php endif; ?>
	</div>
	<div class="clear"></div>
</div>
<?php if ($this->_aVars['aSetting']['var_name'] == 'watermark_option'): ?>
<div class="table_header2">
<?php echo Phpfox::getPhrase('admincp.image'); ?>
</div>
<div class="table3">
	<div class="row_left">		
<?php echo Phpfox::getPhrase('admincp.your_current_watermark_image'); ?>:
		<div class="p_4">
			<img src="<?php echo $this->_aVars['sWatermarkImage']; ?>" alt="Watermark Image" />
		</div>
		<div class="p_4">
<?php echo Phpfox::getPhrase('admincp.b_notice_b_advised_image_is_a_transparent_png_with_a_max_width_height_of_52_pixels'); ?>
		</div>
	</div>
	<div class="row_right" style="margin-bottom:20px;">
		<input type="file" name="watermark" size="30" />
		<div class="extra_info">
<?php echo Phpfox::getPhrase('admincp.you_can_upload_a_jpg_gif_or_png_file'); ?>
		</div>
	</div>
	<div class="clear"></div>
</div>
<?php endif;  endforeach; endif; ?>
<div class="table_clear table_hover_action">
	<input type="submit" value="<?php echo Phpfox::getPhrase('admincp.submit'); ?>" class="button" />
</div>

</form>

<?php else: ?>
<p><?php echo Phpfox::getPhrase('admincp.setting_group_avaliable_settings'); ?></p>
<?php endif; ?>
