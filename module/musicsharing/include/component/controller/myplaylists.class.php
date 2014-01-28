<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

class musicsharing_Component_Controller_Myplaylists extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
        Phpfox::getLib('setting')->setParam('musicsharing.url_image', Phpfox::getParam('core.url_pic') . 'musicsharing' . PHPFOX_DS);
        phpFox::isUser(true);
        $aParentModule = $this->getParam('aParentModule');
        if($aParentModule)
        {
            phpFox::getLib('session')->set('pages_msf',$aParentModule);
            if(!phpFox::getService('pages')->hasPerm($aParentModule['item_id'], 'musicsharing.can_manage_playlist'))
            {
                $this->url()->send("subscribe");
            }
        }
        else
        {
            phpFox::getLib('session')->remove('pages_msf');
        }
        $this->template()->setBreadCrumb(phpFox::getPhrase('musicsharing.music_sharing'),null);
		$prefix=phpFox::getParam(array('db', 'prefix'));
        $settings = phpFox::getService('musicsharing.music')->getUserSettings(phpFox::getUserId());
        $this->template()->assign(array('settings'=>$settings));
        if(isset($_POST['task']) && $_POST['task'] == "dodelete")
        {
            foreach($_POST['delete_playlist'] as $pid)
            {
                phpFox::getService('musicsharing.music')->deletePlaylist($pid);
            }
        }

        if($this->request()->get("orderplaylist"))
        {
            $id_playlist=$this->request()->get("orderplaylist");
            $playlist_current=phpFox::getService('musicsharing.music')->getplaylists_Id($id_playlist,phpFox::getUserId());
            $order_playlist_current=$playlist_current["order_id"];
            $playlist_after=phpFox::getService('musicsharing.music')->getplaylistsBefore_Id($id_playlist,phpFox::getUserId());
            $order_playlist_after=$playlist_after["order_id"];
            if($order_playlist_after)
            {
                $id_playlist_after=$playlist_after["playlist_id"];
                phpFox::getService('musicsharing.music')->updateplaylist($id_playlist,$order_playlist_after);
                phpFox::getService('musicsharing.music')->updateplaylist($id_playlist_after,$order_playlist_current);
            }
            if ( $this->request()->get('page') > 0)
                $this->url()->send('musicsharing.myplaylists.page_'.$this->request()->get('page'),null,null,null);
            else
                $this->url()->send('musicsharing.myplaylists',null,null,null);
        }
        else if($this->request()->get("orderplaylistdown"))
        {
            $id_playlist=$this->request()->get("orderplaylistdown");
            $playlist_current=phpFox::getService('musicsharing.music')->getplaylists_Id($id_playlist,phpFox::getUserId());
            $order_playlist_current=$playlist_current["order_id"];
            $playlist_after=phpFox::getService('musicsharing.music')->getplaylistsAfter_Id($id_playlist,phpFox::getUserId());
            $order_playlist_after=$playlist_after["order_id"];
            if($order_playlist_after)
            {
                $id_playlist_after=$playlist_after["playlist_id"];
                phpFox::getService('musicsharing.music')->updateplaylist($id_playlist,$order_playlist_after);
                phpFox::getService('musicsharing.music')->updateplaylist($id_playlist_after,$order_playlist_current);
            }
            if ( $this->request()->get('page') > 0)
                $this->url()->send('musicsharing.myplaylists.page_'.$this->request()->get('page'),null,null,null);
            else
                $this->url()->send('musicsharing.myplaylists',null,null,null);
        }

        $user_id = phpFox::getUserId();
        if($this->request()->get('setdefaultplaylist'))
        {
             $idplaylist = $this->request()->get('setdefaultplaylist');
             phpFox::getService('musicsharing.music')->setplaylistprofile($idplaylist,$user_id);
        }
        $where = " where ".$prefix."m2bmusic_playlist.user_id = $user_id";
        $list_total = phpFox::getService('musicsharing.music')->get_total_playlist($where);
        $aGlobalSettings = phpFox::getService('musicsharing.music')->getUserSettings(phpFox::getUserId(), false);
        $iPageSize = isset($aGlobalSettings['number_playlist_per_page']) ? $aGlobalSettings['number_playlist_per_page'] : 5;
        $iPage = $this->request()->get("page");
        if(!$iPage)
            $iPage = 1;
         $max_page = floor($list_total/$iPageSize) + 1;
        if($iPage > $max_page)
            $iPage = $max_page;
        //$sort_by=$prefix.'m2bmusic_playlist.order_id';
        $list_info = phpFox::getService('musicsharing.music')->getPlaylists(($iPage-1)*$iPageSize,$iPageSize,null,null,$where);


        phpFox::getLib('pager')->set(array('page' => $iPage, 'size' => $iPageSize, 'count' => $list_total));

        $this->template()->assign(array('iPage'=>$iPage,'aRows'=>$list_info,'iCnt'=>$list_total))
                                ->setHeader('cache', array(
                                         'pager.css' => 'style_css'));

        $this->template()->assign(array(
            'sDeleteBlock' => 'dashboard',
            'list_info' =>$list_info,
            'core_path' =>phpFox::getParam('core.path'),
            'user_id'   =>phpFox::getUserId(),
            'total_playlist'=>$list_total,
            'cur_page'=>$this->request()->get('page')<=0?1:$this->request()->get('page')
        ));
       $this->template()->setHeader(array(
        'm2bmusic_tabcontent.js' => 'module_musicsharing' ,
        'm2bmusic_class.js' => 'module_musicsharing' ,
        'music.css' => 'module_musicsharing',
        'musicsharing_style.css' => 'module_musicsharing',
		'mobile.css' => 'module_musicsharing',
       ));
		
		//modified section (v 300b1)
		//build filter menu
		phpFox::getService('musicsharing.music')->getSectionMenu($aParentModule);
		$catitle = $this->template()->getBreadCrumb();
		
		if(!$aParentModule){
			$satitle = isset($catitle[1][0])?$catitle[1][0]:$catitle[0][0];
			$this->template()->clearBreadCrumb();
			$this->template()
				->setBreadCrumb(phpFox::getPhrase('musicsharing.music_sharing'), $this->url()->makeUrl('musicsharing'))
				->setBreadCrumb($satitle, null)
				->setBreadCrumb($satitle, null, true);
		}else{
			$this->template()->clearBreadCrumb();
			$this->template()
				->setBreadCrumb(phpFox::getPhrase('musicsharing.music_sharing'), $this->url()->makeUrl('musicsharing'), false);
		}
		///modified section (v 300b1)
	}

	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('musicsharing.component_controller_myplaylists_clean')) ? eval($sPlugin) : false);
	}
}

?>