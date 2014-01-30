<?php

/**
 * @package mfox
 * @version 3.01
 */
defined('PHPFOX') or exit('NO DICE!');

/**
 * @author ductc@younetco.com
 * @package mfox
 * @subpackage mfox.service
 * @version 3.01
 * @since June 5, 2013
 * @link Mfox Api v1.0
 */
class Mfox_Service_User extends Phpfox_Service
{
	CONST COVER_SIZE  = '_500';
	/**
     * Input data:
     * 
     * Output data:
     * + sFullName: string.
     * + CoverImg_Url: string.
     * + iUserId: int.
     * + UserProfileImg_Url: string.
     * + sWorkat: string.
     * + sGraduated: string.
     * + sFrom: string.
     * + isFriend: bool.
     * + PhotoImg_Url: string.
     * + FriendImg1_Url: string.
     * + FriendImg2_Url: string.
     * + FriendImg3_Url: string.
     * + FriendImg4_Url: string.
     * + FriendImg5_Url: string.
     * + FriendImg6_Url: string.
     * + PhotoImage_Url: string.
     * + CoverImg_Url: string.
     * 
     * @see Mobile - API phpFox/Api V1.0 - Restful.
     * @see home
     * 
     * @param array $aVals
     * @param int $iForceUserId
     * @return array
     */
    function getByIdAction($aData, $iId)
    {
        return $this->home($aData, $iId);
    }
    
    /**
     * Input data:
     * + sEmail: string, required.
     * 
     * Output data:
     * + error_code: int.
     * + error_message: string.
     * 
     * @see Mobile - API phpFox/Api V2.0.
     * @see user/forgot
     * 
     * @see Phpfox_Mail
     * @param array $aData
     * @return array
     */
    public function forgot($aData)
    {
        /**
         * @var string.
         */
        $sEmail = isset($aData['sEmail']) ? $aData['sEmail'] : '';
        /**
         * @var array
         */
        $aUser = $this->database()->select('user_id, profile_page_id, email, full_name')
			->from($this->_sTable)
			->where('email = \'' . $this->database()->escape($sEmail) . '\'')
			->execute('getRow');
			
		if (!isset($aUser['user_id']))
		{
            return array(
                'error_message' => Phpfox::getPhrase('user.not_a_valid_email'),
                'error_code' => 1
            );
		}
		
		if (empty($aUser['email']) || $aUser['profile_page_id'] > 0)
		{
            return array(
                'error_message' => 'Unable to attain a password for this account.',
                'error_code' => 1
            );
		}
			
		// Send the user an email
		$sHash = md5($aUser['user_id'] . $aUser['email'] . Phpfox::getParam('core.salt'));
		$sLink = Phpfox::getLib('url')->makeUrl('user.password.verify', array('id' => $sHash));
        
		Phpfox::getLib('mail')->to($aUser['user_id'])
			->subject(array('user.password_request_for_site_title', array('site_title' => Phpfox::getParam('core.site_title'))))
			->message(array('user.you_have_requested_for_us_to_send_you_a_new_password_for_site_title', array(
						'site_title' => Phpfox::getParam('core.site_title'),
						'link' => $sLink
					)
				)
			)
			->send();
		
		$this->database()->delete(Phpfox::getT('password_request'), 'user_id = ' . $aUser['user_id']);
		$this->database()->insert(Phpfox::getT('password_request'), array(
				'user_id' => $aUser['user_id'],
				'request_id' => $sHash,
				'time_stamp' => PHPFOX_TIME
			)
		);
		
        return array(
            'error_message' => Phpfox::getPhrase('user.password_request_successfully_sent_check_your_email_to_verify_your_request'),
            'error_code' => 0
        );
    }
    /**
     * Validate for register.
     * @param string $sStep
     * @return string
     */
    public function getValidation($sStep = null)
	{		
		$aValidation = array();

		if ($sStep == 1 || $sStep === null)
		{
			$aValidation['full_name'] = Phpfox::getPhrase('user.provide_your_full_name');
			
			$aValidation['email'] = array(
				'def' => 'email',
				'title' => Phpfox::getPhrase('user.provide_a_valid_email_address')
			);
			$aValidation['password'] = array(
				'def' => 'password',
				'title' => Phpfox::getPhrase('user.provide_a_valid_password')
			);
			
		}
		return $aValidation;
	}
    
