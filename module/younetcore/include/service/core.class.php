<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');
require_once (PHPFOX_DIR . 'module' . PHPFOX_DS . 'younetcore' . PHPFOX_DS . 'include' . PHPFOX_DS . 'service' . PHPFOX_DS . 'libs.class.php');

if (!function_exists('json_decode2'))
{

	function json_decode2($json, $assoc = true)
	{

		$matchString = '/".*?(?<!\\\\)"/';

		// safety / validity test
		$t = preg_replace($matchString, '', $json);
		$t = preg_replace('/[,:{}\[\]0-9.\-+Eaeflnr-u \n\r\t]/', '', $t);
		if ($t != '')
		{
			return null;
		}

		// build to/from hashes for all strings in the structure
		$s2m = array();
		$m2s = array();
		preg_match_all($matchString, $json, $m);
		foreach ($m[0] as $s)
		{
			$hash = '"' . md5($s) . '"';
			$s2m[$s] = $hash;
			$m2s[$hash] = str_replace('$', '\$', $s);
			// prevent $ magic
		}

		// hide the strings
		$json = strtr($json, $s2m);

		// convert JS notation to PHP notation
		$a = ($assoc) ? '' : '(object) ';
		$json = strtr($json, array(
			':' => '=>',
			'[' => 'array(',
			'{' => "{$a}array(",
			']' => ')',
			'}' => ')'
		));

		// remove leading zeros to prevent incorrect type casting
		$json = preg_replace('~([\s\(,>])(-?)0~', '$1$2', $json);

		// return the strings
		$json = strtr($json, $m2s);

		/* "eval" string and return results.
		 As there is no try statement in PHP4, the trick here
		 is to suppress any parser errors while a function is
		 built and then run the function if it got made. */
		$f = @create_function('', "return {$json};");
		$r = ($f) ? $f() : null;

		// free mem (shouldn't really be needed, but it's polite)
		unset($s2m);
		unset($m2s);
		unset($f);

		return $r;
	}

}

if (!function_exists('json_encode2'))
{

	function json_encode2($value)
	{

		if ($value === null)
		{
			return 'null';
		};// gettype fails on null?

		$out = '';
		$esc = "\"\\/\n\r\t" . chr(8) . chr(12);
		// escaped chars
		$l = '.';
		// decimal point

		switch ( gettype( $value ) )
		{
			case 'boolean' :
				$out .= $value ? 'true' : 'false';
				break;

			case 'float' :
			case 'double' :
				// PHP uses the decimal point of the current locale but JSON expects %x2E
				$l = localeconv();
				$l = $l['decimal_point'];
			// fallthrough...

			case 'integer' :
				$out .= str_replace($l, '.', $value);
				// what, no getlocale?
				break;

			case 'array' :
				// if array only has numeric keys, and is sequential... ?
				for ($i = 0; ($i < count($value) && isset($value[$i])); $i++);
				if ($i === count($value))
				{
					// it's a "true" array... or close enough
					$out .= '[' . implode(',', array_map('toJSON', $value)) . ']';
					break;
				}
			// fallthrough to object for associative arrays...

			case 'object' :
				$arr = is_object($value) ? get_object_vars($value) : $value;
				$b = array();
				foreach ($arr as $k => $v)
				{
					$b[] = '"' . addcslashes($k, $esc) . '":' . toJSON($v);
				}
				$out .= '{' . implode(',', $b) . '}';
				break;

			default :
				// anything else is treated as a string
				return '"' . addcslashes($value, $esc) . '"';
				break;
		}
		return $out;

	}

}

class YouNetCore_Service_Core extends Phpfox_Service
{
	/**
	 * Class constructor
	 */
	private $_config = array(
		'url' => '',
		'plfversion' => '',
		'checkPatern' => array(),
		'cache' => array(),
		'iTime' => 0,
	);

