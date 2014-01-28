{if !$bIsUsersProfilePage && count($aSubMenus)}
	
			{foreach from=$aSubMenus key=iKey name=submenu item=aSubMenu}
			<li class="mobile_main_menu"><a href="{url link=$aSubMenu.url)}" {if isset($aSubMenu.css_name)}class="{$aSubMenu.css_name} no_ajax_link"{/if}>{if substr($aSubMenu.url, -4) == '.add' || substr($aSubMenu.url, -7) == '.upload' || substr($aSubMenu.url, -8) == '.compose'}{img theme='layout/section_menu_add.png' class='v_middle'}{/if}{phrase var=$aSubMenu.module'.'$aSubMenu.var_name}</a></li>
			{/foreach}
		
	{/if}