<?php
class musicsharing_Service_Photoyn extends Phpfox_Service
{
    public function getNewPhotos($iTotal)
    {
        $aRows = phpFox::getLib("phpfox.database")->select('p.*, pi.*,u.*')
        ->from(phpFox::getT('photo'),'p')
        ->join(phpFox::getT('photo_info'),'pi', 'p.photo_id=pi.photo_id')
        ->join(phpFox::getT('user'), 'u', 'u.user_id = p.user_id')
        ->where('p.privacy=0 ')        
        ->order('p.time_stamp DESC')
        ->limit($iTotal)
        ->execute('getSlaveRows');

        $aRows = phpFox::getService('musicsharing.music')->getAlbums(0,$iTotal,null,null,null);
        foreach ($aRows as $iKey => $aRow)
        {
            //$aRows[$iKey]['time_stamp'] = date("F j, Y",$aRow['time_stamp']);
            $aRows[$iKey]['time_stamp']="";
            $aRows[$iKey]['large_image']=str_replace("%s","_1024",$aRow['album_image']);
            $aRows[$iKey]['thumb_image']=str_replace("%s","_75",$aRow['album_image']);
        }
        return $aRows;                               
    }   
    
    public function getFeaturePhoto($iTotal)
    {
       $aRows = phpFox::getLib("phpfox.database")->select("p.*, u.user_name")
        ->from(phpFox::getT('photo'),'p')
        ->join(phpFox::getT('user'), 'u', 'u.user_id = p.user_id')
        ->where('p.privacy=0 and p.is_featured=1')
        ->order('p.time_stamp DESC')
        ->limit($iTotal)
        ->execute('getSlaveRows');

       $aRows = phpFox::getService('musicsharing.music')->getAlbums(0,$iTotal,null,null,null);
       
        for($i = 0 ; $i<count($aRows);$i++){
            $aRows[$i]['destination']=str_replace("%s","_240",$aRows[$i]['album_image']);
        }

        return $aRows;

    }    
}
?>