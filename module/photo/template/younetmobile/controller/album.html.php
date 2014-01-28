<div class="item_view">
	<div id="js_album_content">
		{template file='photo.block.photo-entry'}
		{if Phpfox::getUserParam('photo.can_approve_photos') || Phpfox::getUserParam('photo.can_delete_other_photos')}
		{*moderation*}
		{/if}
	</div>
	<div class="item_info">
	    <div class="ym-user-ava">
            {img user=$aForms suffix='_50_square' max_width=50 max_height=50}
        </div>
         <div class="ym-photo-info">
              <p><a class="ym-fullname" href="{url link='$aForms.user_name'}">{$aForms.full_name}</a></p>
             
                {if ((Phpfox::getUserId() == $aForms.user_id && Phpfox::getUserParam('photo.can_edit_own_photo_album')) || Phpfox::getUserParam('photo.can_edit_other_photo_albums'))
                    || (Phpfox::getUserId() == $aForms.user_id && $aForms.profile_id == '0')
                    || ($aForms.profile_id == '0' && (((Phpfox::getUserId() == $aForms.user_id && Phpfox::getUserParam('photo.can_delete_own_photo_album')) || Phpfox::getUserParam('photo.can_delete_other_photo_albums'))))
                }
                <div class="item_bar">
                    <div class="item_bar_action_holder">
                        <a href="#" class="item_bar_action"><span>{phrase var='photo.actions'}</span></a>       
                        <ul>
                            {template file='photo.block.menu-album'}
                        </ul>           
                    </div>      
                </div>
                {/if}          
          </div>
          
       
    </div>
    
    <div id="js_album_description">
        {$aForms.description|clean}
        
    </div>
	<div {if $aForms.view_id != 0}style="display:none;" class="js_moderation_on"{/if}>
		{module name='feed.comment'}
	</div>	

</div>