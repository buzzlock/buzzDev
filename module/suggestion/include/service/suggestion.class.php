<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

/**
 * @copyright		[YOUNETCO]
 * @author  		NghiDV
 * @package  		Module_Suggestion
 * @version 		$Id: Suggestion.class.php 1 2011-11-25 15:29:17Z YOUNETCO $
 */
class Suggestion_Service_Suggestion extends Phpfox_Service {

    /**
     * Class constructor
     */
    public function __construct() {
        $this->_sTable = Phpfox::getT('suggestion');
    }
    
    /*
     * check is page with short link
     */
    public function isPage($sUrl){
        
        $aPage = $this->database()->select('*')
                ->from(Phpfox::getT('pages_url'))
                ->where('vanity_url = \'' . $this->database()->escape($sUrl) . '\'')
                ->execute('getSlaveRow');

        if (!isset($aPage['page_id']))
        {
                return false;
        }       
        
        return $aPage;
    }
    
    public function getModuleName($sModule){
        
        $sModulePhrase = $this->database()->select('m.phrase_var_name')->from(Phpfox::getT('module'),'m')->where('module_id = "' . $sModule .'" AND product_id != "phpfox"')->execute('getField');
        
        if ($sModulePhrase != null && Phpfox::isModule($sModule))
            return ucfirst(Phpfox::getPhrase($sModule.'.'.$sModulePhrase));
        else
        {
            $sModulePhrase = $this->database()->select('m.phrase_var_name')->from(Phpfox::getT('module'),'m')->where('module_id = "' . $sModule .'" AND product_id = "phpfox"')->execute('getField');
            if ($sModulePhrase != null && Phpfox::isModule($sModule))
            {
                $phares = ucfirst(Phpfox::getPhrase($sModule.'.'.$sModulePhrase));
                $phares = trim($phares,"s");
                return $phares;
            }
            else
            {
                return ucfirst($sModule);
            }
        }
    }
    
    
    public function getUserList()
    {
        
        /*dont get friends list in filter of search in suggestion/recommendation block.*/
        return;
        
/*        static $aRows = array();


        if ($aRows) 		{
            return $aRows;
        }


        $aRows = $this->database()->select('fl.list_id, fl.name, COUNT(f.friend_id) AS used')
                ->from(Phpfox::getT('friend_list'), 'fl')

                ->leftJoin(Phpfox::getT('friend'), 'f', 'f.list_id = fl.list_id AND f.user_id = fl.user_id')
                ->where('fl.user_id = ' . (int) Phpfox::getUserId())
                ->group('fl.list_id')
                ->order('fl.name ASC')
                ->execute('getSlaveRows');*/

        
    }
    
    public function isNotificationMessage($sModule){
        /*check module name does not have string 'itemLiked' and 'friend'; 
         * expect feed item like and just friend together
         * expect comment not show suggestion
         */
        $bHasItemLiked = strpos($sModule, 'itemLiked');
        $bHasFriend = strpos($sModule, 'friend');
        $bHasComment = strpos($sModule, 'comment');
        if ($bHasFriend !== FALSE || $bHasItemLiked !== FALSE || $bHasComment !== FALSE)
            return true;
        return false;
    }
    
    public function getPhotoDetail($iPhotoId){
        return $this->database()->select('p.title')->from(Phpfox::getT('photo'), 'p')->where('photo_id = ' . (int)$iPhotoId)->execute('getRow');
    }
    
    public function getFoxFeedsProDetail($iFoxFeedsProId){
        return $this->database()->select('p.item_alias')->from(Phpfox::getT('ynnews_items'), 'p')->where('item_id = ' . (int)$iFoxFeedsProId)->execute('getRow');
    }
    
    public function getContestDetail($iContestId){
        return $this->database()
                ->select('c.contest_name')
                ->from(Phpfox::getT('contest'), 'c')
                ->where('contest_id = ' . (int)$iContestId)
                ->execute('getRow');
    }
    
