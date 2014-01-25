<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');
/**
 * 
 * 
 * @copyright       [YOUNET_COPYRIGHT]
 * @author          YouNet Company
 * @package         YouNet_Event
 */
class Fevent_Component_Block_Category extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
        if($this->request()->get('req2')=='category')
        {
            $iCurrentCategoryId = $this->request()->get('req3');
        }
        else
        {
            $iCurrentCategoryId = 0;
        }
        
        $sHtmlCat = Phpfox::getService('fevent.multicat')->getMenu($iCurrentCategoryId, null, null);
		
		$this->template()->assign(array(
				'sHeader' => Phpfox::getPhrase('fevent.categories'),
                'sHtmlCat' => $sHtmlCat
			)
		);
		
		return 'block';
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('fevent.component_block_category_clean')) ? eval($sPlugin) : false);
	}
}

?>
