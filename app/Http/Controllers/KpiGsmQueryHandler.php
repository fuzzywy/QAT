<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 2018/11/27
 * Time: 18:02
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;
use PDO;
use Exception;
use App\Models\Template;
use App\Models\Kpiformula;
trait KpiGsmQueryHandler
{
    //数据库类型：ENIQ
    public $dataSource;
    
    //数据类型：TDD/FDD/NBIOT/GSM
    public $dataType;
    
    //模版名id
    public $templateId;
    //模版名
    public $template;

    //时间类型：天/day,天组/dayGroup,小时/hour,小时组/hourgroup,15分钟/quarter
    public $timeDim;

    //查询维度：city/城市,BSC,BSCGroup,cell/小区,cellGroup/小区组
    public $locationDim;
    
    //城市
    public $cities;
    
    //子网
    public $subnets;
    
    //基站
    public $baseStation;
    
    //小区
    public $cell;
    
    //日期
    public $dateStart;
    public $dateEnd;
    
    //小时
    public $hour;
    //分钟
    public $minute;

    public $resultText;
    public $sql;



   /**
     * 查询初始化赋值
     * @DateTime 2019-01-15
     * @param    Request    $request 
     * @return   
     */
    public function init(Request $request)
    {   
        $this->dataSource = "ENIQ";
        $this->dataType = $request['dataType']; //GSM
        $this->templateId = $request['template']; //模板名称
        $this->timeDim = $request['timeDim'];
        $this->locationDim = $request['locationDim'];
        $this->baseStation = $request['baseStation'];
        $this->cell = $request['cell'];
        $this->dateStart =substr($request['date'][0],0,10);
        $this->dateEnd =substr($request['date'][1],0,10);
        $this->hour = $request['hour'];
        $this->minute = $request['minute'];
        $this->result=""; //title
        $res = Template::select('templateName')
                ->where("id",$this->templateId)
                ->first()->toArray();

        $this->template = $res['templateName'];
        $this->sql = new \stdClass();
    }

