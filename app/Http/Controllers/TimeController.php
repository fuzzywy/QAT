<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class TimeController extends Controller 
{
	public function getTime() {
		sleep(1);
		$dataSource = Input::get('dataSource');
		$dataType = Input::get('dataType');
		if($dataSource=="ENIQ"){
				$arr = array(
						array('value'=>'day', 'label'=>'天'),
						array('value'=>'dayGroup', 'label'=>'天组'),
						array('value'=>'hour', 'label'=>'小时'),
						array('value'=>'hourGroup', 'label'=>'小时组'),
						array('value'=>'quarter', 'label'=>'15分钟')
					);
			}else{
				$arr = array(
						// array('value'=>$dataSource, 'label'=>$dataSource),
						// array('value'=>$dataType, 'label'=>$dataType),
						array('value'=>'day', 'label'=>'天'),
						array('value'=>'dayGroup', 'label'=>'天组'),
						array('value'=>'hour', 'label'=>'小时'),
						array('value'=>'hourGroup', 'label'=>'小时组'),
						array('value'=>'quarter', 'label'=>'15分钟')
					);
			}
	
		return $arr;
	}
}
