<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

class MusicStore_Component_Block_Form extends Phpfox_Component
{	
	public function process()
	{

        
		    $_SESSION['payment_sercurity'] = phpFox::getService('musicstore.cart.music')->getSecurityCode();
           $is_items_include = 1;
           $method_payment = array('direct'=>'Directly','multi'=>'Multipartite payment');  
           //echo $_SESSION['payment_sercurity'];
           $session_id = $this->getParam('id');
           $cartsec = phpFox::getService('musicstore.cart.shop')->getCartItems();     
           list($total_amount,$cartlist) =  phpFox::getService('musicstore.cart.shop')->getCartItemsInfo($cartsec); 
           $couponcart = phpFox::getService('musicstore.cart.shop')->getCouponCodeCart();
           //$paymentForm =  phpFox::getLib('url')->makeUrl('musicstore.cart');  
           $gateway_name = "paypal";
           $gateway = phpFox::getService('musicstore.cart.music')->loadGateWay($gateway_name);
           $settings = phpFox::getService('musicstore.cart.music')->getSettingsGateWay($gateway_name);
           
           $returnUrl =  phpFox::getLib('url')->makeUrl('musicstore.cart.success',$_SESSION['payment_sercurity']);
           $cancelUrl = phpFox::getLib('url')->makeUrl('musicstore.cart.cancel',$_SESSION['payment_sercurity']);
           $_SESSION['url']['cancel'] = $cancelUrl;
           $_SESSION['url']['success'] = $returnUrl;
           
           $returnUrl = phpFox::getParam('core.path').'module/musicstore/static/redirect.php?pstatus=success&req4='.$_SESSION['payment_sercurity'].'&req5=';
           $cancelUrl = phpFox::getParam('core.path').'module/musicstore/static/redirect.php?pstatus=cancel&req4='.$_SESSION['payment_sercurity'].'&req5=';
           $notifyUrl = phpFox::getParam('core.path').'module/musicstore/static/callback.php?action=callback&req4='.$_SESSION['payment_sercurity'].'&req5=';
           list($receiver,$paramsPay) = phpFox::getService('musicstore.cart.music')->getParamsPay($gateway_name,$returnUrl,$cancelUrl,$method_payment,$notifyUrl);
           $_SESSION['receiver'] = $receiver;
           $method_payment = 'directly';
           $paymentForm = "https://www.sandbox.paypal.com";
           if ($settings['env'] == 'sandbox')
           {
               $paymentForm = "https://www.sandbox.paypal.com";
           }
           else
           {
               $paymentForm = "https://www.paypal.com";
           } 
           $total = phpFox::getService('musicstore.cart.shop')->getTotalAmount();
           $this->template()->assign(array(
                                'paymentForm'=>$paymentForm,
                                'include_items'=>$is_items_include,
                                'method'=>$method_payment,
                                'sercurity'=>$_SESSION['payment_sercurity'],
                                'is_popup_cart'=>1,
                                'core_path'=>phpFox::getParam('core.path'),
                                'cartlist' =>$cartlist, 
                                'total_amount' =>$total_amount,
                                'total' =>$total,
                                'couponcart' =>$couponcart,
                                'currency' => phpFox::getService('core.currency')->getDefault(),
                                'paramPay'  =>$paramsPay,
                                'receiver' => $receiver[0],
                                 
                            ));
		return 'content';
	}

}

?>