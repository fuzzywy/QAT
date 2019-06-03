<?php
/** 
* 用户管理数据上传后台代码
* 
* @author zhujiaojiao
* @version 0.0   
*/ 
namespace App\Http\Controllers\UserManage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\RegisterProcess;
use App\Models\Role;
use App\User;


/** 
* 用户管理控制器
* 
* @author zhujiaojiao      
* @version 0.0  
*/ 
class UserController extends Controller 
{   
    /**  
    * 获取登录用户信息
    * 
    * @access public 
    * @return Json users
    */
    public function getUser() {
        return Auth::user();
    }
    /**  
    * 获取注册待审核数据
    * 
    * @access public 
    * @return Json reviews
    */
    public function getReviews() {
        return RegisterProcess::where('status','ongoing')->get();
    }
    /**  
    * 获取用户角色
    * 
    * @access public 
    * @return Json 目录结构
    */
    public function getRoles() {
        return Role::select('type as label','type as value')->get();
    }
    /**  
    * 用户审核
    * 
    * @access public 
    * @return Json 目录结构
    */
    public function reviewUser() {

        RegisterProcess::where('email',Input::get('email'))->update(['type' => Input::get('type'),'status' => 'complete']);
        return User::create([
            'name' => Input::get('name'),
            'email' => Input::get('email'),
            'password' => Input::get('password'),
            'type' => Input::get('type')
        ]);
    }
    /**  
    * 获取用户信息
    * 
    * @access public 
    * @return Json users
    */
    public function getUsers() {
        return User::select('name','email','type')->get();
    }
    /**  
    * 修改用户信息
    * 
    * @access public 
    * @return Json user
    */
    public function modifyUser() {
        return User::where('email',Input::get('email'))->update(['type' => Input::get('type')]);
    }
    /**  
    * 删除用户
    * 
    * @access public 
    * @return Json user
    */
    public function deleteUser() {
        RegisterProcess::where('email',Input::get('email'))->delete();
        return User::where('email',Input::get('email'))->delete();
    }
    /**  
    * 获取用户角色信息
    * 
    * @access public 
    * @return Json roles
    */
    public function getRoleData() {
        return Role::get();
    }
    /**  
    * 修改/添加用户角色
    * 
    * @access public 
    * @return Json user
    */
    public function modifyRole() {
        Role::updateOrInsert(
        ['type' => Input::get('type')],
        ['description' => Input::get('description')]);
    }
    /**  
    * 删除用户角色
    * 
    * @access public 
    * @return Json user
    */
    public function deleteRole() {
        return Role::where('type',Input::get('type'))->delete();
    }
}
