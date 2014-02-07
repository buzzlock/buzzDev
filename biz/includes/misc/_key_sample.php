<?php
/* ------------
$customer_domain = input value (insert by member, e.g. domain name, username, order number, etc)
$product_license = output value (produce by system, e.g. license key, password, serial number, etc)
--------------*/

function generate_key($key, $len=10) {
	$res = substr(md5($key.'+'.$len), 0, $len);
	return strtoupper($res);
}

// "$product_license" value generated from "$customer_domain" value.
$product_license = generate_key($customer_domain);
?>