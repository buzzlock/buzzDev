<?php

defined('PHPFOX') or exit('NO DICE!');

class MobileTemplate_Service_Process extends Phpfox_Service
{
	public function __construct()
	{
		
	}
	
	public function deleteAllMTActiveThemeStyle(){
		$this->database()->delete(Phpfox::getT('mobiletemplate_active_theme_style'), ' 1=1 ');
	}

	public function insertMTActiveThemeStyle($aActiveStyle){
		if(isset($aActiveStyle) && isset($aActiveStyle['style_id'])){
	        $iId = $this->database()->insert(Phpfox::getT('mobiletemplate_active_theme_style'), array(
	                'style_id' => $aActiveStyle['style_id'],
					'theme_id' =>  $aActiveStyle['theme_id']
	            )
	        );
			return $iId;
		}else{
			return false;
		}
	}
	
    public function addMobileCustomStyle($aVals)
    {
        if (($sMsg = $this->__verifyMobileCustomStyleData($aVals)) !== true)
        {
            return Phpfox_Error::set($sMsg);
        }
		$oParseInput = Phpfox::getLib('parse.input');
		
        return $this->database()->insert(Phpfox::getT('mobiletemplate_mobile_custom_style'), array(
            'name' => $oParseInput->clean($aVals['name'], 255),
            'is_active' => 0,
            'data' => base64_encode(serialize($aVals)),
            'time_stamp' => PHPFOX_TIME,
        ));
    }
	
    public function updatedMobileCustomStyle($iStyleId, $aVals)
    {
        if (($sMsg = $this->__verifyMobileCustomStyleData($aVals)) !== true)
        {
            return Phpfox_Error::set($sMsg);
        }
		$oParseInput = Phpfox::getLib('parse.input');
		
        return $this->database()->update(Phpfox::getT('mobiletemplate_mobile_custom_style')
        			, array('name' => $oParseInput->clean($aVals['name'], 255), 'data' => base64_encode(serialize($aVals)))
        			, 'style_id = ' . (int) $iStyleId);
    }
	
    private function __verifyMobileCustomStyleData($aVals)
    {
        if (empty($aVals['name']))
        {
            return Phpfox::getPhrase('mobiletemplate.style_name_is_required');
        }
        
        return true;
    }

    public function deleteMobileCustomStyleByID($iId)
    {
        $this->database()->delete(Phpfox::getT('mobiletemplate_mobile_custom_style'), 'style_id = '.(int)$iId);
        
        return true;
    }
	
    public function deleteMobileCustomStyleByListOfID($aIds)
    {
    	foreach($aIds as $id){
    		$this->database()->delete(Phpfox::getT('mobiletemplate_mobile_custom_style'), 'style_id = '.(int)$id);	
    	}
        
        return true;
    }
	
    public function updateMobileCustomStyleStatus($iStyleId, $iAction)
    {
        $this->database()->update(Phpfox::getT('mobiletemplate_mobile_custom_style'), array('is_active' => 0), 'style_id <>' . $iStyleId);
        $this->database()->update(Phpfox::getT('mobiletemplate_mobile_custom_style'), array('is_active' => $iAction), 'style_id = ' . $iStyleId);
    }
	
    public function addMenuNavigation($aVals)
    {
        $oParseInput = Phpfox::getLib('parse.input');
        
        $this->cache()->remove('mt_leftnavi', 'substr'); 
        
        return $this->database()->insert(Phpfox::getT('mobiletemplate_menu_navigation'), array(
            'url' => $aVals['url'],
            'orginal_var_name' => $aVals['orginal_var_name'],
            'display_name' => $oParseInput->clean($aVals['display_name'], 255),
            'is_active' => 1,
            'ordering' => $aVals['ordering'] 
        ));
    }

    public function deleteMenuNavigationByID($iId)
    {
        $this->database()->delete(Phpfox::getT('mobiletemplate_menu_navigation'), 'menu_id = '.(int)$iId);

        $this->cache()->remove('mt_leftnavi', 'substr'); 
        
        return true;
    }

    public function updateMenuNavigationOrdering($aVal)
    {
        foreach ($aVal as $iId => $iPosition)
        {
            $this->database()->update(Phpfox::getT('mobiletemplate_menu_navigation'), array('ordering' => (int) $iPosition), 'menu_id = ' . (int) $iId);
        }

        $this->cache()->remove('mt_leftnavi', 'substr'); 

        return true;
    }

    public function updateMenuNavigationStatus($iNavigationId = 0, $iActive = 0)
    {
        $this->database()->update(Phpfox::getT('mobiletemplate_menu_navigation'), array('is_active' => (int) $iActive), 'menu_id = ' . (int) $iNavigationId);

        $this->cache()->remove('mt_leftnavi', 'substr'); 

        return true;
    }

	
}
?>