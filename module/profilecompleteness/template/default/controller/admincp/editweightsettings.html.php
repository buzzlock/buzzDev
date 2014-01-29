<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<style type="text/css">
    {literal}
    .space_weight >ul >li{
        padding: 10px 0 10px 0;
        border-bottom: 1px solid #DFDFDF;
        position: relative;
        overflow: hidden;

    }

    .space_weight >ul >li >span{
        position: absolute;
        left: 200px;
        bottom: 4px;
    }

    .link_profile_edit_weight{
        padding: 10px 0;
        font-size: 0.5cm;
    }
    .link_profile_edit_weight a:hover{

        text-decoration: underline !important;

    }
    .admin_notice {
        margin: 0px 0px 5px 0px;
    }

    {/literal}
</style>
<div class="admin_notice">{phrase var='profilecompleteness.admin_message_weight_settings'}</div>

<form method="post" action="{url link='admincp.profilecompleteness.editweightsettings'}">

<div class="table_header">
    {phrase var='profilecompleteness.profile_photo'}
</div>
<div class="space_weight">
    <ul>
        <li>{phrase var='profilecompleteness.profile_photo'}<span><input type="text" id="user_image" name="user_image" value="{$aPhoto.user_image}"/></span></li>
    </ul>    
</div>    
    
    <div class="table_header">
        {phrase var='profilecompleteness.basic_information'}
    </div>    
    <div class="space_weight">
        <ul>
            <li>{phrase var='profilecompleteness.location'} <span><input type="text" name="val[country_iso]" value="{$aRow.country_iso}"/></span></li>
            <li>{phrase var='profilecompleteness.city'} <span><input type="text" name="val[city_location]" value="{$aRow.city_location}"/></span></li>
            <li>{phrase var='profilecompleteness.zip_postal_code'} <span><input name="val[postal_code]" value="{$aRow.postal_code}"/></span></li>
            <li {if $settingdefault.cf_birthday==0}style="display:none"{/if}>{phrase var='profilecompleteness.date_of_birth'} <span><input type="text" name="val[birthday]" value="{$aRow.birthday}"/></span></li>
            <li {if $settingdefault.cf_gender==0}style="display:none"{/if}>{phrase var='profilecompleteness.gender'} <span><input type="text" name="val[gender]" value="{$aRow.gender}"/></span></li>  
            <li {if $settingdefault.enable_relationship_status==0}style="display:none"{/if}>{phrase var='profilecompleteness.relationship_status'} <span><input type="text" name="val[cf_relationship_status]" value="{$aRow.cf_relationship_status}"/></span></li>
            <li {if $settingdefault.cf_signature==0}style="display:none"{/if}>{phrase var='profilecompleteness.forum_signature'} <span><input type="text" name="val[signature]" value="{$aRow.signature}"/></span></li>
        </ul>
    </div>

    {foreach from=$ListCustom key=KeyName item=CustomField}
    {if isset($CustomField.child) && count($CustomField.child)>0 && $CustomField.child[0].group_id!=0}
    <div {if $CustomField.is_active==0}style="display: none"{/if}>
        <div class="table_header">
            {phrase var=$CustomField.phrase_var_name}
        </div>

        <div class="space_weight">
            <ul>
                {foreach from=$CustomField.child item=child}
                    {if $child.is_active !=0}
                        <li>{phrase var=$child.phrase_var_name} <span><input type="text" name="val[cf_{$child.field_name}]" value="{$child.weight}"/></span></li>
                    {/if}
                {/foreach}
            </ul>
        </div>
    </div>
    {/if}
    {/foreach}


    <div style="padding-top: 10px">
        <input type="submit" class="button" name="SaveChangesProfile" id="SaveChangesProfile" value="{phrase var='profilecompleteness.save_changes'}"/>
    </div>
</form>