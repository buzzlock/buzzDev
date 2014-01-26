<?php 
  
class BirthdayReminder_Service_Process extends Phpfox_Service  
{ 
   public function editEmail($aEmail) 
    { 
		$aEmail['subject'] = $this->preParse()->clean($aEmail['subject']);
		$aEmail['text'] = $this->preParse()->clean($aEmail['text']);
		
        return $this->database()->update(phpfox::getT('birthdayreminder'), array(
				'subject' => $aEmail['subject'],
				'text' => $aEmail['text']
			), '1=1'
		);
    }
	
	public function editSettings($aSettings) 
    { 	
        return $this->database()->update(phpfox::getT('birthdayreminder_setting'), array(
				'create_event' => $aSettings['create_event'],
				'create_event_date' => $aSettings['create_event_date'],
				'send_mail_date' => $aSettings['send_mail_date']
			), '1=1'
		);
    }
	
	public function insertEventUser($UserID, $EventID, $EventType) 
    { 	
		$aSql = array(
			'user_id' => $UserID,
			'event_id' => $EventID,
			'event_type' => $EventType
		);
        return $this->database()->insert(Phpfox::getT('birthdayreminder_event'), $aSql);
    }
} 
  
?>