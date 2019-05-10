<?php

/**
 * KgetUtil.php
 *
 * @category Utils
 * @package  App\Http\Controllers\Utils
 * @author   zhujiaojiao
 * @license  MIT License
 */
namespace App\Http\Controllers\Utils;
use PDO;
/**
 * kget工具类
 * Class KgetUtil
 *
 * @category Utils
 * @package  App\Http\Controllers\Utils
 * @author   zhujiaojiao
 * @license  MIT License
 */
class KgetUtil
{
    /**
     * 
     *
     * @param string $pmoName 父节点
     * @param string $rows 子节点
     * @return json
     */
    public function constructMoTree($parent, $rows){

        $result = array();

        foreach ($rows as $row) {

            $arr = array();

            if ($row['pmoName'] == $parent) {

                $arr['id'] = $row['id'];

                if ( strpos($row['moName'], "_2") !== false) {

                    $arr['label'] = str_replace("_2", "_FDD", $row['moName']);

                } else{

                    $arr['label'] = $row['moName'];
                }
                
                if ($children = $this->constructMoTree($row['moName'], $rows)) {

                    $arr['children'] = $children;
                }

                array_push($result, $arr);
            }
        }
        return $result;
    }


}//end class
