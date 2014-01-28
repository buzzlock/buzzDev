<?php
defined('PHPFOX') or exit('NO DICE!');

?>
{literal}
<script language="javascript">
        function delete_singer(singer_id,div_id){
            var div = "#tr_singer_"+singer_id+"_checkbox";
            $(div).hide();
                $.ajaxCall('musicsharing.deleteSinger','idsinger='+singer_id);

        }

        function check_all_singer(obj){
            var root = obj.parentNode.parentNode.parentNode;
            for(i=0; i<root.childNodes.length; i++){
                if(document.getElementById(root.childNodes[i].id+"_checkbox") != null){
                    document.getElementById(root.childNodes[i].id+"_checkbox").checked = obj.checked;
                }
            }
        }
</script>
{/literal}

{literal}
<script language="javascript1.2" type="text/javascript" >
    function setDeleteAlbumButtonStatus(status) {
        if (status)
        {
            $('.delete').removeClass('disabled');
            $('.delete').attr('disabled', false);
        }
        else
        {
            $('.delete').addClass('disabled');
            $('.delete').attr('disabled', true);
        }
    }
    
    function switchCheckAll(setCheck){
        $('.delete_singer').each(function(index, element){
            element.checked = setCheck;
        });
    }
    
    function switchCheck(){
        if(document.getElementById('delete_singer_check_all').checked==true)
            switchCheckAll(true);
        else
            switchCheckAll(false);
    }
    
    function checkDisableStatus(){
        var status = false;
        
        $('.delete_singer').each(function(index, element){
            var sIdName = '#tr_' + element.id;
            
            if (element.checked == true)
            {
                status = true;
                $(sIdName).css({'backgroundColor':'#FFFF88'});
            }
            else
            {
                $(sIdName).css({'backgroundColor':'#FFF'});
            }
        });
        
        setDeleteAlbumButtonStatus(status);
        
        return status;
    }
    
    function initEditInlineSinger()
    {
        var aElement = $('.quickEdit');
        
        aElement.each(function(){
            var oElement = $(this);
            
            oElement.click(function(oEvent){
                oEvent.preventDefault();                
            });
        });
    }
$Behavior.MusicSharingSingerScript12 = function() {    
    $(document).ready(function(){
        initEditInlineSinger();
    });    
}
</script>
{/literal}

<form method="post" action="{url link='admincp.musicsharing.singer'}" enctype="multipart/form-data">
	<div class="table_header">
		{phrase var='musicsharing.singer'} {phrase var='musicsharing.details'}
	</div>
    <div class="table">
        <div class="table_left">
            {phrase var='musicsharing.singer'} {phrase var='musicsharing.type'}:
        </div>
        
        <div class="table_right">
            <select name="val[songSingerType]" id="songSingerType" style="width:180px" >
                <option value="">{phrase var='musicsharing.select_singer_type'}</option>
                {foreach from=$aSingerTypes key = key item=aSingerType}
                <option value="{$aSingerType.singertype_id}">{$aSingerType.title}</option>
                {/foreach}
            </select>
        </div>
        <div class="clear"></div>
    </div>
	<div class="table">
		<div class="table_left">
			{phrase var='musicsharing.singer'} {phrase var='musicsharing.name_upper'}:
		</div>
		<div class="table_right">
			<input type="text" name="val[title]" value="" id="title" size="40" maxlength="150" />
		</div>
		<div class="clear"></div>
	</div>
    <div class="table">
              <div class="table_left">{phrase var='musicsharing.singer_s_image'}:</div>
              <div class="table_right">  <input id="singer_image" type="file" name="singer_image">  </div>
          </div>
	<div class="table_clear">
		<input type="submit" value="{phrase var='musicsharing.add_singer'}" class="button" />
	</div>
</form>
{if count($list_info)>0}
 <form action="{url link='current'}" method="post" id="order_display_sb" >
    <table align="center" style="text-align:left;">
    <tr>
        <th><input type='checkbox' onclick="switchCheck();checkDisableStatus()" id='delete_singer_check_all' name='delete_singer_check_all' /> </th>
        <th>{phrase var='musicsharing.name'}</th>
        <th>{phrase var='musicsharing.singer_s_image'}</th>
        <th>{phrase var='musicsharing.options'}</th>
    </tr>
    
    {foreach from=$list_info key=key item=aSingerType}
        {if isset($aSingerType.singer)}
            {foreach from=$aSingerType.singer key=iKey item=singer name=singerName}
                <tr id="tr_singer_{$singer.singer_id}_checkbox" class='classified_row {if $phpfox.iteration.singerName % 2 == 0 } classified_even{else} classified_odd{/if}' style="height:30px;">
                    <td style="width:10px">
                        <input type='checkbox' class="delete_singer" id="singer_{$singer.singer_id}_checkbox" name='delete_singer[]' value='{$singer.singer_id}' onclick="checkDisableStatus()"/>
                    </td>
                    
                    <td id="js_blog_edit_title{$singer.singer_id}">
                        <a href="#?type=input&amp;id=js_blog_edit_title{$singer.singer_id}&amp;content=js_category{$singer.singer_id}&amp;call=musicsharing.updateSingerTitle&amp;singer_id={$singer.singer_id}" class="quickEdit" id="js_category{$singer.singer_id}">
                            {$singer.title|convert|clean}
                        </a>
                    </td>
                    
                    <td>
                        {if $singer.singer_image != ''}
                            {$singer.sImage}
                        {/if}
                    </td>
                    
                    <td style="width: 200px;">
                        <a  href="{url link ='admincp.musicsharing.editsinger.singerid_'.$singer.singer_id}">{phrase var='musicsharing.edit'}</a>
                        |
                        <a  href="javascript:void(0);" onclick="if (confirm('{phrase var='musicsharing.are_you_sure' phpfox_squote=true}')) {literal}{{/literal} delete_singer('{$singer.singer_id}','tr_singer_{$singer.singer_id}_checkbox');{literal}}{/literal} return false;">{phrase var='musicsharing.delete'}</a>
                    </td>
                </tr>   
             {/foreach}
        {/if}
    {/foreach}
    </table>
    <div class="table_bottom">
        <input type="submit" name="delete" id="delete" disabled value="{phrase var='musicsharing.delete_selected'}" class="sJsConfirm delete button sJsCheckBoxButton disabled" />
        <input type='hidden' name='task' value='dodelete' />
    </div>
  </form>
{/if}