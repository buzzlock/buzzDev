<?php

defined('SOCIALMEDIAIMPORTER_ENABLE_LOG') or define('SOCIALMEDIAIMPORTER_ENABLE_LOG',1);

class Socialmediaimporter_Service_Log extends Phpfox_Service{ 
	
	public function write($data, $message = null)
	{
	
		if(!SOCIALMEDIAIMPORTER_ENABLE_LOG){
			return;
		}
		$file = PHPFOX_DIR_FILE . '/log/socialmedia-impoter.log';
		
		if(!is_string($data)){
			$data = var_export($data);
		}
		
		if(null  === $message)
		{
			$message = 'info';
		}
		
		$fp =  @fopen($file, 'a+');
		if($fp)
		{
			fwrite($fp,PHP_EOL. date('Y-m-d H:i:s') . ':'. $message . PHP_EOL. $data);
			fclose($fp);
		}else if(PHPFOX_DEBUG){
			throw new Exception("logfile $file is not writeable!");
		}
			
	}
}