<?php

defined('PHPFOX') or exit('NO DICE!');
 
class Abstractcronjob_Component_Controller_Admincp_Add extends Phpfox_Component
{

	public function process()
	{	
		
		$oDb = Phpfox::getLib('phpfox.database');
		
		$aNewCron = $_POST;
		
		$bCronCreated = false;
		$bCronCreatedError = false;
		if(isset($_POST['abstract_form_posted']) 
			&& isset($_POST['product_id']) && $_POST['product_id'] != "" 
			&& isset($_POST['module_id']) && $_POST['module_id'] != "" 
			&& isset($_POST['is_active']) && $_POST['is_active'] != "" 
			&& isset($_POST['type_id']) && $_POST['type_id'] != "" 
			&& isset($_POST['every']) && $_POST['every'] != "" 
			&& isset($_POST['php_code']) && $_POST['php_code'] != "" 
		){ 
			
			$aInsert['product_id'] = $_POST['product_id'];
			$aInsert['module_id'] = $_POST['module_id'];
			$aInsert['is_active'] = $_POST['is_active'];
			$aInsert['type_id'] = $_POST['type_id'];
			$aInsert['every'] = $_POST['every'];
			$aInsert['php_code'] = $_POST['php_code'];
			$iId = $oDb->insert(Phpfox::getT('cron'), $aInsert); 
			Phpfox::addMessage('Cronjob Created!');
			$bCronCreated = true; 
			
			//Recache Cron File
			Phpfox::getLib('cache')->remove(Phpfox::getParam('core.dir_cache').'cron.php', 'path');
		}
		
		if(isset($_POST['abstract_form_posted']) && $bCronCreated != true){ 
			$bCronCreatedError = true;
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
			
				'aNewCron' => $aNewCron,
				'aProducts' => Phpfox::getService('admincp.product')->get(),
				'aModules' => Phpfox::getService('admincp.module')->getModules(),
				'bCronCreatedError' => $bCronCreatedError,
			)
		
		);
	}
}

?>