    public function getFundRaisingDetail($iItemId){
        return $this->database()
                ->select('fc.title')
                ->from(Phpfox::getT('fundraising_campaign'), 'fc')
                ->where('campaign_id = ' . (int)$iItemId)
                ->execute('getRow');
    }
    
    public function getCouponDetail($iItemId){
        return $this->database()
                ->select('c.title')
                ->from(Phpfox::getT('coupon'), 'c')
                ->where('coupon_id = ' . (int)$iItemId)
                ->execute('getRow');
    }
    
    /*
     * get user by field
     */

    public function getUserBy($sField, $sValue){
        return $this->database()->select('*')->from(Phpfox::getT('user'))->where($sField . ' = "' . $sValue .'" AND user_name != ""' )->execute('getRow');
    }
    /*
     * get random pages to share ...
     */

    public function getPages() {
        if (Phpfox::isModule('pages')){
            //limit pages appear in block
            $iLimit = (int) Phpfox::getUserParam('suggestion.number_of_entries_display_in_blocks');

            //one month in seconds
//            $iOneMonth = 30 * 24 * 60 * 60;

            $aRows = $this->database()
                    ->select('*')
                    ->from(Phpfox::getT('pages'), 'p')
                    //->where('p.user_id != ' . (int) Phpfox::getUserId() . ' AND ' . PHPFOX_TIME . ' - time_stamp < ' . $iOneMonth)
                    ->where('p.view_id = 0')
                    ->order('p.time_stamp DESC')
                    ->limit($iLimit)
                    ->execute('getRows');

            if (count($aRows) > 0) {
                foreach ($aRows as &$aRow) {
                    $sLink = Phpfox::getService('pages')->getUrl($aRow['page_id'], $aRow['title']);

                    $aRow['title_link'] = Phpfox::getService('suggestion.url')->makeLink($sLink, $aRow['title']);

                    $aUser = Phpfox::getService('suggestion')->getUser($aRow['user_id']);
                    $aUser['suffix'] = '_50_square';
                    $aUser['max_width'] = '50';
                    $aUser['max_height'] = '50';
                    $aUser['user'] = $aUser;

                    $img = '<span class="thumb">' . Phpfox::getLib('phpfox.image.helper')->display($aUser) . '</span>';
					
					$pattern = '/(.+)href="(.+)" title(.+)/i';
					$replacement = 'href="${2}';
					$strtmp = preg_replace($pattern, $replacement, $img);
					$img = str_replace($strtmp,'href="'.$sLink,$img);
					
                    $aRow['img'] = $img;                

                    $aRow['link'] = $sLink;

                    $aRow['encode_link'] = base64_encode($aRow['title_link']);
                    
                    $aRow['isAllowSuggestion'] = Phpfox::getUserParam('suggestion.enable_friend_suggestion') && Phpfox::getService('suggestion')->isSupportModule('pages');

                    //process privacy
                    $iPrivacy = $aRow['privacy'];
                    $iUserId = $aRow['user_id'];
                    $iFriendId = Phpfox::getUserId();
                    $aRow['is_right'] = (int)Phpfox::getService('suggestion')->isRightPrivacy($iPrivacy, $iUserId, $iFriendId);
                    
                    $isUserViewSuggestion = Phpfox::getService('suggestion')->isUserViewSuggestion($iFriendId, 'suggestion_pages', $aRow['page_id']);
                    
                    //if recent is belong current user, not display link join pages;
                    if ($aRow['user_id'] == Phpfox::getUserId()){
                        $aRow['display_join_link'] = false;
                        $aRow['user_link'] = Phpfox::getPhrase('suggestion.me');
                    }elseif(!$isUserViewSuggestion){
                        $aRow['display_join_link'] = true;
                        $aRow['user_link'] = Phpfox::getService('suggestion')->getUserLink($aRow['user_id']);
                    }else{
                        $aRow['display_join_link'] = false;
                        $aRow['user_link'] = Phpfox::getService('suggestion')->getUserLink($aRow['user_id']);
                    }
                }
            }
            return $aRows;
        }
    }

