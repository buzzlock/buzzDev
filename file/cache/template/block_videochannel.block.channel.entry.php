<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php /* Cached: March 16, 2014, 7:30 pm */ ?>
<?php 

 

?>
<div id="js_channel_entry_<?php echo $this->_aVars['channel']['channel_id']; ?>" class="channel_entry">
   <div class='post_info' style="display: none">
      <div class="en_img"><?php echo $this->_aVars['channel']['en_video_image']; ?></div>            
      <div class="en_url"><?php echo $this->_aVars['channel']['en_url']; ?></div>            
      <div class="en_title"><?php echo $this->_aVars['channel']['en_title']; ?></div>            
      <div class="en_summary"><?php echo $this->_aVars['channel']['en_summary']; ?></div>            
   </div>
   <div class="<?php if ($this->_aVars['count'] % 2 == 0): ?> row2 <?php else: ?> row1 <?php endif; ?>  <?php if ($this->_aVars['count'] == 0): ?>row_first<?php endif; ?>">
      <div class="row_title">
         <div class="row_title_image">            
<?php if (isset ( $this->_aVars['channel']['isExist'] )): ?>
<?php if (( Phpfox ::getUserParam('videochannel.can_add_channels') && ! isset ( $this->_aVars['sSubmitUrl'] ) ) || ( $this->_aVars['bCanAddChannelInPage'] )): ?>
                  <div class="video_moderate_link">
                     <a href="#<?php echo $this->_aVars['channel']['isExist']; ?>" class="moderate_link" rel="videochannel"><?php echo Phpfox::getPhrase('videochannel.moderate'); ?></a>
                  </div>				
<?php endif; ?>
                  <a title="<?php echo $this->_aVars['channel']['title']; ?>" href="<?php echo $this->_aVars['channel']['link']; ?>">
<?php else: ?>
                  <a title="<?php echo $this->_aVars['channel']['title']; ?>" href="<?php echo $this->_aVars['channel']['link']; ?>" target="_blank">
<?php endif; ?>
<?php if (! empty ( $this->_aVars['channel']['video_image'] )): ?>
               <img class="channel_image" src="<?php echo $this->_aVars['channel']['video_image']; ?>" alt="<?php echo $this->_aVars['channel']['title']; ?>" height="90" width="120"/>
<?php else: ?>
<?php echo Phpfox::getLib('phpfox.image.helper')->display(array('theme' => 'noimage/item.png')); ?>
<?php endif; ?>
            </a>            
         </div>
         <div class="row_title_info">
            <span style="font-size: 12px; font-weight: bold;">
<?php if (isset ( $this->_aVars['channel']['isExist'] )): ?>
               <a  class="channel_title" title="<?php echo Phpfox::getLib('phpfox.parse.output')->clean($this->_aVars['channel']['title']); ?>" href="<?php echo Phpfox::getLib('phpfox.parse.output')->clean($this->_aVars['channel']['link']); ?>"><?php echo Phpfox::getLib('phpfox.parse.output')->split(Phpfox::getLib('phpfox.parse.output')->shorten(Phpfox::getLib('phpfox.parse.output')->clean($this->_aVars['channel']['title']), 50, '...'), 20); ?></a>
<?php else: ?>
               <a  class="channel_title" title="<?php echo Phpfox::getLib('phpfox.parse.output')->clean($this->_aVars['channel']['title']); ?>" href="<?php echo Phpfox::getLib('phpfox.parse.output')->clean($this->_aVars['channel']['link']); ?>" target="_blank"><?php echo $this->_aVars['channel']['title']; ?></a>
<?php endif; ?>
            </span>            
            <div class="channel_desctiption">
               <div class="extra_info">
<?php echo Phpfox::getLib('phpfox.parse.output')->split(Phpfox::getLib('phpfox.parse.output')->shorten(Phpfox::getLib('phpfox.parse.output')->clean($this->_aVars['channel']['summary']), 300, '...'), 20); ?>
               </div>
            </div>
<?php if (Phpfox ::getUserParam('videochannel.can_add_channels') || ( $this->_aVars['bCanAddChannelInPage'] )): ?>
            <div class="chanel_action">               		   
<?php if (isset ( $this->_aVars['channel']['isExist'] )): ?>
<?php if (isset ( $this->_aVars['sSubmitUrl'] )): ?>
                  <span id="highlight_<?php echo $this->_aVars['channel']['isExist']; ?>" class="highlight" <?php if (isset ( $this->_aVars['channel']['isBrowse'] ) && $this->_aVars['channel']['isBrowse'] == true): ?> style="display:none" <?php endif; ?> /><?php echo Phpfox::getPhrase('videochannel.this_channel_is_already_added'); ?></span>
<?php endif; ?>
<?php if (! isset ( $this->_aVars['sSubmitUrl'] )): ?>
                  <div id="js_channel_processing_<?php echo $this->_aVars['channel']['isExist']; ?>" class="channel_processing">                  
