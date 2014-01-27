<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

class Fundraising_Component_Block_Detail extends Phpfox_Component 
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$iLimit = 5; $iTotal = 0;
		$iId = $this->getParam('id');
		$iPage = $this->getParam('page') ? $this->getParam('page') : 1;
		//$aFundraising = Phpfox::getService('fundraising.campaign')->getCampaignById($iCampaignId = $iId );
        //$aCampaign = Phpfox::getService('fundraising.campaign')->getMockupCampaignForEdit();
        //$aCampaign = $this->getParam('aFrCampaign',false);
        $aCampaign = Phpfox::getService('fundraising.campaign')->getCampaignById($iId);
		$sType = $this->getParam('sType');

        if(!empty($aCampaign['gmap']) && Phpfox::getLib('parse.format')->isSerialized($aCampaign['gmap']))
        {
            $gmap = unserialize($aCampaign['gmap']);
            $aCampaign['latitude'] = $gmap['latitude'];
            $aCampaign['longitude'] = $gmap['longitude'];
        }

		if($sType == 'donations')
		{
            $iTotal = Phpfox::getService('fundraising.user')->getTotalDonorsOfCampaign($aCampaign['campaign_id']);
			$aDonations = Phpfox::getService('fundraising.user')->getDonorsOfCampaign($aCampaign['campaign_id'], $iLimit, $iPage, $iTotal);
			foreach($aDonations as &$aDonor)
		{
			$aDonor['amount_text'] = Phpfox::getService('fundraising')->getCurrencyText($aDonor['amount'], $aDonor['currency']);
		}

			$aCampaign['donations'] = $aDonations;
		}
		else if($sType == 'news')
		{			
			$aValidation = array(
				'news_headline' => array(
					'def' => 'required',
					'title' => 'Please fill in news headline'
				),
				'news_content' => array(
					'def' => 'required',
					'title' => 'Please fill in news content'
				)
			);
					
			$oValid = Phpfox::getLib('validator')->set(array(
					'sFormName' => 'js_form_news', 
					'aParams' => $aValidation
				)
			);
			
			list($iTotal, $aNews) = Phpfox::getService('fundraising')->getNews($iId, $iPage*$iLimit);
            $aCampaign['news'] = $aNews;

            $sCheckFormNewsLink = "<script type=\"text/javascript\">
                function checkFormNewsLink()
                {
                    var sLink = $('#news_link').val();
                    if($.trim(sLink).length == 0)
                        return true;
                    if ($.trim(sLink).search(/(http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/) == -1)
                    {
                        $('#js_form_news_msg').message('" . Phpfox::getPhrase('fundraising.please_provide_a_valid_url') . "', 'error');
                        $('#news_link').addClass('alert_input');
                        return false;
                    }
                    return true;
                }
                </script>";
                                       
			$this->template()->assign(array(
				'sCreateJs' => $oValid->createJS(),
				'sGetJsForm' => $oValid->getJsForm(),
                        'sCheckFormNewsLink' => $sCheckFormNewsLink
			));			
		}

		Phpfox::getLib('pager')->set(array('page' => $iPage, 'size' => $iLimit, 'count' => $iTotal, 'ajax'=>'fundraising.displayDetail','aParams' => array('id' => $iId,'sType'=>$sType)));
		
		$this->template()->assign(array(
			'aCampaign' 	=> $aCampaign,
			'sType'		=> $sType 
		));
		if(empty($aCampaign['contact_about_me']))
        {
            $aMenus = array(
                Phpfox::getPhrase('fundraising.description')=> '#fundraising.displayDetail?sType=description&id='.$iId,
                Phpfox::getPhrase('fundraising.donors_upper')=>'#fundraising.displayDetail?sType=donations&id='.$iId,
                Phpfox::getPhrase('fundraising.news')=>'#fundraising.displayDetail?sType=news&id='.$iId
            );
        }
        else
        {
            $aMenus = array(
                Phpfox::getPhrase('fundraising.description')=> '#fundraising.displayDetail?sType=description&id='.$iId,
                Phpfox::getPhrase('fundraising.donors_upper')=>'#fundraising.displayDetail?sType=donations&id='.$iId,
                Phpfox::getPhrase('fundraising.news')=>'#fundraising.displayDetail?sType=news&id='.$iId,
                Phpfox::getPhrase('fundraising.about_us')=>'#fundraising.displayDetail?sType=about&id='.$iId,
            );
        }
		if(!phpfox::isMobile()){
			$this->template()->assign(array(
				'corepath'=>phpfox::getParam('core.path'),
				'aMenu' => $aMenus,
				'sHeader' => '',
			       ));
		}
		else
		{
			$this->template()->assign(array(
				'corepath'=>phpfox::getParam('core.path'),
				'sHeader' => '',
			       ));
		}
		
		return 'block';
	}
	
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('fundraising.component_block_detail_clean')) ? eval($sPlugin) : false);
	}
}

?>