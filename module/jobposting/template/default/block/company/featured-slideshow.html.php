<?php
/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');
?>

{literal}
<script type="text/javascript">
var initSlideCompany = '0';
$Behavior.ynjpSlide = function() {
    $(function(){
    	if('0' == initSlideCompany)
    	{    		
    		initSlideCompany = '1';
    		
	        var startSlide = 1;
	
	        $('#ync_slides').slides({
	            preload: true,
	            play: 7000,
	            pause: 2500,
	            hoverPause: true,
	            generatePagination: false,
	            start: startSlide
	        });
    	}    	
    });
};     

    //$Core.loadInit = ynjobposting.overridedLoadInit;
    $Behavior.yncouponOverrideLoadInit = function() {
        $Core.loadInit = ynjobposting.overridedLoadInit;
    }; 

</script>
{/literal}
<div id="ync_slides">
    <div class="slides_container ync_featured_slides_container">
    	{foreach from=$aFeaturedCompany item=item}
			{template file='jobposting.block.company.entry_slider'}   
	    {/foreach} 
		
    </div>
    <a href="#" class="ync-prev prev">{phrase var='jobposting.previous'}</a>
    <a href="#" class="ync-next next">{phrase var='jobposting.next'}</a>
</div>
<div class="clear"></div>