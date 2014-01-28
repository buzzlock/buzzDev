<?php
defined('PHPFOX') or exit('NO DICE!');

?>
{literal}
<script language="javascript">
    function delete_category(cat_id,div_id){
        var myAlbum = document.getElementById(div_id);
        myAlbum.style.display="none";
        $.ajaxCall('musicsharing.deleteCategory','idCategory='+cat_id);
    }

    function check_all_category(obj){
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
        $('.delete_category').each(function(index, element){
            element.checked = setCheck;
        });
    }
    function switchCheck()
    {
        if(document.getElementById('delete_category_check_all').checked==true)
            switchCheckAll(true);
        else
            switchCheckAll(false);
    }
    function checkDisableStatus(){
        var status = false;
        $('.delete_category').each(function(index, element){
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

<form method="post" action="{url link='admincp.musicsharing.category'}" >
	<div class="table_header">
		{phrase var='musicsharing.category_details'}
	</div>
	<div class="table">
		<div class="table_left">
			{phrase var='musicsharing.category_name'}:
		</div>
		<div class="table_right">
			<input type="text" name="val[title]" value="" id="title" size="40" maxlength="150" />
		</div>
		<div class="clear"></div>
	</div>
	<div class="table_clear">
		<input type="submit" value="{phrase var="musicsharing.add_category"}" class="button" />
	</div>
</form>
{if count($list_info)>0}
 <form action="{url link='current'}" method="post" id="order_display_sb" >
    <table align="center" style="text-align:left;">
    <tr>
        <th><input type='checkbox' onclick="switchCheck();checkDisableStatus()" id='delete_category_check_all' name='delete_category_check_all' /> </th>
        <th>{phrase var='musicsharing.name_upper'}</th>
        <th>{phrase var='musicsharing.option'}</th>
    </tr>

    {foreach from=$list_info key=iKey item=category}
    <tr  id="tr_category_{$category.cat_id}_checkbox" class='classified_row {if $category.index % 2 == 0 } classified_even{else} classified_odd{/if}' style="height:30px;">
        <td style="width:10px">
            <input type='checkbox' class="delete_category" id="category_{$category.cat_id}_checkbox" name='delete_category[]' value='{$category.cat_id}' onclick="checkDisableStatus()"/>
        </td>
        <td id="js_blog_edit_title{$category.cat_id}"><a href="#?type=input&amp;id=js_blog_edit_title{$category.cat_id}&amp;content=js_category{$category.cat_id}&amp;call=musicsharing.updateCategory&amp;cat_id={$category.cat_id}" class="quickEdit" id="js_category{$category.cat_id}">{$category.title|convert|clean}</a></td>
        <td style="width: 200px;">
         <a  href="javascript:void(0);" onclick="if (confirm('{phrase var='musicsharing.are_you_sure' phpfox_squote=true}')) {literal}{{/literal} delete_category('{$category.cat_id}','tr_category_{$category.cat_id}_checkbox');{literal}}{/literal} return false;">{phrase var='musicsharing.delete'}</a>
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