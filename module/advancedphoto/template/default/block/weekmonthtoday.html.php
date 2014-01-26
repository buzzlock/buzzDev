{if !phpfox::isMobile()}
<script tyle="text/javascript">
 {literal}
$Behavior.advancedphotodate = function(){
        var fadeTTime = 0.00001;
		$("#yn_show_month").click(function(evt){
                evt.preventDefault();
                $("#yn_month").stop(false, false).fadeIn(fadeTTime, function(){
                     setTimeout(function() {
                        $("#yn_week").fadeOut(fadeTTime);
                        $("#yn_today").fadeOut(fadeTTime);
                     }, 1);
                });
                $("#yn_show_week").find(".active").removeClass("active");
                $("#yn_show_today").find(".active").removeClass("active");
                $(this).parent().addClass("active");
                return false;
        });
        $("#yn_show_week").click(function(evt){
                evt.preventDefault();
                $("#yn_week").stop(false,false).fadeIn(fadeTTime, function(){
                     setTimeout(function() {
                        $("#yn_month").fadeOut(fadeTTime);
                        $("#yn_today").fadeOut(fadeTTime);
                     }, 1);
                        
                });
                $("#yn_show_month").find(".active").removeClass("active");
                $("#yn_show_today").find(".active").removeClass("active");
                $(this).parent().addClass("active");
                return false;
        });
        
		$("#yn_show_today").click(function(evt){
                evt.preventDefault();
                $("#yn_today").stop(false, false).fadeIn(fadeTTime, function(){
                    setTimeout(function() {
                        $("#yn_month").fadeOut(fadeTTime);
                        $("#yn_week").fadeOut(fadeTTime);
                    }, 1);
                });
                $("#yn_show_month").find(".active").removeClass("active");
                $("#yn_show_week").find(".active").removeClass("active");
                $(this).parent().addClass("active");
                return false;
        });
        $("#yn_week").hide();
        $("#yn_today").hide();
		$("#yn_show_month").trigger('click');

};
        {/literal}
</script>

<div class="menu">
    <ul id="advancedphoto_tab">
		<li class="first active"><a id="yn_show_month" href="#"><span>{phrase var='advancedphoto.this_month_photos'}</span></a></li>
        <li class=""><a id="yn_show_week" href="#"><span>{phrase var='advancedphoto.this_week_photos'}</span></a></li> 
        <li class=" last"><a id="yn_show_today" href="#"><span>{phrase var='advancedphoto.today_photos'}</span></a></li> 
    </ul>
    <div class="clear"></div>
</div> 
<div class="clear"></div>

<!-- Content -->


<!-- Most Commented -->
<ul id="yn_month" class="clear-float viewimage">
	{foreach from=$aThisMonthTops item=aPhoto}
		{template file='advancedphoto.block.small-photo'}
	{/foreach}
	<div class="clear"></div>
	<p class="advancedphoto-viewmore">
		<a href="{$sViewAllThisMonthLink}"> {phrase var='advancedphoto.view_more'} </a>
	</p>
</ul>
<!-- Most Viewed -->

<ul  id="yn_week" class="clear-float viewimage">
	{foreach from=$aThisWeekTops item=aPhoto}
		{template file='advancedphoto.block.small-photo'}
	{/foreach}
	<div class="clear"></div>
	<p class="advancedphoto-viewmore">
		<a href="{$sViewAllThisWeekLink}"> {phrase var='advancedphoto.view_more'} </a>
	</p>
</ul>
<!-- Most Shared -->
<ul id="yn_today" class="clear-float viewimage">
	{foreach from=$aTodayTops item=aPhoto}
		{template file='advancedphoto.block.small-photo'}
	{/foreach}
	<div class="clear"></div>
	<p  class="advancedphoto-viewmore">
		<a href="{$sViewAllTodayLink}"> {phrase var='advancedphoto.view_more'} </a>
	</p>
</ul>

{else}
	<div class="block">
		<div class="title">{phrase var='advancedphoto.this_week_photos'}</div>
		<div class="content">
			{foreach from=$aThisWeekTops item=aPhoto name=weekP }
			{if $phpfox.iteration.weekP < 9}
				{template file='advancedphoto.block.mobile-detail'}
			{/if}
			{/foreach}
			<div class="clear"></div>
			<div class="clear"></div>
			<p class="advancedphoto-viewmore">
				<a href="{$sViewAllMostViewedLink}"> {phrase var='advancedphoto.view_more'} </a>
			</p>
		</div>
	</div>
{/if}
<div class="clear"></div>
