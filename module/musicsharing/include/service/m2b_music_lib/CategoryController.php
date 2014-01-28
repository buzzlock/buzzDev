<?php

/**
 * author: Nguyen Cong Minh
 */
//  METHODS IN THIS CLASS
//  category()
//  category_list()
//  category_delete()
//  category_edit()
require_once "CategoryModel.php";
include_once 'YounetDb.php';

class CategoryController extends YounetDb
{

    function category_create($category)
    {
        $connection = $this->ConnectDatabase();

        mysql_query("SET character_set_client=utf8", $connection);

        $title_url = $this->convertURL($category->get_title_url());

        $strSQL = "INSERT INTO `" . $this->prefix() . "m2bmusic_category` (
                `title` ,
                `title_url`
                )
                VALUES (
                 '" . htmlspecialchars($category->get_title(), ENT_QUOTES) . "', '$title_url');";
        
        mysql_query($strSQL);

        $lastID = mysql_insert_id();

        if (!$lastID)
            $lastID = -1;

        return $lastID;
    }

    //Get list categorys
    function category_list($sort_by = NULL, $limit = NULL)
    {
        $connection = $this->ConnectDatabase();

        mysql_query("SET character_set_client=utf8", $connection);

        // GENERATE QUERY
        $sql = "SELECT *";
        $sql .= " FROM " . $this->prefix() . "m2bmusic_category ";

        // CUSTOM
        if ($sort_by)
            $sql .= " ORDER BY $sort_by ";
        else
            $sql .= "  ORDER BY title ASC";

        // LIMIT
        if ($limit)
            $sql .= " LIMIT";
        if ($limit)
            $sql .= " {$limit} ";

        $categorylist = null;
        $index = 0;
        $resource = mysql_query($sql) or die(mysql_error() . " <b>SQL was: </b>$sql");
        while ($obj = mysql_fetch_assoc($resource))
        {
            $index++;
            $obj['index'] = $index;
            $categorylist[] = $obj;
        }
        
        return $categorylist;
    }

    //Get list category by id ; jh
    function getCategoryById($id = NULL)
    {
        $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_client=utf8", $connection);
        // GENERATE QUERY
        $sql = "SELECT *";
        $sql .= " FROM " . $this->prefix() . "m2bmusic_category ";
        $sql .= " WHERE " . $this->prefix() . "m2bmusic_category.cat_id = " . (int) $id;

        $categorylist = null;
        $index = 0;
        $resource = mysql_query($sql) or die(mysql_error() . " <b>SQL was: </b>$sql");
        while ($obj = mysql_fetch_assoc($resource))
        {
            $index++;
            $obj['index'] = $index;
            $categorylist = $obj;
        }
        //mysql_close($connection);
        return $categorylist;
    }

    //Edit category
    function category_edit($category_id, $title)
    {
        $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_client=utf8", $connection);
        $sql = "SELECT * FROM " . $this->prefix() . "m2bmusic_category WHERE cat_id='{$category_id}'";
        $category = mysql_fetch_assoc(mysql_query($sql));
        if (empty($category))
            return FALSE;
        $title_url = $this->convertURL($title);
        $sql = "Update " . $this->prefix() . "m2bmusic_category set `title` = '" . htmlspecialchars($title, ENT_QUOTES) . "', title_url= '" . $title_url . "' Where cat_id='{$category_id}'";
        mysql_query($sql);
        // mysql_close($connection);
        return TRUE;
    }

    //Delete category
    function category_delete($category_id)
    {
        $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_client=utf8", $connection);
        $sql = "SELECT * FROM " . $this->prefix() . "m2bmusic_category WHERE cat_id='{$category_id}'";
        $category = mysql_fetch_assoc(mysql_query($sql));
        if (empty($category))
            return FALSE;
        mysql_query("DELETE FROM " . $this->prefix() . "m2bmusic_category WHERE cat_id='{$category_id}' LIMIT 1");
        // mysql_close($connection);
        return TRUE;
    }

}

?>