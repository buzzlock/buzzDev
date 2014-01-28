<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = 'if (Phpfox::isMobile()) {
	?>

    <script language="javascript">        
        $Behavior.ynmtRemoveSortFeed = function(){
        	if($(\'#ym-open-right\').length){
		      $(\'#ym-open-right\').hide();  		
		      $(\'.feed_sort_order\').hide();
        	}
        };    
    </script>
	
	<?php
} '; ?>