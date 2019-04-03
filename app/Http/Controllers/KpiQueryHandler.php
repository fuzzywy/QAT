<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 2018/11/27
 * Time: 18:02
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use PDO;
use Exception;
use App\Models\Template;
use App\Models\Kpiformula;
trait KpiQueryHandler
{
    use FormulaHandler;
    //数据库类型：ENIQ
    public $dataSource;
    //数据类型：TDD/FDD/NBIOT/GSM
    public $dataType;
    //模版名Id
    public $templateId;
    //模版名
    public $template;
    //时间类型：天/day,天组/dayGroup,小时/hour,小时组/hourgroup,15分钟/quarter
    public $timeDim;
    //查询维度：city/城市,subnet/子网,subnetGroup/子网组,baseStation/基站,baseStationGroup/基站组,cell/小区,cellGroup/小区组
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
    public $minute;

    public $resultText;
    public $sql;


    public $neighborCell = false;//邻区对

    /**
     * 查询初始化赋值
     * @DateTime 2019-01-15
     * @param    Request    $request 
     * @return   
     */
    public function init(Request $request)
    {   

        $this->dataSource = "ENIQ";
        $this->dataType = $request['dataType'];
        $this->templateId = $request['template'];
        
   
        $subnets = $request['subnets'];
        foreach ($subnets as $key => $value) {
            if($value&&$value!=="allSelect"){
                $city = explode(":",$value);
                $newsubnets[$city[0]][]=$city[1];
            }
        }

        $this->cities = array_keys($newsubnets);
        $this->subnets =$newsubnets;
        $this->timeDim = $request['timeDim'];
        $this->locationDim = $request['locationDim'];
        $this->baseStation = $request['baseStation'];
        $this->cell = $request['cell'];
        $this->dateStart =substr($request['date'][0],0,10);
        $this->dateEnd =substr($request['date'][1],0,10);
        $this->hour = $request['hour'];
        $this->minute = $request['minute'];
        $this->result="";
        $this->sql = new \stdClass();


        $res = Template::select('templateName','description')
                ->where("id",$this->templateId)
                ->first()->toArray();

        $this->template = $res['templateName'];
        if($res['description']=="neighborCell"){
            $this->neighborCell = true;
        }
    }

