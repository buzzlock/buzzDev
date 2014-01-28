<?php
/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');
?>
<?php
class musicsharing_Component_Controller_Admincp_Managealbum extends Phpfox_Component
{

    public function process()
    {
        Phpfox::getLib('setting')->setParam('musicsharing.url_image', Phpfox::getParam('core.url_pic') . 'musicsharing' . PHPFOX_DS);
        
        $aParentModule = $this->getParam('aParentModule');
        if($aParentModule)
        {
            phpFox::getLib('session')->set('pages_msf',$aParentModule);
        }
        else
        {
            phpFox::getLib('session')->remove('pages_msf');
        }
        phpFox::isUser(true);
		$prefix=phpFox::getParam(array('db', 'prefix'));
        if(isset($_POST['task']) && $_POST['task'] == "dodelete")
        {
            foreach($_POST['delete_album'] as $aid)
            {
               phpFox::getService('musicsharing.music')->deleteAlbum($aid);
            }
			$this->url()->send('admincp.musicsharing.managealbum');
        }
        $aFilters = array(
            'album_name' => array(
                'type' => 'input:text',
                'search' => "title_url LIKE '%[VALUE]%'"
            ),
            'sort_by' => array(
                'type' => 'select',
                'options' => array(
                    'DESC' => phpFox::getPhrase('musicsharing.descending'),
                    'ASC' => phpFox::getPhrase('musicsharing.ascending')
                ),
                'default' => 'DESC',
                'search' =>"[VALUE]"
            )

        );
        $oSearch = phpFox::getLib('search')->set(array(
                'type' => '',
                'filters' => $aFilters,
                'search' => 'search'
            )
        );
        $arrSearch = $oSearch->getConditions();
        $strWhereAlbum = "";
        if(isset($arrSearch[1]) && $arrSearch[1])
            $strWhereAlbum = $arrSearch[0];
        $type = isset($arrSearch[1])?$arrSearch[1]:"";
        $where ="";
        if($strWhereAlbum != "")
        {
            $where = " ".$prefix."m2bmusic_album.".$strWhereAlbum;
        }
        if($strWhereAlbum == "")
            $type = isset($arrSearch[0])?$arrSearch[0]:"";

        $sort = "";
        switch($type)
        {
            case 'DESC':
                $sort = " ".$prefix."m2bmusic_album.title DESC";
            break;
            case 'ASC':
                 $sort = " ".$prefix."m2bmusic_album.title ASC";
            break;
        }
        $list_total = phpFox::getService('musicsharing.music')->get_total_album($where);
        $iPageSize = 10;
        $iPage = $this->request()->get("page");
        if(!$iPage)
            $iPage = 1;
         $max_page = floor($list_total/$iPageSize) + 1;
        if($iPage > $max_page)
            $iPage = $max_page;
        $list_info = phpFox::getService('musicsharing.music')->getAlbums(($iPage-1)*$iPageSize,$iPageSize,$sort,null,$where, true);
        phpFox::getLib('pager')->set(array('page' => $iPage, 'size' => $iPageSize, 'count' => $list_total));

        $this->template()->assign(array('iPage'=>$iPage,'aRows'=>$list_info,'iCnt'=>$list_total))
                                ->setHeader('cache', array(
                                         'pager.css' => 'style_css'));
       $this->template()->setHeader(array(
        'm2bmusic_tabcontent.js' => 'module_musicsharing' ,
        'm2bmusic_class.js' => 'module_musicsharing' ,
        'upload.css' => 'module_musicsharing',
        'manage.css' => 'module_musicsharing',
	'quick_edit.js' => 'static_script'
       ));

        $this->template()->assign(array(
            'sDeleteBlock' => 'dashboard',
            'list_info' =>$list_info,
            'core_path' =>phpFox::getParam('core.path')
        ));
		
		$this->template()
			->setBreadCrumb(phpFox::getPhrase('musicsharing.admin_menu_manage_albums'), null, true);
    }

}