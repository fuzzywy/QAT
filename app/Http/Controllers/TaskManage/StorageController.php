<?php
/** 
* 任务管理入库管理后台代码
* 
* @author zhujiaojiao
* @version 0.0   
*/ 
namespace App\Http\Controllers\TaskManage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Config;
use PDO;
use App\Http\Controllers\Utils\TaskUtil;
use App\Models\Task;
use App\Models\USERS;


/** 
* 入库管理控制器
* 
* @author zhujiaojiao      
* @version 0.0  
*/ 
class StorageController extends Controller 
{   
    private $taskUtil;

    public function __construct() {

        $this->taskUtil = new TaskUtil();

    }
    /**  
    * 获取存储类型
    * 
    * @access public 
    * @return Json 目录结构
    */
    public function getStorageType() {

        return DB::table('dataSource')->select('type as label','directory','taskType')->get();

    }
    /**  
    * 获取指定入库类型下的文件列表
    * 
    * @access public 
    * @param  storageType 入库类型
    * @return Json 文件列表
    */
    public function getStorageDirByType() {

        $directory = getenv('APP_URL').Input::get('directory').'/'.Auth::user()->name;
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
            chmod($directory, 0777);
        }
        return $this->taskUtil->constructTree($directory);
    }
    /**  
    * 新建任务
    * 
    * @access public 
    * @param  taskName 任务名称
    * @param  storageType 入库类型
    * @param  storageDir 数据路径
    * @param  taskType 任务类型
    * @return boolean
    */
    public function saveTask() {

        $res      = [];
        $taskName = Input::get('taskName');

        if (Task::where('taskName', $taskName)->exists()) {
            $res['code'] = 0;
            $res['msg']  = 'taskNameExists';
        } else {
            $newTask             = new Task;
            $newTask->taskName   = $taskName;
            $newTask->status     = 'prepare';
            $newTask->tracePath  = Input::get('storageDir');
            $newTask->owner      = Auth::user()->name;
            $newTask->createTime = date('Y-m-d H:i:s');
            $newTask->type       = Input::get('taskType');
            
            try {
                $newTask->save();
                $res['code'] = 1;
                $res['msg']  = 'saveTaskSuccTip';
            } catch (\Illuminate\Database\QueryException $ex) {
                $res['code'] = 0;
                $res['msg']  = 'saveTaskFailTip';
            }
        }
        return $res;
    }
    /**  
    * 获取任务信息
    * 
    * @access public 
    * @param  taskType 任务类型
    * @return json 任务列表
    */
    public function getTask() {

        $user = Auth::user()->name;
        $task = Task::where('type',Input::get('taskType'));
        if(!USERS::where('type','admin')->where('name',$user)->count()){
            $task = $task->where('owner',$user);
        }
        return $task->orderBy('createTime', 'desc')->get();

    }
    /**  
    * 删除指定任务
    * 
    * @access public 
    * @param  taskName 任务名称
    * @return json 删除结果
    */
    public function deleteTask() {
        $taskName = Input::get('taskName');
        $res      = [];
        try {
            Task::where('taskName',$taskName)->delete();
            DB::statement("DROP DATABASE IF EXISTS ".$taskName);
            $res['code'] = 1;
            $res['msg']  = 'deleteSuccess';
        } catch (\Illuminate\Database\QueryException $ex) {
            $res['code'] = 0;
            $res['msg']  = 'deleteFailed';
        }
        return $res;
    }
    /**  
    * 执行指定任务
    * 
    * @access public 
    * @param  taskName 任务名称
    * @return json 
    */
    public function runTask() {
        $user        = Auth::user()->name;
        $taskName    = Input::get('taskName');
        $storageType = strtolower(Input::get('storageType'));
        Task::where('taskName',$taskName)->update(['status' => 'ongoing','startTime' => date('Y-m-d H:i:s')]);
        $task        = Task::where('taskName',$taskName)->first();
        $tracePath   = str_replace(getenv('APP_URL'), getenv('APP_LOCAL_URL'), $task->tracePath);
        system('sudo ssh root@172.16.120.232 "cd /opt/gback/mparser/scripts ; ./parser.sh -t '.$storageType.' -r '.$tracePath.' -d '.$taskName.' ; cd /opt/gback/gprocedure/KGET ; sh kgetProcedure.sh '.$taskName.'"');
        return Task::where('taskName', $taskName)->update(['status'=>'complete','endTime'=>date('Y-m-d H:i:s')]);
    }
    /**  
    * 停止指定正在执行的任务
    * 
    * @access public 
    * @param  taskName 任务名称
    * @return json 
    */
    public function stopTask() {
        $taskName = Input::get('taskName');
        system('sudo ssh root@172.16.120.232 " sh '.getenv('APP_LOCAL_URL').'public/common/sh/stop_monitor.sh '.$taskName.'"');
        return Task::where('taskName', $taskName)->update(['status'=>'abort','endTime'=>date('Y-m-d H:i:s')]);
    }
}
