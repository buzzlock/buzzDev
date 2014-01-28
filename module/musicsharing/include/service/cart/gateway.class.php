<?php
defined('PHPFOX') or exit('NO DICE!');   
$pathfile = PHPFOX_DIR_MODULE.'musicsharing'.PHPFOX_DS.'include'.PHPFOX_DS.'service'.PHPFOX_DS.'m2b_music_lib'.PHPFOX_DS.'cart'.PHPFOX_DS.'gateway.php';

if(file_exists($pathfile))      
{
    require_once $pathfile;
}

class Musicsharing_Service_Cart_gateway extends Phpfox_Service  
{ 
     /**
     * Save setting gateway. Update info if it exists
     * 
     * @param mixed $gateway_name
     * @param mixed $params
     */
     public function saveSettingGateway($gateway_name ='paypal',$params = array()) 
     {
         $gateway = $this->getSettingGateway($gateway_name);
         
         if ( $gateway != null)
         {
             if(isset($params['is_from_finance']) && $params['is_from_finance'] == 1)
             {
                 $gateway['admin_account'] = $params['admin_account'];
                 
             }
             else
             {
                
                 $gateway['admin_account'] = $params['admin_account'];
                 $gateway['is_active'] = $params['is_active'];
                 $gateway['params'] = serialize($params['params']) ;
           
                 
             }
             
             phpFox::getLib('phpfox.database')->update(phpFox::getT('m2bmusic_gateway'),
                                                 $gateway,
                                                 'gateway_name = "'.$gateway_name.'"'                   
                                            );
             
         }
         else
         {
             if(isset($params['is_from_finance']) && $params['is_from_finance'] == 1)
             {
                 //$params['admin_account'] = $params['admin_account'];
                unset($params['is_from_finance']) ;
             }
             $params['gateway_name'] = $gateway_name;
             $params['params'] = serialize($params['params']);
             
             phpFox::getLib('phpfox.database')->insert(phpFox::getT('m2bmusic_gateway'),
                                           $params     
                                        );
             
           
         }
             
     }
     /**
     * Get setting of gateway 
     * 
     * @param mixed $gateway_name
     */
     public function getSettingGateway($gateway_name = 'paypal')   
     {
         $result = phpFox::getLib('phpfox.database')->select('*')
                ->from(phpFox::getT('m2bmusic_gateway'))
                ->where('gateway_name = "'.$gateway_name.'"')
                ->execute('getRow');
         return $result;
     }
     
} 
  
?>
