{if !phpfox::isMobile()}
<script tyle="text/javascript">
 {literal}
$Behavior.advancedphotonew = function(){
        var fadeTTime = 0.00001;
        $("#yn_show_new").click(function(evt){
                evt.preventDefault();
                $("#yn_new").stop(false,false).fadeIn(fadeTTime, function(){
                        $("#yn_random").fadeOut(fadeTTime);
                        $("#yn_mostshare").fadeOut(fadeTTime);
                        
                });
                $("#yn_show_random").find(".active").removeClass("active");
                $(this).parent().addClass("active");
                return false;
        });
        $("#yn_show_random").click(function(evt){
                evt.preventDefault();
                $("#yn_random").stop(false, false).fadeIn(fadeTTime, function(){
                        $("#yn_new").fadeOut(fadeTTime);
                });
                $("#yn_show_new").find(".active").removeClass("active");
                $(this).parent().addClass("active");
                return false;
        });
        $("#yn_random").hide();
		
		$("#yn_show_new").trigger('click');
};


        {/literal}
</script>

<div class="menu">
    <ul id="advancedphoto_tab">
        <li class="first active "><a id="yn_show_new" href="#"><span>{phrase var='advancedphoto.new_photos'}</span></a></li> 
        <li class=""><a id="yn_show_random" href="#"><span>{phrase var='advancedphoto.random_photos'}</span></a></li>
      </ul>
    <div class="clear"></div>
</div> 
<div class="clear"></div>

<!-- Content -->

<!-- Newest Photos -->
<ul class="clear-float viewimage" id="yn_new">
	{foreach from=$aNewestPhotos item=aPhoto}
		{template file='advancedphoto.block.small-photo'}
	{/foreach}
	<div class="clear"></div>
	<p class="advancedphoto-viewmore">
		<a href="{$sViewAllRecentLink}"> {phrase var='advancedphoto.view_more'} </a>
	</p>
</ul>

<!-- Random Photos -->
<ul id="yn_random" class="clear-float viewimage">
	{foreach from=$aRandomPhotos item=aPhoto}
		{template file='advancedphoto.block.small-photo'}
	{/foreach}
	<div class="clear"></div>
	<p class="advancedphoto-viewmore">
		<a href="#" onclick="$.ajaxCall('advancedphoto.reloadRandom'); return false;"> {phrase var='advancedphoto.refresh'} </a>
	</p>
</ul>
{else}
	<div class="block">
		<div class="title">{phrase var='advancedphoto.new_photos'}</div>
		<div class="content">
			{foreach from=$aNewestPhotos item=aPhoto name=newP }
				{if $phpfox.iteration.newP < 9}
				{template file='advancedphoto.block.mobile-detail'}
				{/if}
			{/foreach}
			<div class="clear"></div>
			<div class="clear"></div>
			<p class="advancedphoto-viewmore">
				<a href="{$sViewAllRecentLink}"> {phrase var='advancedphoto.view_more'} </a>
			</p>
		</div>
	</div>
{/if}

<div class="clear"></div>
