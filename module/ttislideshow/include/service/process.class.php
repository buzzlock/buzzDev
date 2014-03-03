<?php

defined('PHPFOX') or exit('NO DICE!');

/**
 * author		Teamwurkz Technologies Inc.
 * package		tti_components
 */

class Ttislideshow_Service_Process extends Phpfox_Service 
{

	public function __construct()
	{	
		$this->_sTable = Phpfox::getT('tti_slideshow');	
	}
	
	public function add($aVals, $iSlideID = null)
	{		
		
		$oFilter = Phpfox::getLib('parse.input');
		
		$aForms = array(
			'title' => array(
				'message' => 'Please enter slide title.',
				'type' => array('string:required')
			),
			'title_link' => array(
				'message' => 'Please enter slide title.',
				'type' => array('string:required')
			),			
			'description' => array(
				'message' => 'Please enter slide description.',
				'type' => array('string:required')
			),			
			'is_active' => array(
				'message' => 'Please enter active.',
				'type' => 'int:required'
			),
			'ordering' => array(
				'message' => 'Please enter ordering.',
				'type' => 'int'
			)			
		);		

		$aVals = $this->validator()->process($aForms, $aVals);
				
		if (!Phpfox_Error::isPassed())
		{
			return false;
		}			
		
		if ($iSlideID !== null)
		{
			$iId = $iSlideID ;								
			$this->database()->update($this->_sTable, $aVals, 'slide_id = ' . (int) $iSlideID);	
		}
		else 
		{	
			$iId = $this->database()->insert($this->_sTable, $aVals);
		}

		// Upload the image

			if (!empty($_FILES['image']['name']))
				{
					$aImage = Phpfox::getLib('file')->load('image', array('jpg', 'gif', 'png'));
					
					if ($aImage === false)
					{
						return false;
					}			
				}			

			if (!empty($_FILES['image']['name']) && ($sFileName = Phpfox::getLib('file')->upload('image', Phpfox::getParam('core.dir_pic').'ttislideshow'.PHPFOX_DS, $iId)))
			{
				$this->database()->update($this->_sTable, array('image_path' => $sFileName, 'server_id' => Phpfox::getLib('request')->getServer('PHPFOX_SERVER_ID')), 'slide_id = ' . (int) $iId);		
				
				$sFileName_thumb = $sFileName;
				$sFileName_thumb2 = $sFileName;
				
				Phpfox::getLib('image')->createThumbnail(Phpfox::getParam('core.dir_pic').'ttislideshow'.PHPFOX_DS . sprintf($sFileName, ''), Phpfox::getParam('core.dir_pic').'ttislideshow'.PHPFOX_DS . sprintf($sFileName, '_980'), 980, 980);								
				Phpfox::getLib('image')->createThumbnail(Phpfox::getParam('core.dir_pic').'ttislideshow'.PHPFOX_DS . sprintf($sFileName_thumb, ''), Phpfox::getParam('core.dir_pic').'ttislideshow'.PHPFOX_DS . sprintf($sFileName_thumb, '_650'), 650, 650);
				Phpfox::getLib('image')->createThumbnail(Phpfox::getParam('core.dir_pic').'ttislideshow'.PHPFOX_DS . sprintf($sFileName_thumb2, ''), Phpfox::getParam('core.dir_pic').'ttislideshow'.PHPFOX_DS . sprintf($sFileName_thumb2, '_120'), 120, 120);					
				
				//unlink(Phpfox::getParam('core.dir_pic').'ttislideshow'.PHPFOX_DS . sprintf($sFileName, ''));
				//unlink(Phpfox::getParam('core.dir_pic').'ttislideshow'.PHPFOX_DS . sprintf($sFileName_thumb, ''));
			}	
			
		return $iId;
	}
	
	public function update($iId, $aVals)
	{
		return $this->add($aVals, $iId);
	}
	
	public function delete($iId)
	{
		Phpfox::isUser(true);
		
		$aSlide = $this->database()->select('slide_id')
			->from($this->_sTable)
			->where('slide_id = ' . (int) $iId)
			->execute('getRow');
			
		if (!isset($aSlide['slide_id']))
		{
			return Phpfox_Error::set('Record not found');
		}
		
		$this->database()->delete($this->_sTable, 'slide_id = ' . $aSlide['slide_id']);
		
		return true;
	}

	public function deleteImage($iId)
	{
		Phpfox::isUser(true);

		$aSlide = $this->database()->select('slide_id, image_path, server_id')
				->from($this->_sTable)
				->where('slide_id = ' . (int) $iId)
				->execute('getRow');
		
		if (!empty($aSlide['image_path']))
		{
			$sImage = Phpfox::getParam('core.dir_pic').'ttislideshow'.PHPFOX_DS. sprintf($aSlide['image_path'], '_980');
			$sImage_thumb = Phpfox::getParam('core.dir_pic').'ttislideshow'.PHPFOX_DS. sprintf($aSlide['image_path'], '_650');
			$sImage_thumb2 = Phpfox::getParam('core.dir_pic').'ttislideshow'.PHPFOX_DS. sprintf($aSlide['image_path'], '_120');
			if (file_exists($sImage))
			{
				unlink($sImage);
				unlink($sImage_thumb);
				unlink($sImage_thumb2);
			}
			
			$this->database()->update($this->_sTable, array('image_path' => null, 'server_id' => '0'), 'slide_id = ' . $aSlide['slide_id']);		
		}
		
		return true;
	}	
}

?>