<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = 'if (Phpfox::isMobile()) {
	?>

    <script language="javascript">        
        $Behavior.ynmtSetFullControllerName = function(){
        	ynmtMobileTemplate.fullControllerName = \'<?php echo Phpfox::getLib(\'module\')->getFullControllerName(); ?>\';
        };    
    </script>
	
	<?php
} '; ?>