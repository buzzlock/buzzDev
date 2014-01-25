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
class Fevent_Component_Controller_Admincp_Custom_Add extends Phpfox_Component
{
    /**
     * Class process method wnich is used to execute this component.
     */
    public function process()
    {        
        $bHideOptions = true;
        $iDefaultSelect = 4;
        
        Phpfox::getUserParam('fevent.can_add_custom_fields', true);
        $this->template()->assign(array('aForms' => array()));
            
        $aFieldValidation = array(
            'category_id' => Phpfox::getPhrase('fevent.select_a_category_this_custom_field_will_belong_to'),
            'var_type' => Phpfox::getPhrase('fevent.select_what_type_of_custom_field_this_is')
        );
        
        $oCustomValidator = Phpfox::getLib('validator')->set(array(
                'sFormName' => 'js_custom_field', 
                'aParams' => $aFieldValidation,
                'bParent' => true
            )
        );        
        
        $this->template()->assign(array(
                'sCustomCreateJs' => $oCustomValidator->createJS(),
                'sCustomGetJsForm' => $oCustomValidator->getJsForm()    
            )
        );        
    
        if (($aVals = $this->request()->getArray('val')))
        {
            if ($oCustomValidator->isValid($aVals))
            {
                if (Phpfox::getService('fevent.custom.process')->add($aVals))
                {
                    $this->url()->send('admincp.fevent.custom.add', null, Phpfox::getPhrase('fevent.field_successfully_added'));
                }
            }
            
            if (isset($aVals['var_type']) && $aVals['var_type'] == 'select')
            {
                $bHideOptions = false;
                $iCnt = 0;
                $sOptionPostJs = '';
                foreach ($aVals['option'] as $iKey => $aOptions)
                {
                    if (!$iKey)
                    {
                        continue;
                    }
                    
                    $aValues = array_values($aOptions);
                    if (!empty($aValues[0]))
                    {
                        $iCnt++;
                    }
                    
                    foreach ($aOptions as $sLang => $mValue)
                    {
                        $sOptionPostJs .= 'option_' . $iKey . '_' . $sLang . ': \'' . str_replace("'", "\'", $mValue) . '\',';    
                    }
                }
                $sOptionPostJs = rtrim($sOptionPostJs, ',');
                $iDefaultSelect = $iCnt;        
            }
        }        
        
        
        $oMulticat = Phpfox::getService('fevent.multicat');
        $css = array('id'=>'', 'name'=>'val[category_id]', 'class'=>'category_id');
        
        if($aVals = $this->request()->getArray('val')) {
            $sOptions = $oMulticat->getSelectBox($css, $aVals['category_id'], null, null);
        } else {
            $sOptions = $oMulticat->getSelectBox($css, null, null, null);
        }
        
        $this->template()->setTitle(Phpfox::getPhrase('fevent.add_a_new_custom_field'))
            ->setBreadcrumb(Phpfox::getPhrase('fevent.add_a_new_custom_field'), $this->url()->makeUrl('admincp.fevent.custom.add'))
            ->setPhrase(array(
                    'fevent.are_you_sure_you_want_to_delete_this_custom_option'
                )
            )
            ->setHeader(array(
                    '<script type="text/javascript"> var bIsEdit = false; </script>',
                    'admin.js' => 'module_custom',
                    '<script type="text/javascript">$Behavior.feventAdminCustomAdd = function(){$Core.custom.init(' . $iDefaultSelect . '' . (isset($sOptionPostJs) ? ', {' . $sOptionPostJs . '}' : '') . ');}</script>'
                )
            )
            ->assign(array(
                    'aLanguages' => Phpfox::getService('language')->getAll(),
                    'bHideOptions' => $bHideOptions,
                    'sOptions' => $sOptions,
                )
            );
    }
    
    /**
     * Garbage collector. Is executed after this class has completed
     * its job and the template has also been displayed.
     */
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('fevent.component_controller_admincp_custom_add_clean')) ? eval($sPlugin) : false);
    }
}

?>