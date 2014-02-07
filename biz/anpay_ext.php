<title><?php echo $CONF["site_name"];?></title>
<?php
	$returnURL = $CONF['site_url']."/index.php?a=client";
	$cancelURL = $CONF['site_url']."/index.php?a=join";
	if ($_REQUEST["returnURL"] != '') $returnURL = $_REQUEST["returnURL"];
	if ($_REQUEST["cancelURL"] != '') $cancelURL = $_REQUEST["cancelURL"];
	
	if ($auth_approve == 1) {
?>
<p align="center">
<strong>Thank you</strong><br />
Payment Success!<br /><br />
<a href="<?php echo $_REQUEST["returnURL"];?>">Click here</a> to continue.<br /><br />
</p>
<?php
	} else {
?>
<strong><font color="#FF0000">Order Declined</font></strong><br />
This transaction cannot be accepted!<br /><br />
<a href="<?php echo $_REQUEST["cancelURL"];?>">Click here</a> to continue.<br /><br />
<?php
	}
?>