<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php /* Cached: February 6, 2014, 9:56 pm */ ?>
<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond_Benc
 * @package 		Phpfox
 * @version 		$Id: template-copyright.html.php 3056 2011-09-09 18:28:44Z Raymond_Benc $
 */
 
 

 echo Phpfox::getParam('core.site_copyright'); ?> &middot; <a href="#" id="select_lang_pack"><?php if (Phpfox ::getParam('language.display_language_flag') && ! empty ( $this->_aVars['sLocaleFlagId'] )): ?><img src="<?php echo $this->_aVars['sLocaleFlagId']; ?>" alt="<?php echo $this->_aVars['sLocaleName']; ?>" class="v_middle" /> <?php endif;  echo $this->_aVars['sLocaleName']; ?></a> <?php echo ' &middot; ' . PhpFox::link(); ?>
