	<?php 
	include "cli.php";
	session_start();
	if (file_exists(PHPFOX_DIR . 'module' . PHPFOX_DS . 'socialmediaimporter' . PHPFOX_DS . 'include' . PHPFOX_DS . 'service' . PHPFOX_DS . 'libs' . PHPFOX_DS . 'phpFlickr.php')) {
		require_once (PHPFOX_DIR . 'module' . PHPFOX_DS . 'socialmediaimporter' . PHPFOX_DS . 'include' . PHPFOX_DS . 'service' . PHPFOX_DS . 'libs' . PHPFOX_DS . 'phpFlickr.php');
	}
	Phpfox::isUser(true);
	
	$oRequest = Phpfox::getLib('request');
	$aParams = $_REQUEST;
	$bRedirect = $oRequest -> get('redirect');
	$sService = $oRequest -> get('service', 'facebook');
	if (isset($_REQUEST['frob'])) {
		$sService = 'flickr';
	}
	$oService = Phpfox::getService('socialmediaimporter.services') -> getObject($sService);
	
	$bRedirect = 0;
	if (isset($aParams['redirect'])) {
		$bRedirect = $aParams['redirect'];
	}
	
	if (!is_object($oService)) {
		exit("service name {$sService} does not exsits or not support . " . __FILE__);
	}
	
	if (!method_exists($oService, 'processAuthCallback')) {
		exit("service name {$sService} has not implement processAuthCallback. " . __FILE__);
	}
	
	try {
		if (isset($aParams['user_data'])) {
			$aParams['user_data'] = stripslashes($aParams['user_data']);
		}
		$aExtra = $oService -> processAuthCallback($oRequest, $aParams);
		if (is_array($aExtra) && $aExtra) {
			$sFullName = $aExtra['full_name'];
			$sImgUrl = $aExtra['img_url'];
		}
	} catch(Exception $e) {
		exit($e -> getMessage() . ' at ' . __FILE__);
	}
	
	$isFBConnectCancel = 0;
	if ($sService == 'facebook' && !$aExtra['identity']) {
		$isFBConnectCancel = 1;
	}
	$sUrlRedirect = Phpfox::getLib('url') -> makeUrl('socialmediaimporter.' . $sService);
	?>
	<script type="text/javascript">
		opener.location = '<?php echo $sUrlRedirect; ?>
			';
			self.close();
	</script>
