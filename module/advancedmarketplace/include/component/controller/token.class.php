<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

/**
 * 
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package 		Phpfox_Component
 * @version 		$Id: view.class.php 3444 2011-11-03 12:56:50Z Raymond_Benc $
 */
class AdvancedMarketplace_Component_Controller_Token extends Phpfox_Component {

    /**
     * Class process method wnich is used to execute this component.
     */
    public function process() {
		$sToken = Phpfox::getService('log.session')->getToken();
		echo $sToken;
		// echo $sPermalink = Phpfox::getLib('url')->makeUrl('advancedmarketplace.token');
        exit;
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