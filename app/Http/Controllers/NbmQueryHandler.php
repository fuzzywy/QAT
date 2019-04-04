<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Common\DataBaseConnection;
use Illuminate\Http\Request;
use PDO;

class NbmQueryHandler extends Controller
{
    use KpiNbmQueryHandler;

    public function templateQuery(Request $request)
    {
    	$this->init($request);
    	$sql = $this->createsql();
    	$dc = new DataBaseConnection();
    	$pdo = $dc->getDB("nbm");
    	$row = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
         //导出数据限制100万条
        $data = array_slice($row, 0, 1000000);
    	$fileName = $this->download($data, $this->sql->resultText);
         //显示数据显示1000条
        $arr = array_slice($row, 0, 1000);
    	return  array("data"=>$arr,"file"=>$fileName);
    }
}