    /*
     * check FriendID is right privacy with UserID
     */

    public function isRightPrivacy($iPrivacy, $iUserId, $iFriendId) {
        
        if ($iUserId == $iFriendId) return true;
        
        switch ($iPrivacy) {
            case 0:
                return true;
                break;
            case 1://is friends
                return Phpfox::getService('suggestion')->isMyFriend($iFriendId, $iUserId);
                break;
            case 2://get friends of friends
                $aRows = Phpfox::getService('suggestion')->getFriendsOfFriends($iUserId);
                $sFriendsId = ',' . implode(',', $aRows) . ',';
                if (strpos($sFriendsId, ',' . $iFriendId . ',') !== FALSE) {
                    return true;
                }
                return false;
                break;
            case 3://only me
                return false;
                break;
        }
    }

    /*
     * get friends list of user's friends
     */

    public function getFriendsOfFriends($iUserId) {
        $aFriendsList = Phpfox::getService('suggestion')->getFriendsOfUserId($iUserId);
        $aRet = array();

        if (count($aFriendsList) > 0) {
            foreach ($aFriendsList as $k => $iFriendId) {

                $_aFriendsList = Phpfox::getService('suggestion')->getFriendsOfUserId($iFriendId['friend_user_id']);
                if (count($_aFriendsList) > 0) {

                    foreach ($_aFriendsList as $_k => $_iFriendId)
                        $aRet[$_iFriendId['friend_user_id']] = $_iFriendId['friend_user_id'];
                }
            }
        }

        return $aRet;
    }

    /*
     * get friends list of userid
     */

    public function getFriendsOfUserId($iUserId) {
        $aRows = $this->database()
                ->select('friend_user_id')
                ->from(Phpfox::getT('friend'), 'f')
                ->where('f.user_id = ' . (int) $iUserId)
                ->execute('getRows');
        return $aRows;
    }

    /*
     * get random events still not end date
     */

