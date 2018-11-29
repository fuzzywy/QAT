<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

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
}
