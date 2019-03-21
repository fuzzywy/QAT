<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Common\DataBaseConnection;
use Illuminate\Http\Request;
use PDO;

class AlarmQueryHandler extends Controller
{   
    //时间类型
    public $alarmTime;
    //数据类型：LTE/GSM
    public $alarmstyle;
    //时间类型：天/day
    public $timeDim;
    //查询维度：city/城市,baseStation/基站,cell/小区
    public $locationDim;
    //城市
    public $cities;
    //基站
    public $baseStation;
    //小区
    public $cell;
    //日期
    public $dateStart;
    public $dateEnd;

    public $resultText;
    public $sql;

    public $table;//数据表名


    public function templateQuery(Request $request)
    {   
        $dc = new DataBaseConnection();
        $this->alarmstyle =  $request['dataType'];
        $this->alarmTime = $request["alarmTime"];
        $this->locationDim = $request['locationDim'];
        $this->timeDim = $request['timeDim'];
        $cityNames = "";
        foreach ($request["cities"] as $city) {
            $cityNames = $cityNames . "'" . $this->getEnglishCityName($city) . "',";
        }
        $cityNames = rtrim($cityNames, ",");
        $this->cities = $cityNames;
        if ($request['baseStation']) {
            $this->baseStation = "'" . implode("','", explode(",", $request['baseStation'])) . "'";
        }
        if ($request['cell']) {
            $this->cell = "'" . implode("','", explode(",", $request['cell'])) . "'";  
        }
        
        //时间
        switch ($this->alarmTime) {
            case "Current":
                $this->dateStart = date('Y-m-d');
                $this->dateEnd = date('Y-m-d');
                $this->table = "FMA_alarm_list";
                break;
            case "History":
                $this->dateStart =substr($request['date'][0],0,10);
                $this->dateEnd =substr($request['date'][1],0,10);
                $this->table = "FMA_alarm_log";
                break;
        }
        //数据库
        switch (substr($this->alarmstyle, 0, 3)) {
            case "GSM":
                $pdo = $dc->getDB("alarm_2G");
                $fileType = "2G";
                break;
            case "LTE":
                $pdo = $dc->getDB("alarm");
                $fileType = "4G";
                break;
        }
        $this->sql = new \stdClass();
        $sql = $this->createSql();
        $row = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        $fileName = $this->download($row, $this->sql->resultText, $fileType);

    	return  array("data"=>$row,"file"=>$fileName);
    }


    /**
     * 创建sql语句
     * @param    void 
     * @return   string 
     */

    public function createSql()
    {
        switch(substr($this->alarmstyle, 0, 3)) {
            case "GSM":
                $this->sql->selectsql = "select Event_time,city,subNetwork,ManagedElement,BtsSiteMgr,Cease_time,SP_text,Problem_text,Alarm_id ";
                $this->sql->wheresql = " where city in ($this->cities) and SP_text != '' and Perceived_severity != 5 and Event_time >= '$this->dateStart' and Event_time <= '$this->dateEnd'";
                $this->sql->ordersql = " ORDER BY Event_time DESC";
                if ($this->baseStation) {
                    $this->sql->wheresql .= " and ManagedElement in ($this->baseStation) ";
                }
                if ($this->cell) {
                   $this->sql->wheresql .= " and BtsSiteMgr in ($this->cell) ";
                }
                $this->sql->resultText = "Event_time,city,subNetwork,ManagedElement,BtsSiteMgr,Cease_time,SP_text,Problem_text,Alarm_id";
                break;
            case "LTE":
                $this->sql->selectsql = "SELECT a.Event_time,a.city,a.subNetwork,b.cluster,b.siteType,b.siteNameChinese,c.alarmNameC,c.levelC,a.meContext,a.eutranCell,a.SP_text,a.Problem_text ";
                $this->sql->wheresql = " a LEFT JOIN mongs.siteLte b ON a.eutranCell = b.cellName left join Alarm.AlarmInfo c ON a.SP_text = c.alarmNameE where a.city in ($this->cities) and a.Perceived_severity != 5  AND a.SP_text != '' and a.Event_time >= '$this->dateStart' and a.Event_time <= '$this->dateEnd' ";
                $this->sql->ordersql = " ORDER BY a.Event_time DESC";
                if ($this->baseStation) {
                    $this->sql->wheresql .= " and a.meContext in ($this->baseStation) ";
                }
                if ($this->cell) {
                   $this->sql->wheresql .= " and a.eutranCell in ($this->cell) ";
                }
                $this->sql->resultText = "Event_time,city,subNetwork,cluster,siteType,siteNameChinese,alarmNameC,levelC,meContext,eutranCell,SP_text,Problem_text";
                break; 
        }
        switch(substr($this->alarmstyle, 4, 6)) {
            case "TDD":
                $this->sql->wheresql .= " and b.duplexMode='TDD' ";
                break;
            case "FDD":
                $this->sql->wheresql .= " and b.duplexMode='FDD' ";
                break;
        }
        $sql = $this->sql->selectsql . " from $this->table " . $this->sql->wheresql . $this->sql->ordersql;
        return $sql;

        
    }


    /**
     * 获取城市英文名
     * @param    array 
     * @return   string 
     */
    public function getEnglishCityName($city)
    {
        $englishCity = '';
        if ($city == '常州' || $city == '常州市') {
            $englishCity = 'changzhou';
        } else if ($city == '无锡' || $city == '无锡市') {
            $englishCity = 'wuxi';
        } else if ($city == '镇江' || $city == '镇江市') {
            $englishCity = 'zhenjiang';
        } else if ($city == '南通' || $city == '南通市') {
            $englishCity = 'nantong';
        } else if ($city == '苏州' || $city == '苏州市') {
            $englishCity = 'suzhou';
        }

        return $englishCity;
    }

    /**
     * 文件下载
     * @DateTime 2019-01-15
     * @param    array   
     * @param    string   
     * @param    string    
     * @return   string    
     */
    public function download($data, $title, $fileType)
    {   
        $fileName="common/files/"."alarm"."_".$fileType."_".date("YmdHis").".csv";
        $csvContent = mb_convert_encoding($title."\n", 'GBK');
        $fp         = fopen($fileName, "w");
        fwrite($fp, $csvContent);
        foreach ($data as $row) {
            $newRow = array();
            foreach ($row as $key => $value) {
                $newRow[$key] = mb_convert_encoding($value, 'GBK');
            }

            fputcsv($fp, $newRow);
        }

        fclose($fp);
        return $fileName;
    }
}
