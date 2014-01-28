<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright        YouNet
 * @author          Minh Nguyen
 * @package         Phpfox
 * @version         $Id: song.html.php 1318 2010-12-10  $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
{literal}
<script language="javascript">
    function delete_song(song_id,div_id){
        var myAlbum = document.getElementById(div_id);
        myAlbum.style.display="none";
        $.ajaxCall('musicsharing.deleteAlbumSong','idSong='+song_id);    
    }

    function check_all_song(obj){
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
    function setDeleteSongButtonStatus(status) {
       if(status){
           $('.delete').removeClass('disabled');
           $('.delete').attr('disabled', false);
       }else{
          $('.delete').addClass('disabled');
          $('.delete').attr('disabled', true);
       }
    }
    function switchCheckAll(setCheck){
        $('.delete_song').each(function(index, element){
            element.checked = setCheck;
        });
    }
    function switchCheck()
    {
        if(document.getElementById('delete_song_check_all').checked==true)
            switchCheckAll(true);
        else
            switchCheckAll(false);
    }
    function checkDisableStatus(){
        var status = false;
        $('.delete_song').each(function(index, element){
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
        setDeleteSongButtonStatus(status);
        return status;
    }
</script>
{/literal}

<form method="post" action="{url link='admincp.musicsharing.managesong'}">
<div class="table_header">
    {phrase var='musicsharing.search_filter'}
</div>
<div class="table">
    <div class="table_left">
       {phrase var='musicsharing.search_for_text'}:
    </div>
    <div class="table_right">
        {$aFilters.song_name}
    </div>
    <div class="clear"></div>
</div>
 <div class="table">
    <div class="table_left">
       {phrase var='musicsharing.sort_by'}:
    </div>
    <div class="table_right">
         {$aFilters.sort_by}
    </div>
    <div class="clear"></div>
</div>
<div class="table_clear">
    <input type="submit" name="search[submit]" value="{phrase var='core.submit'}" class="button" />
    <input type="submit" name="search[reset]" value="{phrase var='core.reset'}" class="button" />
    
</div>
</form>
{if count($list_info)>0}
 <form action="{url link='current'}" method="post" id="order_display_sb" >    
    <table align="center" style="text-align:left;">
    <tr>          
        <th><input type='checkbox' onclick="switchCheck();checkDisableStatus()" id='delete_song_check_all' name='delete_song_check_all' /> </th>
        <th>{phrase var='musicsharing.name_upper'}</th>
        <th>{phrase var='musicsharing.album_name'}</th>
        <th>{phrase var='musicsharing.uploaded_by'}</th>
        <th>{phrase var='musicsharing.plays'}</th>
        <th>{phrase var='musicsharing.option'}</th>
    </tr>
    
    {foreach from=$list_info key=iKey item=song}
    <tr id="tr_song_{$song.song_id}_checkbox" class='classified_row {if $song.index % 2 == 0 } classified_even{else} classified_odd{/if}' style="height:30px;">
        <td style="width:10px">
            <input type='checkbox' class="delete_song" id="song_{$song.song_id}_checkbox" name='delete_song[]' value='{$song.song_id}' onclick="checkDisableStatus()"/>
        </td>
        <td>{$song.title}</td>
        <td>{$song.album_title}</td>
        <td>{$song.full_name}</td>
        <td><a href="{url link = 'musicsharing.listen.music_'.$song.song_id}">{$song.play_count}</a></td>
        <td>           
         <a  href="javascript:void(0);" onclick="if (confirm('{phrase var='musicsharing.are_you_sure' phpfox_squote=true}')) {literal}{{/literal} delete_song('{$song.song_id}','tr_song_{$song.song_id}_checkbox');{literal}}{/literal} return false;">{phrase var='musicsharing.delete'}</a>
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
<div style="padding-left:5px ; padding-right: 5px;"> {pager}  </div>
