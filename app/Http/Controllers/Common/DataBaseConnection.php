<?php

/**
 * DataBaseConnection.php
 *
 * @category Common
 * @package  App\Http\Controllers\Common
 * @author   ericsson <genius@ericsson.com>
 * @license  MIT License
 * @link     https://laravel.com/docs/5.4/controllers
 */
namespace App\Http\Controllers\Common;

use Config;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Models\Databaseconns;
use App\Eniq;
use PDO;
use APP;
/**
 * 工具类
 * Class DataBaseConnection
 *
 * @category Common
 * @package  App\Http\Controllers\Common
 * @author   ericsson <genius@ericsson.com>
 * @license  MIT License
 * @link     https://laravel.com/docs/5.4/controllers
 */
class DataBaseConnection
{    /**
     * 获取数据库连接句柄
     *
     * @param string $db 数据库名
     * 
     * @param null $dbName 数据库名
     *
     * @return PDO
     */
    public function getDB($db, $dbName = null)
    {
      if($dbName != null){
            Config::set("database.connections.$db.database",$dbName);
        }
        return DB::connection($db)->getPdo();

        // return $pdo;

    }//end getDB()

    /**
     * 获取城市英文名
     *@return string
     */
    public function getConnName($cityChinese){
        /*if (App::isLocale('en')) {
            if (trans('message.city.'.$cityChinese)) {
                return trans('message.city.'.$cityChinese);
            }
        }*/
        $row      = DB::table('cities')->select('conname')->where('city',$cityChinese)->first();
        return $row ? $row->conname : '';
    }//end getConnName()

    public function getSubnetwork($citys,$dataType,$type)
    {           

        $subNetWork = array();
        switch($dataType)
        {
            case "ENIQ":
            case "KGET":
            switch($type)
            {
                case "TDD":
                $str = "subNetworkTdd";
                break;
                case "FDD":
                $str = "subNetworkFdd";
                break;
                case "NBIOT":
                $str = "subNetworkNbiot";
                break;
                case "ALL":
                $str = "replace(trim(both ',' from concat_ws(',',GROUP_CONCAT(subNetworkTdd),GROUP_CONCAT(subNetworkFdd),GROUP_CONCAT(subNetworkNbiot))), ',,' , ',')";
                break;
                
            }
            foreach ($citys as $key => $value) {
                    $subnet = Eniq::selectRaw("conn , ".$str." as subNetwork")->where("city",$value)->get()->toArray();
                    //print_r($subnet);
                    foreach ($subnet as  $v) {
                         if($v['subNetwork']){
                            $subNetWork[$v['conn']]=array_unique(explode(",", $v['subNetwork']));
                         }
                    }
                }
            break;
        }
        return $subNetWork;

    }
    /**
     * 获取最新的kget时间
     */
    public function getKgetTime(){

        $dbn = $this->getDB('mongs');
        $sql = "select taskName from task where taskName like 'kget1_____' order by endTime desc limit 1 ";
        $res = $dbn->query($sql)->fetch(PDO::FETCH_ASSOC);
        
        return $res['taskName']; 
    }

    public function getSubNetsArr($type,$cityChinese){

        switch($type){
            case "TDD":
            $result = Eniq::select("subNetworkTdd as subNet")->where("city",$cityChinese)->get()->toArray();
            break;
            case "FDD":
            $result = Eniq::select("subNetworkFdd as subNet")->where("city",$cityChinese)->get()->toArray();
            break;
            case "NBIOT":
            $result = Eniq::select("subNetworkNbiot as subNet")->where("city",$cityChinese)->get()->toArray();
            break;
            case "ALL":
            $result = Eniq::selectRaw("replace(trim(both ',' from concat_ws(',',GROUP_CONCAT(subNetworkTdd),GROUP_CONCAT(subNetworkFdd),GROUP_CONCAT(subNetworkNbiot))), ',,' , ',') as subNet")->where("city",$cityChinese)->get()->toArray();
            break;
        }

        $subNet =array();
        foreach ($result as $key => $value) {
            $subNet= array_merge($subNet,explode(",", $value['subNet']));
        }
        return array_filter($subNet);
    }
        public function getSubNetsStr($type,$cityChinese){

        switch($type){
            case "TDD":
            $result = Databaseconns::select("subNetwork as subnet")->where("cityChinese",$cityChinese)->get()->toArray();
            break;
            case "FDD":
            $result = Databaseconns::select("subNetworkFDD as subnet")->where("cityChinese",$cityChinese)->get()->toArray();
            break;
            case "NBIOT":
            $result = Databaseconns::select("subNetworkNbiot as subnet")->where("cityChinese",$cityChinese)->get()->toArray();
            break;
        }

        $subnet ="";
        foreach ($result as $key => $value) {
            $array = array_filter(explode(",", $value['subnet']));
            if($array){
                foreach ($array as $k => $v) {
                    $subnet.="'".$v."',";
                }
            }
           
        }
        $subnet = rtrim($subnet,",");
        return $subnet;
    }

