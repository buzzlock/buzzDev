<?php

defined('PHPFOX') or exit('NO DICE!');


class Module_AdvancedMarketplace
{	
	public static $aTables = array(
		'advancedmarketplace',
		'advancedmarketplace_category',
		'advancedmarketplace_category_data',
		'advancedmarketplace_image',
		'advancedmarketplace_invite',
		'advancedmarketplace_invoice',
		'advancedmarketplace_text'
	);
	
	public static $aInstallWritable = array(
		'file/pic/advancedmarketplace/'
	);		
}

?>