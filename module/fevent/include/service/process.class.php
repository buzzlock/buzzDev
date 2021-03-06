<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');
/**
 * 
 * 
 * @copyright       [YOUNET_COPYRIGHT]
 * @author          YouNet Company
 * @package         YouNet_Event
 */
class Fevent_Service_Process extends Phpfox_Service 
{
	private $_bHasImage = false;
	
	private $_aInvited = array();
	
	private $_aCategories = array();
	
	private $_bIsEndingInThePast = false;
	
	/**
	 * Class constructor
	 */	
	public function __construct()
	{	
		$this->_sTable = Phpfox::getT('fevent');
	}
	
	public function add($aVals, $sModule = 'fevent', $iItem = 0)
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
			'user_id' => Phpfox::getUserId(),
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
			
			
            'total_attachment' => ($bHasAttachments ? Phpfox::getService('attachment')->getCount($aVals['attachment']) : 0)
		);
		
		if (Phpfox::getUserParam('fevent.can_add_gmap') && isset($aVals['gmap']) 
				&& is_array($aVals['gmap']) && isset($aVals['gmap']['latitude'])
				&& isset($aVals['gmap']['longitude']))
		{
			$aSql['gmap'] = serialize($aVals['gmap']);
            $aSql['lat'] = $aVals['gmap']['latitude'];
            $aSql['lng'] = $aVals['gmap']['longitude'];
		}
        //if(empty($aSql['gmap']))
        {
            $sFullAddress = $aSql["location"] . " " . $aSql["address"] . " " . $aSql["city"] . " " . $aSql["country_iso"];
            list($aCoordinates, $sGmapAddress) = $this->address2coordinates($sFullAddress);
            if(!empty($aCoordinates[1]))
            {
                $aSql['lat'] = $aCoordinates[1];
                $aSql['lng'] = $aCoordinates[0];
				$aSql['gmap_address'] = $oParseInput->prepare($sGmapAddress);
            }
        }
		
		if ($sPlugin = Phpfox_Plugin::get('fevent.service_process_add__start')){return eval($sPlugin);}
		
		if (!Phpfox_Error::isPassed())
		{
			return false;
		}
		
		$iId = $this->database()->insert($this->_sTable, $aSql);
		
		if(!$iId)
		{
			return false;
		}
		
		// If we uploaded any attachments make sure we update the 'item_id'
		if ($bHasAttachments)
		{
			Phpfox::getService('attachment.process')->updateItemId($aVals['attachment'], Phpfox::getUserId(), $iId);
		}
		
		$this->database()->insert(Phpfox::getT('fevent_text'), array(
				'event_id' => $iId,
				'description' => (empty($aVals['description']) ? null : $oParseInput->clean($aVals['description'])),
				'description_parsed' => (empty($aVals['description']) ? null : $oParseInput->prepare($aVals['description']))
			)
		);
        
        foreach($aVals['custom'] as $iFieldId => $sValue)
        {
            $this->database()->insert(Phpfox::getT('fevent_custom_value'), array(
                    'event_id' => $iId,
                    'field_id' => $iFieldId,
                    'value' => is_array($sValue) ? json_encode($sValue) : $sValue
                )
            );
        }
		
		foreach ($this->_aCategories as $iCategoryId)
		{
			$this->database()->insert(Phpfox::getT('fevent_category_data'), array('event_id' => $iId, 'category_id' => $iCategoryId));
		}		
		
		$bAddFeed = ($sModule == 'fevent' ? (Phpfox::getUserParam('fevent.event_must_be_approved') ? false : true) : true);

		if ($bAddFeed === true)
		{
			if ($sModule == 'fevent')
			{
				(Phpfox::isModule('feed') ? Phpfox::getService('feed.process')->add('fevent', $iId, $aVals['privacy'], (isset($aVals['privacy_comment']) ? (int) $aVals['privacy_comment'] : 0)) : null);
			}
			else
			{
				(Phpfox::isModule('feed') ? Phpfox::getService('feed.process')->callback(Phpfox::callback($sModule . '.getFeedDetails', $iItem))->add('fevent', $iId, $aVals['privacy'], (isset($aVals['privacy_comment']) ? (int) $aVals['privacy_comment'] : 0), $iItem) : null);
			}			
			
			Phpfox::getService('user.activity')->update(Phpfox::getUserId(), 'fevent');
		}
		
		$this->addRsvp($iId, 1, Phpfox::getUserId());

		if (Phpfox::VERSION >= '3.7.0' && Phpfox::isModule('tag') && Phpfox::getParam('tag.enable_hashtag_support') && !empty($aVals['description']))
		{
			Phpfox::getService('tag.process')->add('fevent', $iId, Phpfox::getUserId(), $aVals['description'], true);
		}

        // Plugin call
		if ($sPlugin = Phpfox_Plugin::get('fevent.service_process_add__end')){eval($sPlugin);}

		return $iId;
	}
    
    public function updateView($iId)
    {
        $this->database()->query("
            UPDATE " . $this->_sTable . "
            SET total_view = total_view + 1
            WHERE event_id = " . (int) $iId . "
        ");
        
        return true;
    }
	
	public function update($iId, $aVals, $aEventPost = null)
	{
		if (!$this->_verify($aVals, true))
		{
			return false;
		}		
		
		if (!isset($aVals['privacy']))
		{
			$aVals['privacy'] = 0;
		}
		
		if (!isset($aVals['privacy_comment']))
		{
			$aVals['privacy_comment'] = 0;
		}
		
		$oParseInput = Phpfox::getLib('parse.input');
		
		Phpfox::getService('ban')->checkAutomaticBan($aVals['title'] . ' ' . $aVals['description']);
        
        $iStartTime = Phpfox::getLib('date')->mktime($aVals['start_hour'], $aVals['start_minute'], 0, $aVals['start_month'], $aVals['start_day'], $aVals['start_year']);
		$iEndTime = Phpfox::getLib('date')->mktime($aVals['end_hour'], $aVals['end_minute'], 0, $aVals['end_month'], $aVals['end_day'], $aVals['end_year']);	
		if ($this->_bIsEndingInThePast === true)
		{
			$iEndTime = $iStartTime + 3600;		
		}
		
        if ($iStartTime > $iEndTime)
		{
			$iEndTime = $iStartTime;
		}
		
        $bHasAttachments = (!empty($aVals['attachment']) && Phpfox::getUserParam('fevent.can_attach_on_event'));
        if ($bHasAttachments)
        {
            Phpfox::getService('attachment.process')->updateItemId($aVals['attachment'], Phpfox::getUserId(), $iId);
        }
		
		$timerepeat=0;
		
		if($aVals['daterepeat']!="")
		{
			$atimerepeat=explode("/", $aVals['daterepeat']);
			
			$timerepeat = Phpfox::getLib('date')->mktime($aVals['start_hour'], $aVals['start_minute'], 0, $atimerepeat[0], $atimerepeat[1], $atimerepeat[2]);
			
		}	
		$repeat=$aVals['txtrepeat'];
		$range_value_real=$aVals['range_type']*1000;
		if($range_value_real==0)
			$range_value_real=1609;
		$aSql = array(
			'privacy' => (isset($aVals['privacy']) ? $aVals['privacy'] : '0'),
			'privacy_comment' => (isset($aVals['privacy_comment']) ? $aVals['privacy_comment'] : '0'),
			'title' => $oParseInput->clean($aVals['title'], 255),
			'location' => $oParseInput->clean($aVals['location'], 255),
			'country_iso' => $aVals['country_iso'],
			'isrepeat' => $aVals['txtrepeat'],
			'timerepeat' => Phpfox::getLib('date')->convertToGmt($timerepeat),
			'range_value' => $aVals['range_value'],
			'range_type' => $aVals['range_type'],
			'range_value_real' => $aVals['range_value']*$range_value_real,
			'country_child_id' => (isset($aVals['country_child_id']) ? Phpfox::getService('core.country')->getValidChildId($aVals['country_iso'], (int) $aVals['country_child_id']) : 0),
			'city' => (empty($aVals['city']) ? null : $oParseInput->clean($aVals['city'], 255)),		
			'postal_code' => (empty($aVals['postal_code']) ? null : Phpfox::getLib('parse.input')->clean($aVals['postal_code'], 20)),
			'start_time' => Phpfox::getLib('date')->convertToGmt($iStartTime),
			'end_time' => Phpfox::getLib('date')->convertToGmt($iEndTime),
			'start_gmt_offset' => Phpfox::getLib('date')->getGmtOffset($iStartTime),
			'end_gmt_offset' => Phpfox::getLib('date')->getGmtOffset($iEndTime),
			'address' => (empty($aVals['address']) ? null : Phpfox::getLib('parse.input')->clean($aVals['address'])),
            'total_attachment' => (Phpfox::isModule('attachment') ? Phpfox::getService('attachment')->getCountForItem($iId, 'fevent') : '0')
		);			
		
		if (Phpfox::getUserParam('fevent.can_add_gmap') && isset($aVals['gmap']) 
                && is_array($aVals['gmap']) && isset($aVals['gmap']['latitude'])
                && isset($aVals['gmap']['longitude']))
        {
            $aSql['gmap'] = serialize($aVals['gmap']);
            $aSql['lat'] = $aVals['gmap']['latitude'];
            $aSql['lng'] = $aVals['gmap']['longitude'];
        }
        if(empty($aSql['gmap']))
        {
            $sFullAddress = $aSql["location"] . " " . $aSql["address"] . " " . $aSql["city"] . " " . $aSql["country_iso"];
            list($aCoordinates, $sGmapAddress) = $this->address2coordinates($sFullAddress);
            if(!empty($aCoordinates[1]))
            {
                $aSql['lat'] = $aCoordinates[1];
                $aSql['lng'] = $aCoordinates[0];
				$aSql['gmap_address'] = $oParseInput->prepare($sGmapAddress);
            }
        }
		
        /*
		if ($this->_bHasImage)
		{			
			$oImage = Phpfox::getLib('image');
			
			$sFileName = Phpfox::getLib('file')->upload('image', Phpfox::getParam('event.dir_image'), $iId);
			$iFileSizes = filesize(Phpfox::getParam('event.dir_image') . sprintf($sFileName, ''));			
			
			$aSql['image_path'] = $sFileName;
			$aSql['server_id'] = Phpfox::getLib('request')->getServer('PHPFOX_SERVER_ID');
			
			$iSize = 50;			
			$oImage->createThumbnail(Phpfox::getParam('event.dir_image') . sprintf($sFileName, ''), Phpfox::getParam('event.dir_image') . sprintf($sFileName, '_' . $iSize), $iSize, $iSize);			
			$iFileSizes += filesize(Phpfox::getParam('event.dir_image') . sprintf($sFileName, '_' . $iSize));			
			
			$iSize = 120;			
			$oImage->createThumbnail(Phpfox::getParam('event.dir_image') . sprintf($sFileName, ''), Phpfox::getParam('event.dir_image') . sprintf($sFileName, '_' . $iSize), $iSize, $iSize);			
			$iFileSizes += filesize(Phpfox::getParam('event.dir_image') . sprintf($sFileName, '_' . $iSize));

			$iSize = 200;			
			$oImage->createThumbnail(Phpfox::getParam('event.dir_image') . sprintf($sFileName, ''), Phpfox::getParam('event.dir_image') . sprintf($sFileName, '_' . $iSize), $iSize, $iSize);			
			$iFileSizes += filesize(Phpfox::getParam('event.dir_image') . sprintf($sFileName, '_' . $iSize));
			
			// Update user space usage
			Phpfox::getService('user.space')->update(Phpfox::getUserId(), 'fevent', $iFileSizes);
		}
        */
        // Multi-upload
        if ($this->_bHasImage)
        {            
            $oImage = Phpfox::getLib('image');
            $oFile = Phpfox::getLib('file');
            
            $aSizes = array(50, 120, 200);
            
            $iFileSizes = 0;
			
            foreach ($_FILES['image']['error'] as $iKey => $sError)
            {
                if ($sError == UPLOAD_ERR_OK) 
                {            
                    if ($aImage = $oFile->load('image[' . $iKey . ']', array(
                                'jpg',
                                'gif',
                                'png'
                            ), (Phpfox::getUserParam('fevent.max_upload_size_event') === 0 ? null : (Phpfox::getUserParam('fevent.max_upload_size_event') / 1024))
                        )
                    )
                    {                    
                        $sFileName = Phpfox::getLib('file')->upload('image[' . $iKey . ']', Phpfox::getParam('event.dir_image'), $iId);
                        
                        $iFileSizes += filesize(Phpfox::getParam('event.dir_image') . sprintf($sFileName, ''));
                        
                        $this->database()->insert(Phpfox::getT('fevent_image'), array('event_id' => $iId, 'image_path' => $sFileName, 'server_id' => Phpfox::getLib('request')->getServer('PHPFOX_SERVER_ID')));
						//$sFileName= str_replace("/", "\\", $sFileName);
						
						$a=Phpfox::getParam('event.dir_image') . sprintf($sFileName, '');
	
						list($width, $height, $type, $attr) = getimagesize($a);   
						
                        foreach ($aSizes as $iSize)
                        {
                            if($iSize == 120)
                            {
                            	if($width<120 || $height<120)
								{
									$this->resizeImage($sFileName, $width>120?120:$width, $height>120?120:$height, "_120");
								}
								else
                                	$this->resizeImage($sFileName, 120, 120, "_120");
                            }
                            elseif($iSize == 200)
                            {
                            	if($width<200 || $height<200)
								{
									$this->resizeImage($sFileName, $width>200?200:$width, $height>200?200:$height, "_200");
								}
								else {
									$this->resizeImage($sFileName, 160, 200, "_200");
								}
                            }
                            elseif($iSize == 50)   
                            {
								if($width<50 || $height<50)
								{
									$this->resizeImage($sFileName, $width>50?50:$width, $height>50?50:$height, "_50");
								}
                                else
									$this->resizeImage($sFileName, 50, 50, "_50");
                            }
                            else
                            {
                                $oImage->createThumbnail(Phpfox::getParam('event.dir_image') . sprintf($sFileName, ''), Phpfox::getParam('event.dir_image') . sprintf($sFileName, '_' . $iSize), $iSize, $iSize);
                                $oImage->createThumbnail(Phpfox::getParam('event.dir_image') . sprintf($sFileName, ''), Phpfox::getParam('event.dir_image') . sprintf($sFileName, '_' . $iSize . '_square'), $iSize, $iSize, false);
                            }
                            $iFileSizes += filesize(Phpfox::getParam('event.dir_image') . sprintf($sFileName, '_' . $iSize));
                        } 
						

						
						if($width>800)
						{
							if($height>500)
							{
								$this->resizeImage($sFileName, "", 500, "");
							}
							list($width1, $heigh1, $type, $attr) = getimagesize($a);
							if($width1>520)
							{
								$iStartWidth=(int)($width1-520)/2-10;
								$iStartHeight=0;
								$oImage->cropImage($a, $a, 520, 250,$iStartWidth,$iStartHeight,520);
							}
						}                   
                    }
                }
            }
            if ($iFileSizes === 0)
            {
                return false;
            }
            // Update user space usage
            Phpfox::getService('user.space')->update(Phpfox::getUserId(), 'fevent', $iFileSizes);
            $aSql['image_path'] = $sFileName;
            $aSql['server_id'] = Phpfox::getLib('request')->getServer('PHPFOX_SERVER_ID');
        }
        
		if ($sPlugin = Phpfox_Plugin::get('fevent.service_process_update__start')){return eval($sPlugin);}
		$this->database()->update($this->_sTable, $aSql, 'event_id = ' . (int) $iId);
		$this->cache()->remove('event_featured', 'substr');	
		
		$this->database()->update(Phpfox::getT('fevent_text'), array(				
				'description' => (empty($aVals['description']) ? null : $oParseInput->clean($aVals['description'])),
				'description_parsed' => (empty($aVals['description']) ? null : $oParseInput->prepare($aVals['description']))
			), 'event_id = ' . (int) $iId
		);
        
        foreach($aVals['custom'] as $iFieldId => $sValue)
        {
            $this->database()->update(Phpfox::getT('fevent_custom_value'), array(
                    'value' => is_array($sValue) ? json_encode($sValue) : $sValue
                ),
                'event_id = ' . $iId . ' AND field_id = ' . $iFieldId
            );
        }
		
		$aEvent = $this->database()->select('event_id, user_id, title, module_id')
			->from($this->_sTable)
			->where('event_id = ' . (int) $iId)
			->execute('getSlaveRow');		
		
		if (isset($aVals['emails']) || isset($aVals['invite']))
		{		
			$aInvites = $this->database()->select('invited_user_id, invited_email')
				->from(Phpfox::getT('fevent_invite'))
				->where('event_id = ' . (int) $iId)
				->execute('getRows');
			$aInvited = array();
			foreach ($aInvites as $aInvite)
			{
				$aInvited[(empty($aInvite['invited_email']) ? 'user' : 'email')][(empty($aInvite['invited_email']) ? $aInvite['invited_user_id'] : $aInvite['invited_email'])] = true;
			}			
		}
		
		if (isset($aVals['emails']))
		{
			// if (strpos($aVals['emails'], ','))
			{
				$aEmails = explode(',', $aVals['emails']);
				$aCachedEmails = array();
				foreach ($aEmails as $sEmail)
				{
					$sEmail = trim($sEmail);
					if (!Phpfox::getLib('mail')->checkEmail($sEmail))
					{
						continue;
					}
					
					if (isset($aInvited['email'][$sEmail]))
					{
						continue;
					}
					
					$sLink = Phpfox::getLib('url')->permalink('fevent', $aEvent['event_id'], $aEvent['title']);

					$sMessage = Phpfox::getPhrase('fevent.full_name_invited_you_to_the_title', array(
							'full_name' => Phpfox::getUserBy('full_name'),
							'title' => $oParseInput->clean($aVals['title'], 255),
							'link' => $sLink
						)
					);
					if (!empty($aVals['personal_message']))
					{
						$sMessage .= Phpfox::getPhrase('fevent.full_name_added_the_following_personal_message', array(
								'full_name' => Phpfox::getUserBy('full_name')
							)
						) . "\n";
						$sMessage .= $aVals['personal_message'];
					}
					$oMail = Phpfox::getLib('mail');
					if (isset($aVals['invite_from']) && $aVals['invite_from'] == 1)
					{
						$oMail->fromEmail(Phpfox::getUserBy('email'))
								->fromName(Phpfox::getUserBy('full_name'));
					}
					$bSent = $oMail->to($sEmail)
						->subject(array('fevent.full_name_invited_you_to_the_event_title', array('full_name' => Phpfox::getUserBy('full_name'), 'title' => $oParseInput->clean($aVals['title'], 255))))
						->message($sMessage)
						->send();
						
					if ($bSent)
					{
						$this->_aInvited[] = array('email' => $sEmail);
						
						$aCachedEmails[$sEmail] = true;
						
						$this->database()->insert(Phpfox::getT('fevent_invite'), array(
								'event_id' => $iId,
								'type_id' => 1,
								'user_id' => Phpfox::getUserId(),
								'invited_email' => $sEmail,
								'time_stamp' => PHPFOX_TIME
							)
						);
					}
				}
			}
		}
		
		if (isset($aVals['invite']) && is_array($aVals['invite']))
		{
			$sUserIds = '';
			foreach ($aVals['invite'] as $iUserId)
			{
				if (!is_numeric($iUserId))
				{
					continue;
				}
				$sUserIds .= $iUserId . ',';
			}
			$sUserIds = rtrim($sUserIds, ',');
			
			$aUsers = $this->database()->select('user_id, email, language_id, full_name')
				->from(Phpfox::getT('user'))
				->where('user_id IN(' . $sUserIds . ')')
				->execute('getSlaveRows');
				
			foreach ($aUsers as $aUser)
			{
				if (isset($aCachedEmails[$aUser['email']]))
				{
					continue;
				}	
				
				if (isset($aInvited['user'][$aUser['user_id']]))
				{
					continue;
				}
				
				$sLink = Phpfox::getLib('url')->permalink('fevent', $aEvent['event_id'], $aEvent['title']);

				$sMessage = Phpfox::getPhrase('fevent.full_name_invited_you_to_the_title', array(
						'full_name' => Phpfox::getUserBy('full_name'),
						'title' => $oParseInput->clean($aVals['title'], 255),
						'link' => $sLink
					), false,null, $aUser['language_id']);
				if (!empty($aVals['personal_message']))
				{
					$sMessage .= Phpfox::getPhrase('fevent.full_name_added_the_following_personal_message', array(
							'full_name' => Phpfox::getUserBy('full_name')
						), false, null, $aUser['language_id']
					) .":\n". $aVals['personal_message'];
				}
				$bSent = Phpfox::getLib('mail')->to($aUser['user_id'])						
					->subject(array('fevent.full_name_invited_you_to_the_event_title', array('full_name' => Phpfox::getUserBy('full_name'), 'title' => $oParseInput->clean($aVals['title'], 255))))
					->message($sMessage)
					->notification('fevent.invite_to_event')
					->send();
						
				if ($bSent)
				{
					$this->_aInvited[] = array('user' => $aUser['full_name']);	
					
					$iInviteId = $this->database()->insert(Phpfox::getT('fevent_invite'), array(
							'event_id' => $iId,								
							'user_id' => Phpfox::getUserId(),
							'invited_user_id' => $aUser['user_id'],
							'time_stamp' => PHPFOX_TIME
						)
					);
					
					(Phpfox::isModule('request') ? Phpfox::getService('request.process')->add('fevent_invite', $iId, $aUser['user_id']) : null);
				}
			}
		}
		
		$this->database()->delete(Phpfox::getT('fevent_category_data'), 'event_id = ' . (int) $iId);
		foreach ($this->_aCategories as $iCategoryId)
		{
			$this->database()->insert(Phpfox::getT('fevent_category_data'), array('event_id' => $iId, 'category_id' => $iCategoryId));
		}		
				
		if (empty($aEvent['module_id']))
		{
			(Phpfox::isModule('feed') ? Phpfox::getService('feed.process')->update('fevent', $iId, $aVals['privacy'], $aVals['privacy_comment'], 0, $aEvent['user_id']) : null);
		}

		if (Phpfox::VERSION >= '3.7.0' && Phpfox::isModule('tag') && Phpfox::getParam('tag.enable_hashtag_support') && !empty($aVals['description']))
		{
			Phpfox::getService('tag.process')->update('fevent', $aEvent['event_id'], $aEvent['user_id'], $aVals['description'], true);
		}

		return true;
	}
	
    /*
	public function deleteImage($iId)
	{
		$aEvent = $this->database()->select('user_id, image_path')
			->from($this->_sTable)
			->where('event_id = ' . (int) $iId)
			->execute('getRow');		
			
		if (!isset($aEvent['user_id']))
		{
			return Phpfox_Error::set('Unable to find the event.');
		}
			
		if (!Phpfox::getService('user.auth')->hasAccess('fevent', 'event_id', $iId, 'fevent.can_edit_own_event', 'fevent.can_edit_other_event', $aEvent['user_id']))
		{
			return Phpfox_Error::set(Phpfox::getPhrase('fevent.you_do_not_have_sufficient_permission_to_modify_this_event'));
		}			
		
		if (!empty($aEvent['image_path']))
		{
			$aImages = array(
				Phpfox::getParam('event.dir_image') . sprintf($aEvent['image_path'], ''),
				Phpfox::getParam('event.dir_image') . sprintf($aEvent['image_path'], '_50'),
				Phpfox::getParam('event.dir_image') . sprintf($aEvent['image_path'], '_120'),
				Phpfox::getParam('event.dir_image') . sprintf($aEvent['image_path'], '_200')
			);			
			
			$iFileSizes = 0;
			foreach ($aImages as $sImage)
			{
				if (file_exists($sImage))
				{
					$iFileSizes += filesize($sImage);
					
					@unlink($sImage);
				}
			}
			
			if ($iFileSizes > 0)
			{
				Phpfox::getService('user.space')->update($aEvent['user_id'], 'fevent', $iFileSizes, '-');
			}
		}

		$this->database()->update($this->_sTable, array('image_path' => null), 'event_id = ' . (int) $iId);	
		
		return true;
	}
    */
    
    public function setDefault($iImageId)
    {
        $aEvent = $this->database()->select('fei.image_path, fei.server_id, fe.event_id, fe.user_id')
            ->from(Phpfox::getT('fevent_image'), 'fei')
            ->join($this->_sTable, 'fe', 'fe.event_id = fei.event_id')
            ->where('fei.image_id = ' . (int) $iImageId)
            ->execute('getSlaveRow');
            
        if (!isset($aEvent['user_id']))
        {
            return Phpfox_Error::set('Unable to find the image.');
        }

		if (!Phpfox::getService('user.auth')->hasAccess('fevent', 'event_id', $aEvent['event_id'], 'fevent.can_edit_own_event', 'fevent.can_edit_other_event', $aEvent['user_id']))
        {
            return Phpfox_Error::set(Phpfox::getPhrase('fevent.you_do_not_have_sufficient_permission_to_modify_this_event'));
        }
        
        $this->database()->update($this->_sTable, array('image_path' => $aEvent['image_path'], 'server_id' => $aEvent['server_id']), 'event_id = ' . $aEvent['event_id']);
        
        return true;
    }
    
    public function deleteImage($iImageId)
    {
        $aEvent = $this->database()->select('fei.image_id, fei.image_path, fei.server_id, fe.user_id, fe.event_id, fe.image_path AS default_image_path')
            ->from(Phpfox::getT('fevent_image'), 'fei')
            ->join($this->_sTable, 'fe', 'fe.event_id = fei.event_id')
            ->where('fei.image_id = ' . (int) $iImageId)
            ->execute('getSlaveRow');
            
        if (!isset($aEvent['user_id']))
        {
            return Phpfox_Error::set('Unable to find the image.');
        }    

		if (!Phpfox::getService('user.auth')->hasAccess('fevent', 'event_id', $aEvent['event_id'], 'fevent.can_edit_own_event', 'fevent.can_edit_other_event', $aEvent['user_id']))
        {
            return Phpfox_Error::set(Phpfox::getPhrase('fevent.you_do_not_have_sufficient_permission_to_modify_this_event'));
        }            
        
        if ($aEvent['default_image_path'] == $aEvent['image_path'])
        {
            $aImage = $this->database()->select('image_path, server_id')
                ->from(Phpfox::getT('fevent_image'))
                ->where('event_id = ' . $aEvent['event_id'])
                ->execute('getSlaveRow');
            
            $this->database()->update($this->_sTable, array('image_path' => (isset($aImage['image_path']) ? $aImage['image_path'] : null), 'server_id' => (isset($aImage['server_id']) ? $aImage['server_id'] : null)), 'event_id = ' . $aEvent['event_id']);            
        }
        
        $iFileSizes = 0;
        $aSizes = array('', 50, 120, 200);
        foreach ($aSizes as $iSize)
        {
            $sImage = Phpfox::getParam('event.dir_image') . sprintf($aEvent['image_path'], (empty($iSize) ? '' : '_' ) . $iSize);
            if (file_exists($sImage))
            {
                $iFileSizes += filesize($sImage);
                
                @unlink($sImage);
            }
        }
        
        if ($iFileSizes > 0)
        {
            Phpfox::getService('user.space')->update($aEvent['user_id'], 'fevent', $iFileSizes, '-');
        }
        
        $this->database()->delete(Phpfox::getT('fevent_image'), 'image_id = ' . $aEvent['image_id']);
        
        return true;
    }
	
	public function addRsvp($iEvent, $iRsvp, $iUserId)
	{
		if (!Phpfox::isUser())
		{
			return false;
		}
		
		if (($iInviteId = $this->database()->select('invite_id')
			->from(Phpfox::getT('fevent_invite'))
			->where('event_id = ' . (int) $iEvent . ' AND invited_user_id = ' . (int) $iUserId)
			->execute('getField')))
		{
			$this->database()->update(Phpfox::getT('fevent_invite'), array(
					'rsvp_id' => $iRsvp,					
					'invited_user_id' => $iUserId,
					'time_stamp' => PHPFOX_TIME
				), 'invite_id = ' . $iInviteId
			);
			
			(Phpfox::isModule('request') ? Phpfox::getService('request.process')->delete('fevent_invite', $iEvent, $iUserId) : false);
		}
		else 
		{
			$this->database()->insert(Phpfox::getT('fevent_invite'), array(
					'event_id' => $iEvent,			
					'rsvp_id' => $iRsvp,					
					'user_id' => $iUserId,
					'invited_user_id' => $iUserId,
					'time_stamp' => PHPFOX_TIME
				)
			);
		}
		
		return true;
	}
	
	public function deleteGuest($iInviteId)
	{
		$aEvent = $this->database()->select('e.event_id, e.user_id')
			->from(Phpfox::getT('fevent_invite'), 'ei')
			->join($this->_sTable, 'e', 'e.event_id = ei.event_id')
			->where('ei.invite_id = ' . (int) $iInviteId)
			->execute('getRow');
			
		if (!isset($aEvent['user_id']))
		{
			return Phpfox_Error::set(Phpfox::getPhrase('fevent.unable_to_find_the_event'));
		}
			
		if (!Phpfox::getService('user.auth')->hasAccess('fevent', 'event_id', $aEvent['event_id'], 'fevent.can_edit_own_event', 'fevent.can_edit_other_event', $aEvent['user_id']))
		{
			return Phpfox_Error::set(Phpfox::getPhrase('fevent.you_do_not_have_sufficient_permission_to_modify_this_event'));
		}	

		$this->database()->delete(Phpfox::getT('fevent_invite'), 'invite_id = ' . (int) $iInviteId);	
			
		return true;
	}
	
	public function delete($iId, &$aEvent = null)
	{
		if ($sPlugin = Phpfox_Plugin::get('fevent.service_process_delete__start')){return eval($sPlugin);}
	
		$mReturn = true;
		if ($aEvent === null)
		{
			$aEvent = $this->database()->select('user_id, module_id, item_id, image_path, is_sponsor')
				->from($this->_sTable)
				->where('event_id = ' . (int) $iId)
				->execute('getRow');
			
			if ($aEvent['module_id'] == 'pages' && Phpfox::getService('pages')->isAdmin($aEvent['item_id']))
			{
				$mReturn = Phpfox::getService('pages')->getUrl($aEvent['item_id']) . 'fevent/';
			}
			else
			{
				if (!isset($aEvent['user_id']))
				{
					return Phpfox_Error::set(Phpfox::getPhrase('fevent.unable_to_find_the_event_you_want_to_delete'));
				}

				if (!Phpfox::getService('user.auth')->hasAccess('fevent', 'event_id', $iId, 'fevent.can_delete_own_event', 'fevent.can_delete_other_event', $aEvent['user_id']))
				{
					return Phpfox_Error::set(Phpfox::getPhrase('fevent.you_do_not_have_sufficient_permission_to_delete_this_event'));
				}
			}
		}
		
        $aTemp = $this->database()->select("image_path")->from(Phpfox::getT('fevent_image'))->where("event_id = '$iId'")->execute("getRows");
        $this->database()->delete(Phpfox::getT('fevent_image'), "event_id = '$iId'");
        $aThumbs = array();
        foreach($aTemp as $aRow)
        {
            $aThumbs[] = $aRow["image_path"];
        }
        $aThumbs[] = $aEvent['image_path'];
        foreach($aThumbs as $sImagePath)
        {
            $aEvent['image_path'] = $sImagePath;
		    if (!empty($aEvent['image_path']))
		    {
			    $aImages = array(
				    Phpfox::getParam('event.dir_image') . sprintf($aEvent['image_path'], ''),
				    Phpfox::getParam('event.dir_image') . sprintf($aEvent['image_path'], '_50'),
				    Phpfox::getParam('event.dir_image') . sprintf($aEvent['image_path'], '_120'),
				    Phpfox::getParam('event.dir_image') . sprintf($aEvent['image_path'], '_200'),
                    Phpfox::getParam('event.dir_image') . sprintf($aEvent['image_path'], '_50_square')
			    );
                //var_dump($aImages);
			    
			    $iFileSizes = 0;
			    foreach ($aImages as $sImage)
			    {
				    if (file_exists($sImage))
				    {
					    $iFileSizes += filesize($sImage);
					    if ($sPlugin = Phpfox_Plugin::get('fevent.service_process_delete__pre_unlink')){return eval($sPlugin);}
					    @unlink($sImage);
				    }
			    }
			    
			    if ($iFileSizes > 0)
			    {
				    if ($sPlugin = Phpfox_Plugin::get('fevent.service_process_delete__pre_space_update')){return eval($sPlugin);}
				    Phpfox::getService('user.space')->update($aEvent['user_id'], 'fevent', $iFileSizes, '-');
			    }
		    }
        }
		
		if ($sPlugin = Phpfox_Plugin::get('fevent.service_process_delete__pre_deletes')){return eval($sPlugin);}
		
		(Phpfox::isModule('comment') ? Phpfox::getService('comment.process')->deleteForItem(null, $iId, 'fevent') : null);		
		(Phpfox::isModule('feed') ? Phpfox::getService('feed.process')->delete('fevent', $iId) : null);
		(Phpfox::isModule('feed') ? Phpfox::getService('feed.process')->delete('comment_event', $iId) : null);
		
		$aInvites = $this->database()->select('invite_id, invited_user_id')
			->from(Phpfox::getT('fevent_invite'))
			->where('event_id = ' . (int) $iId)
			->execute('getSlaveRows');
		foreach ($aInvites as $aInvite)
		{
			(Phpfox::isModule('request') ? Phpfox::getService('request.process')->delete('fevent_invite', $aInvite['invite_id'], $aInvite['invited_user_id']) : false);			
		}		
		
		$this->database()->delete($this->_sTable, 'event_id = ' . (int) $iId);
		$this->cache()->remove('event_featured', 'substr');
		$this->database()->delete(Phpfox::getT('fevent_text'), 'event_id = ' . (int) $iId);
		$this->database()->delete(Phpfox::getT('fevent_category_data'), 'event_id = ' . (int) $iId);
		$this->database()->delete(Phpfox::getT('fevent_invite'), 'event_id = ' . (int) $iId);
		$iTotalEvent = $this->database()
                        ->select('total_fevent')
                        ->from(Phpfox::getT('user_field'))
                        ->where('user_id =' . (int)$aEvent['user_id'])->execute('getSlaveField');
        $iTotalEvent = $iTotalEvent -1;
        
		if ($iTotalEvent > 0)
		{
			$this->database()->update(Phpfox::getT('user_field'),
                        array('total_fevent' => $iTotalEvent),
                        'user_id = ' . (int)$aEvent['user_id']);
		}
        
		if (isset($aEvent['is_sponsor']) && $aEvent['is_sponsor'] == 1)
		{
			$this->cache()->remove('event_sponsored');
		}
		
		if ($sPlugin = Phpfox_Plugin::get('fevent.service_process_delete__end')){return eval($sPlugin);}
		
		return $mReturn;
	}

	public function feature($iId, $iType)
	{
		Phpfox::isUser(true);
		Phpfox::getUserParam('fevent.can_feature_events', true);
		
		$this->database()->update($this->_sTable, array('is_featured' => ($iType ? '1' : '0')), 'event_id = ' . (int) $iId);
		
		$this->cache()->remove('event_featured', 'substr');
		
		return true;
	}	

	public function sponsor($iId, $iType)
	{
	    if (!Phpfox::getUserParam('fevent.can_sponsor_fevent') && !Phpfox::getUserParam('fevent.can_purchase_sponsor') && !defined('PHPFOX_API_CALLBACK'))
	    {
			return Phpfox_Error::set('Hack attempt?');
	    }
	    
	    $iType = (int)$iType;
	    if ($iType != 1 && $iType != 0)
	    {
			return false;
	    }
	    
	    $this->database()->update($this->_sTable, array('is_featured' => 0, 'is_sponsor' => $iType), 'event_id = ' . (int)$iId);

	    $this->cache()->remove('event_sponsored', 'substr');
		$this->cache()->remove('event_featured', 'substr');
	    
	    if ($sPlugin = Phpfox_Plugin::get('fevent.service_process_sponsor__end')){return eval($sPlugin);}
	    
	    return true;
	}

	public function approve($iId)
	{
		Phpfox::isUser(true);
		Phpfox::getUserParam('fevent.can_approve_events', true);
		
		$aEvent = $this->database()->select('v.*, ' . Phpfox::getUserField())
			->from($this->_sTable, 'v')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = v.user_id')
			->where('v.event_id = ' . (int) $iId)
			->execute('getRow');
			
		if (!isset($aEvent['event_id']))
		{
			return Phpfox_Error::set(Phpfox::getPhrase('fevent.unable_to_find_the_event_you_want_to_approve'));
		}
		
		$this->database()->update($this->_sTable, array('view_id' => '0'), 'event_id = ' . $aEvent['event_id']);
		
		if (Phpfox::isModule('notification'))
		{
			Phpfox::getService('notification.process')->add('fevent_approved', $aEvent['event_id'], $aEvent['user_id']);
		}
		
		// Send the user an email
		$sLink = Phpfox::getLib('url')->permalink('fevent' , $aEvent['event_id'], $aEvent['title']);
		
		Phpfox::getLib('mail')->to($aEvent['user_id'])
			->subject(array('fevent.your_event_has_been_approved_on_site_title', array('site_title' => Phpfox::getParam('core.site_title'))))
			->message(array('fevent.your_event_has_been_approved_on_site_title_link', array('site_title' => Phpfox::getParam('core.site_title'), 'link' => $sLink)))
			->notification('fevent.event_is_approved')
			->send();				

		(Phpfox::isModule('feed') ? Phpfox::getService('feed.process')->add('fevent', $aEvent['event_id'], $aEvent['privacy'], $aEvent['privacy_comment'], 0, $aEvent['user_id']) : null);
			
		return true;
	}	
	
	public function massEmail($iId, $iPage, $sSubject, $sText)
	{
		Phpfox::isUser(true);
		Phpfox::getUserParam('fevent.can_mass_mail_own_members', true);
		
		$aEvent = Phpfox::getService('fevent')->getEvent($iId, true);
		
		if (!isset($aEvent['event_id']))
		{
			return false;
		}
		
		if ($aEvent['user_id'] != Phpfox::getUserId())
		{
			return false;
		}
		if ($sPlugin = Phpfox_Plugin::get('fevent.service_process_massemail__start')){return eval($sPlugin);}
		Phpfox::getService('ban')->checkAutomaticBan($sText);
		list($iCnt, $aGuests) = Phpfox::getService('fevent')->getInvites($iId, 1, $iPage, 20);
		
		$sLink = Phpfox::getLib('url')->permalink('fevent' , $aEvent['event_id'], $aEvent['title']);
		
		$sText = '##<br />
		' . Phpfox::getPhrase('fevent.notice_this_is_a_newsletter_sent_from_the_event') . ': ' . $aEvent['title'] . '<br />
		<a href="' . $sLink . '">' . $sLink . '</a>
		##<br />
		' . $sText;
		
		foreach ($aGuests as $aGuest)
		{
			if ($aGuest['user_id'] == Phpfox::getUserId())
			{
				continue;
			}
			
			Phpfox::getLib('mail')->to($aGuest['user_id'])
				->subject($sSubject)
				->message($sText)
				->notification('fevent.mass_emails')
				->send();			
		}
		if ($sPlugin = Phpfox_Plugin::get('fevent.service_process_massemail__end')){return eval($sPlugin);}
		$this->database()->update($this->_sTable, array('mass_email' => PHPFOX_TIME), 'event_id = ' . $aEvent['event_id']);
		
		return $iCnt;
	}
	
	public function removeInvite($iId)
	{
		$this->database()->delete(Phpfox::getT('fevent_invite'), 'event_id = ' . (int) $iId . ' AND invited_user_id = ' . Phpfox::getUserId());
		
		(Phpfox::isModule('request') ? Phpfox::getService('request.process')->delete('fevent_invite', $iId, Phpfox::getUserId()) : false);
		
		return true;
	}
	
	/**
	 * If a call is made to an unknown method attempt to connect
	 * it to a specific plug-in with the same name thus allowing 
	 * plug-in developers the ability to extend classes.
	 *
	 * @param string $sMethod is the name of the method
	 * @param array $aArguments is the array of arguments of being passed
	 */
	public function __call($sMethod, $aArguments)
	{
		/**
		 * Check if such a plug-in exists and if it does call it.
		 */
		if ($sPlugin = Phpfox_Plugin::get('fevent.service_process__call'))
		{
			return eval($sPlugin);
		}
			
		/**
		 * No method or plug-in found we must throw a error.
		 */
		Phpfox_Error::trigger('Call to undefined method ' . __CLASS__ . '::' . $sMethod . '()', E_USER_ERROR);
	}

	private function _verify(&$aVals, $bIsUpdate = false)
	{				
		/*
		if (!isset($aVals['category']))
		{
			return Phpfox_Error::set(Phpfox::getPhrase('fevent.provide_a_category_this_event_will_belong_to'));
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
			return Phpfox_Error::set(Phpfox::getPhrase('fevent.provide_a_category_this_event_will_belong_to'));
		}		
		*/
		
        /*
		if (isset($_FILES['image']['name']) && ($_FILES['image']['name'] != ''))
		{
			$aImage = Phpfox::getLib('file')->load('image', array(
					'jpg',
					'gif',
					'png'
				), (Phpfox::getUserParam('fevent.max_upload_size_event') === 0 ? null : (Phpfox::getUserParam('fevent.max_upload_size_event') / 1024))
			);
			
			if ($aImage === false)
			{
				return false;
			}
			
			$this->_bHasImage = true;
		}
        */
        
        if (isset($_FILES['image']))
        {
            foreach ($_FILES['image']['error'] as $iKey => $sError)
            {
                if ($sError == UPLOAD_ERR_OK) 
                {            
                    $aImage = Phpfox::getLib('file')->load('image[' . $iKey . ']', array(
                            'jpg',
                            'gif',
                            'png'
                        )
                    );
                    
                    if ($aImage === false)
                    {
                        continue;
                    }
                    
                    $this->_bHasImage = true;
                }
            }
        }
		
		//if ($bIsUpdate === false)
		{			
			$iStartTime = Phpfox::getLib('date')->mktime($aVals['start_hour'], $aVals['start_minute'], 0, $aVals['start_month'], $aVals['start_day'], $aVals['start_year']);
			$iEndTime = Phpfox::getLib('date')->mktime($aVals['end_hour'], $aVals['end_minute'], 0, $aVals['end_month'], $aVals['end_day'], $aVals['end_year']);			
			
			if ($iEndTime < $iStartTime)
			{
				// return Phpfox_Error::set(Phpfox::getPhrase('fevent.your_event_is_ending_before_it_starts'));
				$this->_bIsEndingInThePast = true;
			}
			/*
			if (Phpfox::getLib('date')->convertToGmt($iStartTime) < PHPFOX_TIME)
			{
				return Phpfox_Error::set(Phpfox::getPhrase('fevent.your_event_is_starting_in_the_past'));
			}
			 * 
			 */
		}

		return true;	
	}
    
    public function resizeImage($sFilePath, $iThumbWidth, $iThumbHeight, $sSubfix)
    {
        $sRealPath = Phpfox::getParam('event.dir_image');
        // Resize to Width/Height
        list($iWidth, $iHeight, $sType, $sAttr) = getimagesize($sRealPath . sprintf($sFilePath, ""));
        $iNewWidth = $iWidth;
        $iNewHeight = $iHeight;
        $fSourceRatio = $iWidth / $iHeight;
        $fThumbRatio = $iThumbWidth / $iThumbHeight;
        if($fSourceRatio > $fThumbRatio)
        {
            $iNewHeight = $iThumbHeight;
            $fRatio = $iNewHeight / $iHeight;
            $iNewWidth = $iWidth * $fRatio;
        }
        else
        {
            $iNewWidth = $iThumbWidth;
            $fRatio = $iNewWidth / $iWidth;
            $iNewHeight = $iHeight * $fRatio;                            
        }

        Phpfox::getLib("image")->createThumbnail($sRealPath . sprintf($sFilePath, ""), $sRealPath . sprintf($sFilePath, $sSubfix), $iNewWidth, $iNewHeight, true, false);

        // Crop the resized image
        if($iNewWidth > $iThumbWidth)
        {
            $iX = ceil(($iNewWidth - $iThumbWidth)/2);
            Phpfox::getLib("image")->cropImage($sRealPath . sprintf($sFilePath, $sSubfix), $sRealPath . sprintf($sFilePath, '_temp'), $iThumbWidth, $iThumbHeight, $iX, 0, $iThumbWidth);
            copy($sRealPath . sprintf($sFilePath, '_temp'), $sRealPath . sprintf($sFilePath, $sSubfix));
            unlink($sRealPath . sprintf($sFilePath, '_temp'));
        }
        if($iNewHeight > $iThumbHeight)
        {
            $iY = ceil(($iNewHeight - $iThumbHeight)/2);
            Phpfox::getLib("image")->cropImage($sRealPath . sprintf($sFilePath, $sSubfix), $sRealPath . sprintf($sFilePath, '_temp'), $iThumbWidth, $iThumbHeight, 0, $iY, $iThumbWidth);
            copy($sRealPath . sprintf($sFilePath, '_temp'), $sRealPath . sprintf($sFilePath, $sSubfix));
            unlink($sRealPath . sprintf($sFilePath, '_temp'));
        }
    }
    
    public function address2coordinates($sAddress)
    {
        $apiaddress = "http://maps.googleapis.com/maps/api/geocode/json?address=".  urlencode($sAddress)."&sensor=true";
        $aResponse = json_decode(Phpfox::getLib('request')->send($apiaddress, array(), 'GET', $_SERVER['HTTP_USER_AGENT']), true);
        $tmpaCoordinates = $aResponse['results'][0]['geometry']['location'];
        $aCoordinates[1] = $tmpaCoordinates['lat'];
        $aCoordinates[0] = $tmpaCoordinates['lng'];
        $sGmapAddress = $aResponse['results'][0]['formatted_address'];
       
        return array($aCoordinates, $sGmapAddress);
    }
}

?>
