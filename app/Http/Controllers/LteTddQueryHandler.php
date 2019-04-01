<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redis;
use App\Eniq;
use PDO;
class LteTddQueryHandler extends Controller
{
    use KpiQueryHandler;

    /**
     * 查询TDD制式
     * @DateTime 2019-01-04
     * @param    Request    
     * @return   array('data'=>array(),'file'=>fileName)
     */
    public function templateQuery(Request $request){

        //初始化赋值
        $this->init($request);

        $array=[];
		foreach ($this->subnets as $key => $value) {
            $dbServers = Eniq::where("conn",$key)->get()->toArray();
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
            $sql = $this->createSql($key,$value);   

            $result = $pmDB->query($sql)->fetchAll(PDO::FETCH_ASSOC);

            $key = trim(preg_replace("/[0-9]+/", '', $key));
            // exit;
            if($result){
                $array[$key][] = $result;
            }
		}

        //得到运算的结果
        $data=    $this->getResult($array);
        if(!$data){
            return array("data"=>array(),"file"=>array());
        }
        //导出数据限制100万条
        $data = array_slice($data, 0, 1000000);
        
        $title = explode(",",$this->sql->resultText);
        // foreach ($data as $key => $value) {
        //     $newData[] = array_combine($title, $value);
        // }
        // $fileName = $this->download($data,$this->sql->resultText);
        //  //显示数据显示1000条
        // $arr = array_slice($newData, 0, 10);
        // return array("data"=>$arr,"file"=>$fileName);
        foreach($data as $rows){
        	$keys = $title;
        	if($this->locationDim=='baseStation'){
              	$baseStationData = unserialize(Redis::get("siteLte_".$rows['location']));
        		if($baseStationData){
        			$cluster = $baseStationData['cluster'];
             		$siteType = $baseStationData['siteType'];
             		$siteNameChinese = $baseStationData['siteNameChinese'];
        		}else{
        			$cluster = "无数据";
             		$siteType = "无数据";
             		$siteNameChinese = "无数据";
        		}	

        		if($this->timeDim=="day"){
        			        array_splice($rows, 3, 0, $cluster);
                            array_splice($rows, 5, 0, $siteType);
                            array_splice($rows, 6, 0, $siteNameChinese);
                            array_splice($keys, 3, 0, 'cluster');
                            array_splice($keys, 5, 0, 'siteType');
                            array_splice($keys, 6, 0, 'siteNameChinese');
        		}elseif($this->timeDim=="hour"||$this->timeDim=="hourgroup"){
        					array_splice($rows, 4, 0, $cluster);
                            array_splice($rows, 6, 0, $siteType);
                            array_splice($rows, 7, 0, $siteNameChinese);
                            array_splice($keys, 4, 0, 'cluster');
                            array_splice($keys, 6, 0, 'siteType');
                            array_splice($keys, 7, 0, 'siteNameChinese');
        		}elseif($this->timeDim=="quarter"){
        					array_splice($rows, 5, 0, $cluster);
                            array_splice($rows, 7, 0, $siteType);
                            array_splice($rows, 8, 0, $siteNameChinese);
                            array_splice($keys, 5, 0, 'cluster');
                            array_splice($keys, 7, 0, 'siteType');
                            array_splice($keys, 8, 0, 'siteNameChinese');
        		}


        	}
        	if($this->locationDim=='cell'){
              	$cellData = unserialize(Redis::get("siteLte_".$rows['location']));
        		if($cellData){
        			$cluster = $cellData['cluster'];
             		$siteType = $cellData['siteType'];
             		$siteNameChinese = $cellData['siteNameChinese'];
             		$cellNameChinese = $cellData['cellNameChinese'];
             		$coverage = $cellData['覆盖属性'];
             		$ecgi = $cellData['ecgi'];
        		}else{
        			$cluster = "无数据";
             		$siteType = "无数据";
             		$siteNameChinese = "无数据";
             		$coverage = "无数据";
             		$cellNameChinese = "无数据";
             		$ecgi = "无数据";
        		}	
        		if($this->timeDim=="day"){
                     
                            array_splice($rows, 3, 0, $cellNameChinese);
                            array_splice($rows, 4, 0, $coverage);
                            array_splice($rows, 5, 0, $ecgi);
        					array_splice($rows, 7, 0, $cluster);
                            array_splice($rows, 8, 0, $siteType);
                            array_splice($rows, 9, 0, $siteNameChinese);

                            array_splice($keys, 3, 0, 'cellNameChinese');
                            array_splice($keys, 4, 0, '覆盖属性');
                            array_splice($keys, 5, 0, 'ecgi');
                            array_splice($keys, 7, 0, 'cluster');
                            array_splice($keys, 8, 0, 'siteType');
                            array_splice($keys, 9, 0, 'siteNameChinese');

        		}elseif($this->timeDim=="hour"||$this->timeDim=="hourgroup"){
        			 		array_splice($rows, 4, 0, $cellNameChinese);
                            array_splice($rows, 5, 0, $coverage);
                            array_splice($rows, 6, 0, $ecgi);
        					array_splice($rows, 8, 0, $cluster);
                            array_splice($rows, 9, 0, $siteType);
                            array_splice($rows, 10, 0, $siteNameChinese);

                            array_splice($keys, 4, 0, 'cellNameChinese');
                            array_splice($keys, 5, 0, '覆盖属性');
                            array_splice($keys, 6, 0, 'ecgi');
                            array_splice($keys, 8, 0, 'cluster');
                            array_splice($keys, 9, 0, 'siteType');
                            array_splice($keys, 10, 0, 'siteNameChinese');

        		}elseif($this->timeDim=="quarter"){
        					array_splice($rows, 5, 0, $cellNameChinese);
                            array_splice($rows, 6, 0, $coverage);
                            array_splice($rows, 7, 0, $ecgi);
        					array_splice($rows, 9, 0, $cluster);
                            array_splice($rows, 10, 0, $siteType);
                            array_splice($rows, 11, 0, $siteNameChinese);

                            array_splice($keys, 5, 0, 'cellNameChinese');
                            array_splice($keys, 6, 0, '覆盖属性');
                            array_splice($keys, 7, 0, 'ecgi');
                            array_splice($keys, 9, 0, 'cluster');
                            array_splice($keys, 10, 0, 'siteType');
                            array_splice($keys, 11, 0, 'siteNameChinese');
        		}


        	}
        		$newData[] = array_combine($keys, $rows);

        }


        $fileName = $this->download($newData,implode(",", $keys));
         //显示数据显示1000条
        $arr = array_slice($newData, 0, 1000);
        return array("data"=>$arr,"file"=>$fileName);
 
    
    	
    }
    
}
