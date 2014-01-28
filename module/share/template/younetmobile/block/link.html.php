<li class=" ym-share-link{if $sBookmarkDisplay == 'menu'} display-box-item sub_menu_bar_li{/if}">
{if (isset($aFeed) && !isset($aPage)) || (isset($aFeed) && isset($aPage)) }
   <div class="share_popupWrapper">
        <?php
                $aMTLFeed = Phpfox::getLib('template')->getVar('aFeed');        
                $ret = Phpfox::getService('mobiletemplate')->getListOfShare($aMTLFeed, 'feed');
                Phpfox::getLib('template' )->assign( array('listOfShare' => $ret['listOfShare'], 'infoShare' => $ret['infoShare']));
            ?>
            
        <ul>
            {if $sBookmarkDisplay == 'menu'}
                {if isset($listOfShare.post)} <li> <a href="#" onclick="ynmtMobileTemplate.getSharePost('post', '{$sBookmarkType}', '{$sBookmarkUrl}', '{$sBookmarkTitle}', '{$sShareModuleId}', {if isset($sFeedShareId) && $sFeedShareId > 0}'{$sFeedShareId}'{else}null{/if}, {if isset($sFeedType)}1{else}null{/if}); return false;">{$listOfShare.post.title}</a> </li> {/if}
                {if isset($listOfShare.bookmarks)} <li> <a href="#" onclick="ynmtMobileTemplate.getSharePost('bookmark', '{$sBookmarkType}', '{$sBookmarkUrl}', '{$sBookmarkTitle}', '{$sShareModuleId}', {if isset($sFeedShareId) && $sFeedShareId > 0}'{$sFeedShareId}'{else}null{/if}, {if isset($sFeedType)}1{else}null{/if}); return false;">{$listOfShare.bookmarks.title}</a> </li> {/if}
            {elseif $sBookmarkDisplay == 'menu_link'}
                {if isset($listOfShare.post)} <li> <a href="#" onclick="ynmtMobileTemplate.getSharePost('post', '{$sBookmarkType}', '{$sBookmarkUrl}', '{$sBookmarkTitle}', null, null, null); return false;">{$listOfShare.post.title}</a> </li> {/if}
                {if isset($listOfShare.bookmarks)} <li> <a href="#" onclick="ynmtMobileTemplate.getSharePost('bookmark', '{$sBookmarkType}', '{$sBookmarkUrl}', '{$sBookmarkTitle}', null, null, null); return false;">{$listOfShare.bookmarks.title}</a> </li> {/if}
            {elseif $sBookmarkDisplay == 'image'}
                {if isset($listOfShare.post)} <li> <a href="#" onclick="ynmtMobileTemplate.getSharePost('post', '{$sBookmarkType}', '{$sBookmarkUrl}', '{$sBookmarkTitle}', null, null, null); return false;">{$listOfShare.post.title}</a> </li> {/if}
                {if isset($listOfShare.bookmarks)} <li> <a href="#" onclick="ynmtMobileTemplate.getSharePost('bookmark', '{$sBookmarkType}', '{$sBookmarkUrl}', '{$sBookmarkTitle}', null, null, null); return false;">{$listOfShare.bookmarks.title}</a> </li> {/if}
            {else}
                {if isset($listOfShare.post)} <li> <a href="#" onclick="ynmtMobileTemplate.getSharePost('post', '{$sBookmarkType}', '{$sBookmarkUrl}', '{$sBookmarkTitle}', null, null, null); return false;">{$listOfShare.post.title}</a> </li> {/if}
                {if isset($listOfShare.bookmarks)} <li> <a href="#" onclick="ynmtMobileTemplate.getSharePost('bookmark', '{$sBookmarkType}', '{$sBookmarkUrl}', '{$sBookmarkTitle}', null, null, null); return false;">{$listOfShare.bookmarks.title}</a> </li> {/if}
            {/if}        
        </ul>
        
    </div>
{/if}

