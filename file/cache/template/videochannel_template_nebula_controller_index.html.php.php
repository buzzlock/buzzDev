<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php /* Cached: March 16, 2014, 7:30 pm */ ?>
<?php 

 
?>
<?php echo $this->_aVars['sJs']; ?>

<?php if ($this->_aVars['bIsUserTimeLine']): ?>
<?php echo '
<script language="javascript" type="text/javascript">
    $Behavior.addCustomSubMenus = function(){
        $(\'#section_menu ul\').last().append($(\'.buton_on_top_right_for_timeline\').html());
        $(\'.buton_on_top_right_for_timeline\').html(\'\');
    }
</script>
'; ?>

<ul class="buton_on_top_right_for_timeline" style="display: none;">
<?php if (count((array)$this->_aVars['aCustomSubMenus'])):  $this->_aPhpfoxVars['iteration']['submenu'] = 0;  foreach ((array) $this->_aVars['aCustomSubMenus'] as $this->_aVars['iKey'] => $this->_aVars['aSubMenu']):  $this->_aPhpfoxVars['iteration']['submenu']++; ?>

    <li>
        <a href="<?php echo Phpfox::getLib('phpfox.url')->makeUrl($this->_aVars['aSubMenu']['url']); ?>" class="ajax_link">
<?php if ($this->_aVars['aSubMenu']['showAddButton']): ?>
<?php echo Phpfox::getLib('phpfox.image.helper')->display(array('theme' => 'layout/section_menu_add.png','class' => 'v_middle')); ?>
<?php endif; ?>
<?php echo $this->_aVars['aSubMenu']['phrase']; ?>
        </a>
    </li>
<?php endforeach; endif; ?>
</ul>
<?php endif; ?>

<?php if (isset ( $this->_aVars['aSlideShowVideos'] ) && isset ( $this->_aVars['aVideos'] )): ?>
<style type="text/css">
.slide_info{
    background:url(<?php echo $this->_aVars['sCorePath']; ?>module/videochannel/static/image/black50.png);
}
</style>
<div class="block" id="yn_slide_show_block">
    <div class="title"><?php echo Phpfox::getPhrase('videochannel.featured_videos'); ?></div>
    <div class="border">
        <div class="content">
<?php if (! phpfox ::isMobile()): ?>
            <div style="opacity: 0;filter: alpha(opacity = 0);" id="jhslider" class="jhslider">
				<ul>
<?php if (count((array)$this->_aVars['aSlideShowVideos'])):  $this->_aPhpfoxVars['iteration']['af'] = 0;  foreach ((array) $this->_aVars['aSlideShowVideos'] as $this->_aVars['aVideo']):  $this->_aPhpfoxVars['iteration']['af']++; ?>

				 
					<li>
						<div class="jhslider-info-detail">
							<a href="<?php echo Phpfox::permalink('videochannel', $this->_aVars['aVideo']['video_id'], $this->_aVars['aVideo']['title'], false, null, (array) array (
)); ?>"><strong style="text-transform:uppercase;"><?php echo Phpfox::getLib('phpfox.parse.output')->shorten(Phpfox::getLib('phpfox.parse.output')->clean($this->_aVars['aVideo']['title']), 50, "...", false); ?></strong></a>
							
							<div> <?php echo $this->_aVars['aVideo']['total_view']; ?> <?php echo Phpfox::getPhrase('videochannel.views'); ?> - <?php echo Phpfox::getPhrase('videochannel.by_lowercase'); ?>: <?php echo Phpfox::getLib('phpfox.parse.output')->split(Phpfox::getLib('phpfox.parse.output')->shorten('<span class="user_profile_link_span" id="js_user_name_link_' . $this->_aVars['aVideo']['user_name'] . '"><a href="' . Phpfox::getLib('phpfox.url')->makeUrl('profile', array($this->_aVars['aVideo']['user_name'], ((empty($this->_aVars['aVideo']['user_name']) && isset($this->_aVars['aVideo']['profile_page_id'])) ? $this->_aVars['aVideo']['profile_page_id'] : null))) . '">' . Phpfox::getLib('phpfox.parse.output')->shorten(Phpfox::getService('user')->getCurrentName($this->_aVars['aVideo']['user_id'], $this->_aVars['aVideo']['full_name']), Phpfox::getParam('user.maximum_length_for_full_name')) . '</a></span>', 20, '...'), 20); ?></div>
						
						</div>
						<div class="jhslider-info-quick">
							
<?php echo Phpfox::getLib('phpfox.image.helper')->display(array('server_id' => $this->_aVars['aVideo']['server_id'],'title' => '','path' => 'video.url_image','file' => $this->_aVars['aVideo']['image_path'],'suffix' => '_120','onerror' => $this->_aVars['sImageOnError'])); ?>
						
						</div>
<?php echo Phpfox::getLib('phpfox.image.helper')->display(array('class' => "big-image",'thickbox' => true,'server_id' => $this->_aVars['aVideo']['server_id'],'title' => $this->_aVars['aVideo']['title'],'path' => 'video.url_image','file' => $this->_aVars['aVideo']['image_path'],'suffix' => '_480','onerror' => $this->_aVars['sImageOnError'])); ?>
					</li>
				
