<?php
class Bizcore_Service_User extends Phpfox_Service  
{
	public function ud($field)
	{
		return Phpfox::getLib('database')
			->select('u.' . $field)
			->from(Phpfox::getT('user'), 'u')
			->where('u.user_id=' . Phpfox::getUserId())
			->execute('getField');
	}
	public function up($table, $field, $value)
	{
		Phpfox::getLib('database')->update
			(	$table,
				array($field => $value),
				'user_id=' . Phpfox::getUserId()
			);
	}
}
?>