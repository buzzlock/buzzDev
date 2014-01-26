<?php

/*
 * @copyright        [YouNet_COPYRIGHT]
 * @author           YouNet Development
 * @package          Module_Contactimporter
 * @version          2.06
 *
 */
defined('PHPFOX') or exit('NO DICE!');

class contactimporter_Component_Controller_Invite extends Phpfox_Component
{
	public function process()
	{
		$iUserId = intval(Phpfox::getUserId());
		$this -> template() -> assign(array(
			'contactimporter_link' => phpfox::getLib('url') -> makeUrl('contactimporter'),
			'homepage' => phpfox::getParam('core.path'),
		));
		
		$max_invitation = Phpfox::getService('contactimporter') -> getMaxInvitation();
		$request = $this -> request();
		$errors = array();
		$oi_session_id = $request -> get('oi_session_id');
		$provider = $request -> get('provider_box', '');
		if (isset($oi_session_id) && $oi_session_id != null)
		{
			$task = $request -> get('task');
			if (isset($task) && $task == 'do_add')
			{
				$message = $request -> get('message');
				if (phpfox::getUserParam('contactimporter.hide_the_custom_invittation_message') == true)
				{
					$mess = phpfox::getPhrase('contactimporter.default_invite_message_text');
				}
				$selected_contacts = $request -> get('contacts');
				$selected_contacts = explode(',', $selected_contacts);
				$selected_contacts = array_slice($selected_contacts, 0, $max_invitation);
				list($bIsRegistration, $sNextUrl) = $this -> url() -> isRegistration(2);
				$sFailed = '';
				$bSent = true;
				if (!empty($selected_contacts))
				{
					foreach ($selected_contacts as $sMail)
					{
						$sMail = trim($sMail);
						$iInvite = Phpfox::getService('invite.process') -> addInvite($sMail, $iUserId);
						(($sPlugin = Phpfox_Plugin::get('invite.component_controller_invite_process_send')) ? eval($sPlugin) : false);
						// check if we could send the mail
						$sLink = Phpfox::getLib('url') -> makeUrl('invite', array('id' => $iInvite));
						$is_unsubcribe = phpfox::getLib('database') -> select('param_values') -> from(phpfox::getT('contactimporter_settings')) -> where('settings_type="is_unsubcribed"') -> execute('getRow');
						$subcribe_message = "";
						if (isset($is_unsubcribe['param_values']))
						{
							$encoded = PREG_REPLACE("'(.)'e", "dechex(ord('\\1'))", $sMail);
							//$whyyouwereinvited = Phpfox::getParam('core.path').'contactimporter/whyyouwereinvited?id='. $encoded;
							$whyyouwereinvited = phpfox::getLib('url') -> makeURL('contactimporter.whyyouwereinvited');
							$whyyouwereinvited .= '?id=' . $encoded;
							//$blockall = Phpfox::getParam('core.path').'contactimporter/blockallfurtheremailmessages?id='. $encoded;
							$blockall = Phpfox::getLib('url') -> makeURL('contactimporter.blockallfurtheremailmessages');
							$blockall .= '?id=' . $encoded;
							$subcribe_message = '<a target="_blank" href="' . $whyyouwereinvited . '">' . Phpfox::getPhrase('contactimporter.find_out_why_you_were_invited_by_clicking_here') . '</a><br /><a target="_blank" href="' . $blockall . '">' . Phpfox::getPhrase('contactimporter.block_all_further_email_mesages') . '</a>';
						}
						$unsubscribeEmail = phpfox::getService('contactimporter') -> getEmailUnsubscribe();
						if (!in_array($sMail, $unsubscribeEmail))
						{
							try
							{
								$bSent = Phpfox::getLib('mail') -> to($sMail)
								// -> fromEmail(Phpfox::getUserBy('email'))
								//->fromName(Phpfox::getUserBy('full_name'))
								-> subject(array(
									'invite.full_name_invites_you_to_site_title',
									array(
										'full_name' => Phpfox::getUserBy('full_name'),
										'site_title' => Phpfox::getParam('core.site_title')
									)
								)) -> message(array(
									'invite.full_name_invites_you_to_site_title_link',
									array(
										'full_name' => Phpfox::getUserBy('full_name'),
										'site_title' => Phpfox::getParam('core.site_title') . '<br/>' . $message . '<br/>' . $subcribe_message,
										'link' => $sLink
									)
								)) -> send();
							}
							catch (Exception $ex)
							{
								$errors[] = $sMail . " can't send !";
							}
						}
						else
						{
							$errors[] = Phpfox::getPhrase('contactimporter.the_invitation_was_added_in_the_unsubscribe_please_do_not_send_to_this_email');
						}
					}
					phpfox::getService('contactimporter') -> updateStatistic($iUserId, count($selected_contacts), 0, $provider);
				}
				else
				{
					$errors[] = Phpfox::getPhrase('contactimporter.you_haven_t_selected_any_contacts_to_invite') . "!";
				}
				if ($bIsRegistration === true)
				{
					$this -> url() -> send($sNextUrl, null, Phpfox::getPhrase('invite.your_friends_have_successfully_been_invited'));
				}
			}
		}
		else
		{
			$errors[] = Phpfox::getPhrase('contactimporter.you_haven_t_selected_any_contacts_to_invite') . "!";
		}
		$this -> template() -> setBreadcrumb(Phpfox::getPhrase('contactimporter.breadcrumb_contactimporter_title'));
		$this -> template() -> setTitle(Phpfox::getPhrase('contactimporter.contact_importer') . ' >> ' . Phpfox::getPhrase('contactimporter.invite_friends'));
		$this -> template() -> assign(array('errors' => $errors));
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

}
