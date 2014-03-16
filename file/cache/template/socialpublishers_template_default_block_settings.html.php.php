<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php /* Cached: March 16, 2014, 7:24 pm */ ?>
<?php

 echo '
<script type="text/javascript">
    function updateSocialPublishersSetting(oObj)
    {		
        $(oObj).ajaxCall(\'socialpublishers.updateModulesSettings\');
        return false;
    }
</script>
'; ?>

<div align="left" class="page_section_menu_holder" id="js_setting_block_socialpublishers" style="display:none">  
    <div class="table">
<?php if (count ( $this->_aVars['aModules'] )): ?>
        <div class="table_left">
<?php echo Phpfox::getPhrase('socialpublishers.activity_management'); ?>
        </div>
        <div class="table_right">                
            <form method="post" action="#" onsubmit="return updateSocialPublishersSetting(this);">
<?php echo '<div><input type="hidden" name="' . Phpfox::getTokenName() . '[security_token]" value="' . Phpfox::getService('log.session')->getToken() . '" /></div>'; ?>
<?php if (count((array)$this->_aVars['aModules'])):  foreach ((array) $this->_aVars['aModules'] as $this->_aVars['aModule']): ?>
                <div class="table" style="padding-top: 10px; padding-left: 5px; border-bottom: 1px solid #DFDFDF;">
                    <div class="table_left" style="display: inline; line-height: 20px; padding-left: 20px">
<?php echo Phpfox::getPhrase($this->_aVars['aModule']['title']); ?>
                    </div>
                    <div class="table_right" style="margin-right: 250px; display: inline; line-height: 20px; float: right;">
<?php if ($this->_aVars['aModule']['facebook'] == 1): ?>
                        <label><input type="checkbox" value="1" name="val[<?php echo $this->_aVars['aModule']['module_id']; ?>][facebook]" <?php if (! isset ( $this->_aVars['aModule']['user_setting']['facebook'] ) || $this->_aVars['aModule']['user_setting']['facebook'] == 1): ?>checked<?php endif; ?>/><?php echo Phpfox::getPhrase('socialpublishers.facebook'); ?></label>
<?php endif; ?>
<?php if ($this->_aVars['aModule']['twitter'] == 1): ?>
                        <label><input type="checkbox" value="1" name="val[<?php echo $this->_aVars['aModule']['module_id']; ?>][twitter]" <?php if (! isset ( $this->_aVars['aModule']['user_setting']['twitter'] ) || $this->_aVars['aModule']['user_setting']['twitter'] == 1): ?>checked<?php endif; ?>/><?php echo Phpfox::getPhrase('socialpublishers.twitter'); ?></label>
<?php endif; ?>
<?php if ($this->_aVars['aModule']['linkedin'] == 1): ?>
                        <label><input type="checkbox" value="1" name="val[<?php echo $this->_aVars['aModule']['module_id']; ?>][linkedin]" <?php if (! isset ( $this->_aVars['aModule']['user_setting']['linkedin'] ) || $this->_aVars['aModule']['user_setting']['linkedin'] == 1): ?>checked<?php endif; ?>/><?php echo Phpfox::getPhrase('socialpublishers.linkedin'); ?></label>
<?php endif; ?>
                        <label><input type="checkbox" value="1" name="val[<?php echo $this->_aVars['aModule']['module_id']; ?>][no_ask]" <?php if (! isset ( $this->_aVars['aModule']['user_setting']['no_ask'] ) || $this->_aVars['aModule']['user_setting']['no_ask'] == 1): ?>checked<?php endif; ?>/><?php echo Phpfox::getPhrase('socialpublishers.don_t_ask_me_again'); ?></label>
                        <input type="hidden" value="<?php echo $this->_aVars['aModule']['is_insert']; ?>" name="val[<?php echo $this->_aVars['aModule']['module_id']; ?>][is_insert]"/>
                    </div>            
                </div>     
                <div class="clear"></div>
<?php endforeach; endif; ?>
                <div class="table_clear" style="margin-top: 10px;">
                    <input type="submit" value="<?php echo Phpfox::getPhrase('core.update'); ?>" class="button" />            
                </div>                
            
</form>

        </div>

<?php endif; ?>
    </div>
</div>
