<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use App\Models\Template;
use App\Models\Kpiformula;

class TemplateController extends Controller 
{
    public function getTemplate() {
        // sleep(1);
        $dataSource = Input::get('dataSource');
        $dataType = Input::get('dataType');
        $arr = array(
                array('value'=>$dataSource, 'label'=>$dataSource),
                array('value'=>$dataType, 'label'=>$dataType)
                    );
        return $arr;
    }

    public function getQatLoginUser() {
        return Auth::user()->name;
    }

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
    public function removeQatTemplateName() {
        //执行模板删除语句
        $auth = Input::get('auth');
        $templateName = Input::get('templateName');
        $id = Input::get('id');
        template::where('id', $id)->delete();
        return $this->getQatTemplateData();
    }
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
    public function orderQatElementData(){
        //这些参数不知道有没有用，先传进来。防止需要
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
            array_push($elements, json_decode($value));
            array_push($ids, json_decode($value)->id);
        }
        template::where('format', $parent)
                ->where('user', $grandparent)
                ->where('templateName', $templateName)
                ->update(['elementId' => implode(',', $ids)]);
        return json_encode($elements);
    }
    public function loadQatFormulaData(){
          //elemrnt id/name
        $id = Input::get('id');
        $label = Input::get('label');
        $templateName = Input::get('templateName');
        $parent = Input::get('parent');
        $grandparent = Input::get('grandparent');
        $auth = Input::get('auth');
          // print_r($id.$label.$templateName.$parent.$grandparent.$auth);
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
            // if( $datum['user'] == Auth::user()->name ) {
            //     $arrs[$datum['user']]['children'][$datum['format']]['children'][$datum['kpiName'].$datum['id']] = array('id'=>$datum['id'], 'label'=>$datum['kpiName'], 'showRemove'=>true );
            // } else {
            $arrs[$datum['user']]['children'][$datum['format']]['children'][$datum['kpiName'].$datum['id']] = array('id'=>$datum['id'], 'name'=>$datum['kpiName'], 'formula'=>$datum['kpiFormula'], 'precision'=>$datum['kpiPrecision']);
            // }
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
    public function deleteQatFormula() {
        $id = Input::get('id');
        Kpiformula::where('id', $id)
                    ->where('user', Auth::user()->name)
                    ->delete();
    }
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
        if( Auth::user()->name == 'admin' || $grandparent == Auth::user()->name ) {
            $s = Template::where('templateName', $templateName)
                    ->where('format', $parent)
                    ->where('user', $grandparent)
                    ->get()->toArray();
            if (count($s) == 1) {
                Template::where('templateName', $templateName)
                    ->where('format', $parent)
                    ->where('user', $grandparent)
                    ->update(['elementId'=>implode(',', $ids)]);
            }
        }
    }
}
