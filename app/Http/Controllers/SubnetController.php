<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Input;

class SubnetController extends Controller 
{
	public function getSubnet() {
		sleep(2);
		// print_r(Input::get('citys'));
		// echo 'subnetcontroller';
		//$city = json_encode(Input::get('citys'));
		$arr = array( array( 'value'=>'allSelect', 'label'=>'全选' ),
					  array( 'value'=>'t1', 'label'=>Input::get('citys')[0] ),
					  array( 'value'=>'t2', 'label'=>Input::get('dataSource') ),
					  array( 'value'=>'t3', 'label'=>Input::get('dataType') )
					);
		return $arr;
	}
}
