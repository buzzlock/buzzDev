<?php
defined('PHPFOX') or exit('NO DICE!');
?>		
<div class="table">
    <div class="table_left">
        {required}{phrase var='videochannel.video_title'}:
    </div>
    <div class="table_right">
        <input type="text" name="val[title]" value="{value type='input' id='title'}" size="30" id="js_video_title" maxlength="200" />
    </div>
</div>		

{if (!isset($sModule) || $sModule == false) }
    <div class="table">
        <div class="table_left">
            <label for="category">{required}{phrase var='videochannel.category'}:</label>
        </div>
        <div class="table_right">
            {$sCategories}
        </div>
    </div>
{/if}

<div class="table">
    <div class="table_left">
        {phrase var='videochannel.description'}:
    </div>
    <div class="table_right">
        <textarea cols="40" rows="3" name="val[text]" class="js_edit_video_form" style="height:30px;">{value id='text' type='textarea'}</textarea>		
    </div>
</div>

{if Phpfox::isModule('tag') && Phpfox::getUserParam('tag.can_add_tags_on_blogs')}
    {if isset($sModule) && $sModule != ''}
        {module name='tag.add' sType=video_group}
    {else}
        {module name='tag.add' sType=video}
    {/if}
{/if}

<div id="js_custom_privacy_input_holder">
    {if isset($aForms.video_id)}
        {module name='privacy.build' privacy_item_id=$aForms.video_id privacy_module_id='videochannel'}	
    {/if}
</div>		

{if (isset($sModule) && $sModule) || (isset($aForms) && $aForms.item_id > 0)}

{else}
    {if Phpfox::isModule('privacy')}
        <div class="table">
            <div class="table_left">
                {phrase var='videochannel.privacy'}:
            </div>
            <div class="table_right">	
                {module name='privacy.form' privacy_name='privacy' privacy_info='videochannel.control_who_can_see_this_video' default_privacy='videochannel.display_on_profile'}
            </div>			
        </div>
    {/if}

    {if Phpfox::isModule('comment') && Phpfox::isModule('privacy')}
        <div class="table">
            <div class="table_left">
                {phrase var='videochannel.comment_privacy'}:
            </div>
            <div class="table_right">	
                {module name='privacy.form' privacy_name='privacy_comment' privacy_info='videochannel.control_who_can_comment_on_this_video' privacy_no_custom=true}
            </div>			
        </div>
    {/if}			
{/if}