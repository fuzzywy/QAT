<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use PDO;
use Exception;
use App\Http\Controllers\Common\DataBaseConnection;
use App\Models\TemplateNbi;
use App\Models\KpiformulaNbi;
trait KpiNbmQueryHandler
{
    use FormulaHandler;
    //数据库类型：ENIQ
    public $dataSource;
    //数据类型：TDD/FDD/NBIOT
    public $dataType;
    //模版名Id
    public $templateId;
    //模版名
    public $template;
    //时间类型：天/day,天组/dayGroup,小时/hour,小时组/hourgroup,15分钟/quarter
    public $timeDim;
    //查询维度：city/城市,baseStation/基站,baseStationGroup/基站组,cell/小区,cellGroup/小区组
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
    //小时
    public $hour;
    public $minute;

    public $resultText;
    public $sql;

    /**
     * 初始化请求值
     *
     * @param object
     *
     * @return void
     */

    public function init(Request $request)
    {   

        $dc = new DataBaseConnection;
        $cityNames = "";
        foreach ($request["cities"] as $city) {
            $cityNames = $cityNames . "'" . $dc->getNbiOptions($city) . "',";
        }
        $cityNames = rtrim($cityNames, ",");
        $this->cities = $cityNames;
        $this->dataSource = $request['dataSource'];
        $this->dataType = $request['dataType'];
        $this->templateId = $request['template'];
        $this->timeDim = $request['timeDim'];
        $this->locationDim = $request['locationDim'];
        $this->baseStation = $request['baseStation'];
        $this->cell = "'" . implode("','", explode(",", $request['cell'])) . "'";
        $this->dateStart =substr($request['date'][0],0,10);
        $this->dateEnd =substr($request['date'][1],0,10);
        $this->hour = implode(",", $request['hour']);
        $this->minute = implode(",", $request['minute']);
        $this->result="";
        $this->sql = new \stdClass();
        $res = TemplateNbi::select('templateName','description')
                ->where("id",$this->templateId)->get()
                ->toArray();
        $this->template = $res[0]['templateName'];
    }

    /**
     * 创建sql语句
     *
     * @param void
     *
     * @return string
     */

