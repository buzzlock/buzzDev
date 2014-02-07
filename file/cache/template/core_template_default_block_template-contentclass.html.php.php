<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php /* Cached: February 6, 2014, 10:06 pm */ ?>
<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond_Benc
 * @package 		Phpfox
 * @version 		$Id: template-contentclass.html.php 6620 2013-09-11 12:10:20Z Miguel_Espinoza $
 */
 
 

 if (! $this->_aVars['bUseFullSite']): ?>class="content_column <?php if (count ( $this->_aVars['aBlocks3'] ) || count ( $this->_aVars['aBlocks1'] ) || count ( $this->_aVars['aAdBlocks3'] ) || count ( $this->_aVars['aAdBlocks1'] )): ?> content_float<?php endif; ?> <?php if (( count ( $this->_aVars['aBlocks1'] ) || count ( $this->_aVars['aAdBlocks1'] ) ) && ( count ( $this->_aVars['aBlocks3'] ) || count ( $this->_aVars['aAdBlocks3'] ) )): ?> content3<?php endif; ?> <?php if (count ( $this->_aVars['aBlocks1'] ) || count ( $this->_aVars['aBlocks3'] ) || count ( $this->_aVars['aAdBlocks3'] )): ?> <?php if (isset ( $this->_aVars['aFilterMenus'] ) && ( count ( $this->_aVars['aBlocks3'] ) || count ( $this->_aVars['aAdBlocks3'] ) ) && ! count ( $this->_aVars['aBlocks1'] )): ?>content3<?php else: ?>content2<?php endif;  endif; ?>"<?php endif; ?>
