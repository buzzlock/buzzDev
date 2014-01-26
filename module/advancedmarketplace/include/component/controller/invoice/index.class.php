<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

define('PHPFOX_SKIP_POST_PROTECTION', true);

/**
 * 
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond_Benc
 * @package 		Phpfox_Component
 * @version 		$Id: index.class.php 1558 2010-05-04 12:51:22Z Raymond_Benc $
 */
class AdvancedMarketplace_Component_Controller_Invoice_Index extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		Phpfox::isUser(true);
		
		$aCond = array();
		
		$aCond[] = 'AND mi.user_id = ' . Phpfox::getUserId();
		
		list($iCnt, $aInvoices) = Phpfox::getService('advancedmarketplace')->getInvoices($aCond);
		
		$this->template()->setTitle(Phpfox::getPhrase('advancedmarketplace.advancedmarketplace_invoices'))	
			->setBreadcrumb(Phpfox::getPhrase('advancedmarketplace.advancedmarketplace'), $this->url()->makeUrl('advancedmarketplace'))
			->setBreadcrumb(Phpfox::getPhrase('advancedmarketplace.invoices'), null, true)
			->setHeader('cache', array(
					'table.css' => 'style_css'
				)
			)					
			->assign(array(
					'aInvoices' => $aInvoices
				)
			);
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('advancedmarketplace.component_controller_invoice_index_clean')) ? eval($sPlugin) : false);
	}
}

?>