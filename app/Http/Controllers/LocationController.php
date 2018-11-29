<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class LocationController extends Controller 
{
	public function getLocation() {
		sleep(1);
		$dataSource = Input::get('dataSource');
		$dataType = Input::get('dataType');
		$arr = array(
				array('value'=>$dataSource, 'label'=>$dataSource),
				array('value'=>$dataType, 'label'=>$dataType),
				array('value'=>'city', 'label'=>'城市'),
				array('value'=>'subnet', 'label'=>'子网'),
				array('value'=>'subnetGroup', 'label'=>'子网组'),
				array('value'=>'baseStation', 'label'=>'基站'),
				array('value'=>'baseStationGroup', 'label'=>'基站组'),
				array('value'=>'cell', 'label'=>'小区'),
				array('value'=>'cellGroup', 'label'=>'小区组'),
					);
		return $arr;
	}
}
