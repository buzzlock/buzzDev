<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = array (
  1 => 
  array (
    'cron_id' => '1',
    'module_id' => 'log',
    'product_id' => 'phpfox',
    'next_run' => '1393817449',
    'last_run' => '1393813849',
    'type_id' => '2',
    'every' => '1',
    'is_active' => '1',
    'php_code' => 'Phpfox::getLib(\'phpfox.database\')->delete(Phpfox::getT(\'log_session\'), "last_activity < \'" . ((PHPFOX_TIME - (Phpfox::getParam(\'log.active_session\') * 60))) . "\'");
',
  ),
  2 => 
  array (
    'cron_id' => '2',
    'module_id' => 'mail',
    'product_id' => 'phpfox',
    'next_run' => '1395789114',
    'last_run' => '1393197114',
    'type_id' => '3',
    'every' => '30',
    'is_active' => '1',
    'php_code' => 'Phpfox::getService(\'mail.process\')->cronDeleteMessages();',
  ),
  4 => 
  array (
    'cron_id' => '4',
    'module_id' => 'subscribe',
    'product_id' => 'phpfox',
    'next_run' => '1393817449',
    'last_run' => '1393813849',
    'type_id' => '2',
    'every' => '1',
    'is_active' => '1',
    'php_code' => 'Phpfox::getService(\'subscribe.purchase.process\')->downgradeExpiredSubscribers();',
  ),
  5 => 
  array (
    'cron_id' => '5',
    'module_id' => 'socialstream',
    'product_id' => 'socialstream',
    'next_run' => '1393832432',
    'last_run' => '1393789232',
    'type_id' => '2',
    'every' => '12',
    'is_active' => '1',
    'php_code' => 'php C:\\buzzDev\\htdocs\\module\\socialstream\\cron.php',
  ),
); ?>