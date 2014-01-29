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
    }

    .space_weight >ul >li >b{
        font-weight: bold;
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
<div class="table_header">
    {phrase var='profilecompleteness.profile_photo'}
</div>
<div class="space_weight">
    <ul>
        <li>{phrase var='profilecompleteness.profile_photo'}<b> (+{$aPhoto.user_image})</b></li>
    </ul>    
</div>

<div class="table_header">
    {phrase var='profilecompleteness.basic_information'}
</div>

<div class="space_weight">
    <ul>
        <li>{phrase var='profilecompleteness.location'} <b>(+{$aRow.country_iso})</b></li>
        <li>{phrase var='profilecompleteness.city'} <b>(+{$aRow.city_location})</b></li>
        <li>{phrase var='profilecompleteness.zip_postal_code'} <b>(+{$aRow.postal_code})</b></li>
        <li {if $settingdefault.cf_birthday===false}style="display:none"{/if}>{phrase var='profilecompleteness.date_of_birth'} <b>(+{$aRow.birthday})</b></li>
        <li {if $settingdefault.cf_gender===false}style="display:none"{/if}>{phrase var='profilecompleteness.gender'} <b>(+{$aRow.gender})</b></li>  
        <li {if $settingdefault.enable_relationship_status===false}style="display:none"{/if}>{phrase var='profilecompleteness.relationship_status'} <b>(+{$aRow.cf_relationship_status})</b></li>
        <li {if $settingdefault.cf_signature===false}style="display:none"{/if}>{phrase var='profilecompleteness.forum_signature'} <b>(+{$aRow.signature})</b></li>
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
                    <li>{phrase var=$child.phrase_var_name} <b>(+{$child.weight})</b></li>
                {/if}
            {/foreach}
            
        </ul>
    </div>
</div>
{/if}
{/foreach}


<ul class="link_profile_edit_weight">
    <li><a style="text-decoration: none" href="{url link='admincp.profilecompleteness.editweightsettings'}">{phrase var='profilecompleteness.edit_weight_of_profile_fields'}</a>
    </li>
</ul>