    public function getEvents() {
        if (Phpfox::isModule('event') || Phpfox::isModule('fevent')) {
            
            (Phpfox::isModule('fevent')==true ? $sTableName = 'fevent' : $sTableName = 'event' );
            
            //get country iso of current user
            $sCountryIso = Phpfox::getUserBy('country_iso');
            
            $iLimit = (int) Phpfox::getUserParam('suggestion.number_of_entries_display_in_blocks');
            $aRows = $this->database()
                    ->select('*')
                    ->from(Phpfox::getT($sTableName), 'e')
                    //->where('e.end_time > ' . PHPFOX_TIME . ' AND user_id != ' . (int) Phpfox::getUserId())//get event not expect current user
                    ->where('e.end_time > ' . PHPFOX_TIME . ' AND e.country_iso ="' . $sCountryIso . '"')
                    ->order('e.time_stamp DESC')
                    ->limit($iLimit)
                    ->execute('getRows');
            
                        
            if (count($aRows) < $iLimit){
                $iRemainItems = $iLimit - count($aRows);
                //get remain items with other location
                $aRows2 = $this->database()
                    ->select('*')
                    ->from(Phpfox::getT($sTableName), 'e')
                    //->where('e.end_time > ' . PHPFOX_TIME . ' AND user_id != ' . (int) Phpfox::getUserId())//get event not expect current user
                    ->where('e.end_time > ' . PHPFOX_TIME . ' AND e.country_iso !="' . $sCountryIso . '"')
                    ->order('e.time_stamp DESC')
                    ->limit($iRemainItems)
                    ->execute('getRows');
                $aRows = array_merge($aRows, $aRows2);
            }
            
            if (count($aRows) > 0) {
                foreach ($aRows as &$aRow) {
                    $sCallback = $sTableName.'.callback';
                    $sLink = Phpfox::getService($sCallback)->getFeedRedirect($aRow['event_id']);
                    
                    $aRow['title_link'] = Phpfox::getService('suggestion.url')->makeLink($sLink, $aRow['title']);
                    //$aRow['join_link'] = Phpfox::getService('suggestion.url')->makeLink($sLink, Phpfox::getPhrase('suggestion.join_event'));
                    
                    $aUser = Phpfox::getService('suggestion')->getUser($aRow['user_id']);
                    $aUser['suffix'] = '_50_square';
                    $aUser['max_width'] = '50';
                    $aUser['max_height'] = '50';
                    $aUser['user'] = $aUser;
                    $img = '<span class="thumb">' . Phpfox::getLib('phpfox.image.helper')->display($aUser) . '</span>';
					$pattern = '/(.+)href="(.+)" title(.+)/i';
					$replacement = 'href="${2}';
					$strtmp = preg_replace($pattern, $replacement, $img);
					$img = str_replace($strtmp,'href="'.$sLink,$img);

                    $aRow['img'] = $img;

                    $aRow['link'] = $sLink;
                    
                    $aRow['encode_link'] = base64_encode($aRow['title_link']);

                    $aRow['isAllowSuggestion'] = Phpfox::getUserParam('suggestion.enable_friend_suggestion') && Phpfox::getService('suggestion')->isSupportModule($sTableName);
                    
                    //process privacy
                    $iPrivacy = $aRow['privacy'];
                    $iUserId = $aRow['user_id'];
                    $iFriendId = Phpfox::getUserId();
                    
                    $isUserViewSuggestion = Phpfox::getService('suggestion')->isUserViewSuggestion($iFriendId, 'suggestion_'.$sTableName, $aRow['event_id'], $sTableName);
                        
                    //if item is belong current user return true
                    if ($iUserId != $iFriendId)
                        $aRow['is_right'] = (int)Phpfox::getService('suggestion')->isRightPrivacy($iPrivacy, $iUserId, $iFriendId);
                    else
                        $aRow['is_right'] = 1;
                    
                    //if recent is belong current user, not display link join pages;
                    if ($aRow['user_id'] == Phpfox::getUserId()){
                        $aRow['display_join_link'] = false;
                        $aRow['user_link'] = Phpfox::getPhrase('suggestion.me');
                    }elseif(!$isUserViewSuggestion){
                        $aRow['display_join_link'] = true;
                        $aRow['user_link'] = Phpfox::getService('suggestion')->getUserLink($aRow['user_id']);                       
                    }else{
                        $aRow['display_join_link'] = false;
                        $aRow['user_link'] = Phpfox::getService('suggestion')->getUserLink($aRow['user_id']);                       
                    }
                }
            }
            return $aRows;
        }
    }

    public function isMyFriend($iFriendId, $iUserId = '') {
        if ($iUserId === '') {
            $aRow = $this->database()
                    ->select('*')
                    ->from(Phpfox::getT('friend'), 'f')
                    ->where('f.user_id = ' . (int) Phpfox::getUserId() . ' AND f.friend_user_id = ' . (int) $iFriendId)
                    ->execute('getRow');
            if (count($aRow) > 0)
                return true;
            return false;
        }else {
            $aRow = $this->database()
                    ->select('*')
                    ->from(Phpfox::getT('friend'), 'f')
                    ->where('f.user_id = ' . (int) $iUserId . ' AND f.friend_user_id = ' . (int) $iFriendId)
                    ->execute('getRow');
            if (count($aRow) > 0)
                return true;
            return false;
        }
    }

    public function getPrivateData($iUserId) {
        $aRow = $this->database()->select('*')
                ->from($this->_sTable, 's')
                ->where('s.user_id = ' . $iUserId . ' AND s.friend_user_id = 0 AND s.item_id = 0')
                ->order('s.suggestion_id DESC')
                ->execute('getRow');
        return $aRow;
    }

    public function getSuggestionData($iSuggestionId) {
        $aRows = $this->database()->select('DISTINCT(item_id) as item_id, processed')->from(Phpfox::getT('suggestion'))->where('suggestion_id = ' . (int) $iSuggestionId)->execute('getRows');
        return (array) $aRows;
    }

