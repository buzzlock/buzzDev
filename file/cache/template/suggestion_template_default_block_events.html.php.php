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
 * @copyright		[YOUNETCO]
 * @author  		NghiDV
 * @package  		Module_Suggestion
 * @version 		$Id: ajax.class.php 1 2011-11-25 15:29:17Z YOUNETCO $
 */



 if (count ( $this->_aVars['aRows'] ) > 0): ?>
<div id="eventsBlock">  
    <input type="hidden" value="<?php echo $this->_aVars['iUserId']; ?>" id="userlogin"/>
<?php if (count((array)$this->_aVars['aRows'])):  foreach ((array) $this->_aVars['aRows'] as $this->_aVars['aRow']): ?>
        <p style="position: absolute">
<?php if ($this->_aVars['aRow']['image_path'] == ""): ?>
<?php echo $this->_aVars['aRow']['img']; ?>
<?php else: ?>
        		<a class="large_item_image" href="<?php echo Phpfox::permalink($this->_aVars['aRow']['module_id'], $this->_aVars['aRow']['event_id'], $this->_aVars['aRow']['title'], false, null, (array) array (
)); ?>" title="<?php echo Phpfox::getLib('phpfox.parse.output')->clean($this->_aVars['aRow']['title']); ?>">
<?php echo Phpfox::getLib('phpfox.image.helper')->display(array('server_id' => $this->_aVars['aRow']['server_id'],'return_url' => false,'path' => 'core.url_pic','file' => 'event/'.$this->_aVars['aRow']['image_path'],'suffix' => '_50','max_width' => 50)); ?>
        		</a>
<?php endif; ?>
        </p>
        <p style="padding: 0 0 5px 60px; border-bottom:1px solid #CCCCCC;">
            <span class="l13">                
                <a style="font-weight:bold;" rel="<?php echo $this->_aVars['aRow']['is_right']; ?>" id="<?php echo $this->_aVars['aRow']['link']; ?>" href="<?php echo $this->_aVars['aRow']['link']; ?>" target="_blank" class="suggestion-join-events"><?php echo $this->_aVars['aRow']['title']; ?>
                    <span style="display:none"><?php echo $this->_aVars['aRow']['encode_link']; ?></span>
                    <span class="divIUserId" style="display:none;"><?php echo $this->_aVars['aRow']['user_id']; ?></span>
                    <span class="title" style="display:none;"><?php echo $this->_aVars['aRow']['title']; ?></span>
                </a>
            </span><br />                        
            <span class="l13 suser"><?php echo Phpfox::getPhrase('suggestion.created_by'); ?> <?php echo $this->_aVars['aRow']['user_link']; ?></span><br />
            <span style="color:#808080"><?php echo Phpfox::getLib('date')->convertTime($this->_aVars['aRow']['time_stamp']); ?></span><br />
            
            
            <span class="l13">
<?php if ($this->_aVars['aRow']['isAllowSuggestion']): ?>
                    <a id="<?php echo $this->_aVars['aRow']['event_id']; ?>" class="suggest-event" href="#" rel=""><?php echo Phpfox::getPhrase('suggestion.suggest_to_friends_2'); ?></a>
<?php endif; ?>
<?php if (( $this->_aVars['aRow']['isAllowSuggestion'] && $this->_aVars['aRow']['display_join_link'] )): ?> - <?php endif; ?>
<?php if ($this->_aVars['aRow']['display_join_link']): ?>
                    <a rel="<?php echo $this->_aVars['aRow']['is_right']; ?>" id="<?php echo $this->_aVars['aRow']['link']; ?>" href="<?php echo $this->_aVars['aRow']['link']; ?>" target="_blank" class="suggestion-join-events"><?php echo Phpfox::getPhrase('suggestion.join_event'); ?>
                        <span style="display:none"><?php echo $this->_aVars['aRow']['encode_link']; ?></span>
                        <span class="divIUserId" style="display:none;"><?php echo $this->_aVars['aRow']['user_id']; ?></span>
                        <span class="title" style="display:none;"><?php echo $this->_aVars['aRow']['title']; ?></span>
                    </a>
<?php else: ?>
                <a href="#" style="display:none;"><span class="divIUserId" style="display:none;"><?php echo $this->_aVars['aRow']['user_id']; ?></span></a>
<?php endif; ?>
            </span><br />
            
        </p>
        <p id="suggestion-event-<?php echo $this->_aVars['aRow']['event_id']; ?>" style="display:none"><?php echo $this->_aVars['aRow']['event_id']; ?>++<?php echo $this->_aVars['aRow']['link']; ?>++<?php echo base64_encode($this->_aVars['aRow']['title']); ?></p>
<?php endforeach; endif; ?>
</div>
<?php echo '
<script language="javascript">
        $Behavior.eventsClick = function(){
            $(\'.suggest-event\').click(function(e){
                e.preventDefault();
                
                var _iId = $(this).attr(\'id\');               
                
                var _sExpectUserId = $(this).next().find(\'span[class="divIUserId"]\').eq(0).html();
                if (_sExpectUserId != \'\')                    
                    _sExpectUserId = parseInt(_sExpectUserId);
                
                var _aParams = $(\'#suggestion-event-\'+_iId).html().split(\'++\'); 
                var user_id=$(\'#userlogin\').val();
                if(user_id==0)
                {
                    suggestion_and_recommendation_tb_show(\'Login\', $.ajaxBox(\'user.login\', \'height=250&width=400\'));$(\'body\').css(\'cursor\', \'auto\');
                }
                else
                {
                    suggestion_and_recommendation_tb_show("...",$.ajaxBox(\'suggestion.friends\',\'iFriendId=\'+_aParams[0]+\'&sSuggestionType=suggestion\'+\'&sModule=suggestion_event&sLinkCallback=\'+_aParams[1]+\'&sTitle=\'+_aParams[2]+\'&sPrefix=\'+\'&sExpectUserId=\'+_sExpectUserId));
                }
            });
            
            $(\'.suggestion-join-events\').click(function(e){
                e.preventDefault();
                var _bIsRight = $(this).attr(\'rel\');
                if (_bIsRight == \'1\'){
                    var pop_window = window.open($(this).attr(\'id\'),\'pop_window\',\'toolbar=yes,location=yes,directories=yes,status=yes,menubar=yes,scrollbars=yes,copyhistory=yes,resizable=yes\');
                    if (window.focus) {pop_window.focus();}                        
                }else{
                    var _iUserId = $(this).find(\'.divIUserId\').eq(0).html();
                    var user_id_page=$(\'#userlogin\').val();
                    var _sTitle = $(this).find(\'.title\').html();
                    if(user_id_page==0)
                    {
                         suggestion_and_recommendation_tb_show(\'Login\', $.ajaxBox(\'user.login\', \'height=250&width=400\'));$(\'body\').css(\'cursor\', \'auto\');  
                    }
                    else
                    {
                        suggestion_and_recommendation_tb_show(\'\', $.ajaxBox(\'suggestion.compose\', \'height=300&width=500&id=\' + _iUserId + \'&link=\' + _sTitle + \'&no_remove_box=true\'));
                    }
                }
            });
        };
</script>
<style>
    .l13{line-height: 1.5em}
    .suser{color:#808080}
    .suser a{color:#4F4F4F}
</style>
'; ?>

<?php else:  echo '
<style>
    #js_block_border_suggestion_events{display:none;}
</style>
'; ?>

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
