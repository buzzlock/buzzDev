<?php
defined('PHPFOX') or exit('NO DICE!');

?>
{literal}
<script language="javascript">
    function delete_singer_type(singer_type_id,div_id){
        var myAlbum = document.getElementById(div_id);
        myAlbum.style.display="none";
        $.ajaxCall('musicsharing.deleteSingerType','idsingertype='+singer_type_id);
    }

    function check_all_singer_type(obj){
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
       if(status){
           $('.delete').removeClass('disabled');
           $('.delete').attr('disabled', false);
       }else{
          $('.delete').addClass('disabled');
          $('.delete').attr('disabled', true);
       }

    }
    function switchCheckAll(setCheck){
        $('.delete_singer_type').each(function(index, element){
            element.checked = setCheck;
        });
    }
    function switchCheck()
    {
        if(document.getElementById('delete_singer_type_check_all').checked==true)
            switchCheckAll(true);
        else
            switchCheckAll(false);
    }
    function checkDisableStatus(){
        var status = false;
        $('.delete_singer_type').each(function(index, element){
            var sIdName = '#tr_'+element.id;
            if(element.checked==true){
            status =  true;
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

</script>
{/literal}

<form method="post" action="{url link='admincp.musicsharing.singertype'}">
	<div class="table_header">
		{phrase var='musicsharing.singer_type_details'}
	</div>
	<div class="table">
		<div class="table_left">
			{phrase var='musicsharing.singer_type_name'}:
		</div>
		<div class="table_right">
			<input type="text" name="val[title]" value="" id="title" size="40" maxlength="150" />
		</div>
		<div class="clear"></div>
	</div>
	<div class="table_clear">
		<input type="submit" value="{phrase var='musicsharing.add_singer_type'}" class="button" />
	</div>
</form>
{if count($list_info)>0}
 <form action="{url link='current'}" method="post" id="order_display_sb" >
    <table align="center" style="text-align:left;">
    <tr>
        <th><input type='checkbox' onclick="switchCheck();checkDisableStatus()" id='delete_singer_type_check_all' name='delete_singer_type_check_all' /> </th>
        <th>{phrase var='musicsharing.name_upper'}</th>
        <th>{phrase var='musicsharing.option'}</th>
    </tr>

    {foreach from=$list_info key=iKey item=singer}
    <tr  id="tr_singer_{$singer.singertype_id}_checkbox" class='classified_row {if $singer.index % 2 == 0 } classified_even{else} classified_odd{/if}' style="height:30px;">
        <td style="width:10px">
            <input type='checkbox'class="delete_singer_type" id="singer_{$singer.singertype_id}_checkbox" name='delete_singer_type[]' value='{$singer.singertype_id}' onclick="checkDisableStatus()"/>
        </td>
        <td id="js_blog_edit_title{$singer.singertype_id}"><a href="#?type=input&amp;id=js_blog_edit_title{$singer.singertype_id}&amp;content=js_category{$singer.singertype_id}&amp;call=musicsharing.updateSingerType&amp;type_id={$singer.singertype_id}" class="quickEdit" id="js_category{$singer.singertype_id}">{$singer.title|convert|clean}</a></td>
        <td style="width: 200px;">
         <a  href="javascript:void(0);" onclick="if (confirm('{phrase var='musicsharing.are_you_sure' phpfox_squote=true}')) {literal}{{/literal} delete_singer_type('{$singer.singertype_id}','tr_singer_{$singer.singertype_id}_checkbox');{literal}}{/literal} return false;">{phrase var='musicsharing.delete'}</a>
        </td>
    </tr>

    {/foreach}
    </table>
    <div class="table_bottom">
        <input type="submit" name="delete" id="delete" disabled value="{phrase var='musicsharing.delete_selected'}" class="sJsConfirm delete button sJsCheckBoxButton disabled" />
        <input type='hidden' name='task' value='dodelete' />
    </div>
  </form>
{/if}