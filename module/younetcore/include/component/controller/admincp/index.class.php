<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

class YouNetCore_Component_Controller_Admincp_Index extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$oService = Phpfox::getService('younetcore.core');
		$oVerify = phpfox::getService('younetcore.verify');
		$oFunctions = phpfox::getService('younetcore.functions');
		$this->template()->setBreadCrumb(Phpfox::getPhrase('younetcore.younet_plugins_manager'));
		$this->template()->setHeader(
			array(
			    'tabs.js'=>'module_younetcore',
			    'sl.js'=>'module_younetcore',
			    'tab.css' =>'module_younetcore',
			    'younet.css' =>'module_younetcore',
			    'slimbox2.css' =>'module_younetcore',
			)
			);
			
		if ($_POST)
		{
			if ($this->request()->get('recheck') != phpfox::getPhrase('younetcore.check_problems'))
			{
				$aVals = $this->request()->getRequests();
				if(isset($aVals['do']))unset($aVals['do']);
				if(isset($aVals['core']))unset($aVals['core']);
				if(isset($aVals['req1']))unset($aVals['req1']);
				if(isset($aVals['req2']))unset($aVals['req2']);
				$product_id = isset($aVals['m'])?$aVals['m']:"";
                $oVerify->verified($aVals,$product_id);
				$this->url()->send('admincp.younetcore');				
			}
			else
			{
				$oService->rmc();
				$oService->rma();
				$this->url()->send('admincp.younetcore');          
			}
            
		}
		$oService->checkYouNetProducts();
		
		$aProducts  = $oService->getPhpFoxProducts(false);

		$this->template()->setTitle(Phpfox::getPhrase('admincp.manage_products'))
			->setFullSite(true)
			->assign(array(
					'aProducts' => $aProducts,
					'sCoreUrl' =>phpfox::getParam('core.path'),
				)
			);
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		//(($sPlugin = Phpfox_Plugin::get('younetcore.component_controller_admincp_index_clean')) ? eval($sPlugin) : false);
	}
}

?>