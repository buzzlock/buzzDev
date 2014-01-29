<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

class SocialPublishers_Component_Controller_Admincp_Statisticuser extends Phpfox_Component
{
    public function delete()
    {
        // Delete case.
        $sDelete = $this->request()->get('delete');

        if ($sDelete != '')
        {
            if (Phpfox::getService('socialpublishers.statisticuser.process')->deleteAll())
            {
                $this->url()->send('admincp.socialpublishers.statisticuser', null, Phpfox::getPhrase('socialpublishers.statistic_by_user_successfully_deleted'));
            }
            else
            {
                $this->url()->send('admincp.socialpublishers.statisticuser', null, Phpfox::getPhrase('socialpublishers.delete_statistic_by_user_fail'));
            }
        }
    }
    
    public function process()
    {
        Phpfox::isUser(true);
        
        $this->delete();
        
        // Get current page.
        $iPage = $this->request()->getInt('page');

        // Get array of limit.
        $aPages = array(10, 20, 50, 100);

        $aDisplays = array();
        foreach ($aPages as $iPageCnt)
        {
            $aDisplays[$iPageCnt] = Phpfox::getPhrase('core.per_page', array('total' => $iPageCnt));
        }

        // Config the sort conditions.
        $aSorts = array(
            'id' => Phpfox::getPhrase('socialpublishers.id'),
            'full_name' => Phpfox::getPhrase('socialpublishers.full_name'),
            'total_facebook_post' => Phpfox::getPhrase('socialpublishers.facebook'),
            'total_twitter_post' => Phpfox::getPhrase('socialpublishers.twitter'),
            'total_linkedin_post' => Phpfox::getPhrase('socialpublishers.linkedin')
        );

        // Compose the filter.
        $aFilters = array(
            'full_name' => array(
                'type' => 'input:text',
                'search' => "AND u.full_name = '%[VALUE]%'"
            ),
            'display' => array(
                'type' => 'select',
                'options' => $aDisplays,
                'default' => '10'
            ),
            'sort' => array(
                'type' => 'select',
                'options' => $aSorts,
                'default' => 'id',
                'alias' => 'su'
            ),
            'sort_by' => array(
                'type' => 'select',
                'options' => array(
                    'DESC' => Phpfox::getPhrase('core.descending'),
                    'ASC' => Phpfox::getPhrase('core.ascending')
                ),
                'default' => 'DESC'
            )
        );

        $aSearchConfig = array(
            'type' => 'socialpublishers',
            'filters' => $aFilters,
            'search' => 'search'
        );
        
        // Get the limit.
        $iLimit = $this->search()->set($aSearchConfig)->getDisplay();

        list($iCount, $aItems) = Phpfox::getService('socialpublishers.statisticuser')->adminGet($this->search()->getConditions(), $this->search()->getSort(), $this->search()->getPage(), $iLimit);
        
        Phpfox::getLib('pager')->set(array('page' => $iPage, 'size' => $iLimit, 'count' => $this->search()->getSearchTotal($iCount)));

        $aParams = array(
            'aItems' => $aItems,
            'iCount' => $iCount
        );
        
        $this->template()
                ->setBreadcrumb(Phpfox::getPhrase('socialpublishers.statistic_by_user'), $this->url()->makeUrl('admincp.socialpublishers.statisticuser'))
                ->assign($aParams)
                ->setPhrase(array('socialpublishers.are_you_sure'));
    }
    
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('socialpublishers.component_controller_admincp_statisticuser_clean')) ? eval($sPlugin) : false);
	}
}

?>