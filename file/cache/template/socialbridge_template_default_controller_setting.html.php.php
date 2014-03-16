<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php /* Cached: March 16, 2014, 7:24 pm */ ?>
<?php

 echo '
<style type="text/css">
    .socialbridge_provider_img {
        width: 248px;
        border: 1px solid #CCCCCC;
        height: 90px;
    }
    .socialbridge_provider{
        float: left;
        margin: 0 30px;
        position: relative;
    }
    .socialbridge_provider .text {
        border: 1px solid #CCCCCC;
        display: block;
        padding: 3px;
        width: 242px;
        text-align: center;
        overflow: hidden;
    }
</style>
'; ?>

<?php echo '
<script type="text/javascript">
	function confirmDisconnect(providername,link){
		if(confirm("';  echo Phpfox::getPhrase('socialbridge.are_you_sure_you_want_to_disconnect_this_account');  echo '"))
		{
			window.location = link;
		}
	}
</script>
'; ?>

<?php if (count ( $this->_aVars['aProviders'] )): ?>
<div id="privacy_holder_table" class="p_4">
    <div align="center" class="page_section_menu_holder" id="js_setting_block_connections" style="display:none">
<?php if (count((array)$this->_aVars['aProviders'])):  $this->_aPhpfoxVars['iteration']['Provider'] = 0;  foreach ((array) $this->_aVars['aProviders'] as $this->_aVars['aProvider']):  $this->_aPhpfoxVars['iteration']['Provider']++; ?>

<?php if (isset ( $this->_aVars['aProvider'] )): ?>
        <div class="socialbridge_provider">
            <a href="<?php if (isset ( $this->_aVars['aProvider']['Agent'] )):  echo Phpfox::getLib('phpfox.url')->makeUrl('socialbridge.setting');  else: ?>javascript:void(openauthsocialbridge('<?php echo Phpfox::getLib('phpfox.url')->makeUrl('socialbridge.sync', array('service' => $this->_aVars['aProvider']['name'],'status' => 'connect','redirect' => 1)); ?>'));<?php endif; ?>">
                <img src="<?php echo $this->_aVars['sCoreUrl']; ?>module/socialbridge/static/image/<?php echo $this->_aVars['aProvider']['service']; ?>.jpg" alt="<?php echo $this->_aVars['aProvider']['name']; ?>" class="socialbridge_provider_img"/>
            </a>
            <div class="text">
<?php if (isset ( $this->_aVars['aProvider']['connected'] ) && $this->_aVars['aProvider']['connected']): ?>
                <div class="socialbridge_connect_link" id="socialbridge_connect_link_<?php echo $this->_aVars['aProvider']['name']; ?>">
<?php if (isset ( $this->_aVars['aProvider']['profile']['img_url'] )): ?><img src="<?php echo $this->_aVars['aProvider']['profile']['img_url']; ?>" alt="<?php echo $this->_aVars['aProvider']['profile']['full_name']; ?>" align="left" height="32"/><?php endif; ?>
<?php echo Phpfox::getPhrase('socialbridge.connected_as', array('full_name' => '')); ?> <?php echo Phpfox::getLib('phpfox.parse.output')->shorten(Phpfox::getLib('phpfox.parse.output')->clean($this->_aVars['aProvider']['profile']['full_name']), 18); ?><br/>
                    <a href="#" onclick="return confirmDisconnect('<?php echo $this->_aVars['aProvider']['service']; ?>','<?php echo Phpfox::getLib('phpfox.url')->makeUrl('socialbridge.setting', array('disconnect' => $this->_aVars['aProvider']['service'])); ?>');"><?php echo Phpfox::getPhrase('socialbridge.click_here'); ?></a> <?php echo Phpfox::getPhrase('socialbridge.to'); ?> <?php echo Phpfox::getPhrase('socialbridge.disconnect'); ?>.
                </div>
<?php else: ?>
                <div class="socialbridge_connect_link" id="socialbridge_connect_link_<?php echo $this->_aVars['aProvider']['name']; ?>">
                    <a href="javascript:void(openauthsocialbridge('<?php echo Phpfox::getLib('phpfox.url')->makeUrl('socialbridge.sync', array('service' => $this->_aVars['aProvider']['service'],'status' => 'connect','redirect' => 1)); ?>'));"><?php echo Phpfox::getPhrase('socialbridge.click_here'); ?></a> <?php echo Phpfox::getPhrase('socialbridge.to'); ?> <?php echo Phpfox::getPhrase('socialbridge.connect'); ?>.
                </div>
<?php endif; ?>
            </div>
        </div>
<?php if (is_int ( $this->_aPhpfoxVars['iteration']['Provider'] / 3 ) || Phpfox ::isMobile()): ?>
        <div class="clear"></div>
<?php endif; ?>
<?php endif; ?>
<?php endforeach; endif; ?>
    </div>
<?php (($sPlugin = Phpfox_Plugin::get('socialbridge.template_controller_setting')) ? eval($sPlugin) : false); ?>
</div>
<?php if (! empty ( $this->_aVars['sTab'] )):  echo '
<script type="text/javascript">
    $Behavior.pageSectionMenuRequest = function() {
        $Core.pageSectionMenuShow(\'#js_setting_block_';  echo $this->_aVars['sTab'];  echo '\');
    }
</script>
'; ?>

<?php endif;  else: ?>
<div class="pulic_message"><?php echo Phpfox::getPhrase('socialbridge.there_are_no_social_providers_were_enable_please_contact_to_admin_site_to_get_more_information'); ?></div>
<?php endif; ?>