    /**
     * Input data:
     * + sFullName: string, required.
     * + sEmail: string, required.
     * + sPassword: string, required.
     * + iMonth: int, required.
     * + iDay: int, required.
     * + iYear: int, required.
     * + iGender: int, required.
     * 
     * Output data:
     * + error_message: string.
     * + error_code: int.
     * + result: int.
     * + user_id: int.
     * + full_name: string.
     * + user_name: string.
     * + profileimage: string.
     * + token: string.
     * 
     * @see Mobile - API phpFox/Api V2.0.
     * @see user/register
     * 
     * @param array $aData
     * @return array
     */
    public function register($aData)
    {
        /**
         * @var array
         */
        $aVals = array(
            'full_name' => isset($aData['sFullName']) ? $aData['sFullName'] : '',
            'email' => isset($aData['sEmail']) ? $aData['sEmail'] : '',
            'password' => isset($aData['sPassword']) ? $aData['sPassword'] : '',
            'month' => isset($aData['iMonth']) ? (int) $aData['iMonth'] : '',
            'day' => isset($aData['iDay']) ? (int) $aData['iDay'] : '',
            'year' => isset($aData['iYear']) ? (int) $aData['iYear'] : '',
            'gender' => isset($aData['iGender']) ? (int) $aData['iGender'] : ''
        );
        
        $this->email($aVals['email']);
        
        $oValid = Phpfox::getLib('validator')->set(array('sFormName' => 'js_form', 'aParams' => $this->getValidation()));
        
        if ($oValid->isValid($aVals))
        {
            if ($aVals['day'] < 0 || $aVals['day'] > 31)
            {
                Phpfox_Error::set('Day is not valid!');
            }
            
            if ($aVals['month'] < 0 || $aVals['month'] > 12)
            {
                Phpfox_Error::set('Month is not valid!');
            }
            
            if ($aVals['year'] < Phpfox::getParam('user.date_of_birth_start') || $aVals['year'] > Phpfox::getParam('user.date_of_birth_end'))
            {
                Phpfox_Error::set('Year is not valid!');
            }
            
            if (Phpfox_Error::isPassed())
            {
                if ($iId = Phpfox::getService('user.process')->add($aVals))
                {
                    return $this->login($aData);
                }
            }
        }
        
        return array(
            'error_message' => Phpfox_Error::get(),
            'error_code' => 1
        );
    }

    /**
     * Check email is ban.
     * @param string $sEmail
     * @return \Mfox_Service_User
     */
    private function email($sEmail)
	{
		$iCnt = $this->database()->select('COUNT(*)')
			->from($this->_sTable)
			->where("email = '" . $this->database()->escape($sEmail) . "'")
			->execute('getField');
		
		if ($iCnt)
		{
            $sMessage = 'There is already an account assigned with the email "' . trim(strip_tags($sEmail)) . '". If this is your email please login.';
			Phpfox_Error::set($sMessage);
		}
		
		if (!Phpfox::getService('ban')->check('email', $sEmail))
		{
			Phpfox_Error::set(Phpfox::getPhrase('user.this_email_is_not_allowed_to_be_used'));
		}		
		
		return $this;
	}
    
	private $_aUser = array();

	/**
	 * constructor
	 * @return void
	 */
	function __construct()
	{
		$this -> _sTable = Phpfox::getT('user');
	}

