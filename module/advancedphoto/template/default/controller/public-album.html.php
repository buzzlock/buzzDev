<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package 		Phpfox
 * @version 		$Id: public-album.html.php 1301 2009-12-07 15:43:49Z Raymond_Benc $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
<div class="main_break"></div>
{if count($aAlbums)}
{template file='advancedphoto.block.album-entry'}
{else}
<div class="extra_info">
	{phrase var='advancedphoto.no_public_photo_albums_found'}
</div>
{/if}