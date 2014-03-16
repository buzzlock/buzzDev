<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php /* Cached: March 15, 2014, 7:44 am */ ?>
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
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package  		Module_Blog
 * @version 		$Id: top.html.php 1318 2009-12-14 22:34:04Z Raymond_Benc $
 */
 
 

?>
<div style="display:none; margin-left:250px; background:url(<?php echo $this->_aVars['core_url']; ?>module/contactimporter/static/image/loading_small.gif) no-repeat;width:320px;height:100px;" id="loading">    
		<div style="text-align:left;padding-top:50px;padding-left:-20px; "><?php echo Phpfox::getPhrase('contactimporter.sending_request'); ?></div>
</div>

<link rel="stylesheet" type="text/css" href="<?php echo $this->_aVars['core_url']; ?>module/contactimporter/static/css/default/default/Ynscontactimporter.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->_aVars['core_url']; ?>module/contactimporter/static/css/default/default/jquery.autocomplete.css" />

<script  type="text/javascript" src="<?php echo $this->_aVars['core_url']; ?>module/contactimporter/static/jscript/jquery.autocomplete.js" /></script>

<?php echo '
<style type="text/css">
	#homecontact {
		padding-left: 8px;
	}
    #homecontact .logoContact {  
		float:left;
		height:';  echo $this->_aVars['icon_size'];  echo 'px;        
        width:';  echo $this->_aVars['icon_size'];  echo 'px;
		padding:0px 12px 4px 0px;
    }
    #homecontact .logoContact img,#homecontact .logoContact a {
		display:block;
        height:';  echo $this->_aVars['icon_size'];  echo 'px;
        width:';  echo $this->_aVars['icon_size'];  echo 'px;
    }
</style>
'; ?>

<?php echo '
<script type="text/javascript">

</script>
'; ?>

<center>
<div id="homecontact">
<table width="100%" border="0"><tr><td align="center">	
<?php if (count((array)$this->_aVars['top_5_email'])):  foreach ((array) $this->_aVars['top_5_email'] as $this->_aVars['email']):  if ($this->_aVars['email']['logo'] != ''): ?>
<?php if ($this->_aVars['email']['name'] == 'yahoo'): ?>
		<div class="logoContact">
		   <a id="yahoo" href="#?call=contactimporter.callYahoo&amp;height=80&amp;width=270"  class=" inlinePopup usingapi"  title="<?php echo Phpfox::getPhrase('contactimporter.yahoo_contacts'); ?>">
				<img alt="<?php echo $this->_aVars['email']['title']; ?>" title="<?php echo $this->_aVars['email']['title']; ?>" src="<?php echo $this->_aVars['core_url']; ?>module/contactimporter/static/image/<?php echo $this->_aVars['email']['logo']; ?>_status_up.png" />
		   </a>
		</div>
<?php elseif ($this->_aVars['email']['name'] == 'gmail'): ?>
		<div class="logoContact">
			<a id="gmail" href="#?call=contactimporter.callGmail&amp;height=80&amp;width=270" class="inlinePopup usingapi" title="<?php echo Phpfox::getPhrase('contactimporter.gmail_authorization'); ?>">
				<img alt="<?php echo $this->_aVars['email']['title']; ?>" title="<?php echo $this->_aVars['email']['title']; ?>" src="<?php echo $this->_aVars['core_url']; ?>module/contactimporter/static/image/<?php echo $this->_aVars['email']['logo']; ?>_status_up.png"/>
			</a>
		</div>
<?php elseif ($this->_aVars['email']['name'] == 'hotmail'): ?>
		<div class="logoContact">
			<a id="hotmail" href="#?call=contactimporter.callHotmail&amp;height=80&amp;width=270" class="inlinePopup usingapi" title="<?php echo Phpfox::getPhrase('contactimporter.hotmail_authorization'); ?>">
				<img alt="<?php echo $this->_aVars['email']['title']; ?>" title="<?php echo $this->_aVars['email']['title']; ?>" src="<?php echo $this->_aVars['core_url']; ?>module/contactimporter/static/image/<?php echo $this->_aVars['email']['logo']; ?>_status_up.png"/>
			</a>
		</div>
<?php elseif ($this->_aVars['email']['name'] == 'linkedin'): ?>
		<div class="logoContact">
		   <a id="linkedinA" href="#?call=contactimporter.callLinkedIn&amp;height=80&amp;width=270" class="inlinePopup usingapi" title="<?php echo Phpfox::getPhrase('contactimporter.linkedin_authorization'); ?>">
			 <img alt="<?php echo $this->_aVars['email']['title']; ?>" title="<?php echo $this->_aVars['email']['title']; ?>"  src="<?php echo $this->_aVars['core_url']; ?>module/contactimporter/static/image/<?php echo $this->_aVars['email']['logo']; ?>_status_up.png" />
			</a>
		</div>
<?php elseif ($this->_aVars['email']['name'] == 'twitter'): ?>
	<div class="logoContact">
		<a id="twitterA" href="#?call=contactimporter.callTwitter&amp;height=80&amp;width=270" class="inlinePopup usingapi" title="<?php echo Phpfox::getPhrase('contactimporter.twitter_authorization'); ?>">
			<img alt="<?php echo $this->_aVars['email']['title']; ?>" title="Twitter"  src="<?php echo $this->_aVars['core_url']; ?>module/contactimporter/static/image/<?php echo $this->_aVars['email']['logo']; ?>_status_up.png"/>
		</a>
	</div>
<?php elseif ($this->_aVars['email']['name'] == 'facebook_'): ?>
	<div class="logoContact">
		<a id="fbApi" href="#?call=contactimporter.callFacebook&amp;height=80&amp;width=270" class="inlinePopup usingapi" title="<?php echo Phpfox::getPhrase('contactimporter.facebook_authorization'); ?>">
			<img alt="<?php echo $this->_aVars['email']['title']; ?>" title="Facebook"  src="<?php echo $this->_aVars['core_url']; ?>module/contactimporter/static/image/<?php echo $this->_aVars['email']['logo']; ?>_status_up.png"/>
		</a>
	</div>
<?php else: ?>
	<div class="logoContact">
		<a title="<?php echo Phpfox::getPhrase('contactimporter.import_your_contacts'); ?>" href="#?call=contactimporter.callImporterForm&amp;height=150&amp;width=400&amp;provider_type=<?php echo $this->_aVars['email']['type']; ?>&amp;default_domain=<?php echo $this->_aVars['email']['default_domain']; ?>&amp;provider_box=<?php echo $this->_aVars['email']['name']; ?>" class="inlinePopup">
			<img alt="<?php echo $this->_aVars['email']['title']; ?>" title="<?php echo $this->_aVars['email']['title']; ?>"  src="<?php echo $this->_aVars['core_url']; ?>module/contactimporter/static/image/<?php echo $this->_aVars['email']['logo']; ?>_status_up.png">
		</a>
	</div>
<?php endif;  endif;  endforeach; endif; ?>
</td></tr></table>	
<div style="clear:both;width:100%;display:block"></div>
<span style="display:block;text-align: right;padding-right: 20px;"><a alt="<?php echo Phpfox::getPhrase('contactimporter.view_all_of_providers'); ?>" title="<?php echo Phpfox::getPhrase('contactimporter.view_all_of_providers'); ?>" href="<?php echo $this->_aVars['more_path']; ?>"><?php echo Phpfox::getPhrase('contactimporter.view_more'); ?> &raquo;</a></span>
</div>			
<div style="clear:both;width:100%;display:block"></div>
</center>

		
		
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
