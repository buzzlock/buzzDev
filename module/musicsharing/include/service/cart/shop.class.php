<?php
defined('PHPFOX') or exit('NO DICE!');   
$pathfile = PHPFOX_DIR_MODULE.'musicsharing'.PHPFOX_DS.'include'.PHPFOX_DS.'service'.PHPFOX_DS.'m2b_music_lib'.PHPFOX_DS.'cart'.PHPFOX_DS.'gateway.php';

if(file_exists($pathfile))      
{
    require_once $pathfile;
}

class Musicsharing_Service_Cart_Shop extends Phpfox_Service  
{ 
   
    /**
    * Check coupon code is valid ?
    * 
    * @param mixed $coupon
    */
    public function checkCouponCode($coupon ="")
    {
         
         //return 10;
         $now = time();
         $coup = phpFox::getLib('phpfox.database')->select('*')
                ->from(phpFox::getT('m2bmusic_coupon'))
                ->where('coupon_code = "'.$coupon .'" AND start_date <= '.$now .' AND end_date >= '.$now.' AND coupon_status = 1')
                ->execute('getRow') ;
       
         if ($coup != null)
         {
             return $coup['coupon_value'];
         }
         
         return 0;
    }
    /**
    * Init value for shoping cart
    * 
    * @param mixed $security_code
    */
    public function initCartShopSession($security_code = null)
    {
       
            $_SESSION['musicsharing_cart'] = array(
                        //'payment_sercurity' => $security_code,
                        'user_id'=>phpFox::getUserId(),
                        'total_amount' => 0,
                        'coupon_code'=>array(
                                'code' => '',
                                'value' =>0,
                                ),
                        'items' =>array(
                                
                                ),
                    );
       
    }
    public function getTotalAmount()
    {
        if(!isset($_SESSION['musicsharing_cart']))
         {
            $this->initCartShopSession();
         }
         return $_SESSION['musicsharing_cart']['total_amount'];
    }
    /**
    * Get current cart user id
    * 
    */
    public function getCurrentUserCart()
    {
         if(!isset($_SESSION['musicsharing_cart']['user_id']))
         {
             return -1;
         }
         return $_SESSION['musicsharing_cart']['user_id'];
    }
    /**
    * get Coupon for cart.
    * 
    */
    public function getCouponCodeCart()
    {
         if(!isset($_SESSION['musicsharing_cart']))
         {
             $this->initCartShopSession();
         }
         return  $_SESSION['musicsharing_cart']['coupon_code'];
    }
    /**
    * Put coupon code to your bill
    * 
    * @param mixed $coupon_code
    * @param mixed $value
    */
    public function updateCouponCode($coupon_code = '' ,$value = 0)
    {
         if(!isset($_SESSION['musicsharing_cart']))
         {
             $this->initCartShopSession();
         }
         if($_SESSION['musicsharing_cart']['user_id'] == phpFox::getUserId())                   
         {
               $_SESSION['musicsharing_cart']['coupon_code'] = array('code'=>$coupon_code,'value'=>$value);
         }
         
    }
    public function checkExistCouponCode($coupon_code = '')
    {
         if(!isset($_SESSION['musicsharing_cart']))
         {
             $this->initCartShopSession();
         }
         if($_SESSION['musicsharing_cart']['user_id'] == phpFox::getUserId())                   
         {
               if ( $_SESSION['musicsharing_cart']['coupon_code']['code'] == $coupon_code)
               {
                   return true;
               }
         }
         return false;
         
    }
    /**
    * Update total Amount from cart.
    * 
    * @param mixed $value
    * @param mixed $add
    * @return mixed
    */
    public function updateTotalAmount($value,$add = true)
    {
         if(!isset($_SESSION['musicsharing_cart']))
         {
             $this->initCartShopSession();
         }
         if($_SESSION['musicsharing_cart']['user_id'] == phpFox::getUserId())                   
         {
                if($add == true)
                 {
                    $_SESSION['musicsharing_cart']['total_amount'] = $_SESSION['musicsharing_cart']['total_amount'] + $value; 
                 }
                 else
                 {
                     $_SESSION['musicsharing_cart']['total_amount'] = $_SESSION['musicsharing_cart']['total_amount'] - $value;
                 }
                 if ($_SESSION['musicsharing_cart']['total_amount'] < 0 )
                    $_SESSION['musicsharing_cart']['total_amount'] = 0   ;
         }
         
         return $_SESSION['musicsharing_cart']['total_amount'];
            
            
    }
    /**
    * Get cart item from current session 
    * 
    */
    public function getCartItems()
    {
       
        if(isset($_SESSION['musicsharing_cart']))            
        {
            if($_SESSION['musicsharing_cart']['user_id'] == phpFox::getUserId())
            {
                return $_SESSION['musicsharing_cart']['items'];
            }
            else
            {
                $this->initCartShopSession();
                return array();
            }
        }
        else
            return array();
        
    }
    /**
    * get information of cart list
    * 
    * @param mixed $cartlist
    */
    public function getCartItemsInfo($cartlist)
    {
        
        $songs = "(-1";
        $albums = "(-1";
        $total = 0;
        foreach ($cartlist as $ct)
        {
            if ( $ct['type'] =='song')
            {
               $songs .=",".$ct['item_id'];
            }
            if ( $ct['type'] =='album')
            {
                $albums.=",".$ct['item_id'];
            }
            $total = $total + $ct['amount'];
        }
        $songs .=')';
        $albums .=')';
        phpFox::getLib('phpfox.database')->query('SET character_set_results=utf8 ');   
        $song_list = phpFox::getLib('phpfox.database')->select('so.song_id as item_id,so.title,so.price as amount,"song" as type,u.*')  
                    ->from(phpFox::getT('m2bmusic_album_song'),'so') 
                    ->leftJoin(phpFox::getT('m2bmusic_album'),'al','al.album_id = so.album_id')
                    ->leftJoin(phpFox::getT('user'),'u','al.user_id = u.user_id')
                    ->where('so.song_id IN '.$songs)
                    ->execute('getRows')
                    ;  
        
        $album_list = phpFox::getLib('phpfox.database')->select('al.album_id as item_id,al.title,al.price as amount,"album" as type,u.*')  
                    ->from(phpFox::getT('m2bmusic_album'),'al') 
                    ->leftJoin(phpFox::getT('user'),'u','al.user_id = u.user_id')   
                    ->where('al.album_id IN '.$albums)
                    ->execute('getRows')
                    ;  
        //print_r($album_list);
        //print_r($song_list);
         if ( $_SESSION['musicsharing_cart']['user_id'] == phpFox::getUserId())
         {
            $total =  $_SESSION['musicsharing_cart']['total_amount'];  
            $coupon = $this->getCouponCodeCart();
            $total = $total - $coupon['value']  ;
         }
         else
         {
             $total = 0;
         }
         
         $item_info = array_merge($album_list,$song_list) ;
         return array($total,$item_info);
    }
    /**
    * add item to cart;

    * @param mixed $item
    * $item is array (
    *               'item_id' =>
    *               'type' =>
    *               'owner_id'=>
    *               'amount' =>
    *           )
    */
    public function setCartItem($item)
    {
        if(isset($_SESSION['musicsharing_cart']) && $_SESSION['musicsharing_cart']['user_id'] == phpFox::getUserId())            
        {
          
             $_SESSION['musicsharing_cart']['items'][$item['type'].'_'.$item['item_id']] = $item;
             
        }
        else
        {
            
            $this->initCartShopSession();
            $_SESSION['musicsharing_cart']['items'][$item['type'].'_'.$item['item_id']] = $item;
            
            
        } 
        $this->updateTotalAmount($item['amount'],true)  ;
        return true;        
    }
    /**
    * Clear All Item from cart shop
    * 
    */
    public function clearCart()
    {
        $this->initCartShopSession();
    }
    public function removeCartItem($item_id,$type)
    {
         if(isset($_SESSION['musicsharing_cart']['items']))
        {
            $amount = $_SESSION['musicsharing_cart']['items'][$type.'_'.$item_id]['amount'];
            $this->updateTotalAmount($amount,false);
            unset($_SESSION['musicsharing_cart']['items'][$type.'_'.$item_id]);
            return true;
        }
        return false;
    }
    public function checkExist($item_id,$type)
    {
        if(isset($_SESSION['musicsharing_cart']['items'][$type.'_'.$item_id])
         && $_SESSION['musicsharing_cart']['items'][$type.'_'.$item_id]['item_id']>0)
        {
             return true;
        }
        else
        {
             return false; 
        }
    }
    /**
    * If the items are in downloadlist or in cart. User can add them again.
    * 
    * @param mixed $type
    * @param mixed $user_id
    */
    public function getHiddenCartItem($type,$user_id)
    {
        $query = "";
        if ($type =='song')
        {
            $query = " AND dl_song_id >0";
            $sl = 'dl_song_id';
        }
        else
        {
             $query = " AND dl_album_id >0";        
             $sl = 'dl_album_id';
        }
        $result=phpFox::getLib('phpfox.database')->select($sl)
                    ->from(phpFox::getT('m2bmusic_downloadlist'),'dl')
                    ->where('dl.user_id = '.$user_id.$query)
                    ->execute('getSlaveRows');
        $listHidden = array();
        foreach($result as $res)
        {
            $listHidden[] = $res[$sl];
        }
        if($type =='song')
        {
           $result = phpFox::getLib('phpfox.database')->select('song_id')
                    ->from(phpFox::getT('m2bmusic_album_song'),'alsong')
                    ->join(phpFox::getT('m2bmusic_downloadlist'),'dl','dl.dl_album_id = alsong.album_id')
                    ->where('dl.dl_album_id <> 0 and dl.user_id = '.$user_id)
                    ->execute('getRows');
           
           foreach($result as $res)
           {
              $listHidden[]= $res['song_id'];
           }
          
        }
        
        if( isset($_SESSION['musicsharing_cart']['items']))
        {
            if($_SESSION['musicsharing_cart']['user_id'] ==phpFox::getUserId())
            {
                foreach($_SESSION['musicsharing_cart']['items'] as $ct)
                {
                    if ($ct['type'] == $type)
                    {
                        $listHidden[] = $ct['item_id'];
                    }
                }    
            }
            
        }

        //$user_group_id=phpFox::getService('musicsharing.cart.account')->getValueUserGroupId(phpFox::getUserId());
        //$selling_settings=phpFox::getService("musicsharing.cart.music")->getSettingsSelling($user_group_id);
        //print_r($selling_settings);
        //die();

        $listgroup=phpFox::getLib('phpfox.database')->select('user_group_id')
                ->from(phpFox::getT('user_group'))
                ->execute('getRows');

        foreach($listgroup as $list)
        {
            $user_group_id=$list['user_group_id'];
            $selling_settings=phpFox::getService("musicsharing.cart.music")->getSettingsSelling($user_group_id);
            if($selling_settings['can_sell_song']==0)
            {
                $prefix = phpFox::getParam(array('db', 'prefix'));
                if ($type =='song')
                {
                    $NoSell=phpFox::getLib('phpfox.database')->select('song_id')
                    ->from(phpFox::getT('m2bmusic_album_song'),'song')
                    ->leftJoin(phpFox::getT('m2bmusic_album'),'album','song.album_id=album.album_id')
                    ->where('album.user_id in (select u.user_id from '.$prefix.'user u where u.user_group_id='.$user_group_id.')')
                    ->execute('getRows');
                    foreach($NoSell as $res)
                    {
                        $listHidden[]= $res['song_id'];
                    }
                }
                else
                {
                    $NoSell=phpFox::getLib('phpfox.database')->select('album_id')
                    ->from(phpFox::getT('m2bmusic_album'),'album')
                    ->where('album.user_id in (select u.user_id from '.$prefix.'user u where u.user_group_id='.$user_group_id.')')
                    ->execute('getRows');
                    foreach($NoSell as $res)
                    {
                        $listHidden[]= $res['album_id'];
                    }
                }
                
            }
        }
        
        return $listHidden;
    }
    /**
    * Rebuild the cart.
    * 
    * @param mixed $cartsec
    * @param mixed $hiddencartalbum
    * @param mixed $hiddencartsong
    */
    public function reCheckCart($cartsec,$hiddencartalbum = array(),$hiddencartsong = array())
    {
        //print_r($cartsec);
        //print_r($hiddencartalbum);
         foreach($cartsec as $ct)
         {
            
             if ($ct['type'] == 'song' && in_array($ct['item_id'],$hiddencartsong))
             {
                 $this->removeCartItem($ct['item_id'],'song');
             }
             if ($ct['type'] == 'album' && in_array($ct['item_id'],$hiddencartalbum))
             {
                 $this->removeCartItem($ct['item_id'],'album');
             }
         }
         return $cartsec;
    }
    public function getCart()
    {
        if(isset($_SESSION['musicsharing_cart']) && $_SESSION['musicsharing_cart']['user_id'] == phpFox::getUserId())
        {
              return $_SESSION['musicsharing_cart'];
        }
    }
    /**
    * Create a bill from cart item;
    * 
    * @param mixed $cart
    * @param mixed $receiver
    * @return mixed
    */
    public function makeBillFromCart($cart,$receiver)
    {
         $insert_item = array();
         /*
         list($iCnt,$sender_payment_account) = phpFox::getService('musicsharing.cart.music')->getFinanceAccounts('ni.user_id = '.$this->getCurrentUserCart());
         if ( !isset($sender_payment_account[0]) || @$sender_payment_account[0]['payment_account_id']<=0)
         {
             return -2;
         }
         */
         foreach ($receiver as $re)
         {
             $seliz = serialize($cart);
             list($iCnt,$receiver_account) = phpFox::getService('musicsharing.cart.music')->getFinanceAccounts('ni.account_username = "'.$re['email'].'"');
             if ( !isset($receiver_account[0]) || @$receiver_account[0]['payment_account_id']<=0)
             {
                 return -1;
             }
             $insert_item[] = array($re['invoice'],$_SESSION['payment_sercurity'],$this->getCurrentUserCart(),$sender_payment_account[0]['payment_account_id'],
                                  $re['email'],$receiver_account[0]['payment_account_id'],time(),0,$seliz);
         }
         $result =  phpFox::getLib('phpfox.database')
                ->multiInsert(phpFox::getT('m2bmusic_bills'),
                array('invoice','sercurity','user_id','finance_account_id','emal_receiver','payment_receiver_id','date_bill','bill_status','params'),
                $insert_item
                ) ;
         return 1;
         
    }
    /**
    * Get a bill 
    * 
    * @param mixed $invoice
    * @param mixed $security
    */
    public function getBill($security = null,$invoice = null)
    {
        $con = " 1 = 1 ";
        if ($invoice != null)
        {
            $con .=" AND invoice = '". $invoice."'";
        }
        if ($security != null)
        {
            $con .=" AND sercurity = '". $security."'";
        }
        $con .= " AND bill_status = 0";
        $results = phpFox::getLib('phpfox.database')->select('*')
                   ->from(phpFox::getT('m2bmusic_bills'))
                   ->where($con)
                   ->execute('getRow');
        return  $results;
        
    }
    public function updateBillStatus($bill,$status)
    {
        phpFox::getLib('phpfox.database')->update(
                        phpFox::getT('m2bmusic_bills'),
                        array('bill_status' =>$status),
                        'bill_id  = '.$bill['bill_id'] );
    }
     public function getDownloadList($type,$user_id)
    {
        $query = "";
        if ($type =='song')
        {
            $query = " AND dl_song_id >0";
            $sl = 'dl_song_id';
        }
        else
        {
             $query = " AND dl_album_id >0";        
             $sl = 'dl_album_id';
        }
        $result=phpFox::getLib('phpfox.database')->select($sl)
                    ->from(phpFox::getT('m2bmusic_downloadlist'),'dl')
                    ->where('dl.user_id = '.$user_id.$query)
                    ->execute('getSlaveRows');
        $listHidden = array();
        foreach($result as $res)
        {
            $listHidden[] = $res[$sl];
        }
        return $listHidden;
    }
}
  
?>
