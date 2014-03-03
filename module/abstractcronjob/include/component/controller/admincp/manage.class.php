<?php

defined('PHPFOX') or exit('NO DICE!');
 
class Abstractcronjob_Component_Controller_Admincp_Manage extends Phpfox_Component
{

	public function process()
	{	
		//Load Things 
		$oDb = Phpfox::getLib('phpfox.database');
		$aVals = Phpfox::getLib('phpfox.request')->getRequests();
		//print_r($aVals);
		
		//Picture Location 
		$sSiteUrl = str_replace('index.php?do=/','',Phpfox::getParam('core.path'));
		 
		//Set Action 
		$sAction = ''; 
		
		//Perform Update 
		$bCronUpdated = false;
		if(isset($_POST['abstract_form_posted']) && isset($_POST['php_code']) && $_POST['php_code'] != ""){ 
			if(isset($aVals['req4']) && $aVals['req4'] == 'edit' && isset($aVals['req5']) && $aVals['req5'] > 0){ 
				
				$aUpdate['product_id'] = $_POST['product_id'];
				$aUpdate['module_id'] = $_POST['module_id'];
				$aUpdate['php_code'] = $_POST['php_code'];
				$aUpdate['is_active'] = $_POST['is_active'];
				$aUpdate['every'] = $_POST['every'];
				$aUpdate['type_id'] = $_POST['type_id'];
				$oDb->update(Phpfox::getT('cron'), $aUpdate, "cron_id = ".$aVals['req5']);
				$bCronUpdated = true;
				$sAction = 'edit'; 
				Phpfox::addMessage('Cronjob Updated!');
				
				//Recache Cron File
				Phpfox::getLib('cache')->remove(Phpfox::getParam('core.dir_cache').'cron.php', 'path');
			}
		}
		
		//Get Cron For Edit 
		$aCronEdit = array();
		if(isset($aVals['req4']) && $aVals['req4'] == 'edit' && isset($aVals['req5']) && $aVals['req5'] > 0){ 
			
			$aCronEdit = $oDb
				->select('*')
				->from(Phpfox::getT('cron'))
				->where("cron_id = ".$aVals['req5'])
				->execute('getSlaveRow');
				$sAction = 'edit'; 
		}
		
		//Delete Cron 
		if(isset($aVals['req4']) && $aVals['req4'] == 'delete' && isset($aVals['req5']) && $aVals['req5'] > 0){ 
			
			$oDb->delete(Phpfox::getT('cron'), "cron_id = ".$aVals['req5']);
			$sAction = ''; 
			Phpfox::addMessage('Cronjob Deleted!');
			
			//Recache Cron File
			Phpfox::getLib('cache')->remove(Phpfox::getParam('core.dir_cache').'cron.php', 'path');
		}
		
		//Run Now 
		if(isset($aVals['req4']) && $aVals['req4'] == 'run' && isset($aVals['req5']) && $aVals['req5'] > 0){ 
			
			$aCronRun = $oDb
				->select('*')
				->from(Phpfox::getT('cron'))
				->where("cron_id = ".$aVals['req5'])
				->execute('getSlaveRow');
				
				//Execute Code 
				eval($aCronRun['php_code']); 
				
				//Update Last and Next Run 
				$iTimeAdd = 0;
				if($aCronRun['type_id'] == 1){ $iTimeAdd = 60; } 
				if($aCronRun['type_id'] == 2){ $iTimeAdd = 3600; }
				if($aCronRun['type_id'] == 3){ $iTimeAdd = 86400; }
				if($aCronRun['type_id'] == 4){ $iTimeAdd = 30*86400; }
				if($aCronRun['type_id'] == 5){ $iTimeAdd = 365*86400; }
				$aUpdate['last_run'] = time();
				$aUpdate['next_run'] = time() + ( $aCronRun['every'] * $iTimeAdd );
				$oDb->update(Phpfox::getT('cron'), $aUpdate, "cron_id = ".$aCronRun['cron_id']);
				
				Phpfox::addMessage('Cronjob Executed!');
				$sAction = ''; 
				
				//Recache Cron File
				Phpfox::getLib('cache')->remove(Phpfox::getParam('core.dir_cache').'cron.php', 'path');
		}
		 
				
		//Get Cron List 
		$aCrons = array();
		if($sAction == ''){
			$aCrons = $oDb
				->select('*')
				->from(Phpfox::getT('cron'))
				->order('product_id ASC')
				->execute('getSlaveRows');		
				
				foreach($aCrons as $key => $aCron){ 
					
					if($aCron['next_run'] > 0){ 
						$aCrons[$key]['next_run'] = Phpfox::getTime('D, j M Y g:ia', $aCron['next_run']); 
					}
					if($aCron['last_run'] > 0){ 
						$aCrons[$key]['last_run'] = Phpfox::getTime('D, j M Y g:ia', $aCron['last_run']); 
					}
					
					
				}
		}		
		
		
		$this->template()->setTitle('Manage Cronjobs by Abstract Enterprises');
		$this->template()->setBreadCrumb('Manage Cronjobs by Abstract Enterprises', '');
	
		Phpfox::getLib('template')
			->setHeader(array(
					'editarea/edit_area_full.js' => 'static_script',
					'<script type="text/javascript">				
						editAreaLoader.init({
							id: "php_code"	
							,start_highlight: true
							,allow_resize: "both"
							,allow_toggle: false
							,word_wrap: false
							,language: "en"
							,syntax: "php"
						});		
					</script>'
				)
								
			
			)
			->assign(
		
			array(
			
				'aCrons' => $aCrons,
				'aCronEdit' => $aCronEdit,
				'bCronUpdated' => $bCronUpdated,
				'sAction' => $sAction,
				'aProducts' => Phpfox::getService('admincp.product')->get(),
				'aModules' => Phpfox::getService('admincp.module')->getModules(),
				'sSiteUrl' => $sSiteUrl,
			)
		
		);
	}
}

?>

