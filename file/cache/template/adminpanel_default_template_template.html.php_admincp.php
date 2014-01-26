<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php /* Cached: January 26, 2014, 10:12 pm */ ?>
<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author			Raymond Benc
 * @package 		Phpfox
 * @version 		$Id: template.html.php 5581 2013-03-28 07:36:56Z Raymond_Benc $
 */
 
 

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $this->_aVars['sLocaleDirection']; ?>" lang="<?php echo $this->_aVars['sLocaleCode']; ?>">
	<head>
		<title><?php echo $this->getTitle(); ?></title>	
<?php echo $this->getHeader(); ?>
	</head>
	<body>	
		<div id="global_ajax_message"></div>
		
		<div id="top_holder">
		
		<div id="main_top_fixed">
			<div id="main_top">
				<div class="main_holder">
					<div id="main_top_inner">
						<a href="<?php echo Phpfox::getLib('phpfox.url')->makeUrl('admincp'); ?>" id="logo">AdminCP</a>
						<div id="user_info_link">
<?php echo Phpfox::getPhrase('admincp.logged_in_as', array('user' => $this->_aVars['aUserDetails'])); ?> <span class="separator">|</span> <a href="<?php echo Phpfox::getLib('phpfox.url')->makeUrl(''); ?>"><?php echo Phpfox::getPhrase('admincp.view_site'); ?></a>
						</div>
					</div>
				</div>
			</div>
		<div id="top">
		
			<div class="main_holder">
		
				<div id="top_right">
					
					

			
					<div id="admincp_search">
						<div id="admincp_search_inner">
							<div id="admincp_search_input_default_value"><?php echo Phpfox::getPhrase('admincp.search'); ?></div>
							<input type="text" name="q" value="<?php echo Phpfox::getPhrase('admincp.search'); ?>" id="admincp_search_input" class="admincp_search_input" autocomplete="off" />
							<div id="admincp_search_input_results"></div>
						</div>
					</div>			
					
					
					<div class="main_menu_holder">
						<ul class="main_menu">
<?php if (count((array)$this->_aVars['aAdminMenus'])):  foreach ((array) $this->_aVars['aAdminMenus'] as $this->_aVars['sPhrase'] => $this->_aVars['sLink']): ?>
<?php if (is_array ( $this->_aVars['sLink'] )): ?>
<?php if (count ( $this->_aVars['sLink'] )): ?>
							<li class="main_menu_link_li"><a class="main_menu_link" href="#"><?php echo Phpfox::getPhrase($this->_aVars['sPhrase']); ?></a>
								<div class="main_sub_menu">
<?php if (count((array)$this->_aVars['sLink'])):  foreach ((array) $this->_aVars['sLink'] as $this->_aVars['sPhrase2'] => $this->_aVars['sLink2']): ?>
									<div class="main_sub_menu_holder">
<?php if (is_array ( $this->_aVars['sLink2'] )): ?>
<?php if (count ( $this->_aVars['sLink2'] )): ?>
										<div class="main_sub_menu_holder_header"><?php echo Phpfox::getPhrase($this->_aVars['sPhrase2']); ?></div>
										<ul>
<?php if (count((array)$this->_aVars['sLink2'])):  foreach ((array) $this->_aVars['sLink2'] as $this->_aVars['sPhrase3'] => $this->_aVars['sLink3']): ?>
											<li><a href="<?php echo Phpfox::getLib('phpfox.url')->makeUrl("".$this->_aVars['sLink3'].""); ?>"><?php echo Phpfox::getPhrase($this->_aVars['sPhrase3']); ?></a></li>
<?php endforeach; endif; ?>
										</ul>										
<?php endif; ?>
<?php else: ?>
										<ul>
											<li><a href="<?php echo Phpfox::getLib('phpfox.url')->makeUrl("".$this->_aVars['sLink2'].""); ?>" class="group_menu_sub_clone"><?php echo Phpfox::getPhrase($this->_aVars['sPhrase2']); ?></a></li>
										</ul>
<?php endif; ?>
									</div>						
<?php endforeach; endif; ?>
									<div class="clear"></div>
								</div>
							</li>
