<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

/**
 * @copyright		[YOUNETCO]
 * @author  		NghiDV
 * @package  		Module_Donation
 * @version 		$Id: callback.class.php 1 2012-02-15 10:33:17Z YOUNETCO $
 */
class Donation_Service_Callback extends Phpfox_Service {

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->_sTable = Phpfox::getT('donation');
    }

    public function paymentApiCallback($aParam)
    {
        if ($aParam['status'] == 'completed')
        {
            $iInvoiceId = $aParam['custom'];
            $aInvoice = Phpfox::getService('donation')->getInvoice($iInvoiceId);
            $iPageId = $aInvoice['page_id'];
            $iUserId = $aInvoice['user_id'];
            $bNotShowName = $aInvoice['not_show_name'];
            $bNotShowFeed = $aInvoice['not_show_feed'];
            $bNotShowMoney = $aInvoice['not_show_money'];
            $bIsGuest = $aInvoice['is_guest'];
            $fQuantity = (float) $aParam['total_paid'];
            $sCurrency = isset($aParam['currency']) ? $aParam['currency'] : $aInvoice['currency'];
            Phpfox::getService('donation.process')->addToDonationLists($iUserId, $iPageId, $fQuantity, $bNotShowName, $bNotShowFeed, $bNotShowMoney, $bIsGuest, $sCurrency);
        }
    }

    public function getTotalItemCount($iUserId)
    {
        return array(
            'field' => 'total_donation',
            'total' => $this->database()
                    ->select('COUNT(*)')
                    ->from(Phpfox::getT('donation_pages'))
                    ->where('user_id = ' . (int) $iUserId)
                    ->execute('getSlaveField')
        );
    }

    public function getActivityPointField()
    {
        return array(
            Phpfox::getPhrase('donation.donations') => 'activity_donation'
        );
    }

    public function getDashboardActivity()
    {
        $aUser = Phpfox::getService('user')->get(Phpfox::getUserId(), true);
        return array(
            Phpfox::getPhrase('donation.donations') => $aUser['activity_donation']
        );
    }

    public function getActivityFeed($aRow)
    {
        if ($aRow['parent_user_id'] == 0)
        {
            $aDonation = $this->database()
                    ->select('*')
                    ->from(phpfox::getT('donation_pages'))
                    ->where('page_id = -1 AND donation_id = ' . $aRow['item_id'])
                    ->execute('getRow');
            if (empty($aDonation))
            {
                return false;
            }
            else
            {
                $aReturn = array(
                    'feed_title' => '',
                    'feed_info' => Phpfox::getPhrase('donation.fullname_donated_to_our_website'),
                    'feed_icon' => Phpfox::getLib('image.helper')->display(array('theme' => 'misc/comment.png', 'return_url' => true)),
                    'time_stamp' => $aRow['time_stamp'],
                    'enable_like' => false,
                    'feed_link' => Phpfox::getLib('url')->makeUrl('phpfox_full_site'),
                );
            }
        }
        else
        {
            $aPage = $this->database()
                    ->select('p.page_id, p.title')
                    ->from(phpfox::getT('donation_pages'), 'dc')
                    ->join(phpfox::getT('pages'), 'p', 'p.page_id = dc.page_id')
                    ->where('dc.donation_id = ' . $aRow['item_id'])
                    ->execute('getRow');
            if (empty($aPage))
            {
                return false;
            }
            $sPageLink = Phpfox::getLib('url')->makeUrl('pages.' . $aPage['page_id']);
            $aReturn = array(
                'feed_title' => '',
                'feed_info' => Phpfox::getPhrase('donation.fullname_donates_a_page', array('link' => $sPageLink, 'page_name' => $aPage['title'])),
                'feed_link' => $sPageLink,
                'feed_icon' => Phpfox::getLib('image.helper')->display(array('theme' => 'misc/comment.png', 'return_url' => true)),
                'time_stamp' => $aRow['time_stamp'],
                'enable_like' => false
            );
        }
        return $aReturn;
    }

    public function onDeleteUser($iUser)
    {
        $aDonationIds = $this->database()
                ->select('dp.donation_id')
                ->from(phpfox::getT('donation_pages'), 'dp')
                ->where('dp.user_id = ' . $iUser)
                ->execute('getRows');
        if (!empty($aDonationIds))
        {
            foreach ($aDonationIds as $aId)
            {
                $this->database()->delete(phpfox::getT('donation_pages'), 'donation_id = ' . $aId['donation_id']);
            }
        }
    }

    /**
     * If a call is made to an unknown method attempt to connect
     * it to a specific plug-in with the same name thus allowing 
     * plug-in developers the ability to extend classes.
     *
     * @param string $sMethod is the name of the method
     * @param array $aArguments is the array of arguments of being passed
     */
    public function __call($sMethod, $aArguments)
    {
        /**
         * Check if such a plug-in exists and if it does call it.
         */
        if ($sPlugin = Phpfox_Plugin::get('donation.service_callback__call'))
        {
            return eval($sPlugin);
        }

        /**
         * No method or plug-in found we must throw a error.
         */
        Phpfox_Error::trigger('Call to undefined method ' . __CLASS__ . '::' . $sMethod . '()', E_USER_ERROR);
    }

}

?>