    /**
     * Input data:
     * 
     * Output data:
     * + sFullName: string.
     * + CoverImg_Url: string.
     * + iUserId: int.
     * + UserProfileImg_Url: string.
     * + sWorkat: string.
     * + sGraduated: string.
     * + sFrom: string.
     * + isFriend: bool.
     * + PhotoImg_Url: string.
     * + FriendImg1_Url: string.
     * + FriendImg2_Url: string.
     * + FriendImg3_Url: string.
     * + FriendImg4_Url: string.
     * + FriendImg5_Url: string.
     * + FriendImg6_Url: string.
     * + PhotoImage_Url: string.
     * + CoverImg_Url: string.
     * 
     * @param array $aVals
     * @param int $iForceUserId
     * @return array
     */
    function home($aVals, $iForceUserId = 0)
    {
        extract($aVals, EXTR_SKIP);

        if ($iForceUserId > 0)
        {
            $iUserId = $iForceUserId;
        }
		
		if (!isset($iUserId))
		{
			$iUserId = Phpfox::getUserId();
		}
        /**
         * @var array
         */
		$aUser = Phpfox::getService('user') -> get($iUserId);
        /**
         * @var bool
         */
		$hasImg  = FALSE;
		if (isset($aUser['user_image']) && $aUser['user_image'] != '')
		{
			$img = Phpfox::getParam('core.url_user') . $aUser['user_image'];
			$hasImg = TRUE;
		}
		else
		{
			$img = Phpfox::getParam('core.path') . 'theme/frontend/default/style/default/image/noimage/profile_%s.png';
		}

		list($iCount, $aFriends) =  Phpfox::getService('friend')->get($aCond ='', $sSort = 'friend.time_stamp DESC', $iPage = '', $sLimit = 6, $bCount = true, $bAddDetails = false, $bIsOnline = false, $iUserId , $bIncludeList = false, $iListId = 0);

		$aFriendImages = array();
		
		for($i = 0; $i<6; ++$i)
		{
			if(isset($aFriends[$i]) && isset($aFriends[$i]['user_image']) && is_file(Phpfox::getParam('core.dir_pic') . 'user' . PHPFOX_DS . sprintf($aFriends[$i]['user_image'], MAX_SIZE_OF_USER_IMAGE)))
			{
				$aFriendImages[] =  Phpfox::getParam('core.url_user') .  sprintf($aFriends[$i]['user_image'], MAX_SIZE_OF_USER_IMAGE);
			}else{
				$aFriendImages[] =  Phpfox::getParam('core.path') . 'theme/frontend/default/style/default/image/noimage/profile_50.png';
			}
		}
		
		if($aUser['cover_photo']){
			$aCoverPhoto  = Phpfox::getService('photo')->getCoverPhoto($aUser['cover_photo']);
			if($aCoverPhoto){
				$aCoverPhotoUrl  =  Phpfox::getParam('photo.url_photo') . sprintf($aCoverPhoto['destination'],self::COVER_SIZE);
			}
		}
        
        $aPhotos = Phpfox::getService('mfox.photo')->getMyLatestPhoto($iUserId);
        $sPhotoImageUrl = Phpfox::getLib('image.helper')->display(array('theme' => 'noimage/profile_50.png', 'return_url' => true));
        if (isset($aPhotos[0]['sPhotoUrl']))
        {
            $sPhotoImageUrl = $aPhotos[0]['sPhotoUrl'];
        }
		return array(
			'sFullName' => $aUser['full_name'],
			'CoverImg_Url'=>$aUser['cover_photo'],
			'iUserId' => $iUserId,
			'UserProfileImg_Url' => sprintf($img,$hasImg? '_100_square' :'100'),
			'sWorkat' => '[no data]',
			'sGraduated' => '[no data]',
			'sFrom' => $aUser['city_location'],
			'isFriend' => $aUser['is_friend'],
			'PhotoImg_Url' => $sPhotoImageUrl,
			'FriendImg1_Url' => $aFriendImages[0],
			'FriendImg2_Url' => $aFriendImages[1],
			'FriendImg3_Url' => $aFriendImages[2],
			'FriendImg4_Url' => $aFriendImages[3],
			'FriendImg5_Url' => $aFriendImages[4],
			'FriendImg6_Url' => $aFriendImages[5],
			'PhotoImage_Url' => sprintf($img,$hasImg? '_100_square' :'100'),
            'CoverImg_Url' => $aCoverPhotoUrl,
		);	
	}

	/**
     * Get user by email.
     * @param string $sEmail
     * @return array|null
     */
	function getUserByEmail($sEmail)
	{
		$sEmail = $this -> database() -> escape($sEmail);
		$sCond = "email='{$sEmail}'";
		$aRow = $this -> database() -> select('*') -> from($this -> _sTable) -> where($sCond) -> execute('getSlaveRow');
		return $aRow;
	}

