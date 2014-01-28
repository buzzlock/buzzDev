<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<?php
defined('PHPFOX') or exit('NO DICE!');
?>


<div class="table">
                
                  <div class="table_left" style="font-weight: bold;">{phrase var='musicsharing.amount'}</div>
                      <div class="table_right" style="margin-bottom: 10px;">
                     <input type="text" name="txtrequest_money" id="txtrequest_money"/>
                        </div>
                  <div class="table_left" style="font-weight: bold;">{phrase var='musicsharing.reason'}</div>
                  <div class="table_right"><textarea  name="textarea_request" id="textarea_request" style="height:60px; width: 250px;"></textarea></div>
                  </div>
                <div class="table_clear">
                 <input type="submit" name="submit" value="Request money" onclick="$.ajaxCall('musicsharing.requestmoney', 'currentmoney=' + $('#txtrequest_money').val()+'&reason='+$('#textarea_request').val());" class="button"/>
                 </div>
