<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Common\DataBaseConnection;
use App\Eniq;
class SubnetController extends Controller 
{
	/**
	 * 获得子网信息
	 * @DateTime 2019-01-03
	 * @return   array()
	 */
	public function getSubnet() {

		$citys = input::get("citys"); //城市
		$dataType = input::get("dataType");//制式
		$dataSource = input::get("dataSource");//数据库
		if($dataType=="GSM"){
			$arr = array();
		}else{
			$dbc = new DataBaseConnection();
			$arr = array(array("value"=>"allSelect","label"=>"全选"));
			$subNetWork = $dbc->getSubnetwork($citys,$dataSource,$dataType);
			foreach ($subNetWork as $key=>$value) {
				foreach ($value as $k=>$v) {
					//输出格式 chengshi:ziwang
					array_push($arr, array("value"=>$key.":".$v,"label"=>$v));
					
				}
			}
		}
		
		return $arr;
	}
}
