<?php 

class BirthdayReminder_Component_Controller_Admincp_Edit extends Phpfox_Component 
{ 
    public function process() 
    {    
		if(isset($_POST['save']) && $_POST['save'] != null)
		{
			$aVals = $this->request()->getArray('val');
			Phpfox::getService('birthdayreminder.process')->editEmail($aVals);
			//Phpfox::getService('birthdayreminder')->createBirthdayEvent();
			//Phpfox::getService('birthdayreminder')->sendMail();
		}
		
		$aEmail = Phpfox::getService('birthdayreminder')->getEmail();	
		
		$this->template()->setTitle(Phpfox::getPhrase('birthdayreminder.admin_menu_edit_email'))
			->setBreadcrumb(Phpfox::getPhrase('birthdayreminder.admin_menu_edit_email'), $this->url()->makeUrl('admincp.birthdayreminder.edit'))
			->setEditor()
			->assign(array(
                    'aForms' => $aEmail[0],
					'aEmail' => $aEmail[0]
					)
            );				
    } 
} 

?>