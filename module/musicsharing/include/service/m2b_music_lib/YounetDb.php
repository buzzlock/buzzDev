<?php

/**
 * author: Nguyen Cong Minh
 */
class YounetDb
{

    private $connection;

    public function ConnectDatabase()
    {
        return $this->connection;
    }

    public function prefix()
    {
        return phpFox::getParam(array('db', 'prefix'));
    }

    function __construct()
    {
        $connection = mysql_connect(phpFox::getParam(array('db', 'host')), phpFox::getParam(array('db', 'user')), phpFox::getParam(array('db', 'pass')));
        if (!$connection)
            die("can't connect server");

        $db_selected = mysql_select_db(phpFox::getParam(array('db', 'name')), $connection);
        if (!$db_selected)
            die("have not database");
        $this->connection = $connection;
    }

    function __destruct()
    {
        //mysql_close($this->connection) ;
    }

    /* public function ConnectDatabase()
      {
      $connection = mysql_connect(phpFox::getParam(array('db', 'host')), phpFox::getParam(array('db', 'user')), phpFox::getParam(array('db', 'pass')));
      if (!$connection)
      die("can't connect server");

      $db_selected = mysql_select_db(phpFox::getParam(array('db', 'name')),$connection);
      if (!$db_selected)
      die ("have not database");
      return $connection;
      } */

    function convertURL($str)
    {
        $str = strtolower($str);
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", "a", $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", "e", $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", "i", $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", "o", $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", "u", $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", "y", $str);
        $str = preg_replace("/(đ)/", "d", $str);
        $str = preg_replace("/(!|@|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:|\;|\'| |\"|\&|\#|\[|\]|~|$|_)/", "-", $str);
        $str = preg_replace("/(-+-)/", "-", $str);
        $str = preg_replace("/(^\-+|\-+$)/", "", $str);
        $str = preg_replace("/(-)/", " ", $str);
        return $str;
    }

}

?>