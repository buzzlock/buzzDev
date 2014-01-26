<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

/**
 * PayPal Payment Gateway API
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author			Raymond Benc
 * @package 		Phpfox
 * @version 		$Id: paypal.class.php 3837 2011-12-22 14:38:44Z Miguel_Espinoza $
 */
class Younetpaymentgateways_Service_Api_Paypal implements Younetpaymentgateways_gatewayinterface {

	/**
	 * Holds an ARRAY of settings to pass to the form
	 *
	 * @var array
	 */
	private $_aParam = array();

	/**
	 * Holds an ARRAY of supported currencies for this payment gateway
	 *
	 * @var array
	 */
	private $_aCurrency = array('USD', 'GBP', 'EUR', 'AUD', 'CAD', 'JPY', 'NZD', 'CHF', 'HKD', 'SGD', 'SEK', 'DKK', 'PLN', 'NOK', 'HUF', 'CZK', 'ILS', 'MXN', 'BRL', 'MYR', 'PHP', 'TWD', 'THB', 'TRY');

	/**
	 * Class constructor
	 *
	 */
	public function __construct() {
		
	}

	public function getSupportedCurrencies() {
		return $this->_aCurrency;
	}

	public function getCheckoutUrl() {
		$aParam = array();
		$aTempParam = array();

		$aParam = $this->getForm();
		$sUrl = $aParam['url'];

		foreach ($aParam['param'] as $sKey => $sValue) {
			if ($sValue) {
				$aTempParam[] = $sKey . "=" . $sValue;
			}
		}
		$aTempParam = implode('&', $aTempParam);
		$sUrl .= '?' . $aTempParam;

		return $sUrl;
	}

	public function test() {
		return 'mm';
	}

	/**
	 * Set the settings to be used with this class and prepare them so they are in an array
	 *
	 * @param array $aSetting ARRAY of settings to prepare
	 */
	public function set($aSetting) {
		$this->_aParam = $aSetting;

		if (Phpfox::getLib('parse.format')->isSerialized($aSetting['setting'])) {
			$this->_aParam['setting'] = unserialize($aSetting['setting']);
		}
	}

	/**
	 * Each gateway has a unique list of params that must be passed with the HTML form when posting it
	 * to their site. This method creates that set of custom fields.
	 *
	 * @return array ARRAY of all the custom params
	 */
	public function getEditForm() {
		return array(
			'paypal_email' => array(
				'phrase' => Phpfox::getPhrase('core.paypal_email'),
				'phrase_info' => Phpfox::getPhrase('core.the_email_that_represents_your_paypal_account'),
				'value' => (isset($this->_aParam['paypal_email']) ? $this->_aParam['setting']['paypal_email'] : '')
			)
		);
	}

	/**
	 * Returns the actual HTML <form> used to post information to the 3rd party gateway when purchasing
	 * an item using this specific payment gateway
	 *
	 * @return bool FALSE if we can't use this payment gateway to purchase this item or ARRAY if we have successfully created a form
	 */
	public function getForm() {
		if (!in_array($this->_aParam['currency_code'], $this->_aCurrency)) {
			if (isset($this->_aParam['alternative_cost'])) {
				$aCosts = unserialize($this->_aParam['alternative_cost']);
				$bPassed = false;
				foreach ($aCosts as $sCode => $iPrice) {
					if (in_array($sCode, $this->_aCurrency)) {
						$this->_aParam['amount'] = $iPrice;
						$this->_aParam['currency_code'] = $sCode;
						$bPassed = true;
						break;
					}
				}

				if ($bPassed === false) {
					return false;
				}
			} else {
				return false;
			}
		}

		$aForm = array(
			'url' => ($this->_aParam['is_test'] ? 'https://www.sandbox.paypal.com' : 'https://www.paypal.com/cgi-bin/webscr'),
			'param' => array(
				'business' => $this->_aParam['paypal_email'],
				'item_name' => isset($this->_aParam['item_name']) ? $this->_aParam['item_name'] : '',
				'item_number' => isset($this->_aParam['item_number']) ? $this->_aParam['item_number'] : '',
				'custom' => isset($this->_aParam['custom']) ? $this->_aParam['custom'] : '',
				'currency_code' => isset($this->_aParam['currency_code']) ? $this->_aParam['currency_code'] : 'USD',
				'notify_url' => Phpfox::getService('younetpaymentgateways')->url('paypal'),
				'return' => isset($this->_aParam['return']) ? $this->_aParam['return'] : '',
				'no_shipping' => '1',
				'no_note' => '1'
			)
		);

		if ($this->_aParam['recurring'] > 0) {
			switch ($this->_aParam['recurring']) {
				case '1':
					$t3 = 'M';
					$p3 = 1;
					break;
				case '2':
					$t3 = 'M';
					$p3 = 3;
					break;
				case '3':
					$t3 = 'M';
					$p3 = 6;
					break;
				case '4':
					$t3 = 'Y';
					$p3 = 1;
					break;
			}

			$aCosts = unserialize($this->_aParam['alternative_recurring_cost']);

			$aForm['param']['cmd'] = '_xclick-subscriptions';
			$aForm['param']['a1'] = $this->_aParam['amount'];
			$aForm['param']['a3'] = $aCosts[Phpfox::getService('core.currency')->getDefault()];
			$aForm['param']['t1'] = $t3;
			$aForm['param']['p1'] = $p3;
			$aForm['param']['t3'] = $t3;
			$aForm['param']['p3'] = $p3;
			$aForm['param']['src'] = '1';
			$aForm['param']['sra'] = '1';
		} else {
			$aForm['param']['cmd'] = '_xclick';
			$aForm['param']['amount'] = $this->_aParam['amount'];
		}

		return $aForm;
	}

