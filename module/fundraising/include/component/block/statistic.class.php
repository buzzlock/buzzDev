<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

class Fundraising_Component_Block_Statistic extends Phpfox_Component
{
    /**
     * Class process method wnich is used to execute this component.
     */
    public function process()
    {
        $sType = $this->request()->get('sType');

        $aTransactions = $this->getParam('aTransactions', false);

        $aCampaignStats = $this->getParam('aCampaignStats', false);

        $aPager = $this->getParam('aPager');

        Phpfox::getLib('pager')->set(array('page' => $aPager['iPage'], 'size' => $aPager['iLimit'], 'count' => $aPager['iTotal']));

        if($aTransactions && isset($aTransactions) && count($aTransactions)) {
            $sUrl = 'fundraising.list.' . $this->request()->getInt('req3');
        }
        elseif($aCampaignStats && isset($aCampaignStats) && count($aCampaignStats))
            $sUrl = 'admincp.fundraising.statistic';
        else
            $sUrl = 'current';

        $this->template()->assign(array(
            'aTransactions' => $aTransactions,
            'aCampaignStats' => $aCampaignStats,
            'sType' => $sType,
            'sUrl' => $sUrl,
        ));

        return 'block';
    }

}

?>