	/**
	 * @see User_Service_Auth
	 * @see Phpfox_Hash
	 *
	 * @param string $sPasswordHash hashed password in database
	 * @param string $sPasswordSalt salt string int database
	 * @param string $sPassword password string send from client
	 * @return TRUE|FALSE
	 */
	function checkPassword($sPasswordHash, $sPasswordSalt, $sPassword)
	{
		return $sPasswordHash == Phpfox::getLib('hash') -> setHash($sPassword, $sPasswordSalt);
	}

	/**
	 * login user id
	 * <pre>
	 * result: array{token: , error_code, error_message, user_id}
	 * </pre>
	 * @return array {token: required}
	 */

	/**
     * Input data:
     * + sEmail: string, required.
     * + sPassword: string, required.
     * 
     * Output data:
     * + error_message: string.
     * + error_code: int.
     * + result: int.
     * + user_id: int.
     * + full_name: string.
     * + user_name: string.
     * + profileimage: string.
     * + token: string.
     * 
     * @see Mobile - API phpFox/Api V2.0.
     * @see user/login
     * 
     * @param array $aData
     * @return array
     */
	function login($aData)
	{
        /**
         * @var string
         */
		$sPassword = isset($aData['sPassword']) ? $aData['sPassword'] : '';
        /**
         * @var string
         */
        $sEmail = isset($aData['sEmail']) ? $aData['sEmail'] : '';
        /**
         * @var array
         */
		$aUser = $this -> getUserByEmail($sEmail);
		if (!$aUser)
		{
			return array(
				'error_message' => 'invalid email',
				'error_code' => 1,
				'result' => 0
			);
		}
		if (!$this -> checkPassword($sPasswordHash = $aUser['password'], $sPasswordSalt = $aUser['password_salt'], $sPassword))
		{
			return array(
				'error_message' => 'invalid password',
				'error_code' => 1,
				'result' => 0
			);
		}
        // ban check
		$oBan = Phpfox::getService('ban');
		if (!$oBan->check('email', $sEmail))
		{
			Phpfox_Error::set(Phpfox::getPhrase('ban.global_ban_message'));
		}
        /**
         * @var array
         */
		$aBanned = Phpfox::getService('ban')->isUserBanned($aUser);
		if ( $aBanned['is_banned'])
		{
			if (isset($aBanned['reason']) && !empty($aBanned['reason']))
			{
				$aBanned['reason'] = str_replace('&#039;', "'", Phpfox::getLib('parse.output')->parse($aBanned['reason']));
				$sReason = preg_replace('/\{phrase var=\'(.*)\'\}/ise', "'' . Phpfox::getPhrase('\\1',array(), false, null, '" . Phpfox::getUserBy('language_id') . "') . ''", $aBanned['reason']);
				Phpfox_Error::set($sReason);
			}
			else
			{
				Phpfox_Error::set(Phpfox::getPhrase('ban.global_ban_message'));
			}
		}
        /**
         * @var array
         */
        $aError = Phpfox_Error::get();
        if (count($aError))
        {
            return array(
				'error_message' => $aError,
				'error_code' => 1,
				'result' => 0
			);
        }        
        /**
         * @var array
         */       
		$aToken = Phpfox::getService('mfox.token') -> createToken($aUser);
        /**
         * @var string
         */
		$sProfileImage = Phpfox::getLib('image.helper')->display(array(
            'server_id' => $aUser['server_id'],
            'path' => 'core.url_user',
            'file' => $aUser['user_image'],
            'suffix' => MAX_SIZE_OF_USER_IMAGE,
            'return_url' => true
                )
        );

		// get user id by email
		return array(
			'error_code' => 0,
			'result' => 1,
			'user_id' => $aUser['user_id'],
			'full_name' => $aUser['full_name'],
			'user_name' => $aUser['user_name'],
			'profileimage' => $sProfileImage,
			'token' => $aToken['token_id'],
		);
	}