    public function getSearchKey($sView) {
        //check if current view return key or unset Key
        
        
        if (isset($_SESSION['suggestion']['current_view']) && $_SESSION['suggestion']['current_view'] != $sView){
            Phpfox::getService('suggestion')->resetSearchKey();
            return '';
        }
        
        if (isset($_SESSION[Phpfox::getParam('core.session_prefix')]['search']['suggestion'])) {//is choose keywords
            foreach ($_SESSION[Phpfox::getParam('core.session_prefix')]['search']['suggestion'] as $key => $value) {
                if (isset($_SESSION['suggestion']['keys']))
                    unset($_SESSION['suggestion']['keys']);
                if ($value[0] != '') {
                    $_SESSION['suggestion']['keys'] = $value[0];
                    $_SESSION['suggestion']['current_view'] = $sView;
                    return $value[0] . '';
                } else {
                    return '';
                }
            }
        } else {
            if (isset($_SESSION['suggestion']['keys']))
                return $_SESSION['suggestion']['keys'];
            else
                return '';
        }
    }
    
    public function resetSearchKey(){
        if (isset($_SESSION[Phpfox::getParam('core.session_prefix')]['search']['suggestion'])) {
            unset($_SESSION[Phpfox::getParam('core.session_prefix')]['search']['suggestion']);
        }
                
        if (isset($_SESSION['suggestion']['keys']))
            unset($_SESSION['suggestion']['keys']);
        
        if(isset($_SESSION['suggestion']['current_view']))
            unset($_SESSION['suggestion']['current_view']);
    }
    
    public function getMessageStruct($sModule) {
        switch ($sModule) {
            case 'suggestion_friend';
                return Phpfox::getPhrase('suggestion.message_friend_suggestion');
                break;
            case 'suggestion_recommendation';
                return Phpfox::getPhrase('suggestion.message_friend_recommendation');
                break;
        }
    }

    /*
     * convert from short module to long 'friend => suggestion_suggestion' or from long to short suggestion_suggestion=>friend
     */

    public function convertModule($sModule) {
        $sModule = strtolower($sModule);
        if (strpos($sModule, 'suggestion_') === FALSE) {
            return 'suggestion_' . $sModule;
        } else {
            $sShortModule = preg_replace('/suggestion_/', '', $sModule);
            return preg_replace('/(suggestion|recommendation)/', 'friend', $sShortModule);
        }
    }

    public function isSupportModule($sModule) {
        /*hard code for module link*/
        if ($sModule == 'link') return true;
        
        $sSupportModule = Phpfox::getUserParam('suggestion.support_module');
        
        if ($sSupportModule != '') {
            $sModule = strtolower($sModule);   
            if ($sModule == 'friend') return true;//default support module friend
            $sSupportModule = strtolower($sSupportModule);
            if($sModule!="")
            {
                if (strpos($sSupportModule, $sModule) !== FALSE && Phpfox::isModule($sModule)) {
                    return true;
                }
            }
        }
        return false;
    }

    public function buildMenu() {
        $aFilterMenu = array();

        if (!defined('PHPFOX_IS_USER_PROFILE')) {
            $aFilterMenu[Phpfox::getPhrase('suggestion.all_suggestions')] = 'all';
            $aFilterMenu[Phpfox::getPhrase('suggestion.my_suggestions')] = 'my';
            $aFilterMenu[Phpfox::getPhrase('suggestion.friend_suggestion')] = 'friends';
            $aFilterMenu[Phpfox::getPhrase('suggestion.incoming_suggestions')] = 'incoming';
            $aFilterMenu[Phpfox::getPhrase('suggestion.pending_suggestions')] = 'pending';
//			$aFilterMenu[] = true; //breakline
        }
        Phpfox::getLib('template')->buildSectionMenu('suggestion', $aFilterMenu);
    }