<?php endif; ?>
<?php else: ?>
							<li><a href="<?php echo Phpfox::getLib('phpfox.url')->makeUrl(''.$this->_aVars['sLink'].''); ?>" class="main_menu_link"><?php echo Phpfox::getPhrase($this->_aVars['sPhrase']); ?></a></li>
<?php endif; ?>
<?php endforeach; endif; ?>
<?php if (is_array ( $this->_aVars['aModulesMenu'] ) && count ( $this->_aVars['aModulesMenu'] )): ?>
							<li class="main_menu_link_li"><a class="main_menu_link" href="#"><?php echo Phpfox::getPhrase('admincp.modules'); ?></a>
						<div class="main_sub_menu">	
					
						
<?php if (count((array)$this->_aVars['aModulesMenu'])):  foreach ((array) $this->_aVars['aModulesMenu'] as $this->_aVars['aModule']): ?>
<?php if (isset ( $this->_aVars['aModule']['module_id'] )): ?>
							<div class="main_sub_menu_holder">
								<div class="main_sub_menu_holder_header"><?php if (isset ( $this->_aVars['aModule']['module_image'] )): ?><img src="<?php echo $this->_aVars['aModule']['module_image']; ?>" /> <?php endif;  echo Phpfox::getLib('phpfox.locale')->translate($this->_aVars['aModule']['module_id'], 'module'); ?></a></div>
<?php if ($this->_aVars['aModule']['menu']): ?>
								<ul>
<?php if (count((array)$this->_aVars['aModule']['menu'])):  foreach ((array) $this->_aVars['aModule']['menu'] as $this->_aVars['sMenuName'] => $this->_aVars['aMenu']): ?>
									<li><a href="<?php echo Phpfox::getLib('phpfox.url')->makeUrl("admincp.".$this->_aVars['aMenu']['url'].""); ?>" class="group_menu_sub_clone"><?php echo Phpfox::getPhrase($this->_aVars['sMenuName']); ?></a></li>
<?php endforeach; endif; ?>
								</ul>
<?php endif; ?>
							</div>
<?php endif; ?>
<?php endforeach; endif; ?>
						
					
					</div>
						
							</li>					
<?php endif; ?>
						</ul>				
						<div class="clear"></div>		
					</div>					
									
				</div>
				<div class="clear"></div>
			
			</div>
			
		</div>
		
		</div>
		
		</div>
		
		<div id="main_body_holder"></div>
			<div class="main_holder">					
				<div id="js_content_container">					
					<div id="main">						
<?php if ($this->_aVars['iBreadTotal'] = count ( $this->_aVars['aBreadCrumbs'] )): ?>
						<div class="main_title_holder">
							
<?php if (count ( $this->_aVars['aBreadCrumbs'] ) == 1): ?>
						<div id="main_title_holder">
<?php if (count((array)$this->_aVars['aBreadCrumbs'])):  $this->_aPhpfoxVars['iteration']['link'] = 0;  foreach ((array) $this->_aVars['aBreadCrumbs'] as $this->_aVars['sLink'] => $this->_aVars['sCrumb']):  $this->_aPhpfoxVars['iteration']['link']++; ?>

								<h1><?php if (! empty ( $this->_aVars['sLink'] )): ?><a href="<?php echo $this->_aVars['sLink']; ?>" class="<?php if ($this->_aPhpfoxVars['iteration']['link'] == '1'): ?> first<?php endif; ?>"><?php endif;  echo Phpfox::getLib('phpfox.parse.output')->clean($this->_aVars['sCrumb']);  if (! empty ( $this->_aVars['sLink'] )): ?></a><?php endif; ?></h1>								
<?php endforeach; endif; ?>
						</div>
<?php else: ?>
<?php if ($this->_aVars['iBreadTotal'] = count ( $this->_aVars['aBreadCrumbs'] )):  endif; ?>
						<div id="breadcrumb_list">
							<ul>
