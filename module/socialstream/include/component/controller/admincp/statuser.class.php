<?php

/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * 
 * 
 * @copyright		YOUNETCO
 * @author  		AnNT
 * @package 		YouNet SocialStream
 * @version 		3.03
 */
class SocialStream_Component_Controller_Admincp_StatUser extends Phpfox_Component
{
    /**
     * Class process method wnich is used to execute this component.
     */
    public function process()
    {
        $iPage = $this->request()->getInt('page');
        if (!$iLimit = $this->request()->getInt('show'))
        {
            $iLimit = 10;
        }

        $sKeyword = $this->request()->get('keyword');
        $sType = $this->request()->get('type');

        $aStats = array();
        $bIsSearch = (!empty($sKeyword) && !empty($sType)) ? true : false;

        list($iCnt, $aStats) = Phpfox::getService('socialstream.services')->getStatsByUser($sKeyword, $sType, $iPage, $iLimit);

        Phpfox::getLib('pager')->set(array('page' => $iPage, 'size' => $iLimit, 'count' => $iCnt));

        $aServices = Phpfox::getService('socialstream.services')->getProviders();

        $this->template()->setTitle(Phpfox::getPhrase('socialstream.statistics_by_user'))
            ->setBreadcrumb(Phpfox::getPhrase('socialstream.statistics_by_user'))
            ->setHeader(array('admin.css' => 'module_socialstream'))
            ->assign(array(
                'aServices' => $aServices, 
                'aStats' => $aStats,
                'bIsSearch' => $bIsSearch,
                'aForms' => array('keyword' => $sKeyword, 'type' => $sType)
            ));
    }

    /**
     * Garbage collector. Is executed after this class has completed
     * its job and the template has also been displayed.
     */
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('socialstream.component_controller_admincp_statuser_clean')) ? eval($sPlugin) : false);
    }
}

?>