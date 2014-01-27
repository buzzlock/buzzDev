<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

class Fundraising_Component_Block_Search extends Phpfox_Component
{
	public function process()
	{
		return false;
		$aCategories = Phpfox::getService('fundraising.category')->getCategories('c.user_id = 0');
		if (!is_array($aCategories))
		{
			return false;
		}
		
		if (!$aCategories)
		{
			return false;
		}

		$aRow = array();
		$sStartTime = $this->request()->get('from');
		$aRow['start_time_search'] = str_replace('_','/',$sStartTime);
		$sEndTime = $this->request()->get('to');
		$aRow['end_time_search'] = str_replace('_','/',$sEndTime);
						
		$sView = $this->request()->get('view');
		
		$iStatus = 2;
		$sStatus = $this->getParam('iStatus');
		if($sStatus !== '')
		{
			$iStatus = (int)$sStatus;
		}
		/*
		$iStatus = 2;
		$sStatus = $this->request()->get('status');
		if($sStatus != '')
		{               
			 $iStatus = (int)$sStatus;
		}
		*/
		$iCategoryFundraisingView = $this->request()->getInt('req3');
		
		$iChecked = 'false';
		
		if($sStartTime != '' || $sEndTime != '')
		{
			$iChecked = 'true';
		}
		
		$this->template()->assign(array(
			'sHeader'	=> Phpfox::getPhrase('fundraising.search'),
			'sUrl'	 	=> Phpfox::getLib('url')->makeUrl('fundraising'),			
			'iCategoryFundraisingView' => $iCategoryFundraisingView,
			'aCategories'	=> $aCategories,
			'iStatus'	=> $iStatus,
			'sView'		=> $sView,
			'iChecked' => $iChecked 
			)
		);
		
		if(!empty($aRow))
		{
			$this->template()->assign(array('aForms'=> $aRow,
								  'corepath' => Phpfox::getParam('core.path')
								  ));
		}
		
		return 'block';
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('fundraising.component_block_search_clean')) ? eval($sPlugin) : false);
	}
}

?>
