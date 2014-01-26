<?php

/*
 * @copyright        [YouNet_COPYRIGHT]
 * @author           YouNet Development
 * @package          Module_Contactimporter
 * @version          2.06
 *
 */
defined('PHPFOX') or exit('NO DICE!');

class Contactimporter_Component_Controller_Invitations extends Phpfox_Component
{

	public function process()
	{
		if (!Phpfox::isUser())
		{
			$path = phpfox::getParam('core.path') . 'user/login';
			$this -> url() -> send('login', null, Phpfox::getPhrase('contactimporter.need_to_be_logged_in_for_showing_your_invitations'));
		}

		if ($iInvite = $this -> request() -> getInt('del'))
		{
			list($iSocial, $iEmail) = Phpfox::getService('contactimporter.process') -> deleteInvitation((array)$iInvite);

			if ($iSocial > 0 || $iEmail > 0)
			{
				$this -> url() -> send('contactimporter.invitations', null, Phpfox::getPhrase('invite.invitation_deleted'));
			}
			$this -> url() -> send('contactimporter.invitations', null, Phpfox::getPhrase('contactimporter.the_invitations_is_not_found'));
		}
		elseif ($sInvites = $this -> request() -> get('selectedVals'))
		{
			$aInvite = explode(',', $sInvites);
			list($iSocial, $iEmail) = Phpfox::getService('contactimporter.process') -> deleteInvitation($aInvite);

			if ($iSocial > 0 || $iEmail > 0)
			{
				$this -> url() -> send('contactimporter.invitations', null, Phpfox::getPhrase('invite.invitation_deleted'));
			}
			$this -> url() -> send('contactimporter.invitations', null, Phpfox::getPhrase('contactimporter.the_invitations_is_not_found'));
		}
		$iPage = $this -> request() -> getInt('page');
		if ($iPage == 0)
		{
			setcookie('contactimporter.pendings', '', -1);
		}
		$iPageSize = (int)Phpfox::getParam('contactimporter.how_many_pendings_to_show_per_page');

		list($iCnt, $aInvites) = Phpfox::getService('contactimporter') -> get(Phpfox::getUserId(), $iPage, $iPageSize);

		Phpfox::getLib('pager') -> set(array(
			'page' => $iPage,
			'size' => $iPageSize,
			'count' => $iCnt
		));

		$this -> template() -> setTitle(Phpfox::getPhrase('invite.pending_invitations')) -> setBreadcrumb(Phpfox::getPhrase('invite.pending_invitations')) -> assign(array(
			'aInvites' => $aInvites,
			'iPage' => $iPage,
			'core_url' => Phpfox::getParam('core.path')
		)) -> setHeader(array(
			'pager.css' => 'style_css',
			'pending.js' => 'module_contactimporter',
			'rtl.css' => 'module_contactimporter',
		));
		//$this->template()->setBreadcrumb(Phpfox::getPhrase('contactimporter.breadcrumb_contactimporter_title'));
		$this -> template() -> setPhrase(array(
			'contactimporter.are_you_sure_you_want_to_delete',
			'contactimporter.are_you_sure_you_want_to_delete_this_action_will_delete_all_feeds_belong_to',
			'contactimporter.you_can_send',
			'contactimporter.invitations_per_time',
			'contactimporter.you_have_selected',
			'contactimporter.contacts',
			'contactimporter.select_current_page',
			'contactimporter.unselect_current_page',
			'contactimporter.your_email_is_empty',
			'contactimporter.this_mail_domain_is_not_supported',
			'contactimporter.email_should_not_be_left_blank',
			'contactimporter.no_contacts_were_selected',
			'contactimporter.updating'
		));
	}

	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('invite.component_controller_invitations_clean')) ? eval($sPlugin) : false);
	}

}
?>