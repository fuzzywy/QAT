<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class DataController extends Controller 
{
	public function getQatData() {
		sleep(10);
		$dataSource = Input::get('dataSource');
		$dataType = Input::get('dataType');
		$cancelToken = Input::get('cancelToken');
		// print_r(json_decode($cancelToken));
		$arr = 	array(
					"data"=>array(
						array('date'=>'2016-05-03', 'name'=>'1', 'address'=>$cancelToken, '这是萨达萨达是啊是大大撒大撒大撒撒打算的阿萨大啊'=>$dataSource ),
						array('date'=>'2016-05-03', 'name'=>'2', 'address'=>$dataSource, '这是萨达萨达是啊是大大撒大撒大撒撒打算的阿萨大啊'=>$dataSource ),
						array('date'=>'2016-05-03', 'name'=>'3', 'address'=>$dataSource, '这是萨达萨达是啊是大大撒大撒大撒撒打算的阿萨大啊'=>$dataSource ),
						array('date'=>'2016-05-03', 'name'=>'4', 'address'=>$dataSource, '这是萨达萨达是啊是大大撒大撒大撒撒打算的阿萨大啊'=>$dataSource ),
						array('date'=>'2016-05-03', 'name'=>'5', 'address'=>$dataSource, '这是萨达萨达是啊是大大撒大撒大撒撒打算的阿萨大啊'=>$dataSource ),
						array('date'=>'2016-05-03', 'name'=>'6', 'address'=>$dataSource, '这是萨达萨达是啊是大大撒大撒大撒撒打算的阿萨大啊'=>$dataSource ),
						array('date'=>'2016-05-03', 'name'=>'7', 'address'=>$dataSource, '这是萨达萨达是啊是大大撒大撒大撒撒打算的阿萨大啊'=>$dataSource ),
						array('date'=>'2016-05-03', 'name'=>'8', 'address'=>$dataSource, '这是萨达萨达是啊是大大撒大撒大撒撒打算的阿萨大啊'=>$dataSource ),
						array('date'=>'2016-05-03', 'name'=>'9', 'address'=>$dataSource, '这是萨达萨达是啊是大大撒大撒大撒撒打算的阿萨大啊'=>$dataSource ),
						array('date'=>'2016-05-03', 'name'=>'王小虎', 'address'=>$dataSource, '这是萨达萨达是啊是大大撒大撒大撒撒打算的阿萨大啊'=>$dataSource ),
						array('date'=>'2016-05-03', 'name'=>'王小虎', 'address'=>$dataSource, '这是萨达萨达是啊是大大撒大撒大撒撒打算的阿萨大啊'=>$dataSource ),
						array('date'=>'2016-05-03', 'name'=>'王小虎', 'address'=>$dataSource, '这是萨达萨达是啊是大大撒大撒大撒撒打算的阿萨大啊'=>$dataSource ),
						array('date'=>'2016-05-03', 'name'=>'王小虎', 'address'=>$dataSource, '这是萨达萨达是啊是大大撒大撒大撒撒打算的阿萨大啊'=>$dataSource ),
						array('date'=>'2016-05-03', 'name'=>'王小虎', 'address'=>$dataSource, '这是萨达萨达是啊是大大撒大撒大撒撒打算的阿萨大啊'=>$dataSource ),
						array('date'=>'2016-05-03', 'name'=>'王小虎', 'address'=>$dataSource, '这是萨达萨达是啊是大大撒大撒大撒撒打算的阿萨大啊'=>$dataSource ),
						array('date'=>'2016-05-03', 'name'=>'王小虎', 'address'=>$dataSource, '这是萨达萨达是啊是大大撒大撒大撒撒打算的阿萨大啊'=>$dataSource ),
						array('date'=>'2016-05-03', 'name'=>'王小虎', 'address'=>$dataSource, '这是萨达萨达是啊是大大撒大撒大撒撒打算的阿萨大啊'=>$dataSource ),
						array('date'=>'2016-05-03', 'name'=>'王小虎', 'address'=>$dataType, '这是萨达萨达是啊是大大撒大撒大撒撒打算的阿萨大啊'=>$dataSource)
						),
					"title"=>'common_files_1125丢包201812041929405c05f4a4c3869.csv'
					);
		// print_r($arr);
		return $arr;
	}
}
