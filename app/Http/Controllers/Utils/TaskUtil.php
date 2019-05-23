<?php

/**
 * TaskUtil.php
 *
 * @category Utils
 * @package  App\Http\Controllers\Utils
 * @author   zhujiaojiao
 * @license  MIT License
 */
namespace App\Http\Controllers\Utils;
use PDO;
/**
 * task工具类
 * Class TaskUtil
 *
 * @category Utils
 * @package  App\Http\Controllers\Utils
 * @author   zhujiaojiao
 * @license  MIT License
 */
class TaskUtil
{
    /**
     * 
     *
     * @param string $pmoName 父节点
     * @param string $rows 子节点
     * @return json
     */
    public function constructTree($directory, $user = null){
        $mydir   = opendir($directory);
        $content = array();
        while ($file = readdir($mydir)) {
            $temp = [];
            if ((is_dir("$directory/$file")) && ($file != ".") && ($file != "..")) {
                $childrens = $this->constructTree("$directory/$file");
                $temp['label'] = $file;
                $temp['directory'] = $directory.'/'.$file;
                $temp['user'] = $user;
                if ($childrens) {
                    $temp['children'] = $childrens;
                }
                $content[] = $temp;
            }//end if
        }//end while
        closedir($mydir);
        return $content;
    }


}//end class