    public function getTotalIncomingSuggestion($iUserId) {
        $aRows = $this->database()->select('count(*) as total')
                ->from(Phpfox::getT('suggestion'), 's')
                ->where('s.processed = 0 AND s.friend_user_id = ' . $iUserId)
                ->execute('getRow')
        ;
        if (count($aRows) > 0)
            return $aRows['total'];
        return 0;
    }

    public function getUserLink($iUserId, $_blankpage = true) {
        $aUser = Phpfox::getService('user')->getUser($iUserId, 'u.full_name, u.user_name');
        if ($_blankpage==true) {
            $target = "target='_blank'";
        }else
            $target = "";
        $sLink = Phpfox::permalink($aUser['user_name'], null);
        return "<a href='" . $sLink . "' " . $target . ">" . $aUser['full_name'] . "</a>";
    }

	public function getSuggestionPopup($_notification)
	{
		$aRows=phpfox::getLib("database")->select('*')
		->from(phpfox::getT('suggestion_setting'))
		->where('user_id='.phpfox::getUserId().' and user_notification="'.$_notification.'"')
		->execute('getSlaveRows');
		return $aRows;
		
	}

    public function isAllowSuggestionPopup() {
        //$aRow = Phpfox::getService('user.privacy')->get();
        //return !isset($aRow[0]['notification']['suggestion.enable_system_suggestion']);
        if(count($this->getSuggestionPopup("suggestion.enable_system_suggestion"))>0)
		{
			return 0;
		}
		return 1;
    }

    public function isAllowContentSuggestionPopup() {
        if(count($this->getSuggestionPopup("suggestion.enable_content_suggestion_popup")) > 0)
		{
			return 0;
		}
		return 1;
    }

    public function isAllowRecommendationPopup() {
        //$aRow = Phpfox::getService('user.privacy')->get();
        //return !isset($aRow[0]['notification']['suggestion.enable_system_recommendation']);
        if(count($this->getSuggestionPopup("suggestion.enable_system_recommendation"))>0)
		{
			return 0;
		}
		return 1;
    }

    public function getUser($iUserId) {
        $aUser = $this->database()
                ->select(Phpfox::getUserField())
                ->from(Phpfox::getT('user'), 'u')
                ->where('u.user_id = ' . (int) $iUserId)
                ->execute('getRow');
        return $aUser;
    }

    public function getMutualFriends($iUserId, $iLimit=0) {
        if ($iUserId > 0 && (int) Phpfox::getUserId() > 0) {
            $aRows = $this->database()->select('f.friend_user_id as id')
                    ->from(Phpfox::getT('friend'), 'f')
                    ->innerJoin(Phpfox::getT('friend'), 'f2', 'f.user_id = ' . $iUserId . ' AND f2.friend_user_id = f.friend_user_id AND f2.user_id = ' . Phpfox::getUserId())
            ;

            if ($iLimit > 0) {
                $aRows = $this->database()
                        ->limit($iLimit)
                        ->execute('getRows');
            } else {
                $aRows = $this->database()
                        ->execute('getRows');
            }
            return $aRows;
        }
        return array();
    }

    public function getSuggestionDetailByUserId($iUserId, $iFriendId, $sModule, $iItemid) {
        $iUserId = (int) $iUserId;
        $iFriendId = (int) $iFriendId;
        $iItemid = (int) $iItemid;
        $sModule = $sModule . '';

        $aRow = $this->database()->select('*')
                ->from(Phpfox::getT('suggestion'), 's')
                ->where('s.user_id = ' . $iUserId . ' AND s.module_id="' . $sModule . '" AND s.item_id=' . $iItemid . ' AND s.friend_user_id = ' . $iFriendId)
                ->order('s.time_stamp DESC')
                ->execute('getRow')
        ;

        return $aRow;
    }

