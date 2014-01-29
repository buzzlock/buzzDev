<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * @copyright		[YOUNETCO]
 * @author  		NghiDV
 * @package  		Module_Suggestion
 * @version 		$Id: sample.class.php 1 2011-11-25 15:29:17Z YOUNETCO $
 */
class Suggestion_Component_Block_Events extends Phpfox_Component
{
	/**
	 * Class process method which is used to execute this component.
	 */
	public function process()
	{
		   $bAllow = Phpfox::getUserParam('suggestion.enable_friend_suggestion');
			if($bAllow)       
            	$bAllow = Phpfox::getUserParam('suggestion.display_events_block');
            
                if(!$bAllow){
                    //no support this module
                    $aRows = null;
                    $sHeader = '';
                    
                }else{
                
                    $aRows = Phpfox::getService('suggestion')->getEvents();

                    if (count($aRows)==0)
                        $sHeader = '';
                    else{
                        $sHeader = Phpfox::getPhrase('suggestion.recent_events');
                    }
                    
                }
			
                $this->template()->assign(array(
                    'sHeader' => $sHeader,
                    'aRows' => $aRows,                
                    'iUserId' => Phpfox::getUserId()
                ));
                
                return 'block';  
	}

	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('suggestion.component_events_clean')) ? eval($sPlugin) : false);
	}
}

?>