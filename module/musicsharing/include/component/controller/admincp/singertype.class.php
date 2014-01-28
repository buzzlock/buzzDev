<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

class Musicsharing_Component_Controller_Admincp_Singertype extends Phpfox_Component
{
    /**
     *
     * @var Musicsharing_Service_Music 
     */
    public $oSerMusic;
    
    public function __construct($aParams)
    {
        parent::__construct($aParams);
        
        $this->oSerMusic = PhpFox::getService('musicsharing.music');
    }
    
    /**
     * Class process method wnich is used to execute this component.
     */
    public function process()
    {
        $aParentModule = $this->getParam('aParentModule');
        if ($aParentModule)
        {
            phpFox::getLib('session')->set('pages_msf', $aParentModule);
        }
        else
        {
            phpFox::getLib('session')->remove('pages_msf');
        }

        if (isset($_POST['task']) && $_POST['task'] == "dodelete")
        {
            foreach ($_POST['delete_singer_type'] as $aid)
            {
                $this->oSerMusic->deleteSingerType($aid);
            }
        }
        $list_info = $this->oSerMusic->getSingerTypes();
        $this->template()
                ->setTitle('Add Singer Type')
                ->setHeader('cache', array(
                    'quick_edit.js' => 'static_script'));
        $this->template()->assign(array(
            'sDeleteBlock' => 'dashboard',
            'list_info' => $list_info,
            'core_path' => phpFox::getParam('core.path')
        ));
        $this->template()->setHeader(array(
            'm2bmusic_tabcontent.js' => 'module_musicsharing',
            'm2bmusic_class.js' => 'module_musicsharing',
            'upload.css' => 'module_musicsharing'
        ));
        
        $this->save();

        $this->template()
                ->setBreadCrumb(phpFox::getPhrase('musicsharing.admin_menu_ws'), null, true);
    }

    public function save()
    {
        if ($aVals = $this->request()->getArray('val'))
        {
            $title = $aVals['title'];

            if ($title != "")
            {
                if ($this->oSerMusic->addSingerType($title))
                {
                    $this->url()->send('admincp.musicsharing.singertype', null, 'Singer type successfully added.');
                }
            }
            else
                return Phpfox_Error::set('Please enter singer type name!');
        }
    }
    
    /**
     * Garbage collector. Is executed after this class has completed
     * its job and the template has also been displayed.
     */
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('musicsharing.component_controller_admincp_singertype_clean')) ? eval($sPlugin) : false);
    }

}

?>