<?php

defined('PHPFOX') or exit('NO DICE!');

class MobileTemplate_Component_Controller_Admincp_Managenavigation extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$oService = Phpfox::getService('mobiletemplate');
		$oServiceProcess = Phpfox::getService('mobiletemplate.process');

		//	get data
		$mobileMenus = Phpfox::getService('mobile')->getMenu();
		$menuNavigation = $oService->getAllMenuNavigation();

		//	get max ordering
		$maxOrdering = 0;
		if(is_array($menuNavigation) && count($menuNavigation) > 0){
			$maxOrdering = (int) $menuNavigation[count($menuNavigation) - 1]['ordering'];
		}

		// check 
		foreach($mobileMenus as $menuKey => $menuVal){
			$isInMenuAndNotInNav = true;

			foreach($menuNavigation as $navKey => $navVal){
				if($menuVal['url'] == $navVal['url']){
					$isInMenuAndNotInNav = false;
					break;
				}
			}

			if($isInMenuAndNotInNav == true){
				//	add new menu navigation
				$maxOrdering = $maxOrdering + 1;
				$oServiceProcess->addMenuNavigation(array('url' => $menuVal['url'], 'orginal_var_name' => $menuVal['module'] . '.' . $menuVal['var_name'], 'display_name' => '', 'ordering' => $maxOrdering));
			}
		}
		foreach($menuNavigation as $navKey => $navVal){
			$isInNavAndNotInMenu = true;

			foreach($mobileMenus as $menuKey => $menuVal){
				if($menuVal['url'] == $navVal['url']){
					$isInNavAndNotInMenu = false;
					break;
				}
			}

			if($isInNavAndNotInMenu == true){
				//	delete not used menu navigation
				$oServiceProcess->deleteMenuNavigationByID($navVal['menu_id']);
			}
		}

		//	refresh menu navigation
        $sCacheId = Phpfox::getLib('cache')->set('mt_leftnavi');
        Phpfox::getLib('cache')->remove($sCacheId);

		$refreshMenuNavigation = $oService->getAllMenuNavigation();
		foreach($refreshMenuNavigation as $navKey => $navVal){
			if(empty($navVal['display_name']) == true){
				$refreshMenuNavigation[$navKey]['display_name'] = Phpfox::getPhrase($navVal['orginal_var_name']);
			}
		}

		$this->template()->setTitle(Phpfox::getPhrase('mobiletemplate.manage_menu_navigation'));
		$this->template()->setBreadcrumb(Phpfox::getPhrase('mobiletemplate.manage_menu_navigation'), $this->url()->makeUrl('admincp.mobiletemplate.managenavigation'));
        $this->template()->assign(array(
        	'refreshMenuNavigation' => $refreshMenuNavigation 
            )
        );
        $this->template()->setHeader('cache', array(
            'drag.js' => 'static_script',
            '<script type="text/javascript">$Behavior.mtmmnCoreDrad = function() { Core_drag.init({table: \'#js_drag_drop\', ajax: \'mobiletemplate.updateMenuNavigationOrdering\'}); } </script>'
                ));
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('mobiletemplate.component_controller_admincp_managenavigation_clean')) ? eval($sPlugin) : false);
	}
}

?>