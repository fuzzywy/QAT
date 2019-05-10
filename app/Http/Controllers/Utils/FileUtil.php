<?php

/**
 * FileUtil.php
 *
 * @category Utils
 * @package  App\Http\Controllers\Utils
 * @author   zhujiaojiao
 * @license  MIT License
 */
namespace App\Http\Controllers\Utils;
use PDO;
/**
 * file工具类
 * Class FileUtil
 *
 * @category Utils
 * @package  App\Http\Controllers\Utils
 * @author   zhujiaojiao
 * @license  MIT License
 */
class FileUtil
{
    /**
     * 压缩CSV文件
     *
     * @return array|string
     */
    public function getZipFile($file)
    {
        $fileSize = filesize($file);
        if (is_dir($file) || $fileSize >= 10000000) {
            $fileDir  = explode('/', $file);
            $fileName = $fileDir[(count($fileDir) - 1)];
            $dir      = "";
            for ($i = 0,$count=count($fileDir) - 1; $i < $count; $i++) {
                $dir = $dir.$fileDir[$i]."/";
            }
            exec('tar -C '.$dir.' -cvzf '.$file.'.tar.gz '.$fileName);
            return $file.'.tar.gz';
        } else {
            return $file;
        }

    }//end getZipFile()


}//end class