	public function __construct()
	{
		$this -> _sTable = Phpfox::getT('younetcore_license');
		$this -> _config = array(
			'url' => 'http://auth.younetco.com/ls.php',
			'plfversion' => Phpfox::getParam('core.phpfox_version'),
			'checkPatern' => array(
				'description' => 'by YouNet Company',
				//'version' => substr(Phpfox::getParam('core.phpfox_version'),2),
				'check_id' => 'younetcore'
			),
			'cache' => array(
				'sCacheYNModuleName' => array(
					'younetcore',
					'_cache_module_yn_name'
				),
				'sCacheModuleName' => array(
					'younetcore',
					'_cache_module_yours_name'
				),
				'sCacheYNModuleNameInProduction' => array(
					'younetcore',
					'_cache_module_yn_name_in_production'
				),
				'sCacheInvalidName' => array(
					'younetcore',
					'_cache_invalid_yours_name'
				),
				'sCacheYNNews' => array(
					'younetcore',
					'_cache_younet_news'
				)
			),
			'iTime' => 60, //cache time in minutes
		);
	}

	public function c($aData = array(), $sName = "")
	{
		$this -> cache() -> set($sName);
		$this -> cache() -> save($sName, $aData);
	}

	public function rma()
	{
		//$this->cache()->remove();
		$this -> rmc();
	}

	public function rmc($sName = "")
	{
		$this -> cache() -> remove(array(
			'younetcore',
			''
		), 'substr');
	}

	public function getUrl()
	{
		return $this -> _config['url'];
	}

	public function getCurrentDomain()
	{
		return strtolower(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : (isset($_SERVER['HOST']) ? $_SERVER['HOST'] : ''));
	}

	public function getPhotos($m, $t)
	{
		$url = $this -> _config['url'];
		$params['t'] = $t;
		$params['m'] = $m;
		$params['tt'] = 'phpfox';
		$params['ttversion'] = $this -> _config['plfversion'];
		$results = $this -> doPost($params, $url);
		//$results = json_decode2($results);
		return $results;
	}

	public function getVerifyKey($params)
	{
		$url = $this -> _config['url'];
		$domain = $this -> getCurrentDomain();
		$domain = base64_encode($domain);
		$params['t'] = 'license';
		$params['d'] = $domain;
		$params['tt'] = 'phpfox';
		$license = $this -> doPost($params, $url);
		return $license;
	}

