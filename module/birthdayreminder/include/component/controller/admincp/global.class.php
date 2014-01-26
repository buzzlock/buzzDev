<?php 

class BirthdayReminder_Component_Controller_Admincp_Global extends Phpfox_Component 
{ 
    public function process() 
    {    
		if(isset($_POST['save']) && $_POST['save'] != null)
		{
			$aVals = $this->request()->getArray('val');
			if($aVals['create_event_date'] < $aVals['send_mail_date'])
			{
				$aSettings = Phpfox::getService('birthdayreminder')->getSettings();
				$this->template()->setTitle(Phpfox::getPhrase('birthdayreminder.admin_global_settings'))
								->setBreadcrumb(Phpfox::getPhrase('birthdayreminder.admin_global_settings'), $this->url()->makeUrl('admincp.birthdayreminder.global'))
								->assign(array(
												'create_event' => $aSettings[0]['create_event'],
												'create_event_date' => $aSettings[0]['create_event_date'],
												'send_mail_date' => $aSettings[0]['send_mail_date']
												)
										);
										
				return Phpfox_Error::set(Phpfox::getPhrase('birthdayreminder.the_create_event_date_must_be_greater_than_send_mail_date'));

			}
			else
			{
				$aTest = Phpfox::getService('birthdayreminder.process')->editSettings($aVals);
			}
		}
	
		$aSettings = Phpfox::getService('birthdayreminder')->getSettings();
		$this->template()->setTitle(Phpfox::getPhrase('birthdayreminder.admin_global_settings'))
			->setBreadcrumb(Phpfox::getPhrase('birthdayreminder.admin_global_settings'), $this->url()->makeUrl('admincp.birthdayreminder.global'))
			->assign(array(
					'create_event' => $aSettings[0]['create_event'],
					'create_event_date' => $aSettings[0]['create_event_date'],
					'send_mail_date' => $aSettings[0]['send_mail_date']
					)
            );				
    } 
} 

?>