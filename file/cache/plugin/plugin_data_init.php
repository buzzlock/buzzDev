<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = '$sCacheId = Phpfox::getLib(\'cache\')->set(\'373f8034a282365715a1d3a6f335bb31\');

if (!Phpfox::getLib(\'cache\')->get($sCacheId))
{
	$aRow = Phpfox::getLib(\'database\')->select(\'value_actual\')->from(Phpfox::getT(\'setting\'))->where(\'var_name LIKE "checked_socialstream_feeds"\')->execute(\'getRow\');
	$isModuleSocialStream = Phpfox::isModule(\'socialstream\');
	$oDb = Phpfox::getLib(\'database\');
	if(count($aRow) == 0)
	{
		$aInsert = array(\'module_id\' => \'admincp\',
						 \'product_id\' => \'phpfox\',
						 \'is_hidden\' => 1,
						 \'version_id\'=> \'2.0.0rc1\',
						 \'type_id\' => \'boolean\',
						 \'var_name\' => \'checked_socialstream_feeds\',
						 \'phrase_var_name\' => "Checked Social Stream Feeds",
						 \'value_actual\' => $isModuleSocialStream ? 1 : 0,
						 \'value_default\' => $isModuleSocialStream ? 1 : 0 ,
						 \'ordering\' => 1,
		);		
		$oDb->insert(Phpfox::getT(\'setting\'),$aInsert);
		$aRow = $aInsert;
	}
	
    $isCheckedSocialStream = (int) $aRow[\'value_actual\'];
    
    if ($isModuleSocialStream && !$isCheckedSocialStream) //enable && 0
    {
        $sSQL1 = "UPDATE `" . Phpfox::getT(\'feed\') . "` SET `privacy` = `privacy` - 7,`feed_reference` = `feed_reference` -7  WHERE `type_id` LIKE \'socialstream_%\' AND `item_id` IS NOT NULL AND `feed_reference` IS NOT NULL AND `privacy` >= 7";
        Phpfox::getLib(\'database\')->query($sSQL1);
     
		$sSQL2 = "UPDATE `" . Phpfox::getT(\'setting\') . "` SET `value_actual` = \'1\',`value_default` = \'1\' WHERE `var_name` = \'checked_socialstream_feeds\' AND `module_id` = \'admincp\'";
        Phpfox::getLib(\'database\')->query($sSQL2);
    }
    else if (!$isModuleSocialStream && $isCheckedSocialStream) //disable && 1
    {        
        $sSQL1 = "UPDATE `" . Phpfox::getT(\'feed\') . "` SET `privacy` = `privacy` + 7,`feed_reference` = `feed_reference` +7 WHERE `type_id` LIKE \'socialstream_%\' AND `item_id` IS NOT NULL AND `feed_reference` IS NOT NULL AND `privacy` < 7";
        Phpfox::getLib(\'database\')->query($sSQL1);
        
		$sSQL2 = "UPDATE `" . Phpfox::getT(\'setting\') . "` SET `value_actual` = \'0\',`value_default` = \'0\' WHERE `var_name` = \'checked_socialstream_feeds\' AND `module_id` = \'admincp\'";
        Phpfox::getLib(\'database\')->query($sSQL2);
    }
    Phpfox::getLib(\'cache\')->save($sCacheId, true);
} if (Phpfox::getParam(\'facebook.enable_facebook_connect\'))
{
	if (!empty($_REQUEST[\'facebook-process-login\']))
	{	
		if (!empty($_REQUEST[\'code\']))
		{
			switch ($_REQUEST[\'facebook-process-login\'])
			{
				case \'sync-email\':
					if ($oObject = Phpfox::getService(\'facebook\')->get(\'me\', Phpfox::getParam(\'core.path\') . \'index.php?facebook-process-login=sync-email\'))
					{
						if (isset($oObject->id))
						{
							Phpfox::getService(\'facebook.process\')->syncAccounts($oObject->email, $oObject->id);
							
							$aUserCache = Phpfox::getService(\'facebook\')->checkEmail($oObject->email);
							
							list($bIsLoggedIn, $aPostUserInfo) = Phpfox::getService(\'user.auth\')->login($aUserCache[\'user_name\'], null, false, \'user_name\', true);
							
							if ($bIsLoggedIn)
							{
								Phpfox::getLib(\'url\')->send(Phpfox::getParam(\'user.redirect_after_login\'));	
							}
							else 
							{
								Phpfox::getLib(\'url\')->send(\'facebook.account\', array(\'type\' => \'no-login\'));
							}
						}
						else 
						{
							Phpfox::getLib(\'url\')->send(\'facebook.account\', array(\'type\' => \'no-account\'));
						}
					}					
					break;
				default:							
					if (!($oObject = Phpfox::getService(\'facebook\')->get(\'me\', Phpfox::getParam(\'core.path\') . \'index.php?facebook-process-login=true\')))
					{
						Phpfox::getLib(\'url\')->send(\'facebook.account\', array(\'type\' => \'no-account\'));	
					}
		
					if (isset($oObject->id))
					{
						$aUser = Phpfox::getService(\'facebook\')->getUser($oObject->id);
						if (!isset($aUser[\'user_id\']) && isset($oObject->email))
						{
							if ($aUserCache = Phpfox::getService(\'facebook\')->checkEmail($oObject->email))
							{						
								if (isset($aUserCache[\'user_id\']) && empty($aUserCache[\'fb_user_id\']))
								{
									Phpfox::getLib(\'database\')->insert(Phpfox::getT(\'fbconnect\'), array(\'user_id\' => $aUserCache[\'user_id\'], \'fb_user_id\' => (int) $oObject->id));
								}
								else
								{
									Phpfox::getLib(\'database\')->update(Phpfox::getT(\'fbconnect\'), array(\'is_unlinked\' => 0), \'user_id = \' . (int) $aUserCache[\'user_id\']);
								}
								
								list($bIsLoggedIn, $aPostUserInfo) = Phpfox::getService(\'user.auth\')->login($aUserCache[\'user_name\'], null, false, \'user_name\', true);
								if ($bIsLoggedIn)
								{							
									Phpfox::getLib(\'url\')->send(Phpfox::getParam(\'user.redirect_after_login\'));	
								}																	
							}
						}
						
						if (isset($aUser[\'user_id\']))
						{
							Phpfox::getLib(\'database\')->update(Phpfox::getT(\'fbconnect\'), array(\'is_unlinked\' => 0), \'user_id = \' . (int) $aUser[\'user_id\']);
							
							list($bIsLoggedIn, $aPostUserInfo) = Phpfox::getService(\'user.auth\')->login($aUser[\'user_name\'], null, false, \'user_name\', true);
							if ($bIsLoggedIn)
							{						
								Phpfox::getLib(\'url\')->send(Phpfox::getParam(\'user.redirect_after_login\'));	
							}							
						}
						
						$aUserInfo = (array) $oObject;
				
						if (is_array($aUserInfo))
						{
							$aVals[\'full_name\'] = $aUserInfo[\'name\'];

							if (isset($aUserInfo[\'first_name\']))
							{
								$aVals[\'first_name\'] = $aUserInfo[\'first_name\'];
							}
							if (isset($aUserInfo[\'last_name\']))
							{
								$aVals[\'last_name\'] = $aUserInfo[\'last_name\'];
							}
							
							if (empty($aVals[\'full_name\']))
							{
								Phpfox::getLib(\'url\')->send(\'facebook.account\', array(\'type\' => \'full-name\'));
							}					
							
							if (!empty($aUserInfo[\'link\']))
							{
								if (preg_match(\'/http:\\/\\/(.*?)\\.facebook\\.com\\/(.*)/i\', $aUserInfo[\'link\'], $aMatches) && isset($aMatches[2]))
								{
									$aVals[\'user_name\'] = (substr($aMatches[2], 0, 11) == \'profile.php\' ? $aUserInfo[\'id\'] : $aMatches[2]);
								}			
							}							

							if (empty($aVals[\'user_name\']))
							{
								$aVals[\'user_name\'] = $aUserInfo[\'name\'];
							}
							
							Phpfox::getService(\'user.validate\')->email($aUserInfo[\'email\']);					
							if (Phpfox_Error::get())
							{
								Phpfox::getLib(\'url\')->send(\'facebook.account\', array(\'type\' => \'email\'));
							}
							
							$aVals[\'user_name\'] = Phpfox::getLib(\'parse.input\')->prepareTitle(\'user\', $aVals[\'user_name\'], \'user_name\', null, Phpfox::getT(\'user\'));
							$aVals[\'email\'] = $aUserInfo[\'email\'];
							$aVals[\'password\'] = md5($aUserInfo[\'id\']);
							$aVals[\'gender\'] = ($aUserInfo[\'gender\'] == \'female\' ? \'2\' : \'1\');
							$aVals[\'country_iso\'] = null;	
							
							if (empty($aUserInfo[\'birthday\']))
							{
								$aVals[\'day\'] = \'1\';
								$aVals[\'month\'] = \'1\';
								$aVals[\'year\'] = \'1982\';
							}
							else 
							{
								$aParts = explode(\'/\', $aUserInfo[\'birthday\']);		
								$aVals[\'day\'] = (isset($aParts[1]) ? $aParts[1] : \'1\');
								$aVals[\'month\'] = (isset($aParts[0]) ? $aParts[0] : \'1\');
								$aVals[\'year\'] = (isset($aParts[2]) ? $aParts[2] : \'1982\');
							}	
							
							if (!defined(\'PHPFOX_SKIP_EMAIL_INSERT\'))
							{
								define(\'PHPFOX_SKIP_EMAIL_INSERT\', true);
							}
							if (!defined(\'PHPFOX_IS_FB_USER\'))
							{
								define(\'PHPFOX_IS_FB_USER\', true);
							}						
							
							$iUserId = Phpfox::getService(\'user.process\')->add($aVals);
							
							if ($iUserId === false)
							{
								Phpfox::getLib(\'url\')->send(\'facebook.account\', array(\'type\' => \'no-account\', \'error\' => serialize(Phpfox_Error::get())));
							}
							else 
							{
								Phpfox::getService(\'facebook.process\')->addUser($iUserId, $aUserInfo[\'id\']);					
								
								$sImage = \'https://graph.facebook.com/me/picture?type=large&access_token=\' . Phpfox::getService(\'facebook\')->getToken();
								Phpfox::getLib(\'file\')->writeToCache(\'fb_\' . $iUserId . \'_\' . md5($sImage), file_get_contents($sImage));							
								$sNewImage = \'fb_\' . $iUserId . \'_\' . md5($sImage) . \'%s.jpg\';
								copy(PHPFOX_DIR_CACHE . \'fb_\' . $iUserId . \'_\' . md5($sImage), Phpfox::getParam(\'core.dir_user\') . sprintf($sNewImage, \'\'));
								foreach(Phpfox::getParam(\'user.user_pic_sizes\') as $iSize)
								{
									Phpfox::getLib(\'image\')->createThumbnail(Phpfox::getParam(\'core.dir_user\') . sprintf($sNewImage, \'\'), Phpfox::getParam(\'core.dir_user\') . sprintf($sNewImage, \'_\' . $iSize), $iSize, $iSize);
									Phpfox::getLib(\'image\')->createThumbnail(Phpfox::getParam(\'core.dir_user\') . sprintf($sNewImage, \'\'), Phpfox::getParam(\'core.dir_user\') . sprintf($sNewImage, \'_\' . $iSize . \'_square\'), $iSize, $iSize, false);
								}	
								unlink(PHPFOX_DIR_CACHE . \'fb_\' . $iUserId . \'_\' . md5($sImage));
									
								Phpfox::getLib(\'database\')->update(Phpfox::getT(\'user\'), array(\'user_image\' => $sNewImage, \'server_id\' => 0), \'user_id = \' . (int) $iUserId);
								
								if (Phpfox::getService(\'user.auth\')->login($aVals[\'user_name\'], null, false, \'user_name\', true))
								{
									Phpfox::getLib(\'url\')->send(\'\');	
								}
								else 
								{
									Phpfox::getLib(\'url\')->send(\'facebook.account\', array(\'type\' => \'no-login\'));
								}								
							}
						}
					}		
					
					exit;
					break;
			}
		}
	}
} if(Phpfox::isModule(\'fanot\'))
{
    $bgcolor = (Phpfox::getParam(\'fanot.notification_bgcolor\')!=\'\') ? Phpfox::getParam(\'fanot.notification_bgcolor\') : \'#CAD1DE\';
	Phpfox::getLib(\'template\')->setHeader(\' <style type=\\"text/css\\"> .fanotui .fanot_item:hover {background-color: \'.$bgcolor.\' !important;} .fanotui .fanot_selected {background: \'.$bgcolor.\' !important;} </style> \');
} if (Phpfox::isMobile()) {
	Phpfox::getLib(\'setting\')->setParam(\'comment.load_delayed_comments_items\',false);
} if (isset($_REQUEST[\'share-connect\']))
{
	Phpfox::getComponent(\'share.connect\', array(), \'controller\');	
	exit;
} //This plugin is no longer used. if (!PHPFOX_IS_AJAX)
{
	$mRedirectId = Phpfox::getService(\'subscribe.purchase\')->getRedirectId();
	if (is_numeric($mRedirectId) && $mRedirectId > 0)
	{
		Phpfox::getLib(\'url\')->send(\'subscribe.register\', array(\'id\' => $mRedirectId), Phpfox::getPhrase(\'subscribe.please_complete_your_purchase\'));	
	}
} $sYounetLibPath = PHPFOX_DIR . \'module\' . PHPFOX_DS . \'younetcore\' . PHPFOX_DS . \'include\' . PHPFOX_DS . \'service\' . PHPFOX_DS . \'libs\' . PHPFOX_DS;
if (!class_exists(\'Younet_Cache\'))
{	
	require_once($sYounetLibPath . \'yncache.class.php\');
}
if (!class_exists(\'Younet_Service\'))
{
	require_once($sYounetLibPath . \'ynservice.class.php\');
} '; ?>