<?php 
class BirthdayReminder_Service_BirthdayReminder extends Phpfox_Service  
{ 
	private $_bIsEndingInThePast = false;
	
	private $_aCategories = array();
	
	public function __construct()
	{	
		$this->_eventTable = Phpfox::getT('event');
		$this->_feventsTable = Phpfox::getT('fevent');
	}
	
	public function getEmail() 
    { 
        return $this->database()->select('*') 
            ->from(Phpfox::getT('birthdayreminder'), 'b') 
            ->execute('getRows'); 
    } 
	
	public function getSettings() 
    { 
        return $this->database()->select('*') 
            ->from(Phpfox::getT('birthdayreminder_setting'), 'b') 
            ->execute('getRows'); 
    } 
	
	public function getCreateEventSettings() 
    { 
        return $this->database()->select('create_event') 
            ->from(Phpfox::getT('birthdayreminder_setting'), 'b') 
            ->execute('getSlaveField'); 
    } 
	
	public function getSendMailDateSettings() 
    { 
        return $this->database()->select('send_mail_date') 
            ->from(Phpfox::getT('birthdayreminder_setting'), 'b') 
            ->execute('getSlaveField'); 
    } 
	
	public function getCreateEventDateSettings() 
    { 
        return $this->database()->select('create_event_date') 
            ->from(Phpfox::getT('birthdayreminder_setting'), 'b') 
            ->execute('getSlaveField'); 
    } 
	
	public function getUser()
	{
		return $aUsers = $this->database()->select('u.user_id, u.full_name, u.email, u.birthday') 
            ->from(Phpfox::getT('user'), 'u') 
            ->execute('getRows'); 
	}
	
	public function getBirthdayUser($Days)
	{
		return $aUsers = $this->database()->select('u.user_id, u.full_name, u.email, u.birthday, user_field.dob_setting') 
            ->from(Phpfox::getT('user'), 'u') 
			->join(Phpfox::getT('user_field'), 'user_field', 'user_field.user_id = u.user_id')
			->where("u.birthday like '%" . $Days . "%'")
            ->execute('getRows'); 
	}
	
	public function getPrivacy($User)
	{
		return $Privacy = $this->database()->select('user_privacy.user_value') 
            ->from(Phpfox::getT('user'), 'u') 
			->join(Phpfox::getT('user_privacy'), 'user_privacy', "user_privacy.user_id = u.user_id AND user_privacy.user_privacy = 'profile.basic_info'")
			->where('u.user_id = ' . $User)
            ->execute('getSlaveField'); 
	}
	
	public function calculateDate($input_date, $number_of_day)
	{	
		$date = strtotime(date("Y-m-d", strtotime($input_date)) . " +". $number_of_day . " day");
		//$date = strtotime(date("Y/m/d", strtotime($input_date)) . " +". $number_of_day . " day");
		//var_dump($date);
		$date = strftime("%d/%m/%Y", $date);
		//var_dump($date);
		
		$result['day'] = substr($date,3,2);
		$result['month'] = substr($date,0,2);
		$result['year'] = substr($date,6,4);
	
		$final = $result['day'] . $result['month'] . $result['year'];
		
		return $final; 
	}
	
