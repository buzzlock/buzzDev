<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * 
 * 
 * @copyright        [YOUNET_COPPYRIGHT]
 * @author          VuDP, AnNT
 * @package          Module_jobposting
 */

class JobPosting_Service_Custom_Custom extends Phpfox_service
{
    private $_aHasOption = array('select', 'radio', 'multiselect', 'checkbox');
    
    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->_sTable = Phpfox::getT('jobposting_custom_field');
        $this->_sTableOption = Phpfox::getT('jobposting_custom_option');
        $this->_sTableValue = Phpfox::getT('jobposting_custom_value');
    }
    
    /**
     * Get custom fields for review when edit company
     * @param array $aFields
     * @return html
     */
    public function buildHtmlForReview($aFields = array())
    {
        $sHtml = '';
        
        $aType = array(
            'textarea' => Phpfox::getPhrase('custom.large_text_area'),
            'text' => Phpfox::getPhrase('custom.small_text_area_255_characters_max'),
            'select' => Phpfox::getPhrase('custom.selection'),
            'multiselect' => Phpfox::getPhrase('core.multiple_selection'),
            'radio' => Phpfox::getPhrase('core.radio'),
            'checkbox' => Phpfox::getPhrase('core.checkbox'),
        );
        
        if(is_array($aFields) && count($aFields))
        {
            foreach($aFields as $k=>$aField)
            {
                $sHtml .= '<div class="ynjp_customField_holder" id="js_custom_field_'.$aField['field_id'].'"><div class="ynjp_customField_left"><div class="ynjp_customField_title">';
                if($aField['is_required'])
                {
                    $sHtml .= '<span class="required">*</span>';
                }
                $sHtml .= '<span>'.Phpfox::getPhrase($aField['phrase_var_name']).'</span></div>';
                $sHtml .= '<div class="ynjp_customField_control_holder"><a href="#" onclick="tb_show(\''.Phpfox::getPhrase('jobposting.edit_field_question').'\', $.ajaxBox(\'jobposting.controllerAddField\', \'height=300&width=300&action=edit&id='.$aField['field_id'].'\')); return false;" class="ynjp_customField_control_edit">'.Phpfox::getPhrase('jobposting.edit').'</a>';
                $sHtml .= '<a href="#" onclick="if(confirm(\''.Phpfox::getPhrase('core.are_you_sure').'\')) $.ajaxCall(\'jobposting.deleteField\', \'id='.$aField['field_id'].'\'); return false;" class="ynjp_customField_control_delete">'.Phpfox::getPhrase('jobposting.delete').'</a></div>';
                $sHtml .= '</div>';
				$sHtml .= '<p class="extra_info">'.Phpfox::getPhrase('jobposting.type').': '.$aType[$aField['var_type']].'</p>';
                if(in_array($aField['var_type'], $this->_aHasOption))
                {
                    $sHtml .= '<div class="ynjp_customField_right">';
                    if(!empty($aField['option']))
                    {
                        $iNo = 0;
                        foreach($aField['option'] as $k2=>$sOption)
                        {
                            $sHtml .= Phpfox::getPhrase('jobposting.option').' '.(++$iNo).': '.Phpfox::getPhrase($sOption).'<br />';
                        }
                    }
                    else
                    {
                        $sHtml .= Phpfox::getPhrase('jobposting.this_field_will_be_hidden_until_it_has_at_least_one_option');
                    }
                    $sHtml .= '</div>';
                }
                $sHtml .= '</div>';
            }
        }
                
        return $sHtml;
    }
    
    /**
     * @param int $iCompanyId
     * @return array
     */
    public function getByCompanyId($iCompanyId)
    {
        $aFields = $this->database()->select('*')
            ->from($this->_sTable)
            ->where('company_id = '.(int)$iCompanyId)
            ->order('field_id ASC')
            ->execute('getSlaveRows');
        
        if(is_array($aFields) && count($aFields))
        {
            foreach($aFields as $k=>$aField)
            {
                if(in_array($aField['var_type'], $this->_aHasOption))
                {
                    $aOptions = $this->database()->select('*')->from($this->_sTableOption)->where('field_id = '.$aField['field_id'])->order('option_id ASC')->execute('getSlaveRows');
                    if(is_array($aOptions) && count($aOptions))
                    {
                        foreach($aOptions as $k2=>$aOption)
                        {
                            $aFields[$k]['option'][$aOption['option_id']] = $aOption['phrase_var_name'];
                        }
                    }
                }
            }
        }
        
        return $aFields;
    }
    
    public function getByApplicationId($iApplicationId)
    {
        $aFields = $this->database()->select('cf.*, cv.value')
            ->from($this->_sTable, 'cf')
            ->join($this->_sTableValue, 'cv', 'cv.field_id = cf.field_id')
            ->where('cv.application_id = '.$iApplicationId)
            ->group('cf.field_id')
            ->order('cf.field_id ASC')
            ->execute('getSlaveRows');
        
        if(is_array($aFields) && count($aFields))
        {
            foreach($aFields as $k=>$aField)
            {
                if(in_array($aField['var_type'], $this->_aHasOption))
                {
                    $aOptions = $this->database()->select('co.*')
                        ->from($this->_sTableOption, 'co')
                        ->join($this->_sTableValue, 'cv', 'cv.option_id = co.option_id')
                        ->where('co.field_id = '.$aField['field_id'].' AND cv.application_id = '.$iApplicationId)
                        ->order('co.option_id ASC')
                        ->execute('getSlaveRows');
                    
                    if(is_array($aOptions) && count($aOptions))
                    {
                        foreach($aOptions as $k2=>$aOption)
                        {
                            $aFields[$k]['option'][$aOption['option_id']] = $aOption['phrase_var_name'];
                        }
                    }
                }
            }
        }
        
        return $aFields;
    }
            
    public function getForCustomEdit($iId)
    {
        $aField = $this->database()->select('*')->from($this->_sTable)->where('field_id = '.(int)$iId)->execute('getRow');
        
        list($sModule, $sVarName) = explode('.', $aField['phrase_var_name']);
        
        // Get the name of the field in every language
        $aPhrases = $this->database()->select('language_id, text')
            ->from(Phpfox::getT('language_phrase'))
            ->where('var_name = \'' . $this->database()->escape($sVarName) . '\'')
            ->execute('getSlaveRows');
        
        foreach ($aPhrases as $aPhrase)
        {
            $aField['name'][$aField['phrase_var_name']][$aPhrase['language_id']] = $aPhrase['text'];
        }
        
        if ($aField['var_type'] == 'select' || $aField['var_type'] == 'multiselect' || $aField['var_type'] == 'radio' || $aField['var_type'] == 'checkbox')
        {
            $aOptions = $this->database()->select('option_id, field_id, phrase_var_name')
                ->from($this->_sTableOption)
                ->where('field_id = ' . $aField['field_id'])
                ->order('option_id ASC')
                ->execute('getSlaveRows');
            
            foreach ($aOptions as $iKey => $aOption)
            {
                list($sModule, $sVarName) = explode('.', $aOption['phrase_var_name']);

                $aPhrases = $this->database()->select('language_id, text, var_name')
                    ->from(Phpfox::getT('language_phrase'))
                    ->where('var_name = \'' . $this->database()->escape($sVarName) . '\'')
                    ->execute('getSlaveRows');
                
                foreach ($aPhrases as $aPhrase)
                {
                    if (!isset($aField['option'][$aOption['option_id']][$aOption['phrase_var_name']][$aPhrase['language_id']]))
                    {
                        $aField['option'][$aOption['option_id']][$aOption['phrase_var_name']][$aPhrase['language_id']] = array();
                    }
                    $aField['option'][$aOption['option_id']][$aOption['phrase_var_name']][$aPhrase['language_id']]['text'] = $aPhrase['text'];
                }
            }
        }
        return $aField;
    }

}