	/**
     * process logout
     * 
	 * Input data:
     * N/A
     * 
     * Output data:
     * + result: int.
     * 
     * @global string $token
	 * @param array $aData
	 * @return array
	 */
	function logout($aData)
	{
		global $token;

		if (NULL == $token)
		{
			return array(
				'error_message' => 'token required!',
				'error_code' => 1,
				'result' => 0
			);
		}

		Phpfox::getService('mfox.token') -> deleteToken($token);

		return array('result' => 1);
	}

	/**
	 * Returns how old is a user based on its birthdate
	 * @param string $sAge
	 * @return int
	 */
	public function age($sAge)
	{
		if (!$sAge)
		{
			return $sAge;
		}
		$iYear = intval(substr($sAge, 4));
		$iMonth = intval(substr($sAge, 0, 2));
		$iDay = intval(substr($sAge, 2, 2));
		$iAge = date('Y') - (int)$iYear;
		$iCurrDate = date('m') * 100 + date('d');
		$iBirthDate = $iMonth * 100 + $iDay;

		if ($iCurrDate < $iBirthDate)
		{
			$iAge--;
		}

		return $iAge;
	}

    /**
     * Get user fields.
     * @param bool $bReturnUserValues
     * @param array $aUser
     * @param string $sPrefix
     * @param int $iUserId
     * @return string
     */
	public function getUserFields($bReturnUserValues = false, &$aUser = null, $sPrefix = null, $iUserId = null)
	{
        /**
         * @var array
         */
		$aFields = array(
			'user_id',
			'profile_page_id',
			'server_id',
			'user_name',
			'full_name',
			'gender',
			'user_image',
			'is_invisible',
			'user_group_id', // Fixes DRQ-307282
			'language_id'
		);

		if (Phpfox::getParam('user.display_user_online_status'))
		{
			$aFields[] = 'last_activity';
		}

		/* Return $aFields but about iUserId */
		if ($iUserId != null)
		{
			$aUser = $this -> database() -> select(implode(',', $aFields)) -> from(Phpfox::getT('user')) -> where('user_id = ' . (int)$iUserId) -> execute('getSlaveRow');

			return $aUser;
		}
		if ($bReturnUserValues)
		{
			$aCache = array();
			foreach ($aFields as $sField)
			{
				if ($sPrefix !== null)
				{
					if ($sField == 'server_id')
					{
						$sField = 'user_' . $sPrefix . $sField;
					}
					else
					{
						$sField = $sPrefix . $sField;
					}
				}
				$aCache[$sField] = ($aUser === null ? Phpfox::getService('mfox.phpfox') -> getUserBy($sField) : $aUser[$sField]);
			}
			return $aCache;
		}
		return $aFields;
	}

    /**
     * Get user object.
     * @param int $iUserId
     * @return bool
     */
	public function getUserObject($iUserId)
	{
		return (isset($this -> _aUser[$iUserId]) ? (object)$this -> _aUser[$iUserId] : false);
	}

