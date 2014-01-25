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
class Fevent_Service_Custom_Process extends Phpfox_Service 
{    
    private $_aOptions = array();
    
    /**
     * Used to control when to add another feed
     * @var array of boolean 
     */
    private $_aFeedsAdded = array();
    /**
     * Class constructor
     */    
    public function __construct()
    {    
        $this->_sTable = Phpfox::getT('fevent_custom_field');
    }
    
    /**
     * Adds a new custom field, the options must come in this structure
     *  array(
     *    option = array(
     *        # => array(
     *        <language_id> => array(
     *            text => option text
     * @param type $aVals
     * @return type 
     */
    public function add($aVals)
    {
        $this->_aOptions = array();
        
        Phpfox::getUserParam('fevent.can_add_custom_fields', true);
        if(empty($aVals['category_id']))
        {
            $aVals['category_id'] = 0;
        }
        // Prepare the name of the custom field
        $sVarName = '';
        foreach ($aVals['name'] as $iId => $aText)
        {
            if (empty($aText['text']))
            {
                continue;
            }
            
            $sVarName = Phpfox::getService('language.phrase.process')->prepare($aText['text']);
            
            break;
        }    
        
        if (empty($sVarName))
        {
            return Phpfox_Error::set(Phpfox::getPhrase('custom.provide_a_name_for_the_custom_field'));
        }
        
        $sFieldName = substr($sVarName, 0, 20);        
        $sVarName = 'custom_' . $sVarName;
        
        $bAddToOptions = false;
        switch ($aVals['var_type'])
        {
            case 'select':
            case 'radio':
                $sTypeName = 'VARCHAR(150)';
                $sValueTypeName = 'SMALLINT(5)';
                $bAddToOptions = true;
                break;
            case 'multiselect':
            case 'checkbox':
                $sTypeName = 'MEDIUMTEXT';
                $sValueTypeName = 'MEDIUMTEXT';
                $bAddToOptions = true;
                break;
            case 'text':
                $sTypeName = 'VARCHAR(255)';
                $sValueTypeName = 'VARCHAR(255)';        
                break;
            case 'textarea':
                $sTypeName = 'MEDIUMTEXT';
                $sValueTypeName = 'MEDIUMTEXT';
                break;
            default:
                return Phpfox_Error::set(Phpfox::getPhrase('custom.not_a_valid_type_of_custom_field'));
                break;
        }
        
        if ($bAddToOptions && !empty($aVals['option']) && is_array($aVals['option']))
        {
            $iTotalOptions = 0;
            foreach ($aVals['option'] as $aOption)
            {
                foreach ($aOption as $aLanguage)
                {                    
                    if (isset($aLanguage['text']) && !empty($aLanguage['text']))
                    {
                        $iTotalOptions++;
                        // there may be more languages, counting them would give an incorrect number of options
                        break;
                    }
                }
                $aValues = array_values($aOption);
            }
            
            if (!$iTotalOptions)
            {
                return Phpfox_Error::set(Phpfox::getPhrase('custom.you_have_selected_that_this_field_is_a_select_custom_field_which_requires_at_least_one_option'));
            }
        }
        
        $iCustomFieldCount = $this->database()->select('COUNT(*)')
            ->from(Phpfox::getT('fevent_custom_field'))
            ->where('phrase_var_name = \'' . $this->database()->escape('core.' . $sVarName) . '\'')
            ->execute('getField');
        
        if ($iCustomFieldCount > 0)
        {
            $sVarName = $sVarName . ($iCustomFieldCount + 1);
            $sFieldName = $sFieldName . ($iCustomFieldCount + 1);            
        }
        
        $aSql = array(
            'field_name' => $sFieldName,
            'category_id' => $aVals['category_id'],
            'phrase_var_name' => 'core.' . $sVarName,
            'type_name' => $sTypeName,
            'var_type' => $aVals['var_type'],
            'is_required' => (isset($aVals['is_required']) ? (int) $aVals['is_required'] : 0)
        );
        
        /*
        switch($aVals['var_type'])
        {
            case 'select':
            case 'multiselect':
            case 'checkbox':
            case 'radio':
            $bAddField = false;
            break;
            default:
            $bAddField = true;
        }
        
        if ( $bAddField && 
            !$this->database()->isField(Phpfox::getT('fevent_custom_value'), Phpfox::getService('custom')->getAlias() . $sFieldName))
        {
            $this->database()->addField(array(
                    'table' => Phpfox::getT('fevent_custom_value'),
                    'field' => Phpfox::getService('custom')->getAlias() . $sFieldName,
                    'type' => $sValueTypeName
                )
            );
        }
        */
        
        // Insert into DB
        $iFieldId = $this->database()->insert($this->_sTable, $aSql);        
        if ($bAddToOptions && !empty($aVals['option']) && is_array($aVals['option']))
        {
            $this->_addOptions($iFieldId, $aVals);
        }        
        // Add the new phrase
        if (!Phpfox::getService('language.phrase')->isValid('core.' . $sVarName))
        {
            foreach ($aVals['name'] as $sLang => $aName)
            {
               Phpfox::getService('language.phrase.process')->add(array(
                    'var_name' => $sVarName,
                    'module' => 'core|core',
                    'product_id' => 'phpfox',
                    'text' => array($sLang => $aName['text'])
                ), true
                ); 
            }
        }
        $this->cache()->remove();
    
        return array(
            $iFieldId,
            $this->_aOptions
        );
    }
    
