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
          if( $grandparent === $auth ) {
               $showRemove = true;
          }else {
               $showRemove = false;
          }
		$arr = array(
			array(
                    'id'=>1,
				'label'=>$templateName,
                    'showRemove'=>$showRemove
			),
			array(
                    'id'=>2,
				'label'=>$parent,
                    'showRemove'=>$showRemove
			),
			array(
                    'id'=>3,
				'label'=>$grandparent,
                    'showRemove'=>$showRemove
			),
			array(
                    'id'=>4,
				'label'=>$auth,
                    'showRemove'=>$showRemove
			)
		);
		print_r(json_encode($arr));
	}
     public function orderQatElementData(){
          //这些参数不知道有没有用，先传进来。防止需要
          $templateName = Input::get('templateName');
          $parent = Input::get('parent');
          $grandparent = Input::get('grandparent');
          $auth = Input::get('auth');

          $element = Input::get('element');
          $elements = [];
          foreach ($element as $value) {
               array_push($elements, json_decode($value));
          }
          print_r(json_encode($elements));
     }
     public function loadQatFormulaData(){
          //elemrnt id/name
          // $id = Input::get('id');
          // $label = Input::get('label');
          // $templateName = Input::get('templateName');
          // $parent = Input::get('parent');
          // $grandparent = Input::get('grandparent');
          // $auth = Input::get('auth');
          // print_r($id.$label.$templateName.$parent.$grandparent.$auth);
          $arr = array(
                    /*'name'=>array(*/
                         array(
                              'id'=>0,
                              'name'=>'通用模板',
                              'type'=>array(
                                   array(
                                        'id'=>0,
                                        'name'=>'TDD0',
                                        'formula'=>array(
                                             array(
                                                  "id"=>1000,
                                                  "name"=>"无线接通率1",
                                                  "formula"=>"1+1",
                                                  "precision"=>"2"
                                             ),
                                             array(
                                                  "id"=>1001,
                                                  "name"=>"无线掉线率1",
                                                  "formula"=>"sdaf+asdad/asda",
                                                  "precision"=>"5"
                                             )
                                        )

                                   ),
                                   array(
                                        'id'=>1,
                                        'name'=>'FDD0',
                                        'formula'=>array(
                                             array(
                                                  "id"=>1002,
                                                  "name"=>"无线接通率2",
                                                  "formula"=>"1+1",
                                                  "precision"=>"2"
                                             ),
                                             array(
                                                  "id"=>1003,
                                                  "name"=>"无线掉线率2",
                                                  "formula"=>"sdaf+asdad/asda",
                                                  "precision"=>"5"
                                             )
                                        )
                                   )
                              )
                                   
                         ),
                         array(
                              'id'=>1,
                              'name'=>'系统模板',
                              'type'=>array(
                                   array(
                                        'id'=>5,
                                        'name'=>'TDD1',
                                        'formula'=>array(
                                             array(
                                                  "id"=>1004,
                                                  "name"=>"无线接通率3",
                                                  "formula"=>"1+1",
                                                  "precision"=>"2"
                                             ),
                                             array(
                                                  "id"=>1005,
                                                  "name"=>"无线掉线率3",
                                                  "formula"=>"sdaf+asdad/asda",
                                                  "precision"=>"5"
                                             )
                                        )
                                   ),
                                   array(
                                        'id'=>6,
                                        'name'=>'FDD1',
                                        'formula'=>array(
                                             array(
                                                  "id"=>1006,
                                                  "name"=>"无线接通率4",
                                                  "formula"=>"1+1",
                                                  "precision"=>"2"
                                             ),
                                             array(
                                                  "id"=>1007,
                                                  "name"=>"无线掉线率4",
                                                  "formula"=>"sdaf+asdad/asda",
                                                  "precision"=>"5"
                                             )
                                        )
                                   )
                              )
                         ),
                         array(
                              'id'=>2,
                              'name'=>Auth::user()->name,
                              'type'=>array(
                                   array(
                                        'id'=>7,
                                        'name'=>'TDD2',
                                        'formula'=>array(
                                             array(
                                                  "id"=>1008,
                                                  "name"=>"无线接通率5",
                                                  "formula"=>"1+1",
                                                  "precision"=>"2"
                                             ),
                                             array(
                                                  "id"=>1009,
                                                  "name"=>"无线掉线率5",
                                                  "formula"=>"sdaf+asdad/asda",
                                                  "precision"=>"5"
                                             )
                                        )
                                   ),
                                   array(
                                        'id'=>8,
                                        'name'=>'FDD2'
                                   )
                              )
                         )
                   /* )*/
              /* ,
               "type"=>array(
                    array(
                         'name'=>'TDD'
                    ),
                    array(
                         'name'=>'FDD'
                    ),
                    array(
                         'name'=>'FDD'
                    )
               )*/
          );
          print_r(json_encode($arr));
     }
}
