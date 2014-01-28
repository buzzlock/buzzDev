<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');


class Musicsharing_Service_Api extends Phpfox_Service 
{
	public function __construct()
	{	
		$this->_sTable = phpFox::getT('m2bmusic_album_song');
		$this->_oApi = phpFox::getService('api');	
	}
	public function addSong()
	{
		return $this->_oApi->error('musicsharing.add_photo_process', 'Could not add song at this time.Turn off this feature now');
	
	}
	
	public function getSongs()
	{
        
		if ((int) $this->_oApi->get('user_id') === 0)
		{
			$iUserId = $this->_oApi->getUserId();
		}
		else
		{
			$iUserId = $this->_oApi->get('user_id');
		}		
		 require_once 'logging.php'    ;
            $log = new Logging();
            $log->lwrite('aaaa');
		$iCnt = $this->database()->select('COUNT(*)')
			->from($this->_sTable, 's')
            ->join(phpFox::getT('m2bmusic_album'),'ma','ma.album_id = s.album_id AND ma.user_id = ' . (int) $iUserId)
			->where('s.privacy = 1')
			->execute('getSlaveField');
       
		$this->_oApi->setTotal($iCnt);
		
		$aRows = $this->database()->select('s.*,ma.*')
		    ->from($this->_sTable, 's')
            ->join(phpFox::getT('m2bmusic_album'),'ma','ma.album_id = s.album_id AND ma.user_id = ' . (int) $iUserId)
            ->where('s.privacy = 1')
			->limit($this->_oApi->get('page'), 10, $iCnt)
			->execute('getSlaveRows');
            
		$aMusicSongs = array();
        if(count($aRows)<=0)
        {
            return $aMusicSongs;
        }
		foreach ($aRows as $iKey => $aRow)
		{
			$sImagePath = $aRow['album_image'];
            $aMusicSongs[$iKey]['path_file'] = phpFox::getParam('core.path').$aRow['url'];
            $aMusicSongs[$iKey]['album_url'] = phpFox::getLib('url')->makeUrl('musicsharing.listen',array('album'=>$aRow['album_id']));
			$aMusicSongs[$iKey]['listen'] = $aRow['play_count'];
			
		}
		return $aMusicSongs;
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
		if ($sPlugin = Phpfox_Plugin::get('photo.service_api__call'))
		{
			eval($sPlugin);
			return;
		}
			
		/**
		 * No method or plug-in found we must throw a error.
		 */
		Phpfox_Error::trigger('Call to undefined method ' . __CLASS__ . '::' . $sMethod . '()', E_USER_ERROR);
	}	
}

?>