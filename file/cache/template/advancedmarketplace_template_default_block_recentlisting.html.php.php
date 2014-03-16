<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php /* Cached: March 16, 2014, 7:30 pm */ ?>
<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author			Raymond Benc
 * @package 		Phpfox
 * @version 		$Id: block.html.php 6820 2013-10-22 13:05:35Z Raymond_Benc $
 */
 
 

 if (( isset ( $this->_aVars['sHeader'] ) && ( ! PHPFOX_IS_AJAX || isset ( $this->_aVars['bPassOverAjaxCall'] ) || isset ( $this->_aVars['bIsAjaxLoader'] ) ) ) || ( defined ( "PHPFOX_IN_DESIGN_MODE" ) && PHPFOX_IN_DESIGN_MODE ) || ( Phpfox ::getService('theme')->isInDnDMode())): ?>

<div class="block<?php if (( defined ( 'PHPFOX_IN_DESIGN_MODE' ) || Phpfox ::getService('theme')->isInDnDMode()) && ( ! isset ( $this->_aVars['bCanMove'] ) || ( isset ( $this->_aVars['bCanMove'] ) && $this->_aVars['bCanMove'] == true ) )): ?> js_sortable<?php endif;  if (isset ( $this->_aVars['sCustomClassName'] )): ?> <?php echo $this->_aVars['sCustomClassName'];  endif; ?>"<?php if (isset ( $this->_aVars['sBlockBorderJsId'] )): ?> id="js_block_border_<?php echo $this->_aVars['sBlockBorderJsId']; ?>"<?php endif;  if (defined ( 'PHPFOX_IN_DESIGN_MODE' ) && Phpfox ::getLib('module')->blockIsHidden('js_block_border_' . $this->_aVars['sBlockBorderJsId'] . '' )): ?> style="display:none;"<?php endif; ?>>
<?php if (! empty ( $this->_aVars['sHeader'] ) || ( defined ( "PHPFOX_IN_DESIGN_MODE" ) && PHPFOX_IN_DESIGN_MODE ) || ( Phpfox ::getService('theme')->isInDnDMode())): ?>
		<div class="title <?php if (defined ( 'PHPFOX_IN_DESIGN_MODE' ) || Phpfox ::getService('theme')->isInDnDMode()): ?>js_sortable_header<?php endif; ?>">		
<?php if (isset ( $this->_aVars['sBlockTitleBar'] )): ?>
<?php echo $this->_aVars['sBlockTitleBar']; ?>
<?php endif; ?>
<?php if (( isset ( $this->_aVars['aEditBar'] ) && Phpfox ::isUser())): ?>
			<div class="js_edit_header_bar">
				<a href="#" title="<?php echo Phpfox::getPhrase('core.edit_this_block'); ?>" onclick="$.ajaxCall('<?php echo $this->_aVars['aEditBar']['ajax_call']; ?>', 'block_id=<?php echo $this->_aVars['sBlockBorderJsId'];  if (isset ( $this->_aVars['aEditBar']['params'] )):  echo $this->_aVars['aEditBar']['params'];  endif; ?>'); return false;"><?php echo Phpfox::getLib('phpfox.image.helper')->display(array('theme' => 'misc/application_edit.png','alt' => '','class' => 'v_middle')); ?></a>				
			</div>
<?php endif; ?>
<?php if (true || isset ( $this->_aVars['sDeleteBlock'] )): ?>
			<div class="js_edit_header_bar js_edit_header_hover" style="display:none;">
<?php if (Phpfox ::getService('theme')->isInDnDMode() && ( ( isset ( $this->_aVars['bCanMove'] ) && $this->_aVars['bCanMove'] ) || ! isset ( $this->_aVars['bCanMove'] ) )): ?>
					<a href="#" onclick="if (confirm('<?php echo Phpfox::getPhrase('core.are_you_sure', array('phpfox_squote' => true)); ?>')){
					$(this).parents('.block:first').remove(); $.ajaxCall('core.removeBlockDnD', 'sController=' + oParams['sController'] 
					+ '&amp;block_id=<?php if (isset ( $this->_aVars['sDeleteBlock'] )):  echo $this->_aVars['sDeleteBlock'];  else: ?> <?php echo $this->_aVars['sBlockBorderJsId'];  endif; ?>');} return false;"title="<?php echo Phpfox::getPhrase('core.remove_this_block'); ?>">
