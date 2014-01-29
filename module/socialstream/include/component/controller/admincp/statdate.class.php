<?php

/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * 
 * 
 * @copyright		YOUNETCO
 * @author  		AnNT
 * @package 		YouNet SocialStream
 * @version 		3.03
 */
class SocialStream_Component_Controller_Admincp_StatDate extends Phpfox_Component
{
    /**
     * Class process method wnich is used to execute this component.
     */
    public function process()
    {
        if (!$iPage = $this->request()->getInt('page'))
        {
            $iPage = 1;
        }
        if (!$iLimit = $this->request()->getInt('show'))
        {
            $iLimit = 10;
        }

        $sFrom = $this->request()->get('from');
        $sTo = $this->request()->get('to');

        $aStats = array();

        if (!empty($sFrom) && !empty($sTo))
        {
            $aFrom = explode('-', $sFrom);
            $aTo = explode('-', $sTo);
            
            if(!checkdate($aFrom[0], $aFrom[1], $aFrom[2]) || !checkdate($aTo[0], $aTo[1], $aTo[2]))
            {
                $this->url()->send('admincp.socialstream.statdate', null, Phpfox::getPhrase('socialstream.invalid_date'));
            }
            if($aFrom[2]>2037 || $aTo[2]>2037)
            {
                $this->url()->send('admincp.socialstream.statdate', null, Phpfox::getPhrase('socialstream.currently_we_do_not_support_the_year_greater_than_2037'));
            }
            
            $iStartTime = mktime(0, 0, 0, $aFrom[0], $aFrom[1], $aFrom[2]);
            $iEndTime = mktime(23, 59, 59, $aTo[0], $aTo[1], $aTo[2]);
            
            if ($iEndTime < $iStartTime)
            {
                $this->url()->send('admincp.socialstream.statdate', null, Phpfox::getPhrase('socialstream.the_end_date_must_be_greater_than_or_equal_to_the_start_date'));
            }
            
            list($iCnt, $aStats) = Phpfox::getService('socialstream.services')->getStatsByDate($iStartTime, $iEndTime, $iPage, $iLimit);
            
            $this->template()->assign('aForms', array(
                'from_month' => $aFrom[0],
                'from_day' => $aFrom[1],
                'from_year' => $aFrom[2],
                'to_month' => $aTo[0],
                'to_day' => $aTo[1],
                'to_year' => $aTo [2]
            ));
        }
        else
        {
            list($iCnt, $aStats) = Phpfox::getService('socialstream.services')->getStatsByDate(null, null, $iPage, $iLimit);
            
            if($iCnt > 0)
            {
                $iEndTime = strtotime($aStats[0]['feeds_date']) + 86399 + ($iPage-1)*$iLimit*86400;
                $iStartTime = $iEndTime - $iCnt*86400 + 1;
            }
            else
            {
                $iStartTime = strtotime('today');
                $iEndTime = $iStartTime + 86399;
            }
            
            $this->template()->assign('aForms', array(
                'from_month' => date('n', $iStartTime),
                'from_day' => date('j', $iStartTime),
                'from_year' => date('Y', $iStartTime),
                'to_month' => date('n', $iEndTime),
                'to_day' => date('j', $iEndTime),
                'to_year' => date('Y', $iEndTime)
            ));
        }

        foreach ($aStats as $k => $aStat)
        {
            $aStats[$k]['feeds_date'] = date('F j, Y', strtotime($aStat['feeds_date']));
        }

        Phpfox::getLib('pager')->set(array('page' => $iPage, 'size' => $iLimit, 'count' => $iCnt));

        $aServices = Phpfox::getService('socialstream.services')->getProviders();

        $this->template()->setTitle(Phpfox::getPhrase('socialstream.statistics_by_date'))
            ->setBreadcrumb(Phpfox::getPhrase('socialstream.statistics_by_date'))
            ->setHeader(array('admin.css' => 'module_socialstream'))
            ->assign(array(
                'aServices' => $aServices,
                'aStats' => $aStats
            ));
    }

    /**
     * Garbage collector. Is executed after this class has completed
     * its job and the template has also been displayed.
     */
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('socialstream.component_controller_admincp_statdate_clean')) ? eval($sPlugin) : false);
    }
}

?>