    /**
     * Get user by id or name.
     * @staticvar array $aUser
     * @param mix $mName
     * @param bool $bUseId
     * @return array|boolean
     */
	public function get($mName = null, $bUseId = true)
	{
		static $aUser = array();
        /**
         * @var int
         */
		$iPhpfoxUserId = Phpfox::getService('mfox.auth') -> getUserId();

		if (isset($aUser[$mName]))
		{
			return $aUser[$mName];
		}

		if (Phpfox::getService('mfox.auth') -> isUser())
		{
			$this -> database() -> select('ut.item_id AS is_viewed, ') -> leftJoin(Phpfox::getT('user_track'), 'ut', 'ut.item_id = u.user_id AND ut.user_id = ' . $iPhpfoxUserId);
		}

		$this -> database() -> select('ur.rate_id AS has_rated, ') -> leftJoin(Phpfox::getT('user_rating'), 'ur', 'ur.item_id = u.user_id AND ur.user_id = ' . $iPhpfoxUserId);

		if (Phpfox::getUserParam('user.can_feature'))
		{
			$this -> database() -> select('uf.user_id as is_featured, uf.ordering as featured_order, ') -> leftjoin(Phpfox::getT('user_featured'), 'uf', 'uf.user_id = u.user_id');
		}
        /**
         * @var array
         */
		$aRow = $this -> database() -> select('u.*, user_space.*, user_field.*, user_activity.*, ls.user_id AS is_online, ts.style_id AS designer_style_id, ts.folder AS designer_style_folder, t.folder AS designer_theme_folder, t.total_column, ts.l_width, ts.c_width, ts.r_width, t.parent_id AS theme_parent_id, ug.prefix, ug.suffix, ug.icon_ext, ug.title') -> from($this -> _sTable, 'u') -> join(Phpfox::getT('user_group'), 'ug', 'ug.user_group_id = u.user_group_id') -> join(Phpfox::getT('user_space'), 'user_space', 'user_space.user_id = u.user_id') -> join(Phpfox::getT('user_field'), 'user_field', 'user_field.user_id = u.user_id') -> join(Phpfox::getT('user_activity'), 'user_activity', 'user_activity.user_id = u.user_id') -> leftJoin(Phpfox::getT('theme_style'), 'ts', 'ts.style_id = user_field.designer_style_id AND ts.is_active = 1') -> leftJoin(Phpfox::getT('theme'), 't', 't.theme_id = ts.theme_id') -> leftJoin(Phpfox::getT('log_session'), 'ls', 'ls.user_id = u.user_id AND ls.im_hide = 0') -> where(($bUseId ? "u.user_id = " . (int)$mName . "" : "u.user_name = '" . $this -> database() -> escape($mName) . "'")) -> execute('getSlaveRow');

		if (isset($aRow['is_invisible']) && $aRow['is_invisible'])
		{
			$aRow['is_online'] = '0';
		}

		$aUser[$mName] = &$aRow;

		if (!isset($aUser[$mName]['user_name']))
		{
			return false;
		}

		$aUser[$mName]['user_server_id'] = $aUser[$mName]['server_id'];

		$aUser[$mName]['is_friend'] = false;
		$aUser[$mName]['is_friend_of_friend'] = false;

		if (Phpfox::getService('mfox.auth') -> isUser() && Phpfox::isModule('friend') && $iPhpfoxUserId != $aUser[$mName]['user_id'])
		{
			$aUser[$mName]['is_friend'] = Phpfox::getService('mfox.friend') -> isFriend($iPhpfoxUserId, $aUser[$mName]['user_id']);
			$aUser[$mName]['is_friend_of_friend'] = Phpfox::getService('friend') -> isFriendOfFriend($aUser[$mName]['user_id']);

			if (!$aUser[$mName]['is_friend'])
			{
				$aUser[$mName]['is_friend'] = (Phpfox::getService('friend.request') -> isRequested($iPhpfoxUserId, $aUser[$mName]['user_id']) ? 2 : false);
				if (!$aUser[$mName]['is_friend'])
				{
					$aUser[$mName]['is_friend'] = (Phpfox::getService('friend.request') -> isRequested($aUser[$mName]['user_id'], $iPhpfoxUserId) ? 3 : false);
				}
			}
		}

		$this -> _aUser[$aRow['user_id']] = $aUser[$mName];

		return $aUser[$mName];
	}
    /**
     * Using in notification.
     * @param array $aNotification
     * @return array
     */
    public function doUserGetCommentNotificationStatus($aNotification)
    {
        /**
         * @var array
         */
        $aUserStatus = $this->database()->select('us.status_id, u.user_id, us.content, u.gender, u.user_name, u.full_name')
                ->from(Phpfox::getT('user_status'), 'us')
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = us.user_id')
                ->where('us.status_id = ' . (int) $aNotification['item_id'])
                ->execute('getSlaveRow');

        $aUserStatus['content'] = Phpfox::getLib('parse.bbcode')->removeTagText($aUserStatus['content']);
        /**
         * @var string
         */
        $sPhrase = '';
        if ($aNotification['user_id'] == $aUserStatus['user_id'])
        {
            $sPhrase = Phpfox::getPhrase('user.span_class_drop_data_user_full_name_span_commented_on_gender_status_update_title', array('full_name' => $aNotification['full_name'], 'gender' => Phpfox::getService('user')->gender($aUserStatus['gender'], 1), 'title' => Phpfox::getLib('parse.output')->shorten($aUserStatus['content'], Phpfox::getParam('notification.total_notification_title_length'), '...')));
        }
        elseif ($aNotification['user_id'] == Phpfox::getUserId())
        {
            $sPhrase = Phpfox::getPhrase('user.span_class_drop_data_user_full_name_span_commented_on_your_status_update_title', array('full_name' => $aNotification['full_name'], 'title' => Phpfox::getLib('parse.output')->shorten($aUserStatus['content'], Phpfox::getParam('notification.total_notification_title_length'), '...')));
        }
        else
        {
            $sPhrase = Phpfox::getPhrase('user.span_class_drop_data_user_full_name_span_commented_on_span_class_drop_data_user_other_full_name_s_span_status_update_title', array('full_name' => $aNotification['full_name'], 'other_full_name' => $aUserStatus['full_name'], 'title' => Phpfox::getLib('parse.output')->shorten($aUserStatus['content'], Phpfox::getParam('notification.total_notification_title_length'), '...')));
        }
        /**
         * @var array
         */
        $aFeeds = Phpfox::getService('mfox.feed')->getfeed(array('status-id' => $aUserStatus['status_id']), $aUserStatus['user_id']);
        return array(
            'link' => array(
                'iFeedId' => isset($aFeeds[0]['feed_id']) ? $aFeeds[0]['feed_id'] : 0,
            ),
            'message' => strip_tags($sPhrase),
            'sModule' => 'user',
            'sMethod' => 'getCommentNotificationStatus'
        );
    }
    /**
     * Using in notification.
     * @param array $aNotification
     * @return array
     */
    public function doUserGetCommentNotificationStatusTag($aNotification)
    {
        return array(
            'message' => Phpfox::getPhrase('user.user_name_tagged_you_in_a_comment', array('user_name' => $aNotification['full_name'])),
            'link' => array('iCommentId' => $aNotification['item_id'])
        );
    }
    /**
     * Using in notification.
     * @param array $aNotification
     * @return array
     */
    public function doUserGetNotificationStatus_Like($aNotification)
    {
        /**
         * @var array
         */
        $aRow = $this->database()->select('us.status_id, us.content, us.user_id, u.gender, u.user_name, u.full_name')
                ->from(Phpfox::getT('user_status'), 'us')
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = us.user_id')
                ->where('us.status_id = ' . (int) $aNotification['item_id'])
                ->execute('getSlaveRow');
        $aRow['content'] = Phpfox::getLib('parse.bbcode')->removeTagText($aRow['content']);
        /**
         * @var string
         */
        $sPhrase = '';
        if ($aNotification['user_id'] == $aRow['user_id'])
        {
            $sPhrase = Phpfox::getPhrase('user.user_name_liked_gender_own_status_update_title', array('user_name' => Phpfox::getService('notification')->getUsers($aNotification), 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1), 'title' => Phpfox::getLib('parse.output')->shorten($aRow['content'], Phpfox::getParam('notification.total_notification_title_length'), '...')));
        }
        elseif ($aRow['user_id'] == Phpfox::getUserId())
        {
            $sPhrase = Phpfox::getPhrase('user.user_name_liked_your_status_update_title', array('user_name' => Phpfox::getService('notification')->getUsers($aNotification), 'title' => Phpfox::getLib('parse.output')->shorten($aRow['content'], Phpfox::getParam('notification.total_notification_title_length'), '...')));
        }
        else
        {
            $sPhrase = Phpfox::getPhrase('user.user_name_liked_span_class_drop_data_user_full_name_s_span_status_update_title', array('user_name' => Phpfox::getService('notification')->getUsers($aNotification), 'full_name' => $aRow['full_name'], 'title' => Phpfox::getLib('parse.output')->shorten($aRow['content'], Phpfox::getParam('notification.total_notification_title_length'), '...')));
        }
        /**
         * @var array
         */
        $aFeeds = Phpfox::getService('mfox.feed')->getfeed(array('status-id' => $aRow['status_id']), $aRow['user_id']);
        return array(
            'link' => (isset($aFeeds[0]['feed_id']) ? array('iFeedId' => $aFeeds[0]['feed_id']) : 0),
            'message' => strip_tags($sPhrase),
            'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
        );
    }

}
