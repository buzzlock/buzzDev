<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = array (
  1 => 
  array (
    'cron_id' => '1',
    'module_id' => 'log',
    'product_id' => 'phpfox',
    'next_run' => '1390872752',
    'last_run' => '1390869152',
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
    'next_run' => '1393197067',
    'last_run' => '1390605067',
    'type_id' => '3',
    'every' => '30',
    'is_active' => '1',
    'php_code' => 'Phpfox::getService(\'mail.process\')->cronDeleteMessages();',
  ),
  3 => 
  array (
    'cron_id' => '3',
    'module_id' => 'shoutbox',
    'product_id' => 'phpfox',
    'next_run' => '1390936380',
    'last_run' => '1390849980',
    'type_id' => '3',
    'every' => '1',
    'is_active' => '1',
    'php_code' => 'Phpfox::getService(\'shoutbox.process\')->clear(Phpfox::getParam(\'shoutbox.shoutbox_total\'));
',
  ),
  4 => 
  array (
    'cron_id' => '4',
    'module_id' => 'subscribe',
    'product_id' => 'phpfox',
    'next_run' => '1390872752',
    'last_run' => '1390869152',
    'type_id' => '2',
    'every' => '1',
    'is_active' => '1',
    'php_code' => 'Phpfox::getService(\'subscribe.purchase.process\')->downgradeExpiredSubscribers();',
  ),
); ?>