     /**
     * 生成sql语句
     * @DateTime 2019-01-15
     * @param    string     $city       城市
     * @return   string                 sql语句
     */
    public function createSql($city)
    {   

        switch ($this->timeDim) {
            case 'day':
                $this->sql->selectSql = "select AGG_TABLE0.DAY,AGG_TABLE0.location,";
                $this->sql->resultText ="day,location,";
                $this->sql->orderbySql = " ORDER BY AGG_TABLE0.DAY";
                $this->sql->groupbySql = " GROUP BY AGG_TABLE0.DAY,AGG_TABLE0.location";
                break;
            case 'dayGroup':
                $this->sql->selectSql = "select AGG_TABLE0.DAY,AGG_TABLE0.location,";
                $this->sql->resultText ="dayGroup,location,";
                $this->sql->orderbySql = " ORDER BY AGG_TABLE0.DAY";
                $this->sql->groupbySql = " GROUP BY AGG_TABLE0.DAY,AGG_TABLE0.location";
                break;
            case 'hour':
                $this->sql->selectSql = "select AGG_TABLE0.DAY,AGG_TABLE0.HOUR,AGG_TABLE0.location,";
                $this->sql->resultText ="day,hour,location,";
                $this->sql->orderbySql = " ORDER BY AGG_TABLE0.DAY,AGG_TABLE0.HOUR";
                $this->sql->groupbySql = " GROUP BY AGG_TABLE0.DAY,AGG_TABLE0.location,AGG_TABLE0.HOUR";
                break;
            case 'hourGroup':
                $this->sql->selectSql = "select AGG_TABLE0.DAY,AGG_TABLE0.HOUR,AGG_TABLE0.location,";
                $this->sql->resultText ="day,hourGroup,location,";
                $this->sql->orderbySql = " ORDER BY AGG_TABLE0.DAY,AGG_TABLE0.HOUR";
                $this->sql->groupbySql = " GROUP BY AGG_TABLE0.DAY,AGG_TABLE0.location,AGG_TABLE0.HOUR";
                break;
            case 'quarter':
                $this->sql->selectSql = "select AGG_TABLE0.DAY,AGG_TABLE0.HOUR,AGG_TABLE0.MINUTE,AGG_TABLE0.location,";
                $this->sql->resultText ="day,hour,minute,location,";
                $this->sql->orderbySql = " ORDER BY AGG_TABLE0.DAY,AGG_TABLE0.HOUR,AGG_TABLE0.MINUTE";
                $this->sql->groupbySql = " GROUP BY AGG_TABLE0.DAY,AGG_TABLE0.location,AGG_TABLE0.HOUR,AGG_TABLE0.MINUTE";
                break;
        }

        if($this->locationDim=="BSC"){
            $this->sql->selectSql.="AGG_TABLE0.BSC,";
            $this->sql->resultText .="BSC,";
            $this->sql->groupbySql.=",AGG_TABLE0.BSC";

        }
        if($this->locationDim=="cell"){
            $this->sql->selectSql.="AGG_TABLE0.CELL_NAME,";
            $this->sql->resultText .="cell,";
            $this->sql->groupbySql.=",AGG_TABLE0.CELL_NAME";


        }

        $kpis=$this->getKpis();
        
        $counterMap = $this->getCheckCounters($kpis['ids']);
        $tables  = array_keys(array_count_values($counterMap));
        $aggselectSQL=$this->createSelectSql($city);
        $filterSQL=$this->createFilterSql($city);
        $groupbySQL=$this->createGroupBySql();

        $index  = 0;
        $kpiStr = "";
 
        foreach ($kpis['kpiformula'] as $key => $value) {
                $formula = $value['kpiFormula'];
                $formula = "cast(".$this->formulaTransform($formula)." as decimal(18,".$value['kpiPrecision']."))";
                $kpiStr    = $kpiStr.$formula." as kpi".$index.",";
                $index++;
        }

        $kpiStr = rtrim($kpiStr,",");
        $this->sql->resultText .=$kpis['names']; 

        $tempTableSQL = "";
        $index        = 0;
        foreach ($tables as $table) {
            $countersForQuery = array_keys($counterMap, $table);
            $tableSQL         = $this->createSubQuerySql($countersForQuery,$aggselectSQL,$filterSQL,$groupbySQL,$table);
            $tableSQL         = $tableSQL."as AGG_TABLE$index ";
            if ($index == 0) {
                if ($index != (sizeof($tables) - 1)) {
                    $tableSQL = $tableSQL." left join";
                }
            } else {
                if ($this->timeDim == "dayGroup") {
                    $tableSQL = $tableSQL."on AGG_TABLE0.DAY = AGG_TABLE$index.DAY";
                } else if ($this->timeDim == "day" || $this->timeDim == 'dayGroup') {
                    $tableSQL = $tableSQL."on AGG_TABLE0.day = AGG_TABLE$index.day";
                } else if ($this->timeDim == "hour") {
                    $tableSQL = $tableSQL."on AGG_TABLE0.day = AGG_TABLE$index.day and AGG_TABLE0.hour = AGG_TABLE$index.hour";
                } else if ($this->timeDim == "hourGroup") {
                    $tableSQL = $tableSQL."on AGG_TABLE0.day = AGG_TABLE$index.day and AGG_TABLE0.hour = AGG_TABLE$index.hour";
                } else if ($this->timeDim == "quarter") {
                    $tableSQL = $tableSQL."on AGG_TABLE0.day = AGG_TABLE$index.day and AGG_TABLE0.hour = AGG_TABLE$index.hour and AGG_TABLE0.minute = AGG_TABLE$index.minute";
                }
                if ($this->locationDim == 'cell' ) {
                    $tableSQL = $tableSQL." and AGG_TABLE0.CELL_NAME = AGG_TABLE$index.CELL_NAME";
                }
                // echo $locationDim;
                if ($this->locationDim == "cellGroup" || $this->locationDim == "BSCGroup") {
                    // echo $locationDim;
                    $tableSQL = $tableSQL;
                } else {
                    // echo $tableSQL;
                    $tableSQL = $tableSQL." and AGG_TABLE0.location = AGG_TABLE$index.location";
                }

                if ($index != (sizeof($tables) - 1)) {
                    $tableSQL = $tableSQL." left join ";
                }
            }//end if
            $tempTableSQL = $tempTableSQL.$tableSQL;
            $index++;
        }//end foreach

        
        $sql =    $this->sql->selectSql.$kpiStr.' from '.$tempTableSQL.$this->sql->groupbySql.$this->sql->orderbySql;
 
        return $sql;


    }

    /**
     * 生成select sql
     * @DateTime 2019-01-15
     * @param    string     $city 城市
     * @return   string          select sql
     */
    public function createSelectSql($city)
    {      
    
        switch ($this->timeDim) {
            case 'day':
               $sql = "select convert(char(10),date_id) as day,'$city' as location,";
                break;
            case 'dayGroup':
               $sql = "select 'ALLDAY' as day,'$city' as location,";
                break;
            case 'hour':
                $sql = "select convert(char(10),date_id) as day,hour_id as hour,'$city' as location,";
                break;
            case 'hourGroup':
                if (!$this->hour) {
                    $hour = 'AllHour';
                }else{
                    $hour = implode(',',$this->hour);
                }
                $sql = "select convert(char(10),date_id) as day,'$hour' as hour,'$city' as location,";
                break;
            case 'quarter':
                $sql = "select convert(char(10),date_id) as day,hour_id as hour,min_id as minute,'$city' as location,";
                break;
        }
        if($this->locationDim=="BSC"){
        	$sql.="BSC,";
        }
        if($this->locationDim=="cell"){
        	$sql.="CELL_NAME,";
        }
        return $sql;
    }


