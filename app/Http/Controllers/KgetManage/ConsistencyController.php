<?php
/** 
* 参数检查后台代码
* 
* @author zhujiaojiao
* @version 0.0   
*/ 
namespace App\Http\Controllers\KgetManage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Config;
use PDO;
use App\Http\Controllers\Common\DataBaseConnection;
use App\Models\Kget\ConsistencyCheckTableInfo;
use App\Models\COLUMNS;
use Excel;
use App\Http\Controllers\Utils\FileUtil;

/** 
* 参数检查控制器 
* 
* @author zhujiaojiao      
* @version 0.0  
*/ 
class ConsistencyController extends Controller 
{   
    private $dbc;
    private $fileUtil;

    public function __construct() {

        $this->dbc      = new DataBaseConnection();
        $this->fileUtil = new FileUtil();

    }
    /**  
    * 获取参数检查项
    * 
    * @access public 
    * @return Json 参数检查项
    */
    public function getItem() {

        $itemTreeData = ConsistencyCheckTableInfo::select('item as label')->where('status','=','Y')->groupBy('item')->get();
        return $itemTreeData;

    }
    /**  
    * 获取每个省份对应的运营商列表
    * 
    * @access public 
    * @return Json 运行商列表
    */
    public function getOperator() {

        $province     = Input::get('province');
        $operatorData = DB::table('operator')->select('name as label','name as value')->where('province','=',$province)->get();
        return $operatorData;

    }
    /**  
    * 获取省份列表
    * 
    * @access public 
    * @return Json 省份列表
    */
    public function getProvince() {

        $provinceData = DB::table('province')->select('name as label','name as value')->get();
        return $provinceData;

    }
    /**  
    * 获取城市列表
    * 
    * @access public 
    * @return Json 城市列表
    */
    public function getCity() {
        $province = Input::get('province');
        $cityData = DB::table('cities')->select('city as label','city as value')->where('province','=',$province)->get()->toArray();
        if ($cityData) {
            array_unshift($cityData, array('label' => '全选', 'value' => 'allSelect'));
        }
        return json_encode($cityData);

    }