<?php echo Phpfox::getLib('phpfox.image.helper')->display(array('theme' => 'misc/application_delete.png','alt' => '','class' => 'v_middle')); ?>
					</a>
<?php else: ?>
<?php if (( ( isset ( $this->_aVars['bCanMove'] ) && $this->_aVars['bCanMove'] ) || ! isset ( $this->_aVars['bCanMove'] ) )): ?>
						<a href="#" onclick="if (confirm('<?php echo Phpfox::getPhrase('core.are_you_sure', array('phpfox_squote' => true)); ?>')) { $(this).parents('.block:first').remove();
						$.ajaxCall('core.hideBlock', '<?php if (isset ( $this->_aVars['sCustomDesignId'] )): ?>custom_item_id=<?php echo $this->_aVars['sCustomDesignId']; ?>&amp;<?php endif; ?>sController=' + oParams['sController'] + '&amp;type_id=<?php if (isset ( $this->_aVars['sDeleteBlock'] )):  echo $this->_aVars['sDeleteBlock'];  else: ?> <?php echo $this->_aVars['sBlockBorderJsId'];  endif; ?>&amp;block_id=' + $(this).parents('.block:first').attr('id')); } return false;" title="<?php echo Phpfox::getPhrase('core.remove_this_block'); ?>">
<?php echo Phpfox::getLib('phpfox.image.helper')->display(array('theme' => 'misc/application_delete.png','alt' => '','class' => 'v_middle')); ?>
						</a>				
<?php endif; ?>
<?php endif; ?>
			</div>
			
<?php endif; ?>
<?php if (empty ( $this->_aVars['sHeader'] )): ?>
<?php echo $this->_aVars['sBlockShowName']; ?>
<?php else: ?>
<?php echo $this->_aVars['sHeader']; ?>
<?php endif; ?>
		</div>
<?php endif; ?>
<?php if (isset ( $this->_aVars['aEditBar'] )): ?>
	<div id="js_edit_block_<?php echo $this->_aVars['sBlockBorderJsId']; ?>" class="edit_bar" style="display:none;"></div>
<?php endif; ?>
<?php if (isset ( $this->_aVars['aMenu'] ) && count ( $this->_aVars['aMenu'] )): ?>
	<div class="menu">
	<ul>
<?php if (count((array)$this->_aVars['aMenu'])):  $this->_aPhpfoxVars['iteration']['content'] = 0;  foreach ((array) $this->_aVars['aMenu'] as $this->_aVars['sPhrase'] => $this->_aVars['sLink']):  $this->_aPhpfoxVars['iteration']['content']++; ?>
 
		<li class="<?php if (count ( $this->_aVars['aMenu'] ) == $this->_aPhpfoxVars['iteration']['content']): ?> last<?php endif;  if ($this->_aPhpfoxVars['iteration']['content'] == 1): ?> first active<?php endif; ?>"><a href="<?php echo $this->_aVars['sLink']; ?>"><?php echo $this->_aVars['sPhrase']; ?></a></li>
<?php endforeach; endif; ?>
	</ul>
	<div class="clear"></div>
	</div>
<?php unset($this->_aVars['aMenu']); ?>
<?php endif; ?>
	<div class="content"<?php if (isset ( $this->_aVars['sBlockJsId'] )): ?> id="js_block_content_<?php echo $this->_aVars['sBlockJsId']; ?>"<?php endif; ?>>
<?php endif; ?>
		<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
 if ($this->_aVars['aRecentListing'] !== NULL): ?>

<div class="recent_listing">
<?php if (count((array)$this->_aVars['aRecentListing'])):  foreach ((array) $this->_aVars['aRecentListing'] as $this->_aVars['iKey'] => $this->_aVars['aListing']): ?>
    <div class="listing_1">
        <div class="row_title_image_header">
        
