<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php /* Cached: March 3, 2014, 3:16 am */ ?>
<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright      YouNet Company
 * @author         LyTK
 * @package        Module_ProfilePopup
 * @version        3.01
 */
 
 

?>
<script type="text/javascript">
<?php echo '
        $Behavior.ynppInitUser = function()
        {
                $(\'#update\').click(function(event)
                {
                        event.preventDefault();
                        if($(\'#public_message\'))
                        {
                                $(\'#public_message\').remove();
                        }
                        if($(\'#core_js_messages\'))
                        {
                                $(\'#core_js_messages\').html(\'\');
                        }
                        $(\'#update\').ajaxCall(\'profilepopup.updateGlobalSettings\', \'&item_type=user\' + \'&\' + $(\'#globalSettings\').serialize());
                }); 
                
                $(\'.checkbox\').change(function(){
                        var sIdName = \'#js_row\' + $(this).get(0).id.replace(\'js_id_row\', \'\');
                        if ($(sIdName).hasClass(\'is_checked\'))
                        {
                                $(sIdName).removeClass(\'is_checked\');
                        }
                        $(\'.sJsCheckBoxButton\').removeClass(\'disabled\');
                        $(\'.sJsCheckBoxButton\').attr(\'disabled\', false); 
                });
                $("#js_check_box_all_basic").change(function(){
                        $(\'.checkRow .basic\').removeClass(\'is_checked\');
                        $(\'.sJsCheckBoxButton\').removeClass(\'disabled\');
                        $(\'.sJsCheckBoxButton\').attr(\'disabled\', false); 
                });
                $("#js_check_box_all_resume").change(function(){
                        $(\'.checkRow .resume\').removeClass(\'is_checked\');
                        $(\'.sJsCheckBoxButton\').removeClass(\'disabled\');
                        $(\'.sJsCheckBoxButton\').attr(\'disabled\', false); 
                });
                
				$("#js_check_box_all_basic").click(function()
				{
					var bStatus = this.checked;
					if (bStatus)
					{
						$(\'.checkRow .ynpp_basic\').addClass(\'is_checked\');
						$(\'.sJsCheckBoxButton\').removeClass(\'disabled\');
						$(\'.sJsCheckBoxButton\').attr(\'disabled\', false);
					}
					else
					{
						$(\'.checkRow .ynpp_basic\').removeClass(\'is_checked\');
						$(\'.sJsCheckBoxButton\').addClass(\'disabled\');
						$(\'.sJsCheckBoxButton\').attr(\'disabled\', true);
					}
					$(".checkRow .ynpp_basic").each(function()
					{
						this.checked = bStatus;
					});
				});                
				$("#js_check_box_all_resume").click(function()
				{
					var bStatus = this.checked;
					if (bStatus)
					{
						$(\'.checkRow .ynpp_resume\').addClass(\'is_checked\');
						$(\'.sJsCheckBoxButton\').removeClass(\'disabled\');
						$(\'.sJsCheckBoxButton\').attr(\'disabled\', false);
					}
					else
					{
						$(\'.checkRow .ynpp_resume\').removeClass(\'is_checked\');
						$(\'.sJsCheckBoxButton\').addClass(\'disabled\');
						$(\'.sJsCheckBoxButton\').attr(\'disabled\', true);
					}
					$(".checkRow .ynpp_resume").each(function()
					{
						this.checked = bStatus;
					});
				});                
        };
'; ?>

</script>
<style type="text/css">
        <?php echo '
        '; ?>

</style>

<?php if (count ( $this->_aVars['aAllItems'] )): ?>
        <div class="p_4"><span style="font-weight: bold; text-decoration: underline; font-size: 15px;"><?php echo Phpfox::getPhrase('profilepopup.notice'); ?></span>: <?php echo Phpfox::getPhrase('profilepopup.global_settings_info'); ?></div>
        <form id="globalSettings" method="post" action="<?php echo Phpfox::getLib('phpfox.url')->makeUrl('admincp.profilepopup.user'); ?>">
<?php echo '<div><input type="hidden" name="' . Phpfox::getTokenName() . '[security_token]" value="' . Phpfox::getService('log.session')->getToken() . '" /></div>'; ?>
    		<div class="table_header">
<?php echo Phpfox::getPhrase('profilepopup.basic_information'); ?>:
			</div>
            <table>
                    <tr>
                            <th><?php echo Phpfox::getPhrase('profilepopup.field_label'); ?></th>
                            <th style="width:10px;"><input type="checkbox" name="val[id]" value="" id="js_check_box_all_basic" class="main_checkbox" /></th>
                            <th><?php echo Phpfox::getPhrase('profilepopup.global_settings_ordering'); ?></th>
                    </tr>
<?php if (count((array)$this->_aVars['aAllItems'])):  foreach ((array) $this->_aVars['aAllItems'] as $this->_aVars['iKey'] => $this->_aVars['aItem']): ?>
                    <tr id="js_row<?php echo $this->_aVars['aItem']['item_id']; ?>" class="checkRow<?php if (is_int ( $this->_aVars['iKey'] / 2 )): ?> tr<?php else:  endif; ?>">
                            <td width="470px"><?php echo $this->_aVars['aItem']['lang_name']; ?></td>
                            <td><input type="checkbox" name="display[]" class="checkbox ynpp_basic" <?php echo $this->_aVars['aItem']['checked']; ?> value="<?php echo $this->_aVars['aItem']['item_id']; ?>" id="js_id_row<?php echo $this->_aVars['aItem']['item_id']; ?>" /></td>
                            <td>
                                    <input type="text" name="ordering[]" value="<?php echo $this->_aVars['aItem']['ordering']; ?>" id="ordering[]" size="5" maxlength="5" style="text-align: right;" />
                                    <input type="hidden" name="id[]" value="<?php echo $this->_aVars['aItem']['item_id']; ?>" />
                            </td>
                    </tr>
<?php endforeach; endif; ?>
            </table>
            
			<!-- Resume -->
    		<div class="table_header">
<?php echo Phpfox::getPhrase('profilepopup.resume_fields'); ?>:
			</div>
            <table>
                <tr>
                    <th><?php echo Phpfox::getPhrase('profilepopup.field_label'); ?></th>
                    <th style="width:10px;"><input type="checkbox" name="val[id_resume]" value="" id="js_check_box_all_resume" class="main_checkbox" /></th>
                    <th><?php echo Phpfox::getPhrase('profilepopup.global_settings_ordering'); ?></th>
                </tr>
<?php if (count((array)$this->_aVars['aResumeItems'])):  foreach ((array) $this->_aVars['aResumeItems'] as $this->_aVars['iKey'] => $this->_aVars['aItem']): ?>
                <tr id="js_row<?php echo $this->_aVars['aItem']['item_id']; ?>" class="checkRow<?php if (is_int ( $this->_aVars['iKey'] / 2 )): ?> tr<?php else:  endif; ?>">
                    <td width="470px"><?php echo $this->_aVars['aItem']['lang_name']; ?></td>
                    <td><input type="checkbox" name="display_resume[]" class="checkbox ynpp_resume" <?php echo $this->_aVars['aItem']['checked']; ?> value="<?php echo $this->_aVars['aItem']['item_id']; ?>" id="js_id_row<?php echo $this->_aVars['aItem']['item_id']; ?>" /></td>
                    <td>
                        <input type="text" name="ordering_resume[]" value="<?php echo $this->_aVars['aItem']['ordering']; ?>" id="ordering[]" size="5" maxlength="5" style="text-align: right;" />
                        <input type="hidden" name="id_resume[]" value="<?php echo $this->_aVars['aItem']['item_id']; ?>" />
                    </td>
                </tr>
<?php endforeach; endif; ?>
            </table>
			
<!-- 			End -->
                <div class="table_bottom">
                        <input type="button" id="update" name="update" value="<?php echo Phpfox::getPhrase('profilepopup.global_settings_update'); ?>" class="button sJsCheckBoxButton" />
                </div>
        
</form>

<?php else: ?>
        <div class="p_4">
<?php echo Phpfox::getPhrase('profilepopup.no_user_global_settings'); ?>
        </div>
<?php endif; ?>

