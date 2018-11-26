<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class CityController extends Controller 
{
	public function getCity() {
		sleep(1);
		$dataSource = Input::get('dataSource');
		$dataType = Input::get('dataType');
		$arr = array(
				array('value'=>'allSelect', 'label'=>'全选/全不选'),
				array('value'=>$dataSource, 'label'=>$dataSource),
				array('value'=>$dataType, 'label'=>$dataType),
				array('value'=>'常州', 'label'=>'changzhou')
					);
		return $arr;
	}
}