    public function toggleActivity($iId)
    {
        Phpfox::getUserParam('fevent.can_manage_custom_fields', true);
        
        $aField = $this->database()->select('field_id, is_active')
            ->from($this->_sTable)
            ->where('field_id = ' . (int) $iId)
            ->execute('getSlaveRow');
            
        if (!isset($aField['field_id']))
        {
            return Phpfox_Error::set(Phpfox::getPhrase('custom.unable_to_find_the_custom_field'));
        }
        
        $this->database()->update($this->_sTable, array('is_active' => ($aField['is_active'] ? 0 : 1)), 'field_id = ' . $aField['field_id']);
        
        $this->cache()->remove('custom_field', 'substr');
        
        return true;
    }
    
    public function updateOrder($aVals)
    {
        Phpfox::getUserParam('fevent.can_manage_custom_fields', true);    
        
        foreach ($aVals as $iId => $iOrder)
        {
            $this->database()->update($this->_sTable, array('ordering' => (int) $iOrder), 'field_id = ' . (int) $iId);
        }
        
        //$this->cache()->remove('custom_field', 'substr');
        
        return true;
    }
    
    public function delete($iId)
    {
        Phpfox::getUserParam('fevent.can_manage_custom_fields', true);    
        
        $aField = $this->database()->select('*')
            ->from($this->_sTable)
            ->where('field_id = ' . (int) $iId)
            ->execute('getRow');
            
        if (!isset($aField['field_id']))
        {
            return Phpfox_Error::set(Phpfox::getPhrase('custom.unable_to_find_the_custom_field_you_want_to_delete'));
        }
        
        list($sModule, $sPhrase) = explode('.', $aField['phrase_var_name']);
        
        $this->database()->delete(Phpfox::getT('language_phrase'), 'module_id = \'' . $sModule . '\' AND var_name = \'' . $sPhrase . '\'');
        
        $aOptions = $this->database()->select('*')
            ->from(Phpfox::getT('fevent_custom_option'))
            ->where('field_id = ' . $aField['field_id'])
            ->execute('getRows');        
            
        foreach ($aOptions as $aOption)
        {
            list($sModule, $sPhrase) = explode('.', $aOption['phrase_var_name']);
    
            $this->database()->delete(Phpfox::getT('language_phrase'), 'module_id = \'' . $sModule . '\' AND var_name = \'' . $sPhrase . '\'');
        }
        
        $this->database()->delete(Phpfox::getT('fevent_custom_option'), 'field_id = ' . $aField['field_id']);
        
        // Also delete the values associated with deleted field
        $this->database()->delete(Phpfox::getT('fevent_custom_value'),'field_id = ' . $aField['field_id']);
        $this->database()->delete($this->_sTable, 'field_id = ' . $aField['field_id']);
        $this->cache()->remove();
        
        return true;    
    }
    
    /**
     * If a call is made to an unknown method attempt to connect
     * it to a specific plug-in with the same name thus allowing 
     * plug-in developers the ability to extend classes.
     *
     * @param string $sMethod is the name of the method
     * @param array $aArguments is the array of arguments of being passed
     */
    public function __call($sMethod, $aArguments)
    {
        /**
         * Check if such a plug-in exists and if it does call it.
         */
        if ($sPlugin = Phpfox_Plugin::get('fevent.service_process__call'))
        {
            return eval($sPlugin);
        }
            
        /**
         * No method or plug-in found we must throw a error.
         */
        Phpfox_Error::trigger('Call to undefined method ' . __CLASS__ . '::' . $sMethod . '()', E_USER_ERROR);
    }
    
    private function _addOptions($iFieldId, &$aVals)
    {
        foreach ($aVals['option'] as $iKey => $aOptions)
        {
            if (isset($aVals['option'][$iKey]['added']) && $aVals['option'][$iKey]['added'] == true)
            {
                continue;
            }
            $aOptionsAdded = array();
            $aExisting = array();
            $iSeqNumber = in_array($iKey, $aExisting) ? (max($aExisting) + 1) : $iKey;
            $aExisting[] = $iSeqNumber;
            foreach ($aOptions as $sLang => $aOption)
            {
                if (empty($aOption['text'])) 
                {
                    continue;
                }
                
                $sPhraseVar = 'cf_option_' . $iFieldId . '_' . $iSeqNumber;
                
                Phpfox::getService('language.phrase.process')->add(array(
                        'var_name' => $sPhraseVar,//'cf_option_' . Phpfox::getService('language.phrase.process')->prepare($aOption['text']),//$sOptionVarName . '_feed',                    
                        'module' => 'fevent|fevent',
                        'product_id' => 'younetevent',
                        'text' => array($sLang => $aOption['text'])
                    ));
                
                // Only add one option per language
                if (!in_array($iKey, $aOptionsAdded))
                {
                    $this->_aOptions[$iKey . $sLang] = $this->database()->insert(Phpfox::getT('fevent_custom_option'), array(
                        'field_id' => $iFieldId,
                        'phrase_var_name' => 'fevent.' .$sPhraseVar
                    )
                    );
                    $aOptionsAdded[] = $iKey;
                }                
            }
            $aVals['option'][$iKey]['added'] = true;
        }
        
        return true;                        
    }
}

?>