<?php if (isset ( $this->_aVars['aListing']['is_expired'] )): ?>
				<div class="row_featured_link">
<?php echo Phpfox::getPhrase('advancedmarketplace.expired'); ?>
				</div>
<?php else: ?>
<?php if (isset ( $this->_aVars['sView'] ) && $this->_aVars['sView'] == 'featured'): ?>
<?php else: ?>
                <div id="js_featured_phrase_<?php echo $this->_aVars['aListing']['listing_id']; ?>" class="row_featured_link"<?php if (! $this->_aVars['aListing']['is_featured']): ?> style="display:none;"<?php endif; ?>>
<?php echo Phpfox::getPhrase('advancedmarketplace.featured'); ?>
                </div>					
<?php endif; ?>
                <div id="js_sponsor_phrase_<?php echo $this->_aVars['aListing']['listing_id']; ?>" class="js_sponsor_event row_sponsored_link"<?php if (! $this->_aVars['aListing']['is_sponsor']): ?> style="display:none;"<?php endif; ?>>
<?php echo Phpfox::getPhrase('advancedmarketplace.sponsored'); ?>
                </div>
<?php endif; ?>
            
<?php if (! phpfox ::isMobile()): ?>
				<a href="<?php echo $this->_aVars['aListing']['url']; ?>" title="<?php echo Phpfox::getLib('phpfox.parse.output')->clean(Phpfox::getLib('phpfox.parse.output')->parse($this->_aVars['aListing']['title'])); ?>">
<?php if ($this->_aVars['aListing']['image_path'] != NULL): ?>
<?php echo Phpfox::getLib('phpfox.image.helper')->display(array('server_id' => $this->_aVars['aListing']['server_id'],'path' => 'core.url_pic','file' => 'advancedmarketplace/'.$this->_aVars['aListing']['image_path'],'suffix' => '_120','max_width' => 120,'max_height' => 90,'title' => $this->_aVars['aListing']['title'])); ?>
                        <!--<img title="<?php echo $this->_aVars['aListing']['title']; ?>" src="<?php echo $this->_aVars['advancedmarketplace_url_image'] . PHPFOX::getService("advancedmarketplace")->proccessImageName($this->_aVars["aListing"]["image_path"], "_120"); ?>" style="max-width: 120px; max-height: 90px;" max-width='120' max-height='90' />-->
<?php else: ?>
                        <img title="<?php echo $this->_aVars['aListing']['title']; ?>" src="<?php echo $this->_aVars['corepath']; ?>module/advancedmarketplace/static/image/default/noimage.png" style="max-width:120px;max-height:90px" max-width='120' max-height='90' />
<?php endif; ?>
                </a>
<?php else: ?>
				<a href="<?php echo $this->_aVars['aListing']['url']; ?>" title="<?php echo Phpfox::getLib('phpfox.parse.output')->clean(Phpfox::getLib('phpfox.parse.output')->parse($this->_aVars['aListing']['title'])); ?>">
<?php if ($this->_aVars['aListing']['image_path'] != NULL): ?>
<?php echo Phpfox::getLib('phpfox.image.helper')->display(array('server_id' => $this->_aVars['aListing']['server_id'],'path' => 'core.url_pic','file' => 'advancedmarketplace/'.$this->_aVars['aListing']['image_path'],'suffix' => '_120','max_width' => 90,'max_height' => 90,'title' => $this->_aVars['aListing']['title'])); ?>
<?php else: ?>
                        <img title="<?php echo $this->_aVars['aListing']['title']; ?>" src="<?php echo $this->_aVars['corepath']; ?>module/advancedmarketplace/static/image/default/noimage.png" style="max-width:90px;max-height:90px" max-width='90' max-height='90' />
<?php endif; ?>
                </a>
<?php endif; ?>
        </div>	
        <div class="row_title_image_header_body">				
            <div class="row_title">	
                <div class="row_title_image">
