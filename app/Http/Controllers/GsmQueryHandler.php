<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eniq_2G;
use PDO;
class GsmQueryHandler extends Controller
{
    use KpiGsmQueryHandler;

    /**
     * GSM查询
     * @DateTime 2019-01-16
     * @param    Request    $request 输入信息
     * @return   array              数据和文件名
     */
    public function templateQuery(Request $request){

    	$res =Eniq_2G::select()->get()->toArray();

    	$cities = $request['cities'];
    	$this->init($request);

    	$item = array();

    	foreach ($cities as $key => $value) {
            $dbServers = Eniq_2G::where("city",$value)->get()->toArray();
    		
    		$host     = $dbServers[0]['host'];
            $port     = $dbServers[0]['port'];
            $dbName   = $dbServers[0]['dbName'];
            $userName = $dbServers[0]['userName'];
            $password = $dbServers[0]['password'];
            $pmDbDSN = "dblib:host=".$host.":".$port.";".((float)phpversion()>7.0?'dbName':'dbname')."=".$dbName;
            try {
                $pmDB     = new PDO($pmDbDSN, $userName, $password);
                
            } catch (\Exception $e) {
                continue;
            }
            $sql = $this->createSql($dbServers[0]['conn']);
            $result = $pmDB->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            if($result){
    			$title = explode(",",$this->sql->resultText);
            	foreach ($result as $k => $v) {
            		$item[] = array_combine($title, $v);
            	}
            }
    	}

    	if(!$item){
    		return array("data"=>array(),"file"=>array());
    	}
    	$fileName = $this->download($item,implode(",", $title));
         //显示数据显示1000条
        $arr = array_slice($item, 0, 1000);
        return array("data"=>$arr,"file"=>$fileName);
    }
}