	private function _verify(&$aVals, $bIsUpdate = false)
	{				
		/*
		if (!isset($aVals['category']))
		{
			return Phpfox_Error::set(Phpfox::getPhrase('event.provide_a_category_this_event_will_belong_to'));
		}
		*/	
		if (isset($aVals['category']) && is_array($aVals['category']))
		{
			foreach ($aVals['category'] as $iCategory)
			{		
				if (empty($iCategory))
				{
					continue;
				}

				if (!is_numeric($iCategory))
				{
					continue;
				}			

				$this->_aCategories[] = $iCategory;
			}
		}
		
		/*
		if (!count($this->_aCategories))
		{
			return Phpfox_Error::set(Phpfox::getPhrase('event.provide_a_category_this_event_will_belong_to'));
		}		
		*/
		
		if (isset($_FILES['image']['name']) && ($_FILES['image']['name'] != ''))
		{
			$aImage = Phpfox::getLib('file')->load('image', array(
					'jpg',
					'gif',
					'png'
				), (Phpfox::getUserParam('event.max_upload_size_event') === 0 ? null : (Phpfox::getUserParam('event.max_upload_size_event') / 1024))
			);
			
			if ($aImage === false)
			{
				return false;
			}
			
			$this->_bHasImage = true;
		}	
		
		//if ($bIsUpdate === false)
		{			
			$iStartTime = Phpfox::getLib('date')->mktime($aVals['start_hour'], $aVals['start_minute'], 0, $aVals['start_month'], $aVals['start_day'], $aVals['start_year']);
			$iEndTime = Phpfox::getLib('date')->mktime($aVals['end_hour'], $aVals['end_minute'], 0, $aVals['end_month'], $aVals['end_day'], $aVals['end_year']);			
			
			if ($iEndTime < $iStartTime)
			{
				// return Phpfox_Error::set(Phpfox::getPhrase('event.your_event_is_ending_before_it_starts'));
				$this->_bIsEndingInThePast = true;
			}
			/*
			if (Phpfox::getLib('date')->convertToGmt($iStartTime) < PHPFOX_TIME)
			{
				return Phpfox_Error::set(Phpfox::getPhrase('event.your_event_is_starting_in_the_past'));
			}
			 * 
			 */
		}

		return true;	
	}
	
	public function createEvent($aVals, $sModule = 'event', $iItem = 0, $User)
	{
		if (!$this->_verify($aVals))
		{
			return false;
		}
		
		if (!isset($aVals['privacy']))
		{
			$aVals['privacy'] = 0;
		}
		
		$oParseInput = Phpfox::getLib('parse.input');	
		Phpfox::getService('ban')->checkAutomaticBan($aVals);
					
		$iStartTime = Phpfox::getLib('date')->mktime($aVals['start_hour'], $aVals['start_minute'], 0, $aVals['start_month'], $aVals['start_day'], $aVals['start_year']);		
		if ($this->_bIsEndingInThePast === true)
		{
			$aVals['end_hour'] = ($aVals['start_hour'] + 1);
			$aVals['end_minute'] = $aVals['start_minute'];
			$aVals['end_day'] = $aVals['start_day'];
			$aVals['end_year'] = $aVals['start_year'];			
		}
		
		$iEndTime = Phpfox::getLib('date')->mktime($aVals['end_hour'], $aVals['end_minute'], 0, $aVals['end_month'], $aVals['end_day'], $aVals['end_year']);				
		
		if ($iStartTime > $iEndTime)
		{
			$iEndTime = $iStartTime;
		}
				
		$aSql = array(
			'view_id' => (($sModule == 'event' && Phpfox::getUserParam('event.event_must_be_approved')) ? '1' : '0'),
			'privacy' => (isset($aVals['privacy']) ? $aVals['privacy'] : '0'),
			'privacy_comment' => (isset($aVals['privacy_comment']) ? $aVals['privacy_comment'] : '0'),
			'module_id' => 'event',
			'item_id' => $iItem,
			'user_id' => $User,
			'title' => $oParseInput->clean($aVals['title'], 255),
			'location' => $oParseInput->clean($aVals['location'], 255),
			'country_iso' => (empty($aVals['country_iso']) ? Phpfox::getUserBy('country_iso') : $aVals['country_iso']),
			'country_child_id' => (isset($aVals['country_child_id']) ? (int) $aVals['country_child_id'] : 0),
			'postal_code' => (empty($aVals['postal_code']) ? null : Phpfox::getLib('parse.input')->clean($aVals['postal_code'], 20)),
			'city' => (empty($aVals['city']) ? null : $oParseInput->clean($aVals['city'], 255)),
			'time_stamp' => PHPFOX_TIME,
			'start_time' => Phpfox::getLib('date')->convertToGmt($iStartTime),
			'end_time' => Phpfox::getLib('date')->convertToGmt($iEndTime),
			'start_gmt_offset' => Phpfox::getLib('date')->getGmtOffset($iStartTime),
			'end_gmt_offset' => Phpfox::getLib('date')->getGmtOffset($iEndTime),
			'address' => (empty($aVals['address']) ? null : Phpfox::getLib('parse.input')->clean($aVals['address']))
		);
		
		if (Phpfox::getUserParam('event.can_add_gmap') && isset($aVals['gmap']) 
				&& is_array($aVals['gmap']) && isset($aVals['gmap']['latitude'])
				&& isset($aVals['gmap']['longitude']))
		{
			$aSql['gmap'] = serialize($aVals['gmap']);
		}
		
		if ($sPlugin = Phpfox_Plugin::get('event.service_process_add__start')){return eval($sPlugin);}
		
		if (!Phpfox_Error::isPassed())
		{
			return false;
		}
		
		//var_dump($aSql);
		
		$iId = $this->database()->insert($this->_eventTable, $aSql);
		
		if (!$iId)
		{
			//echo('fail');
			return false;
		}
		
		$this->database()->insert(Phpfox::getT('event_text'), array(
				'event_id' => $iId,
				'description' => (empty($aVals['description']) ? null : $oParseInput->clean($aVals['description'])),
				'description_parsed' => (empty($aVals['description']) ? null : $oParseInput->prepare($aVals['description']))
			)
		);		
		
		foreach ($this->_aCategories as $iCategoryId)
		{
			$this->database()->insert(Phpfox::getT('event_category_data'), array('event_id' => $iId, 'category_id' => $iCategoryId));
		}		

        // Plugin call
		if ($sPlugin = Phpfox_Plugin::get('event.service_process_add__end')){eval($sPlugin);}

		return $iId;
	}
	
