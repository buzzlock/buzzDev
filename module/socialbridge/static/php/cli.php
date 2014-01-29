<?php
/**
 * Key to include phpFox
 *
 */
define('PHPFOX', true);


if (defined('DEBUG') && DEBUG)
{
	ini_set('display_startup_errors', 1);
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
}

ob_start();
/**
 * Directory Seperator
 *
 */
define('PHPFOX_DS', DIRECTORY_SEPARATOR);

/**
 * phpFox Root Directory
 *
 */
define('PHPFOX_DIR', dirname(dirname(dirname(dirname(dirname(__FILE__))))) . PHPFOX_DS);
// Require phpFox Init

include PHPFOX_DIR .PHPFOX_DS.'include'.PHPFOX_DS.'init.inc.php';


function processRedirectAndExit($sService, $bRedirect, $sUrlRedirect,$sConnected = NULL, $bRedirectLoop = false){
	
	$iUserId  = Phpfox::getUserId();
	
	if($iUserId)
	{
		(($sPlugin = Phpfox_Plugin::get('socialbridge.service_agents_addtoken_end'))?eval($sPlugin):'');
	}
	
	
	
	if ($bRedirect == 1):?>
        <script type="text/javascript">
            var openerurl = '<?php echo $sUrlRedirect; ?>';
            
            var sRedirectLoopMsg = '<?php echo Phpfox::getPhrase('socialbridge.redirect_loop_msg'); ?>';
            var bRedirectLoop = '<?php echo $bRedirectLoop?'1':'0'; ?>';
            if(bRedirectLoop == '1'){
            	alert(sRedirectLoopMsg);
            }
            
            if(opener == null || opener == undefined)
            {
                window.location.href = openerurl;
            }else
            {
                  opener.location = openerurl;
                  self.close();
            }

        </script>
    <?php else: ?>
        <script type="text/javascript">
            var openerurl = '<?php echo $sUrlRedirect; ?>';
            
            var sRedirectLoopMsg = '<?php echo Phpfox::getPhrase('socialbridge.redirect_loop_msg'); ?>';
            var bRedirectLoop = '<?php echo $bRedirectLoop?'1':'0'; ?>';
            if(bRedirectLoop == '1'){
            	alert(sRedirectLoopMsg);
            }
            
            if(opener == null || opener == undefined)
            {
                window.location.href = openerurl;
            }
            else if(opener.location.href == openerurl)
            {
                opener.location = openerurl;
                self.close();
            }
            else if(opener.document.getElementById('showpopup_span_connected_<?php echo $sService; ?>'))
            {
                opener.document.getElementById('showpopup_span_connected_<?php echo $sService; ?>').innerHTML ="<?php echo $sConnected; ?>";
                opener.document.getElementById('showpopup_checkbox_connected_<?php echo $sService; ?>').checked =true;
                opener.document.getElementById('showpopup_checkbox_connected_<?php echo $sService; ?>').removeAttribute("onclick");
                self.close();
            }else{
            	self.close();
            }
        </script>
    <?php
    endif;
	exit;
}
?>