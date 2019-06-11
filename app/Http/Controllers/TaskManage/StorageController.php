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
use Illuminate\Support\Facades\Storage;
use Config;
use PDO;
use Exception;
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
    
    /**  
    * 获取任务类型
    * 
    * @access public 
    * @return Json
    */
    public function getStorageType() {
        return DB::table('storage')->select('type as label','type as value')->get();

    }

    /**  
    * 文件上传到指定目录下
    * 
    * @access public 
    * @param  string $storageType
    * @param  string $taskName
    * @param  binary $file
    * @return string
    */
    public function uploadFile(Request $request) {

        $file       = $request->file('file');
        $dir = 'task/'.Input::get('storageType').'/'.Auth::user()->name.'/'.Input::get('taskName');
        $path = Storage::putFileAs($dir, $file, $file->getClientOriginalName());
        
        return strval(Storage::disk('local')->exists($path));

    }
    /**  
    * 新建任务
    * 
    * @access public 
    * @param  string $taskName
    * @param  string $storageType
    * @return void
    */
    public function saveTask() {

        $res      = [];
        $taskName = Input::get('taskName');
        $owner = Auth::user()->name;
        $type = Input::get('storageType');
        $tracePath = 'task/'.$type.'/'.$owner.'/'.$taskName;
        
        $newTask             = new Task;
        $newTask->taskName   = $taskName;
        $newTask->status     = 'prepare';
        $newTask->tracePath  = $tracePath;
        $newTask->owner      = $owner;
        $newTask->createTime = date('Y-m-d H:i:s');
        $newTask->type       = $type;
        
        try {
            $newTask->save();
        } catch (\Illuminate\Database\QueryException $ex) {
            abort(2000,$ex->getMessage());
        }
    }
    /**  
    * 获取任务信息
    * 
    * @access public 
    * @return json
    */
    public function getTask() {
        
        return Task::orderBy('createTime', 'desc')->get();

    }
    /**  
    * 删除指定任务
    * 
    * @access public 
    * @param  string $taskName
    * @param  string $tracePath
    * @return void
    */
    public function deleteTask() {
        $taskName = Input::get('taskName');
        $tracePath = Input::get('tracePath');
        try {
            Task::where('taskName',$taskName)->delete();
            DB::statement("DROP DATABASE IF EXISTS ".$taskName);
            Storage::deleteDirectory($tracePath);
        } catch (Exception $ex) {
            abort(2000,$ex->getMessage());
        }
    }
    /**  
    * 执行指定任务
    * 
    * @access public 
    * @param  string $taskName
    * @param  string $storageType
    * @param  string $tracePath
    * @return integer 
    */
    public function runTask() {
        $taskName    = Input::get('taskName');
        Task::where('taskName',$taskName)->update(['status' => 'ongoing','startTime' => date('Y-m-d H:i:s')]);
        
        $tracePath = str_replace(env('APP_URL'), env('APP_LOCAL_URL'), Storage::disk('local')->path(Input::get('tracePath')));
        system('cd '.getenv('APP_URL').' && /usr/local/bin/php artisan mparser '.strtolower(Input::get('type')).' '.$tracePath.' '.$taskName);
        system('cd '.getenv('APP_URL').' && /usr/local/bin/php artisan kgetProcedure '.$taskName);
        return Task::where('taskName', $taskName)->update(['status'=>'complete','endTime'=>date('Y-m-d H:i:s')]);
    }
    /**  
    * 停止指定正在执行的任务
    * 
    * @access public 
    * @param  string $taskName
    * @return integer 
    */
    public function stopTask() {
        $taskName = Input::get('taskName');
        system('cd '.getenv('APP_URL').' ; /usr/local/bin/php artisan stopParser '.$taskName);
        return Task::where('taskName', $taskName)->update(['status'=>'abort','endTime'=>date('Y-m-d H:i:s')]);
    }
}
