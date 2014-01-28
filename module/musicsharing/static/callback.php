<?php
include 'logging.php';
define('ALLOWED_REFERRER', '');
define('LOG_DOWNLOADS',true);
define('LOG_FILE','downloads.log');
define('PHPFOX',1);
   require_once "../../../include/setting/server.sett.php";

    $connection = mysql_connect($_CONF['db']['host'], $_CONF['db']['user'], $_CONF['db']['pass']);
    $prefix = $_CONF['db']['prefix'];
    if (!$connection)
        die("can't connect server");

    $db_selected = mysql_select_db($_CONF['db']['name']);
    if (!$db_selected)
        die ("have not database");
   mysql_query("SET character_set_client=utf8", $connection);
        mysql_query("SET character_set_connection=utf8",  $connection);
   $action = $_REQUEST['action'];
   $log = new Logging();
   $log->log_file = dirname(__FILE__).'/log_text.txt';
   $log->lwrite(print_r($_REQUEST,true));
   switch($action)
   {
       case 'callback':
            
            $req4 = @mysql_escape_string($_REQUEST['req4']);
            $req5 = @mysql_escape_string($_REQUEST['req5']);
            $status = @mysql_escape_string($_REQUEST['status']);
            $payer_status = @mysql_escape_string(($_REQUEST['payer_status']));
            $payment_status = @mysql_escape_string(($_REQUEST['payment_status']));
            //$log->lwrite('req4'.$req4);
            //$log->lwrite('req5'.$req5);
            //$log->lwrite('status'.$status);
            //get bill
            $sql = "SELECT * FROM ".$prefix."m2bmusic_bills WHERE "
                   ."sercurity ='".$req4 ."' AND invoice = '".$req5."'"
                   ." LIMIT 0,1"    
                   ;
            
            //$log->lwrite('sql::::'.$sql);
            $result = mysql_query($sql) or die(mysql_error()."<b>SQL was: </b>$sql");
            if($result)
            {
                $billtmp = mysql_fetch_row($result);
                $bill = array(
                        'bill_id'=>$billtmp[0],
                        'invoice'=>$billtmp[1],
                        'sercurity'=>$billtmp[2],
                        'user_id'=>$billtmp[3],
                        'finance_account_id'=>$billtmp[4],
                        'emal_receiver'=>$billtmp[5],
                        'payment_receiver_id'=>$billtmp[6],
                        'date_bill'=>$billtmp[7],
                        'bill_status'=>$billtmp[8],
                        'params'=>$billtmp[9],
                        );
                //$log->lwrite(print_r($bill,true));
                
                if($bill['bill_status'] == 0 && ($status == 'COMPLETED' ||$payment_status =='Completed')&& $payer_status =='verified')//
                {
                    $cartitem = unserialize($bill['params']);
                    //$log->lwrite(print_r($cartitem,true));
                    //move item to downloadlist
                    moveItems2DownloadList($cartitem['items'],$prefix,$bill['user_id']);
                   
                    //update status of bill
                    updateBillStatus($prefix,$bill,1);
                    //save to history
                    $type = 'bill';
                     $date = date('Y-m-d');
                    //$timestamp = strtotime($date);
                    $arrtoDate = explode('-',$date);
                    $timestamp = mktime(12,0,0,$arrtoDate[1],$arrtoDate[2],$arrtoDate[0]);
                    $bill['bill_status'] = 1;
                    updateHistories($bill,$type,$timestamp,$prefix);   
                     //saveTracking
                    saveTrackingPayIn($bill,$type,$prefix); 
                     
                    //pay for owner of item
                    $pta = array();
                    $totsl = 0;
                    foreach($cartitem['items'] as $itc)
                    {
                         if ( isset($pta[$itc['owner_id']]))
                         {
                             $pta[$itc['owner_id']] = $pta[$itc['owner_id']] + $itc['amount'];
                         }
                         else
                         {
                             $pta[$itc['owner_id']] = $itc['amount'];
                         }
                         $totsl += $itc['amount']; 
                    }
                    foreach($pta as $key=>$value)
                    {
                        $user_group_id = getGroupUser($key,$prefix);
                        $settings = getSettingsSelling($user_group_id,$prefix);
                        if ( !isset($settings['comission_fee']))
                            {
                                $fee = 0;
                            }
                            else
                            {
                                $fee = $settings['comission_fee'];
                            }
                        $coupon = $cartitem['coupon_code']['value'];
                        $coupon = $coupon/$totsl;
                        //$coupon = 0;
                        //echo $_SESSION['musicsharing_cart']['coupon_code']['value'];die();
                        $coupon = round($coupon,2);
                        $val = ($value-$coupon*$value);
                        $fee = $fee*$val/100;  
                        $fee = round($fee,2);
                        $valuer = $val-$fee;
                        
                        
                        updateTotalAmount($key,$valuer,false,$prefix)   ;
                        
                          //send money to admin
                        $admin = getFinanceAccount(null,1,$prefix);  
                        
                        //phpFox::getService('musicsharing.cart.music')->updateTotalAmount($admin['user_id'],$totsl,false)   ;
                        //$link = $_REQUEST['return_url'];
                        $link = "";
                        $index1 = strpos($link,'cart');
                        $link = substr($link,0,$index1);
                        sendNotifycation('admin',$admin['user_id'],$cartitem,false,$prefix,$link) ;   
                        
                        
                        //end
                            //send notification for owner
                        foreach($cartitem['items'] as $itc)
                        {    
                            sendNotifycation($itc['type'],$bill['user_id'],$itc,false,$prefix,$link) ;
                             
                        }
                        die('sss'); 
                    }  
                }
                
            }
            
       break;
       default:
            die('No action');
}
function sendNotifycation($type,$user_id,$item,$is_request = false,$prefix,$link ='')
{
     $sql = "SELECT * FROM ".$prefix."user WHERE "
                        ." user_id = ".$user_id
                        ;
    $aActualUser = null;
    $result = mysql_query($sql);
    if($result)
    {
         $aAc = mysql_fetch_row($result) ;  
         $aActualUser = array(
                'user_id'=>$aAc[0],
                'server_id'=>$aAc[1],
                'user_group_id'=>$aAc[2],
                'status_id'=>$aAc[3],
                'view_id'=>$aAc[4],
                'user_name'=>$aAc[5],
                'full_name'=>$aAc[6],
                'password'=>$aAc[7],
                'password_salt'=>$aAc[8],
                'email'=>$aAc[9],
                'gender'=>$aAc[10],
                'birthday'=>$aAc[11],
                'birthday_search'=>$aAc[12],
                'country_iso'=>$aAc[13],
                'language_id'=>$aAc[14],
                'style_id'=>$aAc[15],
                'time_zone'=>$aAc[16],
                'dst_check'=>$aAc[17],
                'joined'=>$aAc[18],
                'last_login'=>$aAc[19],
                'last_activity'=>$aAc[20],
                'user_image'=>$aAc[21],
                'hide_tip'=>$aAc[22],
                'status'=>$aAc[23],
                'footer_bar'=>$aAc[2],
                'invite_user_id'=>$aAc[25],
                'im_beep'=>$aAc[26],
                'im_hide'=>$aAc[27],
                'is_invisible'=>$aAc[28],
                'total_spam'=>$aAc[29],
                'last_ip_address'=>$aAc[30],
                'test'=>$aAc[31],
            );
    }
    //echo "<pre>".print_r($aActualUser,true)."<pre>";
   // echo "<pre>".print_r($item,true)."<pre>";
    if ($aActualUser != null && $is_request == false)
        {
            if ( $type == 'song')
            {
                $sLink = $link.'music_'.$item['item_id'] ;
                $sql = "SELECT * FROM ".$prefix."m2bmusic_album_song WHERE "
                        ." song_id = ".$item['item_id'] 
                        ;
                $music = null;
                $result = mysql_query($sql);
                if($result)
                {
                     $music = mysql_fetch_row($result) ;  
                     $title = $music[2];
                } 
                $sql_insert = "INSERT INTO ".$prefix."notification"
                    ."(type_id,item_id,item_title,item_server_id,item_image,user_id,owner_user_id,is_seen,time_stamp) VALUES "
                    ."("
                    ."'musicsharing_buyitems'"
                    .",'".$item['item_id']."'"
                    .",'".$title."'"
                    .",'"."0"."'"
                    .",'".$aActualUser['user_image']."'"
                    .",'".$item['owner_id']."'"
                    .",'".$user_id."'"
                    .",'"."0"."'"
                    .",'".time()."'"
                    . ")";
                    $result =  mysql_query($sql_insert); 
                    if (!$result) {
                        echo ('Invalid query: ' . mysql_error());
                    }   
              
                    
            }
            elseif($type =='album')
            {
                $sLink = $link.'album_'.$item['item_id'] ;
                $sql = "SELECT * FROM ".$prefix."m2bmusic_album WHERE "
                        ." album_id = ".$item['item_id'] 
                        ;
                $music = null;
                $result = mysql_query($sql);
                //echo $sql;
                if($result)
                {
                     $music = mysql_fetch_row($result) ;  
                     $title = $music[2];
                }
                $sql_insert = "INSERT INTO ".$prefix."notification"
                    ."(type_id,item_id,item_title,item_server_id,item_image,user_id,owner_user_id,is_seen,time_stamp) VALUES "
                    ."("
                    ."'musicsharing_buyalbums'"
                    .",'".$item['item_id']."'"
                    .",'".$title."'"
                    .",'"."0"."'"
                    .",'".$aActualUser['user_image']."'"
                    .",'".$item['owner_id']."'"
                    .",'".$user_id."'"
                    .",'"."0"."'"
                    .",'".time()."'"
                    . ")";
                    $result =  mysql_query($sql_insert); 
                    if (!$result) {
                        echo ('Invalid query: ' . mysql_error());
                    }   
              
            }
            else
            {
                $sLink = $link.'myaccounts';
                $sql_insert = "INSERT INTO ".$prefix."notification"
                    ."(type_id,item_id,item_title,item_server_id,item_image,user_id,owner_user_id,is_seen,time_stamp) VALUES "
                    ."("
                    ."'musicsharing_buys'"
                    .",'".$item['user_id']."'"
                    .",'"."make a bill"."'"
                    .",'"."0"."'"
                    .",'".$aActualUser['user_image']."'"
                    .",'".$user_id."'"
                    .",'".$item['user_id']."'"
                    .",'"."0"."'"
                    .",'".time()."'"
                    . ")";
                    $result =  mysql_query($sql_insert); 
                    if (!$result) {
                        echo ('Invalid query: ' . mysql_error());
                    }   
                    
                //send to admin
               
                    
            }
    
      }
      if($is_request == true && $aActualUser != null)
      {
          if($type == 'yes')
          {
              $sLink = $link.'myaccounts';
                $sql_insert = "INSERT INTO ".$prefix."notification"
                    ."(type_id,item_id,item_title,item_server_id,item_image,user_id,owner_user_id,is_seen,time_stamp) VALUES "
                    ."("
                    ."'musicsharing_request_yes'"
                    .",'".$user_id."'"
                    .",'"."make a bill"."'"
                    .",'"."0"."'"
                    .",'".$aActualUser['user_image']."'"
                    .",'".$user_id."'"
                    .",'".$user_id."'"
                    .",'"."0"."'"
                    .",'".time()."'"
                    . ")";
                    $result =  mysql_query($sql_insert); 
                    if (!$result) {
                        echo ('Invalid query: ' . mysql_error());
                    }   
            
          }
          if($type =='no')
          {
              $sLink = $link.'myaccounts';
                $sql_insert = "INSERT INTO ".$prefix."notification"
                    ."(type_id,item_id,item_title,item_server_id,item_image,user_id,owner_user_id,is_seen,time_stamp) VALUES "
                    ."("
                    ."'musicsharing_request_refund_no'"
                    .",'".$user_id."'"
                    .",'"."make a bill"."'"
                    .",'"."0"."'"
                    .",'".$aActualUser['user_image']."'"
                    .",'".$user_id."'"
                    .",'".$user_id."'"
                    .",'"."0"."'"
                    .",'".time()."'"
                    . ")";
                    $result =  mysql_query($sql_insert); 
                    if (!$result) {
                        echo ('Invalid query: ' . mysql_error());
                    }   
                //send to admin
               
           
          }
      }
}
function updateTotalAmount($request_id,$total_amount,$is_request = true,$prefix)
{
    $account = getFinanceAccount($request_id,null,$prefix);
    
    if($account)
    {
          $sql_update = "UPDATE ".$prefix."m2bmusic_payment_account SET "
                  ."total_amount = ".($total_amount+$account['total_amount'])
                  ." WHERE user_id = ".$account['user_id']
                  ;
          
           $result =  mysql_query($sql_update); 
           
           if (!$result) {
               echo ('Invalid query: ' . mysql_error());
           }
           
      
    }
}
function getSettingsSelling($user_group_id,$prefix)
{
    $sql = "SELECT * FROM ".$prefix."m2bmusic_selling_settings WHERE "
                        ." user_group_id = ".$user_group_id
                        ;
    $settings = null;
    $result = mysql_query($sql);
    if($result)
    {
        $settings_ar2 = array();
        while ($row = mysql_fetch_assoc($result)) {
            $settings_ar2[] =  array(
                'setting_id'=>$row['setting_id'],
                'user_group_id'=>$row['user_group_id'],
                'module_id'=>$row['module_id'],
                'name'=>$row['name'],
                'default_value'=>$row['default_value'],
                'params'=>$row['params'],
                );
        }

       
        if(count($settings_ar2))
        {
            $settings = array();
            foreach($settings_ar2 as $ar )
            {
                 $settings[$ar['name']] = $ar['default_value'];  
            }
            return $settings;
        }
       
       
    }
    return null;
   
}
function getGroupUser($user_id,$prefix)
{
    $sql = "SELECT * FROM ".$prefix."user WHERE "
                        ." user_id = ".$user_id
                        ;
    
    $user = null;
    $result = mysql_query($sql);
    if($result)
    {
        $user = mysql_fetch_array($result);
        return $user['user_group_id'];
    }
    return 0;
        
}
function getFinanceAccount($user_id = null,$payment_type = null,$prefix)
{
    $query = "1 AND 1";
    if($user_id)
    {
        $query .= " AND user_id = ".$user_id ;
    }
    if($payment_type)
    {
        $query .=" AND payment_type = ".$payment_type;    
    }
    
    $sql = "SELECT * FROM ".$prefix."m2bmusic_payment_account WHERE "
                   .$query
                   ." LIMIT 0,1"    
                   ;

            $result = mysql_query($sql) or die(mysql_error()."<b>SQL was: </b>$sql");
            if($result)
            {
                $acc1 = mysql_fetch_row($result);
                $acc = array(
                    'payment_account_id'=>$acc1[0],
                    'account_username'=>$acc1[1],
                    'account_password'=>$acc1[2],
                    'user_id'=>$acc1[3],
                    'payment_type'=>$acc1[4],
                    'is_save_password'=>$acc1[5],
                    'total_amount'=>$acc1[6],
                    'last_check_out'=>$acc1[7],
                    'account_status'=>$acc1[8],
                    );
                return $acc;
            }
    return null;
}
function saveTrackingPayIn($bill,$type,$prefix)
{
    switch($type)
         {
             case 'bill':
             default:
                $bill_details = unserialize($bill['params']); 
                $acc = getFinanceAccount($bill_details['user_id'],null,$prefix);  
               
                foreach ($bill_details['items'] as $item)
                {
                    $sql_insert = "INSERT INTO ".$prefix."m2bmusic_transaction_tracking"
                                ."(transaction_date,user_seller,user_buyer,item_id,item_type,
                                   amount,account_seller_id,account_buyer_id,transaction_status,params) VALUES "
                                ."("
                                .$bill['date_bill']
                                .",'".$item['owner_id']."'"
                                .",'".$bill_details['user_id']."'"
                                .",'".$item['item_id']."'"
                                .",'".$item['type']."'"
                                .",'".$item['amount']."'"
                                .",'".$item['account_id']."'"
                                .",'".$acc['payment_account_id']."'"
                                .",'".$bill['bill_status']."'"
                                .",'".'buy'."'"
                                . ")";
                    $result =  mysql_query($sql_insert); 
                    if (!$result) {
                        echo ('Invalid query: ' . mysql_error());
                    }
                   
                    
                }
                
                break;
         }
}
function updateHistories($bill,$type,$timestamp,$prefix)
{
    switch($type)
        {
            case 'bill':
            default:
                $bill_details = unserialize($bill['params']);
             
                $total = $bill_details['total_amount'];
                $number_songs = 0;
                $number_albums = 0;
                foreach ($bill_details['items'] as $it)
                {
                    if ($it['type'] =='song')
                    {
                        $number_songs++;
                    }
                    if ($it['type'] =='album')
                    {
                        $number_albums++;
                    }
                }
             
                //get History
                $sql = "SELECT * FROM ".$prefix."m2bmusic_selling_history WHERE "
                        ." selling_datetime = ".$timestamp
                        ;
                
                $history = null;
                $result = mysql_query($sql);
                if($result)
                {
                     $his = mysql_fetch_row($result);
                     $history = array(
                        'm2bmusic_selling_history_id'=>$his[0],
                        'selling_datetime'=>$his[1],
                        'selling_total_upload_songs'=>$his[2],
                        'selling_total_download_songs'=>$his[3],
                        'selling_sold_songs'=>$his[4],
                        'selling_sold_albums'=>$his[5],
                        'selling_final_new_account'=>$his[6],
                        'selling_transaction_succ'=>$his[7],
                        'selling_transaction_fail'=>$his[8],
                        'selling_total_amount'=>$his[9],
                        'params'=>$his[10],
                        );
                }
                
                $params =  getParamHistory(array('sold_songs'=>$number_songs,
                                                'sold_albums'=>$number_albums,
                                                'total_amount'=>$total,
                                                'transaction_succ'=>$bill['bill_status']));    
               
                if ($history == null)
                {
                    //insert new history
                    $sql_insert = "INSERT INTO ".$prefix."m2bmusic_selling_history"
                    ."(selling_datetime,selling_total_upload_songs,selling_total_download_songs,selling_sold_songs,selling_sold_albums,
                       selling_final_new_account,selling_transaction_succ,selling_transaction_fail,selling_total_amount,params) VALUES "
                    ."("
                    .$timestamp
                    .",'".$params['selling_total_upload_songs']."'"
                    .",'".$params['selling_total_download_songs']."'"
                    .",'".$params['selling_sold_songs']."'"
                    .",'".$params['selling_sold_albums']."'"
                    .",'".$params['selling_final_new_account']."'"
                    .",'".$params['selling_transaction_succ']."'"
                    .",'".$params['selling_transaction_fail']."'"
                    .",'".$params['selling_total_amount']."'"
                    .",'".$params['params']."'"
                    . ")";
                    //echo $sql_insert;
                    $result =  mysql_query($sql_insert); 
                    if (!$result) {
                        echo ('Invalid query: ' . mysql_error());
                    }
                  
                    
                    
                }
                else
                {
                    //update infor
                    $params = $history;
                    $params['selling_sold_songs'] = $params['selling_sold_songs']+  $number_songs;
                    $params['selling_sold_albums'] = $params['selling_sold_albums']+  $number_albums;
                    $params['selling_total_amount'] = $params['selling_total_amount']+  $total ;
                    $params['selling_transaction_succ'] = $params['selling_transaction_succ']+  $bill['bill_status'] ;
                    $sql_update = "UPDATE ".$prefix."m2bmusic_selling_history SET "
                                  ."selling_sold_songs = ".$params['selling_sold_songs'].","
                                  ."selling_sold_albums = ".$params['selling_sold_albums'].","
                                  ."selling_total_amount = ".$params['selling_total_amount'].","
                                  ."selling_transaction_succ = ".$params['selling_transaction_succ']
                                  ." WHERE m2bmusic_selling_history_id = ".$history['m2bmusic_selling_history_id']
                                  ;
                   $result =  mysql_query($sql_update); 
                   //echo $sql_update;
                   if (!$result) {
                       echo ('Invalid query: ' . mysql_error());
                   }
                                    
                    
                    
                }
                
            break;
        }
}
function getParamHistory($object)
{
        $params = array();
        if (isset($object['upload_songs']))
        {
           $params['selling_total_upload_songs']  = $object['upload_songs'];
        }
        else
        {
           $params['selling_total_upload_songs'] = 0; 
        }
        if (isset($object['download_songs']))
        {
           $params['selling_total_download_songs']  = $object['download_songs'];
        }
        else
        {
           $params['selling_total_download_songs'] = 0; 
        }
        if (isset($object['sold_songs']))
        {
           $params['selling_sold_songs']  = $object['sold_songs'];
        }
        else
        {
           $params['selling_sold_songs'] = 0; 
        }
        if (isset($object['sold_albums']))
        {
           $params['selling_sold_albums']  = $object['sold_albums'];
        }
        else
        {
           $params['selling_sold_albums'] = 0; 
        }
        if (isset($object['new_accounts']))
        {
           $params['selling_final_new_account']  = $object['new_accounts'];
        }
        else
        {
           $params['selling_final_new_account'] = 0; 
        }
        if (isset($object['transaction_succ']))
        {
           $params['selling_transaction_succ']  = $object['transaction_succ'];
        }
        else
        {
           $params['selling_transaction_succ'] = 0; 
        }
        if (isset($object['transaction_fail']))
        {
           $params['selling_transaction_fail']  = $object['transaction_fail'];
        }
        else
        {
           $params['selling_transaction_fail'] = 0; 
        }
        if (isset($object['total_amount']))
        {
           $params['selling_total_amount']  = $object['total_amount'];
        }
        else
        {
           $params['selling_total_amount'] = 0; 
        }
        if (isset($object['params']))
        {
           $params['params']  = serialize($object['params']);
        }
        else
        {
           $params['params'] = ''; 
        }
        return $params;
        
        
}
function updateBillStatus($prefix,$bill,$status)
{
    $sql_update = "UPDATE ".$prefix."m2bmusic_bills SET"
                  ." bill_status = ".$status
                  ." WHERE bill_id = ".$bill['bill_id']
                  ;
    
   $result =  mysql_query($sql_update); 
   if (!$result) {
       echo ('Invalid query: ' . mysql_error());
   }
    
}
function moveItems2DownloadList($items,$prefix,$user_id)
{
  
        foreach($items as $key=>$value)
        {
            if ($value['type'] == 'song')           
            {
                   $sql_insert = "INSERT INTO ".$prefix."m2bmusic_downloadlist"
                    ."(dl_song_id,dl_album_id,user_id) VALUES "
                    ."("
                    .$value['item_id']
                    .",'"."0"."'"
                    .",'".$user_id."'"
                    . ")";
                    $result =  mysql_query($sql_insert); 
                    if (!$result) {
                        echo ('Invalid query: ' . mysql_error());
                    }
                  
            }
            if ($value['type'] == 'album')           
            {
                   $sql_insert = "INSERT INTO ".$prefix."m2bmusic_downloadlist"
                    ."(dl_song_id,dl_album_id,user_id) VALUES "
                    ."("
                    ."0"
                    .",'".$value['item_id']."'"
                    .",'".$user_id."'"
                    . ")";
                    $result =  mysql_query($sql_insert); 
                    if (!$result) {
                        echo ('Invalid query: ' . mysql_error());
                    }
                  
            }
        }
}
?>