<div class="pages_view_sub_menu" id="pages_menu">
	<ul class="display-box ym-button-profile">
	    {if !Phpfox::getUserBy('profile_page_id')}
            <li class="display-box-item" id="js_add_pages_unlike" {if !$aPage.is_liked} style="display:none;"{/if}>
                <a href="#" onclick="$(this).parent().hide(); $('#pages_like_join_position').show(); $.ajaxCall('like.delete', 'type_id=pages&amp;item_id={$aPage.page_id}'); return false;">
                    {if $aPage.page_type == '1'}{phrase var='pages.remove_membership'}
                    {else}
                    {phrase var='pages.unlike'}{/if}
                 </a>
             </li>
             <li class="display-box-item" {if $aPage.is_liked} style="display:none;"{/if}>
                 {if !Phpfox::getUserBy('profile_page_id') && Phpfox::isUser()}
                    {if isset($aPage) && $aPage.reg_method == '2' && !isset($aPage.is_invited) && $aPage.page_type == '1'}
                    {else}
                        {if isset($aPage) && isset($aPage.is_reg) && $aPage.is_reg}
                        {else}
                            
                            {if isset($aPage) && !isset($aPage.is_liked) && $aPage.is_liked != true}
                                {if !isset($aUser) || !isset($aUser.use_timeline)}
                                        <span id="pages_like_join_position"{if $aPage.is_liked} style="display:none;"{/if}> 
                                 {/if}
                                    <a href="#" id="pages_like_join" {if isset($aUser) && isset($aUser.use_timeline) && $aUser.use_timeline}style=""{/if}onclick="$(this).parent().hide(); $('#js_add_pages_unlike').show(); {if $aPage.page_type == '1' && $aPage.reg_method == '1'} $.ajaxCall('pages.signup', 'page_id={$aPage.page_id}'); {else}$.ajaxCall('mobiletemplate.likeAdd', 'type_id=pages&amp;item_id={$aPage.page_id}');{/if} return false;">
                                        {if $aPage.page_type == '1' }
                                            {phrase var='pages.join'}
                                        {else}
                                            {phrase var='pages.like'}
                                        {/if}
                                    </a>
                                {if !isset($aUser) || !isset($aUser.use_timeline)}</span>{/if}
                            {/if}
                        {/if}
                    {/if}
                {/if}
             </li>
             
        {/if} 
        {*  
		{if $aPage.is_admin}
			<li class="display-box-item"><a href="{url link='pages.add' id=$aPage.page_id}">{phrase var='pages.edit_page'}</a></li>		
		{/if}*}

		{module name='share.link' type='pages' url=$aPage.link title=$aPage.title display='menu' sharefeedid=$aPage.page_id sharemodule='pages'}
		
		{if !$aPage.is_admin && Phpfox::getUserParam('pages.can_claim_page') && empty($aPage.claim_id)}
			<li class="display-box-item">
				<a href="#?call=contact.showQuickContact&amp;height=600&amp;width=600&amp;page_id={$aPage.page_id}" class="inlinePopup js_claim_page" title="{phrase var='pages.claim_page'}">
					{phrase var='pages.claim_page'}
				</a>
			</li>
		{/if}
	</ul>
</div>