    /**
     * 生成filter sql
     * @DateTime 2019-01-15
     * @param    string     $city 城市
     * @return   string           filter sql
     */
    public function createFilterSql($city)
    {   


        $aggWhereSql = " where date_id>='".$this->dateStart."' and date_id<='".$this->dateEnd."'";
        switch ($this->timeDim) {
            case 'day':
            case 'dayGroup':
                 $aggWhereSql.="and TIMELEVEL = 'HOUR'";
                break;
            case 'hour':
            case 'hourGroup':
                if($this->hour){
                    $hour = implode(",", $this->hour);
                    $aggWhereSql.="and TIMELEVEL = 'HOUR' and hour_id in($hour)";
                }else{
                    $aggWhereSql.="and TIMELEVEL = 'HOUR'";

                }
                break;
            case 'quarter':
                if($this->hour){
                    $hour = implode(",", $this->hour);
                    $aggWhereSql.="and hour_id in($hour)";
                } 
                if($this->minute){
                    $minute = implode(",", $this->minute);
                    $aggWhereSql.="and min_id in($minute) AND TIMELEVEL = '15MIN'";
                }
                break;
        }


        if($this->locationDim=="BSC"||$this->locationDim=="BSCGroup"){
            if($this->baseStation){
                $baseStation = trim($this->baseStation);
                $baseStation="'".str_replace(",", "','", $baseStation)."'";
                $aggWhereSql.="and BSC in($baseStation)";
            }
        }
          if($this->locationDim=="cell"||$this->locationDim=="cellGroup"){
            if($this->cell){
                $cell = trim($this->cell);
                $cell="'".str_replace(",", "','", $cell)."'";
                $aggWhereSql.="AND CELL_NAME IN ($cell)";
            }
        }

        return $aggWhereSql;
    }

     /**
     * 生成 groupBy sql
     * @DateTime 2019-01-15
     * @return   string group by sql
     */
    public function createGroupBySql()
    {   
        $aggGroupBy = "";

        switch ($this->timeDim) {
            case 'day':
            case 'dayGroup':
                $aggGroupBy = " group by DAY,location";
                break;
            case 'hour':
            case 'hourGroup':
                $aggGroupBy = " group by DAY,hour_id,location";
                break;
            case 'quarter':
                $aggGroupBy = " group by DAY,hour_id,min_id,location";
                break;
        }

        if($this->locationDim=="BSC"){
            $aggGroupBy.=",BSC";
        }
        if($this->locationDim=="cell"){
            $aggGroupBy.=",CELL_NAME";
        }

        return $aggGroupBy;
      
    }

     /**
     * 对每张表建立sql
     * @DateTime 2019-01-15
     * @param    array     $countersForQuery counters列表
     * @param    string    $selectSQL        select sql
     * @param    string    $filterSQL        filter sql
     * @param    string    $groupbySQL       groupby sql
     * @param    string     $table           sysbase table
     * @return   string                      单张表的sql
     */
    public function createSubQuerySql($countersForQuery,$selectSQL,$filterSQL,$groupbySQL,$table)
    {   
         foreach ($countersForQuery as $counter) {
            $counter     = trim($counter);
            $counterName = $counter;

            $selectSQL = $selectSQL." sum(".$counter.") as '$counterName',";
        }
        $selectSQL = rtrim($selectSQL,",");
        return "($selectSQL from dc.$table $filterSQL $groupbySQL)";
    }

    /**
     * 文件下载
     * @DateTime 2019-01-15
     * @param    array     $data  文件数据
     * @param    string    $title 文件title
     * @return   string           文件名
     */
    public function download($data,$title)
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