	public function createFevent($aVals, $sModule = 'fevent', $iItem = 0, $User)
	{
		if (!$this->_verify($aVals))
		{
			return false;
		}
		
		if (!isset($aVals['privacy']))
		{
			$aVals['privacy'] = 0;
		}
		
		$oParseInput = Phpfox::getLib('parse.input');	
		Phpfox::getService('ban')->checkAutomaticBan($aVals);

		$iStartTime = Phpfox::getLib('date')->mktime($aVals['start_hour'], $aVals['start_minute'], 0, $aVals['start_month'], $aVals['start_day'], $aVals['start_year']);		
		$iEndTime = Phpfox::getLib('date')->mktime($aVals['end_hour'], $aVals['end_minute'], 0, $aVals['end_month'], $aVals['end_day'], $aVals['end_year']);
		if ($this->_bIsEndingInThePast === true)
		{
			$iEndTime = $iStartTime + 3600;		
		}
        
        $bHasAttachments = (!empty($aVals['attachment']) && Phpfox::getUserParam('fevent.can_attach_on_event'));        
		
		$timerepeat=0;
		
		if($aVals['daterepeat']!="")
		{
			die(1);
			$atimerepeat=explode("/", $aVals['daterepeat']);
			$timerepeat = Phpfox::getLib('date')->mktime($aVals['start_hour'], $aVals['start_minute'], 0, $atimerepeat[0], $atimerepeat[1], $atimerepeat[2]);
		}
		$repeat=$aVals['txtrepeat'];
		$range_value_real=$aVals['range_type']*1000;
		if($range_value_real==0)
			$range_value_real=1609;
		
		$aSql = array(
			'view_id' => (($sModule == 'fevent' && Phpfox::getUserParam('fevent.event_must_be_approved')) ? '1' : '0'),
			'privacy' => (isset($aVals['privacy']) ? $aVals['privacy'] : '0'),
			'privacy_comment' => (isset($aVals['privacy_comment']) ? $aVals['privacy_comment'] : '0'),
			'module_id' => $sModule,
			'isrepeat' => $aVals['txtrepeat'],
			'timerepeat' => Phpfox::getLib('date')->convertToGmt($timerepeat),
			'range_value' => $aVals['range_value'],
			'range_type' => $aVals['range_type'],
			'range_value_real' => $aVals['range_value']*$range_value_real,
			'item_id' => $iItem,
			'user_id' => $User,
			'title' => $oParseInput->clean($aVals['title'], 255),
			'location' => $oParseInput->clean($aVals['location'], 255),
			'country_iso' => (empty($aVals['country_iso']) ? Phpfox::getUserBy('country_iso') : $aVals['country_iso']),
			'country_child_id' => (isset($aVals['country_child_id']) ? (int) $aVals['country_child_id'] : 0),
			'postal_code' => (empty($aVals['postal_code']) ? null : Phpfox::getLib('parse.input')->clean($aVals['postal_code'], 20)),
			'city' => (empty($aVals['city']) ? null : $oParseInput->clean($aVals['city'], 255)),
			'time_stamp' => PHPFOX_TIME,
			'start_time' => Phpfox::getLib('date')->convertToGmt($iStartTime),
			'end_time' => Phpfox::getLib('date')->convertToGmt($iEndTime),
			'start_gmt_offset' => Phpfox::getLib('date')->getGmtOffset($iStartTime),
			'end_gmt_offset' => Phpfox::getLib('date')->getGmtOffset($iEndTime),
			'address' => (empty($aVals['address']) ? null : Phpfox::getLib('parse.input')->clean($aVals['address'])),
			
			
            //'total_attachment' => ($bHasAttachments ? Phpfox::getService('attachment')->getCount($aVals['attachment']) : 0)
		);
		//echo(1);
		if (Phpfox::getUserParam('fevent.can_add_gmap') && isset($aVals['gmap']) 
				&& is_array($aVals['gmap']) && isset($aVals['gmap']['latitude'])
				&& isset($aVals['gmap']['longitude']))
		{
			$aSql['gmap'] = serialize($aVals['gmap']);
			$aSql['lat'] = $aVals['gmap']['latitude'];
            $aSql['lng'] = $aVals['gmap']['longitude'];
		}
		
		if ($sPlugin = Phpfox_Plugin::get('fevent.service_process_add__start')){return eval($sPlugin);}
		
		if (!Phpfox_Error::isPassed())
		{
			return false;
		}
		
		$iId = $this->database()->insert($this->_feventsTable, $aSql);
		//var_dump($iId);
		if(!$iId)
		{
			//die('fail');
			return false;
		}
		
		$this->database()->insert(Phpfox::getT('fevent_text'), array(
				'event_id' => $iId,
				'description' => (empty($aVals['description']) ? null : $oParseInput->clean($aVals['description'])),
				'description_parsed' => (empty($aVals['description']) ? null : $oParseInput->prepare($aVals['description']))
			)
		);
		
		foreach ($this->_aCategories as $iCategoryId)
		{
			$this->database()->insert(Phpfox::getT('fevent_category_data'), array('event_id' => $iId, 'category_id' => $iCategoryId));
		}		

        // Plugin call
		if ($sPlugin = Phpfox_Plugin::get('fevent.service_process_add__end')){eval($sPlugin);}

		return $iId;
	}
	
