<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Eniq;
use App\Models\Eniq_2G;

class UploadController extends Controller 
{
	public function uploadCog() {
		$data = Input::get('data'); //注意post传值格式
		$arr = explode('&', $data);
		$ip = '';
		$conn = '';
		$city = '';
		$type = 'LTE';
		$port = '';
		$user = '';
		$pwd = '';
		$database = '';
		$subNetworkTdd = '';
		$subNetworkFdd = '';
		$subNetworkNbiot = '';
		foreach ($arr as $value) {
			$d = explode('=', $value);
			switch ( $d[0] ) {
				case 'ip':
					$ip = $d[1];
					break;
				case 'conn':
					$conn = $d[1];
					break;
				case 'city':
					$city = urldecode($d[1]);
					break;		
				case 'type':
					$type = $d[1];
					break;
				case 'port':
					$port = $d[1];
					break;
				case 'database':
					$database = $d[1];
					break;
				case 'user':
					$user = $d[1];
					break;
				case 'pwd':
					$pwd = $d[1];
					break;
				case 'subNetworkTdd':
					$subNetworkTdd = urldecode($d[1]);
					break;
				case 'subNetworkFdd':
					$subNetworkFdd = urldecode($d[1]);
					break;
				case 'subNetworkNbiot':
					$subNetworkNbiot = urldecode($d[1]);
					break;	
				default:
					break;
			}
		}
		if ( strtoupper( $type ) === 'LTE' ) {
			$sql = Eniq::firstOrCreate(['host' => $ip]);
			Eniq::where('host', $ip)
				->update(['conn' => $conn, 'city' => $city, 'port' => $port, 'dbName' => $database, 'userName' => $user, 'password' => $pwd, 'subNetworkTdd' => $subNetworkTdd, 'subNetworkFdd' => $subNetworkFdd, 'subNetworkNbiot' => $subNetworkNbiot ]);
			
		} elseif ( strtoupper( $type ) === 'GSM' ) {
			Eniq_2G::firstOrCreate(['host' => $ip]);
			Eniq_2G::where('host', $ip)
				->update(['conn' => $conn, 'city' => $city, 'port' => $port, 'dbName' => $database, 'userName' => $user, 'password' => $pwd ]);
		} else {
			// return false;
		}
	}

	public function showCog() {
		$arr = array();
		$i = 0;
		foreach (Eniq::get()->toArray() as $key => $value) {
			$arr[$i]['id'] = $i;
			$arr[$i]['ip'] = $value['host'];
			$arr[$i]['conn'] = $value['conn'];
			$arr[$i]['city'] = $value['city'];
			$arr[$i]['port'] = $value['port'];
			$arr[$i]['database'] = $value['dbName'];
			$arr[$i]['user'] = $value['userName'];
			$arr[$i]['pwd'] = $value['password'];
			$arr[$i]['subNetworkTdd'] = $value['subNetworkTdd'];
			$arr[$i]['subNetworkFdd'] = $value['subNetworkFdd'];
			$arr[$i]['subNetworkNbiot'] = $value['subNetworkNbiot'];
			$arr[$i++]['type'] = 'LTE';
		}
		foreach (Eniq_2G::get()->toArray() as $key => $value) {
			$arr[$i]['id'] = $i;
			$arr[$i]['ip'] = $value['host'];
			$arr[$i]['conn'] = $value['conn'];
			$arr[$i]['city'] = $value['city'];
			$arr[$i]['port'] = $value['port'];
			$arr[$i]['database'] = $value['dbName'];
			$arr[$i]['user'] = $value['userName'];
			$arr[$i]['pwd'] = $value['password'];
			$arr[$i]['subNetworkTdd'] = '-';
			$arr[$i]['subNetworkFdd'] = '-';
			$arr[$i]['subNetworkNbiot'] = '-';
			$arr[$i++]['type'] = 'GSM';
		}
		return $arr;
	}

	public function deleteCog() {
		$data = Input::get('data');
		$arr = explode('&', $data);
		$ip = '';
		$type = 'LTE';
		foreach ($arr as $value) {
			$d = explode('=', $value);
			switch ( $d[0] ) {
				case 'ip':
					$ip = $d[1];
					break;
				case 'type':
					$type = $d[1];
					break;
				default:
					break;
			}
		}
		if ( strtoupper( $type ) === 'LTE' ) {
			Eniq::where('host', $ip)->delete();
		} elseif ( strtoupper( $type ) === 'GSM' ) {
			Eniq_2G::where('host', $ip)->delete();
		} else {
			// return false;
		}
	}
}
