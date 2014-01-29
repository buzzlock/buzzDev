<?php 
    /**
    * [PHPFOX_HEADER]
    * 
    * @copyright		[PHPFOX_COPYRIGHT]
    * @author  		Raymond Benc
    * @package 		Phpfox
    * @version 		$Id: index.html.php 1544 2010-04-07 13:20:17Z Raymond_Benc $
    */

    defined('PHPFOX') or exit('NO DICE!'); 

?>
<form action="{url link='admincp.socialpublishers.modules'}" method="post">
{foreach from=$aModules item=aModule}
<div class="table_header">
    {phrase var=$aModule.title}
</div>
<div class="table">

    <div class="table_left" style="width: 200px;">
        {required}{phrase var='socialpublishers.active'}
    </div>
    <div class="table_right" style="margin-left:200px">
        <div class="item_can_be_closed_holder">
            <span class="item_is_active">
                <input type="radio" name="val[{$aModule.module_id}][is_active]" value="1"  {if isset($aModule.is_active) && $aModule.is_active == 1}checked{/if}/> {phrase var='admincp.yes'}
            </span>
            <span class=" item_is_not_active">
                <input type="radio" name="val[{$aModule.module_id}][is_active]" value="0" {if !isset($aModule.is_active) || $aModule.is_active == 0}checked{/if}/> {phrase var='admincp.no'}
            </span>
        </div>
    </div>
    <div class="clear"></div>
</div>
<div class="table">

    <div class="table_left" style="width: 200px;">
        {required}{phrase var='socialpublishers.publish_provides'}
    </div>
    <div class="table_right" style="margin-left:200px">
        <label><input type="checkbox" value="1" name="val[{$aModule.module_id}][facebook]" {if $aModule.facebook == 1}checked{/if}/>{phrase var='socialpublishers.facebook'}</label>
        <label><input type="checkbox" value="1" name="val[{$aModule.module_id}][twitter]" {if $aModule.twitter == 1}checked{/if}/>{phrase var='socialpublishers.twitter'}</label>
        <label><input type="checkbox" value="1" name="val[{$aModule.module_id}][linkedin]" {if $aModule.linkedin == 1}checked{/if}/>{phrase var='socialpublishers.linkedin'}</label>
    </div>
    <div class="clear"></div>
</div>
<br/>
{/foreach}
<div class="table_clear">
    <input type="submit" value="{phrase var='core.submit'}" class="button" />
</div>
</form>