<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class AdvancedMarketplace_Component_Block_Owner extends Phpfox_Component
{
	public function process()
	{
		$aListing = $this->getParam('aListing');	

		if (Phpfox::isModule('tag'))
		{
			$aTags = Phpfox::getService('tag')->getTagsById('advancedmarketplace', $aListing['listing_id']);	
			if (isset($aTags[$aListing['listing_id']]))
			{
				$aListing['tag_list'] = $aTags[$aListing['listing_id']];
			}
		}		
		//d($aListing); die();
		$aFollower = phpfox::getLib('database')->select('*')
					->from(phpfox::getT('advancedmarketplace_follow'))
					->where('user_id = '.$aListing['user_id'].' and  user_follow_id = ' . phpfox::getUserId())
					->execute('getSlaveRow');
		$bFollow = 'unfollow';
		if(!empty($aFollower) && phpfox::getUserId() > 0)
		{
			$bFollow = 'follow';
		}
        $this->template()->assign(array(
                'corepath'=>phpfox::getParam('core.path'),
                'bFollow' => $bFollow,
                'aListing' =>$aListing,
                'sTagType' => 'advancedmarketplace'
                                                            ));
            return 'block';
	}
}
?>
