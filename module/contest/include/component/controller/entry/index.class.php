<?php

defined('PHPFOX') or exit('NO DICE!');

class Contest_Component_Controller_Entry_Index extends Phpfox_component {
    
    private $_aParentModule = null;

    private function _buildSubsectionMenu() {
        if ($this->_aParentModule === null && !defined('PHPFOX_IS_USER_PROFILE') && !defined('PHPFOX_IS_PAGES_VIEW')) {
            Phpfox::getService('contest.helper')->buildMenu();
        }
    }

    private function _view($sView) {

        switch ($sView) {
            case 'pending_entries':
                Phpfox::isUser(true);
				if(!Phpfox::isAdmin())
                	$this->search()->setCondition('AND en.status = 0 and ct.user_id = '. PHpfox::getUserId());
				else
					$this->search()->setCondition('AND en.status = 0 ');
                break;
            default:
                Phpfox::isUser(true);
                $this->search()->setCondition('AND en.user_id = ' . Phpfox::getUserId());
                break;
        }
    }

    public function process()
    {
        Phpfox::getService('contest.contest.process')->checkAndUpdateStatusOfContests();
        
        $this->template()->setBreadcrumb(Phpfox::getPhrase('contest.contest'), $this->url()->makeUrl('contest'));

        $this->_buildSubsectionMenu();

        //search contest
        $aSearchNumber = array(10, 20, 30, 40);
        $sActionUrl = $this->url()->makeUrl('contest', array('view' => $this->request()->get('view')));
        $this->search()->set(
                array(
                    'type' => 'entry',
                    'field' => 'en.entry_id',
                    'search' => 'search',
                    'search_tool' => array(
                        'table_alias' => 'en',
                        'search' => array(
                            'action' => $sActionUrl,
                            'default_value' => Phpfox::getPhrase('contest.search_entries'),
                            'name' => 'search',
                            'field' => 'en.title'
                        ),
                        'sort' => array(
                            'latest' => array('en.time_stamp', Phpfox::getPhrase('contest.lastest')),
                            'most-viewed' => array('en.total_view', Phpfox::getPhrase('contest.most_viewed')),
                            'most-vote' => array('en.total_vote', Phpfox::getPhrase('contest.most_voted')),
                            'most-liked' => array('en.total_like', Phpfox::getPhrase('contest.most_liked')),
                        ),
                        'show' => $aSearchNumber
                    )
                )
        );
        
        $sView = $this->request()->get('view', false);

        $sDefaultType = Phpfox::getService('contest.entry')->getDefaultSearchType($sView);
        $sType = $this->request()->get('type', $sDefaultType);
        $iType = Phpfox::getService('contest.constant')->getContestTypeIdByTypeName($sType);
        $this->search()->setCondition('AND en.type = '. $iType);

        $aBrowseParams = array(
            'module_id' => 'contest',
            'alias' => 'en',
            'field' => 'entry_id',
            'table' => Phpfox::getT('contest_entry'),
            'hide_view' => array('my')
        );

        $this->_view($sView);

        $this->search()->browse()->params($aBrowseParams)->execute();
        $aEntries = $this->search()->browse()->getRows();

        //here we should and a funtion corresponding with entry/view
        //
        foreach($aEntries as $key=>$aEntry)
		{
			$aEntry['status_entry'] = $aEntry['status'];

			$aEntry['approve'] = $aEntry['status_entry']==1?0:1;
			$aEntry['deny'] = $aEntry['status_entry']==2?0:1;
			$is_entry_winning = Phpfox::getService("contest.entry")->CheckExistEntryWinning($aEntry['entry_id']);
			$aEntry['winning'] = ($aEntry['contest_status']==5 && $is_entry_winning==0)?1:0;
			$aEntry['offaction'] = 0;
			if($aEntry['contest_user_id']!=Phpfox::getUserId() && !PHpfox::isAdmin())
			{
				$aEntry['offaction'] = 1;
			}

            $aEntry = Phpfox::getService('contest.entry')->retrieveEntryPermission($aEntry);
            
			$aEntries[$key] = $aEntry;

		}

        Phpfox::getLib('pager')->set(array('page' => $this->search()->getPage(), 'size' => $this->search()->getDisplay(), 'count' => $this->search()->browse()->getCount()));

        $this->template()->assign(array(
            'aEntries' => $aEntries,
            'sView' => $sView,
            'sType' => $sType,
            'bIsEntryIndex' => true,
			'corepath' => phpfox::getParam('core.path')
        ))->setHeader(
                array(
                    'pager.css' => 'style_css',
					'yncontest.css' => 'module_contest',
                    'yncontest.js' => 'module_contest'
                )
        );

    }

}