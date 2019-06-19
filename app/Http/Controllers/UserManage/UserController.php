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
use App\Models\Menu;
use App\Http\Controllers\Utils\UserUtil;


/** 
* 用户管理控制器
* 
* @author zhujiaojiao      
* @version 0.0  
*/ 
class UserController extends Controller 
{   
    private $userUtil;
    public function __construct() {

        $this->userUtil = new UserUtil();

    }
    /**  
    * 获取登录用户信息
    * 
    * @access public 
    * @return array
    */
    public function getUser() {
        $user       = Auth::user();
        $menuIds    = explode(',',Role::select('permission')->where('type',$user->type)->first()->permission);
        $pmenuIds   = Menu::select('pmenu')->distinct()->whereIn('id',$menuIds)->get()->toArray();
        $menus      = Menu::whereIn('id',array_merge($pmenuIds,$menuIds))->get()->toArray();

        $asideMenus = Menu::whereIn('id',array_merge($pmenuIds,$menuIds))->where(function($query){
            $query->whereNull('pmenu')->orWhere('pmenu','');
        })->get()->toArray();
        $asideMenuTreeData    = array();
        $dropdownMenuTreeData = array();
        foreach ($asideMenus as $asideMenu) {
            $arr          = array();
            $arr['index'] = $asideMenu['menu'];
            $arr['name']  = $asideMenu['menu'];
            $arr['icon']  = $asideMenu['icon'];
            $dropdownMenuTreeData[$asideMenu['menu']] = $this->userUtil->constructMenuTree($asideMenu['id'], $menus,$asideMenu['menu']);
            array_push($asideMenuTreeData, $arr);
        }
        $user['asideMenu']    = $asideMenuTreeData;
        $user['dropdownMenu'] = $dropdownMenuTreeData;
        return $user;
    }
    /**  
    * 获取注册待审核数据
    * 
    * @access public 
    * @return Json
    */
    public function getReviews() {
        return RegisterProcess::where('status','ongoing')->get();
    }
    /**  
    * 获取用户角色
    * 
    * @access public 
    * @return Json
    */
    public function getRoles() {
        return Role::select('type as label','type as value')->get();
    }
    /**  
    * 用户审核
    * 
    * @access public
    * @return User
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
    * @return Json
    */
    public function getUsers() {
        return User::select('name','email','type')->get();
    }
    /**  
    * 修改用户信息
    * 
    * @access public 
    * @return integer
    */
    public function modifyUser() {
        return User::where('email',Input::get('email'))->update(['type' => Input::get('type')]);
    }
    /**  
    * 删除用户
    * 
    * @access public
    * @return integer
    */
    public function deleteUser() {
        RegisterProcess::where('email',Input::get('email'))->delete();
        return User::where('email',Input::get('email'))->delete();
    }
    /**  
    * 获取用户角色信息
    * 
    * @access public 
    * @return json
    */
    public function getRoleData() {
        return Role::get();
    }
    /**  
    * 修改/添加用户角色
    * 
    * @access public
    * @return void
    */
    public function modifyRole() {
        Role::updateOrInsert(
        ['type' => Input::get('type')],
        ['description' => Input::get('description'),'permission' => implode(',',Input::get('permission'))]);
    }
    /**  
    * 删除用户角色
    * 
    * @access public 
    * @return void
    */
    public function deleteRole() {
        Role::where('type',Input::get('type'))->delete();
    }
    /**  
    * 显示权限对应菜单
    * 
    * @access public
    * @return array
    */
    public function getPermissionData() {
        $type        = Input::get('type') ? Input::get('type') : 'normal';
        $checkedKeys = explode(',',Role::where('type',$type)->first()->permission);

        $asideMenus  = Menu::whereNull('pmenu')->orWhere('pmenu','')->get()->toArray();
        $menus       = Menu::get()->toArray();
        $treeData    = array();
        foreach ($asideMenus as $asideMenu) {
            $arr          = array();
            $arr['id']    = $asideMenu['id'];
            $arr['label'] = $asideMenu['menu'];
            if($children  = $this->userUtil->constructTree($asideMenu['id'], $menus)) $arr['children'] = $children;
            array_push($treeData, $arr);
        }
        $result['data']        = $treeData;
        $result['checkedKeys'] = $checkedKeys;
        return $result;
    }
}
