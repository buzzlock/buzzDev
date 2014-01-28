<?php
defined('PHPFOX') or exit('NO DICE!');   

class Musicsharing_Service_Cart_coupon extends Phpfox_Service  
{    
    /**
    * Auto generate Coupon Codes
    * 
    */
    public function generateCode()
    {
        return phpFox::getService('musicsharing.cart.music')->getSecurityCode();
    }
    /**
    * Add new Coupon Code
    * 
    * @param mixed $coupon
    */
    public function addNewCoupon($coupon)
    {
        return phpFox::getLib('phpfox.database')->insert(
                phpFox::getT('m2bmusic_coupon'),$coupon
            );
    }
    /**
    * Delete coupon by coupon_ids
    * 
    * @param mixed $coupon_id
    */
    public function deleteCoupon($coupon_id)
    {
       return phpFox::getLib('phpfox.database')->delete(
                phpFox::getT('m2bmusic_coupon'),'coupon_id = '.$coupon_id
            );
    }
    /**
    * Update coupon codes 
    * 
    * @param mixed $coupon
    */
    public function updateCoupon($coupon)
    {
        return phpFox::getLib('phpfox.database')->update(
                phpFox::getT('m2bmusic_coupon'),$coupon,'coupon_id = '.$coupon['coupon_id']
            );
    }
    /**
    * Get list of coupon
    * 
    * @param mixed $aConds
    * @param mixed $sSort
    * @param mixed $iPage
    * @param mixed $sLimit
    * @param mixed $bCount
    */
    public function getCoupons($aConds = array(),$sSort = 'coupon_id DESC', $iPage = '', $sLimit = '', $bCount = true)
    {
         
         phpFox::getLib('phpfox.database')->query('SET character_set_results=utf8 ');    
         $iCnt = ($bCount ? 0 : 1);
         $items = array();
         if ($bCount )
         { 
             $iCnt = phpFox::getLib('phpfox.database')->select('COUNT(*)')
                    ->from(phpFox::getT('m2bmusic_coupon'),'cou')
                    ->where($aConds)
                    ->execute('getField');
         }
         if ($iCnt)
         {
            $items = phpFox::getLib('phpfox.database')->select('*,DATE( FROM_UNIXTIME(start_date) ) AS sDate,DATE( FROM_UNIXTIME(end_date) ) AS tDate')
                    ->from(phpFox::getT('m2bmusic_coupon'),'cou')
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
}
  
?>
