<?php

defined('PHPFOX') or exit('NO DICE!');

if (Phpfox::isModule('profilepopup') && !Phpfox::isMobile() && Phpfox::getUserParam('profilepopup.can_view_profile_popup'))
{
        $aRet = Phpfox::getService('profilepopup')->initThemeTemplateBodyPlugin();

        echo " <script type=\"text/javascript\"> 
        var iOpeningDelayTime = " . $aRet['iOpeningDelayTime'] . "; 
        var iClosingDelayTime = " . $aRet['iClosingDelayTime'] . "; 
        var sEnableCache = " . $aRet['sEnableCache'] . "; 
         </script> ";
		 
	?>

    <script language="javascript">        
        $Behavior.ynppSetDataForYnfbpp = function(){
        	ynfbpp.rewriteData = $.parseJSON('<?php echo $aRet['rewriteData']; ?>');
        };    
    </script>
	
	<?php
		 
}
?>



