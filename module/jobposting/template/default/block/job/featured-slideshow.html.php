<?php
/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');
?>

{literal}
<script type="text/javascript">
var initSlideJob = '0';
$Behavior.ynjpSlideJob = function() {
    $(function(){
    	if('0' == initSlideJob)
    	{    		
    		initSlideJob = '1';
    		
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

{if count($aFeaturedJobs)>0}
	<div id="ync_slides">
		<div class="slides_container ync_featured_slides_container">
		{foreach from=$aFeaturedJobs item=item}
			{template file='jobposting.block.job.entry_slider'}
	    {/foreach}  
	      </div> 
	    <a href="#" class="ync-prev prev">{phrase var='jobposting.previous'}</a>
	    <a href="#" class="ync-next next">{phrase var='jobposting.next'}</a>
	</div>
<div class="clear"></div>
{/if}