	public function copyFile($sourcePath, $destPath)
    {
        if(file_exists($sourcePath))
        {
            copy($sourcePath, $destPath);
            return true;
        }

        return false;
    }
	
	public function addImage($iId)
	{
		$oImage = Phpfox::getLib('image');
		
		$mode = 0777;
		
		$year = strftime("%Y");
		
		$month = strftime("%m");
		
		if(Phpfox::isModule('event'))
		{
			// check folder year
			if (!file_exists(Phpfox::getParam('event.dir_image').$year))
			{
				Phpfox::getLib('file')->mkdir(Phpfox::getParam('event.dir_image').$year, $mode);
			}
			// check folder month
			if (!file_exists(Phpfox::getParam('event.dir_image').$year.'/'.$month))
			{
				Phpfox::getLib('file')->mkdir(Phpfox::getParam('event.dir_image').$year.'/'.$month, $mode);
			}
			
			//copy
			$fileOrg = PHPFOX_DIR_MODULE . 'birthdayreminder/static/image/birthday.jpg';
			$fileCopy =  Phpfox::getParam('event.dir_image') . $year. '/' . $month . '/birthday.jpg';
			$sFileName =  $year. '/' . $month . '/' . md5(time().$iId);
			$sFileNameDB =  $sFileName . "%s.jpg";
			$sFileName = $sFileName . ".jpg";	
					
			$this->copyFile($fileOrg, $fileCopy);
			
			$fileRename =  Phpfox::getParam('event.dir_image') . $sFileName;
			Phpfox::getLib('file')->rename($fileCopy, $fileRename);
				
			$iSize = 50;		
			$oImage->createThumbnail(Phpfox::getParam('event.dir_image') . sprintf($sFileName, ''), Phpfox::getParam('event.dir_image') . str_replace('.', '_' . $iSize . '.', $sFileName), $iSize, $iSize);					
				
			$iSize = 120;			
			$oImage->createThumbnail(Phpfox::getParam('event.dir_image') . sprintf($sFileName, ''), Phpfox::getParam('event.dir_image') . str_replace('.', '_' . $iSize . '.', $sFileName), $iSize, $iSize);		

			$iSize = 200;			
			$oImage->createThumbnail(Phpfox::getParam('event.dir_image') . sprintf($sFileName, ''), Phpfox::getParam('event.dir_image') . str_replace('.', '_' . $iSize . '.', $sFileName), $iSize, $iSize);		
	
			return $this->database()->update($this->_eventTable, array('image_path' => $sFileNameDB), 'event_id = ' . (int)$iId);
		}
		else if(Phpfox::isModule('fevent'))	
		{	
			// check folder year
			if (!file_exists(Phpfox::getParam('event.dir_image').$year))
			{
				Phpfox::getLib('file')->mkdir(Phpfox::getParam('event.dir_image').$year, $mode);
			}
			// check folder month
			if (!file_exists(Phpfox::getParam('event.dir_image').$year.'/'.$month))
			{
				Phpfox::getLib('file')->mkdir(Phpfox::getParam('event.dir_image').$year.'/'.$month, $mode);
			}
			
			//copy
			$fileOrg = PHPFOX_DIR_MODULE . 'birthdayreminder/static/image/birthday.jpg';
			$fileCopy =  Phpfox::getParam('event.dir_image') . $year. '/' . $month . '/birthday.jpg';
			$sFileName =  $year. '/' . $month . '/' . md5(time().$iId);
			$sFileNameDB =  $sFileName . "%s.jpg";
			$sFileName = $sFileName . ".jpg";	
					
			$this->copyFile($fileOrg, $fileCopy);
			
			$fileRename =  Phpfox::getParam('event.dir_image') . $sFileName;
			Phpfox::getLib('file')->rename($fileCopy, $fileRename);
				
			$iSize = 50;		
			$oImage->createThumbnail(Phpfox::getParam('event.dir_image') . sprintf($sFileName, ''), Phpfox::getParam('event.dir_image') . str_replace('.', '_' . $iSize . '.', $sFileName), $iSize, $iSize);					
				
			$iSize = 120;			
			$oImage->createThumbnail(Phpfox::getParam('event.dir_image') . sprintf($sFileName, ''), Phpfox::getParam('event.dir_image') . str_replace('.', '_' . $iSize . '.', $sFileName), $iSize, $iSize);		

			$iSize = 200;			
			$oImage->createThumbnail(Phpfox::getParam('event.dir_image') . sprintf($sFileName, ''), Phpfox::getParam('event.dir_image') . str_replace('.', '_' . $iSize . '.', $sFileName), $iSize, $iSize);		
	
			return $this->database()->update($this->_feventsTable, array('image_path' => $sFileNameDB), 'event_id = ' . (int)$iId);
		}
		
		return false;
	}
	