{if (isset($aPage) && !isset($aFeed))}
    <div class="share_popupWrapper">
		<?php
			$aMTLFeed = Phpfox::getLib('template')->getVar('aPage');		
			$ret = Phpfox::getService('mobiletemplate')->getListOfShare($aMTLFeed, 'pages');
			Phpfox::getLib('template' )->assign( array('listOfShare' => $ret['listOfShare'], 'infoShare' => $ret['infoShare']));
		?>

        <ul>
            {if $sBookmarkDisplay == 'menu'}
                {if isset($listOfShare.post)} <li> <a href="#" onclick="ynmtMobileTemplate.getSharePost('post', '{$sBookmarkType}', '{$sBookmarkUrl}', '{$sBookmarkTitle}', '{$sShareModuleId}', {if isset($sFeedShareId) && $sFeedShareId > 0}'{$sFeedShareId}'{else}null{/if}, {if isset($sFeedType)}1{else}null{/if}); return false;">{$listOfShare.post.title}</a> </li> {/if}
                {if isset($listOfShare.bookmarks)} <li> <a href="#" onclick="ynmtMobileTemplate.getSharePost('bookmark', '{$sBookmarkType}', '{$sBookmarkUrl}', '{$sBookmarkTitle}', '{$sShareModuleId}', {if isset($sFeedShareId) && $sFeedShareId > 0}'{$sFeedShareId}'{else}null{/if}, {if isset($sFeedType)}1{else}null{/if}); return false;">{$listOfShare.bookmarks.title}</a> </li> {/if}
            {elseif $sBookmarkDisplay == 'menu_link'}
                {if isset($listOfShare.post)} <li> <a href="#" onclick="ynmtMobileTemplate.getSharePost('post', '{$sBookmarkType}', '{$sBookmarkUrl}', '{$sBookmarkTitle}', null, null, null); return false;">{$listOfShare.post.title}</a> </li> {/if}
                {if isset($listOfShare.bookmarks)} <li> <a href="#" onclick="ynmtMobileTemplate.getSharePost('bookmark', '{$sBookmarkType}', '{$sBookmarkUrl}', '{$sBookmarkTitle}', null, null, null); return false;">{$listOfShare.bookmarks.title}</a> </li> {/if}
            {elseif $sBookmarkDisplay == 'image'}
                {if isset($listOfShare.post)} <li> <a href="#" onclick="ynmtMobileTemplate.getSharePost('post', '{$sBookmarkType}', '{$sBookmarkUrl}', '{$sBookmarkTitle}', null, null, null); return false;">{$listOfShare.post.title}</a> </li> {/if}
                {if isset($listOfShare.bookmarks)} <li> <a href="#" onclick="ynmtMobileTemplate.getSharePost('bookmark', '{$sBookmarkType}', '{$sBookmarkUrl}', '{$sBookmarkTitle}', null, null, null); return false;">{$listOfShare.bookmarks.title}</a> </li> {/if}
            {else}
                {if isset($listOfShare.post)} <li> <a href="#" onclick="ynmtMobileTemplate.getSharePost('post', '{$sBookmarkType}', '{$sBookmarkUrl}', '{$sBookmarkTitle}', null, null, null); return false;">{$listOfShare.post.title}</a> </li> {/if}
                {if isset($listOfShare.bookmarks)} <li> <a href="#" onclick="ynmtMobileTemplate.getSharePost('bookmark', '{$sBookmarkType}', '{$sBookmarkUrl}', '{$sBookmarkTitle}', null, null, null); return false;">{$listOfShare.bookmarks.title}</a> </li> {/if}
            {/if}        
        </ul>
        
    </div>
{/if}

{if $sBookmarkDisplay == 'menu'}
    <a href="#" onclick="return false;"{if $bIsFirstLink} class="first"{/if}>
        <div class="share_popup_arrow share_popup_bottom"></div>
        <i class="icon-share"></i>{phrase var='share.share'}
    </a>
{elseif $sBookmarkDisplay == 'menu_link'}
<a href="#" onclick="return false;"{if $bIsFirstLink} class="first"{/if}>
    <div class="share_popup_arrow share_popup_bottom"></div>
    {img theme='icon/share.png' class='item_bar_image'} <i class="icon-share"></i>{phrase var='share.share'}

</a>
{elseif $sBookmarkDisplay == 'image'}
<a href="#" onclick="return false;">
     <div class="share_popup_arrow share_popup_bottom"></div>
    {img theme='misc/icn_share.png' class='v_middle'} <i class="icon-share"></i>{phrase var='share.share'}
  </a>
{else}
<a href="#">{img theme='misc/add.png' alt='' style='vertical-align:middle;'}</a> 
<a href="#" onclick="return false;">
    <div class="share_popup_arrow share_popup_bottom"></div>
    <i class="icon-share"></i>{phrase var='share.share'}
</a>
{/if}


</li>