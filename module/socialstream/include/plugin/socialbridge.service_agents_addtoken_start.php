<?php
    defined('PHPFOX') or exit('NO DICE!');
    
    if(isset($aInsert) && is_array($aInsert))
    {      
      $aInsert['privacy'] = 3;	
      switch(Phpfox::getParam('socialstream.default_privacy'))
      {
          case "Everyone":
               $aInsert['privacy'] = 0;
              break;
          case "Friends":
               $aInsert['privacy'] = 1;
              break;
          case "Friends of Friends":
               $aInsert['privacy'] = 2;
              break;			
          default:				
               $aInsert['privacy'] = 3;				
      }
    }
?>