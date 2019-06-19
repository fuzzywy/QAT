<?php

/**
 * UserUtil.php
 *
 * @category Utils
 * @package  App\Http\Controllers\Utils
 * @author   zhujiaojiao
 * @license  MIT License
 */
namespace App\Http\Controllers\Utils;
use PDO;
/**
 * user工具类
 * Class UserUtil
 *
 * @category Utils
 * @package  App\Http\Controllers\Utils
 * @author   zhujiaojiao
 * @license  MIT License
 */
class UserUtil
{
    /**
     * @param string $parent 父节点
     * @param string $rows 子节点
     * @return json
     */
    public function constructTree($parent, $rows){
        $result = array();
        foreach ($rows as $row) {
            $arr = array();
            if ($row['pmenu'] == $parent) {
                $arr['id'] = $row['id'];
                $arr['label'] = $row['menu'];
                if ($children = $this->constructTree($row['id'], $rows)) {
                    $arr['children'] = $children;
                }
                array_push($result, $arr);
            }
        }
        return $result;
    }
    /**
     * @param string $parent 父节点Id
     * @param string $rows 子节点
     * @param string $pmenu 父节点
     * @return json
     */
    public function constructMenuTree($parent, $rows, $pmenu=null){
        $result = array();
        foreach ($rows as $row) {
            $arr = array();
            if ($row['pmenu'] == $parent) {
                $arr['id'] = $row['id'];
                $arr['index'] = $pmenu.'-'.$row['menu'];
                $arr['name'] = $row['menu'];
                $children = array();
                if ($pmenu) {
                    $arr['index'] = $pmenu.'-'.$row['menu'];
                    $children = $this->constructMenuTree($row['id'], $rows, $row['menu']);
                } else {
                    $arr['index'] = $row['menu'];
                    $children = $this->constructMenuTree($row['id'], $rows);
                }
                if ($children) $arr['children'] = $children;
                array_push($result, $arr);
            }
        }
        return $result;
    }


}//end class