	public function createBirthdayEvent()
	{
		$Id = 0;
		
		$create_event_date = $this->getCreateEventDateSettings();
		
		$input_date = date('Y-m-d', time());
		$Days = $this->calculateDate($input_date, $create_event_date);
		
		$Days = substr($Days,0,4);
		
		$aUsers = $this->getBirthdayUser($Days);

		$EventType = '';
		
		foreach($aUsers as $User)
		{
			If($this->getPrivacy($User['user_id']) == null)
			{
				$User['privacy'] = 1;
			}
			else
			{
				$User['privacy'] = $this->getPrivacy($User['user_id']);
			}
			
			If($this->getCreateEventSettings() == 1 && Phpfox::isModule('event'))
			{	
				$EventType = 'event';
				
				$aVals['title'] = Phpfox::getPhrase('birthdayreminder.happy_birthday').' '.$User['full_name'];
			
				$aVals['description'] = Phpfox::getPhrase('birthdayreminder.happy_birthday');
				$aVals['start_month'] = substr($User['birthday'],0,2);
				$aVals['start_day'] = substr($User['birthday'],2,2);
				$aVals['start_year'] = strftime("%Y");
				$aVals['start_hour'] = 00;
				$aVals['start_minute'] = 00;
			
				$create_event_date = $create_event_date + 1;
				$end = strtotime(date("d/m/Y", strtotime($input_date)) . " +". $create_event_date . " day");
				$end = strftime("%d/%m/%Y", $end);
				$create_event_date = $create_event_date - 1;
				
				$aVals['end_month'] = substr($end,0,2);
				$aVals['end_day'] = substr($end,3,2);
				$aVals['end_year'] = strftime("%Y");
				$aVals['end_hour'] = 00;
				$aVals['end_minute'] = 00;
				
				$aVals['location'] = '';
				$aVals['address'] = '';
				$aVals['city'] = '';
				$aVals['postal_code'] = ''; 
				$aVals['country_iso'] = '';
				
				if($User['dob_setting'] == 2 or $User['dob_setting'] == 3)
				{
					$aVals['privacy'] = 3;
				}
				else If($User['privacy'] == 1)
				{
					$aVals['privacy'] = 0;
				}
				else If($User['privacy'] == 2)
				{
					$aVals['privacy'] = 1;
				}
				else If($User['privacy'] == 3)
				{
					$aVals['privacy'] = 2;
				}
				else If($User['privacy'] == 4)
				{
					$aVals['privacy'] = 3;
				}
				
				if($User['dob_setting'] == 2 or $User['dob_setting'] == 3)
				{
					$aVals['privacy_comment'] = 3;
				}
				else
				{
					$aVals['privacy_comment'] = 0;
				}
				
				$aVals['emails'] = '';
				$aVals['personal_message'] = '';
				
				$Id = $this->createEvent($aVals, 'event', $iItem = 0, $User['user_id']);
				$path = Phpfox::getLib('url')->permalink('event', $Id);
				$this->addImage($Id);
				Phpfox::getService('birthdayreminder.process')->insertEventUser($User['user_id'], $Id, $EventType);
			}
			else If($this->getCreateEventSettings() == 1 && Phpfox::isModule('fevent'))
			{
				$EventType = 'fevent';
			
				$aVals['title'] = Phpfox::getPhrase('birthdayreminder.happy_birthday').' '.$User['full_name'];
			
				$aVals['description'] = Phpfox::getPhrase('birthdayreminder.happy_birthday');
				$aVals['start_month'] = substr($User['birthday'],0,2);
				$aVals['start_day'] = substr($User['birthday'],2,2);
				$aVals['start_year'] = strftime("%Y");
				$aVals['start_hour'] = 00;
				$aVals['start_minute'] = 00;
			
				$create_event_date = $create_event_date + 1;
				$end = strtotime(date("d/m/Y", strtotime($input_date)) . " +". $create_event_date . " day");
				$end = strftime("%d/%m/%Y", $end);
				$create_event_date = $create_event_date - 1;
				
				$aVals['end_month'] = substr($end,0,2);
				$aVals['end_day'] = substr($end,3,2);
				$aVals['end_year'] = strftime("%Y");
				$aVals['end_hour'] = 00;
				$aVals['end_minute'] = 00;
				
				$aVals['location'] = '';
				$aVals['address'] = '';
				$aVals['city'] = '';
				$aVals['postal_code'] = ''; 
				$aVals['country_iso'] = '';
				
				if($User['dob_setting'] == 2 or $User['dob_setting'] == 3)
				{
					$aVals['privacy'] = 3;
				}
				else If($User['privacy'] == 1)
				{
					$aVals['privacy'] = 0;
				}
				else If($User['privacy'] == 2)
				{
					$aVals['privacy'] = 1;
				}
				else If($User['privacy'] == 3)
				{
					$aVals['privacy'] = 2;
				}
				else If($User['privacy'] == 4)
				{
					$aVals['privacy'] = 3;
				}
				
				if($User['dob_setting'] == 2 or $User['dob_setting'] == 3)
				{
					$aVals['privacy_comment'] = 3;
				}
				else
				{
					$aVals['privacy_comment'] = 0;
				}
				
				$aVals['privacy_comment'] = 0;
				$aVals['emails'] = '';
				$aVals['personal_message'] = '';
				$aVals['daterepeat'] = '';
				$aVals['txtrepeat'] = -1;
				$aVals['range_value'] = '';
				$aVals['range_type'] = '';
				$aVals['range_value_real'] = '';
				
				$Id = $this->createFevent($aVals, 'fevent', $iItem = 0, $User['user_id']);
				$path = Phpfox::getLib('url')->permalink('fevent', $Id);
				$this->addImage($Id);
				Phpfox::getService('birthdayreminder.process')->insertEventUser($User['user_id'], $Id, $EventType);
			}
		}
    }
	
