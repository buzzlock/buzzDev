{if count($aNewAlbums)>0}
	<link rel="stylesheet" type="text/css" href="{$sLink2}module/musicsharing/static/css/style.css" />
	<script language="javascript" type="text/javascript" src="{$sLink2}module/musicsharing/static/jscript/init_index.js"></script>

	{literal}
	<style type="text/css">
	.lof-main-item-desc a {
		text-decoration: none;
		color: #FFFFFF;
	}

	.content3
	{
		width:540px;
	}
	.lof-slidecontent .preload div{
		height:100%;
		width:100%;
		background:transparent url("{/literal}{$sLink2}{literal}module/musicsharing/static/image/slideshow/load-indicator.gif") no-repeat scroll 50% 50%;
	}
	.lof-main-item-desc{
		z-index:100px;
		position:absolute;
			bottom:0;
		width:348px;
		background:url("{/literal}{$sLink2}{literal}module/musicsharing/static/image/slideshow/transparent_bg.png");
			height: 60px;
		/* filter:0.7(opacity:60) */
	}
	ul.lof-main-wapper{
		/* margin-right:auto; */
		overflow:hidden;
		background:transparent url("{/literal}{$sLink2}{literal}module/musicsharing/static/image/slideshow/load-indicator.gif") no-repeat scroll 50% 50%;
		padding:0px;
		margin:0;
		height:248px;
		width:530px;
		position:absolute;
		overflow:hidden;
		border-top: 1px solid #FFFFFF;
	}
	li-desc{
		z-index:100px;
		position:absolute;
		top:150px;
		left:50px;
		width:400px;
		background:url("{/literal}{$sLink2}{literal}module/musicsharing/static/image/slideshow/transparent_bg.png");

		/* filter:0.7(opacity:60) */
	}
	.lof-navigator li.active{
		background:url("{/literal}{$sLink2}{literal}module/musicsharing/static/image/slideshow/arrow-bg.png") no-repeat scroll left center; 
		color:#FFF
	}
	.lof-navigator li div{
		background:url("{/literal}{$sLink2}{literal}module/musicsharing/static/image/slideshow/transparent_bg.png");
		color:#FFF;
		height:100%;
		position:relative;
		margin-left:15px;
		padding-left:15px;
		border-top:1px solid #E1E1E1;
	}
	.lof-navigator li.active div{
		background:url("{/literal}{$sLink2}{literal}module/musicsharing/static/image/slideshow/grad-bg.gif");
		color:#FFF;
	}
	.block .content {
		padding: 0px 0 0;
		position: relative;
	}

	.lof-main-wapper img {
		background-color: #FFFFFF;
	}
    .image_slide_show_full{z-index: 3; width: auto; height: auto;}
    .image_slide_show{border:none !important; width: 56px !important; height: 58px !important; padding: 0px !important; float: none !important; margin: auto !important; max-width: 50px !important; max-height: 50px !important;}
    
	</style>
	{/literal}

	<div id="lofslidecontent45" class="lof-slidecontent">
	<div class="preload"><div></div></div>
	 <!-- MAIN CONTENT --> 
	  <div class="lof-main-outer" style="z-index: 33;width: 345px;">
		<ul class="lof-main-wapper" style="width: 345px;">
		{foreach from=$aNewAlbums item=aNewAlbum name=anew} 
			<li style="position: relative;width: 345px;text-align: center;">
				{if isset($aNewAlbum.album_image) && $aNewAlbum.album_image !=""}
                    {img server_id=$aNewAlbum.server_id path='musicsharing.url_image' file=$aNewAlbum.album_image suffix='' max_width='345' max_height='250' class='image_slide_show_full' title=$aNewAlbum.title}
				{else}
					<img style="z-index: 3;" src="{$sLink2}module/musicsharing/static/image/music.png" title="{$aNewAlbum.title|clean}" max-height="250">
				{/if}
				<div class="lof-main-item-desc" style="position: absolute; bottom: 0px; left: 0px;">
					<h3 style="text-align: left;">
						<a target="_parent" title="{$aNewAlbum.title|clean}" href="{*
							*}{if !isset($aParentModule)}{*
								*}{url link='musicsharing.listen' album=$aNewAlbum.album_id}{*
							*}{else}{*
								*}{url link=$aParentModule.module_id.".".$aParentModule.item_id.'.musicsharing.listen' album=$aNewAlbum.album_id}{*
							*}{/if}{*
						*}">
							{$aNewAlbum.title|clean|shorten:15:"...":false}
						</a>
					</h3>

					<p style="text-align: left;">{$aNewAlbum.full_name|clean|shorten:50:"...":false}</p>
				</div>
			</li> 
		 {/foreach}
		  </ul>  	
	  </div>
	  <!-- END MAIN CONTENT --> 
		<!-- NAVIGATOR -->

	  <div class="lof-navigator-outer" style="z-index: 999; position: absolute; top: 0px; right: 0px;">
			<ul class="lof-navigator">
			<?php $cx = -1; ?>
			{foreach from=$aNewAlbums item=aNewAlbum name=anews}
				<?php $cx++; ?>
				<li style="z-index: 32;" onclick="/* seft.jumping( <?php echo $cx; ?>, true ); seft.setNavActive( <?php echo $cx; ?>, this ); */">
					<div>
						<table cellpadding="0" cellspacing="0" style="float: left; vertical-align: middle;text-align: center; border: 1px solid #C5C5C5;margin: 15px 15px 10px 0;width: 56px; height: 58px;">
                            <tr>
                                <td style="padding: 3px; padding-bottom: 0px; height: 50px; width: 50px; overflow: hidden; vertical-align: middle;">
                                    {if isset($aNewAlbum.album_image) && $aNewAlbum.album_image !=""}
                                        {img server_id=$aNewAlbum.server_id path='musicsharing.url_image' file=$aNewAlbum.album_image suffix='_115' max_width='50' max_height='50' class='image_slide_show'}
                                    {else}
                                        {img path='core.path' file=$sDefaultImage suffix='_50' max_width='50' max_height='50' class='image_slide_show'}
                                    {/if}
                                </td>
                            </tr>
                        </table>
						<h3><span style="color: #fff;">{$aNewAlbum.title|clean|shorten:15:"...":false}</span></h3>
						{$aNewAlbum.full_name|clean|shorten:40:"...":false}
					</div>    
				</li>
			{/foreach}    	
			</ul>
	  </div>
	 </div> 
{/if}