<?php echo Phpfox::getLib('phpfox.image.helper')->display(array('user' => $this->_aVars['aListing'],'suffix' => '_50_square','max_width' => '50','max_height' => '50')); ?>

<?php if (( $this->_aVars['aListing']['user_id'] == Phpfox ::getUserId() && Phpfox ::getUserParam('advancedmarketplace.can_edit_own_listing')) || Phpfox ::getUserParam('advancedmarketplace.can_edit_other_listing') || ( $this->_aVars['aListing']['user_id'] == Phpfox ::getUserId() && Phpfox ::getUserParam('advancedmarketplace.can_delete_own_listing')) || Phpfox ::getUserParam('advancedmarketplace.can_delete_other_listings') || ( Phpfox ::getUserParam('advancedmarketplace.can_feature_listings'))): ?>
                    <div class="row_edit_bar_parent">
                        <div class="row_edit_bar_holder">
                            <ul>
                                <?php
						Phpfox::getLib('template')->getBuiltFile('advancedmarketplace.block.menu');						
						?>
                            </ul>			
                        </div>
                        <div class="row_edit_bar">
                            <a href="#" class="row_edit_bar_action"><span><?php echo Phpfox::getPhrase('advancedmarketplace.actions'); ?></span></a>
                        </div>
						</div>		
<?php endif; ?>

<?php if (Phpfox ::getUserParam('event.can_approve_events') || Phpfox ::getUserParam('event.can_delete_other_event')): ?><a href="#<?php echo $this->_aVars['aListing']['listing_id']; ?>" class="moderate_link" rel="advancedmarketplace">Moderate</a><?php endif; ?>
                </div>
                <div class="row_title_info">		
                    <a class="advlink" href="<?php echo $this->_aVars['aListing']['url']; ?>" title="<?php echo Phpfox::getLib('phpfox.parse.output')->clean(Phpfox::getLib('phpfox.parse.output')->parse($this->_aVars['aListing']['title'])); ?>">
<?php if (! phpfox ::isMobile()): ?>
<?php echo $this->_aVars['aListing']['title']; ?>
<?php else: ?>
<?php echo Phpfox::getLib('phpfox.parse.output')->shorten($this->_aVars['aListing']['title'], 15, "..."); ?>
<?php endif; ?>
					</a>
                    <div class="advancedmarketplace_price_tag_cs">
<?php if ($this->_aVars['aListing']['price'] == '0.00'): ?>
<?php echo Phpfox::getPhrase('advancedmarketplace.free'); ?>
<?php else: ?>
<?php echo Phpfox::getService('core.currency')->getSymbol($this->_aVars['aListing']['currency_id']);  echo $this->_aVars['aListing']['price']; ?>
<?php endif; ?>
                    </div>																
                    <div class="extra_info"> <?php echo Phpfox::getLib('date')->convertTime($this->_aVars['aListing']['time_stamp']); ?> <span>&middot;</span> <?php echo '<span class="user_profile_link_span" id="js_user_name_link_' . $this->_aVars['aListing']['user_name'] . '"><a href="' . Phpfox::getLib('phpfox.url')->makeUrl('profile', array($this->_aVars['aListing']['user_name'], ((empty($this->_aVars['aListing']['user_name']) && isset($this->_aVars['aListing']['profile_page_id'])) ? $this->_aVars['aListing']['profile_page_id'] : null))) . '">' . Phpfox::getLib('phpfox.parse.output')->shorten(Phpfox::getService('user')->getCurrentName($this->_aVars['aListing']['user_id'], $this->_aVars['aListing']['full_name']), Phpfox::getParam('user.maximum_length_for_full_name')) . '</a></span>'; ?> <span>&middot;</span> <a class="js_hover_title" href="<?php echo Phpfox::getLib('phpfox.url')->makeUrl('advancedmarketplace', array('location' => $this->_aVars['aListing']['country_iso'])); ?>"><?php echo Phpfox::getService('core.country')->getCountry($this->_aVars['aListing']['country_iso']); ?><span class="js_hover_info"><?php if (! empty ( $this->_aVars['aListing']['city'] )): ?> <?php echo Phpfox::getLib('phpfox.parse.output')->clean($this->_aVars['aListing']['city']); ?> &raquo; <?php endif;  if (! empty ( $this->_aVars['aListing']['country_child_id'] )): ?> <?php echo Phpfox::getService('core.country')->getChild($this->_aVars['aListing']['country_child_id']); ?> &raquo; <?php endif; ?> <?php echo Phpfox::getService('core.country')->getCountry($this->_aVars['aListing']['country_iso']); ?></span></a></div>
                    <div class="item_content">
