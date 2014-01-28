<?php
if (Phpfox::isMobile()) {
	?>

    <script language="javascript">        
        $Behavior.ynmtSetValueOnJS = function(){
        	ynmtMobileTemplate.langText['mobiletemplate.at_uppercase'] = '<?php echo Phpfox::getPhrase('mobiletemplate.at_uppercase'); ?>';
        };    
    </script>
	
	<?php
}
?>