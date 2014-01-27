<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

class Fundraising_Component_Controller_Admincp_Index extends Phpfox_Component
{
	/**
	 * Class process method which is used to execute this component.
	 */
	public function process()
	{	
		if (($iId = $this->request()->getInt('approve')))
		{			
			if (Phpfox::getService('fundraising.campaign.process')->approve($iId))
			{
				$this->url()->send('admincp.fundraising', null, Phpfox::getPhrase('fundraising.fundraising_successfully_approved'));
			}
		}		
						
		if (($iId = $this->request()->getInt('delete')))
		{
			if (Phpfox::getService('fundraising.campaign.process')->delete($iId))
			{
				$this->url()->send('admincp.fundraising', null, Phpfox::getPhrase('fundraising.fundraising_successfully_deleted'));
			}
		}
		
		$iPage = $this->request()->getInt('page');
		
		$aPages = array(5, 10, 15, 20);
		$aDisplays = array();
		foreach ($aPages as $iPageCnt)
		{
			$aDisplays[$iPageCnt] = Phpfox::getPhrase('core.per_page', array('total' => $iPageCnt));
		}		
		
		$aSorts = array(
			'end_time' => Phpfox::getPhrase('fundraising.expired_date'),
			'total_view' => Phpfox::getPhrase('fundraising.most_viewed'),
			'total_like' => Phpfox::getPhrase('fundraising.most_liked'),
			'total_donate' => Phpfox::getPhrase('fundraising.most_donated')
		);
		
		$aStatus = array('%' => Phpfox::getPhrase('fundraising.any'),
			Phpfox::getService('fundraising.campaign')->getStatusCode('closed') => Phpfox::getPhrase('fundraising.closed'),
			Phpfox::getService('fundraising.campaign')->getStatusCode('ongoing') => Phpfox::getPhrase('fundraising.on_going'),
			Phpfox::getService('fundraising.campaign')->getStatusCode('expired') => Phpfox::getPhrase('fundraising.expired'),
			Phpfox::getService('fundraising.campaign')->getStatusCode('reached') => Phpfox::getPhrase('fundraising.reached'),
			Phpfox::getService('fundraising.campaign')->getStatusCode('draft') => Phpfox::getPhrase('fundraising.draft'),
			Phpfox::getService('fundraising.campaign')->getStatusCode('pending') => Phpfox::getPhrase('fundraising.pending'),
		);
		$aFeatured = array('%'=>Phpfox::getPhrase('fundraising.any'),				   
				   '%1%'=>Phpfox::getPhrase('core.yes'),
				   '%0%'=>Phpfox::getPhrase('core.no')
				  );

		$aFilters = array(
			'search' => array(
				'type' => 'input:text',
				'search' => "AND campaign.title LIKE '%[VALUE]%'"
			),	
			'user' => array(
				'type' => 'input:text',
				'search' => "AND u.full_name LIKE '%[VALUE]%'"
			),
			'status' => array(
				'type' => 'select',
				'options' => $aStatus,				
				'search' => "AND campaign.status LIKE '[VALUE]'"
			),
			'featured' => array(
				'type' => 'select',
				'options' => $aFeatured,
				'search' => "AND campaign.is_featured LIKE '[VALUE]'",
			),
			'approved' => array(
				'type' => 'select',
				'options' => $aFeatured,
				'search' => "AND campaign.is_approved LIKE '[VALUE]'",
			),
			'pages' => array(
				'type' => 'select',
				'options' => array('%'=>Phpfox::getPhrase('fundraising.any'),				   
								'pages'=>Phpfox::getPhrase('core.yes'),
								'fundraising'=>Phpfox::getPhrase('core.no')
							    ),
				'search' => "AND campaign.module_id LIKE '[VALUE]'",
			),
			'display' => array(
				'type' => 'select',
				'options' => $aDisplays,
				'alias' => 'p',
				'default' => '10'
			),
			'sort' => array(
				'type' => 'select',
				'options' => $aSorts,
				'default' => 'start_time',
				'alias' => 'campaign'
			),
			'sort_by' => array(
				'type' => 'select',
				'options' => array(
					'DESC' => Phpfox::getPhrase('core.descending'),
					'ASC' => Phpfox::getPhrase('core.ascending')
				),
				'default' => 'DESC'
			),
		);		
		
		$oSearch = Phpfox::getLib('search')->set(array(
				'type' => 'fundraising',
				'filters' => $aFilters,
				'search' => 'search'
			)
		);
		
		$iLimit = $oSearch->getDisplay();
		
		list($iCnt, $aCampaigns) = Phpfox::getService('fundraising.campaign')->searchCampaigns($oSearch->getConditions(), $oSearch->getSort(), $oSearch->getPage(), $iLimit);
				
		Phpfox::getLib('pager')->set(array('page' => $iPage, 'size' => $iLimit, 'count' => $oSearch->getSearchTotal($iCnt)));

		foreach($aCampaigns as &$aCampaign)
		{
			$aCampaign = Phpfox::getService('fundraising.campaign')->retrieveMoreInfoFromCampaign($aCampaign, true);
		}
		
		$this->template()->setTitle(Phpfox::getPhrase('fundraising.fundraising'))
			->setBreadcrumb(Phpfox::getPhrase('fundraising.manage_campaigns'), $this->url()->makeUrl('admincp.fundraising'))
			->assign(array(
					'aCampaigns' 	=> $aCampaigns,
					'aStatus'	=> $aStatus,
					'iTotalResults' => $iCnt,
					'aCampaignStatus' => Phpfox::getService('fundraising.campaign')->getAllStatus()
				)
			)
			->setHeader('cache', array(
				'quick_edit.js' => 'static_script',
                        'admin.js'      => 'module_fundraising'
			)			
		);

	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('fundraising.component_controller_admincp_index_clean')) ? eval($sPlugin) : false);
	}
}

?>
