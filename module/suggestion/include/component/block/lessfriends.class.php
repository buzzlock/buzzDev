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
class Suggestion_Component_Block_Lessfriends extends Phpfox_Component
{
	/**
	 * Class process method which is used to execute this component.
	 */
	public function process()
	{

                $sSuggest = Phpfox::getPhrase('suggestion.suggest_to_friends_2');                                
                
                $bAllow = (Phpfox::getUserParam('suggestion.enable_friend_recommend'));                
                $bAllow = $bAllow || (Phpfox::getUserParam('suggestion.enable_friend_suggestion'));
                $bAllow = $bAllow && Phpfox::getUserParam('suggestion.display_fewer_friend_block');
				if(!Phpfox::getUserParam('suggestion.enable_friend_suggestion'))
					$bAllow=Phpfox::getUserParam('suggestion.enable_friend_suggestion');
                $bIsAllowSuggestion = Phpfox::getUserParam('suggestion.enable_friend_suggestion');
                
                if ($bAllow){
                    //get total random friends
                    $aFriends = Phpfox::getService('suggestion')->getLessFriendsList(Phpfox::getUserId());
                }else{
                    $aFriends = array();
                }
                 
               
                if (count($aFriends)){

                    $sLink = Phpfox::permalink(Phpfox::getUserBy('full_name'), null);
                    $sTitle = Phpfox::getPhrase('suggestion.will_get_friends_suggestion_from');
                    $sLinkUser = '<a target="_blank" href="'.$sLink.'">'.Phpfox::getUserBy('full_name').'</a>';
                    $sTitle = preg_replace('/{{friend_name}}/', $sLinkUser, $sTitle);
                    $sHeader = Phpfox::getPhrase('suggestion.help_your_friends_find_more_friends_2');
                    $aFooter = array(
                                Phpfox::getPhrase('suggestion.view_more') => $this->url()->makeUrl('friend')   
                            );
                    foreach($aFriends as &$aFriend){                
                        $aFriend['url'] = '<a href="#" class="suggest-user" rel="'.$aFriend['user_id'].'">'.$sSuggest.'</a>';
                        $aFriend['user_link'] = Phpfox::getService('suggestion')->getUserLink($aFriend['user_id'], false);                        
                    }
                }else{
                    $sTitle = '';
                    $sHeader = '';
                    $aFooter = array();
                }
                $this->template()->assign(array(
                    'sHeader' => $sHeader,
                    'aFooter' => $aFooter, 
                    'sTitle' => $sTitle,
                    'bIsAllowSuggestion' => (int)$bIsAllowSuggestion,
                    'iCurrentUserId' => Phpfox::getUserId(),
                    'aFriends' => $aFriends                    
                ));
                return 'block';  
	}

	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('suggestion.component_block_friends_clean')) ? eval($sPlugin) : false);
	}
}

?>