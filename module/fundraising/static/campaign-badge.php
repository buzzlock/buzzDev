<?php

include 'cli.php';

$iCampaignId = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
$iStatus = isset($_REQUEST['status']) ? $_REQUEST['status'] : 0;

$oAjax = Phpfox::getLib('ajax');

Phpfox::getBlock('fundraising.highlight-campaign', array('iCampaignId' => $iCampaignId, 
														 'bIsBadge' => true, 
													 	 'iStatus' => $iStatus
													));

$sContent = $oAjax->getContent();
$sContent = str_replace(array("\n", "\r"), '\\n', $sContent);
$sContent =  stripslashes($sContent);
$sCorePath = Phpfox::getParam('core.path');

?>

<head>
</head>
<link rel="stylesheet" type="text/css" href="<?php echo( $sCorePath);?>/module/fundraising/static/css/default/default/global.css?" />
<body style="font-family: 'lucida grande',tahoma,verdana,arial,sans-serif;
font-size: 11px;">
	<div style="width:250px">
		<?php echo $sContent; ?>
	</div>
</body>

<script type="text/javascript" src="<?php echo( $sCorePath);?>/static/jscript/jquery/jquery.js" />


<?php
ob_flush();