    public function isUserViewSuggestion($iFriendId, $sModule, $iItemid, $sTableName='event') {
        
        $iFriendId = (int) $iFriendId;
        $iItemid = (int) $iItemid;
        $sModule = $sModule . '';

        $aRow = $this->database()->select('s.suggestion_id')
                ->from(Phpfox::getT('suggestion'), 's')
                ->where('s.module_id="' . $sModule . '" AND s.item_id=' . $iItemid . ' AND s.friend_user_id = ' . $iFriendId . ' AND processed = 1')
                ->execute('getRow')
        ;
        /*check if user like this item*/
        $aType = explode('_', $sModule);
        $sType = $aType[1];
        
        $aLike = $this->database()->select('*')
                ->from(Phpfox::getT('like'), 'l')                
                ->where('l.type_id = \'' . $this->database()->escape($sType) . '\' AND l.item_id = ' . (int) $iItemid . ' AND user_id = ' . (int)$iFriendId)
                ->execute('getRow');
        
        /*check if user join events*/
        if ($sModule == 'suggestion_event' || $sModule == 'suggestion_fevent'){
            $aEventInvite = $this->database()
                            ->select('invite_id')
                            ->from(Phpfox::getT($sTableName.'_invite'), 'e')
                            ->where('e.invited_user_id = ' . (int)$iFriendId . ' AND event_id = ' . (int)$iItemid)
                            ->execute('getRow');
        }else{
            $aEventInvite = array();
        }
        if (count($aRow)>0 || count($aLike)>0 || count($aEventInvite)>0) return true;
        return false;
    }

    /*
     * get unique firend user id, whom has been suggested an item
     */

    public function getSuggestionListByUserId($iUserId, $iItemId, $sModule='') {
        if ((int) $iUserId == 0 || $sModule == '' || (int) $iItemId == 0)
            return null;
        $aRows = $this->database()->select('DISTINCT s.friend_user_id, s.suggestion_id')
                ->from(Phpfox::getT('suggestion'), 's')
                ->where('s.user_id = ' . $iUserId . ' AND module_id="' . $sModule . '" AND processed<2 AND item_id = ' . $iItemId)
                ->execute('getRows')
        ;
        return $aRows;
    }

    /*
     * get suggestion of UserID to iFriendId and notification ID
     */

    public function getSuggestionDetailByNotification($iUserId, $iFriendId, $sModule='', $iNotificationId=0) {        
        
        $where[] = 's.user_id =' . $iUserId;
        $where[] = 's.friend_user_id=' . $iFriendId;
        $where[] = 'sn.notification_id=' . $iNotificationId;        
        
        $where = implode(' AND ', $where);
        
        if ($sModule != '') {
            $where .= ' AND s.module_id ="' . $sModule . '"';
        }
        $aRows = $this->database()->select('s.*')
                ->from(Phpfox::getT('suggestion'), 's')
                ->join(Phpfox::getT('suggestion_notification'),'sn','sn.suggestion_id = s.suggestion_id')
                ->where($where)
                ->order('time_stamp DESC')
                ->execute('getRow')
        ;

        return $aRows;
    }
    
    /*
     * get suggestion of UserID to iFriendId
     */

    public function getSuggestionDetail($iUserId, $iFriendId, $sModule='') {        
        
        $where[] = 's.user_id =' . $iUserId;
        $where[] = 'friend_user_id=' . $iFriendId;
        
        $where = implode(' AND ', $where);
        
        if ($sModule != '') {
            $where .= ' AND module_id ="' . $sModule . '"';
        }
        $aRows = $this->database()->select('*')
                ->from(Phpfox::getT('suggestion'), 's')
                ->where($where)
                ->order('time_stamp DESC')
                ->execute('getRow')
        ;

        return $aRows;
    }

    /*
     * get suggestion list of UserID to iFriendId
     */

    public function getSuggestionList($iUserId, $iFriendId, $sModule = '') {
        $where = 's.user_id =' . $iUserId . ' AND friend_user_id=' . $iFriendId;
        if ($sModule != '') {
            $where .= ' AND module_id ="' . $sModule . '"';
        }

        $aRows = $this->database()->select('*')
                ->from(Phpfox::getT('suggestion'), 's')
                ->where($where)
                ->execute('getRows')
        ;

        return $aRows;
    }

