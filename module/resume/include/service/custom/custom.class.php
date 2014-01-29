<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');
/**
 * 
 * 
 * @copyright       [YOUNET_COPYRIGHT]
 * @author          YouNet Company
 * @package         YouNet_Event
 */
class Resume_Service_Custom_Custom extends Phpfox_Service 
{
    /**
     * Class constructor
     */    
    public function __construct()
    {    
        $this->_sTable = Phpfox::getT('resume_custom_field');
    }
	
	public function getCustomFieldsByFieldId($field_id){
		$aInfoFields = $this->database()->select('*')
            ->from($this->_sTable,'cf')
       		->where('field_id = '.$field_id)
            ->execute('getRow');
		return $aInfoFields;
	}
	
	public function getInfoFieldsByPhares($name){
		$aInfoFields = $this->database()->select('cf.*')
            ->from($this->_sTable,'cf')
       		->where('cf.field_name ="'.$name.'"')
            ->execute('getRow');
		return $aInfoFields;
	}
	
	public function getFieldsByResumeIdAndFieldId($resume_id, $field_id){
		 $aRow = $this->database()->select('cv.*')
			->from(Phpfox::getT('resume_custom_value'),'cv')
            ->where('cv.field_id = '.$field_id.' and cv.resume_id = '.$resume_id)
            ->execute('getRow');
		return $aRow;
			
	}
    
    public function getFields($resume_id)
    {
        $aCustomFields = $this->database()->select('cf.*,cv.resume_id,cv.value')
            ->from($this->_sTable,'cf')
			->leftJoin(Phpfox::getT('resume_custom_value'), 'cv', 'cf.field_id = cv.field_id and resume_id = '.$resume_id)
            ->where('cf.is_active = 1')
            ->order('ordering')
            ->execute('getRows');
			
		
	 	foreach($aCustomFields as $iKey => $aCustomField)
        {
            if($aCustomField["var_type"] != "text" && $aCustomField["var_type"] != "textarea")
            {
                $aCustomOptions = $this->getCustomOptions($aCustomField["field_id"]);
                if(isset($aCustomOptions[0]))
                {
                    if(!empty($aCustomField['value']))
                    {
                        $aValues = json_decode($aCustomField['value'], true);
                        if(!is_array($aValues))
                        {
                            $aValues = array($aCustomField['value']);
                        }
                        foreach($aCustomOptions as $iKey2 => $aCustomOption)
                        {
                            if(in_array(Phpfox::getPhrase($aCustomOption['phrase_var_name']), $aValues))
                            {
                                $aCustomOptions[$iKey2]["selected"] = true;
                            }
                        }
                    }
                    $aCustomFields[$iKey]["options"] = $aCustomOptions;
                }
            }
        }

        return $aCustomFields;
    }
    
    public function getCustomOptions($iId)
    {
        return $this->database()->select('*')
            ->from(Phpfox::getT('resume_custom_option'))
            ->where('field_id = ' . (int) $iId)
            ->execute('getRows');
    }
    
    public function getCustomFieldsForEdit($iResume)
    {
        $aCustomFields = $this->database()->select('cf.*, cv.value')
            ->from($this->_sTable, 'cf')
            ->innerJoin(Phpfox::getT('resume_custom_value'), 'cv', 'cf.field_id = cv.field_id')
            ->where('cv.resume_id = ' . (int) $iResume)
            ->order('cf.ordering')
            ->execute('getRows');
        foreach($aCustomFields as $iKey => $aCustomField)
        {
            if($aCustomField["var_type"] != "text" && $aCustomField["var_type"] != "textarea")
            {
                $aCustomOptions = $this->getCustomOptions($aCustomField["field_id"]);
                if(isset($aCustomOptions[0]))
                {
                    if(!empty($aCustomField['value']))
                    {
                        $aValues = json_decode($aCustomField['value'], true);
                        if(!is_array($aValues))
                        {
                            $aValues = array($aCustomField['value']);
                        }
                        foreach($aCustomOptions as $iKey2 => $aCustomOption)
                        {
                            if(in_array(Phpfox::getPhrase($aCustomOption['phrase_var_name']), $aValues))
                            {
                                $aCustomOptions[$iKey2]["selected"] = true;
                            }
                        }
                    }
                    $aCustomFields[$iKey]["options"] = $aCustomOptions;
                }
            }
        }
        return $aCustomFields;
    }
    

    public function display()
    {
      	$aFields = $this->database()->select('cf.*')
                ->from(Phpfox::getT('resume_custom_field'), 'cf')
                ->order('cf.ordering ASC')
                ->execute('getRows');
				
		if (count($aFields))
		{
			$sOutput = '<ul class="ui-sortable">';
			$icon = PHpfox::getParam('core.path')."theme/adminpanel/default/style/default/image/misc/draggable.png";
			foreach($aFields as $Fields){
				$title = Phpfox::getPhrase($Fields['phrase_var_name']);
				if(!$Fields['is_active'])
				{
					$title = "<del>".$title."</del>";
				}
				$sOutput.='
				<li class="field">
					<a id="js_field_'.$Fields['field_id'].'" class="js_drop_down" href="#?id='.$Fields['field_id'].'&amp;type=field"><img class="v_middle" alt="" src="'; 	
				$sOutput.=$icon.'">'.$title.'</a></li>';
			}
			
			$sOutput .= '</ul>';
			return $sOutput;
		}	
	}
}