     public function getSubNetsByconnName($type,$connName){

        switch($type){
            case "TDD":
            $result = Databaseconns::select("subNetwork as subnet")->where("connName",$connName)->get()->toArray();
            break;
            case "FDD":
            $result = Databaseconns::select("subNetworkFDD as subnet")->where("connName",$connName)->get()->toArray();
            break;
            case "NBIOT":
            $result = Databaseconns::select("subNetworkNbiot as subnet")->where("connName",$connName)->get()->toArray();
            break;
        }
        $subnet = "";
        if($result){
            $array = array_filter(explode(",", $result[0]['subnet']));
            if($array){
                foreach ($array as $k => $v) {
                    $subnet.="'".$v."',";
                }
            }
            $subnet = rtrim($subnet,",");
        }
        return $subnet;
    }

    public  function getSN($oss) {
        $SN = "";
        switch ($oss) {
            case 'wuxiENM':
            case "zhenjiang":
                $SN = "substring(substring(SN, 0, charindex(',',SN)-1), 12)";
                break;
            case "wuxi1":
                $SN = "substring(SN, 12, charindex(',', SN)-12)";
                break;
          case "wuxi":
            $SN = "substring(SN, 12, charindex(',', SN)-12)";
            break;
            case "zhenjiang1":
                $SN = "substring(substring(SN, charindex(',', SN)+12), 0, charindex(',', substring(SN, charindex(',', SN)+12))-1)";
                break;
            case "suzhou3":
                $SN = "substring(SN, 12, charindex(',', SN)-12)";
                break;
            case "changzhou":
              $SN = "substring(SN, charindex('=', SN)+1, charindex(',', SN)-charindex('=', SN)-1)";
              break;
            case "suzhou4":
              $SN = "substring(SN, charindex('=', SN)+1, charindex(',', SN)-charindex('=', SN)-1)";
              break;
            default:
                $SN = "substring(SN,charindex('=',substring(SN,32,25))+32,charindex(',',substring(SN,32,25))-charindex('=',substring(SN,32,25))-1)";
                break;
        }
        return $SN;
    }
    public  function getDC($oss) {
        $dc = "";

        switch ($oss){
            case "qingyuan":
            $dc = "";
            break;
            default:
            $dc = "dc.";
        }
        return $dc;
    }
    /**
     * Check数据表是否存在
     *
     * @param string $schema 数据库名
     * 
     * @param string $table 数据表名
     *
     * @return boolean
     */
    public function tableIfExists($schema, $table)
    {
        $dbn = $this->getDB('mongs', 'information_schema');
        $sql = "select TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = '$schema' AND TABLE_NAME='$table'";
        $rs = $dbn->query($sql)->fetchcolumn();
        if ($rs) {
            return true;
        } else {
            return false;
        }

    }//end tableIfExists()


    public function getCddDB(){
        $dbn = $this->getDB('mongs', 'information_schema');
        $sql = "select TABLE_SCHEMA FROM information_schema.TABLES WHERE TABLE_SCHEMA like 'CDD_20______' order by TABLE_SCHEMA desc limit 1";
        $res = $dbn->query($sql)->fetchall(PDO::FETCH_ASSOC);
        return ($res[0]['TABLE_SCHEMA']);

    }


        /**
     * 获得NBI城市名
     *
     * @param string $city 中文城市名
     *
     * @return string NBI城市名
     */
    public function getNbiOptions($city)
    {
        $nbiCity = '';
        if ($city == 'changzhou') {
            $nbiCity = 'ERICSSON-CMJS-CZ';
        } else if ($city == 'wuxi') {
            $nbiCity = 'ERICSSON-CMJS-WX';
        } else if ($city == 'zhenjiang') {
            $nbiCity = 'ERICSSON-CMJS-ZJ';
        } else if ($city == 'nantong') {
            $nbiCity = 'ERICSSON-CMJS-NT';
        } else if ($city == 'suzhou') {
            $nbiCity = 'ERICSSON-CMJS-SZ';
        }

        return $nbiCity;

    }//end getNbiOptions()
}//end class
