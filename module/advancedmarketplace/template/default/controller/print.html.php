<?php

defined('PHPFOX') or exit('NO DICE!');
?>
{literal}
<script language="javascript" type="text/javascript">
    $Behavior.advancedmarketplaceRating = function(){
        $(".yn_reviewrating").click(function(evt){
            evt.preventDefault();
            tb_show("{/literal}{phrase var="advancedmarketplace.rating" phpfox_squote=true}{literal}", $.ajaxBox('advancedmarketplace.ratePopup', 'height=300&width=550&id={/literal}{$aListing.listing_id}{literal}'));
            return false;
        });
    }
    $Behavior.advancedmarketplaceViewDetail = function(){
        var fadeTTime = 100;
        $("#yn_show_yn_listingcontent").click(function(evt){
            evt.preventDefault();
            $("#yn_listingcontent").stop(false, false).fadeIn(fadeTTime, function(){
                $("#yn_listingrating").fadeOut(fadeTTime);
            });
            $("#yn_tab").find(".active").removeClass("active");
            $(this).parent().addClass("active");
            return false;
        });
        $("#yn_show_yn_listingrating").click(function(evt){
            evt.preventDefault();
            $("#yn_listingrating").stop(false, false).fadeIn(fadeTTime, function(){
                $("#yn_listingcontent").fadeOut(fadeTTime);
            });
            $("#yn_tab").find(".active").removeClass("active");
            $(this).parent().addClass("active");
            return false;
        });

        $("#yn_listingrating").hide();
    }
</script>

<style type="text/css">
	#feedback-bt {
		display: none;
	}
    div#content #js_advancedmarketplace_listingdetail_tab div.menu
    {
        height:34px;
        background: #ececec;
        border-bottom: #dfdfdf;
    }
    div#content #js_advancedmarketplace_listingdetail_tab div.menu ul
    {
        padding-left: 10px;
    }
    div#content #js_advancedmarketplace_listingdetail_tab div.menu ul li a
    {
        line-height: 33px;
        font-size: 14px;
        color: #000;
    }
    div#content #js_advancedmarketplace_listingdetail_tab div.menu ul li.active
    {
        background: url({/literal}{$corepath}{literal}module/advancedmarketplace/static/image/default/menu-l.png) no-repeat;
    padding-left: 14px;
    margin-top: -4px;
    }
    div#content #js_advancedmarketplace_listingdetail_tab div.menu ul li.active a
    {
        background: url({/literal}{$corepath}{literal}module/advancedmarketplace/static/image/default/menu-r.png) no-repeat 100% 0;
    display: block;
    line-height: 38px;
    padding-right: 22px;
    }
    div#content #js_advancedmarketplace_listingdetail_tab div.menu ul li a
    {
        border:none;
        border-radius:0;
        background: none;
    }
    .short_description_title
    {
        background: url({/literal}{$corepath}{literal}module/advancedmarketplace/static/image/default/border.png) repeat-x bottom;
    margin: 20px 0;
    }
    .description_title
    {
        background: #fff;
        font-size: 14px;
        padding: 4px 19px 4px 0;
    }
    .listing_detail
    {
        padding-left: 10px;
    }
    .short_description_content
    {
        color: #7B7B7B;
        font-size: 12px;
    }
    .short_description_content table tr td
    {
        height: 20px;
        width: 135px;
    }
    .native-float-div {
        position: absolute;
        right: -270px;
        top: -30px;
    }
    .native-float-div .aitem {
        margin-left: 5px;
    }

    #right {
        display: none;
    }

    .item_bar_action_holder ul {
        border: none;
    }
	.preview-bar {
		width: 100%;
		background-color: #DFDFDF;
		color: #0099FF;
		text-align: center;
		padding: 10px;
		font-size: 16px;
		font-weight: bold;
		margin-top: 20px;
		margin-bottom: 20px;
	}
	.preview-bar a {
		text-decoration: none;
		text-transform: uppercase;
	}
	#im_footer_wrapper {
		display: none;
	}
	#tourcontrols {
		display: none;
	}
</style>

<style media="print">
	#feedback-bt {
		display: none;
	}
	#breadcrumb_holder {
		display: none;
	}
	#header {
		display: none;
	}
	#tm_top_bar {
		display: none;
	}
	#main_footer_holder {
		display: none;
	}
	#js_main_debug_holder {
		display: none;
	}
	.preview-bar {
		display: none;
	}
	#im_footer_wrapper {
		display: none;
	}
	#tourcontrols {
		display: none;
	}
</style>
{/literal}

<div class="preview-bar">
	<a href="#" onclick="$('#tm_top_bar').hide(); window.print(); window.close(); return false;">{phrase var="advancedmarketplace.print"}</a>
</div>
<div class="large_listing_image">
	{if $aListing.image_path != NULL}
        <a class="js_marketplace_click_image no_ajax_link" href="{img server_id=$aListing.server_id path='core.url_pic' file='advancedmarketplace/'.$aListing.image_path suffix='_400' return_url=true}">
            {img server_id=$aListing.server_id title=$aListing.title path='core.url_pic' file='advancedmarketplace/'.$aListing.image_path suffix='' max_width='520' max_height='322'}
		</a>
    {else}
        <img style="max-width:520px;max-height:322px;" title="{$aListing.title}" src="<?php echo Phpfox::getLib('template')->getStyle('image', 'noimage/item.png'); ?>" max_width='520px' max_height='322px' />
    {/if}
</div>
<div class="yn_detail_block">
    <div id="js_advancedmarketplace_listingdetail_tab" class="">
        <div class="">

            <div id="yn_listingcontent">
                {module name="advancedmarketplace.listingdetail"}
            </div>

            <div id="yn_listingrating">
                {module name="advancedmarketplace.review" aRating=$aRating iCount=$iCount iPage=$iPage iSize=$iSize}
            </div>
        </div>
    </div>
</div>