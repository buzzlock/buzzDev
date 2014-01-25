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
class Fevent_Component_Controller_Admincp_Custom_Index extends Phpfox_Component
{
    /**
     * Class process method wnich is used to execute this component.
     */
    public function process()
    {
        
        //d(Phpfox::getService('fevent')->getCustomFields());exit();
        Phpfox::getUserParam('fevent.can_manage_custom_fields', true);
        $bOrderUpdated = false;
        
        if (($aFieldOrders = $this->request()->getArray('field')) && Phpfox::getService('fevent.custom.process')->updateOrder($aFieldOrders))
        {            
            $bOrderUpdated = true;
        }
        
        if ($bOrderUpdated === true)
        {
            $this->url()->send('admincp.fevent.custom', null, Phpfox::getPhrase('custom.custom_fields_successfully_updated'));
        }
        
        $aCategories = Phpfox::getService('fevent')->getCustomFields();
        $sCustoms = '';
        $oCustom = Phpfox::getService('fevent.custom');
        $oCustom->display($aCategories, $sCustoms, 0);
        
        $this->template()->setTitle(Phpfox::getPhrase('fevent.manage_custom_fields'))
            ->setBreadcrumb(Phpfox::getPhrase('fevent.manage_custom_fields'), $this->url()->makeUrl('admincp.fevent.custom'))
            ->setHeader(array(
                    'jquery/ui.js' => 'static_script',
                    'admin.js' => 'module_custom',
                    'custom.js' => 'module_fevent',
                    '<script type="text/javascript">$Behavior.feventAdminCustomIndex = function() { $Core.custom.url(\'' . $this->url()->makeUrl('admincp.fevent.custom') . '\'); $Core.custom.addSort(); }</script>',
                )
            )
            ->assign(array(
                    'aCategories' => $aCategories,
                    'sCustoms' => $sCustoms
                )
            );
    }
    
    /**
     * Garbage collector. Is executed after this class has completed
     * its job and the template has also been displayed.
     */
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('custom.component_controller_admincp_index_clean')) ? eval($sPlugin) : false);
    }
}

?>