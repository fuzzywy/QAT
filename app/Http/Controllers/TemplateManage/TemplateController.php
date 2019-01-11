<?php
/** 
* 模板管理后台代码
* 
* @author LiJian
* @version 0.0   
*/ 
namespace App\Http\Controllers\TemplateManage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use App\Models\Template;
use App\Models\Kpiformula;

/** 
* 模板管理控制器 
* 
* @author LiJian      
* @version 0.0  
*/ 
class TemplateController extends Controller 
{
    /**  
    * 获取模板列表 
    * 
    * @access public 
    * @return array 模板列表
    */
    public function getTemplate() {
        $dataSource = Input::get('dataSource');
        $dataType = Input::get('dataType');
        $arr = array(
                array('value'=>$dataSource, 'label'=>$dataSource),
                array('value'=>$dataType, 'label'=>$dataType)
                    );
        return $arr;
    }
    /**  
    * 获取当前登陆用户 
    * 
    * @access public 
    * @return string 当前登陆用户
    */
    public function getQatLoginUser() {
        return Auth::user()->name;
    }
    /**  
    * 获取模板列表 
    * 
    * @access private 
    * @param mixed $arrs 所有模板数据
    * @param mixed $key 键值
    * @param mixed $datum 单条模板数据
    * @return array 归类模板列表
    */
    private function getRootDir($arrs, $key, $datum) {
        if ( !array_key_exists($key, $arrs) ) {
            if ( $datum['user'] == 'admin' ) {
                $arrs['admin'] = array(
                    'id'=>$datum['id'],
                    'label'=>'通用模板',
                    'showAppend'=>false,
                    'showRemove'=>false,
                    'children'=>array()
                );
            }elseif( $datum['user'] == 'system' ) {
                $arrs['system'] = array(
                    'id'=>$datum['id'],
                    'label'=>'系统模板',
                    'showAppend'=>false,
                    'showRemove'=>false,
                    'children'=>array()
                );
            }else {
                $arrs[$datum['user']] = array(
                    'id'=>$datum['id'],
                    'label'=>$datum['user'],
                    'showAppend'=>false,
                    'showRemove'=>false,
                    'children'=>array()
                );
            }
        }
        return $arrs;
    }
    /**  
    * 获取模板管理列表 
    * 
    * @access private 
    * @return array 模板管理列表
    */
    public function getQatTemplateData() {
        $arrs = [];
        $dataSource = Input::get('dataSource');
        $dataType = Input::get('dataType');
        $data = template::get()->toArray();
        foreach ($data as $key => $datum) {
            $arrs = $this->getRootDir($arrs, $key, $datum);
        }
        foreach ($data as $key => $datum) {
            if( !array_key_exists($datum['format'], $arrs[$datum['user']]['children']) ) {
                if( $datum['user'] == Auth::user()->name ) {
                    $arrs[$datum['user']]['children'][$datum['format']] = array('id'=>$datum['id'], 'label'=>$datum['format'], 'children'=>array(), 'showAppend'=>true, 'showRemove'=>true );
                } else {
                    $arrs[$datum['user']]['children'][$datum['format']] = array('id'=>$datum['id'], 'label'=>$datum['format'], 'children'=>array());
                }
            }
        }
        foreach ($data as $key => $datum) {
            if( $datum['user'] == Auth::user()->name ) {
                $arrs[$datum['user']]['children'][$datum['format']]['children'][$datum['templateName'].$datum['id']] = array('id'=>$datum['id'], 'label'=>$datum['templateName'], 'showRemove'=>true );
            } else {
                $arrs[$datum['user']]['children'][$datum['format']]['children'][$datum['templateName'].$datum['id']] = array('id'=>$datum['id'], 'label'=>$datum['templateName']);
            }
        }
        foreach ($arrs as $key => $arr) {
            foreach ($arr['children'] as $k => $v) {
                $arrs[$key]['children'][$k]['children'] = array_values($v['children']);
            }
        }
        foreach ($arrs as $key => $arr) {
            $arrs[$key]['children'] = array_values($arr['children']);
        }
        return (json_encode(array_values($arrs)));
    }
    /**  
    * 新增模板
    * 
    * @access public 
    * @return array 模板管理列表
    */
    public function insertQatTemplateName() {
        //执行模板插入语句
        $auth = Input::get('auth');
        $templateName = Input::get('templateName');
        $parent = Input::get('parent');
        $template = new template();
        $template->templateName = $templateName;
        $template->user = $auth;
        $template->format = $parent;
        $template->save();
        return $this->getQatTemplateData();
    }
    /**  
    * 删除模板
    * 
    * @access public 
    * @return array 模板管理列表
    */
    public function removeQatTemplateName() {
        $auth = Input::get('auth');
        $templateName = Input::get('templateName');
        $id = Input::get('id');
        template::where('id', $id)->delete();
        return $this->getQatTemplateData();
    }
    /**  
    * 加载元素列表
    * 
    * @access public 
    * @return array 元素列表
    */
    public function loadQatElementData() {
        $templateName = Input::get('templateName');
        $parent = Input::get('parent');
        $grandparent = Input::get('grandparent');
        $auth = Input::get('auth');
        if( $grandparent == '通用模板' ) {
            $grandparent = 'admin';
        }
        if( $grandparent == '系统模板' ) {
            $grandparent = 'system';
        }
        if( $grandparent === $auth ) {
            $showRemove = true;
        }else {
            $showRemove = false;
        }
        $elementId = template::select('elementId')
                ->where('templateName', $templateName)
                ->where('format', $parent)
                ->where('user', $grandparent)
                ->get()
                ->first()
                ->elementId;
        $arrId = explode(',', $elementId);
        $elementId = ',' . $elementId . ',';
        $arrs = Kpiformula::selectRaw("kpiName, instr('$elementId', CONCAT(',',id,',')) as sort, id, format, user")
                        ->whereIn('id', $arrId)
                        ->orderBy('sort')
                        ->get()
                        ->toArray();
        $elements = [];
        foreach ($arrs as $arr) {
            $arr['showRemove'] = $showRemove;
            $arr['label'] = $arr['kpiName'];
            $arr['parent'] = $arr['format'];
            $arr['grandparent'] = $arr['user'];;
            $elements[] = $arr;
        }
        return json_encode($elements);
    }
    /**  
    * 元素列表排序
    * 
    * @access public 
    * @return array 元素列表
    */
    public function orderQatElementData(){
        $templateName = Input::get('templateName');
        $parent = Input::get('parent');
        $grandparent = Input::get('grandparent');
        $auth = Input::get('auth');
        if( $grandparent == '通用模板' ) {
            $grandparent = 'admin';
        }
        if( $grandparent == '系统模板' ) {
            $grandparent = 'system';
        }
        $element = Input::get('element');
        $elements = [];
        $ids = [];
        foreach ($element as $value) {
            array_push($elements, $value['label']);
            array_push($ids, $value['id']);
        }
        template::where('format', $parent)
                ->where('user', $grandparent)
                ->where('templateName', $templateName)
                ->update(['elementId' => implode(',', $ids)]);
        return json_encode($elements);
    }
    /**  
    * 加载公式列表
    * 
    * @access public
    * @return array 公式列表
    */
    public function loadQatFormulaData(){
        $id = Input::get('id');
        // $label = Input::get('label');
        // $templateName = Input::get('templateName');
        $parent = Input::get('parent');
        $grandparent = Input::get('grandparent');
        $auth = Input::get('auth');
        $arrs = [];
        $dataSource = Input::get('grandparent');
        $dataType = Input::get('parent');
        $data = kpiformula::get()->toArray();
        foreach ($data as $key => $datum) {
            if ( !array_key_exists($key, $arrs) ) {
                if ( $datum['user'] == 'admin' ) {
                    $arrs['admin'] = array(
                        'id'=>$datum['id'],
                        'name'=>'通用模板',
                        'showEdit'=>false,
                        'showDelete'=>false,
                        'children'=>array()
                    );
                }elseif( $datum['user'] == 'system' ) {
                    $arrs['system'] = array(
                        'id'=>$datum['id'],
                        'name'=>'系统模板',
                        'showEdit'=>false,
                        'showDelete'=>false,
                        'children'=>array()
                    );
                }else {
                    $arrs[$datum['user']] = array(
                        'id'=>$datum['id'],
                        'name'=>$datum['user'],
                        'showEdit'=>false,
                        'showDelete'=>false,
                        'children'=>array()
                    );
                }
            }
        }
        foreach ($data as $key => $datum) {
            if( !array_key_exists($datum['format'], $arrs[$datum['user']]['children']) ) {
                $arrs[$datum['user']]['children'][$datum['format']] = array('id'=>$datum['id'], 'name'=>$datum['format'], 'children'=>array());
            }
        }
        foreach ($data as $key => $datum) {
            $arrs[$datum['user']]['children'][$datum['format']]['children'][$datum['kpiName'].$datum['id']] = array('id'=>$datum['id'], 'name'=>$datum['kpiName'], 'formula'=>$datum['kpiFormula'], 'precision'=>$datum['kpiPrecision']);
            if( $datum['user'] == Auth::user()->name ) {
                $arrs[$datum['user']]['showEdit'] = true;
                $arrs[$datum['user']]['showDelete'] = true;
            }
        }
        foreach ($arrs as $key => $arr) {
            foreach ($arr['children'] as $k => $v) {
                $arrs[$key]['children'][$k]['children'] = array_values($v['children']);
            }
        }
        foreach ($arrs as $key => $arr) {
            $arrs[$key]['children'] = array_values($arr['children']);
        }
        return (json_encode(array_values($arrs)));
    }
    /**  
    * 选择元素更新公式列表
    * 
    * @access public
    * @return array 公式列表
    */
    public function selectKpiFormula() {
        $clickElement = json_decode(Input::get('clickElement'));
        $elements = Input::get('elements');
        $data = Kpiformula::where('id', $clickElement->id)
                ->get()
                ->toArray();
        $return['click'] = $data;
        $user = $data[0]['user'];
        $format = $data[0]['format'];
        $ids = [];
        foreach ($elements as $key => $element) {
            array_push($ids, json_decode($element)->id);
        }
        $data = Kpiformula::whereIn('id', $ids)
                ->where('user', $user)
                ->where('format', $format)
                ->get()
                ->toArray();
        $return['elements'] = $data;
        return json_encode($return);
    }
    /**  
    * 新增公式
    * 
    * @access public
    * @return array 当前新增公式名
    */
    public function addQatFormula() {
        $kpiName = Input::get('kpiName');
        $kpiFormula = Input::get('kpiFormula');
        $kpiPrecision = Input::get('kpiPrecision');
        $format = Input::get('format');
        $user = Auth::user()->name;
        $s = Kpiformula::where('kpiName', $kpiName)
                    ->where('user', $user)
                    ->where('format', strtolower($format))
                    ->get()->toArray();
        if ( count($s) == 0 ) {
            $formula = new Kpiformula();
            $formula->kpiName = $kpiName;
            $formula->kpiFormula = $kpiFormula;
            $formula->kpiPrecision = $kpiPrecision;
            $formula->format = strtolower($format);
            $formula->user = $user;
            $formula->save();
        }
        return [$kpiName];
    }
    /**  
    * 删除公式
    * 
    * @access public
    * @return null
    */
    public function deleteQatFormula() {
        $id = Input::get('id');
        Kpiformula::where('id', $id)
                    ->where('user', Auth::user()->name)
                    ->delete();
    }
    /**  
    * 修改公式
    * 
    * @access public
    * @return array 当前修改公式名
    */
    public function modifyQatFormula() {
        $id = Input::get('id');
        $kpiName = Input::get('kpiName');
        $kpiFormula = Input::get('kpiFormula');
        $kpiPrecision = Input::get('kpiPrecision');
        $s = Kpiformula::where('id', $id)
                    ->get()->toArray();
        if( count($s) == 1 && $s[0]['user'] == Auth::user()->name ) {
            Kpiformula::where('id', $id)
                        ->update(['kpiName' => $kpiName, 'kpiFormula' => $kpiFormula, 'kpiPrecision' => $kpiPrecision]);
            return [$kpiName];
        }
    }
    /**  
    * 插入公式名到元素列表
    * 
    * @access public
    * @return array 元素列表
    */
    public function insertQatElement() {
        $templateName = Input::get('templateName');
        $parent = Input::get('parent');
        $grandparent = Input::get('grandparent');
        if( $grandparent == '通用模板' ) {
            $grandparent = 'admin';
        }
        if( $grandparent == '系统模板' ) {
            $grandparent = 'system';
        }
        $ids = Input::get('ids');
        $s = Template::where('templateName', $templateName)
                ->where('format', $parent)
                ->where('user', $grandparent)
                ->get()->toArray();
        if (count($s) == 1) {
            $str = '';
            if( $ids == '' ) {
                $str = '';
            }else {
                $str = implode(',', $ids);
            }
            Template::where('templateName', $templateName)
                ->where('format', $parent)
                ->where('user', $grandparent)
                ->update(['elementId'=>$str]);
        }
        return $this->getElement($templateName, $parent, $grandparent);
    }
    /**  
    * 获取元素列表
    * 
    * @access private
    * @param mixed $templateName 模板名
    * @param mixed $parent 字段format/数据源类型
    * @param mixed $grandparent 字段user/数据源
    * @return array 元素列表
    */
    private function getElement($templateName, $parent, $grandparent) {
        if( $grandparent == '通用模板' ) {
            $grandparent = 'admin';
        }
        if( $grandparent == '系统模板' ) {
            $grandparent = 'system';
        }
        $showRemove = true;
        $elementId = template::select('elementId')
                ->where('templateName', $templateName)
                ->where('format', $parent)
                ->where('user', $grandparent)
                ->get()
                ->first()
                ->elementId;
        $arrId = explode(',', $elementId);
        $elementId = ',' . $elementId . ',';
        $arrs = Kpiformula::selectRaw("kpiName, instr('$elementId', CONCAT(',',id,',')) as sort, id, format, user")
                        ->whereIn('id', $arrId)
                        ->orderBy('sort')
                        ->get()
                        ->toArray();
        $elements = [];
        foreach ($arrs as $arr) {
            $arr['showRemove'] = $showRemove;
            $arr['label'] = $arr['kpiName'];
            $arr['parent'] = $arr['format'];
            $arr['grandparent'] = $arr['user'];;
            $elements[] = $arr;
        }
        return json_encode($elements);
    } 
}
