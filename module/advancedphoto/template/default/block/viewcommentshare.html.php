{if !phpfox::isMobile()}
<script tyle="text/javascript">
 {literal}
$Behavior.advancedphotoview = function(){
        var fadeTTime = 0.00001;
        $("#yn_show_mostview").click(function(evt){
                evt.preventDefault();
                $("#yn_mostview").stop(false,false).fadeIn(fadeTTime, function(){
                        $("#yn_mostcomment").fadeOut(fadeTTime);
                        $("#yn_mostlike").fadeOut(fadeTTime);
                        
                });
                $("#yn_show_mostcomment").find(".active").removeClass("active");
                $("#yn_show_mostlike").find(".active").removeClass("active");
                $(this).parent().addClass("active");
                return false;
        });
        $("#yn_show_mostcomment").click(function(evt){
                evt.preventDefault();
                $("#yn_mostcomment").stop(false, false).fadeIn(fadeTTime, function(){
                        $("#yn_mostview").fadeOut(fadeTTime);
                        $("#yn_mostlike").fadeOut(fadeTTime);
                });
                $("#yn_show_mostview").find(".active").removeClass("active");
                $("#yn_show_mostlike").find(".active").removeClass("active");
                $(this).parent().addClass("active");
                return false;
        });
		$("#yn_show_mostlike").click(function(evt){
                evt.preventDefault();
                $("#yn_mostlike").stop(false, false).fadeIn(fadeTTime, function(){
                        $("#yn_mostview").fadeOut(fadeTTime);
                        $("#yn_mostcomment").fadeOut(fadeTTime);
                });
                $("#yn_show_mostcomment").find(".active").removeClass("active");
                $("#yn_show_mostview").find(".active").removeClass("active");
                $(this).parent().addClass("active");
                return false;
        });
        $("#yn_mostcomment").hide();
        $("#yn_mostlike").hide();
        $("#yn_show_mostview").trigger('click');


};
        {/literal}
</script>

<div class="menu">
    <ul id="advancedphoto_tab">
        <li class="first active "><a id="yn_show_mostview" href="#"><span>{phrase var='advancedphoto.most_viewed_photos'}</span></a></li> 
        <li class=""><a id="yn_show_mostcomment" href="#"><span>{phrase var='advancedphoto.most_commented_photos'}</span></a></li>
        <li class=" last"><a id="yn_show_mostlike" href="#"><span>{phrase var='advancedphoto.most_liked_photos'}</span></a></li> 
    </ul>
    <div class="clear"></div>
</div> 
<div class="clear"></div>

<!-- Content -->
<!-- Most Viewed -->

<ul class="clear-float viewimage" id="yn_mostview">
	{foreach from=$aMostViewedPhotos item=aPhoto}
		{template file='advancedphoto.block.small-photo'}
	{/foreach}
	<div class="clear"></div>
	<p class="advancedphoto-viewmore">
		<a href="{$sViewAllMostViewedLink}"> {phrase var='advancedphoto.view_more'} </a>
	</p>
</ul>

<!-- Most Commented -->
<ul id="yn_mostcomment" class="clear-float viewimage">
	{foreach from=$aMostCommentedPhotos item=aPhoto}
		{template file='advancedphoto.block.small-photo'}
	{/foreach}
	<div class="clear"></div>
	<p class="advancedphoto-viewmore">
		<a href="{$sViewAllMostCommentedLink}"> {phrase var='advancedphoto.view_more'} </a>
	</p>
</ul>

<!-- Most Shared -->
<ul id="yn_mostlike" class="clear-float viewimage">
	{foreach from=$aMostLikedPhotos item=aPhoto}
		{template file='advancedphoto.block.small-photo'}
	{/foreach}
	<div class="clear"></div>
	<p class="advancedphoto-viewmore">
		<a href="{$sViewAllMostLikedLink}"> {phrase var='advancedphoto.view_more'} </a>
	</p>
</ul>
{else}
	<div class="block">
		<div class="title">{phrase var='advancedphoto.most_viewed_photos'}</div>
		<div class="content">
			{foreach from=$aMostViewedPhotos item=aPhoto name=mostP}
			{if $phpfox.iteration.mostP < 9}
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