    /**  
    * 获取一致性检查分布
    * 
    * @access public 
    * @return Json 分布数据
    */
    public function getParam() {
        $item          = Input::get('item');
        $province      = Input::get('province');
        $operator      = Input::get('operator');
        $cities        = Input::get('cities');
        $date          = Input::get('date');
        $baseStation   = Input::get('baseStation');

        $data          = [];

        $yAxis         = $cities;
        $data['yAxis'] = $yAxis;

        $xAxis         = [];
        $subItems      = ConsistencyCheckTableInfo::select('sub_item','newTableName')->where('item','=',$item)->where('status','=','Y')->get()->toArray();
        $series        = [];
        for ($i=0,$size_x = count($subItems); $i<$size_x; $i++) {
            array_push($xAxis, $subItems[$i]['sub_item']);
            $table = $subItems[$i]['newTableName'];
            for ($j=0,$size_y=count($yAxis); $j<$size_y; $j++) {
                $city          = $this->dbc->getConnName($yAxis[$j]);
                $subNetworkArr = $this->dbc->getSubNetsArr('ALL',$yAxis[$j]);
                $count         = 0;
                if ($this->dbc->tableIfExists('consistencyCheck', $table)) {
                    $countQuery = DB::connection('consistencyCheck')->table($table)->where('dateId',$date)->where('operator',$operator);
                    if ($baseStation) {
                        $baseStationGroup = explode(',', $baseStation);
                        $countQuery = $countQuery->whereIn('meContext',$baseStationGroup);
                    } 
                    if ($item == '邻区相关检查' ) {
                        $count = $countQuery->where('city',$city)->count(DB::raw("distinct(EUtranCell)"));
                    } elseif ($table == 'MeasuringFrequencyTooMuch') {
                        $count = $countQuery->whereIn('subNetwork',$subNetworkArr)->count(DB::raw("distinct(EUtranCell)"));
                    } elseif ($table == 'TempEUtranCellFreqRelation') {
                        $count = $countQuery->whereIn('subNetwork',$subNetworkArr)->whereNull('remark')->count();
                    } elseif ($table == 'TempCVTooMany') {
                        $count = $countQuery->whereIn('subNetwork',$subNetworkArr)->count(DB::raw("distinct(meContext)"));
                    } else {
                        $count = $countQuery->whereIn('subNetwork',$subNetworkArr)->count();
                    }
                }
                array_push($series, [$i,$j,floatval($count)]);
            }
        }
        $data['xAxis']  = $xAxis;
        $data['series'] = $series;
        return json_encode($data);
    }
    /**  
    * 导出一致性检查分布详情
    * 
    * @access public 
    * @return Json 分布数据
    */
    public function exportParam() {
        $dataSource  = Input::get('dataSource');
        $dataType    = Input::get('dataType');
        $item        = Input::get('item');
        $province    = Input::get('province');
        $operator    = Input::get('operator');
        $cities      = Input::get('cities');
        $date        = Input::get('date');
        $baseStation = Input::get('baseStation');

        $subItems    = ConsistencyCheckTableInfo::select('sub_item','newTableName')->where('item','=',$item)->where('status','=','Y')->get()->toArray();

        $subNetAll   = $this->dbc->getSubnetwork($cities,$dataSource,$dataType);
        $subNetGroup = [];

        $cityEns     = [];
        foreach ($subNetAll as $city => $subNets) {
            $cityEns[]   = $city;
            $subNetGroup = array_merge($subNetGroup, $subNets);
        }
        $baseStationGroup = [];
        if ($baseStation) {
            $baseStationGroup = explode(',', $baseStation);
        }

        $fileDir         = "files/$item/".date('YmdHis');
        exec('mkdir -p '.$fileDir);
        exec('chmod -R 777 '.$fileDir);
        foreach ($subItems as $subItem) {
            $bindParams  = [];

            $table       = $subItem['newTableName'];
            $subItem     = $subItem['sub_item'];
            $params      = COLUMNS::selectRaw('concat(\'"\',GROUP_CONCAT(COLUMN_NAME SEPARATOR \'","\'),\'"\') as params')->where('TABLE_SCHEMA','=','consistencyCheck')->where('TABLE_NAME','=',$table)->first()->toArray();
            $title       = 'select '.$params['params'].' union all ';

            $query = DB::connection('consistencyCheck')->table($table)->where('dateId',$date)->where('operator',$operator);
            $bindParams[] = $date;
            $bindParams[] = $operator;
            
            if ($item == '邻区相关检查' ) {
                $query = $query->whereIn('city',$cityEns);
                $bindParams = array_merge($bindParams, $cityEns);
            } elseif ($table == 'TempEUtranCellFreqRelation') {
                $query      = $query->whereIn('subNetwork',$subNetGroup)->whereNull('remark')->count();
                $bindParams = array_merge($bindParams, $subNetGroup);
            } else {
                $query      = $query->whereIn('subNetwork',$subNetGroup);
                $bindParams = array_merge($bindParams, $subNetGroup);
            }
            if ($baseStationGroup) {
                $query      = $query->whereIn('meContext',$baseStationGroup);
                $bindParams = array_merge($bindParams, $baseStationGroup);
            }
            $db             = DB::connection('consistencyCheck')->getPdo();
            $sql      = $query->toSql();
            $filePath = '../QAT/';
            $fileName = "$fileDir/". $subItem ."_". $date.".csv";
            $sql      = $title.$sql." INTO OUTFILE '".$filePath.$fileName."'
                            FIELDS TERMINATED BY ',' 
                            OPTIONALLY ENCLOSED BY '\"' 
                            LINES TERMINATED BY '\\n'";
            $stmt     = $db->prepare($sql);
            $stmt->execute($bindParams);
        }
        return $this->fileUtil->getZipFile($fileDir);
    }
    /**  
    * 获取一致性检查分布详情
    * 
    * @access public 
    * @return Json 分布数据
    */
    public function getParamDetail() {
        $result        = [];

        $currentPage   = Input::get('currentPage') == '' ? 1 : Input::get('currentPage');
        $pageSize      = Input::get('pageSize') == '' ? 5 : Input::get('pageSize');
        $offset        = ($currentPage - 1) * $pageSize;

        $operator      = Input::get('operator');
        $date          = Input::get('date');
        $baseStation   = Input::get('baseStation');
        $item          = Input::get('item');
        $subItem       = Input::get('subItem');
        $city          = Input::get('city');

        $table         = ConsistencyCheckTableInfo::select('newTableName')->where('item','=',$item)->where('sub_item',$subItem)->where('status','=','Y')->first()->toArray()['newTableName'];

        $subNetworkArr = $this->dbc->getSubNetsArr('ALL',$city);
        $city          = $this->dbc->getConnName($city);

        $query         = DB::connection('consistencyCheck')->table($table)->where('operator',$operator)->where('dateId',$date);
        if ($baseStation) {
            $baseStationGroup = explode(',', $baseStation);
            $query            = $query->whereIn('meContext',$baseStationGroup);
        } 
        if ($item == '邻区相关检查') {
            $query = $query->where('city',$city);
            if ($subItem == '单项邻区') {
                $query = $query->whereRaw('EUtranCell not in (select EUtranCellTDD from UnidirectionalNeighborCell_Template');
            }
        } else {
            $query = $query->whereIn('subNetwork',$subNetworkArr);
        }
        $result["total"] = $query->count();
        $data            = $query->offset($offset)->limit($pageSize)->get()->toArray();
        $result['data']  =  $data;
        return $result;
        
    }
    /**  
    * 导出一致性检查分布详情
    * 
    * @access public 
    * @return Json 分布数据
    */
    public function exportParamDetail() {
        $result        = [];

        $operator      = Input::get('operator');
        $date          = Input::get('date');
        $baseStation   = Input::get('baseStation');
        $item          = Input::get('item');
        $subItem       = Input::get('subItem');
        $city          = Input::get('city');

        $bindParams    = [];

        $table         = ConsistencyCheckTableInfo::select('newTableName')->where('item','=',$item)->where('sub_item',$subItem)->where('status','=','Y')->first()->toArray()['newTableName'];
        

        $params        = COLUMNS::selectRaw('concat(\'"\',GROUP_CONCAT(COLUMN_NAME SEPARATOR \'","\'),\'"\') as params')->where('TABLE_SCHEMA','=','consistencyCheck')->where('TABLE_NAME','=',$table)->first()->toArray();
        $title         = 'select '.$params['params'].' union all ';

        $subNetworkArr = $this->dbc->getSubNetsArr('TDD',$city);
        $city          = $this->dbc->getConnName($city);

        $query         = DB::connection('consistencyCheck')->table($table)->where('operator',$operator)->where('dateId',$date);
        $bindParams[]  = $operator;
        $bindParams[]  = $date;
        if ($baseStation) {
            $baseStationGroup = explode(',', $baseStation);
            $query            = $query->whereIn('meContext',$baseStationGroup);
            $bindParams       = array_merge($bindParams, $baseStationGroup);
        } 
        if ($item == '邻区相关检查') {

            $query        = $query->where('city',$city);
            $bindParams[] = $city;
            if ($subItem == '单向邻区') {
                $query = $query->whereRaw('EUtranCell not in (select EUtranCellTDD from UnidirectionalNeighborCell_Template)');
            }
            
        } else {
            $query      = $query->whereIn('subNetwork',$subNetworkArr);
            $bindParams = array_merge($bindParams, $subNetworkArr);
        }

        $db       = DB::connection('consistencyCheck')->getPdo();
        $sql      = $query->toSql();
        $filePath = '../QAT/';
        $fileName = "files/". $subItem . "_" .$city."_". $date. "_".date('YmdHis').".csv";
        $sql      = $title.$sql." INTO OUTFILE '".$filePath.$fileName."'
                        FIELDS TERMINATED BY ',' 
                        OPTIONALLY ENCLOSED BY '\"' 
                        LINES TERMINATED BY '\\n'";
        $stmt     = $db->prepare($sql);

        if ($stmt->execute($bindParams)) {
            return $this->fileUtil->getZipFile($fileName);
        } else {
            return "noData";
        }
        
    }
    /**  
    * 上传白名单
    * 
    * @access public 
    * @return Json 上传文件返回结果
    */
    public function uploadWhiteList(Request $request) {
        $subItem  = Input::get('subItem');
        $file     = $request->file('file');

        $realPath = $file->getRealPath();
        $fh       =fopen($realPath,"r");//这里我们只是读取数据，所以采用只读打开文件流
        $title    = fgetcsv($fh);

        $data     = [];
        while (!feof($fh))
        {
            $line = fgetcsv($fh);
            if ($line) {
                $data[] = array_combine($title, $line);
            }
        }
        fclose($fh);
        $table = '';
        switch ($subItem) {
            case '单向邻区':
                $table = "UnidirectionalNeighborCell_Template";
                break;
            default:
                break;
        }
        $res = [];
        try {
            $count       = DB::connection('consistencyCheck')->table($table)->delete();
            $result      = DB::connection('consistencyCheck')->table($table)->insert($data);
            $res['code'] = 20000;
        } catch(\Illuminate\Database\QueryException $ex) {
            $res['msg']  = '导入白名单失败，请联系开发人员';
        }
        return $res;
    }
    /**  
    * 导出白名单
    * 
    * @access public 
    * @return Json 返回结果
    */
    public function exportWhiteList() {
        $subItem = Input::get('subItem');
        $table   = '';
        switch ($subItem) {
            case '单向邻区':
                $table = "UnidirectionalNeighborCell_Template";
                break;
            default:
                break;
        }
        $count    = DB::connection('consistencyCheck')->table($table)->count();
        $fileName = "common/template/$table.csv";
        if ($count) {
            $params   = COLUMNS::selectRaw('concat(\'"\',GROUP_CONCAT(COLUMN_NAME SEPARATOR \'","\'),\'"\') as params')->where('TABLE_SCHEMA','=','consistencyCheck')->where('TABLE_NAME','=',$table)->first()->toArray();
            $title    = 'select '.$params['params'].' union all ';

            $db       = DB::connection('consistencyCheck')->getPdo();
            $sql      =  DB::connection('consistencyCheck')->table($table)->toSql();
            $filePath = '../QAT/';
            $fileName = "files/UnidirectionalNeighborCell_Template_".date('YmdHis').".csv";
            $sql      = $title.$sql." INTO OUTFILE '".$filePath.$fileName."'
                            FIELDS TERMINATED BY ',' 
                            OPTIONALLY ENCLOSED BY '\"' 
                            LINES TERMINATED BY '\\n'";
            $stmt     = $db->prepare($sql);

            if ($stmt->execute()) {
                return $fileName;
            }
        }
        return $fileName;
    }
}
