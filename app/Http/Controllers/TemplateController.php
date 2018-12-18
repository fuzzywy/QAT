<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;

class TemplateController extends Controller 
{
	public function getTemplate() {
		sleep(1);
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

	public function getQatTemplateData() {
		$dataSource = Input::get('dataSource');
		$dataType = Input::get('dataType');
		$data = array(
					array(
						'id'=>1,
						'label'=>'通用模板',
						'showAppend'=>false,
						'showRemove'=>false,
						'children'=>array(
							array(
								'id'=> 2,
          						'label'=> 'TDD',
          						'children'=>array(
          							array(
          								"id"=>3,
          								"label"=>'templatetdd0'
          							),
          							array(
          								"id"=>4,
          								"label"=>'templatetdd1'
          							)
          						)
          					),
          					array(
								'id'=> 5,
          						'label'=> 'FDD',
          						'children'=>array(
          							array(
          								"id"=>6,
          								"label"=>'templatefdd0'
          							),
          							array(
          								"id"=>7,
          								"label"=>'templatefdd1'
          							)
          						)
          					)
						),
					),
					array(
						'id'=>8,
						'label'=>'系统模板',
				        'showAppend'=>false,
				        'showRemove'=>false,
				        'children'=>array(
							array(
								'id'=> 9,
          						'label'=> 'TDD',
          						'children'=>array(
          							array(
          								"id"=>10,
          								"label"=>'templatetdd0'
          							),
          							array(
          								"id"=>11,
          								"label"=>'templatetdd1'
          							)
          						)
          					),
          					array(
								'id'=> 12,
          						'label'=> 'FDD',
          						'children'=>array(
          							array(
          								"id"=>13,
          								"label"=>'templatefdd0'
          							),
          							array(
          								"id"=>14,
          								"label"=>'templatefdd1'
          							)
          						)
          					)
						),
					),
					array(
						'id'=>15,
						'label'=>Auth::user()->name,
						'showAppend'=>false,
				        'showRemove'=>false,
				        'children'=>array(
							array(
								'id'=> 16,
          						'label'=> 'TDD',
          						'showAppend'=>true,
				        		'showRemove'=>false,
          						'children'=>array(
          							array(
          								"id"=>17,
          								"label"=>'templatetdd0',
          								'showAppend'=>false,
				        				'showRemove'=>true
          							),
          							array(
          								"id"=>18,
          								"label"=>'templatetdd1',
          								'showAppend'=>false,
				        				'showRemove'=>true
          							)
          						)
          					),
          					array(
								'id'=> 19,
          						'label'=> 'FDD',
          						'showAppend'=>true,
				        		'showRemove'=>false,
          						'children'=>array(
          							array(
          								"id"=>20,
          								"label"=>'templatefdd0',
          								'showAppend'=>false,
				        				'showRemove'=>true
          							),
          							array(
          								"id"=>21,
          								"label"=>'templatefdd1',
          								'showAppend'=>false,
				        				'showRemove'=>true
          							)
          						)
          					)
						),
					)
				);
		print_r(json_encode($data));
		// echo '123';
	}
	public function insertQatTemplateName() {
		//执行模板插入语句
		$auth = Input::get('auth');
		$templateName = Input::get('templateName');
		$data = array(
					array(
						'id'=>1,
						'label'=>'通用模板',
						'showAppend'=>false,
						'showRemove'=>false,
						'children'=>array(
							array(
								'id'=> 2,
          						'label'=> 'TDD',
          						'children'=>array(
          							array(
          								"id"=>3,
          								"label"=>'templatetdd0'
          							),
          							array(
          								"id"=>4,
          								"label"=>'templatetdd1'
          							)
          						)
          					),
          					array(
								'id'=> 5,
          						'label'=> 'FDD',
          						'children'=>array(
          							array(
          								"id"=>6,
          								"label"=>'templatefdd0'
          							),
          							array(
          								"id"=>7,
          								"label"=>'templatefdd1'
          							)
          						)
          					)
						),
					),
					array(
						'id'=>8,
						'label'=>'系统模板',
				        'showAppend'=>false,
				        'showRemove'=>false,
				        'children'=>array(
							array(
								'id'=> 9,
          						'label'=> 'TDD',
          						'children'=>array(
          							array(
          								"id"=>10,
          								"label"=>'templatetdd0'
          							),
          							array(
          								"id"=>11,
          								"label"=>'templatetdd1'
          							)
          						)
          					),
          					array(
								'id'=> 12,
          						'label'=> 'FDD',
          						'children'=>array(
          							array(
          								"id"=>13,
          								"label"=>'templatefdd0'
          							),
          							array(
          								"id"=>14,
          								"label"=>'templatefdd1'
          							)
          						)
          					)
						),
					),
					array(
						'id'=>15,
						'label'=>Auth::user()->name,
						'showAppend'=>false,
				        'showRemove'=>false,
				        'children'=>array(
							array(
								'id'=> 16,
          						'label'=> 'TDD',
          						'showAppend'=>true,
				        		'showRemove'=>false,
          						'children'=>array(
          							array(
          								"id"=>17,
          								"label"=>'templatetdd0',
          								'showAppend'=>false,
				        				'showRemove'=>true
          							),
          							array(
          								"id"=>18,
          								"label"=>'templatetdd1',
          								'showAppend'=>false,
				        				'showRemove'=>true
          							)
          						)
          					),
          					array(
								'id'=> 19,
          						'label'=> 'FDD',
          						'showAppend'=>true,
				        		'showRemove'=>false,
          						'children'=>array(
          							array(
          								"id"=>20,
          								"label"=>'templatefdd0',
          								'showAppend'=>false,
				        				'showRemove'=>true
          							),
          							array(
          								"id"=>21,
          								"label"=>'templatefdd1',
          								'showAppend'=>false,
				        				'showRemove'=>true
          							),
          							array(
          								"id"=>22,
          								"label"=>'templatefdd2',
          								'showAppend'=>false,
				        				'showRemove'=>true
          							)
          						)
          					)
						),
					)
				);
		print_r(json_encode($data));
	}
	public function removeQatTemplateName() {
		//执行模板删除语句
		$auth = Input::get('auth');
		$templateName = Input::get('templateName');
		$data = array(
					array(
						'id'=>1,
						'label'=>'通用模板',
						'showAppend'=>false,
						'showRemove'=>false,
						'children'=>array(
							array(
								'id'=> 2,
          						'label'=> 'TDD',
          						'children'=>array(
          							array(
          								"id"=>3,
          								"label"=>'templatetdd0'
          							),
          							array(
          								"id"=>4,
          								"label"=>'templatetdd1'
          							)
          						)
          					),
          					array(
								'id'=> 5,
          						'label'=> 'FDD',
          						'children'=>array(
          							array(
          								"id"=>6,
          								"label"=>'templatefdd0'
          							),
          							array(
          								"id"=>7,
          								"label"=>'templatefdd1'
          							)
          						)
          					)
						),
					),
					array(
						'id'=>8,
						'label'=>'系统模板',
				        'showAppend'=>false,
				        'showRemove'=>false,
				        'children'=>array(
							array(
								'id'=> 9,
          						'label'=> 'TDD',
          						'children'=>array(
          							array(
          								"id"=>10,
          								"label"=>'templatetdd0'
          							),
          							array(
          								"id"=>11,
          								"label"=>'templatetdd1'
          							)
          						)
          					),
          					array(
								'id'=> 12,
          						'label'=> 'FDD',
          						'children'=>array(
          							array(
          								"id"=>13,
          								"label"=>'templatefdd0'
          							),
          							array(
          								"id"=>14,
          								"label"=>'templatefdd1'
          							)
          						)
          					)
						),
					),
					array(
						'id'=>15,
						'label'=>Auth::user()->name,
						'showAppend'=>false,
				        'showRemove'=>false,
				        'children'=>array(
							array(
								'id'=> 16,
          						'label'=> 'TDD',
          						'showAppend'=>true,
				        		'showRemove'=>false,
          						'children'=>array(
          							array(
          								"id"=>17,
          								"label"=>'templatetdd0',
          								'showAppend'=>false,
				        				'showRemove'=>true
          							),
          							array(
          								"id"=>18,
          								"label"=>'templatetdd1',
          								'showAppend'=>false,
				        				'showRemove'=>true
          							)
          						)
          					),
          					array(
								'id'=> 19,
          						'label'=> 'FDD',
          						'showAppend'=>true,
				        		'showRemove'=>false,
          						'children'=>array(
          							array(
          								"id"=>20,
          								"label"=>'templatefdd0',
          								'showAppend'=>false,
				        				'showRemove'=>true
          							),
          							array(
          								"id"=>21,
          								"label"=>'templatefdd1',
          								'showAppend'=>false,
				        				'showRemove'=>true
          							)
          						)
          					)
						),
					)
				);
		print_r(json_encode($data));
	}
	public function loadQatElementData() {
		$templateName = Input::get('templateName');
		$parent = Input::get('parent');
		$grandparent = Input::get('grandparent');
		$auth = Input::get('auth');
		$arr = array(
			array(
				'label'=>$templateName
			),
			array(
				'label'=>$parent
			),
			array(
				'label'=>$grandparent
			),
			array(
				'label'=>$auth
			)
		);
		print_r(json_encode($arr));
	}
}
