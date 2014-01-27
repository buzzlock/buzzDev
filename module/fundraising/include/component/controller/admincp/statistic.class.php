<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

class Fundraising_Component_Controller_Admincp_Statistic extends Phpfox_Component
{
	/**
	 * Class process method which is used to execute this component.
	 */
	public function process() {
        $iPage = $this->request()->getInt('page');
        $iLimit = 10;
        $iTotal = 0;

        $aFilters = array(
            'keyword' => array(
                'type' => 'input:text',
                'search' =>  '(fc.title LIKE "%[VALUE]%" OR u.full_name LIKE "%[VALUE]%" OR ft.paypal_transaction_id = "[VALUE]" OR ft.amount = "[VALUE]") ',
                'size' => 45,
            ),
        );

        $oSearch = Phpfox::getLib('search')->set(array(
                'type' => 'fundraising',
                'filters' => $aFilters,
                'search' => 'search'
            )
        );

        $sKeyword = $oSearch->get('keyword');
        $sFromDate = strtotime($oSearch->get('fromdate'));
        $sToDate = strtotime($oSearch->get('todate'));

        if($sFromDate && $sToDate)
        {
            $iStartTime = Phpfox::getLib('date')->mktime( 0, 0, 0, date('m',$sFromDate), date('d',$sFromDate), date('Y',$sFromDate));
            $iEndTime = Phpfox::getLib('date')->mktime( 23, 59, 59, date('m',$sToDate), date('d',$sToDate), date('Y',$sToDate));

            if ($iStartTime > $iEndTime)
            {
                $iTemp = $iStartTime;
                $iStartTime = $iEndTime;
                $iEndTime = $iTemp;
            }

            $oSearch->setCondition('AND ft.time_stamp > ' . $iStartTime . ' AND ft.time_stamp < ' . $iEndTime);
        }

        list($iTotal, $aCampaignStats) = Phpfox::getService('fundraising.transaction')->getTransactionForAllCampaign($oSearch->getConditions(), $oSearch->getPage(), $iLimit);

        $this->setParam('aCampaignStats', $aCampaignStats);

        $this->setParam('aPager', array( 'iPage' => $iPage, 'iLimit' => $iLimit, 'iTotal' => $iTotal));

        $sView = '';$iTransaction = 0;$aDetail = null;
        $sView = $this->request()->get('view');
        if($sView == 'detail') {
            $iTransaction = $this->request()->getInt('transaction');
            $aTransaction = Phpfox::getService('fundraising.transaction')->getTransactionById($iTransaction);
            $aDonor = Phpfox::getService('fundraising.user')->getDonorNameById($aTransaction['donor_id']);
			if(!$aDonor)
			{
				$aInvoice = unserialize($aTransaction['invoice']);
				$aDonor = $aInvoice;
				if($aInvoice['is_guest'])
				{
					$aDonor['guest_full_name'] = $aInvoice['full_name'];
				}
				else
				{
					$aDonor['full_name'] = Phpfox::getService('fundraising.user')->getFullNameOfUser($aInvoice['user_id']);
				}
			}

            $aCampaign = Phpfox::getService('fundraising.campaign')->getCampaignById($aTransaction['campaign_id']);

            $aDetail = array(
                'donor_name' => $aDonor['is_guest']? $aDonor['guest_full_name'] : $aDonor['full_name'],
                'campaign_name' => $aCampaign['title'],
                'donate_date' => $aTransaction['time_stamp'],
                'description' => $aCampaign['short_description'],
                'donation_amount' => $aTransaction['amount'],
                'transaction_id' => $aTransaction['paypal_transaction_id'],
                'sBackUrl' => 'admincp.fundraising.statistic',
            );
        }

        $sFromDate = $sFromDate ? date('m/d/Y', $sFromDate) : date('m/d/Y');
        $sToDate = $sToDate ? date('m/d/Y', $sToDate) : date('m/d/Y');

        $this->template()->setTitle(Phpfox::getPhrase('fundraising.statistic'))
            ->setBreadcrumb(Phpfox::getPhrase('fundraising.statistic'), $this->url()->makeUrl('admincp.statistic'))
            ->setHeader('cache', array(
            'jquery/plugin/jquery.highlightFade.js' => 'static_script',
            'jquery/plugin/jquery.scrollTo.js' => 'static_script',
            'pager.css' => 'style_css',
            'feed.js' => 'module_feed',
            ))
            ->assign(array(
            'sViewFr' => $sView,
            'aTransaction' => $aDetail,
            'sFromDate' => $sFromDate,
            'sToDate' => $sToDate,
            ));


		$this->template()->setHeader(
				array(
					'global.css' => 'module_fundraising',
					'ynfundraising.css' => 'module_fundraising',
					'view.css' => 'module_fundraising',
				)
		);
		
	}
	
}

?>