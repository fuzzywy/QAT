<?php
/** 
* KGET查询后台代码
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
use App\Http\Controllers\Utils\KgetUtil;
use App\Models\Kget\TABLES;
use App\Models\Kget\COLUMNS;
use App\Models\Kget\SCHEMATA;
use App\Models\Kget\MoTreeInfo;
use App\Models\Kget\KgetCrontabTask;


/** 
* 模板管理控制器 
* 
* @author zhujiaojiao      
* @version 0.0  
*/ 
class KgetController extends Controller 
{   
	private $dbc;
    private $kgetUtil;
    private $dbName;

    public function __construct() {
    	$this->dbc      = new DataBaseConnection();
        $this->kgetUtil = new KgetUtil();
        $table          = TABLES::select('TABLE_SCHEMA')->where('TABLE_NAME','=','moTreeInfo')->orderBy('TABLE_SCHEMA','desc')->first();
        $this->dbName   = $table['TABLE_SCHEMA'];
    }
    /**  
    * 获取KgetMo层级列表 
    * 
    * @access public 
    * @return Json KgetMo层级列表
    */
    public function getQatKgetMo() {

        $moTreeData = [];
        Config::set('database.connections.kget.database', $this->dbName);
        $rows       = MoTreeInfo::groupBy('moName')->get()->toArray();
        $moTreeData = $this->kgetUtil->constructMoTree('parent', $rows);
        return json_encode($moTreeData);

    }
    /**  
    * 获取过滤之后的KgetMo列表 moTreeInfo
    * 
    * @access public 
    * @return Json 根据输入参数过滤之后的KgetMo列表
    */
    public function getMoByParamFilter() {

        $filterText = Input::get('filterText');
        $moTreeData = [];
        Config::set('database.connections.kget.database', $this->dbName);
        $dataFilterByParam = COLUMNS::select('TABLE_NAME as label')->where('TABLE_SCHEMA','=',$this->dbName)->where('COLUMN_NAME','like','%'.$filterText.'%')->groupBy('TABLE_NAME');
        $moTreeData        = TABLES::select('TABLE_NAME as label')->where('TABLE_SCHEMA','=',$this->dbName)->where('TABLE_NAME','like','%'.$filterText.'%')->union($dataFilterByParam)->get()->toArray();
        return json_encode($moTreeData);
    }
    /**  
    * 获取过滤之后的KgetMo列表 kgetTasks
    * 
    * @access public 
    * @return Json 根据输入参数过滤之后的KgetMo列表
    */
    public function getKgetTask() {

    	$kgetTasks     = TABLES::select('TABLE_SCHEMA as value','TABLE_SCHEMA as label')->where('TABLE_NAME','=','moTreeInfo')->orderBy('TABLE_SCHEMA', 'desc')->get()->toArray();
        return json_encode($kgetTasks);
    }
    /**  
    * 获取mo对应的参数列表 kgetParams
    * 
    * @access public 
    * @return Json 参数列表
    */
    public function getKgetParam() {

        $kgetTask     = Input::get('kgetTask') == '' ? $this->dbName : Input::get('kgetTask');
        $moName       = Input::get('moName');
        $params       = COLUMNS::select('COLUMN_NAME')->where('TABLE_SCHEMA','=',$kgetTask)->where('TABLE_NAME','=',$moName)->get()->toArray();

        $kgetParams   = array();
        $kgetParams[] = array("value"=>"allSelect","label"=>"全选");
        foreach ($params as $param) {
            $kgetParams[] = array("value"=>$param['COLUMN_NAME'],"label"=>$param['COLUMN_NAME']);
        }
        return json_encode($kgetParams);

    }
    /**  
    * 获取kget数据 kgetData
    * 
    * @access public 
    * @return Json 参数列表
    */
    public function getKgetData() {

        $currentPage    = Input::get('currentPage') == '' ? 1 : Input::get('currentPage');
        $pageSize       = Input::get('pageSize') == '' ? 5 : Input::get('pageSize');
        $offset         = ($currentPage - 1) * $pageSize;

        $kgetTaskValue  = Input::get('kgetTaskValue');
        $moName         = Input::get('moName');
        $city           = Input::get('city');
        $subNet         = Input::get('subNet');
        $baseStation    = Input::get('baseStation');
        $kgetParamValue = Input::get('kgetParamValue') ? implode(",", Input::get('kgetParamValue')) : '';

        $dataSource     = Input::get('dataSource');
        $dataType       = Input::get('dataType');
        
        Config::set('database.connections.kget.database', $kgetTaskValue);

        $result         = array();
        $moNameQuery    = DB::connection('kget')->table($moName);

        if ($kgetParamValue) {
        	$moNameQuery = $moNameQuery->selectRaw($kgetParamValue);
        }

        if ($city) {
        	$subNetAll   = $this->dbc->getSubnetwork($city,$dataSource,$dataType);
        	$subNetGroup = [];

        	foreach ($subNetAll as $city => $subNets) {
        		$subNetGroup = array_merge($subNetGroup, $subNets);
        	}
        	$moNameQuery = $moNameQuery->whereIn('subNetwork',$subNetGroup);
        }

        if ($subNet) {
        	$subNetGroup = [];

        	foreach ($subNet as $value) {
        		$subNetGroup[] = explode(':', $value)[1];
        	}
        	$moNameQuery = $moNameQuery->whereIn('subNetwork',$subNetGroup);
        }
        
        if ($baseStation) {
        	$baseStationGroup = explode(',', $baseStation);
        	$moNameQuery      = $moNameQuery->whereIn('meContext',$baseStationGroup);
        }
        
        $total           = $moNameQuery->count();
        $result["total"] = $total;
        $data            = $moNameQuery->offset($offset)->limit($pageSize)->get()->toArray();
        $result['data']  =  $data;
        return $result;
    }
    /**  
    * 导出kget文件 downloadFile
    * 
    * @access public 
    * @return string
    */
    public function exportKgetFile() {

    	$kgetTaskValue  = Input::get('kgetTaskValue');
        $moName         = Input::get('moName');
        $city           = Input::get('city');
        $subNet         = Input::get('subNet');
        $baseStation    = Input::get('baseStation');
        $kgetParamValue = Input::get('kgetParamValue') ? implode(",", Input::get('kgetParamValue')) : '';

        $dataSource     = Input::get('dataSource');
        $dataType       = Input::get('dataType');
        
        Config::set('database.connections.kget.database', $kgetTaskValue);
        $db             = DB::connection('kget')->getPdo();

        $moNameQuery    = DB::connection('kget')->table($moName);
        $title          = '';
        if ($kgetParamValue) {

        	$moNameQuery = $moNameQuery->selectRaw($kgetParamValue);
        	;
        	$title       = 'select '.'"'.str_replace(',', '","', $kgetParamValue).'" union all ';
        } else {
        	$params      = COLUMNS::selectRaw('concat(\'"\',GROUP_CONCAT(COLUMN_NAME SEPARATOR \'","\'),\'"\') as params')->where('TABLE_SCHEMA','=',$kgetTaskValue)->where('TABLE_NAME','=',$moName)->first()->toArray();
        	$title       = 'select '.$params['params'].' union all ';
        }
        $bindParams     = [];
        $allSubNetGroup = [];
        if ($city) {
        	$subNetAll  = $this->dbc->getSubnetwork($city,$dataSource,$dataType);

        	foreach ($subNetAll as $city => $subNets) {
        		$allSubNetGroup = array_merge($allSubNetGroup, $subNets);
        	}
        }
        
        if ($subNet) {
        	$subNetGroup    = [];

        	foreach ($subNet as $value) {
        		$subNetGroup[] = explode(':', $value)[1];
        	}
        	$allSubNetGroup = array_intersect($allSubNetGroup, $subNetGroup);
        }

        if ($allSubNetGroup) {
        	$moNameQuery    = $moNameQuery->whereIn('subNetwork',$allSubNetGroup);
        	$bindParams     = $allSubNetGroup;
        }

        if ($baseStation) {
        	$baseStationGroup = explode(',', $baseStation);
        	$moNameQuery      = $moNameQuery->whereIn('meContext',$baseStationGroup);
        	$bindParams       = array_merge($bindParams,$baseStationGroup);
        }
        
        $sql      = $moNameQuery->toSql();
        $filePath = '../QAT/';
        $fileName = "files/" . $kgetTaskValue . "_" . $moName . "_" . date('YmdHis') . ".csv";
        $sql      = $title.$sql." INTO OUTFILE '".$filePath.$fileName."'
                        FIELDS TERMINATED BY ',' 
                        OPTIONALLY ENCLOSED BY '\"' 
                        LINES TERMINATED BY '\\n'";
        $stmt     = $db->prepare($sql);

        if ($stmt->execute($bindParams)) {
            return $fileName;
        } else {
            return "noData";
        }
    }

