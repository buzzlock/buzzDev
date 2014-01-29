<?php
defined('PHPFOX') or exit('NO DICE!');
/**
 *
 *
 * @copyright       [YOUNET_COPYRIGHT]
 * @author          YouNet Company
 * @package         YouNet_SocialMediaImporter
 */
class SocialMediaImporter_Service_Common extends Phpfox_Service 
{
	function stringRandom($iLength = 20, $bIsUpper = 1, $sChars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ')
	{
		$sChars = md5($sChars . PHPFOX_TIME);	
		$iCount = 0;
		$sRand  = '';
		do
		{
			$sRand .= substr($sChars, rand(0, strlen($sChars)-1), 1);
			$iCount++;
		}
		while ($iCount < $iLength);
		return ($bIsUpper = 1 ? strtoupper($sRand) : $sRand);
	}
	
	function stringSql($sText)
	{
		$aText = explode(',', $sText);		
		for ($i = 0; $i < count($aText); $i++)
		{
			$aText[$i] = sprintf("\"%s\"", $aText[$i]);
		}
		$sText = implode(',', $aText);
		return $sText;
	}
	
	function arrayConverTypeItem($aText, $sType = 'string')
	{		
		if (!$aText) return array();
		for ($i = 0; $i < count($aText); $i++)
		{
			$aText[$i] = (string) $aText[$i];
		}	
		return $aText;
	}
	
	function decodeEntities($text, $bIsTextArea = false) 
	{
		$text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
		if ($bIsTextArea == true)
		{
			$text = Phpfox::getService('groupbuy.common')->strip($text);
		}
		return $text;
	}
	
	function strip($input)
	{
		if (is_string($input)) {
			$input = str_replace(array("\r\n", "\n", "\r"), '<br>', $input);
			$input = str_replace('&nbsp;', ' ', $input);
			$input = preg_replace("/(<br\s*\/?>\s*)+/", "<br/>", $input);
			if (substr($input, -5, 5)=='<br/>')
			{
				$input = substr($input, 0, strlen($input)-5);
			}
			return trim($input);
		}
		return $input;
	}
	
	function arrayNumber($iMin = 0, $iMax = 0, $bIsEqualLength = 0)
	{
		for ($i = $iMin; $i <= $iMax; $i++)
		{
			$v = $i;
			if ($bIsEqualLength == 1) $v = substr(str_pad($i, strlen($iMax), '0', STR_PAD_LEFT), strlen($iMax) * -1);
			$aResult[$v] = $v;
		}
		return $aResult;
	}
	
	function selectNumber($aAttrs = array())
	{
		$sName = isset($aAttrs['name']) ? $aAttrs['name'] : 'name';
		$sClass = isset($aAttrs['class']) ? $aAttrs['class'] : '';
		$sDefault = isset($aAttrs['default']) ? $aAttrs['default'] : '';
		$iMin = isset($aAttrs['min']) ? $aAttrs['min'] : 0;
		$iMax = isset($aAttrs['max']) ? $aAttrs['max'] : 0;
		$bIsEqualLength = 0;		
		$html = "<select name='val[$sName]' id='$sName' class='$sClass'>";
		for ($i = $iMin; $i <= $iMax; $i++)
		{
			$v = $i;
			if ($bIsEqualLength == 1) $v = substr(str_pad($i, strlen($iMax), '0', STR_PAD_LEFT), strlen($iMax) * -1);
			$html .= "<option value='$v'" . ($v == $sDefault ? " selected='selected'" : '') . ">$v</option>";
		}
		$html .= '</select>';
		return $html;
	}
	
	function arrayField($arr2D, $strField)
	{
		$arrResult = array();
		if ($arr2D) {
			for ($i = 0; $i < count($arr2D); $i++) {
				$arrResult[] = $arr2D[$i][$strField];
			}
		}
		return array_unique($arrResult);
	}
	
	function arrayFilter($arr2D, $strField, $strValue)
	{
		$arrResult = array();
		if ($arr2D) {
			foreach($arr2D as $key=>$value) {
				if ($value[$strField] == $strValue) {
					$arrResult[] = $value;
				}
			}
		}
		return $arrResult;
	}
	
	function arrayRand($aRows, $iNum)
	{		
		$iCount = count($aRows);
		if ($iNum > $iCount - 1) return $aRows;		
		$aRand = $aReturn = array();
		while (count($aRand) < $iNum) 
		{
			$iRand = rand(0, $iCount - 1);
			if (!in_array($iRand, $aRand))
			{
				$aReturn[] = $aRows[$iRand];
				$aRand[] = $iRand;
			}
		}
		return $aReturn;
	}	
	
	function arrayKeyValue($arr2D, $strFieldKey, $strFieldValue)
	{
		$arrResult = array();
		if ($arr2D) {
			for ($i = 0; $i < count($arr2D); $i++) {
				$arrResult[$arr2D[$i][$strFieldKey]] = $arr2D[$i][$strFieldValue];
			}
		}
		return $arrResult;
	}

	public function arraySearch($strValue, $arr2D, $strField)
	{
		if ($arr2D) {
			foreach($arr2D as $key=>$value) {
				if ($value[$strField] == $strValue) {
					return $key;
				}
			}
		}
		return -1;
	}
	
	function arrayUpdateField($arr2D, $strFieldKey, $strFieldValue)
	{
		$arrResult = array();
		if ($arr2D) {
			for ($i = 0; $i < count($arr2D); $i++) {
				$arrResult[$i][$strFieldKey] = $strFieldValue;
			}
		}
		return $arrResult;
	}
	
	public function arraySlice($aRows, $iOffset, $iLimit)
	{
		if (!empty($aRows) && is_array($aRows) && $iOffset >= 0 && $iLimit > 0)
		{
			$aRows = array_slice($aRows, $iOffset, $iLimit);
			return $aRows;
		}
		return array();
	}
	
	public function inArray($strValue, $arr1D)
	{
		if (!$arr1D) return 0;
		for ($i = 0; $i < count($arr1D); $i++)				
		{
			if (isset($arr1D[$i]) && strcmp($strValue, $arr1D[$i]) === 0)
			{
				return 1;
			}
		}
		return 0;
	}
	
	public function dateDiff($iEnd, $iStart, &$years = 0, &$months = 0, &$days = 0, &$hours = 0, &$minutes = 0, &$seconds = 0)
	{
		$diff = $iEnd - $iStart;
		if ($diff > 0)
		{
			$years 	= floor($diff / (365*60*60*24));
			$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
			$days 	= floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
			$hours 	= floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60));
			$minutes= floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ (60));
			$seconds= floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minutes*60)/ (1));
		}
	}	
	
	public function buildAlbumSectionMenu($sType = '', $sService = '') 
	{		
		$aFilterMenu[Phpfox::getPhrase('socialmediaimporter.social_media_connect')] = 'socialmediaimporter.connect';
		if (Phpfox::getUserParam('socialmediaimporter.enable_facebook'))
		{
			$aFilterMenu[Phpfox::getPhrase('socialmediaimporter.import_from_facebook')] = 'socialmediaimporter.facebook';
		}
		if (Phpfox::getUserParam('socialmediaimporter.enable_flickr'))
		{
			$aFilterMenu[Phpfox::getPhrase('socialmediaimporter.import_from_flickr')] = 'socialmediaimporter.flickr';
		}
		if (Phpfox::getUserParam('socialmediaimporter.enable_instagram'))
		{
			$aFilterMenu[Phpfox::getPhrase('socialmediaimporter.import_from_instagram')] = 'socialmediaimporter.instagram.photo';
		}
		if (Phpfox::getUserParam('socialmediaimporter.enable_picasa'))
		{
			$aFilterMenu[Phpfox::getPhrase('socialmediaimporter.import_from_picasa')] = 'socialmediaimporter.picasa';
		}
		if ($sType == 'photo')
		{
			if (Phpfox::getUserParam('socialmediaimporter.enable_facebook'))
			{
				$aFilterMenu[Phpfox::getPhrase('socialmediaimporter.import_from_facebook')] = 'socialmediaimporter.facebook.photo';
			}
			if (Phpfox::getUserParam('socialmediaimporter.enable_flickr'))
			{
				$aFilterMenu[Phpfox::getPhrase('socialmediaimporter.import_from_flickr')] = 'socialmediaimporter.flickr.photo';
			}
		}
		Phpfox::getLib("template")->buildSectionMenu('socialmediaimporter', $aFilterMenu);
	}
    
    public function getMinPicSize($iMinSize = 100)
    {
        $aPicSize = Phpfox::getParam('photo.photo_pic_sizes');
        if (!empty($aPicSize))
        {
            asort($aPicSize);
            foreach($aPicSize as $iSize)
            {
                if ($iSize >= $iMinSize)
                {
                    $iMinSize = $iSize;
                    break;
                }
            }
        }
        
        return $iMinSize;
    }
}
?>