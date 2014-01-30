<?php

$sUri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';

$sUri = trim($sUri, '/');

if ($pos = strpos($sUri, '.php'))
{
	$sUri = substr($sUri, $pos + 5);
}

var_dump($sUri);

$requestMethod = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
$actionType = 1;
$sMethod =  'get';

if (preg_match("#^(\w+)\/(\d+)$#", $sUri, $matches))
{
	$actionType =  1;
	$sService = $matches[1];
	$sMethod = strtolower($requestMethod) . 'ByIdAction';
	$sId = $matches[1];
}
else
if (preg_match("#^(\w+)\/(\w+)$#", $sUri, $matches))
{
	$actionType =  2;
	$sService = $matches[1];
	$sMethod = $matches[2];
}
else if (preg_match("#^(\w+)$#", $sUri, $matches))
{
	$actionType = 3;
	$sService = $matches[1];
	$sMethod = strtolower($requestMethod) . 'Action';
	$sId = NULL;
}

echo $sService .':'. $sMethod;
