<?php
/** 
* 任务管理数据上传后台代码
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


/** 
* 数据上传控制器
* 
* @author zhujiaojiao      
* @version 0.0  
*/ 
class LogController extends Controller 
{   
    private $taskUtil;

    public function __construct() {

        $this->taskUtil = new TaskUtil();

    }
    /**  
    * 获取日志目录结构
    * 
    * @access public 
    * @return Json 目录结构
    */
    public function getDir() {
        $user = Auth::user()->name;
        $rows = DB::table('dataSource')->select('type','directory')->get()->toArray();
        $result = [];
        foreach ($rows as $row) {
            $dir  = getenv('APP_URL').$row->directory.'/'.$user;
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
                chmod($dir, 0777);
            }
            $type              = $row->type; 
            $root['label']     = $type;
            $root['directory'] = $dir;
            $root['user']      = $user;
            $root['children']  = $this->taskUtil->constructTree($dir, $user);
            array_push($result, $root);
        }
        return $result;
    }
    /**  
    * 获取指定目录下面文件列表
    * 
    * @access public 
    * @param  directory 目录
    * @return Json 文件列表
    */
    public function getFileByDir() {
        $directory = Input::get('directory');
        $mydir     = opendir($directory);
        $title     = "当前目录：" . $directory;
        $data      = array();
        while ($file = readdir($mydir)) {
            if (is_file("$directory/$file")) {
                $data[] = array($title=>$file);
            }
        }
        closedir($mydir);
        return $data;
    }
    /**  
    * 文件上传到指定目录下
    * 
    * @access public 
    * @param  directory 目录
    * @return Json 上传结果
    */
    public function uploadFile(Request $request) {

        $directory  = Input::get('directory');
        $file       = $request->file('file');
       
        $fileName   =$file->getClientOriginalName();
        $realPath   = $file->getRealPath();
        $res        = [];
        if (file_exists($directory.'/'.$fileName)) {
            $res['code'] = 20000;
        } else {
            if (rename($realPath, $directory.'/'.$fileName)) {
                $res['code'] = 20000;
            } else {
                $res['msg']  = '导入文件失败，请联系开发人员';
            }
        }
        
        return $res;
    }
    /**  
    * 添加目录
    * 
    * @access public 
    * @param  directory 目录
    * @return Json 结果
    */
    public function addDir() {

        $directory = Input::get('directory');
        if (is_dir($directory)) {
            echo false;
        } else {
            mkdir($directory, 0777, true);
            chmod($directory, 0777);
            echo true;
        }
    }
    /**  
    * 删除指定目录
    * 
    * @access public 
    * @param  directory 目录
    * @return Json 结果
    */
    public function deleteDir() {

        $directory = Input::get('directory');
        if (is_dir($directory)) {
            exec("rm -R $directory");
            echo true;
        } else {
            echo false;
        }
    }
}
