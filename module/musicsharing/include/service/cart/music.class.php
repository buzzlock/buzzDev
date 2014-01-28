<?php
defined('PHPFOX') or exit('NO DICE!');   
$pathfile = PHPFOX_DIR_MODULE.'musicsharing'.PHPFOX_DS.'include'.PHPFOX_DS.'service'.PHPFOX_DS.'m2b_music_lib'.PHPFOX_DS.'cart'.PHPFOX_DS.'gateway.php';

if(file_exists($pathfile))      
{
    require_once $pathfile;
}

class Musicsharing_Service_Cart_Music extends Phpfox_Service  
{ 
    //public function updateTotalAmount($user_id,$)
    /** 
    * Update total amount of user from request.
    * 
    * @param mixed $request_id
    * @param mixed $total_amount
    */
    public function updateTotalAmount($request_id,$total_amount,$is_request = true)
    {
        if ($is_request == true)
        {
            $request = $this->getPaymentRequest($request_id);
            phpFox::getLib('phpfox.database')->update(phpFox::getT('m2bmusic_payment_account'),
                                                  array('total_amount'=>$total_amount-$request['request_amount'],
                                                  
                                                  )  ,'user_id  = '.$request['request_user_id']                           
            );
       
            
        }
        else
        {
             $account = $this->getFinanceAccount($request_id);
             $account_id = 0;
             if ( $account == null )
             {
                 $params = array();
                 $params['account_username']=phpFox::getUserBy('email');
                 $params['account_password']='';
                 $params['total_amount']= 0;
                 $params['account_status']= 1;
                 $params['payment_type']= 2;
                 $params['user_id']= phpFox::getUserId();
                 $account_id = phpFox::getService('musicsharing.cart.account')->insertAccount($params);
                  phpFox::getLib('phpfox.database')->update(phpFox::getT('m2bmusic_payment_account'),
                                                  array('total_amount'=>$total_amount+$account['total_amount'],
                                                  
                                                  )  ,'user_id  = '.$account_id      
                                                  );
             }
             else
             {
                 
                  phpFox::getLib('phpfox.database')->update(phpFox::getT('m2bmusic_payment_account'),
                                                  array('total_amount'=>$total_amount+$account['total_amount'],
                                                  
                                                  )  ,'user_id  = '.$account['user_id']       
                                                  );
             }
             //ccount = $this->getFinanceAccount($account_id);
            
        }
        
        
    }
    /**
    * get request information from id
    * 
    * @param mixed $request_id
    */
    public function getPaymentRequest($request_id)
    {
          $request = phpFox::getLib('phpfox.database')->select('*')
                    ->from(phpFox::getT('m2bmusic_payment_request'),'pr')
                    ->leftJoin(phpFox::getT('m2bmusic_payment_account'),'pa','pa.user_id = pr.request_user_id')
                    ->where('pr.payment_request_id = '.$request_id)
                    ->execute('getRow');
          return $request;
    }
    /**
    * update status of payment request
    * 
    * @param mixed $request_id
    * @param mixed $message
    * @param mixed $status
    */
    public function updatePaymentRequest($request_id,$message,$status)
    {
       
         phpFox::getLib('phpfox.database')->update(phpFox::getT('m2bmusic_payment_request '),
                                                    array('request_status'=>$status,'request_answer'=>$message),
                                                    'payment_request_id ='.$request_id) ;
                                                    
    }
    /**
    * Save record tracking when admin accept or deny any request from user.
    * 
    * @param mixed $request_id
    */
    public function saveTrackingPayOut($request_id)
    {
        
    }
    /**
    * Save record tracking when user invoice the bill.
    * 
    * @param mixed $params
    * @type default value =  bill
    */
    public function saveTrackingPayIn($params,$type = 'bill')
    {
        $insert_item = array();
         switch($type)
         {
             case 'bill':
             default:
                $bill_details = unserialize($params['params']); 
                $acc = phpFox::getService('musicsharing.cart.music')->getFinanceAccount($bill_details['user_id']);  
                foreach ($bill_details['items'] as $item)
                {
                    $insert_item[] = array(
                        'transaction_date'=>$params['date_bill'],
                        'user_seller'=>$item['owner_id'],
                        'user_buyer'=>$bill_details['user_id'],
                        'item_id'=>$item['item_id'],
                        'item_type'=>$item['type'],
                        'amount'=>$item['amount'],
                        'account_seller_id'=>$item['account_id'],
                        'account_buyer_id'=>$acc['payment_account_id'],
                        'transaction_status'=>$params['bill_status'],
                        'params'=>'buy',
                        
                    ) ;
                    
                }
                
                break;
         }
         if( count($insert_item)>0)
         {
              phpFox::getLib('phpfox.database')
                ->multiInsert(phpFox::getT('m2bmusic_transaction_tracking'),
                array('transaction_date','user_seller','user_buyer','item_id','item_type','amount','account_seller_id','account_buyer_id','transaction_status','params'),
                $insert_item
                ) ;
             
         }
    }
    /**
    * set default values for settings group member.
    * If the value does not exist, It will be set to default.
    *  
    * @param mixed $settings
    */
    public function setDefaultValueSelling($settings = array())
    {
       if ( !isset($settings['comission_fee'])) 
       {
           $settings['comission_fee'] = 0;
       }
       if ( !isset($settings['min_payout'])) 
       {
           $settings['min_payout'] = 30;
       }if ( !isset($settings['max_payout'])) 
       {
           $settings['max_payout'] = 100;
       }if ( !isset($settings['can_buy_song'])) 
       {
           $settings['can_buy_song'] = 0;
       }
       if ( !isset($settings['can_sell_song'])) 
       {
           $settings['can_sell_song'] = 0;
       }
       if ( !isset($settings['min_price_song'])) 
       {
           $settings['min_price_song'] = 0;
       }
       if ( !isset($settings['method_payment'])) 
       {
           $settings['method_payment'] = 1;
       }
       return $settings;
       
       
    }
    /**
    * Get selling setting of user groups.
    * 
    * @param mixed $user_group_id
    */
    public function getSettingsSelling($user_group_id)
    {
        $settings_ar = phpFox::getLib('phpfox.database')->select('*')
                    ->from(phpFox::getT('m2bmusic_selling_settings'),'ss')
                    ->where('ss.user_group_id = '.$user_group_id)
                    ->execute('getRows');
        $settings = array();
        foreach($settings_ar as $ar )
        {
             $settings[$ar['name']] = $ar['default_value'];  
        }
        return $settings;
    }
    /**
    * Save settings for user group.
    * 
    * @param mixed $settings
    * @param mixed $user_group_id
    */
    public function saveSettingsSelling($settings,$user_group_id)
    {
        phpFox::getLib('phpfox.database')->delete(phpFox::getT('m2bmusic_selling_settings'),'user_group_id  = '.$user_group_id);
        $insert_item = array();
        foreach($settings as $key=>$value)
        {
            if ($key != 'select_group_member')           
            {
                  $insert_item[] = array($user_group_id,'musicsharing',$key,$value 
                                        );
            }
        }
         phpFox::getLib('phpfox.database')
                ->multiInsert(phpFox::getT('m2bmusic_selling_settings'),
                array('user_group_id','module_id','name','default_value'),
                $insert_item
                ) ;
             
    }
    /**
    * get hitories of user from Date to Date
    * If user_id = null: get all users histories
    * 
    * @param mixed $user_id
    * @param mixed $fromDate
    * @param mixed $toDate
    * @param mixed $params : more conditions add here. Example paginator..
    */
    public function updateHistories($object,$type = 'bill',$timestamp)
    {
        switch($type)
        {
            case 'bill':
            default:
                $bill_details = unserialize($object['params']);
             
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
             
                $history = $this->getHistory($timestamp);
                $params =  $this->getParamHistory(array('sold_songs'=>$number_songs,'sold_albums'=>$number_albums,'total_amount'=>$total,'transaction_succ'=>$object['status_bill']));    
               
                if ($history == null)
                {
                    //insert new history
                    $this->insertHistory($timestamp,$params);
                    
                    
                }
                else
                {
                    //update infor
                    $params = $history;
                    $params['selling_sold_songs'] = $params['selling_sold_songs']+  $number_songs;
                    $params['selling_sold_albums'] = $params['selling_sold_albums']+  $number_albums;
                    $params['selling_total_amount'] = $params['selling_total_amount']+  $total ;
                    $params['selling_transaction_succ'] = $params['selling_transaction_succ']+  $object['status_bill'] ;
                   
                    $this->updateHistory($timestamp,$params);
                    
                    
                }
                
            break;
        }
    }
    /**
    * Init param for history
    * If value doesn't exist. It will be set to default value (zero)
    * 
    * @param mixed $object
    * @return string
    */
    public function getParamHistory($object)
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
    public function updateHistory($timestamp,$params = array())
    {
        $params['selling_datetime'] = $timestamp;
        phpFox::getLib('phpfox.database')->update(
                    phpFox::getT('m2bmusic_selling_history'),
                    $params,
                    'm2bmusic_selling_history_id ='.$params['m2bmusic_selling_history_id']
                    
            );
    }
    /**
    *  Insert new history
    * 
    * @param mixed $params
    */
    public function insertHistory($timestamp,$params = array())
    {
        $params['selling_datetime'] = $timestamp;
        phpFox::getLib('phpfox.database')->insert(
                    phpFox::getT('m2bmusic_selling_history'),
                    $params
            );
    }
    /**
    * get history in date
    * 
    * @param mixed $datetime
    */
    public function getHistory($datetime)
    {
       $result = phpFox::getLib('phpfox.database')->select('*')
                 ->from(phpFox::getT('m2bmusic_selling_history'))
                 ->where('selling_datetime = '.$datetime)
                 ->execute('getRow');
       return $result;
    }
    /**
    * get histories of user(s)
    * 
    * @param mixed $datetime
    * @param mixed $user_id
    * @param mixed $fromDate
    * @param mixed $toDate
    * @param mixed $params
    * @return mixed
    */
    public function getHistories($user_id =  null ,$fromDate = null ,$toDate = null,$params = array())
    {
        
        $condition = array();
        $condition[] =" 1=1 ";
        if ($user_id != null)
        {
            $condition[] = " AND his.user_id = ".$user_id;
        }
        if ($fromDate != null)
        {
            //$condition[] = "AND  his.selling_datetime >= ".$fromDate;
            //DATEDIFF(DATE_FORMAT( FROM_UNIXTIME(transaction_date),'%Y-%m-%d'),'2011-03-08')>=0
            $condition[] = " AND DATEDIFF(DATE_FORMAT( FROM_UNIXTIME(his.transaction_date),'%Y-%m-%d'),'".$fromDate."')>=0";
        }
        if ($toDate != null)
        {
            //$condition[] = "AND his.selling_datetime <= ".$toDate;
            $condition[] = " AND DATEDIFF(DATE_FORMAT( FROM_UNIXTIME(his.transaction_date),'%Y-%m-%d'),'".$toDate."')<=0";
        }
        foreach($params as $key=>$value)
        {
            if ( $key != 'limit' && $key!='group_by')
                $condition[]= $value;
        }
        if(!isset($params['limit']))
            $params['limit'] = 50;
        $count = 0;
        if(!isset($params['group_by']))
        {
             $count = 0;
            $table = phpFox::getT('m2bmusic_transaction_tracking') ;
            $histories = phpFox::getLib('phpfox.database')
                    ->select(" DATE_FORMAT( FROM_UNIXTIME(his.transaction_date),'%Y-%m-%d' ) as pDate,
                               (SELECT count(*) FROM ".$table." as t1
                                 WHERE DATE_FORMAT( FROM_UNIXTIME(t1.transaction_date),'%Y-%m-%d') = pDate and item_type='song' and item_id>0 and transaction_status =1) as selling_sold_songs,
                                 (SELECT count(*) FROM ".$table." as t1
                                 WHERE DATE_FORMAT( FROM_UNIXTIME(t1.transaction_date),'%Y-%m-%d') = pDate and item_type='album' and item_id>0 and transaction_status =1) as selling_sold_albums,
                                 (SELECT count(*) FROM ".$table." as t1
                                 WHERE DATE_FORMAT( FROM_UNIXTIME(t1.transaction_date),'%Y-%m-%d') = pDate and item_id > 0 and transaction_status = 0) as selling_transaction_fail,
                                 (SELECT count(*) FROM ".$table." as t1
                                 WHERE DATE_FORMAT( FROM_UNIXTIME(t1.transaction_date),'%Y-%m-%d') = pDate and item_id > 0 and transaction_status = 1) as selling_transaction_succ,
                                  (SELECT sum(amount) FROM ".$table." as t1
                                 WHERE DATE_FORMAT( FROM_UNIXTIME(t1.transaction_date),'%Y-%m-%d') = pDate and (item_type='song' or item_type='album') and item_id>0 and transaction_status = 1 ) as selling_total_amount
                                
                            ")
                    ->from(phpFox::getT('m2bmusic_transaction_tracking'),'his')
                    ->where($condition)
                    ->group("DATE_FORMAT( FROM_UNIXTIME(his.transaction_date),'%Y-%m-%d')")
                    ->order("DATE_FORMAT( FROM_UNIXTIME(his.transaction_date),'%Y-%m-%d') DESC")
                    ->limit($params['limit'])
                    ->execute('getRows');
            $count = count($histories);
            return array($histories,$count);
        }
        else
        {
            
             $count = 0;
            $table = phpFox::getT('m2bmusic_transaction_tracking') ;
            $histories = phpFox::getLib('phpfox.database')
                    ->select(" DATE_FORMAT( FROM_UNIXTIME(his.transaction_date),'%Y-%m-%d' ) as pDate,
                               (SELECT count(*) FROM ".$table." as t1
                                 WHERE DATE_FORMAT( FROM_UNIXTIME(t1.transaction_date),'%Y-%m-%d') = pDate and item_type='song' and item_id>0 and transaction_status =1) as selling_sold_songs,
                                 (SELECT count(*) FROM ".$table." as t1
                                 WHERE DATE_FORMAT( FROM_UNIXTIME(t1.transaction_date),'%Y-%m-%d') = pDate and item_type='album' and item_id>0 and transaction_status =1) as selling_sold_albums,
                                 (SELECT count(*) FROM ".$table." as t1
                                 WHERE DATE_FORMAT( FROM_UNIXTIME(t1.transaction_date),'%Y-%m-%d') = pDate and item_id > 0 and transaction_status = 0) as selling_transaction_fail,
                                 (SELECT count(*) FROM ".$table." as t1
                                 WHERE DATE_FORMAT( FROM_UNIXTIME(t1.transaction_date),'%Y-%m-%d') = pDate and item_id > 0 and transaction_status = 1) as selling_transaction_succ,
                                  (SELECT sum(amount) FROM ".$table." as t1
                                 WHERE DATE_FORMAT( FROM_UNIXTIME(t1.transaction_date),'%Y-%m-%d') = pDate and (item_type='song' or item_type='album') and item_id>0 and transaction_status = 1 ) as selling_total_amount
                                
                            ")
                    ->from(phpFox::getT('m2bmusic_transaction_tracking'),'his')
                    ->where($condition)
                    ->group($params['group_by'])
                    ->order("DATE_FORMAT( FROM_UNIXTIME(his.transaction_date),'%Y-%m-%d') ASC")
                    //->limit($params['limit'])
                    ->execute('getRows');
            $count = count($histories);
            
            return array($histories,$count);
        }
        /*if(!isset($params['group_by']))
        {
              $count = 0;
        
            $histories = phpFox::getLib('phpfox.database')
                    ->select('his.*,DATE( FROM_UNIXTIME( his.selling_datetime) ) AS pDate
                            ')
                    ->from(phpFox::getT('m2bmusic_selling_history '),'his')
                    ->where($condition)
                   // ->group($params['group_by'])
                    ->order('his.selling_datetime DESC')
                    ->limit($params['limit'])
                    ->execute('getRows');
            $count = count($histories);
            return array($histories,$count);
        }
        else
        {
             $count = 0;
        
            $histories = phpFox::getLib('phpfox.database')
                    ->select('his.*,DATE( FROM_UNIXTIME( his.selling_datetime) ) AS pDate
                            ')
                    ->from(phpFox::getT('m2bmusic_selling_history '),'his')
                    ->where($condition)
                    ->group($params['group_by'])
                    ->order('his.selling_datetime DESC')
                    ->limit($params['limit'])
                    ->execute('getRows');
            $count = count($histories);
            return array($histories,$count);
        }*/
        
       
     }
     /**
     * get total statistic of music sharing.
     * 
     * @param mixed $user_id
     * @param mixed $fromDate
     * @param mixed $toDate
     * @param mixed $params
     * @return mixed
     */
    public function getSumHistories($user_id =  null ,$fromDate = null ,$toDate = null,$params = array())
    {
        
        if(!isset($params['group_by']))
        {
              list($histories,$count) = $this->getHistories($user_id,$fromDate,$toDate,$params);
              $sumHistories = array(
                        'pDate'=>date('Y-m-d'),
                        'upload_song'=>0,
                        'download_song'=>0,
                        'sold_song'=>0,
                        'sold_album'=>0,
                        'selling_new_account'=>0,
                        'transaction_succ'=>0,
                       
                        'transaction_fail'=>0,
                         'total_amount'=>0,
                        );
                
              foreach($histories as $his)
              {
                    
                    $sumHistories['sold_song'] += $his['selling_sold_songs'];
                    $sumHistories['sold_album'] += $his['selling_sold_albums'];
                    $sumHistories['transaction_succ'] += $his['selling_transaction_succ'];
                    $sumHistories['transaction_fail'] += $his['selling_transaction_fail'];
                    $sumHistories['total_amount'] += $his['selling_total_amount'];
              }
              return array($sumHistories,1);
        }
        else
        {
              //$params['limit'] = -1;
              list($histories,$count)= $this->getHistories($user_id,$fromDate,$toDate); 
              $result = array();
              foreach($histories as $his)         
              {
                    //echo $his['pDate'].'-'.$his['selling_sold_songs']."<br/>";
                    $date = explode('-',$his['pDate']);
                    if($params['group_by'] == ' MONTH(pDate) ')
                        $index = $date[1];
                    if($params['group_by'] == ' YEAR(pDate) ')
                        $index =  $date[0];
                    
                    if(isset($result[$index]))
                    {
                        $result[$index]['sold_song'] += $his['selling_sold_songs'];
                        $result[$index]['sold_album'] += $his['selling_sold_albums'];
                    }
                    else
                    {
                        $result[$index]['sold_song'] = $his['selling_sold_songs'];
                        $result[$index]['sold_album'] = $his['selling_sold_albums'];
                    }
              }
              return array($result,count($result));
        }
       
        /* $condition = array();
        $condition[] =" 1=1 ";
        if ($user_id != null)
        {
            $condition[] = "AND his.user_id = ".$user_id;
        }
        if ($fromDate != null)
        {
            $condition[] = "AND  his.selling_datetime >= ".$fromDate;
        }
        if ($toDate != null)
        {
            $condition[] = "AND his.selling_datetime <= ".$toDate;
        }
        foreach($params as $key=>$value)
        {
            if ( $key != 'limit' && $key!='group_by')
                $condition[]= $value;
        }
        if(!isset($params['limit']))
            $params['limit'] = 50;
        if(!isset($params['group_by']))
        {
                $count = 0;
                //TRUNCATE(alsong.filesize/1024/1024,2)
            $histories = phpFox::getLib('phpfox.database')
                    ->select('his.*,DATE( FROM_UNIXTIME( his.selling_datetime) ) AS pDate,
                                    sum(his.selling_total_upload_songs) as upload_song,
                                    sum(his.selling_total_download_songs) as download_song,
                                    sum(his.selling_sold_songs) as sold_song,
                                    sum(his.selling_sold_albums) as sold_album,
                                    sum(his.selling_final_new_account) as selling_new_account,
                                    sum(his.selling_transaction_succ) as transaction_succ,
                                    sum(his.selling_transaction_fail) as transaction_fail,
                                    TRUNCATE(sum(his.selling_total_amount),2) as total_amount
                            ')
                    ->from(phpFox::getT('m2bmusic_selling_history '),'his')
                    ->where($condition)
                    //->group($params['group_by']) 
                    ->order('his.selling_datetime DESC')
                    ->limit($params['limit'])
                    ->execute('getRow');
            $count = count($histories);
            return array($histories,$count);    
            
        } 
        else
        {
            $count = 0;
            $histories = phpFox::getLib('phpfox.database')
                    ->select('his.*,DATE( FROM_UNIXTIME( his.selling_datetime) ) AS pDate,
                                    sum(his.selling_total_upload_songs) as upload_song,
                                    sum(his.selling_total_download_songs) as download_song,
                                    sum(his.selling_sold_songs) as sold_song,
                                    sum(his.selling_sold_albums) as sold_album,
                                    sum(his.selling_final_new_account) as selling_new_account,
                                    sum(his.selling_transaction_succ) as transaction_succ,
                                    sum(his.selling_transaction_fail) as transaction_fail,
                                    TRUNCATE(sum(his.selling_total_amount),2) as total_amount
                            ')
                    ->from(phpFox::getT('m2bmusic_selling_history '),'his')
                    ->where($condition)
                    ->group($params['group_by'])
                    ->order('his.selling_datetime DESC')
                    ->limit($params['limit'])
                    ->execute('getRows');
            $count = count($histories);
            return array($histories,$count);
        } */ 
        
     }
     /**
     * save request to tracking.
     * 
     * @param mixed $request_id
     * @param mixed $message
     * @param mixed $status
     */
    public function saveTransactionFromRequest($request_id,$message,$status,$adminccount)
    {
        list($count,$request) = $this->getFinanceAccountRequests("payment_request_id = ".$request_id,"",1,1);
        $re = $request[0];
        $insert_item = array();
        $insert_item[] = array($re['request_date'],$re['request_user_id'],$adminccount['user_id'],'','',$re['request_amount'],$re['request_payment_acount_id'],$adminccount['payment_account_id'],$status,'request') ;
        phpFox::getLib('phpfox.database')->multiInsert(
                phpFox::getT('m2bmusic_transaction_tracking'),
                array('transaction_date','user_seller','user_buyer','item_id','item_type','amount','account_seller_id','account_buyer_id','transaction_status','params'),
                 $insert_item 
            );
    }
     /**
     * get all transaction from date to date
     * 
     * @param mixed $user_id
     * @param mixed $fromDate
     * @param mixed $toDate
     * @param mixed $params
     * @return mixed
     */
    public function getTrackingTransaction($user_id =  null ,$fromDate = null ,$toDate = null,$params = array())
    {
        
        $condition = array();
        $condition[] =" 1=1 ";
        if ($user_id != null)
        {
            $condition[] = " AND (his.user_seller = ".$user_id." OR his.user_buyer = ".$user_id ." )";
        }
        if ($fromDate != null)
        {
            //$condition[] = "AND  his.selling_datetime >= ".$fromDate;
            //DATEDIFF(DATE_FORMAT( FROM_UNIXTIME(transaction_date),'%Y-%m-%d'),'2011-03-08')>=0
            $condition[] = " AND DATEDIFF(DATE_FORMAT( FROM_UNIXTIME(his.transaction_date),'%Y-%m-%d'),'".$fromDate."')>=0";
        }
        if ($toDate != null)
        {
            //$condition[] = "AND his.selling_datetime <= ".$toDate;
            $condition[] = " AND DATEDIFF(DATE_FORMAT( FROM_UNIXTIME(his.transaction_date),'%Y-%m-%d'),'".$toDate."')<=0";
        }
        
        foreach($params as $key=>$value)
        {
            if ($key !='limit' && $key!='page')    
                $condition[]= $value;
        }
        if(!isset($params['limit']))
            $params['limit'] = 500;
        $count = 0;
        if ( isset($params['page']) && $params['page']>0)
        {
           
              $histories = phpFox::getLib('phpfox.database')
                ->select('his.*,DATE( FROM_UNIXTIME( his.transaction_date) ) AS pDate,
                         (SELECT user_name FROM '.phpFox::getT('user').' as pu WHERE pu.user_id = his.user_seller ) as seller_user_name,
                         (SELECT user_name FROM '.phpFox::getT('user').' as pu WHERE pu.user_id = his.user_buyer ) as buyer_user_name,
                         (SELECT account_username FROM '.phpFox::getT('m2bmusic_payment_account').' as pu WHERE pu.payment_account_id = his.account_seller_id ) as account_seller_email,
                         (SELECT account_username FROM '.phpFox::getT('m2bmusic_payment_account ').' as pu WHERE pu.payment_account_id = his.account_buyer_id  ) as account_buyer_email
                        ')
                    ->from(phpFox::getT('m2bmusic_transaction_tracking '),'his')
                    ->where($condition)
                    ->order('his.transaction_date DESC')
                    ->execute('getRows');
           $iCnt = count($histories);
            $page = $params['page'];
              $histories = phpFox::getLib('phpfox.database')
                ->select('his.*,DATE( FROM_UNIXTIME( his.transaction_date) ) AS pDate,
                         (SELECT user_name FROM '.phpFox::getT('user').' as pu WHERE pu.user_id = his.user_seller ) as seller_user_name,
                         (SELECT user_name FROM '.phpFox::getT('user').' as pu WHERE pu.user_id = his.user_buyer ) as buyer_user_name,
                         (SELECT account_username FROM '.phpFox::getT('m2bmusic_payment_account').' as pu WHERE pu.payment_account_id = his.account_seller_id ) as account_seller_email,
                         (SELECT account_username FROM '.phpFox::getT('m2bmusic_payment_account ').' as pu WHERE pu.payment_account_id = his.account_buyer_id  ) as account_buyer_email
                        ')
                ->from(phpFox::getT('m2bmusic_transaction_tracking '),'his')
                //->leftjoin(phpFox::getT('user'),'pu','pu.user_id = his.user_seller')
                ->where($condition)
                //->group('selling_datetime')
                ->order('his.transaction_date DESC')
                ->limit($params['page'], $params['limit'], $iCnt)
                ->execute('getRows');
           
            return array($histories,$iCnt);
        }
        else
        {
            
              $histories = phpFox::getLib('phpfox.database')
                ->select('his.*,DATE( FROM_UNIXTIME( his.transaction_date) ) AS pDate,
                         (SELECT user_name FROM '.phpFox::getT('user').' as pu WHERE pu.user_id = his.user_seller ) as seller_user_name,
                         (SELECT user_name FROM '.phpFox::getT('user').' as pu WHERE pu.user_id = his.user_buyer ) as buyer_user_name,
                         (SELECT account_username FROM '.phpFox::getT('m2bmusic_payment_account').' as pu WHERE pu.payment_account_id = his.account_seller_id ) as account_seller_email,
                         (SELECT account_username FROM '.phpFox::getT('m2bmusic_payment_account ').' as pu WHERE pu.payment_account_id = his.account_buyer_id  ) as account_buyer_email
                        ')
                ->from(phpFox::getT('m2bmusic_transaction_tracking '),'his')
                //->leftjoin(phpFox::getT('user'),'pu','pu.user_id = his.user_seller')
                ->where($condition)
                //->group('selling_datetime')
                 ->order('his.transaction_date DESC')               
                ->limit($params['limit'])
                ->execute('getRows');
            $count = count($histories);
            //die();
            return array($histories,$count);
        }
    
      
     }
     /**
     * get all finance account .
     * 
     * @param mixed $aConds
     * @param mixed $sSort
     * @param mixed $iPage
     * @param mixed $sLimit
     * @param mixed $bCount
     */
     public function getFinanceAccountRequests($aConds = array(),$sSort = 'last_check_out ASC', $iPage = '', $sLimit = '', $bCount = true)
    {

        $iCnt = ($bCount ? 0 : 1);
        $items = array();
        //$con = array();
        if ($bCount ){
            $iCnt = phpFox::getLib('phpfox.database')->select('COUNT(*)')
            ->from(phpFox::getT('m2bmusic_payment_account'),'ni')
            ->leftJoin(phpFox::getT('user'), 'nf', 'nf.user_id = ni.user_id')
            ->leftJoin(phpFox::getT('m2bmusic_payment_request'), 'pr', 'pr.request_payment_acount_id = ni.payment_account_id')
            ->where($aConds)
            ->execute('getField');//echo $iCnt;

        }
        if ($iCnt){

            $items = phpFox::getLib('phpfox.database')->select('ni.*,nf.user_name,pr.request_payment_acount_id,pr.request_amount as payment_request,pr.payment_request_id,pr.request_status,pr.request_date,pr.request_user_id,pr.request_amount,pr.request_reason')
            ->from(phpFox::getT('m2bmusic_payment_account'),'ni')
            ->leftJoin(phpFox::getT('user'), 'nf', 'nf.user_id = ni.user_id')
            ->leftJoin(phpFox::getT('m2bmusic_payment_request'), 'pr', 'pr.request_payment_acount_id = ni.payment_account_id')
            ->where($aConds)
            ->order($sSort)
            ->limit($iPage, $sLimit, $iCnt)
            ->execute('getRows');
        }
        if (!$bCount)
        {
            return $items;
        }
      
        //(($sPlugin = Phpfox_Plugin::get('News.component_service_news_getFeeds__end')) ? eval($sPlugin) : false);

        return array($iCnt, $items);
    }
    /**
    * get finance accounts 
    * 
    * @param mixed $aConds
    * @param mixed $sSort
    * @param mixed $iPage
    * @param mixed $sLimit
    * @param mixed $bCount
    */
      public function getFinanceAccounts($aConds = array(),$sSort = 'last_check_out ASC', $iPage = '', $sLimit = '', $bCount = true)
    {

        $iCnt = ($bCount ? 0 : 1);
        $items = array();
        //$con = array();
        if ($bCount ){
            $iCnt = phpFox::getLib('phpfox.database')->select('COUNT(*)')
            ->from(phpFox::getT('m2bmusic_payment_account'),'ni')
            ->leftJoin(phpFox::getT('user'), 'nf', 'nf.user_id = ni.user_id')
            ->where($aConds)
            ->execute('getField');//echo $iCnt;

        }
        if ($iCnt){

            $items = phpFox::getLib('phpfox.database')->select('ni.*,nf.user_name')
            ->from(phpFox::getT('m2bmusic_payment_account'),'ni')
            ->leftJoin(phpFox::getT('user'), 'nf', 'nf.user_id = ni.user_id')
            ->where($aConds)
            ->order($sSort)
            ->limit($iPage, $sLimit, $iCnt)
            ->execute('getRows');
        }
        if (!$bCount)
        {
            return $items;
        }
      
        //(($sPlugin = Phpfox_Plugin::get('News.component_service_news_getFeeds__end')) ? eval($sPlugin) : false);

        return array($iCnt, $items);
    }
    /**
    * get FinnceAccount from user_id
    * 
    * @param mixed $user_id
    * @param mixed $payment_type
    */
    public function getFinanceAccount($user_id = null,$payment_type = null)
    {
        $con = array();
        $con[] = " 1 AND 1";
        if($user_id != null)
        {
            $con[] = " AND pa.user_id = ".$user_id;
        }
        if($payment_type != null)
        {
            $con[] = " AND pa.payment_type  = ".$payment_type;
        }
        $account = phpFox::getLib('phpfox.database')->select('*')
                   ->from(phpFox::getT('m2bmusic_payment_account'),'pa')
                   ->where($con)
                   ->execute('getRow');
        return $account;
    }
    /**
    * insert or update finance account
    * 
    * @param mixed $account
    */
    public function saveFinanceAccount($account)
    {
        if(isset($account['payment_account_id']) && $account['payment_account_id']>0)
        {
            //update info of this account
            phpFox::getLib('phpfox.database')
                ->update(phpFox::getT('m2bmusic_payment_account '),$account,'payment_account_id  = '.$account['payment_account_id']);
        }
        else
        {
            //insert new account
            phpFox::getLib('phpfox.database')
            ->insert(phpFox::getT('m2bmusic_payment_account '),$account); 

        }
    }
    /**
    * set default value for account if it dose not exist.
    * 
    * @param mixed $account
    */
    public function setDefaultValueAccount($account)
    {
        if(!isset($account['account_username']))
        {
            $account['account_username'] = 'your_email_account@payment.com';
        }
        if(!isset($account['account_password']))
        {
            $account['account_password'] = '';
        }
        if(!isset($account['user_id']))
        {
            $account['user_id'] = phpFox::getUserId();
        }
        if(!isset($account['payment_type']))
        {
            $account['payment_type'] =2;
        }
        if(!isset($account['is_save_password']))
        {
            $account['is_save_password'] =0;
        }
        if(!isset($account['total_amount']))
        {
            $account['total_amount'] = 0;
        }
        if(!isset($account['last_check_out']))
        {
            $account['total_amount'] = '';
        }
        return $account;
    }
    /**
    * get default setting of payment method.
    *      
    * @param mixed $payment_method
    */
    public function getDefaultSettingsPayment($payment_method = 'musicsharing_paypal')
    {
        $setting = phpFox::getLib('phpfox.database')->select('setting')
                    ->from(phpFox::getT('api_gateway'))
                    ->where('gateway_id  = "'.$payment_method.'"')
                    ->execute('getField');
                    
        return $setting;
    }
    /**
    * Get Security Code  for transcation 
    * 
    */
    public function getSecurityCode()
    {
        $sid = 'abcdefghiklmnopqstvxuyz0123456789ABCDEFGHIKLMNOPQSTVXUYZ';
        $max =  strlen($sid) - 1;
        $res = "";
        for($i = 0; $i<16; ++$i){
            $res .=  $sid[mt_rand(0, $max)];
        }  
        return $res;
    }
      /**
    * load gateway user uses
    * 
    * @param mixed $gateway_name
    */
    public function loadGateWay($gateway_name = 'paypal')
    {
        $gateway = new gateway();
        $p = $gateway->load($gateway_name);
        return $p;
    }
    /**
    * get default settings from db of gateway
    * 
    * @param mixed $gateway_name
    */
    public function getSettingsGateWay($gateway_name = 'paypal')
    {
       
        $settings = phpFox::getService('musicsharing.cart.gateway')->getSettingGateway($gateway_name);        
       
        $settings['params'] = unserialize($settings['params']);
        if ( isset($settings['params']['use_proxy']) && $settings['params']['use_proxy'] == 1)
            $use_proxy = true;
        else
            $use_proxy = false;
        $mode = $this->getSettingsSelling(0);
        if ($mode != null)
        {
            $mode = $mode['is_test_mode'];    
        }
        else
        {
            $mode = 0;
        }
        switch($gateway_name)
        {
            case 'paypal':
            default:
                if($mode == 1 )
                {
                    $m = 'sandbox';
                }
                else
                {
                    $m='real';
                }
                $aSetting = array(
                    'env' =>$m,
                    'proxy_host' => $settings['params']['proxy_host'],
                    'proxy_port' => $settings['params']['proxy_port'],
                    'api_username' =>$settings['params']['api_username'],
                    'api_password' =>$settings['params']['api_password'],
                    'api_signature' =>$settings['params']['api_signature'],
                    'api_app_id' =>$settings['params']['api_app_id'],
                    'use_proxy' =>$use_proxy,
                );
                break;
            
        }
        
        return $aSetting;
    }
    /**
    * get params payment for gateway.
    * 
    * @param mixed $gateway_name
    * @param mixed $returnUrl
    * @param mixed $cancelUrl
    * @param mixed $receivers
    */
    public function getReceivers($gateway_name ='paypal',$method_payment = 'directly',$request = false)
    {
        $request = false;
        if ( $request == false)
        {
            $fee = 0;
            $total_pay =phpFox::getService('musicsharing.cart.shop')->updateTotalAmount($fee,true);
            $coupon = phpFox::getService('musicsharing.cart.shop')->getCouponCodeCart();
            $total_pay = $total_pay - $coupon['value']  ;
            
        }
        else
        {
            $fee = 0;
            $total_pay =phpFox::getService('musicsharing.cart.shop')->updateTotalAmount($fee,true);
            $settings = $this->getSettingsSelling(phpFox::getUserBy('user_group_id'));
            if ( !isset($settings['comission_fee']))
            {
                $fee = 0;
            }
            else
            {
                $fee = $settings['comission_fee'];
            }
            $fee = $fee*$total_pay;  
            $total_pay =phpFox::getService('musicsharing.cart.shop')->updateTotalAmount($fee,true);      
            
        }
        
        
        $settings = phpFox::getService('musicsharing.cart.gateway')->getSettingGateway($gateway_name);
        switch($gateway_name)
        {
            case 'paypal':
            default:
                $settings['params'] = unserialize($settings['params']);
                $receivers = array(
                    array('email' => $settings['admin_account'],'amount' => $total_pay,'invoice' => $this->getSecurityCode()),
                   // array('email' => 'music1_1298365241_per@yahoo.com','amount' => '22.22','invoice' => $this->getSecurityCode()),
                    
                 );
            break; 
        }

        return $receivers;
    }
    /**
    * Return param format of payment gateway.
    * 
    * @param mixed $gateway_name
    * @param mixed $returnUrl
    * @param mixed $cancelUrl
    * @param mixed $method_payment
    */
    public function getParamsPay($gateway_name = 'paypal',$returnUrl,$cancelUrl,$method_payment = 'multi',$notifyUrl = '')
    {
          $receivers = $this->getReceivers($gateway_name,$method_payment);
         $invoice = "";
         foreach ($receivers as $rec)
         {
             $invoice .='-'.$rec['invoice'];
         }
         if ($invoice !="")
         {
             $invoice = substr($invoice,1);
             //$invoice = "/".$invoice;
         }
         
         switch($gateway_name)
         {
             case 'paypal':
             default:
                
                $paramsPay = array(
                'actionType' => 'PAY',
                'cancelUrl'  => $cancelUrl.$invoice,
                'returnUrl'  => $returnUrl.$invoice,
                'currencyCode' => phpFox::getService('core.currency')->getDefault(),
                'sender'=>'',
                'feesPayer'=>'EACHRECEIVER',//feesPayer value {SENDER, PRIMARYRECEIVER, EACHRECEIVER}
                'ipnNotificationUrl'=> $notifyUrl.$invoice,
                'memo'=> '',
                'pin'=> '',
                'preapprovalKey'=> '',
                'reverseAllParallelPaymentsOnError'=> '',
                'receivers' => $receivers,
                );
             break;
         }
         return array($receivers,$paramsPay);
    }
    /**
    * move all items from cart to user's download list
    * 
    * @param mixed $cartlist
    */
    public function moveItems2DownloadList($cartlist = array())
    {
        $insert_item = array();
        
        foreach($cartlist as $key=>$value)
        {
            
            if ($value['type'] == 'song')           
            {
               
                  $insert_item[] = array($value['item_id'],0,phpFox::getUserId()
                                        );
            }
            if ($value['type'] == 'album')           
            {
                  $insert_item[] = array(0,$value['item_id'],phpFox::getUserId()
                                        );
            }
        }
         phpFox::getLib('phpfox.database')
                ->multiInsert(phpFox::getT('m2bmusic_downloadlist'),
                array('dl_song_id','dl_album_id','user_id'),
                $insert_item
                ) ;
    }
    public function removeDownloadItem($item_id)
    {
        phpFox::getLib('phpfox.database')->delete(phpFox::getT('m2bmusic_downloadlist'),'dl_id IN('.$item_id.')');
        return true;
    }
    /**
    * Get download list
    * 
    * @param mixed $aConds
    * @param mixed $sSort
    * @param mixed $iPage
    * @param mixed $sLimit
    * @param mixed $bCount
    */
     public function getDownloadList($aConds = array(),$sSort = 'dl_id DESC', $iPage = '', $sLimit = '', $bCount = true)
     {
         
         phpFox::getLib('phpfox.database')->query('SET character_set_results=utf8 ');    
         $iCnt = ($bCount ? 0 : 1);
         $items = array();
         if ($bCount )
         { 
             $iCnt = phpFox::getLib('phpfox.database')->select('COUNT(*)')
                    ->from(phpFox::getT('m2bmusic_downloadlist'),dl)
                    ->leftJoin(phpFox::getT('m2bmusic_album_song'),'alsong','alsong.song_id=dl.dl_song_id')
                    ->leftJoin(phpFox::getT('m2bmusic_singer'),'singer','singer.singer_id=alsong.singer_id')
                    ->leftJoin(phpFox::getT('m2bmusic_album'),'album','album.album_id=dl.dl_album_id')
					
                    ->where($aConds)
                   ->execute('getField');
         }
         if ($iCnt)
         {
            $items = phpFox::getLib('phpfox.database')->select('alsong.*,singer.title as singer_title,album.title as album_title,album.album_id,TRUNCATE(alsong.filesize/1024/1024,2) as sizemb,dl_id')
                  
                    ->from(phpFox::getT('m2bmusic_downloadlist'),dl)
                    ->leftJoin(phpFox::getT('m2bmusic_album_song'),'alsong','alsong.song_id=dl.dl_song_id')
                    ->leftJoin(phpFox::getT('m2bmusic_singer'),'singer','singer.singer_id=alsong.singer_id')
                    ->leftJoin(phpFox::getT('m2bmusic_album'),'album','album.album_id=dl.dl_album_id')
                    ->where($aConds)
                    ->order($sSort)
                    ->limit($iPage, $sLimit, $iCnt)
                    ->execute('getSlaveRows');  
         }
         if (!$bCount)
         {
            return $items;
         }
         
         return array($iCnt, $items);
    }
    /**
    * Get all songs from albums
    * 
    * @param mixed $item_id
    */
    public function getSongInAlbum($item_id = null)
    {
        phpFox::getLib('phpfox.database')->query('SET character_set_results=utf8 ');             
        $songs = phpFox::getLib('phpfox.database')->select('*')
                ->from(phpFox::getT('m2bmusic_album_song'))
                ->where('album_id = '.$item_id)
                ->execute('getRows');
        return $songs;
        
    }
    public function generateTime($value,$option,$first = true)
    {   
        if ( $option == 'month')
        {
            if ( $first == true)
                $day = '01';
            else
                $day = '31';
            $date = date('Y');
            if ($value <10 )
                $date.='-0'.$value.'-'.$day;
            else
                $date.='-'.$value.'-'.$day;
            
            $time = strtotime($date);
            return $time;
        }
        if ( $option =='year')
        {
            if ( $first == true)
                $day = '-01-01';
            else
                $day = '-12-31';
            
            $date = $value.$day;
            $time = strtotime($date);
            return $time;
        }
    }
    /**
    * Get group of user by user_id
    * 
    * @param mixed $user_id
    */
    public function getGroupUser($user_id)
    {
        $user = phpFox::getLib('phpfox.database')->select('*')
                ->from(phpFox::getT('user'),'u')
                ->where('user_id  ='.$user_id)
                ->execute('getRow');
        if($user != null)
        {
            return $user['user_group_id'];
        }
        return 0;
        
    }
    /**
    * Send notification for owner item
    * 
    * @param mixed $type
    * @param mixed $user_id
    * @param mixed $item
    */
    public function sendNotifycation($type,$user_id,$item,$is_request = false)
    {
        $aActualUser = phpFox::getService('user')->getUser($user_id);  
      
        if ($aActualUser != null && $is_request == false)
        {
            if ( $type == 'song')
            {
                $sLink = phpFox::getLib('url')->makeUrl('musicsharing.listen', array('music'=>$item['item_id'])) ;
                            $music = phpFox::getLib('phpfox.database')->select('*')
                    ->from(phpFox::getT('m2bmusic_album_song'))
                    ->where('song_id = '.$item['item_id'])
                    ->execute('getRow');
                phpFox::getService('notification.process')->add('musicsharing_buyitems', $item['item_id'], $item['owner_id'], array(
                    'title' => $music['title'],
                    'user_id' => phpFox::getUserId(),
                    'image' => phpFox::getUserBy('user_image'),
                    'server_id' =>  $aActualUser['server_id'],
                    'owner_user_id'=>$aActualUser['user_id']
                )
            );    
                    
            }
            elseif($type =='album')
            {
                $sLink = phpFox::getLib('url')->makeUrl('musicsharing.listen', array('album'=>$item['item_id'])) ;
                            $music = phpFox::getLib('phpfox.database')->select('*')
                    ->from(phpFox::getT('m2bmusic_album'))
                    ->where('album_id = '.$item['item_id'])
                    ->execute('getRow');
                phpFox::getService('notification.process')->add('musicsharing_buyalbums', $item['item_id'], $item['owner_id'], array(
                    'title' => $music['title'],
                    'user_id' => phpFox::getUserId(),
                    'image' => phpFox::getUserBy('user_image'),
                    'server_id' =>  $aActualUser['server_id'],
                    'owner_user_id'=>$aActualUser['user_id']
                )
                    );
                    
            }
            else
            {
                //send to admin
                $sLink = phpFox::getLib('url')->makeUrl('musicsharing.myaccounts');
                phpFox::getService('notification.process')->add('musicsharing_buys', $item['owner_id'], $user_id, array(
                    'title' => 'make a bill',
                    'user_id' => phpFox::getUserId(),
                    'image' => phpFox::getUserBy('user_image'),
                    'server_id' =>  $aActualUser['server_id'],
                    'owner_user_id'=>$aActualUser['user_id']
                )
                    );
                    
            }
         /*   phpFox::getLib('mail')
                ->to($aActualUser['email'])
                ->subject(array('musicsharing.user_name_buy_an_item_on_site_title', array('user_name' => $aActualUser['user_name'], 'site_title' => phpFox::getParam('core.site_title'))))
                ->message(array('musicsharing.user_name_buy_an_item_on_site_title_message', array(
                            'user_name' => $aActualUser['user_name'],
                            'site_title' => phpFox::getParam('core.site_title'),
                            'link' => $sLink
                        )
                    )
                )
                //->notification('comment.add_new_comment')
                ->send();

           */ 
            //$aActualUser = phpFox::getService('user')->getUser($iUserId);
      }
      if($is_request == true && $aActualUser != null)
      {
          if($type == 'yes')
          {
             $sLink = phpFox::getLib('url')->makeUrl('musicsharing.myaccounts') ;
                          
                phpFox::getService('notification.process')->add('musicsharing_request_yes', $user_id, $user_id, array(
                    'title' => $aActualUser['user_image'],
                    'user_id' => phpFox::getUserId(),
                    'image' => phpFox::getUserBy('user_image'),
                    'server_id' =>  $aActualUser['server_id'],
                    'owner_user_id'=>$aActualUser['user_id']
                )); 
          }
          if($type =='no')
          {
               phpFox::getService('notification.process')->add('musicsharing_request_refund_no', $user_id, $user_id, array(
                    'title' => $aActualUser['user_image'],
                    'user_id' => phpFox::getUserId(),
                    'image' => phpFox::getUserBy('user_image'),
                    'server_id' =>  $aActualUser['server_id'],
                    'owner_user_id'=>$aActualUser['user_id']
                )); 
          }
      }
    }
    public function getRequestsFromUser($user_id)
    {
        $requests = phpFox::getLib('phpfox.database')->select('*,DATE( FROM_UNIXTIME(request_date) ) AS request_date')
                    ->from(phpFox::getT('m2bmusic_payment_request'))
                    ->where('request_user_id = '.$user_id .' AND request_status <>0')
                    ->order('payment_request_id DESC ')
                    ->limit(10)
                    ->execute('getRows');
        return $requests;
        
    }
    public function getSumAmountTransaction($type='buy')
    {
        $query = " AND 1 = 1";
        $query.=" AND transaction_status = 1";
        $res = phpFox::getLib('phpfox.database')->select('sum(amount) as total,params')
              ->from(phpFox::getT('m2bmusic_transaction_tracking'),'his')
              ->where('his.params = "'.$type.'"'.$query)
              ->group('params')
              ->execute('getRow');
        return $res;
        
    }
        
        
} 