	public function getMaxUserBirthdayEvent($User, $EventType)
	{
		return $this->database()->select('MAX(event_id)') 
            ->from(Phpfox::getT('birthdayreminder_event'), 'b') 
			->where("b.user_id = " . $User . " AND b.event_type like '" . $EventType . "'")
            ->execute('getSlaveField'); 
	}
	
	public function getEventCreateTime($EventId)
	{
		return $this->database()->select('e.time_stamp') 
            ->from(Phpfox::getT('event'), 'e') 
			->where("e.event_id = " . $EventId)
            ->execute('getSlaveField'); 
	}
	
	public function getFeventCreateTime($EventId)
	{
		return $this->database()->select('e.time_stamp') 
            ->from(Phpfox::getT('fevent'), 'e') 
			->where("e.event_id = " . $EventId)
            ->execute('getSlaveField'); 
	}
	
	public function sendMail()
	{	
		$aMail = $this->getEmail();
		
		$send_mail_date = $this->getSendMailDateSettings();
		
		$input_date = date('Y-m-d', time());
		$Days = $this->calculateDate($input_date, $send_mail_date);
		
		$Days = substr($Days,0,4);
		
		$aUsers = $this->getBirthdayUser($Days);
		
		foreach($aUsers as $User)
		{	
			$EventType = ((Phpfox::isModule('fevent')) ? 'fevent' : 'event');
			//$path = $this->getMaxUserBirthdayEvent($User['user_id'], $EventType);
			//die($path);
			
			$path = Phpfox::getLib('url')->permalink($EventType, $this->getMaxUserBirthdayEvent($User['user_id'], $EventType));
			
			If($EventType == 'event')
			{
				$EventId = Phpfox::getService('event')->getEvent($this->getMaxUserBirthdayEvent($User['user_id'], $EventType));
				if(isset($EventId) == true && isset($EventId['event_id']) == true)
				{
					$CreateTime = $this->getEventCreateTime($EventId['event_id']);
					If(date("Y", $CreateTime) < date("Y", time()))
					{
						$path = Phpfox::getPhrase('birthdayreminder.event_not_available');
					}
				} else 
				{
					$path = Phpfox::getPhrase('birthdayreminder.event_not_available');
				}
			}
			else if ($EventType == 'fevent')
			{
				$EventId = Phpfox::getService('fevent')->getEvent($this->getMaxUserBirthdayEvent($User['user_id'], $EventType));
				if(isset($EventId) == true && isset($EventId['event_id']) == true)
				{
					$CreateTime = $this->getFeventCreateTime($EventId['event_id']);
					If(date("Y", $CreateTime) < date("Y", time()))
					{
						$path = Phpfox::getPhrase('birthdayreminder.event_not_available');
					}
				} else 
				{
					$path = Phpfox::getPhrase('birthdayreminder.event_not_available');
				}
			}
			
			If($path == '')
			{
				$path = Phpfox::getPhrase('birthdayreminder.event_not_available');
			}
			
			$subject = str_replace('[full_name]', $User['full_name'], $aMail[0]['subject']);
			$text = str_replace('[full_name]', $User['full_name'], $aMail[0]['text']);
			$text = str_replace('[event_link]', $path, $text);
			$text = utf8_encode($text);
			$subject = utf8_encode($subject);
				
			Phpfox::getLib('mail')->to($User['email'])
			->subject($subject)
			->message($text)
			->send();
		}
	}
}	
  
?>