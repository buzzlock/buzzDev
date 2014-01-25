<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php /* Cached: January 24, 2014, 11:11 pm */ ?>
<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author			Raymond Benc
 * @package 		Phpfox
 * @version 		$Id: template.html.php 5861 2013-05-10 08:36:45Z Raymond_Benc $
 */
 
 

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $this->_aVars['sLocaleDirection']; ?>" lang="<?php echo $this->_aVars['sLocaleCode']; ?>">
	<head>
		<title><?php echo $this->getTitle(); ?></title>	
<?php echo $this->getHeader(); ?>
<?php echo $this->_sFooter; ?>
	<?php echo '
		<script type="text/javascript">
		$Core.installer =
		{
			resize: function()
			{
				var iTop = (($(window).height() - $(\'#container\').height()) / 2);
				var iLeft = (($(window).width() - $(\'#container\').width()) / 2);
				
				if (iTop > 0)
				{
					$(\'#container\').css(\'top\', iTop);
				
					// alert((($(window).height() - $(\'#container\').height()) / 2));
				}
				
				if (iLeft > 0)
				{
					$(\'#container\').css(\'left\', iLeft);
				}
				
				$(\'#container\').show();			
			}
		}
		
		$(document).ready(function()
		{
			$(\'#install_form\').submit(function()
			{
				$(\'#button\').attr(\'disabled\', true);
				$(\'#button\').val(\'Processing...\');
				
				return true;
			});			
			/*
			$(window).bind(\'resize\', function() 
			{
				$Core.installer.resize();	
			});
			
			$Core.installer.resize();
			*/
		});		
		</script>
	'; ?>

	</head>
	<body>
		<div id="container">				
			<div id="main">
				<div class="left">
					<div class="logo">
						<a href="http://www.phpfox.com/" target="_blank"><?php echo Phpfox::getLib('phpfox.image.helper')->display(array('theme' => 'layout/phpfox-logo-dark.png','alt' => '')); ?></a>
					</div>				
					<ul class="step">
<?php if (count((array)$this->_aVars['aSteps'])):  foreach ((array) $this->_aVars['aSteps'] as $this->_aVars['aStep']): ?>
						<li<?php if ($this->_aVars['aStep']['is_active']): ?> class="active"<?php endif; ?>><?php echo $this->_aVars['aStep']['count']; ?>. <?php echo $this->_aVars['aStep']['name']; ?></li>
<?php endforeach; endif; ?>
					</ul>
				</div>
				<div class="right">
<?php if ($this->_aVars['sPublicMessage']): ?>
				<div class="public_message" id="public_message">
<?php echo $this->_aVars['sPublicMessage']; ?>
				</div>
				<script type="text/javascript">
					$Behavior.theme_install_template = function()
					{
						$('#public_message').show('slow');
					};
				</script>
<?php endif; ?>
				<div id="core_js_messages">
<?php if (count ( $this->_aVars['aErrors'] )): ?>
<?php if (count((array)$this->_aVars['aErrors'])):  foreach ((array) $this->_aVars['aErrors'] as $this->_aVars['sErrorMessage']): ?>
					<div class="error_message"><?php echo $this->_aVars['sErrorMessage']; ?></div>
<?php endforeach; endif; ?>
<?php unset($this->_aVars['sErrorMessage']); ?>
<?php endif; ?>
				</div>
<?php $this->getLayout($this->_aVars['sTemplate']); ?>
				</div>
				<div class="clear"></div>
			</div>
			<div id="copyright">
<?php echo Phpfox::getLib('phpfox.image.helper')->display(array('theme' => 'layout/phpfox-emblem.png','style' => 'vertical-align:middle;')); ?> <?php echo ' &middot; ' . PhpFox::link(); ?>
			</div>
		</div>
		<script type="text/javascript">
			$Core.init();
		</script>
	</body>
</html>
