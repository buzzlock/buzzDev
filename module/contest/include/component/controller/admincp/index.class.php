<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

class Contest_Component_Controller_Admincp_Index extends Phpfox_Component
{
	/**
	 * Class process method which is used to execute this component.
	 */
	public function process()
	{	
		if (($iId = $this->request()->getInt('approve')))
		{			
			if (Phpfox::getService('contest.contest.process')->approve($iId))
			{
				$this->url()->send('admincp.contest', null, Phpfox::getPhrase('contest.contest_successfully_approved'));
			}
		}		
						
		if (($iId = $this->request()->getInt('delete')))
		{
			if (Phpfox::getService('contest.contest.process')->delete($iId))
			{
				$this->url()->send('admincp.contest', null, Phpfox::getPhrase('contest.contest_successfully_deleted'));
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
			'end_time' => Phpfox::getPhrase('contest.end_date'),
			'total_like' => Phpfox::getPhrase('contest.most_liked'),
			'total_vote' => Phpfox::getPhrase('contest.most_voted'),
		);
		$aAllContestStatus = Phpfox::getService('contest.constant')->getAllContestStatus();

		$aStatus = array('%' => Phpfox::getPhrase('contest.any'),
			
		);

		foreach ($aAllContestStatus as $aContestStatus) {
			$aStatus[$aContestStatus['id']] = Phpfox::getPhrase('contest.' . $aContestStatus['name']);
		}
		$aFeatured = array('%'=>Phpfox::getPhrase('contest.any'),				   
				   '%1%'=>Phpfox::getPhrase('core.yes'),
				   '%0%'=>Phpfox::getPhrase('core.no')
				  );

		$aFilters = array(
			'search' => array(
				'type' => 'input:text',
				'search' => "AND contest.contest_name LIKE '%[VALUE]%'"
			),	
			'user' => array(
				'type' => 'input:text',
				'search' => "AND u.full_name LIKE '%[VALUE]%'"
			),
			'status' => array(
				'type' => 'select',
				'options' => $aStatus,				
				'search' => "AND contest.contest_status LIKE '%[VALUE]%'"
			),
			'featured' => array(
				'type' => 'select',
				'options' => $aFeatured,
				'search' => "AND contest.is_feature LIKE '[VALUE]'",
			),
			'premium' => array(
				'type' => 'select',
				'options' => $aFeatured,
				'search' => "AND contest.is_premium LIKE '[VALUE]'",
			),
			'ending_soon' => array(
				'type' => 'select',
				'options' => $aFeatured,
				'search' => "AND contest.is_ending_soon LIKE '[VALUE]'",
			),
			'approved' => array(
				'type' => 'select',
				'options' => $aFeatured,
				'search' => "AND contest.is_approved LIKE '[VALUE]'",
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
				'alias' => 'contest'
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
				'type' => 'contest',
				'filters' => $aFilters,
				'search' => 'search'
			)
		);
		
		$iLimit = $oSearch->getDisplay();
		
		list($iCnt, $aContests) = Phpfox::getService('contest.contest')->searchContests($oSearch->getConditions(), $oSearch->getSort(), $oSearch->getPage(), $iLimit);
				
		Phpfox::getLib('pager')->set(array('page' => $iPage, 'size' => $iLimit, 'count' => $oSearch->getSearchTotal($iCnt)));

		foreach ($aContests as &$aContest) {
             $aContest = Phpfox::getService('contest.contest')->retrieveContestPermissions($aContest);
        }
		
		$this->template()->setTitle(Phpfox::getPhrase('contest.contest'))
			->setBreadcrumb(Phpfox::getPhrase('contest.manage_contests'), $this->url()->makeUrl('admincp.contest'))
			->assign(array(
					'aContests' 	=> $aContests,
					'aStatus'	=> $aStatus,
					'iTotalResults' => $iCnt,
					'aContestStatus' => $aAllContestStatus
				)
			)
			->setHeader('cache', array(
				'quick_edit.js' => 'static_script',
                'admin.js'      => 'module_contest'
			)			
		);

	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('contest.component_controller_admincp_index_clean')) ? eval($sPlugin) : false);
	}
}

?>
