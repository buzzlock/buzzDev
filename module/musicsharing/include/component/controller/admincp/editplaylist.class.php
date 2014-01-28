<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');
  
class Musicsharing_Component_Controller_Admincp_EditPlaylist extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{	
        $this->template()->setHeader(array(
        'm2bmusic_tabcontent.js' => 'module_musicsharing' ,
        'm2bmusic_class.js' => 'module_musicsharing' ,
        'music.css' => 'module_musicsharing'      
       ));
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
        if( $this->request()->get('playlist')){$playlist_id = $this->request()->get('playlist'); } else {$playlist_id = 0;} 
        $playlist_info =  phpFox::getService('musicsharing.music')->getPlaylistInfo($playlist_id);  
        $user_viewer = phpFox::getUserId();
        if($playlist_info['user_id'] == $user_viewer || phpFox::isAdmin(true))
        {
            $result = 0;
             $this->template()->assign(array(
                'playlist_info' =>$playlist_info
            )); 
            if(isset($_POST['submit']))
            {  
                   $url = $playlist_info['playlist_image'];
                   if(isset($_FILES['playlist_image']) && $_FILES['playlist_image'] != null && $_FILES['playlist_image']['error'] == 0)
                   {
                        $image = $_FILES['playlist_image'];
                        $file_tmp = phpFox::getLib('file')->load('playlist_image',array('jpg', 'gif', 'png'));
                         $p = PHPFOX_DIR_FILE.PHPFOX_DS.'pic'.PHPFOX_DS.'musicsharing'.PHPFOX_DS;
                         if (!is_dir($p))
                        {
                            if(!@mkdir($p,0777,1))
                            {
                                 //$log->lwrite('error create path');   
                            }
                        }
                        $url = phpFox::getLib('file')->upload('playlist_image',$p,$image['name']);
                        
                        $oImage = phpFox::getLib('image');          
                        if ($oImage->createThumbnail($p.PHPFOX_DS . sprintf($url, ''), $p.PHPFOX_DS. sprintf($url, '_' . 'thumb'), 112, 150) === false)
                        {
                               
                            $url = sprintf($url, '');     
                        }
                        else
                        {
                            $url = sprintf($url, '_thumb');   
                        }
                   }
                   $title = "";
                   $aVals = $this->request()->getArray('val');
                   if($aVals['title'] != "")
                   {
                       $title = $aVals['title'];
                   }
                   if(trim($title) == "")
                   {
                      return Phpfox_Error::set('Please enter playlist name!');   
                   }
                    $description = "";
                   if($aVals['description']!= "")
                   {
                       $description = $aVals['description'];
                   }
                   $search = 1;
                   if($_POST['search']) $search = $_POST['search'];
                   else $search = 0;
                   //if($_POST['is_download']) $download = $_POST['is_download'];
                   //else $download = 0;
                   $currentDate = date("Y-m-d H:i:s");
                   $playlist = array();
                   $playlist['playlist_id']  = $playlist_id;
                   $playlist['title'] = $title;
                   $playlist['title_url'] = $title;
                   $playlist['playlist_image'] = $url;
                   $playlist['description'] = $description;
                   $playlist['search'] = $search;
                   $playlist['is_download'] = 1;//$download;
                   $playlist['modified_date'] = $currentDate;
                   $playlist_id = phpFox::getService('musicsharing.music')->editPlaylist($playlist); 
                   $result = $playlist_id;
                  $playlist_info =  phpFox::getService('musicsharing.music')->getPlaylistInfo($playlist_id);  
            }
            $this->template()->assign(array(
                'sDeleteBlock' => 'dashboard',
                'playlist_info' =>$playlist_info,
                'core_path' =>phpFox::getParam('core.path'),
                'user_id'   =>phpFox::getUserId(),
                'result'    =>$result
            )); 
        }

	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('musicsharing.component_controller_editplaylist_clean')) ? eval($sPlugin) : false);
	}
}

?>