    public function createsql()
    {   
        
        switch($this->timeDim) {
            case "day":
                $this->sql->selectsql = "SELECT date_id as date,";
                $this->sql->wheresql = " WHERE date_id>='$this->dateStart' and date_id<='$this->dateEnd' ";
                $this->sql->groupsql = "GROUP BY date_id,";
                $this->sql->resultText = "day,";
                break;
            case "dayGroup":
                break;
            case "hour":
                $this->sql->selectsql = "SELECT date_id as date,hour_id,";
                $this->sql->wheresql = " WHERE date_id>='$this->dateStart' and date_id<='$this->dateEnd' and hour_id in ($this->hour) ";
                $this->sql->groupsql = "GROUP BY date_id,hour_id,";
                $this->sql->resultText = "day,hour,";
                break;
            case "hourGroup":
                $this->sql->selectsql = "SELECT date_id as date,'$this->hour' as hourGroup,";
                $this->sql->wheresql = " WHERE date_id>='$this->dateStart' and date_id<='$this->dateEnd' and hour_id in ($this->hour) ";
                $this->sql->groupsql = "GROUP BY date_id,";
                $this->sql->resultText = "day,hourGroup,";
                break;
            case "quarter":
                $this->sql->selectsql = "SELECT date_id as date,hour_id,min_id,";
                $this->sql->wheresql = " WHERE date_id>='$this->dateStart' and date_id<='$this->dateEnd' and hour_id in ($this->hour) and min_id in ($this->minute) ";
                $this->sql->groupsql = "GROUP BY date_id,hour_id,min_id,";
                $this->sql->resultText = "day,hour,min,";
                break;
        }
        switch($this->locationDim) {
            case "city":
                $this->sql->selectsql .= "city as City,";
                $this->sql->wheresql .= " and city in ($this->cities) ";
                $this->sql->groupsql .= "City";
                $this->sql->resultText .= "city,";
                break;
            case "baseStation":
                $this->sql->selectsql .= "city as City,erbs AS ManagedElement,count(distinct erbs) as erbsTotal,";
                $this->sql->wheresql .= " and city in ($this->cities) ";
                $this->sql->groupsql .= "City,ManagedElement";
                $this->sql->resultText .= "city,erbs,erbsTotal,";
                break;
            case "baseStationGroup":

                break;
            case "cell":
                $this->sql->selectsql .= "city as City,UserLabel AS EutranCellTdd,count(distinct UserLabel) as ecgiTotal,";
                $this->sql->wheresql .= " and city in ($this->cities) ";
                $this->sql->groupsql .= "City,EutranCellTdd";
                $this->sql->resultText .= "city,EutranCellTdd,ecgiTotal,";
                break;
            case "cellGroup":
                $this->sql->selectsql .= "city as City,'cellGroup' as cellgroup,count(distinct UserLabel) as cellTotal,";
                $this->sql->wheresql .= " and city in ($this->cities) and UserLabel in ($this->cell) ";
                $this->sql->groupsql .= "City";
                $this->sql->resultText .= "city,cellgroup,cellTotal,";
                break;  
        }
        $kpis = $this->getKpis();
        $nosum_map = "";
        $counterMap = $this->getCheckCounters($kpis['ids'],$nosum_map);
        $nbiKpiFormula = "";
        for ($i = 0, $len = count($kpis["kpiformula"]); $i < $len;$i++) {
            $nbiKpiFormula = $nbiKpiFormula.$this->formulaTransform($kpis["kpiformula"][$i]["kpiFormula"])." as '".$kpis["kpiformula"][$i]["kpiName"]."',";
            $this->sql->resultText .= $kpis["kpiformula"][$i]["kpiName"]."',";
        }
        $nbiKpiFormula = substr($nbiKpiFormula, 0, (strlen($nbiKpiFormula) - 1));

        $sql = $this->sql->selectsql . $nbiKpiFormula . " from " . $nosum_map . $this->sql->wheresql . $this->sql->groupsql;
        return $sql;
    }

    /**
     * 获取模版信息
     * @return   array   模版对应的信息
     */
    public function getKpis()
    {

        $elementId = TemplateNbi::select("elementId")->where("id", $this->templateId)->first()->toArray();
        $kpis   = $elementId['elementId'];
        $kpisStr = ",".$kpis.",";

        $res = DB::select("select kpiName,kpiPrecision,kpiFormula,instr('$kpisStr',CONCAT(',',id,',')) as sort from kpiFormulaNbi where id in ($kpis) order by sort");

        $kpiNames     = "";
        $result          = array();
        foreach ($res as $row) {
            $result['kpiformula'][] = array('kpiName'=>$row->kpiName,
                                            'kpiPrecision'=>$row->kpiPrecision,
                                            'kpiFormula'=>$row->kpiFormula,
                                             'sort'=> $row->sort);
            $kpiNames = $kpiNames.trim($row->kpiName).",";
        }

        $kpiNames        = substr($kpiNames,0,-1);
        $result['ids']   = $kpis;
        $result['names'] = $kpiNames;
        return $result;
    }

