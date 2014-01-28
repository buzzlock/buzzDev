<?php
/**
 * [PHPFOX_HEADER]
 *
 * @copyright		YouNet
 * @author  		Minh Nguyen
 * @package 		Phpfox
 * @version 		$Id: song.html.php 1318 2010-12-10  $
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
    function delete_album(album_id,div_id){ldelim}
        var myAlbum = document.getElementById(div_id);
        myAlbum.style.display = "none";
        $.ajaxCall('musicsharing.deleteAlbum','idalbum='+album_id);
    {rdelim}

    function check_all_album(obj){ldelim}
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
	
    function setDeleteAlbumButtonStatus(status) {ldelim}
        if(status){ldelim}
            jQuery('input#delete').attr("disabled", '');
        {rdelim}else{ldelim}
            jQuery('input#delete').attr("disabled", 'disabled');
        {rdelim}
    {rdelim}
    function checkDisableStatus(){ldelim}
        return true;
        var status = false;
        $('.album_checkbox').each(function(index, element){ldelim}
            if(element.checked==true){ldelim}
                status =  true;
            {rdelim}
        {rdelim});
        setDeleteAlbumButtonStatus(status);
        return status;
    {rdelim}
$Behavior.MusicSharingMyAlbums = function() {ldelim}
    $(document).ready(function(){ldelim}
        var deleteButton = $("#delete");

        deleteButton.click(function(evt){ldelim}
            evt.preventDefault();
            var aCheckboxs = $("input.album_checkbox:checked");
            if(!aCheckboxs.size()) return false;
            if(confirm(getPhrase('core.are_you_sure'))){ldelim}
                var sIds = "-1";
                aCheckboxs.each(function(){ldelim}
                    var $this = $(this);
                    sIds += "," + $this.val();
                {rdelim});
                $.ajaxCall('musicsharing.deleteAlbums','sIds=' + sIds, "POST");
            {rdelim}
            return false;
        {rdelim});
    {rdelim});
{rdelim}
</script>

{*
	<img src='{$core_path}module/musicsharing/static/image/music/music_icon.gif' border='0' class='icon_big' class="margin-bottom-15">
	<div class='page_header'>{phrase var='musicsharing.menu_musicsharing_my_albums'}</div>
*}
<div>
    {phrase var='musicsharing.this_is_all_of_your_posted_albums'}. {phrase var='musicsharing.total_albums'}: {$total_album}
	<br/><br/>
</div>

{* SHOW BUTTONS *}
{if !phpfox::isMobile()}
    <div class="space-line"></div>
    <table class='tabs' cellpadding='0' cellspacing='0'>
        <tr class="myalbums-background-header">
            <td valign="middle" class='tab1' NOWRAP><a href='{if !isset($aParentModule)}{url link="musicsharing.myalbums"}{else}{url link=$aParentModule.module_id.".".$aParentModule.item_id.".musicsharing.myalbums"}{/if}'>{phrase var='musicsharing.my_albums'}</a></td><td valign="middle" class='tab'>&nbsp;</td>
            <td valign="middle" class='tab2' NOWRAP><a href='{if !isset($aParentModule)}{url link="musicsharing.myplaylists"}{else}{url link=$aParentModule.module_id.".".$aParentModule.item_id.".musicsharing.myplaylists"}{/if}'>{phrase var='musicsharing.my_playlists'}</a></td><td valign="middle" class='tab'>&nbsp;</td>
            <td valign="middle" class='tab3'>&nbsp;</td>
        </tr>
    </table>

    <div class='margin-top-0 margin-bottom-0'>
        <div class='button_music float-left'>
            {if !isset($settings.max_album_created) ||  $settings.max_album_created <= $total_album}
            <div style="color:red">
                {phrase var='musicsharing.you_have_reach_to_limit_for_number_of_albums_please_contact_admin_to_get_more_information'}
            </div>
            {else}
            <img src='{$core_path}module/musicsharing/static/image/music/plus16.gif' border='0' class="icon"><a href='{if !isset($aParentModule)}{url link='musicsharing.createalbum'}{else}{url link=$aParentModule.module_id.".".$aParentModule.item_id.".musicsharing.createalbum"}{/if} '>{phrase var='musicsharing.create_new_album'}</a>
            {/if}
        </div>
        <div class='div-clear'></div>
    </div>
    <br />
    {if $total_album == 0}
    <div align="center"> {phrase var='musicsharing.there_is_no_any_albums_yet'}.  <a href="{if !isset($aParentModule)}{url link='musicsharing.createalbum'}{else}{url link=$aParentModule.module_id.".".$aParentModule.item_id.".musicsharing.createalbum"}{/if}"> {phrase var='musicsharing.please_create_a_album'}</a> </div>
    {else}
    <form action="{url link='musicsharing.myalbums'}" method="post">
		<table cellpadding='0' cellspacing='0' width='100%' align="center">
			<tr class="myalbums-background-header-2">
				<td valign="middle" class="myalbums-tablecell-id line-middle" width="5%" style="vertical-align: middle;font-weight: bold; text-align: center">
					ID
				</td>
				<td valign="middle" class="classified_browse line-middle myalbums-delete_album_check_all" style="vertical-align: middle;text-align: left;vertical-align: middle;">
					<input type='checkbox' onclick="javascript:check_all_album(this);checkDisableStatus();" id='delete_album_check_all' name='delete_album_check_all' />
				</td>
				<td style="vertical-align: middle;" valign="middle" class="classified_browse myalbums-album_name line-middle">
					{phrase var='musicsharing.album_name'}
				</td>
				<td style="vertical-align: middle;" valign="middle" class="classified_browse myalbums-plays line-middle">
					{phrase var='musicsharing.plays'}
				</td>
				<td style="vertical-align: middle;" valign="middle" class="classified_browse myalbums-number_of_songs line-middle">
					{phrase var='musicsharing.number_of_songs'}
				</td>
				<td style="vertical-align: middle;" valign="middle" class="classified_browse myalbums-actions line-middle" width="22%">
					{phrase var='musicsharing.actions'}
				</td>
			</tr>
			{foreach from=$list_info  item=iAlbum }
				{template file='musicsharing.block.myalbum_info'}
			{/foreach}
			<tr>
				<td valign="middle" colspan="6" class="classified_bottom padding-top-5 ncpaginator">
					<div class="table_bottom">
						<input type="submit" onclick="/* return confirm(getPhrase('core.are_you_sure')); */" name="delete" id="delete" value="{phrase var='musicsharing.delete_selected_album'}" class="delete button sJsCheckBoxButton" />
						<input type='hidden' name='task' value='dodelete' />
					</div>
					{pager}
				</td>
			</tr>
		</table>
    </form>
    {/if}
<!-- Mobile Site -->
{else}
<div class="album_list m_album_playlist">
	<div class="margin-top-0">
	</div>
	<div class="list"> 
		{if count($list_info)>0}
			{foreach from=$list_info key=index item=aAlbum}
				{template file='musicsharing.block.mobile.album_info'}
			{/foreach}
		{else}
			<div align="left" class="red margin-right-10 margin-bottom-10 margin-top-10" style="">{phrase var='musicsharing.there_is_no_album_yet'}</div> 
		{/if}
		<br class="clear">
	</div>
</div>
<div class="clear">
</div>
{pager} 
{/if}