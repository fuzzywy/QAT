<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Eniq;
use App\Models\Eniq_2G;

class UploadController extends Controller 
{
	public function upload() {

		$host = Input::get('host');
		$conn = Input::get('conn');
		$city = Input::get('city');
		$port = Input::get('port');
		$userName = Input::get('userName');
		$password = Input::get('password');
		$dbName = Input::get('dbName');
		$subNetworkTdd = Input::get('subNetworkTdd');
		$subNetworkFdd = Input::get('subNetworkFdd');
		$subNetworkNbiot = Input::get('subNetworkNbiot');
		$type = Input::get('type') == '' ? 'LTE': Input::get('type');
		
		Eniq::firstOrCreate(['conn' => $conn, 'type' => $type]);
		Eniq::where('conn', $conn)->where('type', $type)
			->update(['host' => $host, 'city' => $city, 'port' => $port, 'dbName' => $dbName, 'userName' => $userName, 'password' => $password, 'subNetworkTdd' => $subNetworkTdd, 'subNetworkFdd' => $subNetworkFdd, 'subNetworkNbiot' => $subNetworkNbiot]);
			
	}

	public function query() {

		return Eniq::get()->toArray();
	}

	public function delete() {

		Eniq::where('id', Input::get('id'))->delete();
		
	}
}
