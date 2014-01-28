<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

class Musicsharing_Service_Process extends Phpfox_Service
{
    /**
     *
     * @var Phpfox_File 
     */
    public $oLibFile;
    
    /**
     *
     * @var User_Service_Space 
     */
    public $oSerUserSpace;
    
    /**
     *
     * @var Phpfox_Database_Driver_Mysql 
     */
    public $oLibDatabase;
    
    /**
     *
     * @var Phpfox_Setting 
     */
    public $oLibSetting;
    
    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->_sTable = phpFox::getT('m2bmusic_album_song');
        
        $this->oLibFile = Phpfox::getLib('file');
        
        $this->oSerUserSpace = Phpfox::getService('user.space');
        
        $this->oLibDatabase = Phpfox::getLib('database');
        
        $this->oLibSetting = Phpfox::getLib('setting');
    }

    public function createNewColumn($table = null, $name, $type)
    {
        $oSupport = Phpfox::getLib("database.support");
        if ($table == null)
        {
            $columns = $oSupport->getColumns($this->_sTable);
            if (isset($columns[$name]) == null)
            {
                $this->database()->query('ALTER TABLE `' . $this->_sTable . '` ADD ' . $name . ' ' . $type . " DEFAULT 0 ");
            }
        }
        else
        {
            $columns = $oSupport->getColumns($table);
            if (isset($columns[$name]) == null)
            {
                $this->database()->query('ALTER TABLE `' . $table . '` ADD ' . $name . ' ' . $type . " DEFAULT 0 ");
            }
        }
    }

    /**
     * Deletes an image of singer, this function is a response to an ajax call
     * @param integer $iSingerId the identifier of the poll
     * @param integer $iUser the user who triggered the ajax call
     * @return boolean
     */
    public function deleteSingerImage($iSingerId, $iUser)
    {
        $oDb = $this->database();
        $iUser = (int) $iUser;
        $iSingerId = (int) $iSingerId;

        // get the name of the image:
        $sFileName = $oDb->select('singer_image')
                ->from(Phpfox::getT('m2bmusic_singer'))
                ->where('singer_id = ' . $iSingerId)
                ->execute('getSlaveField');

        // calculate space used
        if (!empty($sFileName))
        {
            $sPathImage = PHPFOX_DIR_FILE . 'pic' . PHPFOX_DS . 'musicsharing' . PHPFOX_DS;
            
            // check if the file exists and get its size
            if (is_file($sPathImage . sprintf($sFileName, '')))
            {
                Phpfox::getLib('file')->unlink($sPathImage . sprintf($sFileName, ''));
            }
            $oDb->update(Phpfox::getT('m2bmusic_singer'), array('singer_image' => '', 'server_id' =>  0), 'singer_id = ' . $iSingerId);
        }
        
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
        if ($sPlugin = Phpfox_Plugin::get('musicsharing.service_process__call'))
        {
            return eval($sPlugin);
        }

        /**
         * No method or plug-in found we must throw a error.
         */
        Phpfox_Error::trigger('Call to undefined method ' . __CLASS__ . '::' . $sMethod . '()', E_USER_ERROR);
    }

}

?>