	public function doPost($params, $url)
	{
		$fields_string = "";

		if (!isset($params['d']))
		{
			$params['d'] = base64_encode($this -> getCurrentDomain());
		}

		$params['ttversion'] = $this -> _config['plfversion'];

		foreach ($params as $key => $value)
		{
			$fields_string .= $key . '=' . $value . '&';
		}

		rtrim($fields_string, '&');

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, count($params));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);

		$head = curl_exec($ch);
		curl_close($ch);
		return $head;

	}

	public function getYnModulesInProduction($page = null, $limit = null)
	{
		$sCacheId = $this -> cache() -> set($this -> _config['cache']['sCacheYNModuleNameInProduction']);
		if (!($modules = $this -> cache() -> get($sCacheId, $this -> _config['iTime'])))
		{
			$url = $this -> _config['url'];
			$domain = $this -> getCurrentDomain();
			$domain = base64_encode($domain);
			$params['t'] = 'production_modules';
			$params['d'] = $domain;
			$params['tt'] = 'phpfox';
			$params['ttversion'] = $this -> _config['plfversion'];
			$modules = $this -> doPost($params, $url);
			$modules = json_decode2($modules, true);
			$this -> cache() -> save($sCacheId, $modules);
		}
		$obj = array();
		if ($modules == false || count($modules) <= 0)
		{
			return $obj;
		}
		foreach ($modules as $key => $module)
		{
			$obj[$key] = $module;
		}
		return $obj;
	}

	public function getYnModules($page = null, $limit = null)
	{
		$sCacheId = $this -> cache() -> set($this -> _config['cache']['sCacheYNModuleName']);
		if (!($modules = $this -> cache() -> get($sCacheId, $this -> _config['iTime'])))
		{
			$url = $this -> _config['url'];
			$domain = $this -> getCurrentDomain();
			$domain = base64_encode($domain);
			$params['t'] = 'modules';
			$params['d'] = $domain;
			$params['tt'] = 'phpfox';
			$params['ttversion'] = $this -> _config['plfversion'];
			$modules = $this -> doPost($params, $url);
			$modules = json_decode2($modules, true);
			$this -> cache() -> save($sCacheId, $modules);
		}
		$obj = array();
		if ($modules == false || count($modules) <= 0)
		{
			return $obj;
		}
		foreach ($modules as $key => $module)
		{
			$obj[$key] = $module;
		}
		return $obj;
	}

	public function verifyM($data)
	{
		$url = $this -> _config['url'];
		$results = false;
		$data['tt'] = 'phpfox';
		$results = $this -> doPost($data, $url);
		return $results;
	}

	public function getToken($module = "")
	{
		if ($module == "")
		{
			return false;
		}
		$domain = $this -> getCurrentDomain();
		$domain = base64_encode($domain);
		$params = array(
			't' => 'token',
			'd' => $domain,
			'm' => $module,
			'time' => time(),
			'tt' => 'phpfox'
		);
		$urlget = $this -> _config['url'];
		$token = $this -> doPost($params, $urlget);
		$token = json_decode2($token, true);
		$token['time'] = $params['time'];
		$token['m'] = $module;
		$token_data = array(
			'token' => $token['tk'],
			'params' => $params['time'],
		);
		return $token;
	}

	public function getLicenseRules()
	{
		$params = array(
			't' => 'viewlicense',
			'tt' => 'phpfox'
		);
		$urlget = $this -> _config['url'];
		$license = $this -> doPost($params, $urlget);
		return $license;
	}

	public function insertYNProduct($aInsert = array())
	{
		return $this -> database() -> insert($this -> _sTable, $aInsert);
	}

	public function updateYNProduct($aUpdate = array())
	{
		return $this -> database() -> update($this -> _sTable, $aUpdate, 'name = "' . $aUpdate['name'] . '"');
	}

	public function updateProduct($name = "", $status = 1)
	{

		if ($name == "")
		{
			return false;
		}

		$this -> database() -> update(phpfox::getT('product'), array('is_active' => $status), "product_id='{$name}'");
		$this -> database() -> update(phpfox::getT('module'), array('is_active' => $status), "product_id='{$name}'");
		$this -> cache() -> remove('product');
	}

	public function getYNProduct($iProductName)
	{
		return $this -> database() -> select('p.*') -> from($this -> _sTable, 'p') -> where('p.name = "' . $iProductName . '"') -> execute('getRow');
	}

	public function getModuleFromInstall()
	{
		$lst_modules = $this -> database() -> select('p.*') -> from($this -> _sTable, 'p') -> execute('getRows');
		$modules = array();
		foreach ($lst_modules as $key => $m)
		{
			$modules[$m['name']] = array(
				'name' => $m['title'],
				'is_active' => $m['is_active'],
				'current_v' => $m['current_version'],
				'latest_v' => $m['lasted_version'],
				'demo_url' => $m['demo_link'],
				'image_url' => '',
				'purchase' => '',
				'download' => $m['download_link'],
				'price' => '',
				'currency' => '',
				'params' => $m['params'],
			);
		}
		return $modules;
	}

	public function reverifiedModules()
	{
		$timeCheck = time() + 300;

		$aRows = $this -> database() -> select('p.*') -> from($this -> _sTable, 'p') -> where("is_active=0 AND (date_active < '$timeCheck')") -> execute('getRows');

		if ($aRows)
		{
			foreach ($aRows as $aRow)
			{
				$sName = $aRow['name'];
				if ($sName && $sName != 'younetcore')
				{
					$this -> disableProduct($sName, $timeCheck);
				}
			}
		}
	}

	public function disableProduct($sProductId, $checkTime = 0)
	{
		$this -> database() -> update(Phpfox::getT('product'), array('is_active' => 0), "product_id='{$sProductId}'");
		$this -> database() -> update(Phpfox::getT('module'), array('is_active' => 0), "product_id='{$sProductId}'");
		$this -> database() -> update($this -> _sTable, array('date_active' => (int)$checkTime), "name='{$sProductId}'");
	}

	public function checkYouNetProducts($aModules = array())
	{
		if (!phpfox::isAdminPanel())
		{
			return true;
		}

		$isChanged = $this -> getYounetProductsFromSite();

		if ($isChanged)
		{
			// it's stupid code when we need to check that always changed this system by fault.
			$this -> cache() -> remove('module_menu');
			$this -> cache() -> remove('module');

		}
		return $aModules;
	}

	public function getYounetProductsFromSite()
	{
		// stupid code, should not be double check for its.
		/*
		 $aProducts = $this->database()->select('p.*,pd.type_id,pd.check_id')
		 ->from(phpfox::getT('product'),'p')
		 ->leftJoin(phpfox::getT('product_dependency'),'pd','pd.product_id = p.product_id')
		 ->where("p.product_id !='younetcore'")
		 ->execute('getRows');
		 */
		$aProducts = array();

		(($sPlugin = Phpfox_Plugin::get('younetcore.check_dependency_younet_module')) ? eval($sPlugin) : false);

		$aInserteds = $this -> getProductForQuickCheckFromLicenseTable();

		$isChanged = FALSE;

		foreach ($aProducts as $aProduct)
		{
			$sProductId = $aProduct['product_id'];

			if ($sProductId && !isset($aInserteds[$sProductId]))
			{
				$isChanged = TRUE;
				$aProduct['is_active'] = 0;

				$aInsert = array(
					'name' => $aProduct['product_id'],
					'title' => $aProduct['title'],
					'descriptions' => $aProduct['description'],
					'type' => 'module',
					'current_version' => $aProduct['version'],
					'lasted_version' => $aProduct['version'],
					'is_active' => 0,
					'date_active' => time(),
					'params' => ''
				);

				//exit;
				$this -> database() -> insert($this -> _sTable, $aInsert);
			}
		}
		return $isChanged;
	}

	private $_aInserteds = NULL;

	/**
	 * @see phpfox_younetcore_license
	 * @return array {name=>is_active}
	 */
	public function getProductForQuickCheckFromLicenseTable($cache = TRUE)
	{

		if (NULL === $this -> _aInserteds)
		{

			$aResult = array();
			$aRows = $this -> database() -> select('s.name,s.is_active') -> from(Phpfox::getT('younetcore_license'), 's') -> where("name <> '' and type =  'module'") -> execute('getSlaveRows');

			if ($aRows)
			{
				foreach ($aRows as $aRow)
				{
					$aResult[$aRow['name']] = $aRow['is_active'];
				}
			}
			$this -> _aInserteds = $aResult;
		}
		return $this -> _aInserteds;
	}

	public function getPhpFoxProducts($isCache = false)
	{
		$aInserted = $this->getProductForQuickCheckFromLicenseTable();
		
		$aKeys = implode("','", array_keys($aInserted));
		
		$aRows = $this -> database() -> select('p.*,ync.params as yncparams,ync.is_active as yncstatus') -> from(phpfox::getT('product'), 'p') -> leftJoin(phpfox::getT('younetcore_license'), 'ync', 'ync.name = p.product_id') -> where("p.product_id IN ('$aKeys')") -> execute('getSlaveRows');
		return $aRows;
	}

	public function getNews()
	{
		$sCacheId = $this -> cache() -> set($this -> _config['cache']['sCacheYNNews']);
		if (!($aCache = $this -> cache() -> get($sCacheId, $this -> _config['iTime'])))
		{
			$aNews = Phpfox::getLib('xml.parser') -> parse(file_get_contents('http://www.younetco.com/feed'));
			$aCache = array();
			$iCnt = 0;
			foreach ($aNews['channel']['item'] as $aItem)
			{
				$iCnt++;
				$aCache[] = array(
					'title' => $aItem['title'],
					'link' => $aItem['link'],
					'creator' => $aItem['dc:creator'],
					'time_stamp' => strtotime($aItem['pubDate'])
				);
				if ($iCnt === 20)
				{
					break;
				}
			}
			$this -> cache() -> save($sCacheId, $aCache);
		}

		foreach ($aCache as $iKey => $aRow)
		{
			$aCache[$iKey]['posted_on'] = Phpfox::getPhrase('admincp.posted_on_time_stamp_by_creator', array(
				'creator' => $aRow['creator'],
				'time_stamp' => Phpfox::getTime(Phpfox::getParam('core.global_update_time'), $aRow['time_stamp'])
			));
		}
		return $aCache;
	}

	public function _getPlatformVersion($version = '0')
	{
		if (!strpos($version, '.'))
		{
			return 0;
		}
		$version = explode('.', $version);
		if (count($version) <= 0)
		{
			return 0;
		}
		return $version[0];
	}

}
?>