    /**  
    * 获取KgetCrontabTask数据 KgetCrontabTask
    * 
    * @access public 
    * @return Json 参数列表
    */
    public function getKgetCrontabTask() {

        $currentPage          = Input::get('currentPage') == '' ? 1 : Input::get('currentPage');
        $pageSize             = Input::get('pageSize') == '' ? 4 : Input::get('pageSize');
        $offset               = ($currentPage - 1) * $pageSize;

        $user                 = Auth::user()->name;
        $kgetCrontabTaskQuery = KgetCrontabTask::where('user','=',$user);
        $result               = array();
        $total                = $kgetCrontabTaskQuery->count();
        $result["total"]      = $total;
        $data                 = $kgetCrontabTaskQuery->offset($offset)->limit($pageSize)->get()->toArray();
        $result['data']       =  $data;
        return $result;
    }
    /**  
    * 通过任务名称验证任务是否已经存在 checkKgetTaskName
    * 
    * @access public 
    * @return int
    */
    public function checkKgetTaskName() {

        $user     = Auth::user()->name;
        $taskName = Input::get('taskName');
        
        return KgetCrontabTask::where('user','=',$user)->where('taskName','=',$taskName)->count();

    }
    /**  
    * 插入kget定时任务 kgetCrontabTask
    * 
    * @access public 
    * @return Json 参数列表
    */
    public function insertKgetCrontabTask() {

        $id         = Input::get('taskId');
        $user       = Auth::user()->name;
        $taskName   = Input::get('taskName');
        $email      = Input::get('email');
        $pushTime   = Input::get('pushTime');
        $status     = Input::get('status') ? 'ON' : 'OFF';
        
        $kget       = Input::get('kgetTaskValue');
        $moName     = Input::get('moName');
        $city       = Input::get('city') ? implode(',', Input::get('city')) : '';;
        $subNetwork = Input::get('subNet') ? implode(',', Input::get('subNet')) : '';;
        $meContext  = Input::get('baseStation');
        $params     = Input::get('kgetParamValue') ? implode(',', Input::get('kgetParamValue')) : '';
        if ($id) {
            $kgetCrontabTask = KgetCrontabTask::find($id);
        } else {
            $kgetCrontabTask = new KgetCrontabTask();
        }
        
        $kgetCrontabTask->taskName   = $taskName;
        $kgetCrontabTask->user       = $user;
        $kgetCrontabTask->email      = $email;
        $kgetCrontabTask->pushTime   = $pushTime;
        $kgetCrontabTask->kget       = $kget;
        $kgetCrontabTask->moName     = $moName;
        $kgetCrontabTask->city       = $city;
        $kgetCrontabTask->subNetwork = $subNetwork;
        $kgetCrontabTask->meContext  = $meContext;
        $kgetCrontabTask->params     = $params;
        $kgetCrontabTask->status     = $status;

        $kgetCrontabTask->save();

    }
    /**  
    * 删除kget定时任务 kgetCrontabTask
    * 
    * @access public 
    */
    public function deleteKgetCrontabTask() {

        KgetCrontabTask::where('id', Input::get('id'))->delete();

    }
    /**  
    * 获取KgetMo列表无层级关系
    * 
    * @access public 
    * @return Json KgetMo层级列表无层级关系
    */
    public function getQatKgetMoList() {

        $moTreeData = [];
        Config::set('database.connections.kget.database', $this->dbName);
        $mos        = MoTreeInfo::select('moName')->groupBy('moName')->get()->toArray();
        $kgetMoList = array();
        foreach ($mos as $mo) {
            $kgetMoList[] = array("value"=>$mo['moName'],"label"=>$mo['moName']);
        }
        return json_encode($kgetMoList);

    }
}