    /**
     * 获取模版信息
     * @DateTime 2018-12-12
     * @return   array   模版对应的信息
     */
    public function getKpis()
    {

        $elementId = Template::select("elementId")->where("id",$this->templateId)->first()->toArray();
        $kpis   = $elementId['elementId'];
        $kpisStr = ",".$kpis.",";

        $res = DB::select("select kpiName,kpiPrecision,kpiFormula,instr('$kpisStr',CONCAT(',',id,',')) as sort from kpiformula where id in ($kpis) order by sort");

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
     * 获取模版公式的counter和table
     * @DateTime 2019-01-15
     * @param    string     $queryFormula 公式id
     * @return   array                   模版counter数据信息
     */
    protected function getCheckCounters($queryFormula) {

        $counters = unserialize(Redis::get("Counters_GSM"));
       $queryResult = DB::select("select kpiName,kpiFormula,kpiPrecision,instr('$queryFormula,',CONCAT(id,',')) as sort from kpiformula where id in (".$queryFormula.") order by sort");
        $check = [];
        $counterMap = array();
        foreach ($queryResult as $row) {
            $kpi = trim($row->kpiFormula);
            $pattern       = "/[\(\)\+\*-\/]/";
            $columns = preg_split($pattern, $kpi);
            foreach ($columns as $column) {
                $column      = trim($column);
                $counterName = $column;
                if(array_key_exists(strtolower($counterName), $counters)){
                    @$table      = $counters[strtolower($counterName)];
                     if (!array_key_exists($column, $counterMap)) {
                        $counterMap[$column] = $table;
                    }
                }
               
            }
        }
        return $counterMap;
    }

    /**
     * 获得聚合类型
     *
     * @param array  $aggTypes    聚合类型集合
     * @param string $counterName 计数器名
     *
     * @return string
     */
    protected function getAggType($aggTypes, $counterName)
    {
        // print_r($aggTypes);print_r($counterName);exit;
        if (!array_key_exists($counterName, $aggTypes)) {
            return "sum";
        }

        return trim($aggTypes[$counterName]);

    }//end getAggType()

    /**
     * 公式转换
     *
     * @param string $formula 指标公式
     *
     * @return mixed|string
     */
    protected function formulaTransform($formula)
    {
        if (strpos($formula, 'AVG') === 0 || strpos($formula, 'avg') === 0) {
            // $formula = "AVG(".$formula.")";
            return $formula;
        } else if (strpos($formula, '(') == false && strpos($formula, ')') == false) {
            $formula = "SUM(".$formula.")";
            return $formula;
        } else {
            $firStr  = '';
            $finStr  = '';
            $formula = preg_replace("/\s/", "", $formula);
            $firPos  = strpos($formula, '(');
            if ($firPos != 0) {
                $firStr  = substr($formula, 0, $firPos);
                $formula = substr($formula, $firPos);
            }

            $finPos = strrpos($formula, ')');
            if (($finPos + 1) != strlen($formula)) {
                $finStr  = substr($formula, ($finPos + 1));
                $formula = substr($formula, 0, ($finPos + 1));
            }

            $arr = [0];
            $sum = 0;
            for ($i = 0; $i < strlen($formula); $i++) {
                if ($formula[$i] == '(') {
                    $sum = ($sum + 1);
                }

                if ($formula[$i] == ')') {
                    $sum = ($sum - 1);
                }

                if ($sum == 0) {
                    array_push($arr, $i);
                }
            }

            $comStr = $this->formulaAddSum($arr, $formula);

            if (strlen($firStr) > 0 && strlen($finStr) == 0) {
                $comStr = $firStr.$comStr;
            } else if (strlen($firStr) == 0 && strlen($finStr) > 0) {
                $comStr = $comStr.$finStr;
            } else if (strlen($firStr) > 0 && strlen($finStr) > 0) {
                $comStr = $firStr.$comStr.$finStr;
            }

            return $comStr;
        }//end if

    }//end formulaTransform()
        /**
     * 公式SUM
     *
     * @param array  $arr     位置列表
     * @param string $formula 指标公式
     *
     * @return bool|string
     */
    protected function formulaAddSum($arr, $formula)
    {
        if ((count($arr) % 2) != 0 && count($arr) < 2) {
            return false;
        }

        $comStr = '';
        if (count($arr) == 2) {
            $comStr = "SUM".$formula;
        } else if (count($arr) > 2) {
            for ($i = 0; $i < (count($arr) - 1); $i++) {
                if (($i % 2) == 0 && $i == 0) {
                    $comStr = $comStr."SUM".substr($formula, $arr[$i], ($arr[($i + 1)] - $arr[$i] + 1));
                } else if (($i % 2) == 0 && $i != 0 && $i != (count($arr) - 2)) {
                    $comStr = $comStr."SUM".substr($formula, ($arr[$i] + 1), ($arr[($i + 1)] - $arr[$i]));
                }

                if (($i % 2) == 1) {
                    $comStr = $comStr.$formula[$arr[($i + 1)]];
                }

                if ($i == (count($arr) - 2)) {
                    $comStr = $comStr."SUM".substr($formula, ($arr[$i] + 1), ($arr[($i + 1)] - $arr[$i] + 1));
                }
            }
        }

        return $comStr;

    }//end formulaAddSum()
}