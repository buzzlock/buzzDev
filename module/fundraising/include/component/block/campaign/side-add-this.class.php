<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

class Fundraising_Component_Block_Campaign_Side_Add_This extends Phpfox_Component
{
    /**
     * use for add this
     * Creates parameters to send to the API based on current
     * widget settings
     *
     * @return array
     */
//    protected function getServiceQuery() {
//        $aQuery   = array();
//        $pubid = Phpfox::getParam('fundraising.addthis_pubid');
//        $domain = Phpfox::getParam('fundraising.addthis_domain');
//        $period = Phpfox::getParam('fundraising.addthis_period');
//        $aQuery[] = new QueryParameter('pubid',$pubid);
//        $aQuery[] = new QueryParameter('period', $period);
//        $aQuery[] = new QueryParameter('domain', $domain);
//        return $aQuery;
//    }

    /**
     * Returns the content for the given analytics data
     *
     * @param array $data
     * @return string
     */
//    protected function getDataContent($data,$url) {
//        $content = '';
//        foreach($data as $oData) {
//            if($oData->url == $url)
//            {
//                foreach($oData as $key => $value) {
//                    return $value;
//                }
//            }
//        }
//        return '0';
//    }

    /**
     * Return the widgets content for given data
     *
     * @param array $response
     * @return string
     */
//    public function getContent($data,$url) {
//        if(empty($data)) {
//            return "0";
//        }
//        return $this->getDataContent($data,$url);;
//    }

    /**
     * Main process of widget
     *
     * @param array $args
     * @param array $instance
     */
//    public function widget($metric,$url = null) {
//        $username = Phpfox::getParam('fundraising.addthis_username');
//        $password = Phpfox::getParam('fundraising.addthis_password');
//        if(empty($username) || empty($password))
//            return 0;
//        $dimension = 'url';
//        $oRequest = new Request(
//            new Authentication($username, $password),
//            new Metric($metric),
//            new Dimension($dimension),
//            $this->getServiceQuery()
//        );
//        $oService  = new Service($oRequest);
//        $response  = $oService->getData();
//
//        //var_dump($response);
//        //echo $this->getContent($response,$url);
//        return $this->getContent($response,$url);
//        //return $response[0];
//
//    }

    /**
     * Class process method wnich is used to execute this component.
     */
    public function process()
    {
        //create token for this current user

        $aCampaign = $this->getParam('aFrCampaign');

        //$sString = 'create_token_' . Phpfox::getUserId();

        $sToken = md5(Phpfox::getUserId());


        //get turnback token for another user shared

        $iBackId = $this->request()->get('user');
        if(isset($iBackId) && !empty($iBackId)) {
            if($iBackId != Phpfox::getUserId()) {
                $aUserId = Phpfox::getService('fundraising.user')->getUserIdList();
                foreach($aUserId as $iUserId) {
                    if($iUserId['user_id'] == $iBackId) {
                        Phpfox::getService('fundraising.user')->updateSupporter('comeback', $iBackId);
                        break;
                    }
                }
            }
        }
        // get share , click ...

        list($iShare, $iClick) = Phpfox::getService('fundraising.user')->getShareClick($aCampaign['campaign_id']);
        $iViralLift = ($iShare!=0)?round(($iClick*100)/$iShare,2):'0';

		if(!$iClick)
		{
			$iClick = 0;
		}

		if(!$iShare)
		{
			$iShare = 0;
		}
        $this->template()->assign(array(
                'sHeader' => '',
                'aCampaign' => $aCampaign,
                'sToken' => $sToken,
                'iShare' => $iShare,
                'iClick' => $iClick,
                'iViralLift' => $iViralLift,
            )
        );
        return 'block';
    }

}

?>