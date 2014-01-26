<?php


defined('PHPFOX') or exit('NO DICE!');


 class AdvancedMarketplace_Component_Controller_invites extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
        $aFilterMenu = array();
        if (!defined('PHPFOX_IS_USER_PROFILE')) {
            $sInviteTotal = '';
            if (Phpfox::isUser() && ($iTotalInvites = Phpfox::getService('advancedmarketplace')->getTotalInvites())) {
                $sInviteTotal = '<span class="invited">' . $iTotalInvites . '</span>';
            }

            $aFilterMenu = array(
                Phpfox::getPhrase('advancedmarketplace.all_listings') => '',
                Phpfox::getPhrase('advancedmarketplace.my_listings') => 'my',
                Phpfox::getPhrase('advancedmarketplace.listing_invites') . $sInviteTotal => 'invites'
            );

            if (Phpfox::isModule('friend') && !Phpfox::getParam('core.friends_only_community')) {
                $aFilterMenu[Phpfox::getPhrase('advancedmarketplace.friends_listings')] = 'friend';
            }

            if (Phpfox::getUserParam('event.can_approve_events')) {
                $iPendingTotal = Phpfox::getService('advancedmarketplace')->getPendingTotal();

                if ($iPendingTotal) {
                    $aFilterMenu[Phpfox::getPhrase('advancedmarketplace.pending_listings') . '<span class="pending">' . $iPendingTotal . '</span>'] = 'pending';
                }
            }
        }

        //  query 
		$bIsProfile = false;
        if (defined('PHPFOX_IS_AJAX_CONTROLLER')) {
            $bIsProfile = true;
            $aUser = Phpfox::getService('user')->get($this->request()->get('profile_id'));
            $this->setParam('aUser', $aUser);
        } else {
            $bIsProfile = $this->getParam('bIsProfile');
            if ($bIsProfile === true) {
                $aUser = $this->getParam('aUser');
            }
        }

        $bIsUserProfile = false;
        if (defined('PHPFOX_IS_AJAX_CONTROLLER')) {
            $bIsUserProfile = true;
            $aUser = Phpfox::getService('user')->get($this->request()->get('profile_id'));
            $this->setParam('aUser', $aUser);
        }

        if (defined('PHPFOX_IS_USER_PROFILE')) {
            $bIsUserProfile = true;
            $aUser = $this->getParam('aUser');
        }

        $aSearchFields = array(
            'type' => 'advancedmarketplace.',
            'field' => 'l.listing_id',
            'search_tool' => array(
			'table_alias' => 'l',
                'search' => array(
                    'action' => ($bIsProfile === true ? $this->url()->makeUrl($aUser['user_name'], array('advancedmarketplace.search', 'view' => $this->request()->get('view'))) : $this->url()->makeUrl('advancedmarketplace.search.', array('view' => $this->request()->get('view')))),
                    'default_value' => Phpfox::getPhrase('advancedmarketplace.search_listings'),
                    'name' => 'search',
                    'field' => array('l.title', 'mt.description_parsed')
                ),
                'sort' => array(
                    'latest' => array('l.time_stamp', Phpfox::getPhrase('advancedmarketplace.latest')),
                    'most-viewed' => array('l.total_view', Phpfox::getPhrase('advancedmarketplace.most_viewed')),
                    'most-liked' => array('l.total_like', Phpfox::getPhrase('advancedmarketplace.most_liked')),
                    'most-talked' => array('l.total_comment', Phpfox::getPhrase('advancedmarketplace.most_discussed')),
					'most-reviewed' => array('l.total_rate desc, l.total_score', Phpfox::getPhrase('advancedmarketplace.most_reviewed')),
                    'recent-viewed' => array('review_time', Phpfox::getPhrase('advancedmarketplace.recent_viewed'))
                ),
                'show' => array(10, 15, 18, 21)
            )
        );

        $this->search()->set($aSearchFields);
		
		//  invites...
		Phpfox::isUser(true);
		$oServiceAdvancedMarketplaceBrowse = Phpfox::getService('advancedmarketplace.browse');
		$oServiceAdvancedMarketplaceBrowse->seen();
		/// invites...
		
        $aBrowseParams = array(
            'module_id' => 'advancedmarketplace',
            'alias' => 'l',
            'field' => 'listing_id',
            'table' => Phpfox::getT('advancedmarketplace'),
            'hide_view' => array('pending', 'my')
        );
		$this->search()->browse()->params($aBrowseParams)->execute();
        /// query
        // list($count, $aListings) = PHPFOX::getService("advancedmarketplace")->frontend_getListings(NULL, 'listing_id desc', $iPage = 0);
        $this->template()->buildSectionMenu('advancedmarketplace', $aFilterMenu);

        $this->template()->assign(array(
            'corepath' => phpfox::getParam('core.path'),
            "aListings" => $this->search()->browse()->getRows(),
            'advancedmarketplace_url_image' => Phpfox::getParam('core.url_pic') . "advancedmarketplace/",
        ));
        $this->template()->setBreadcrumb("Marketplace", "advancedmarketplace");
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('advancedmarketplace.component_controller_view_clean')) ? eval($sPlugin) : false);
	}
}

?>
