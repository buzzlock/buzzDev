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
{if Phpfox::isMobile()}
    {literal}
        <style type="text/css">
            .mobile_section_menu{display: none !important;}
            #section_menu{display:none !important;}
        </style>
    {/literal}
{/if}
{template file='musicsharing.block.mexpect'}

<script language="javascript" type="text/javascript">
	$Behavior.initMyPLSpgs = function(){ldelim}
		$("#formpost").unbind("submit");
		$("#delete").unbind("submit");
	{rdelim};
    function delete_playlist(playlist_id,div_id){ldelim}
        var myPlaylist = document.getElementById(div_id);
        myPlaylist.style.display="none";
        $.ajaxCall('musicsharing.deletePlaylist','idplaylist='+playlist_id);
    {rdelim}

    function check_all_playlist(obj){ldelim}
        var root = obj.parentNode.parentNode.parentNode;
        for(i=0; i<root.childNodes.length; i++){ldelim}
            if(document.getElementById(root.childNodes[i].id+"_checkbox") != null){ldelim}
                document.getElementById(root.childNodes[i].id+"_checkbox").checked = obj.checked;
            {rdelim}
        {rdelim}
    {rdelim}

    function jsReload(){ldelim}
        window.location = window.location;
    {rdelim}

    function setDeletePlaylistButtonStatus(status) {ldelim}
		return false;
        if(status){ldelim}
           jQuery('input#delete').attr("disabled", '');
        {rdelim}else{ldelim}
           jQuery('input#delete').attr("disabled", 'disabled');
        {rdelim}
    {rdelim}
    function checkDisableStatus(){ldelim}
		return false;
        var status = false;
        $('.playlist_checkbox').each(function(index, element){ldelim}
            if(element.checked==true){ldelim}
                status =  true;
            {rdelim}
        {rdelim});
        setDeletePlaylistButtonStatus(status);
        return status;
    {rdelim}
</script>

<div>
  {phrase var="musicsharing.list_all_your_posted_playlists"}.
  {phrase var="musicsharing.total_playlists"}: {$total_playlist}
  <br /><br />
</div>

{* SHOW BUTTONS *}
<!-- FullSite -->
{if !phpfox::isMobile()}
<div class="space-line"></div>
<table class='tabs' cellpadding='0' cellspacing='0'>
<tr class="background-header">
    <td valign="middle" class='tab2' NOWRAP><a href='{if !isset($aParentModule)}{url link="musicsharing.myalbums"}{else}{url link=$aParentModule.module_id.".".$aParentModule.item_id.".musicsharing.myalbums"}{/if}'>{phrase var='musicsharing.my_albums'}</a></td><td valign="middle" class='tab'>&nbsp;</td>
    <td valign="middle" class='tab1' NOWRAP><a href='{if !isset($aParentModule)}{url link="musicsharing.myplaylists"}{else}{url link=$aParentModule.module_id.".".$aParentModule.item_id.".musicsharing.myplaylists"}{/if}'>{phrase var='musicsharing.my_playlists'}</a></td><td valign="middle" class='tab'>&nbsp;</td>
    <td valign="middle" class='tab3'>&nbsp;</td>
</tr>
</table>

<div class='margin-top-0 margin-bottom-0'>
   <div class='button_music float-left'>
   {if isset($settings.max_playlist_created) && $settings.max_playlist_created > $total_playlist}
    <img src='{$core_path}module/musicsharing/static/image/music/plus16.gif' border='0' class="icon"><a href='{if !isset($aParentModule)}{url link='musicsharing.createplaylist'}{else}{url link=$aParentModule.module_id.".".$aParentModule.item_id.".musicsharing.createplaylist"}{/if}'>{phrase var='musicsharing.create_new_playlist_fp'}</a>
    {else}
    <div style="color:red">
        {phrase var='musicsharing.you_have_reach_to_limit_for_number_of_playlists_please_contact_admin_to_get_more_information'}
   </div>
    {/if}
  </div>
  <div class="div-clear"></div>
</div>
<br />
<form action="{url link='musicsharing.myplaylists'}" method="post" id="formpost">
{if $total_playlist == 0 }
   <div align="center"> {phrase var='musicsharing.there_is_no_any_playlists_yet'}  <a href="{if !isset($aParentModule)}{url link='musicsharing.createplaylist'}{else}{url link=$aParentModule.module_id.".".$aParentModule.item_id.".musicsharing.createplaylist"}{/if}"> {phrase var='musicsharing.please_create_a_playlist'} !</a> </div>
{else}
	<table cellpadding='0' cellspacing='0' width='100%' align="center">
    <tr class="background-header-2">
		<td valign="middle" width="5%" class="myplaylist-cellid" style="vertical-align: middle; font-weight: bold; text-align: center"> ID </td>
		<td valign="middle" class="classified_browse myplaylist-delete_album_check_all" style="vertical-align: middle;text-align: left;">
			<input type='checkbox' onclick="javascript:check_all_playlist(this);checkDisableStatus()" id='delete_album_check_all' name='delete_album_check_all'/>
		</td>
		<td style="vertical-align: middle;" valign="middle" class="classified_browse myplaylist-playlist_name">
			{phrase var='musicsharing.playlist_name'}
		</td>
		<td style="vertical-align: middle;" valign="middle" class="classified_browse myplaylist-number_of_songs">
			{phrase var='musicsharing.number_of_songs'}
		</td>
		<td valign="middle" class="classified_browse myplaylist-actions" style="vertical-align: middle;border-right: none;">{phrase var='musicsharing.actions'}</td>
		<td style="vertical-align: middle;" valign="middle" class="classified_browse myplaylist-actions">{phrase var='musicsharing.default'}</td>
    </tr>
	{foreach from=$list_info  item=iPlaylist }
		{template file='musicsharing.block.myplaylist_info'}
	{/foreach}
	<tr>
		<td valign="middle" colspan="6" class="classified_bottom ncpaginator" class="padding-top-5">
			<div class="table_bottom">
				<input type="submit" onclick="return confirm(getPhrase('core.are_you_sure'));" name="delete" id="delete" value="{phrase var='musicsharing.delete_selected_playlist'}" class="delete button sJsCheckBoxButton" />
				<input type='hidden' name='task' value='dodelete' />
			</div>
			{pager}
		</td>
	</tr>
</table>
{/if}
</form>
<!-- Mobile Site -->
{else}
	<div class="m_album_playlist"> 
		{if count($list_info)>0}
			{foreach from=$list_info key=index item=aAlbum}
				{template file='musicsharing.block.mobile.playlist-info'}
			  {/foreach}
		{else}
		<div align="left" class="red margin-right-10 margin-bottom-10 margin-top-10"> {phrase var='musicsharing.there_is_no_any_playlists_yet'}. <!-- <a href="{url link='musicsharing.createplaylist'}"> Please create a playlist !</a> --></div>
		{/if}
    <div style="div-clear" ></div>
    {pager}
</div>

{/if}

