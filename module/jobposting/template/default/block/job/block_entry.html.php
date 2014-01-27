<div class="ync-item-content">
	<!-- Image content -->
	<div class="ynjp-image-blockCol">
        <a title="Postcards" href="{permalink module='jobposting' id=$item.job_id title=$item.title}">
			{img server_id=$item.image_server_id path='core.url_pic' file="jobposting/".$item.image_path suffix='_50' max_width='50' max_height='50' class='js_mp_fix_width'} 
		</a>
    </div>
	<!-- Information content -->
	<div class="ync_title_info">
	    <p class="ync-title" id="js_coupon_edit_title50">
	        <strong><a class="link ajax_link" id="js_coupon_edit_inner_title50" href="{permalink module='jobposting' id=$item.job_id title=$item.title}">{$item.title|clean|shorten:35:'...'|split:50}</a></strong>
	    </p>
	    <div class="extra_info">	       
	        <p>{phrase var='jobposting.company'}: <a href="{permalink module='jobposting.company' id=$item.company_id title=$item.name}">{$item.name|clean|shorten:35:'...'|split:50}</a></p>
	        <p>{phrase var='jobposting.posted'}: {$item.time_stamp_phrase}</p>
	    </div>
	</div>
	<div class="clear"></div>
</div>

