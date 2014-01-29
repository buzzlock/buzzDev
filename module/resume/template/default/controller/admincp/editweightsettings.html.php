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
        left: 250px;
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


<div class="table_header">
    {phrase var='resume.edit_weight_of_resume_fields'}
</div>
<form method="post" action="{url link='admincp.resume.editweightsettings'}">
<div class="space_weight">
        <ul>
        	{foreach from=$aRows item=aRow}
            <li>{$aRow.phrase|clean|shorten:40:'...'|split:40} <span><input type="text" value="{$aRow.score}" name="val[{$aRow.name}]"></span></li>
			{/foreach}
        </ul>
    </div>
    
    <div style="padding-top: 10px">
        <input type="submit" class="button" name="{phrase var='resume.savechangesresume'}" id="SaveChangesProfile" value="{phrase var='resume.save_changes'}"/>
    </div>
 </form>