<?php echo Phpfox::getLib('phpfox.parse.output')->shorten(Phpfox::getLib('phpfox.parse.output')->split(Phpfox::getLib('phpfox.search')->highlight('search', Phpfox::getLib('phpfox.parse.output')->parse($this->_aVars['aListing']['short_description'])), 25), 200, 'advancedmarketplace.see_more', true); ?>
                    </div>							
                </div>			
            </div>	
        </div>
        <div class="clear"></div>				
    </div>
<?php endforeach; endif; ?>
</div>
<div <?php if (! phpfox ::isMobile()): ?>class="view_more_link" style="padding-left: 430px!important;background-position: 414px 60% !important;"<?php endif; ?>><a href="<?php echo Phpfox::getLib('phpfox.url')->makeUrl('advancedmarketplace.search.sort_latest'); ?>"><?php echo Phpfox::getPhrase('advancedmarketplace.view_more'); ?></a></div>
<?php endif; ?>

		
		
<?php if (( isset ( $this->_aVars['sHeader'] ) && ( ! PHPFOX_IS_AJAX || isset ( $this->_aVars['bPassOverAjaxCall'] ) || isset ( $this->_aVars['bIsAjaxLoader'] ) ) ) || ( defined ( "PHPFOX_IN_DESIGN_MODE" ) && PHPFOX_IN_DESIGN_MODE ) || ( Phpfox ::getService('theme')->isInDnDMode())): ?>
	</div>
<?php if (isset ( $this->_aVars['aFooter'] ) && count ( $this->_aVars['aFooter'] )): ?>
	<div class="bottom">
		<ul>
<?php if (count((array)$this->_aVars['aFooter'])):  $this->_aPhpfoxVars['iteration']['block'] = 0;  foreach ((array) $this->_aVars['aFooter'] as $this->_aVars['sPhrase'] => $this->_aVars['sLink']):  $this->_aPhpfoxVars['iteration']['block']++; ?>

				<li id="js_block_bottom_<?php echo $this->_aPhpfoxVars['iteration']['block']; ?>"<?php if ($this->_aPhpfoxVars['iteration']['block'] == 1): ?> class="first"<?php endif; ?>>
<?php if ($this->_aVars['sLink'] == '#'): ?>
<?php echo Phpfox::getLib('phpfox.image.helper')->display(array('theme' => 'ajax/add.gif','class' => 'ajax_image')); ?>
<?php endif; ?>
					<a href="<?php echo $this->_aVars['sLink']; ?>" id="js_block_bottom_link_<?php echo $this->_aPhpfoxVars['iteration']['block']; ?>"><?php echo $this->_aVars['sPhrase']; ?></a>
				</li>
<?php endforeach; endif; ?>
		</ul>
	</div>
<?php endif; ?>
</div>
<?php endif;  unset($this->_aVars['sHeader'], $this->_aVars['sComponent'], $this->_aVars['aFooter'], $this->_aVars['sBlockBorderJsId'], $this->_aVars['bBlockDisableSort'], $this->_aVars['bBlockCanMove'], $this->_aVars['aEditBar'], $this->_aVars['sDeleteBlock'], $this->_aVars['sBlockTitleBar'], $this->_aVars['sBlockJsId'], $this->_aVars['sCustomClassName'], $this->_aVars['aMenu']); ?>

<?php if (isset ( $this->_aVars['sClass'] )): ?>
<?php Phpfox::getBlock('ad.inner', array('sClass' => $this->_aVars['sClass']));  endif; ?>
