<?php


defined('PHPFOX') or exit('NO DICE!');


class AdvancedMarketplace_Component_Controller_Test extends Phpfox_Component {

    /**
     * Class process method wnich is used to execute this component.
     */
    public function process() {
        $aCustomGroup = PHPFOX::getService('advancedmarketplace.custom.group')->getForListing();
        $this->template()->assign(array(
            "aCustomGroup" => $aCustomGroup
        ));
    }

    /**
     * Garbage collector. Is executed after this class has completed
     * its job and the template has also been displayed.
     */
    public function clean() {
        (($sPlugin = Phpfox_Plugin::get('advancedmarketplace.component_controller_view_clean')) ? eval($sPlugin) : false);
    }

}

?>