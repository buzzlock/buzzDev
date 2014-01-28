<?php
/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');
?>
<?php
class musicsharing_Component_Controller_Admincp_Managesong extends Phpfox_Component
{
    
    public function process()
    {
        phpFox::isUser(true);
        $aParentModule = $this->getParam('aParentModule');    
        if($aParentModule)
        {
            phpFox::getLib('session')->set('pages_msf',$aParentModule);
        }
        else
        {
            phpFox::getLib('session')->remove('pages_msf');
        }
		$prefix=phpFox::getParam(array('db', 'prefix'));
        if(isset($_POST['task']) && $_POST['task'] == "dodelete")
        {
            foreach($_POST['delete_song'] as $aid)
            {
               phpFox::getService('musicsharing.music')->deleteAlbumSong($aid);
            }
			phpFox::getLib('url')->send('admincp.musicsharing.managesong');
        }
        $aFilters = array(
            'song_name' => array(
                'type' => 'input:text',
                'search' => "title_url LIKE '%[VALUE]%' AND "
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
        $where = " 1 = 1 "; 
        $strWhere = "";
        if(isset($arrSearch[1])&&$arrSearch[1])
            $strWhere = $arrSearch[0];
        $type = isset($arrSearch[1])?$arrSearch[1]:"";
        $where = "";
        if($strWhere != "")
        {
            $where = " ".$prefix."m2bmusic_album_song.".$strWhere; 
        }
        if($strWhere == "")
            $type = isset($arrSearch[0])?$arrSearch[0]:""; 
             
        $sort = "";  
        switch($type)
        {
            case 'DESC':
                $sort = " ".$prefix."m2bmusic_album_song.title DESC";
            break;
            case 'ASC':
                 $sort = " ".$prefix."m2bmusic_album_song.title ASC";
            break;
        }  
        $where .= " (1 = 1 OR ".$prefix."m2bmusic_album.search = 0 )"; 
        $list_total = phpFox::getService('musicsharing.music')->get_total_song($where);
        $iPageSize = 10; 
        $iPage = $this->request()->get("page");
        if(!$iPage)
            $iPage = 1;
         $max_page = floor($list_total/$iPageSize) + 1;
        if($iPage > $max_page)
            $iPage = $max_page;
        $list_info = phpFox::getService('musicsharing.music')->getSongs(($iPage-1)*$iPageSize,$iPageSize,$sort,null,$where);
        phpFox::getLib('pager')->set(array('page' => $iPage, 'size' => $iPageSize, 'count' => $list_total));
       
        $this->template()->assign(array('iPage'=>$iPage,'aRows'=>$list_info,'iCnt'=>$list_total))
                                ->setHeader('cache', array(
                                         'pager.css' => 'style_css'));
       $this->template()->setHeader(array(
        'm2bmusic_tabcontent.js' => 'module_musicsharing' ,
        'm2bmusic_class.js' => 'module_musicsharing' ,
        'manage.css' => 'module_musicsharing',
        'upload.css' => 'module_musicsharing'      
       ));
        $this->template()->assign(array(
            'sDeleteBlock' => 'dashboard',
            'list_info' =>$list_info,
            'core_path' =>phpFox::getParam('core.path')
        )); 
		
		$this->template()
			->setBreadCrumb(phpFox::getPhrase('musicsharing.admin_menu_manage_songs'), null, true);
    }
    
}