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

<div class="table_header">
    {phrase var='resume.admin_menu_weight_settings'}
</div>
<div class="space_weight">
    <ul>
    	{foreach from=$aRows item=aRow}
        <li>{$aRow.phrase} <b>(+{$aRow.score})</b></li>
        {/foreach}
    </ul>
</div>

<ul class="link_profile_edit_weight">
    <li><a style="text-decoration: none" href="{url link='admincp.resume.editweightsettings'}">{phrase var='resume.edit_weight_of_resume_fields'}</a>
    </li>
</ul>
