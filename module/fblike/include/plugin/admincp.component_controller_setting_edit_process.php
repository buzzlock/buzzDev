<?php
  
  if(isset($sProductId) && $sProductId === 'fblike')
  {    
    $bIsEnable = Phpfox::getParam('fblike.is_enable');
    $iId = phpfox::getLib('database')->select('block_id')
                      ->from(phpfox::getT('block'))
                      ->where('product_id LIKE "fblike" AND module_id LIKE "fblike"')
                      ->limit(1)
                      ->execute('getSlaveField');
   
    if(!$bIsEnable)
    {
       Phpfox::getService('admincp.block.process')->updateActivity($iId, 0);
    }
    elseif($bIsEnable)
    {
       Phpfox::getService('admincp.block.process')->updateActivity($iId, 1);
    }   
  }  
?>
