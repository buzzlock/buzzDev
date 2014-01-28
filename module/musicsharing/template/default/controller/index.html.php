<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');
?>
{if Phpfox::isMobile()}
    {literal}
        <style type="text/css">
            .mobile_section_menu{display: none !important;}
            #section_menu{display:none !important;}
        </style>
    {/literal}
{/if}
{template file='musicsharing.block.mexpect'}

<style type="text/css">
    .pic_album .overlay
    {ldelim}
         background: url("{$core_path}/module/musicsharing/static/image/m_size.png") no-repeat scroll 0 0 transparent;
    {rdelim}

</style>
<script language="javascript" type="text/javascript">
	var _js_newpl_home = false;
	var _js_toppl_home = false;
	var _js_topsong_home = false;
	var _js_newsong_home = false;
$Behavior.MusicSharingIndex = function() {ldelim}
	$(document).ready(function(){ldelim}
		$(".header_bar_drop").find("span:contains(Sort:)").html("Search by:");
		$("div.header_bar_menu").css({ldelim}
			"visibility": "visible"
		{rdelim});
		$(".header_bar_menu").find(".header_bar_drop_holder").each(function(index){ldelim}
			if(index > 0){ldelim}
				$(this).find(":hidden").remove();
				$(this).find("a").css({ldelim}
					"color": "#8F8F8F"
				{rdelim});
			{rdelim}
		{rdelim});
	{rdelim});
{rdelim}
	
	$Behavior.initMusicSharingFakeLoader = function(){ldelim}
		setTimeout(function(){l}
			$(".download-icon").unbind();
		{r}, 200);
		$("#js_block_border_musicsharing_newplaylists").find("li.first").children("a").click(function(){ldelim}
			var $this = $(this);
			$(".js_block_click_lis_cache").css({ldelim}
				"position": "absolute",
				"right": "280px",
				"width": "auto",
				"text-align": "right",
				"top": "10px"
			{rdelim}).parent().parent().css({ldelim}
				"position": "relative"
			{rdelim});
			
			if(_js_newpl_home){ldelim}
				$('#js_newtopplaylist').html(_js_newpl_home);
				$this.attr({ldelim}
					"href": "#"
				{rdelim});
			{rdelim}
			$this.unbind();

			$this.click(function(evt){ldelim}
				evt.preventDefault();
				$('#js_newtopplaylist').html(_js_newpl_home);
				$ul = $(this).parent().parent();
				$ul.find(".active").removeClass("active");
				$ul = $(this).parent().addClass("active");
				return false;
			{rdelim});
		{rdelim});
		$("#js_block_border_musicsharing_newplaylists").find("li.last").children("a").click(function(){ldelim}
			var $this = $(this);
			$(".js_block_click_lis_cache").css({ldelim}
				"position": "absolute",
				"right": "15px",
				"width": "auto",
				"text-align": "right",
				"top": "10px"
			{rdelim}).parent().parent().css({ldelim}
				"position": "relative"
			{rdelim});
			
			if(_js_toppl_home){ldelim}
				$('#js_newtopplaylist').html(_js_toppl_home);
				$this.attr({ldelim}
					"href": "#"
				{rdelim});
			{rdelim}
			$this.unbind();

			$this.click(function(evt){ldelim}
				evt.preventDefault();
				$('#js_newtopplaylist').html(_js_toppl_home);
				$ul = $(this).parent().parent();
				$ul.find(".active").removeClass("active");
				$ul = $(this).parent().addClass("active");
				return false;
			{rdelim});
		{rdelim});

		$("#js_block_border_musicsharing_newsongs-front-end").find("li.first").children("a").click(function(){ldelim}
			var $this = $(this);
			$(".js_block_click_lis_cache").css({ldelim}
				"position": "absolute",
				"right": "280px",
				"width": "auto",
				"text-align": "right",
				"top": "10px"
			{rdelim}).parent().parent().css({ldelim}
				"position": "relative"
			{rdelim});
			
			if(_js_newsong_home){ldelim}
				$('#song_list_frame').html(_js_newsong_home);
				$this.attr({ldelim}
					"href": "#"
				{rdelim});
			{rdelim}
			$this.unbind();

			$this.click(function(evt){ldelim}
				evt.preventDefault();
				$('#song_list_frame').html(_js_newsong_home);
				$ul = $(this).parent().parent();
				$ul.find(".active").removeClass("active");
				$ul = $(this).parent().addClass("active");
				return false;
			{rdelim});
		{rdelim});
		$("#js_block_border_musicsharing_newsongs-front-end").find("li.last").children("a").click(function(){ldelim}
			var $this = $(this);
			$(".js_block_click_lis_cache").css({ldelim}
				"position": "absolute",
				"right": "15px",
				"width": "auto",
				"text-align": "right",
				"top": "10px"
			{rdelim}).parent().parent().css({ldelim}
				"position": "relative"
			{rdelim});
			
			if(_js_topsong_home){ldelim}
				$('#song_list_frame').html(_js_topsong_home);
				$this.attr({ldelim}
					"href": "#"
				{rdelim});
			{rdelim}
			$this.unbind();

			$this.click(function(evt){ldelim}
				evt.preventDefault();
				$('#song_list_frame').html(_js_topsong_home);
				$ul = $(this).parent().parent();
				$ul.find(".active").removeClass("active");
				$ul = $(this).parent().addClass("active");
				return false;
			{rdelim});
		{rdelim});
	{rdelim};

	var addPL = function(who){ldelim}
		var me = who;
		tb_show('{phrase var='musicsharing.add_song_to_playlist'}', $.ajaxBox("musicsharing.addplaylist", "" + $(me).attr("href")));
		return false;
	{rdelim};
</script>