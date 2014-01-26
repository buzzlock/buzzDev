<?php

defined('PHPFOX') or exit('NO DICE!');

class Contest_Service_Adapter extends Phpfox_service {

	/**
	 * [adaptDataOfItemInAddEntryList adapt data from phpfox to be compatible with universal inteface used in add entry entry.class.php service getDataOfAddEntryTemplate]
	 * @param  [integer] $iItemTypeId [type id]
	 * @param  [integer] $aItems      [array of items]
	 * @return [array]              [array of transformed data]
	 */
	public function adaptDataOfItemInAddEntryList($iItemTypeId, $aItems)
	{
		$sItemTypeName = Phpfox::getService('contest.constant')->getContestTypeNameByTypeId($iItemTypeId);
		$aAdaptedItems = array();
        $aUserImage = Phpfox::getService('contest.helper')->getUserImage(Phpfox::getUserId());
        $sUserImage = isset($aUserImage['user_image']) ? 'user/' . sprintf($aUserImage['user_image'], '_50_square') : '';
        $iUserServerId = isset($aUserImage['server_id']) ? $aUserImage['server_id'] : 0;
		
        foreach($aItems as $aItem)
		{
			$aTempItem = $aItem;
			switch ($sItemTypeName)
            {
				case 'video':
						$sImagePath = 'video/' . sprintf($aItem['image_path'], '_120');
						$iItemId = $aItem['video_id'];
					break;
				case 'photo':
						$sImagePath = 'photo/' . sprintf($aItem['destination'], '_150');
						$iItemId = $aItem['photo_id'];
					break;
				case 'blog':
						$sImagePath = $sUserImage;
                        $aTempItem['server_id'] = $iUserServerId;
						$iItemId = $aItem['blog_id'];
					break;
				case 'music':
						$sImagePath = $sUserImage;
                        $aTempItem['server_id'] = $iUserServerId;
						$iItemId = $aItem['song_id'];
					break;
				default:
						$sTitle = '';
						$sImagePath = '';
						$iItemId = '';
					break;
			}
            
			$aTempItem['image_path'] = $sImagePath;
			$aTempItem['item_id'] = $iItemId;
			$aTempItem['item_type'] = $sItemTypeName;

			$aAdaptedItems[] = $aTempItem;
		}

		return $aAdaptedItems;
	}
}