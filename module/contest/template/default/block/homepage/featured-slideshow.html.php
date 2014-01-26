{if count($aFeaturedContests)>0}
	<div class="wrap_slider">
		<div id="slider_featured" class="flexslider">
			<ul class="slides">
				{foreach from=$aFeaturedContests item=aContest}
					<li class="ele_relative">
						<span class="entype {$aContest.style_type}"></span> <!-- enblog // enphoto // envideo // enmusic -->
						<a class="title" href="{permalink module='contest' id=$aContest.contest_id title=$aContest.contest_name}">{$aContest.contest_name|clean|shorten:20:'...'|split:20}</a>
						{img path='core.url_pic' file="contest/".$aContest.image_path class='js_mp_fix_width'}
						<div class="slider_des">
							<div class="slider_info">
								<p class="extra_info">
									{phrase var='contest.created_by'} {$aContest|user}
								</p>
								<p class="extra_info">
									{phrase var='contest.end_contest_on'}: {$aContest.end_time_parsed}
								</p>
							</div>
							<ul class="slider_date">
								<li>
									<span>{phrase var='contest.entries'}</span> 
									<b>{$aContest.total_entry}</b>
								</li>
								<li>
									<span>{phrase var='contest.participants'}</span>
									<b>{$aContest.total_participant}</b>
								</li>
								<li class="yc_last">
									<span>{phrase var='contest.submit_entries'}</span>
									<b>
										{if $aContest.submit_timeline == 'opening'}
                                            {phrase var='contest.opening'}
                                        {elseif $aContest.submit_timeline == 'on_going'}
                                            {$aContest.submit_countdown}
                                        {elseif $aContest.submit_timeline == 'end'}
                                            {phrase var='contest.end'}
                                        {/if}
									</b>
								</li>
							</ul>
						</div>
					</li>
				{/foreach}
			</ul>
		</div>
		<div id="carousel_featured" class="flexslider">
		  <ul class="slides">
			{foreach from=$aFeaturedContests item=aContest}
				<li class="ele_relative">
					<span></span>
					{img path='core.url_pic' file="contest/".$aContest.image_path suffix='_50' width='50' height='40' class='js_mp_fix_width'}
				</li>
			{/foreach}
		  </ul>
		</div>
	</div>

<script style="text/javascript">
$Behavior.initSlideshow = function() {l}
    $('#carousel_featured').flexslider({l}
        animation: "slide",
        controlNav: false,
        animationLoop: false,
        slideshow: false,
        itemWidth: 55,
        itemMargin: 5,
        asNavFor: '#slider_featured'
    {r});
    
    $('#slider_featured').flexslider({l}
        animation: "slide",
        controlNav: false,
        animationLoop: {if $bIsAutorun}true{else}false{/if},
        slideshow: {if $bIsAutorun}true{else}false{/if},
        slideshowSpeed: {$iSpeed},
        sync: "#carousel_featured",
        start: function(slider){l}
            $('body').removeClass('loading');
        {r}
    {r});
{r}
</script> 

{/if}