    /**
     * 获取模版公式的table和检查出不存在的counter
     * @DateTime 2019-01-15
     * @param    string     $queryFormula 公式id
     * @param    array     &$nosum_map   存放表名
     * @return   array                   counter表里没有的counter名
     */
    protected function getCheckCounters($queryFormula,&$nosum_map) {
        if($this->dataType=="TDD"){
            $counters = unserialize(Redis::get("counters_EutranCellTdd"));

        }elseif($this->dataType=="FDD"){
            $counters = unserialize(Redis::get("counters_EutranCellFdd"));
            
        }elseif($this->dataType=="NBIOT"){
            $counters = unserialize(Redis::get("counters_EutranCellNB"));

        }
        $queryFormula = DB::select("select kpiName,kpiFormula,kpiPrecision,instr('$queryFormula,',CONCAT(id,',')) as sort from kpiFormulaNbi where id in (".$queryFormula.") order by sort");

        $counterMap = array();
        foreach ($queryFormula as $row) {
            $kpi = $row->kpiFormula;
            $pattern       = "/[\(\)\+\*-\/]/";
            $columns       = preg_split($pattern, $kpi);
            $matches       = array();
            $pattern_nosum = "/[^max][^log10][^power][a-zA-Z_0-9]+/";
            foreach ($columns as $column) {
            	if (is_numeric($column)) {
            		continue;
            	}
                $column      = trim($column);
                $counterName = $column;
                if (array_key_exists($counterName, $counters)) {
                    $table = $counters[$counterName];
                } else {
                	throw new Exception("check $counterName");//检查出不存在的counter值
                }
                if ($this->locationDim == "erbs") {
                    if($this->timeDim=="hour"){
                        $table = trim($table)."_station_hour";
                    }else if ($this->timeDim == "day") {
                        $table = trim($table). "_station_day";
                    } else if ($this->timeDim=="quarter") {
                        $table = trim($table); 
                    }
                } else {
                    if($this->timeDim=="hour"){
                        $table = trim($table)."_cell_hour";
                    }else if ($this->timeDim == "day") {
                        $table = trim($table). "_cell_day";
                    } else if ($this->timeDim=="quarter") {
                        $table = trim($table); 
                    }
                }
                $nosum_map = $table;
            }//end foreach
        }
        
        return $counterMap;
    }

     /**
     * KPI公式转换
     *
     * @param string $formula KPI公式
     *
     * @return mixed|string
     */
    protected function formulaTransform($formula)
    {
        if (strpos($formula, '+') == false && strpos($formula, '-') == false && strpos($formula, '*') == false && strpos($formula, '/') == false && strpos($formula, '(') == false && strpos($formula, ')') == false) {
            $formula = "SUM(".$formula.")";
            return $formula;
        }

        $fields  = preg_split("/[-+*\/() ]+/", trim($formula));
        $formula = " ".$formula." ";
        $formula = str_replace("+", " + ", $formula);
        $formula = str_replace("-", " - ", $formula);
        $formula = str_replace("*", " * ", $formula);
        $formula = str_replace("/", " / ", $formula);
        $formula = str_replace("(", " ( ", $formula);
        $formula = str_replace(")", " ) ", $formula);

        foreach ($fields as $value) {
            if ($value == "" || is_numeric($value) || $value == "power" || $value == "log10" || $value == "max" || $value == "POWER" || $value == "LOG10" || $value == "MAX" || $value == "AVG" || $value == "avg") {
                continue;
            }

            $new_value = "SUM(".$value.")";
            $formula   = str_replace(" ".$value." ", $new_value, $formula);
        }

        $formula = str_replace(" ", "", $formula);

        if (strpos($formula, 'MAX(SUM(') !== false) {
            $formula = str_replace("MAX(SUM(", "(MAX(", $formula);
        }

        if (strpos($formula, 'max(SUM(') !== false) {
            $formula = str_replace("max(SUM(", "(MAX(", $formula);
        }

        if (strpos($formula, 'AVG(SUM(') !== false) {
            $formula = str_replace("AVG(SUM(", "(AVG(", $formula);
        }

        if (strpos($formula, 'avg(SUM(') !== false) {
            $formula = str_replace("avg(SUM(", "(avg(", $formula);
        }

        return $formula;

    }//end formulaTransform()

    /**
     * 文件下载
     * @DateTime 2019-01-15
     * @param    array     $data  文件数据
     * @param    string    $title 文件title
     * @return   string           文件名
     */
    public function download($data, $title)
    {   
        $fileName="common/files/".trim($this->template)."_".date("YmdHis").".csv";
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