<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class LocationController extends Controller 
{
	public function getLocation() {
		$dataSource = Input::get('dataSource');
		$dataType = Input::get('dataType');
		switch($dataSource) {
			case "ENIQ":
				if($dataType=="GSM"){
					$arr = array(
						array('value'=>'city', 'label'=>'城市'),
						array('value'=>'cell', 'label'=>'小区'),
						array('value'=>'cellGroup', 'label'=>'小区组'),
						array('value'=>'BSC', 'label'=>'BSC'),
						array('value'=>'BSCGroup', 'label'=>'BSC组')
							);
				}else{
						$arr = array(
						array('value'=>'city', 'label'=>'城市'),
						array('value'=>'subnet', 'label'=>'子网'),
						array('value'=>'subnetGroup', 'label'=>'子网组'),
						array('value'=>'baseStation', 'label'=>'基站'),
						array('value'=>'baseStationGroup', 'label'=>'基站组'),
						array('value'=>'cell', 'label'=>'小区'),
						array('value'=>'cellGroup', 'label'=>'小区组'),
							);
				}
				break;
			case "NBM":
				$arr = array(
						array('value'=>'city', 'label'=>'城市'),
						array('value'=>'baseStation', 'label'=>'基站'),
						array('value'=>'cell', 'label'=>'小区'),
						array('value'=>'cellGroup', 'label'=>'小区组'),
							);
				break;
			case "ALARM":
				switch (substr($dataType, 0, 3)) {
		            case "GSM":
		                $arr = array(
						array('value'=>'baseStation', 'label'=>'BSC'),
						array('value'=>'cell', 'label'=>'BTS'),
							);
		                break;
		            case "LTE":
		                $arr = array(
						array('value'=>'baseStation', 'label'=>'基站'),
						array('value'=>'cell', 'label'=>'小区'),
							);
		                break;
		        }
				
				break;
		}
		
	
		return $arr;
	}
}
