<?php


defined('PHPFOX') or exit('NO DICE!');


class AdvancedMarketplace_Component_Block_Tag extends Phpfox_Component
{
	public function process()
	{
		if(!phpfox::isModule('tag'))
		{
			return false;
		}
		

		$aRows = phpfox::getService('advancedmarketplace')->getTagCloud();
		
		if(empty($aRows))
		{
			return false;
		}
		
		$max = round($aRows[0]['value']);
		$min = round($aRows[0]['value']);
		$max_position = $min_position = 0;
		$scale = 5;
		foreach($aRows as $Key=>$aRow)
		{
			$aRow['value'] = round($aRow['value']);
			if($max < $aRow['value'])
			{
				$max = $aRow['value'];
				$max_position = $Key;
			}
			if($min > $aRow['value'])
			{
				$min = $aRow['value'];
				$min_position = $Key;
			}
		}
		$range = abs($max-$min);
		foreach($aRows as $Key=>$aRow)
		{
			if($range > 0)
			{
				$aRow['value'] = (25*($aRow['value'] - $min)/($max-$min));
				if($aRow['value']>25)
				{
					$aRow['value'] = 25;
				}
				if($aRow['value'] < 11)
				{
					$aRow['value'] = 11;
				}
				$aRows[$Key]=$aRow;
			}
			else
			{
				$aRow['value'] = 20;
				$aRows[$Key] = $aRow;
			}
		}
		if (!count($aRows))
		{
			return false;
		}
		$this->template()->assign(array(
										'aTags'=>$aRows,
										'sHeader' => Phpfox::getPhrase('advancedmarketplace.topics')
						));
		return 'block';
	}
}
?>