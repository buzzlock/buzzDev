<?php

require_once "cli.php";


$sService = 'linkedin';

try
{
	if(isset($aParams['bredirect']))
	{
		$_SESSION['bredirect'] = $aParams['bredirect'];
	}
	
	if (isset($_GET['callbackUrl']))
	{
		$_SESSION['callbackUrl'] = urldecode($_GET['callbackUrl']);
	}
	
	
	$bRedirect = isset($_SESSION['bredirect'])?$_SESSION['bredirect']:1;
	$sRedirectUrl = isset($_SESSION['callbackUrl'])?$_SESSION['callbackUrl']:'';
	$sConnected = '';

	$Provider = Phpfox::getService('socialbridge') -> getProvider($sService);

	$Provider -> removeTokenData();

	$oLinkedIn = $Provider -> getApi();

	$sOAuthProblem = isset($_GET['oauth_problem']) ? $_GET['oauth_problem'] : NULL;

	if ($sOAuthProblem == 'user_refused')
	{
		processRedirectAndExit($sService,$bRedirect, $sRedirectUrl, $sConnected);
	}

	// check for response from LinkedIn
	$lResponse = isset($_GET['lResponse']) ? $_GET['lResponse'] : '';

	if ($lResponse == '')
	{
		if (isset($_SESSION['linkedin']))
		{
			unset($_SESSION['linkedin']);
		}

		$oLinkedIn -> setToken(NULL);
		// LinkedIn hasn't sent us a response, the user is initiating the
		// connection

		// send a request for a LinkedIn access token
		$response = $oLinkedIn -> retrieveTokenRequest();
		if ($response['success'] === TRUE)
		{
			// store the request token
			$_SESSION['linkedin']['request'] = $response['linkedin'];

			// redirect the user to the LinkedIn authentication/authorisation
			// page to initiate validation.
			header('Location: ' . LinkedInSBYN::_URL_AUTH . $response['linkedin']['oauth_token']);
		}
		else
		if ($response['linkedin']['oauth_problem'] == 'timestamp_refused')
		{
			$delta = time() - intval($response['linkedin']['oauth_acceptable_timestamps']);

			$_SESSION['delta_time_stamp'] = -$delta;
			header('Location: ' . $_SERVER['PHP_SELF']);
			exit ;
		}
		else
		{
			// bad token request
			echo "Request token retrieval failed:<br /><br />RESPONSE:<br /><br /><pre>" . print_r($response, TRUE) . "</pre><br /><br />LINKEDIN OBJ:<br /><br /><pre>" . print_r($oLinkedIn, TRUE) . "</pre>";
		}
	}
	else
	{
		// LinkedIn has sent a response, user has granted permission, take
		// the temp access token, the user's secret and the verifier to
		// request the user's real secret key
		$response = $oLinkedIn -> retrieveTokenAccess($_SESSION['linkedin']['request']['oauth_token'], $_SESSION['linkedin']['request']['oauth_token_secret'], $_GET['oauth_verifier']);

		if ($response['success'] === TRUE)
		{
			// the request went through without an error, gather user's
			// 'access' tokens

			$token = $response['linkedin'];
			$oLinkedIn -> setToken($token);
			$profile = $Provider -> getProfile();
			$Provider -> setTokenData($token, $profile);
			
			$sConnected = phpfox::getPhrase('socialbridge.connected_as', array('full_name' => '')) . ' ' . $profile['full_name'];
			processRedirectAndExit($sService,$bRedirect, $sRedirectUrl, $sConnected);
			exit ;
		}
		else
		if ($response['linkedin']['oauth_problem'] == 'token_rejected')
		{
			$url = Phpfox::getParam('core.path') . 'module/socialbridge/static/php/linkedin.php?lType=initiate';
			header('location:' . $url);
			exit ;
		}
		else
		{
			// bad token access
			echo "Access token retrieval failed:<br /><br />RESPONSE:<br /><br /><pre>" . print_r($response, TRUE) . "</pre><br /><br />LINKEDIN OBJ:<br /><br /><pre>" . print_r($oLinkedIn, TRUE) . "</pre>";
		}
	}

}
catch(Exception $e)
{
	echo $e -> getMessage();
	echo Phpfox::getPhrase('contactimporter.please_enter_your_linkedin_api');
	exit ;
}