    /**
     * 生成sql语句
     * @DateTime 2019-01-15
     * @param    string     $city       城市
     * @param    array      $subnetwork 子网
     * @return   string                 sql语句
     */
    public function createSql($city,$subnetwork)
    {   
        $subnetwork = $this->getSubnetWork($city);//获取子网

        switch ($this->timeDim) {
            case 'day':
                $this->sql->selectSql = "select AGG_TABLE0.DAY,AGG_TABLE0.location,AGG_TABLE0.cellNum,";
                $this->sql->resultText ="day,location,cellNum";
                $this->sql->orderbySql = " ORDER BY AGG_TABLE0.DAY";
                break;
            case 'dayGroup':
                $this->sql->selectSql = "select AGG_TABLE0.DAY,AGG_TABLE0.location,AGG_TABLE0.cellNum,";
                $this->sql->resultText ="dayGroup,location,cellNum";
                $this->sql->orderbySql = " ORDER BY AGG_TABLE0.DAY";
                break;
            case 'hour':
                $this->sql->selectSql = "select AGG_TABLE0.DAY,AGG_TABLE0.HOUR,AGG_TABLE0.location,AGG_TABLE0.cellNum,";
                $this->sql->resultText ="day,hour,location,cellNum";
                $this->sql->orderbySql = " ORDER BY AGG_TABLE0.DAY,AGG_TABLE0.HOUR";
                break;
            case 'hourGroup':
                $this->sql->selectSql = "select AGG_TABLE0.DAY,AGG_TABLE0.HOUR,AGG_TABLE0.location,AGG_TABLE0.cellNum,";
                $this->sql->resultText ="day,hourGroup,location,cellNum";
                $this->sql->orderbySql = " ORDER BY AGG_TABLE0.DAY,AGG_TABLE0.HOUR";
                break;
            case 'quarter':
                $this->sql->selectSql = "select AGG_TABLE0.DAY,AGG_TABLE0.HOUR,AGG_TABLE0.MINUTE,AGG_TABLE0.location,AGG_TABLE0.cellNum,";
                $this->sql->resultText ="day,hour,minute,location,cellNum";
                $this->sql->orderbySql = " ORDER BY AGG_TABLE0.DAY,AGG_TABLE0.HOUR,AGG_TABLE0.MINUTE";
                break;
        }

        switch ($this->locationDim) {
            case 'baseStation':
                $this->sql->selectSql.="AGG_TABLE0.subNet,";
                $this->sql->resultText = str_replace("cellNum", "cellNum,subNet",  $this->sql->resultText);
                break;
            case 'cell':
                $this->sql->selectSql.="AGG_TABLE0.subNet,AGG_TABLE0.site,";
                $this->sql->resultText = str_replace("cellNum", "cellNum,subNet,site",  $this->sql->resultText);
                break;
        }


        $kpis=$this->getKpis();
        $nosum_map = array();//max,min,avg
        $counterMap = $this->getCheckCounters($kpis['ids'],$nosum_map);
      
        $tables  = array_keys(array_count_values($counterMap));
        $aggselectSQL=$this->createSelectSql($city);
        $filterSQL=$this->createFilterSql($city);
        $groupbySQL=$this->createGroupBySql();

        if (count($tables) == 1) {
            $table = $tables[0];
            if (trim(substr($table, 0, (strlen($table) - 4))) == strtolower("DC_E_ERBS_EUTRANCELLRELATION") && $this->template != '切换成功率(不含邻区对)') {
                if ($this->neighborCell){
                    $this->sql->selectSql.="AGG_TABLE0.relation,AGG_TABLE0.eufdd,";
                    $aggselectSQL    = $aggselectSQL."EUtranCellRelation as relation,EUtranCell".$this->dataType." as eufdd,";
                    $groupbySQL  = $groupbySQL.",eufdd,relation";
                    $this->sql->resultText.=",EUtranCellRelation,EUtranCell".$this->dataType;
                }
            } else if (trim(substr($table, 0, (strlen($table) - 4))) == strtolower("DC_E_ERBS_GERANCELLRELATION") && $this->template != '2G邻区切换统计-不含GeranCellRelation') {
                if ($this->neighborCell) {
                    $this->sql->selectSql.="AGG_TABLE0.relation,AGG_TABLE0.eufdd,";
                    $aggselectSQL    = $aggselectSQL."GeranCellRelation as relation,EUtranCell".$this->dataType." as eufdd,";
                    $groupbySQL  = $groupbySQL.",eufdd,relation";
                     $this->sql->resultText.=",GeranCellRelation,EUtranCell".$this->dataType;
                }
            }
        }
        $this->sql->resultText .=','.$kpis['names']; 
        
         $counters = array_unique(array_keys($counterMap));

        foreach ($counters as $key => $value) {
            $this->sql->selectSql.=strtolower($value).",";
        }
        $this->sql->selectSql = trim($this->sql->selectSql,",")." from ";
        $tempTableSQL = "";
        $index        = 0;
        foreach ($tables as $table) {
            $countersForQuery = array_keys($counterMap, $table);
            $tableSQL         = $this->createSubQuerySql($countersForQuery,$aggselectSQL,$filterSQL,$groupbySQL,$table,$nosum_map);
            // print_r($tableSQL);exit;
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
                if ( $this->timeDim == "dayGroup" && $this->locationDim == 'cell' ) {
                    $tableSQL = $tableSQL." and AGG_TABLE0.location = AGG_TABLE$index.location";
                }
                // echo $locationDim;
                if ($this->locationDim == "cellGroup" || $this->locationDim == "erbsGroup") {
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

        
        $sql =    $this->sql->selectSql.$tempTableSQL.$this->sql->orderbySql;
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
        $subnetwork = $this->getSubnetWork($city);//获取子网
        $SN = $this->getSN($this->dataType,$city);
        $conname = trim(preg_replace('/[0-9]+/', '', $city));
    
        $left = "select ";
        $middle = "";
        $right = "";
        if($this->dataSource=="ENIQ"){
            if($this->timeDim=="day"){
                $left.="convert(char(10),date_id) as day,";
            }elseif($this->timeDim=="dayGroup"){
                $left.="'ALLDAY' as day,";
            }elseif($this->timeDim=="hour"){
                $left.="convert(char(10),date_id) as day,";
                $right.="hour_id as hour,";
            }elseif($this->timeDim=="hourGroup"){
                if (!$this->hour) {
                    $hour = 'AllHour';
                } else {
  
                    $hour = implode(',',$this->hour);
                }
                $left.="convert(char(10),date_id) as day,";
                $right.="'$hour' as hour,";

                // $selectSql .= "convert(char(10),date_id) as day,'$conname' as location,'$hour' as hour,";
            }elseif($this->timeDim=="quarter"){
                $left.="convert(char(10),date_id) as day,";
                $right.="hour_id as hour,min_id as minute,";
            }

            if($this->locationDim=="city"){
                $middle.="'$conname' as location,";
            }elseif($this->locationDim=="subnet"){
                $middle .="$subnetwork as location,";
            }elseif($this->locationDim=="subnetGroup"){
                $middle .="'subnetworkGroup' as location,";
            }elseif($this->locationDim=="baseStation"){
                $middle .="$subnetwork as subNet,erbs AS location,";
            }elseif($this->locationDim=="baseStationGroup"){
                $middle .="'AllErbs' AS location,";
            }elseif($this->locationDim=="cell"){

                $middle .="$subnetwork as subNet,$SN,";
                if($this->dataType=="TDD"){
                    $middle.="EutranCellTDD as location,";
                }elseif($this->dataType=="FDD"){
                    $middle.="EutranCellFDD as location,";

                }elseif($this->dataType=="NBIOT"){
                    $middle.="NbIotCell as location,";
                }
            }elseif($this->locationDim=="cellGroup"){
                $middle .="'cellGroup' AS location,";
            }

        }
        $selectSql = $left.$middle.$right;
        return $selectSql;
    }


    /**
     * 生成filter sql
     * @DateTime 2019-01-15
     * @param    string     $city 城市
     * @return   string           filter sql
     */
    public function createFilterSql($city)
    {   
        //获取单个地市的子网 array()
        $subNetwork = $this->subnets[$city];
        $substr = "";
        foreach ($subNetwork as $key => $value) {
            $substr .="'".$value."',";
        }
        $substr = trim($substr,",");//子网string

        $subnetwork = $this->getSubnetWork($city);//获取子网
        $aggWhereSql = " where date_id>='".$this->dateStart."' and date_id<='".$this->dateEnd."' and $subnetwork in ($substr)";

        if($this->timeDim=="day"){
        }elseif($this->timeDim=="dayGroup"){
        }elseif($this->timeDim=="hour"){
            if($this->hour){
                $hour = implode(",", $this->hour);
                $aggWhereSql.="and hour_id in($hour)";
            }
        }elseif($this->timeDim=="hourGroup"){
             if($this->hour){
                $hour = implode(",", $this->hour);
                $aggWhereSql.="and hour_id in($hour)";
            }
        }elseif($this->timeDim=="quarter"){
             if($this->hour){
                $hour = implode(",", $this->hour);
                $aggWhereSql.="and hour_id in($hour)";
            } 
            if($this->minute){
                $minute = implode(",", $this->minute);
                $aggWhereSql.="and min_id in($minute)";
            }
        }
        if($this->locationDim=="baseStation"||$this->locationDim=="baseStationGroup"){
            if($this->baseStation){
                $baseStation = trim($this->baseStation);
                $baseStation="'".str_replace(",", "','", $baseStation)."'";
                $aggWhereSql.="and erbs in($baseStation)";
            }
        }
          if($this->locationDim=="cell"||$this->locationDim=="cellGroup"){
            if ($this->baseStation) {
                $baseStation = "'".str_replace(",", "','", $this->baseStation)."'";
                $aggWhereSql .= " and erbs in($baseStation) ";
            }
            if($this->cell){
                $cell = trim($this->cell);
                $cell="'".str_replace(",", "','", $cell)."'";
                if($this->dataType=="TDD"){
                 $aggWhereSql.="and EutranCellTDD in($cell)";

                }elseif($this->dataType=="FDD"){
                    $aggWhereSql.="and EutranCellFDD in($cell)";

                }elseif($this->dataType=="NBIOT"){
                    $aggWhereSql.="and NbIotCell in($cell)";
                }
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

        if($this->timeDim=="day"){
            $aggGroupBy = " group by DAY,location";
        }elseif($this->timeDim=="dayGroup"){
            $aggGroupBy = " group by DAY,location";
        }elseif($this->timeDim=="hour"){
            $aggGroupBy = " group by DAY,hour_id,location";
        }elseif($this->timeDim=="hourGroup"){
            $aggGroupBy = " group by DAY,hour,location";
        }elseif($this->timeDim=="quarter"){
            $aggGroupBy = " group by DAY,hour_id,min_id,location";
        }
        if($this->locationDim=="baseStation"||$this->locationDim=="cell"){
            $aggGroupBy.=",SN";
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
     * @param    array     $nosum_map        存放特殊情况的counter
     * @return   string                      单张表的sql
     */
    public function createSubQuerySql($countersForQuery,$selectSQL,$filterSQL,$groupbySQL,$table,$nosum_map)
    {   
        $aggTypes = $this->loadAggTypes();
        $pattern_nosum = "/(max|min|avg)\((.*)\)/";
        $i=0;
        if($this->dataType=="TDD"){
            $selectSQL.="count(distinct(EutranCellTDD)) as cellNum,";
        }elseif($this->dataType=="FDD"){
            $selectSQL.="count(distinct(EutranCellFDD)) as cellNum,";

        }elseif($this->dataType=="NBIOT"){
            $selectSQL.="count(distinct(NbIotCell)) as cellNum,";

        }


        foreach ($countersForQuery as $counter) {
            $counter     = trim($counter);
            $counterName = $counter;

         if (stripos($counter, "_") !== false) {
                $elements = explode("_", $counter);
                $name     = $elements[0];
                $index    = $elements[1];
                $counter  = $this->convertInternalCounter($name, $index);
                $selectSQL .=$counter." as '$counterName',";
            } else {
                if(array_key_exists($counter, $nosum_map)){
                // echo $nosum_map[$counter];
                    $selectSQL = $selectSQL.$nosum_map[$counter]." as '$counterName',";
                }else{
                    $aggType = $this->getAggType($aggTypes, $counter);
                    $counter = "$aggType($counter)";
                    $selectSQL = $selectSQL.$counter." as '$counterName',";
                }
             
                // print_r($aggType);exit;
            }
       
        }
        $selectSQL = substr($selectSQL, 0, (strlen($selectSQL) - 1));
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
     * @param    array     &$nosum_map   存放特殊counter
     * @return   array                   模版counter数据信息
     */
    protected function getCheckCounters($queryFormula,&$nosum_map) {
        if($this->dataType=="TDD"){
            $counters = unserialize(Redis::get("Counters_TDD"));

        }elseif($this->dataType=="FDD"){
            $counters = unserialize(Redis::get("Counters_FDD"));
            
        }elseif($this->dataType=="NBIOT"){
            $counters = unserialize(Redis::get("Counters_NBIOT"));

        }
        $queryFormula = DB::select("select kpiName,kpiFormula,kpiPrecision,instr('$queryFormula,',CONCAT(id,',')) as sort from kpiformula where id in (".$queryFormula.") order by sort");

        $counterMap = array();
        foreach ($queryFormula as $row) {
            $kpi = $row->kpiFormula;
            $pattern       = "/[\(\)\+\*-\/]/";
            $columns       = preg_split($pattern, $kpi);
            $matches       = array();
            $pattern_nosum = "/(max|min|avg)\((.*)\)/";
            foreach ($columns as $column) {
                $column      = trim($column);
                $counterName = $column;
                if (stripos($counterName, "pm") === false) {
                    continue;
                }

                if (stripos($counterName, "_") !== false) {
                    $elements    = explode("_", $counterName);
                    $counterName = $elements[0];
                }
                if (array_key_exists(strtolower($counterName), $counters)) {
                    $table = $counters[strtolower($counterName)];
                } else {
                    continue;
                }

                if($this->dataType=="NBIOT"){
                    $table = trim($table)."_RAW";
                }else{
                    if($this->timeDim=="hour"||$this->timeDim=="quarter"){
                        $table=trim($table)."_RAW";
                    }else{
                        $table   = ($this->timeDim == "day") ? trim($table)."_DAY" : trim($table)."_RAW";
                    }
                }

                if (preg_match($pattern_nosum, $kpi, $matches)) {
                    $counterMap[strtolower($matches[2])] = strtolower($table);
                    
                    $nosum_map[strtolower($matches[2])]=strtolower($kpi);
                    break;
                }

                if (!array_key_exists($column, $counterMap)) {
                    $counterMap[strtolower($column)] = strtolower($table);
                }
            }//end foreach
        }
        
        return $counterMap;
    }
        /**
     * 转换内部计数器
     *
     * @param string $counterName 计数器名
     * @param string $index       向量值
     *
     * @return mixed
     */
    protected function convertInternalCounter_minmaxavg($minmaxavg, $counterName, $index)
    {
        $SQL = $minmaxavg."(case DCVECTOR_INDEX when $index then $counterName else 0 end)";
        return str_replace("\n", "", $SQL);

    }//end convertInternalCounter()


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
     * 处理有下标的counter
     * @DateTime 2019-01-15
     * @param    string     $counterName counter名
     * @param    int    $index       数值
     * @return   string               sql
     */
    protected function convertInternalCounter($counterName, $index)
    {
        $SQL = "sum(case DCVECTOR_INDEX when $index then $counterName else 0 end)";
        return str_replace("\n", "", $SQL);

    }//end convertInternalCounter()

    /**
     * 适配子网信息
     * @DateTime 2019-01-15
     * @param    string     $oss oss名称
     * @return   string          子网适配信息
     */
    public function getSubnetWork($oss) {
        $SN = "";
        switch ($oss) {
            case 'wuxiENM':
                $SN = "substring(substring(SN, 0, charindex(',',SN)-1), 12)";
                break;
            case "wuxi1":
                $SN = "substring(SN, 12, charindex(',', SN)-12)";
                break;
          case "wuxi2":
            $SN = "substring(SN, 12, charindex(',', SN)-12)";
            break;
          case "wuxi":
            $SN = "substring(SN, 12, charindex(',', SN)-12)";
            break;
            case "suzhou3":
                $SN = "substring(SN, 12, charindex(',', SN)-12)";
                break;
            case "zhenjiang":
                $SN = "substring(substring(SN, 0, charindex(',',SN)-1), 12)";
            break;
            case "zhenjiang1":
                $SN = "substring(substring(SN, charindex(',', SN)+12), 0, charindex(',', substring(SN, charindex(',', SN)+12))-1)";
                break;
            case "changzhou":
              $SN = "substring(SN, charindex('=', SN)+1, charindex(',', SN)-charindex('=', SN)-1)";
              break;
            case "suzhou":
              $SN = "substring(SN, charindex('=', SN)+1, charindex(',', SN)-charindex('=', SN)-1)";
              break;
            case "nantong":
              $SN = "substring(SN, charindex('=', SN)+1, charindex(',', SN)-charindex('=', SN)-1)";
              break;
            default:
                $SN = "substring(SN,charindex('=',substring(SN,32,25))+32,charindex(',',substring(SN,32,25))-charindex('=',substring(SN,32,25))-1)";
                break;
        }
        return $SN;
    }
    /**
     * 获取site信息
     * @DateTime 2019-01-15
     * @param    string    $format 制式
     * @param    string     $oss    oss名
     * @return   string             site信息
     */
    public function getSN($format, $oss) {
        $SN = "";
        switch ($format) {
            case 'NBIOT':
                switch ($oss) {
                    case 'wuxiENM':
                        $SN = "SN  as site";
                        break;
                    case "suzhou3":
                        $SN = "SN  as site";
                        break;
            case "changzhou":
              $SN = "substring(substring(substring(SN, charindex (',', SN)+1), charindex(',', substring(SN, charindex (',', SN)+1))+1, char_length(substring(SN, charindex (',', SN)+1))), charindex('=', substring(substring(SN, charindex (',', SN)+1), charindex(',', substring(SN, charindex (',', SN)+1))+1, char_length(substring(SN, charindex (',', SN)+1))))+1) as site";
              break;
            case "nantong":
              $SN = "substring(substring(substring(SN, charindex (',', SN)+1), charindex(',', substring(SN, charindex (',', SN)+1))+1, char_length(substring(SN, charindex (',', SN)+1))), charindex('=', substring(substring(SN, charindex (',', SN)+1), charindex(',', substring(SN, charindex (',', SN)+1))+1, char_length(substring(SN, charindex (',', SN)+1))))+1) as site";
              break;
                    default:
                        $SN = "substring(substring(SN,charindex (',', substring(SN, 32, 25)) + 32),11,25) as site";
                        break;
                }
                break;
            default:
                switch ($oss) {
                    case 'wuxiENM':
                        $SN = "SN  as site";
                        break;
                    case "zhenjiang1":
                        $SN = "substring(substring(substring(SN, charindex (',', SN)+1), charindex(',', substring(SN, charindex (',', SN)+1))+1, char_length(substring(SN, charindex (',', SN)+1))), charindex('=', substring(substring(SN, charindex (',', SN)+1), charindex(',', substring(SN, charindex (',', SN)+1))+1, char_length(substring(SN, charindex (',', SN)+1))))+1) as site";
                        break;
                    case "zhenjiang":
                        $SN = "substring(substring(substring(SN, charindex (',', SN)+1), charindex(',', substring(SN, charindex (',', SN)+1))+1, char_length(substring(SN, charindex (',', SN)+1))), charindex('=', substring(substring(SN, charindex (',', SN)+1), charindex(',', substring(SN, charindex (',', SN)+1))+1, char_length(substring(SN, charindex (',', SN)+1))))+1) as site";
                        break;
                    case "suzhou3":
                        $SN = "substring(substring(substring(SN, charindex (',', SN)+1), charindex(',', substring(SN, charindex (',', SN)+1))+1, char_length(substring(SN, charindex (',', SN)+1))), charindex('=', substring(substring(SN, charindex (',', SN)+1), charindex(',', substring(SN, charindex (',', SN)+1))+1, char_length(substring(SN, charindex (',', SN)+1))))+1) as site";
                        break;
                    case "wuxi1":
                        $SN = "substring(substring(substring(SN, charindex (',', SN)+1), charindex(',', substring(SN, charindex (',', SN)+1))+1, char_length(substring(SN, charindex (',', SN)+1))), charindex('=', substring(substring(SN, charindex (',', SN)+1), charindex(',', substring(SN, charindex (',', SN)+1))+1, char_length(substring(SN, charindex (',', SN)+1))))+1) as site";
                        break;
                    case "wuxi":
                        $SN = "substring(substring(substring(SN, charindex (',', SN)+1), charindex(',', substring(SN, charindex (',', SN)+1))+1, char_length(substring(SN, charindex (',', SN)+1))), charindex('=', substring(substring(SN, charindex (',', SN)+1), charindex(',', substring(SN, charindex (',', SN)+1))+1, char_length(substring(SN, charindex (',', SN)+1))))+1) as site";
                        break;
                case "wuxi2":
                    $SN = "substring(substring(substring(SN, charindex (',', SN)+1), charindex(',', substring(SN, charindex (',', SN)+1))+1, char_length(substring(SN, charindex (',', SN)+1))), charindex('=', substring(substring(SN, charindex (',', SN)+1), charindex(',', substring(SN, charindex (',', SN)+1))+1, char_length(substring(SN, charindex (',', SN)+1))))+1) as site";
                    break;
                    case "changzhou":
                        $SN = "substring(substring(substring(SN, charindex (',', SN)+1), charindex(',', substring(SN, charindex (',', SN)+1))+1, char_length(substring(SN, charindex (',', SN)+1))), charindex('=', substring(substring(SN, charindex (',', SN)+1), charindex(',', substring(SN, charindex (',', SN)+1))+1, char_length(substring(SN, charindex (',', SN)+1))))+1) as site";
                        break;
                case "suzhou":
                    $SN = "substring(substring(substring(SN, charindex (',', SN)+1), charindex(',', substring(SN, charindex (',', SN)+1))+1, char_length(substring(SN, charindex (',', SN)+1))), charindex('=', substring(substring(SN, charindex (',', SN)+1), charindex(',', substring(SN, charindex (',', SN)+1))+1, char_length(substring(SN, charindex (',', SN)+1))))+1) as site";
                    break;
                case "nantong":
                    $SN = "substring(substring(substring(SN, charindex (',', SN)+1), charindex(',', substring(SN, charindex (',', SN)+1))+1, char_length(substring(SN, charindex (',', SN)+1))), charindex('=', substring(substring(SN, charindex (',', SN)+1), charindex(',', substring(SN, charindex (',', SN)+1))+1, char_length(substring(SN, charindex (',', SN)+1))))+1) as site";
                    break;
                    default:
                        $SN = "substring(substring(SN,charindex (',', substring(SN, 32, 25)) + 32),11,25) as site";
                        break;
                }
                break;
        }
        return $SN;
    }
        /**
     * 获得AVG聚合类型的计数器集合
     *
     * @return array
     */
    public function loadAggTypes()
    {
        $aggTypeDefs = file("txt/AggTypeDefine.txt");
        $aggTypes    = array();

        foreach ($aggTypeDefs as $aggTypeDef) {
            $aggType = explode("=", $aggTypeDef);
            $aggTypes[$aggType[0]] = $aggType[1];
        }

        return $aggTypes;

    }//end loadAggTypes()

    /**
     * 获得查询结果
     * @DateTime 2019-01-15
     * @param    array     $result 单个oss数据
     * @return   array             查询结果
     */
    public function getResult($result){
        $kpis=$this->getKpis();
        $nosum_map = array();
        $counterMap = $this->getCheckCounters($kpis['ids'],$nosum_map);
        $counters = array_values(array_unique(array_keys($counterMap)));//获取所有的pm键值
        $data = array();
        foreach ($result as $key => $value) {

                if(count($value)>1){
                    $newArray = [];
                    if($this->locationDim=="city"){
                        $newArray = $this->mergeOssData($value,$counters,$nosum_map);
                    }elseif($this->locationDim=="subnetGroup"){
                        $newArray = $this->mergeOssData($value,$counters,$nosum_map);
                    }elseif($this->locationDim=="baseStationGroup"){
                        $newArray = $this->mergeOssData($value,$counters,$nosum_map);
                    }else{
                        foreach ($value as $k => $v) {
                            $newArray = array_merge($newArray,$v);
                        }
                    }
                }else{
                    $newArray = $value[0];
                }

                 $result = $this->getResultArray(array($newArray),$kpis);
                 $data = array_merge($data,$result);
                    
        }
        
        return json_decode(json_encode($data,JSON_PARTIAL_OUTPUT_ON_ERROR),TRUE);

    }
    /**
     * 合并多个OSS的情况数据
     * @DateTime 2018-12-26
     * @return   array();
     */
    public function mergeOssData($data,$counters,$nosum_map){
        $len = count($data);
        array_push($counters, "cellNum");

        for($i=0;$i<$len;$i++){
            foreach ($data[$i] as $value) {
                $key = $value['DAY'].$value['location'];
                if(array_key_exists("HOUR", $value)){
                    $key.=$value['HOUR'];
                }
                if(array_key_exists("MINUTE", $value)){
                    $key.=$value['MINUTE'];
                }

                $newData[$key][]=$value;

            }
        }

        foreach ($newData as $key => $value) {
            $i =count($value);
            for($i=1;$i<$len;$i++){

                 foreach ($counters as $k => $v) {
                    if(array_key_exists($v, $nosum_map)){
                        $newData[$key][0][$v] =max($newData[$key][$i][$v],$newData[$key][0][$v]);

                    }else{
                        $newData[$key][0][$v] +=$newData[$key][$i][$v];
                        
                    }
                    # code...
                }
            }
            $returnDate[] = $newData[$key][0];
           
        }

        return $returnDate;

    }
    /**
     * 获得公式结果
     * @DateTime 2019-01-15
     * @param    array     $value 单条数据
     * @param    array     $kpis  公式
     * @return   array            求到的结果
     */
    public function getResultArray($value,$kpis){
                if(!$value){
                    return array();
                }
                $i=0;
                foreach ($value as $k => $v) {
                    foreach ($v as  $arr) {
                          //时间类型：天/day,天组/dayGroup,小时/hour,小时组/hourgroup,15分钟/quarter
                        switch ($this->timeDim) {
                            case 'day':
                                $newFormula[$i]['day']=$arr['DAY'];
                                $newFormula[$i]['location']=$arr['location'];
                                $newFormula[$i]['cellNum'] = $arr['cellNum'];
                                break;
                            case 'dayGroup':
                                $newFormula[$i]['day']=$arr['DAY'];
                                $newFormula[$i]['location']=$arr['location'];
                                $newFormula[$i]['cellNum'] = $arr['cellNum'];
                                break;
                            case 'hour':
                                $newFormula[$i]['day']=$arr['DAY'];
                                $newFormula[$i]['HOUR']=$arr['HOUR'];
                                $newFormula[$i]['location']=$arr['location'];
                                $newFormula[$i]['cellNum'] = $arr['cellNum'];
                                break;
                            case 'hourGroup':
                                $newFormula[$i]['day']=$arr['DAY'];
                                $newFormula[$i]['HOUR']=$arr['HOUR'];
                                $newFormula[$i]['location']=$arr['location'];
                                $newFormula[$i]['cellNum'] = $arr['cellNum'];
                                break;
                            case 'quarter':
                                $newFormula[$i]['day']=$arr['DAY'];
                                $newFormula[$i]['HOUR']=$arr['HOUR'];
                                $newFormula[$i]['MINUTE']=$arr['MINUTE'];
                                $newFormula[$i]['location']=$arr['location'];
                                $newFormula[$i]['cellNum'] = $arr['cellNum'];
                                break;

                        }

                         //查询维度：city/城市,subnet/子网,subnetGroup/子网组,baseStation/基站,baseStationGroup/基站组,cell/小区,cellGroup/小区组

                       if($this->locationDim=="baseStation"){
                            $newFormula[$i]['subNet'] = $arr['subNet'];
                            
                        }elseif($this->locationDim=="cell"){
                            $newFormula[$i]['subNet'] = $arr['subNet'];
                            $newFormula[$i]['site'] = $arr['site'];
                        }
                        if(array_key_exists('relation', $arr)){
                            $newFormula[$i]['relation'] = $arr['relation'];

                        }
                         if(array_key_exists('eufdd', $arr)){
                            $newFormula[$i]['eufdd'] = $arr['eufdd'];
                                
                        }
                        $j=0;
                      foreach ($kpis['kpiformula'] as $ks => $vs) {
                        $newFormula[$i]['kpi'.$j]=$this->getCalculationRes($vs['kpiFormula'],$vs['kpiPrecision'],$arr);

                            $j++;
                        }
                        $i++;
                    }
                }
                // print_r($newFormula);exit;
            return $newFormula;
    }

    /**
     * 根据公式获得运算结果
     * @DateTime 2019-01-15
     * @param    string     $kpiformula   公式
     * @param    int        $kpiPrecision 精度
     * @param     array       $arr        数据
     * @return   int                       结果值
     */
    protected function getCalculationRes($kpiformula, $kpiPrecision, $arr) {
        $kpiformula = strtolower($kpiformula);
        $pattern       = "/[\(\)\+\*-\/]/";
        $columns       = preg_split($pattern, $kpiformula);
        // print_r($columns);
        foreach ($columns as $column) {
            $column      = strtolower(trim($column));
            if (stripos($column, "pm") !== false) {
                preg_match('/^(max|min|avg)\((.*)\)/', $kpiformula, $match);
                if (count($match) == 3) {
                    $columns = $match[1]."\(".$match[2]."\)";
                    // $kpiformula = str_replace($columns, $arr[$column], $kpiformula);
                    $columnstr = "/$columns/";
                    $kpiformula = preg_replace($columnstr, $arr[$column], $kpiformula, 1);
                } else {
                    // $kpiformula = str_replace($column, $arr[$column], $kpiformula);
                    $myColumn = "/$column/";
                    $kpiformula = preg_replace($myColumn, $arr[$column], $kpiformula, 1);
                }
            }
        }//end foreach
        //eval不支持power
        $kpiformula = str_replace('power', 'pow', $kpiformula);
        // print_r($kpiformula);
        try {
            return round(eval("return $kpiformula;"), $kpiPrecision);
            
        } catch (\Exception  $e) {
            return round('0', $kpiPrecision);
            
        }
    }
}