    /*
     * Get list of friends of iUserId, whom pending list in peding, not process
     * 
     */

    public function getFriendsPendingList($iUserId, $iLimit=0, $sModule = '') {
        if ($iUserId > 0 && (int) Phpfox::getUserId() > 0) {
            $aRows = $this->database()->select('DISTINCT(fr.user_id) as user_id')
                    ->from(Phpfox::getT('friend_request'), 'fr')
                    ->where('fr.friend_user_id=' . $iUserId . ' AND is_ignore = 0')
            ;
            if ($iLimit > 0) {
                $aRows = $this->database()
                        ->limit($iLimit)
                        ->execute('getRows');
            } else {
                $aRows = $this->database()
                        ->execute('getRows');
            }
            return $aRows;
        } else {
            return null;
        }
    }

    /*
     * get random total friends of iUserId
     */

    public function getFriendsOfUser($iUserId, $iLimit = null) {
        $aFriends = $this->database()->select('f.friend_id, rand() rnd, ' . Phpfox::getUserField())
                ->from(Phpfox::getT('friend'), 'f')
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = f.friend_user_Id')
                ->where('u.view_id=0 and f.user_id = ' . (int) $iUserId . ' AND user_name != ""')
                ->order('rnd asc')
                ->limit($iLimit)
                ->execute('getSlaveRows');

        return $aFriends;
    }

    /*
     * Get total friends of iUserId who has total friends lower than total friends set by admin
     * 
     */

    public function getLessFriendsList($iUserId, $iLimit = null) {

        $iLessFriends = (int) Phpfox::getUserParam('suggestion.total_less_friends');
        $iRandomFriends = (int) Phpfox::getUserParam('suggestion.number_of_entries_display_in_blocks');


        $aFriendsList = Phpfox::getService('suggestion')->getFriendsOfUser(Phpfox::getUserId());
        
        $iTotalFriends = 0;
        $result = array();
        
        //check if total friends to display in block greater than 0
        if ($iLessFriends > 0){
            foreach ($aFriendsList as &$aFriend) {
                $iFriendId = $aFriend['user_id'];

    //                    $aFriendDetail = Phpfox::getService('suggestion')->getSuggestionDetail($iUserId, $iFriendId);                    
    //                    if (count($aFriendDetail)>0) continue;

                $_aFriend = Phpfox::getService('suggestion')->getFriendsOfUser($iFriendId);

                if (count($_aFriend) < $iLessFriends) {

                    $aFriend['total_friends'] = count($_aFriend);

                    //get user avatar
                    $iUserId = $aFriend['user_id'];
                    $aUser = Phpfox::getService('suggestion')->getUser($iUserId);
                    $aUser['suffix'] = '_50_square';
                    $aUser['max_width'] = '50';
                    $aUser['max_height'] = '50';
                    $aUser['user'] = $aUser;
                    $img = '<span class="thumb">' . Phpfox::getLib('phpfox.image.helper')->display($aUser) . '</span>';
                    $aFriend['img'] = $img;

                    $result[] = $aFriend;
                    $iTotalFriends++;
                }                

                if ($iTotalFriends >= $iRandomFriends)
                    break;
            }
        }
        if (isset($result) && count($result) > 0)
            return $result;
        return null;
    }

    /**
     * If a call is made to an unknown method attempt to connect
     * it to a specific plug-in with the same name thus allowing 
     * plug-in developers the ability to extend classes.
     *
     * @param string $sMethod is the name of the method
     * @param array $aArguments is the array of arguments of being passed
     */
    public function __call($sMethod, $aArguments) {
        /**
         * Check if such a plug-in exists and if it does call it.
         */
        if ($sPlugin = Phpfox_Plugin::get('suggestion.service_suggestion__call')) {
            eval($sPlugin);
            return;
        }

        /**
         * No method or plug-in found we must throw a error.
         */
        Phpfox_Error::trigger('Call to undefined method ' . __CLASS__ . '::' . $sMethod . '()', E_USER_ERROR);
    }

}

?>