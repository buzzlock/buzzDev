<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class musicsharing_component_controller_addaccount extends Phpfox_Component
{
    public function process()
    {
         $this->template()->setHeader(array(
                'm2bmusic_tabcontent.js' => 'module_musicsharing' ,
                'm2bmusic_class.js' => 'module_musicsharing' ,
                'music.css' => 'module_musicsharing'
       ));

          $is_account=phpFox::getService("musicsharing.cart.account")->getCurrentInfo(phpFox::getUserId());
          if($is_account['account_username']!=null)
              $result=1;
          if(isset($_POST['submit']))
          {
              $aVals = $this->request()->getArray('val');
              $this->template()->assign(array('info'=>$aVals));
              if(trim($aVals['account_username']) == "")
              {
                    return Phpfox_Error::set('Please enter finance username!');
              }
              else if($aVals['account_username'] != "")
              {
                  $email = $aVals['account_username'];
                    $regexp = "/^[A-z0-9_]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/";
                    if(!preg_match($regexp, $email))
                    {
                        $is_validate=1;
                        return Phpfox_Error::set('user account is not valid!');
                    }
              }
              else if(trim($aVals['password']==""))
              {
                    return Phpfox_Error::set('Please enter password!');
              }
              else if(trim($aVals['retype_password']==""))
              {
                  return Phpfox_Error::set('Please enter retype-password');
              }
              if(strcmp($aVals['password'], $aVals['retype_password'])!=0)
              {
                  return Phpfox_Error::set('Password and Retype-Password no match');
              }
              $hash = 'admin';
              $aVals['password']=$aVals['retype_password']=phpFox::getLib('hash')->setHash($aVals['password'], $hash);
              $result=phpFox::getService("musicsharing.cart.account")->insertAccount($aVals);
              
          }
          $this->template()->setBreadCrumb(phpFox::getPhrase('musicsharing.music_sharing'),null);
          $this->template()->assign(array('result'=>$result));
    }
}
?>