<?php echo Phpfox::getLib('phpfox.image.helper')->display(array('theme' => 'ajax/small.gif','id' => 'channel_loading','align' => 'middle','style' => 'margin-right: 10px;')); ?>
<?php echo Phpfox::getPhrase('videochannel.processing'); ?>
                  </div>
                     
                  <div class="item_bar">
                     <div class="item_bar_action_holder">
                        <a class="item_bar_action" href="javascript:void(0)"><span><?php echo Phpfox::getPhrase('videochannel.actions'); ?></span></a>
                        <ul>
                           <li><a id="js_channel_add_more_<?php echo $this->_aVars['channel']['isExist']; ?>" onclick="return autoUpdate(<?php echo $this->_aVars['channel']['isExist']; ?>, '<?php echo $this->_aVars['sModuleId']; ?>', <?php echo $this->_aVars['iItem']; ?>);" href="javascript:void(0)"><?php echo Phpfox::getPhrase('videochannel.auto_update'); ?></a></li>
                           <li><a id="js_channel_add_more_<?php echo $this->_aVars['channel']['isExist']; ?>" onclick="return editChannel(this,<?php echo $this->_aVars['channel']['isExist']; ?>,'yes', '<?php echo $this->_aVars['sModuleId']; ?>', <?php echo $this->_aVars['iItem']; ?>);" href="javascript:void(0)"><?php echo Phpfox::getPhrase('videochannel.add_more_videos'); ?></a></li>
                           <li><a id="js_channel_edit_<?php echo $this->_aVars['channel']['isExist']; ?>" onclick="return editChannel(this,<?php echo $this->_aVars['channel']['isExist']; ?>,'no', '<?php echo $this->_aVars['sModuleId']; ?>', <?php echo $this->_aVars['iItem']; ?>);" href="javascript:void(0)"><?php echo Phpfox::getPhrase('videochannel.edit'); ?></a></li>
                           <li><a id="js_channel_delete_<?php echo $this->_aVars['channel']['isExist']; ?>" onclick="if (confirm('<?php echo Phpfox::getPhrase('videochannel.are_you_sure', array('phpfox_squote' => true)); ?>')) return deleteChannel(<?php echo $this->_aVars['channel']['isExist']; ?>, '<?php echo $this->_aVars['sModuleId']; ?>', <?php echo $this->_aVars['iItem']; ?>);" href="javascript:void(0)"><?php echo Phpfox::getPhrase('videochannel.delete'); ?></a></li>
                        </ul>
                     </div>
                  </div>
<?php else: ?>
                     <div class="item_bar">                     
                        <ul>                           
                           <li>
                              <div id="js_channel_processing_<?php echo $this->_aVars['channel']['isExist']; ?>" class="channel_processing" style="margin-right: 10px">
<?php echo Phpfox::getLib('phpfox.image.helper')->display(array('theme' => 'ajax/small.gif','id' => 'channel_loading','align' => 'middle','style' => 'margin-right: 10px;')); ?>
<?php echo Phpfox::getPhrase('videochannel.processing'); ?>
                              </div>
                           </li>
                           <li><input type="button" class="button" id="js_channel_add_more_<?php echo $this->_aVars['channel']['isExist']; ?>" onclick="return autoUpdate(<?php echo $this->_aVars['channel']['isExist']; ?>, '<?php echo $this->_aVars['sModule']; ?>', <?php echo $this->_aVars['iItem']; ?>);" value="<?php echo Phpfox::getPhrase('videochannel.auto_update'); ?>"/></li>
                           <li><input type="button" class="button" class="moderation_action" id="js_channel_add_more_<?php echo $this->_aVars['channel']['isExist']; ?>" onclick="return editChannel(this,<?php echo $this->_aVars['channel']['isExist']; ?>,'yes', '<?php echo $this->_aVars['sModule']; ?>', <?php echo $this->_aVars['iItem']; ?>);" value="<?php echo Phpfox::getPhrase('videochannel.add_more_videos'); ?>"/></li>
                        </ul>
                     </div>                  
<?php endif; ?>
<?php else: ?>
                  <div class="item_bar">                     
                        <ul>
                           <li>
                              <div id="js_channel_processing_add_<?php echo $this->_aVars['channel']['channel_id']; ?>" class="channel_processing" style="margin-right: 10px">
<?php echo Phpfox::getLib('phpfox.image.helper')->display(array('theme' => 'ajax/small.gif','id' => 'channel_loading','align' => 'middle','style' => 'margin-right: 10px;')); ?>
<?php echo Phpfox::getPhrase('videochannel.processing'); ?>
                              </div>
                           </li>
                           <li><input type="button" class="button" class="moderation_action" id="js_channel_add" name="add_channel" onclick="return addChannel(this,<?php echo $this->_aVars['channel']['channel_id']; ?>, '<?php echo $this->_aVars['sModule']; ?>', <?php echo $this->_aVars['iItem']; ?>);" value="<?php echo Phpfox::getPhrase('core.add'); ?>"/></li>
                        </ul>                     
                  </div>
<?php endif; ?>
            </div>
<?php endif; ?>
         </div>
      </div>
   </div>
</div>

