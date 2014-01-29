<?php
defined('PHPFOX') or exit('NO DICE!');
?>
{literal}
<script type="text/javascript">
    function updateSocialPublishersSetting(oObj)
    {		
        $(oObj).ajaxCall('socialpublishers.updateModulesSettings');
        return false;
    }
</script>
{/literal}
<div align="left" class="page_section_menu_holder" id="js_setting_block_socialpublishers" style="display:none">  
    <div class="table">
        {if count($aModules)}
        <div class="table_left">
            {phrase var='socialpublishers.activity_management'}
        </div>
        <div class="table_right">                
            <form method="post" action="#" onsubmit="return updateSocialPublishersSetting(this);">
                {foreach from=$aModules item=aModule}
                <div class="table" style="padding-top: 10px; padding-left: 5px; border-bottom: 1px solid #DFDFDF;">
                    <div class="table_left" style="display: inline; line-height: 20px; padding-left: 20px">
                        {phrase var=$aModule.title}
                    </div>
                    <div class="table_right" style="margin-right: 250px; display: inline; line-height: 20px; float: right;">
                        {if $aModule.facebook == 1}
                        <label><input type="checkbox" value="1" name="val[{$aModule.module_id}][facebook]" {if !isset($aModule.user_setting.facebook) || $aModule.user_setting.facebook == 1  }checked{/if}/>{phrase var='socialpublishers.facebook'}</label>
                        {/if}
                        {if $aModule.twitter == 1}
                        <label><input type="checkbox" value="1" name="val[{$aModule.module_id}][twitter]" {if  !isset($aModule.user_setting.twitter) || $aModule.user_setting.twitter == 1}checked{/if}/>{phrase var='socialpublishers.twitter'}</label>
                        {/if}
                        {if $aModule.linkedin == 1}
                        <label><input type="checkbox" value="1" name="val[{$aModule.module_id}][linkedin]" {if !isset($aModule.user_setting.linkedin)|| $aModule.user_setting.linkedin == 1}checked{/if}/>{phrase var='socialpublishers.linkedin'}</label>
                        {/if}
                        <label><input type="checkbox" value="1" name="val[{$aModule.module_id}][no_ask]" {if !isset($aModule.user_setting.no_ask) || $aModule.user_setting.no_ask == 1 }checked{/if}/>{phrase var='socialpublishers.don_t_ask_me_again'}</label>
                        <input type="hidden" value="{$aModule.is_insert}" name="val[{$aModule.module_id}][is_insert]"/>
                    </div>            
                </div>     
                <div class="clear"></div>
                {/foreach}   
                <div class="table_clear" style="margin-top: 10px;">
                    <input type="submit" value="{phrase var='core.update'}" class="button" />            
                </div>                
            </form>
        </div>

        {/if}
    </div>
</div>