	/**
	 * Performs the callback routine when the 3rd party payment gateway sends back a request to the server,
	 * which we must then back and verify that it is a valid request. This then connects to a specific module
	 * based on the information passed when posting the form to the server.
	 *
	 */
	public function callback() {
		Phpfox::log('Starting PayPal callback');
		$bVerified = false;
		// Read the post from PayPal system and add 'cmd'
		$req = 'cmd=' . urlencode('_notify-validate');

		foreach ($_POST as $key => $value) {
			$value = urlencode(stripslashes($value));
			$req .= "&$key=$value";
		}

		Phpfox::log('Attempting callback');

		// Post back to PayPal system to validate
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://' . ($this->_aParam['is_test'] ? 'www.sandbox.paypal.com' : 'www.paypal.com' ) . '/cgi-bin/webscr');
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: ' . ($this->_aParam['is_test'] ? 'www.sandbox.paypal.com' : 'www.paypal.com')));
		$res = curl_exec($ch);
		Phpfox::log('respond: ' . $res);
		curl_close($ch);
		if (strcmp($res, "VERIFIED") == 0) {
			$bVerified = true;
		} else if (strcmp($res, "INVALID") == 0) {
			$bVerified = false;
		}
		if ($bVerified === true) {
			Phpfox::log('Callback OK');

			$aParts = explode('|', $this->_aParam['custom']);

			Phpfox::log('Attempting to load module: ' . $aParts[0]);

			if (Phpfox::isModule($aParts[0])) {
				Phpfox::log('Module is valid.');
				Phpfox::log('Checking module callback for method: paymentApiCallback');
				if (Phpfox::hasCallback($aParts[0], 'paymentApiCallback')) {
					Phpfox::log('Module callback is valid.');
					Phpfox::log('Building payment status: ' . (isset($this->_aParam['payment_status']) ? $this->_aParam['payment_status'] : '') . ' (' . (isset($this->_aParam['txn_type']) ? $this->_aParam['txn_type'] : '') . ')');

					$sStatus = null;
					if (isset($this->_aParam['payment_status'])) {
						switch ($this->_aParam['payment_status']) {
							case 'Completed':
								$sStatus = 'completed';
								break;
							case 'Pending':
								$sStatus = 'pending';
								break;
							case 'Refunded':
							case 'Reversed':
								$sStatus = 'cancel';
								break;
						}
					}

					if (isset($this->_aParam['txn_type'])) {
						switch ($this->_aParam['txn_type']) {
							case 'subscr_cancel':
							case 'subscr_failed':
								$sStatus = 'cancel';
								break;
						}
					}

					Phpfox::log('Status built: ' . $sStatus);

					if ($sStatus !== null) {
						Phpfox::log('Executing module callback');
						Phpfox::callback($aParts[0] . '.paymentApiCallback', array(
							'gateway' => 'paypal',
							'ref' => $this->_aParam['txn_id'],
							'status' => $sStatus,
							'item_number' => $this->_aParam['item_number'],
							'custom' => $aParts[1],
							'total_paid' => (isset($this->_aParam['mc_gross']) ? $this->_aParam['mc_gross'] : null),
							'currency' => $this->_aParam['mc_currency'],
							'payer_email' => $this->_aParam['payer_email'],
							'transaction_id' => $this->_aParam['txn_id'],
							//in case need more infor
							'aTransactionDetail' => $this->_aParam 
								)
						);

						header('HTTP/1.1 200 OK');
					} else {
						Phpfox::log('Status is NULL. Nothing to do');
					}
				} else {
					Phpfox::log('Module callback is not valid.');
				}
			} else {
				Phpfox::log('Module is not valid.');
			}
		} else {
			Phpfox::log('Callback FAILED');
		}
	}

}

?>