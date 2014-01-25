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
class Fevent_Service_Custom_Custom extends Phpfox_Service 
{
    /**
     * Class constructor
     */    
    public function __construct()
    {    
        $this->_sTable = Phpfox::getT('fevent_custom_field');
    }
    
    public function getFieldsByCateId($iId)
    {
        $aCustomFields = $this->database()->select('*')
            ->from($this->_sTable)
            ->where('category_id = ' . (int) $iId . ' AND is_active = 1')
            ->order('ordering')
            ->execute('getRows');
        foreach($aCustomFields as $iKey => $aCustomField)
        {
            if($aCustomField["var_type"] != "text" && $aCustomField["var_type"] != "textarea")
            {
                $aCustomOptions = $this->getCustomOptions($aCustomField["field_id"]);
                if(isset($aCustomOptions[0]))
                {
                    $aCustomFields[$iKey]["options"] = $aCustomOptions;
                }
            }
        }
        return $aCustomFields;
    }
    
    public function getCustomOptions($iId)
    {
        return $this->database()->select('*')
            ->from(Phpfox::getT('fevent_custom_option'))
            ->where('field_id = ' . (int) $iId)
            ->execute('getRows');
    }
    
    public function getCustomFieldsForEdit($iEventId)
    {
        $aCustomFields = $this->database()->select('cf.*, cv.value')
            ->from($this->_sTable, 'cf')
            ->innerJoin(Phpfox::getT('fevent_custom_value'), 'cv', 'cf.field_id = cv.field_id')
            ->where('cv.event_id = ' . (int) $iEventId)
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
    
    public function display($aCategories, &$sCustoms, $level)
    {
        $sCustoms .= '<ul>';

        $first = true;
        
        if($level==0) {
            $style = '';
        } else {
            $style = ' style="margin-bottom:0; padding-bottom:5px;"';
        }
        
        foreach($aCategories as $aKey=>$aCategory)
        {
            $class = "";
            if($aKey !== 'PHPFOX_EMPTY_GROUP') {
                $class="group";
            }
            
            if($first) {
                $class_first = " first";
            } else {
                $class_first = "";
            }
            $first = false;
            
            $sCustoms .= '<li class="'.$class.$class_first.'"'.$style.'>';
            
            if($aKey === 'PHPFOX_EMPTY_GROUP') {
                $sCustoms .= Phpfox::getPhrase("custom.general");
            } else {
                $del_l = ""; $del_r = "";
                if(!$aCategory['is_active']) {
                    $del_l = "<del>"; $del_r = "</del>";
                }
                $sCustoms .= '<a style="cursor:default; font-weight:bold;">'.$del_l.$aCategory['name'].'</a>'.$del_r;
            }
            
            if(isset($aCategory['child'])) {
                $sCustoms .= '<ul>';
                foreach($aCategory['child'] as $aField) {
                    $del_l = ""; $del_r = "";
                    if(!$aField['is_active']) {
                        $del_l = "<del>"; $del_r = "</del>";
                    }
                    
                    $cat_name = "";
                    if(!empty($aCategory['category_name'])) {
                        $cat_name = $aCategory['category_name'];
                    }
                    
                    $sCustoms .= '<li class="field" style="margin-bottom:0; padding-bottom:5px;">
                            <div style="display:none;"><input type="hidden" name="field['.$aField['field_id'].']" value="'.$aField['ordering'].'" /></div>
                            <a href="#?id='.$aField['field_id'].'&amp;type=field" class="js_drop_down" id="js_field_'.$aField['field_id'].'"><img src="' . Phpfox::getLib('template')->getStyle('image', 'misc/draggable.png') . '" alt="" /> '.$del_l.Phpfox::getPhrase($aField['phrase_var_name']).$cat_name.$del_r.'</a>
                        </li>';
                }
                $sCustoms .= '</ul>';
            }
            
            $level++;
            if(!empty($aCategory['subs'])) {
                $this->display($aCategory['subs'], $sCustoms, $level);
            }
            
            $sCustoms .= '</li>';
        }
    
        $sCustoms .= '</ul>';
    }
}