<?php if (count((array)$this->_aVars['aBreadCrumbs'])):  $this->_aPhpfoxVars['iteration']['link'] = 0;  foreach ((array) $this->_aVars['aBreadCrumbs'] as $this->_aVars['sLink'] => $this->_aVars['sCrumb']):  $this->_aPhpfoxVars['iteration']['link']++; ?>

								<li><a href="<?php echo $this->_aVars['sLink']; ?>" class="<?php if ($this->_aPhpfoxVars['iteration']['link'] == '1'): ?> first<?php endif; ?>"><?php echo Phpfox::getLib('phpfox.parse.output')->clean($this->_aVars['sCrumb']); ?></a></li>
<?php if ($this->_aVars['iBreadTotal'] != $this->_aPhpfoxVars['iteration']['link']): ?><li>&raquo;</li><?php endif; ?>
<?php endforeach; endif; ?>
							</ul>
							<div class="clear"></div>
						</div>
												
							
							<div id="main_title_holder">
<?php if (isset ( $this->_aVars['aBreadCrumbTitle'] ) && count ( $this->_aVars['aBreadCrumbTitle'] )): ?>
									<h1><a href="<?php echo $this->_aVars['aBreadCrumbTitle'][1]; ?>"><?php echo Phpfox::getLib('phpfox.parse.output')->clean($this->_aVars['aBreadCrumbTitle'][0]); ?></a></h1>
<?php endif; ?>
							</div>
						
<?php endif; ?>
						
						</div>
<?php endif; ?>
						
						
<?php if (isset ( $this->_aVars['bIsModuleConnection'] ) && $this->_aVars['bIsModuleConnection'] && count ( $this->_aVars['aActiveMenus'] )): ?>
					<div id="breadcrumb_holder">
						<div id="breadcrumb_position">
							<ul id="breadcrumb_menu">
<?php if (count((array)$this->_aVars['aActiveMenus'])):  foreach ((array) $this->_aVars['aActiveMenus'] as $this->_aVars['sPhrase'] => $this->_aVars['sLink']): ?>
								<li<?php if ($this->_aVars['sMenuController'] == $this->_aVars['sLink']): ?> class="active"<?php endif; ?>><a href="<?php echo Phpfox::getLib('phpfox.url')->makeUrl($this->_aVars['sLink']); ?>"><?php echo Phpfox::getPhrase($this->_aVars['sPhrase']); ?></a></li>
<?php endforeach; endif; ?>
							</ul>
						</div>
					</div>
<?php endif; ?>
<?php if (isset ( $this->_aVars['bIsModuleConnection'] ) && $this->_aVars['bIsModuleConnection'] && count ( $this->_aVars['aActiveMenus'] )): ?>
						<div id="breadcrumb_content_holder">
<?php endif; ?>
<?php if (!$this->bIsSample):  $this->getLayout('error');  endif; ?>
<?php if (!$this->bIsSample): ?><div id="site_content"><?php if (isset($this->_aVars['bSearchFailed'])): ?><div class="message">Unable to find anything with your search criteria.</div><?php else:  $sController = "admincp.maintain/cache";  if ( Phpfox::getLib("template")->shouldLoadDelayed("admincp.maintain/cache") == true ): ?>
<div id="delayed_block_image" style="text-align:center; padding-top:20px;"><img src="http://localhost/theme/frontend/default/style/default/image/ajax/add.gif" alt="" /></div>
<div id="delayed_block" style="display:none;"><?php echo Phpfox::getLib('phpfox.module')->getFullControllerName(); ?></div>
<?php else:  Phpfox::getLib('phpfox.module')->getControllerTemplate();  endif;  endif; ?></div><?php endif; ?>
<?php if (isset ( $this->_aVars['bIsModuleConnection'] ) && $this->_aVars['bIsModuleConnection'] && count ( $this->_aVars['aActiveMenus'] )): ?>
						</div>
<?php endif; ?>
					</div>
				</div>		
				
				<div id="copyright">
<?php echo Phpfox::getParam('core.site_copyright'); ?> <?php echo ' &middot; ' . PhpFox::link(); ?>
				</div>		
						
			</div>		
<?php (($sPlugin = Phpfox_Plugin::get('theme_template_body__end')) ? eval($sPlugin) : false); ?>
<?php echo $this->_sFooter; ?>
	</body>
</html>
