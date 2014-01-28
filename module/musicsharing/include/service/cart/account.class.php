<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class musicsharing_service_cart_account extends Phpfox_Service
{
    public function getCurrentInfo($user_id)
    {
        $result=phpFox::getLib('phpfox.database')->select('u.*,account.account_username')
                ->from(phpFox::getT('user'),'u')
                ->leftJoin(phpFox::getT('m2bmusic_payment_account'),'account','account.user_id=u.user_id')
                ->where('u.user_id='.$user_id)
                ->execute("getslaveRows");
        return @$result[0];
    }

    public function getCurrentAccount($user_id)
    {
          $result=phpFox::getLib('phpfox.database')->select('*')
                ->from(phpFox::getT('m2bmusic_payment_account'))
                ->where('user_id='.$user_id)
                ->execute("getslaveRows");
           return @$result[0];
    }

    public function getUserGroupId($user_id)
    {
        $result=phpFox::getLib('phpfox.database')->select('*')
                ->from(phpFox::getT('user'))
                ->where('user_id='.$user_id)
                ->execute("getslaveRows");
        $user_group_id=@$result[0]["user_group_id"];
        return $user_group_id;
    }

    public function getSellingSettings($user_group_id)
    {
            $settings=array();
            $result=phpFox::getLib('phpfox.database')->select('*')
                ->from(phpFox::getT('m2bmusic_selling_settings'))
                ->where('user_group_id='.$user_group_id)
                ->execute("getslaveRows");
            foreach ($result as $aRow)
            {
                $settings[$aRow['name']] = $aRow['default_value'];
            }
           return $settings;
    }

    public function getAmountSeller($user_id)
    {
          phpFox::getLib('phpfox.database')->query('SET character_set_results=utf8 ');
          $result = phpFox::getLib('phpfox.database')
          ->select('count(*) as count,dl.dl_song_id,dl.dl_album_id')
                    ->from(phpFox::getT('m2bmusic_downloadlist'),'dl')
                    ->leftJoin(phpFox::getT('m2bmusic_album_song'),'alsong','alsong.song_id=dl.dl_song_id')
                    ->leftJoin(phpFox::getT('m2bmusic_singer'),'singer','singer.singer_id=alsong.singer_id')
                    ->leftJoin(phpFox::getT('m2bmusic_album'),'album','album.album_id=dl.dl_album_id')
                    ->where('album.user_id='.$user_id.' or 0 < ((SELECT count(*) from phpfox_m2bmusic_album_song als1 LEFT JOIN phpfox_m2bmusic_album as ma1 ON als1.album_id = ma1.album_id where ma1.user_id='.$user_id.' and als1.song_id=dl.dl_song_id))')
                    ->group('dl.dl_song_id,dl.dl_album_id')
                    ->execute('getSlaveRows');
          //die();
          return count($result);
    }

    public function getHistorySeller($user_id,$iPage,$iPageSize,$iCnt)
    {
		 phpFox::getLib('phpfox.database')->query('SET character_set_results=utf8 '); 
          $result = phpFox::getLib('phpfox.database')
          ->select('count(*) as count,alsong.*,album.user_id,singer.title as singer_title,album.title as album_title,album.album_id,TRUNCATE(alsong.filesize/1024/1024,2) as sizemb,dl.transaction_tracking_id')
                    ->from(phpFox::getT('m2bmusic_transaction_tracking'),'dl')
                    ->leftJoin(phpFox::getT('m2bmusic_album_song'),'alsong','alsong.song_id=dl.item_id and dl.item_type='.'"song"')
                    ->leftJoin(phpFox::getT('m2bmusic_singer'),'singer','singer.singer_id=alsong.singer_id')
                    ->leftJoin(phpFox::getT('m2bmusic_album'),'album','album.album_id=dl.item_id and dl.item_type='.'"album"')
                    ->where('album.user_id='.$user_id.' or 0 < ((SELECT count(*) from phpfox_m2bmusic_album_song als1 LEFT JOIN phpfox_m2bmusic_album as ma1 ON als1.album_id = ma1.album_id where ma1.user_id='.$user_id.' and als1.song_id=dl.item_id and dl.item_type='.'"song"'.'))')
                    ->group('dl.item_id,dl.item_type')
                    ->limit($iPage, $iPageSize, $iCnt)
                    ->execute('getSlaveRows');
          return $result;
    }

    public function insertRequest($vals=array())
    {
         return phpFox::getLib('phpfox.database')
            ->insert(phpFox::getT('m2bmusic_payment_request'),$vals);
    }

    public function getTotalRequest($request_user_id)
    {
        $result=phpFox::getLib('phpfox.database')->select('sum(request_amount) as totalrequest,request_user_id')
                ->from(phpFox::getT('m2bmusic_payment_request'))
                ->where('request_user_id='.$request_user_id.' and request_status=0')
                ->group('request_user_id')
                ->execute('getSlaveRows');
        return @$result[0]['totalrequest'];
    }

    public function updateinfo($avals=array())
    {
        return phpFox::getLib('phpfox.database')->update(phpFox::getT('user'),array('full_name'=>$avals['full_name'],'status'=>$avals['status'],'gender'=>$avals['gender'],'email'=>$avals['email']),'user_id='.phpFox::getUserId());   
    }

    public function updateusername_account($request_user_id,$account_username)
    {
        return phpFox::getLib('phpfox.database')->update(phpFox::getT('m2bmusic_payment_account'),array('account_username'=>$account_username),'user_id='.$request_user_id);
    }

    public function insertAccount($results = array())
    {
        $aVals=array();
        $aVals['account_username']=$results['account_username'];
        $aVals['account_password']=$results['password'];
        $aVals['total_amount']=0;
        $aVals['account_status']=1;
        $aVals['payment_type']=2;
        $aVals['user_id']=phpFox::getUserId();
        return phpFox::getLib("phpfox.database")->insert(phpFox::getT('m2bmusic_payment_account'),$aVals);
    }

    public function getValueUserGroupId($user_id)
    {
        $aRows=phpFox::getLib('phpfox.database')->select('*')
                ->from(phpFox::getT('user'))
                ->where('user_id='.$user_id)
                ->execute('getSlaveRows');
        $user_group_id=@$aRows[0]["user_group_id"];
        return $user_group_id;
    }
}
?>
