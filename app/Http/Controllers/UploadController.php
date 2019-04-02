<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Eniq;
use App\Models\Eniq_2G;

class UploadController extends Controller 
{
	public function upload() {
		
		$data = Input::get('data'); //注意post传值格式
		$host = $data['host'];
		$conn = $data['conn'];
		$city = $data['city'];
		$port = $data['port'];
		$userName = $data['userName'];
		$password = $data['password'];
		$dbName = $data['dbName'];
		$subNetworkTdd = $data['subNetworkTdd'];
		$subNetworkFdd = $data['subNetworkFdd'];
		$subNetworkNbiot = $data['subNetworkNbiot'];
		$type = $data['type'] == '' ? 'LTE': $data['type'];
		
		Eniq::firstOrCreate(['host' => $host]);
		Eniq::where('host', $host)
			->update(['conn' => $conn, 'city' => $city, 'port' => $port, 'dbName' => $dbName, 'userName' => $userName, 'password' => $password, 'subNetworkTdd' => $subNetworkTdd, 'subNetworkFdd' => $subNetworkFdd, 'subNetworkNbiot' => $subNetworkNbiot, 'type' => $type]);
			
	}

	public function query() {

		return Eniq::get()->toArray();
	}

	public function delete() {

		$data = Input::get('data');
		$host = $data['host'];
		$type = $data['type'];
		Eniq::where('host', $host)->where('type', $type)->delete();
		
	}
}
