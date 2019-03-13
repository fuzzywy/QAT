<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Models\city;
class CityController extends Controller 
{
	public function getCity() {

		$result = city::get()->toArray();
		$arr = array(array( 'value'=>'allSelect', 'label'=>'全选' ));
		if($result){
			foreach ($result as $key => $value) {
				$item = array("value"=>$value['city'],"label"=>$value['city']);
				array_push($arr, $item);
			}
		}

		// print_r($result);
		// sleep(1);
		// $dataSource = Input::get('dataSource');
		// $dataType = Input::get('dataType');
		// $arr = array(
		// 		array('value'=>'allSelect', 'label'=>'全选/全不选'),
		// 		array('value'=>$dataSource, 'label'=>$dataSource),
		// 		array('value'=>$dataType, 'label'=>$dataType),
		// 		array('value'=>'常州', 'label'=>'changzhou')
		// 			);
		return $arr;
	}
}
