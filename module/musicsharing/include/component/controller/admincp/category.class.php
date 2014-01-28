<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

class Musicsharing_Component_Controller_Admincp_category extends Phpfox_Component
{

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

        $this->delete();

        $list_info = Phpfox::getService('musicsharing.music')->getCategories();

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
                ->setTitle(phpFox::getPhrase('musicsharing.add_category'))
                ->setHeader('cache', array(
                    'quick_edit.js' => 'static_script'));

        $this->template()
                ->setBreadCrumb(phpFox::getPhrase('musicsharing.manage_categories'), null, true);
    }

    public function delete()
    {
        if (isset($_POST['task']) && $_POST['task'] == "dodelete")
        {
            foreach ($_POST['delete_category'] as $aid)
            {
                Phpfox::getService('musicsharing.music')->deleteCategory($aid);
            }
        }
    }

    public function save()
    {
        if ($aVals = $this->request()->getArray('val'))
        {
            $title = $aVals['title'];
            if ($title != "")
            {
                if (Phpfox::getService('musicsharing.music')->addCategory($title))
                {
                    $this->url()->send('admincp.musicsharing.category', null, 'Category successfully added.');
                }
            }
            else
            {
                return Phpfox_Error::set(phpFox::getPhrase('musicsharing.please_enter_category_name') . '!');
            }
        }
    }

    /**
     * Garbage collector. Is executed after this class has completed
     * its job and the template has also been displayed.
     */
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('musicsharing.component_controller_admincp_category_clean')) ? eval($sPlugin) : false);
    }

}

?>