<?php endforeach; endif; ?>
				</ul>
				<?php echo '
				<script type="text/javascript" language="javascript">
                    
                    $Behavior.setupSlideShow = function() {
						setTimeout(function(){
                            if($(".jhslider").hasClass("init-ed")) {
                                return false;
                            }
                            $(".jhslider").unbind();
                            $(".jhslider").children().unbind();

                            var x = setTimeout("null", 0);
                            for (var i = 0 ; i < x ; i++) {
                                clearTimeout(i);
                            }
                            $(".jhslider").css({
                                "opacity": "1"
                            });

                            $(".jhslider").JHSlide(5000, 400);
                            $(".jhslider").addClass("init-ed");
                        }, 100);
                    };
                    $Behavior.VideoChannelLoadingSlideShow = function(){
                        $(window).load(function() {
							var my_regex = /static\/image\/noimage/;
							$(\'.big-image\').each(
								function(index, dom){
									if($(dom).width() == 100 || $(dom).width() == 120)
									{
										$(dom).attr(\'src\', $(dom).attr(\'onerror\'));
									}
								}
							);
						});
                    }
				</script>
				'; ?>

			</div>
<?php else: ?>
<?php if (count((array)$this->_aVars['aSlideShowVideos'])):  $this->_aPhpfoxVars['iteration']['af'] = 0;  foreach ((array) $this->_aVars['aSlideShowVideos'] as $this->_aVars['aVideo']):  $this->_aPhpfoxVars['iteration']['af']++; ?>

<?php if ($this->_aPhpfoxVars['iteration']['af'] < 5): ?>
						<?php
						Phpfox::getLib('template')->getBuiltFile('videochannel.block.mobile-featured');						
						?>
<?php endif; ?>
<?php endforeach; endif; ?>
				<div class="clear"></div>
<?php endif; ?>
		</div>
    </div>
</div>



<?php endif; ?>

<?php if ($this->_aVars['sSortTitle']): ?>
	<div class='block'>
		<div class="title"><?php echo $this->_aVars['sSortTitle']; ?></div>
	</div>
<?php endif; ?>


<div id="TB_ajaxContent"></div>
<?php if (isset ( $this->_aVars['aChannels'] )): ?>
<?php if (! count ( $this->_aVars['aChannels'] )): ?>
	  <div class="extra_info">
<?php echo Phpfox::getPhrase('videochannel.no_channels_found'); ?>
	  </div>
<?php else: ?>
<?php if (count((array)$this->_aVars['aChannels'])):  foreach ((array) $this->_aVars['aChannels'] as $this->_aVars['count'] => $this->_aVars['channel']): ?>
<?php if (! phpfox ::isMobile()): ?>
					<?php
						Phpfox::getLib('template')->getBuiltFile('videochannel.block.channel.entry');						
						?>   
<?php else: ?>
					<?php
						Phpfox::getLib('template')->getBuiltFile('videochannel.block.channel.entry-mobile');						
						?>   
<?php endif; ?>
<?php endforeach; endif; ?>
		  <div class="clear"></div>
<?php if (( Phpfox ::getUserParam('videochannel.can_add_channels')) || ( $this->_aVars['bCanAddChannelInPage'] )): ?>
<?php Phpfox::getBlock('core.moderation'); ?>
<?php endif; ?>
<?php if (!isset($this->_aVars['aPager'])): Phpfox::getLib('pager')->set(array('page' => Phpfox::getLib('request')->getInt('page'), 'size' => Phpfox::getLib('search')->getDisplay(), 'count' => Phpfox::getLib('search')->getCount())); endif;  $this->getLayout('pager'); ?>
<?php endif; ?>
<?php endif; ?>
<?php if (isset ( $this->_aVars['aVideos'] )): ?>
<?php if (! count ( $this->_aVars['aVideos'] )): ?>
	  <div class="extra_info">
<?php echo Phpfox::getPhrase('videochannel.no_videos_found'); ?>
	  </div>
<?php else: ?>
	  <div id="js_video_edit_form_outer" style="display:none;">
		  <form method="post" action="#" onsubmit="$(this).ajaxCall('videochannel.viewUpdate'); return false;">
<?php echo '<div><input type="hidden" name="' . Phpfox::getTokenName() . '[security_token]" value="' . Phpfox::getService('log.session')->getToken() . '" /></div>'; ?>
			  <div id="js_video_edit_form"></div>
			  <div class="table_clear">
				  <ul class="table_clear_button">
					  <li><input type="submit" value="<?php echo Phpfox::getPhrase('videochannel.update'); ?>" class="button" /></li>
					  <li><a href="#" id="js_video_go_advanced" class="button_off_link"><?php echo Phpfox::getPhrase('videochannel.go_advanced_uppercase'); ?></a></li>
					  <li><a href="#" onclick="$('#js_video_edit_form_outer').hide(); $('#js_video_outer_body').show(); return false;" class="button_off_link"><?php echo Phpfox::getPhrase('videochannel.cancel_uppercase'); ?></a></li>
				  </ul>
				  <div class="clear"></div>
			  </div>
		  
</form>

	  </div>
	  
	  <div id="js_video_outer_body">
<?php if (count((array)$this->_aVars['aVideos'])):  $this->_aPhpfoxVars['iteration']['videos'] = 0;  foreach ((array) $this->_aVars['aVideos'] as $this->_aVars['aVideo']):  $this->_aPhpfoxVars['iteration']['videos']++; ?>

			  <?php
						Phpfox::getLib('template')->getBuiltFile('videochannel.block.entry');						
						?>
<?php endforeach; endif; ?>
		  <div class="clear"></div>
<?php if (Phpfox ::getUserParam('videochannel.can_approve_videos') || Phpfox ::getUserParam('videochannel.can_delete_other_video')): ?>
<?php Phpfox::getBlock('core.moderation'); ?>
<?php endif; ?>
<?php if (!isset($this->_aVars['aPager'])): Phpfox::getLib('pager')->set(array('page' => Phpfox::getLib('request')->getInt('page'), 'size' => Phpfox::getLib('search')->getDisplay(), 'count' => Phpfox::getLib('search')->getCount())); endif;  $this->getLayout('pager'); ?>
	  </div>
<?php endif; ?>
<?php endif; ?>


