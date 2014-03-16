<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php /* Cached: March 16, 2014, 7:24 pm */ ?>
<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package  		Module_Friend
 * @version 		$Id: accept.html.php 5840 2013-05-09 06:14:35Z Raymond_Benc $
 */
 
 

 if (! isset ( $this->_aVars['bIsFriendController'] )):  if (count ( $this->_aVars['aFriends'] )): ?>
<ul id="js_new_friend_holder_drop">
<?php endif;  endif;  if (! count ( $this->_aVars['aFriends'] )):  if (! isset ( $this->_aVars['bIsFriendController'] )): ?>
<div class="drop_data_empty">
<?php else: ?>
<div class="extra_info">
<?php endif; ?>
<?php echo Phpfox::getPhrase('friend.no_new_requests'); ?>
</div>
<?php else:  if (count((array)$this->_aVars['aFriends'])):  $this->_aPhpfoxVars['iteration']['friends'] = 0;  foreach ((array) $this->_aVars['aFriends'] as $this->_aVars['aFriend']):  $this->_aPhpfoxVars['iteration']['friends']++; ?>

<?php if (! isset ( $this->_aVars['bIsFriendController'] )): ?>
	<li id="js_new_friend_request_<?php echo $this->_aVars['aFriend']['request_id']; ?>" class="holder_notify_drop_data with_padding<?php if ($this->_aPhpfoxVars['iteration']['friends'] == 1): ?> first<?php endif; ?> js_friend_request_<?php echo $this->_aVars['aFriend']['request_id'];  if (! $this->_aVars['aFriend']['is_seen']): ?> is_new<?php endif; ?>">
<?php else: ?>
	<div class="row1 js_friend_request_<?php echo $this->_aVars['aFriend']['request_id']; ?> moderation_row">
<?php endif; ?>
			<div class="drop_data_image">
<?php echo Phpfox::getLib('phpfox.image.helper')->display(array('user' => $this->_aVars['aFriend'],'max_width' => '50','max_height' => '50','suffix' => '_50_square')); ?>
<?php if (isset ( $this->_aVars['bIsFriendController'] )): ?>
				<a href="#<?php echo $this->_aVars['aFriend']['request_id']; ?>" class="moderate_link" rel="friend"><?php echo Phpfox::getPhrase('friend.moderate'); ?></a>
<?php endif; ?>
			</div>
			<div class="drop_data_content">
				<div class="drop_data_user">
					<div class="drop_data_action">
						<div class="js_drop_data_add" style="display:none; padding-right:5px;">
<?php echo Phpfox::getLib('phpfox.image.helper')->display(array('theme' => 'ajax/add.gif')); ?>
						</div>
						<div class="js_drop_data_button" id="drop_down_<?php echo $this->_aVars['aFriend']['request_id']; ?>">
							<ul class="table_clear_button">
								<li><input type="button" name="" value="<?php echo Phpfox::getPhrase('friend.confirm'); ?>" class="button" onclick="$(this).parents('.drop_data_action').find('.js_drop_data_add').show(); <?php if ($this->_aVars['aFriend']['relation_data_id'] > 0): ?> $.ajaxCall('custom.processRelationship', 'relation_data_id=<?php echo $this->_aVars['aFriend']['relation_data_id']; ?>&amp;type=accept&amp;request_id=<?php echo $this->_aVars['aFriend']['request_id']; ?>'); <?php else: ?> $.ajaxCall('friend.processRequest', 'type=yes&amp;user_id=<?php echo $this->_aVars['aFriend']['user_id']; ?>&amp;request_id=<?php echo $this->_aVars['aFriend']['request_id']; ?>&amp;inline=true'); <?php endif; ?>" /></li>
								<li><input type="button" name="" value="<?php echo Phpfox::getPhrase('friend.deny'); ?>" class="button button_off" onclick="$(this).parents('.drop_data_action').find('.js_drop_data_add').show(); <?php if ($this->_aVars['aFriend']['relation_data_id'] > 0): ?> $.ajaxCall('custom.processRelationship', 'relation_data_id=<?php echo $this->_aVars['aFriend']['relation_data_id']; ?>&amp;type=deny&amp;request_id=<?php echo $this->_aVars['aFriend']['request_id']; ?>'); <?php else: ?> $.ajaxCall('friend.processRequest', 'type=no&amp;user_id=<?php echo $this->_aVars['aFriend']['user_id']; ?>&amp;request_id=<?php echo $this->_aVars['aFriend']['request_id']; ?>&amp;inline=true'); <?php endif; ?>" /></li>
							</ul>
							<div class="clear"></div>
						</div>
					</div>
					<div style="width:120px;">
