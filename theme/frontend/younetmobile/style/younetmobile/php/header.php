<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

$oTpl->setHeader('cache', array(
		'main.js' => 'style_script'
	)
);

$oTpl->setHeader(array(
		"<!--[if IE 7]>\n\t\t\t<script type=\"text/javascript\" src=\"" . $oTpl->getStyle('jscript', 'ie7.js') . "?v=" . Phpfox::getLib('template')->getStaticVersion() . "\"></script>\n\t\t<![endif]-->"
	)
);

$oTpl -> assign(array(
		'core_url_module' => phpfox::getParam('core.url_module'),
		'core_url' => phpfox::getParam('core.path')
	)
); 

$oTpl->setPhrase(
	array(
	'mobiletemplate.ga_status_code_error'
	, 'mobiletemplate.ga_status_code_invalid_request'
	, 'mobiletemplate.ga_status_code_over_query_limit'
	, 'mobiletemplate.ga_status_code_request_denied'
	, 'mobiletemplate.ga_status_code_unknown_error'
	, 'mobiletemplate.ga_status_code_zero_results'
	, 'mobiletemplate.please_choose_image'
	)
);

?>