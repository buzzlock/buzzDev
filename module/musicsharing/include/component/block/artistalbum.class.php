<?php 
defined('PHPFOX') or exit('NO DICE!');  
class musicsharing_Component_Block_artistalbum extends Phpfox_Component 
{     
    public function process() 
    {
		//phpFox::isUser(true);
        $a=phpFox::getPhrase('musicsharing.uploader').' '.phpFox::getPhrase('musicsharing.album').'s';
        $artistId = $this->getParam('artistId');
        if(!$artistId || $artistId<=0)
        {
            return false;
        }
        $relatedAlbums =phpFox::getService('musicsharing.music')->getAlbums(0,10,'album_id DESC',null,' '.phpFox::getParam(array('db', 'prefix')).'m2bmusic_album.user_id  = '.$artistId. ' AND '.phpFox::getParam(array('db', 'prefix')).'m2bmusic_album.search = 1'); 
        if(count($relatedAlbums)<=0)
        {
            return false;
        }
		 $this->template()->assign(array(
			'sHeader' => $a,
            'relatedAlbums' =>$relatedAlbums,
		));
        return 'block';
    } 
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('musicsharing.component_block_singers_clean')) ? eval($sPlugin) : false);
    }
} 
  
?>