<?php echo '<span class="user_profile_link_span" id="js_user_name_link_' . $this->_aVars['aFriend']['user_name'] . '"><a href="' . Phpfox::getLib('phpfox.url')->makeUrl('profile', array($this->_aVars['aFriend']['user_name'], ((empty($this->_aVars['aFriend']['user_name']) && isset($this->_aVars['aFriend']['profile_page_id'])) ? $this->_aVars['aFriend']['profile_page_id'] : null))) . '">' . Phpfox::getLib('phpfox.parse.output')->shorten(Phpfox::getService('user')->getCurrentName($this->_aVars['aFriend']['user_id'], $this->_aVars['aFriend']['full_name']), Phpfox::getParam('user.maximum_length_for_full_name')) . '</a></span>'; ?>
<?php if ($this->_aVars['aFriend']['relation_data_id'] > 0): ?>
                            <div class="extra_info_link">
<?php echo Phpfox::getLib('phpfox.image.helper')->display(array('theme' => 'misc/heart.png','class' => 'v_middle')); ?> <?php echo Phpfox::getPhrase('friend.relationship_request'); ?>
                            </div>
<?php else: ?>
<?php if (isset ( $this->_aVars['aFriend']['mutual_friends'] ) && $this->_aVars['aFriend']['mutual_friends']['total'] > 0): ?>
                                <div class="extra_info_link">				
                                    <a href="#" onclick="$Core.box('friend.getMutualFriends', 300, 'user_id=<?php echo $this->_aVars['aFriend']['friend_user_id']; ?>'); return false;">
<?php if ($this->_aVars['aFriend']['mutual_friends']['total'] == 1): ?>
<?php echo Phpfox::getPhrase('friend.1_mutual_friend'); ?>
<?php else: ?>
<?php echo Phpfox::getPhrase('friend.total_mutual_friends', array('total' => $this->_aVars['aFriend']['mutual_friends']['total'])); ?>
<?php endif; ?>
                                    </a>
                                </div>
<?php endif; ?>
<?php endif; ?>
<?php (($sPlugin = Phpfox_Plugin::get('friend.template_block_accept__1')) ? eval($sPlugin) : false); ?>
                        
<?php if (! empty ( $this->_aVars['aFriend']['message'] )): ?>
						<div class="extra_info">
<?php echo Phpfox::getLib('phpfox.parse.output')->shorten(Phpfox::getLib('phpfox.parse.output')->clean($this->_aVars['aFriend']['message'],false), 20, 'friend.view_more', true); ?>
						</div>
<?php endif; ?>
					</div>
					<ul class="extra_info_middot" style="display:none;">
						<li><a href="#" onclick="$Core.composeMessage({user_id: <?php echo $this->_aVars['aFriend']['user_id']; ?>}); return false;"><?php echo Phpfox::getPhrase('friend.send_a_message'); ?></a></li>
						<li>&middot;</li>
						<li><a href="<?php echo Phpfox::getLib('phpfox.url')->makeUrl($this->_aVars['aFriend']['user_name']); ?>"><?php echo Phpfox::getPhrase('friend.view_profile'); ?></a></li>
					</ul>					
				</div>				
			</div>
			<div class="clear"></div>		
<?php if (! isset ( $this->_aVars['bIsFriendController'] )): ?>
	</li>
<?php else: ?>
	</div>
<?php endif;  endforeach; endif;  endif;  if (! isset ( $this->_aVars['bIsFriendController'] )):  if (count ( $this->_aVars['aFriends'] )): ?>
</ul>

<?php echo '
<script type="text/javascript">	
	var $iTotalFriends = parseInt($(\'#js_total_new_friend_requests\').html());
	var $iNewTotalFriends = 0;
	$(\'#js_new_friend_holder_drop li.holder_notify_drop_data\').each(function()
	{
		$iNewTotalFriends++;
		// $aMailOldHistory[$(this).attr(\'id\').replace(\'js_new_friend_request_\', \'\')] = true;		
	});
	
	$iTotalFriends = parseInt(($iTotalFriends - $iNewTotalFriends));
	if ($iTotalFriends < 0)
	{
		$iTotalFriends = 0;
	}
	
	if ($iTotalFriends === 0)
	{
		$(\'#js_total_new_friend_requests\').html(\'\').hide();	
	}
	else
	{
		$(\'#js_total_new_friend_requests\').html($iTotalFriends);
	}	
</script>
'; ?>


<?php endif; ?>
<a href="<?php echo Phpfox::getLib('phpfox.url')->makeUrl('friend.accept'); ?>" class="holder_notify_drop_link"><?php echo Phpfox::getPhrase('friend.see_all_friend_requests'); ?></a>
<?php endif; ?>
