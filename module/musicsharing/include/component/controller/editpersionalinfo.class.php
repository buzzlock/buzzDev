<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class musicsharing_component_controller_editpersionalinfo extends Phpfox_Component
{
    public function process()
    {
          $this->template()->setHeader(array(
                'm2bmusic_tabcontent.js' => 'module_musicsharing' ,
                'm2bmusic_class.js' => 'module_musicsharing' ,
                'music.css' => 'module_musicsharing'
       ));
        $this->template()->setBreadCrumb(phpFox::getPhrase('musicsharing.music_sharing'),null);  
          if(isset($_POST['submit']))
          {
              $aVals = $this->request()->getArray('val');
              $this->template()->assign(array('info'=>$aVals));
              $is_validate=0;
              $is_email=0;
              if(trim($aVals['full_name']) == "")
              {
                    $is_validate=1;
                    Phpfox_Error::set(Phpfox::getPhrase('musicsharing.please_enter_full_name'));
              }
              if(trim($aVals['email']==""))
              {
                    $is_validate=1;
                    $is_email=1;
                    Phpfox_Error::set(Phpfox::getPhrase('musicsharing.please_enter_email'));
              }
              if(trim($aVals['account_username']==""))
              {
                    $is_validate=1;
                    Phpfox_Error::set(Phpfox::getPhrase('musicsharing.please_enter_finance_account'));
              }
              if($is_email==0)
              {
                    $email = $aVals['email'];
                    $regexp = "/^[A-z0-9_]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/";
                    if(!preg_match($regexp, $email))
                    {
                        $is_validate=1;
                        Phpfox_Error::set(Phpfox::getPhrase('musicsharing.email_address_is_not_valid'));
                    }
                    $email = $aVals['account_username'];
                    $regexp = "/^[A-z0-9_]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/";
                    if(!preg_match($regexp, $email))
                    {
                        $is_validate=1;
                        Phpfox_Error::set(Phpfox::getPhrase('musicsharing.user_account_email_is_not_valid'));
                    }
              }
              if($is_validate==0)
              {
                  $result=phpFox::getService("musicsharing.cart.account")->updateinfo($aVals);
                  phpFox::getService("musicsharing.cart.account")->updateusername_account(phpFox::getUserId(),$aVals['account_username']);
                  $info_account=phpFox::getService("musicsharing.cart.account")->getCurrentAccount(phpFox::getUserId());
                  if($info_account != null)
                  {
                        if($info_account['payment_type'] == 1)
                        {
                            $params['admin_account'] = $aVals['account_username'];
                            $params['is_from_finance'] = 1;
                            phpFox::getService('musicsharing.cart.gateway')->saveSettingGateway('paypal',$params);   
                        }    
                  }
                  
              }
              else
                  return;
          }

          $info=phpFox::getService("musicsharing.cart.account")->getCurrentInfo(phpFox::getUserId());
          $this->template()->assign(array('info'=>$info));
          
          $this->template()->assign(array('result'=>$result));
    }
}
?>
