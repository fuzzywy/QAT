<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\LteTddQueryHandler;
use App\Http\Controllers\LteFddQueryHandler;
use App\Http\Controllers\NbiotQueryHandler;
use App\Http\Controllers\NbmQueryHandler;
use App\Http\Controllers\GsmQueryHandler;
use App\Http\Controllers\KpiQueryHandler;
class DataController extends Controller 
{
	public function getQatData(Request $request) {

		$type = Input::get("dataType");

		switch ($type) {
			case 'TDD':
				$query = new LteTddQueryHandler();
				break;
			case 'FDD':
				$query = new LteFddQueryHandler();
				break;
			case 'NBIOT':
				$query = new NbiotQueryHandler();
				break;
			case 'GSM':
				$query = new GsmQueryHandler();
				break;
			
			default:
				# code...
				break;
		}
		$result = $query->templateQuery($request);

		return $result;
		print_r(input::all());
		exit;
		$subnets = input::get("subnets");

		foreach ($subnets as $key => $value) {
			if($value&&$value!=="allSelect"){
				$city = explode(":",$value);
				$newsubnets[$city[0]][]=$city[1];
			}
		}
		// print_r($newsubnets);
		// print_r(input::all());
		// exit;
		$test = new LteTddQueryHandler();
		$result = $test->templateQuery($request);
		  // print_r(serialize($result));
    //     exit;
		return $result;
		$dataSource=$dataType='sad';
		$arr = 	array(
					"data"=>array(
						array('date'=>'2016-05-03', 'name'=>'1', 'address'=>$dataSource, '这是萨达萨达是啊是大大撒大撒大撒撒打算的阿萨大啊'=>$dataSource ),
						array('date'=>'2016-05-03', 'name'=>'2', 'address'=>$dataSource, '这是萨达萨达是啊是大大撒大撒大撒撒打算的阿萨大啊'=>$dataSource ),
						array('date'=>'2016-05-03', 'name'=>'3', 'address'=>$dataSource, '这是萨达萨达是啊是大大撒大撒大撒撒打算的阿萨大啊'=>$dataSource ),
						array('date'=>'2016-05-03', 'name'=>'4', 'address'=>$dataSource, '这是萨达萨达是啊是大大撒大撒大撒撒打算的阿萨大啊'=>$dataSource ),
						array('date'=>'2016-05-03', 'name'=>'5', 'address'=>$dataSource, '这是萨达萨达是啊是大大撒大撒大撒撒打算的阿萨大啊'=>$dataSource ),
						array('date'=>'2016-05-03', 'name'=>'6', 'address'=>$dataSource, '这是萨达萨达是啊是大大撒大撒大撒撒打算的阿萨大啊'=>$dataSource ),
						array('date'=>'2016-05-03', 'name'=>'7', 'address'=>$dataSource, '这是萨达萨达是啊是大大撒大撒大撒撒打算的阿萨大啊'=>$dataSource ),
						array('date'=>'2016-05-03', 'name'=>'8', 'address'=>$dataSource, '这是萨达萨达是啊是大大撒大撒大撒撒打算的阿萨大啊'=>$dataSource ),
						array('date'=>'2016-05-03', 'name'=>'9', 'address'=>$dataSource, '这是萨达萨达是啊是大大撒大撒大撒撒打算的阿萨大啊'=>$dataSource ),
						array('date'=>'2016-05-03', 'name'=>'王小虎', 'address'=>$dataSource, '这是萨达萨达是啊是大大撒大撒大撒撒打算的阿萨大啊'=>$dataSource ),
						array('date'=>'2016-05-03', 'name'=>'王小虎', 'address'=>$dataSource, '这是萨达萨达是啊是大大撒大撒大撒撒打算的阿萨大啊'=>$dataSource ),
						array('date'=>'2016-05-03', 'name'=>'王小虎', 'address'=>$dataSource, '这是萨达萨达是啊是大大撒大撒大撒撒打算的阿萨大啊'=>$dataSource ),
						array('date'=>'2016-05-03', 'name'=>'王小虎', 'address'=>$dataSource, '这是萨达萨达是啊是大大撒大撒大撒撒打算的阿萨大啊'=>$dataSource ),
						array('date'=>'2016-05-03', 'name'=>'王小虎', 'address'=>$dataSource, '这是萨达萨达是啊是大大撒大撒大撒撒打算的阿萨大啊'=>$dataSource ),
						array('date'=>'2016-05-03', 'name'=>'王小虎', 'address'=>$dataSource, '这是萨达萨达是啊是大大撒大撒大撒撒打算的阿萨大啊'=>$dataSource ),
						array('date'=>'2016-05-03', 'name'=>'王小虎', 'address'=>$dataSource, '这是萨达萨达是啊是大大撒大撒大撒撒打算的阿萨大啊'=>$dataSource ),
						array('date'=>'2016-05-03', 'name'=>'王小虎', 'address'=>$dataSource, '这是萨达萨达是啊是大大撒大撒大撒撒打算的阿萨大啊'=>$dataSource ),
						array('date'=>'2016-05-03', 'name'=>'王小虎', 'address'=>$dataType, '这是萨达萨达是啊是大大撒大撒大撒撒打算的阿萨大啊'=>$dataSource)
						),
					"title"=>'common_files_eSRVCC提升20181228073151.csv'
					);
		// print_r($arr);
		$arr = unserialize('a:2:{s:4:"data";a:1:{i:0;a:136:{s:3:"day";s:10:"2018-12-26";s:8:"location";s:9:"changzhou";s:7:"cellNum";d:2201;s:18:"pmCellDownLockAuto";d:77;s:17:"pmCellDownLockMan";d:327;s:18:"pmCellDowntimeAuto";d:357076;s:17:"pmCellDowntimeMan";d:142554;s:15:"pmRaAttNbCbra_0";d:13190056;s:15:"pmRaAttNbCbra_1";d:17613004;s:15:"pmRaAttNbCbra_2";d:16624372;s:19:"pmRaMsg2AttNbCbra_0";d:11184689;s:19:"pmRaMsg2AttNbCbra_1";d:8926626;s:19:"pmRaMsg2AttNbCbra_2";d:7143030;s:16:"pmRaSuccNbCbra_0";d:2258140;s:16:"pmRaSuccNbCbra_1";d:371930;s:16:"pmRaSuccNbCbra_2";d:65132;s:21:"pmRrcConnEstabAttCe_0";d:501011;s:21:"pmRrcConnEstabAttCe_1";d:128785;s:21:"pmRrcConnEstabAttCe_2";d:27737;s:26:"pmRrcConnEstabAttReattCe_0";d:22450;s:26:"pmRrcConnEstabAttReattCe_1";d:4239;s:26:"pmRrcConnEstabAttReattCe_2";d:785;s:22:"pmRrcConnEstabSuccce_0";d:466696;s:22:"pmRrcConnEstabSuccce_1";d:121964;s:22:"pmRrcConnEstabSuccce_2";d:23283;s:23:"pmS1SigConnEstabAttCe_0";d:466407;s:23:"pmS1SigConnEstabAttCe_1";d:122378;s:23:"pmS1SigConnEstabAttCe_2";d:23360;s:24:"pmS1SigConnEstabSuccCe_0";d:462054;s:24:"pmS1SigConnEstabSuccCe_1";d:121217;s:24:"pmS1SigConnEstabSuccCe_2";d:23119;s:13:"pmPagReceived";d:130264;s:14:"pmPagDiscarded";d:24026;s:15:"pmRadioThpVolDl";d:73481;s:15:"pmRadioThpVolUl";d:159336;s:27:"pmRrcConnEstabFailMmeOvlMos";d:0;s:27:"pmRrcConnEstabFailMmeOvlMod";d:0;s:29:"pmS1SigConnEstabFailMmeOvlMos";d:0;s:18:"pmMacHarqDlAckBpsk";d:0;s:18:"pmMacHarqDlAckQpsk";d:339837;s:19:"pmMacHarqDlNackBpsk";d:0;s:19:"pmMacHarqDlNackQpsk";d:66759;s:21:"pmUlPathlossNbDistr_0";d:0;s:21:"pmUlPathlossNbDistr_1";d:0;s:21:"pmUlPathlossNbDistr_2";d:0;s:21:"pmUlPathlossNbDistr_3";d:0;s:21:"pmUlPathlossNbDistr_4";d:0;s:21:"pmUlPathlossNbDistr_5";d:0;s:21:"pmUlPathlossNbDistr_6";d:0;s:21:"pmUlPathlossNbDistr_7";d:0;s:21:"pmUlPathlossNbDistr_8";d:0;s:21:"pmUlPathlossNbDistr_9";d:0;s:22:"pmUlPathlossNbDistr_10";d:0;s:22:"pmUlPathlossNbDistr_11";d:10;s:22:"pmUlPathlossNbDistr_12";d:412;s:22:"pmUlPathlossNbDistr_13";d:737;s:22:"pmUlPathlossNbDistr_14";d:405;s:22:"pmUlPathlossNbDistr_15";d:504;s:22:"pmUlPathlossNbDistr_16";d:301;s:22:"pmUlPathlossNbDistr_17";d:100;s:22:"pmUlPathlossNbDistr_18";d:7;s:22:"pmUlPathlossNbDistr_19";d:0;s:22:"pmUlPathlossNbDistr_20";d:4;s:22:"pmUlPathlossNbDistr_21";d:0;s:22:"pmUlPathlossNbDistr_22";d:0;s:22:"pmUlPathlossNbDistr_23";d:0;s:22:"pmUlPathlossNbDistr_24";d:0;s:19:"pmSinrNPuschDistr_0";d:1004998;s:19:"pmSinrNPuschDistr_1";d:542501;s:19:"pmSinrNPuschDistr_2";d:1271964;s:19:"pmSinrNPuschDistr_3";d:1610058;s:19:"pmSinrNPuschDistr_4";d:1976348;s:19:"pmSinrNPuschDistr_5";d:1698234;s:19:"pmSinrNPuschDistr_6";d:1232538;s:19:"pmSinrNPuschDistr_7";d:937287;s:19:"pmSinrNPuschDistr_8";d:830142;s:17:"pmNpdcchCceUtil_0";d:32656138;s:17:"pmNpdcchCceUtil_1";d:1406392;s:17:"pmNpdcchCceUtil_2";d:888840;s:17:"pmNpdcchCceUtil_3";d:991868;s:17:"pmNpdcchCceUtil_4";d:978058;s:17:"pmNpdcchCceUtil_5";d:735479;s:17:"pmNpdcchCceUtil_6";d:617336;s:17:"pmNpdcchCceUtil_7";d:3399940;s:17:"pmNpdcchCceUtil_8";d:880751;s:17:"pmNpdcchCceUtil_9";d:496071;s:18:"pmNpdcchCceUtil_10";d:426051;s:18:"pmNpdcchCceUtil_11";d:895554;s:18:"pmNpdcchCceUtil_12";d:126041;s:18:"pmNpdcchCceUtil_13";d:78300;s:18:"pmNpdcchCceUtil_14";d:161113;s:18:"pmNpdcchCceUtil_15";d:23386;s:18:"pmNpdcchCceUtil_16";d:5242;s:18:"pmNpdcchCceUtil_17";d:2728;s:18:"pmNpdcchCceUtil_18";d:2764;s:18:"pmNpdcchCceUtil_19";d:3887;s:19:"pmNpdschUtilDistr_0";d:8140453;s:19:"pmNpdschUtilDistr_1";d:232405;s:19:"pmNpdschUtilDistr_2";d:158242;s:19:"pmNpdschUtilDistr_3";d:104910;s:19:"pmNpdschUtilDistr_4";d:55115;s:19:"pmNpdschUtilDistr_5";d:417583;s:19:"pmNpdschUtilDistr_6";d:56750;s:19:"pmNpdschUtilDistr_7";d:34409;s:19:"pmNpdschUtilDistr_8";d:32935;s:19:"pmNpdschUtilDistr_9";d:38678;s:20:"pmNpdschUtilDistr_10";d:4052;s:20:"pmNpdschUtilDistr_11";d:2803;s:20:"pmNpdschUtilDistr_12";d:2143;s:20:"pmNpdschUtilDistr_13";d:2178;s:20:"pmNpdschUtilDistr_14";d:2354;s:20:"pmNpdschUtilDistr_15";d:2021;s:20:"pmNpdschUtilDistr_16";d:1968;s:20:"pmNpdschUtilDistr_17";d:2060;s:20:"pmNpdschUtilDistr_18";d:2811;s:20:"pmNpdschUtilDistr_19";d:31251;s:19:"pmNpuschUtilDistr_0";d:23340018;s:19:"pmNpuschUtilDistr_1";d:199451;s:19:"pmNpuschUtilDistr_2";d:20092;s:19:"pmNpuschUtilDistr_3";d:452;s:19:"pmNpuschUtilDistr_4";d:15;s:19:"pmNpuschUtilDistr_5";d:5;s:19:"pmNpuschUtilDistr_6";d:0;s:19:"pmNpuschUtilDistr_7";d:0;s:19:"pmNpuschUtilDistr_8";d:0;s:19:"pmNpuschUtilDistr_9";d:0;s:20:"pmNpuschUtilDistr_10";d:0;s:20:"pmNpuschUtilDistr_11";d:0;s:20:"pmNpuschUtilDistr_12";d:0;s:20:"pmNpuschUtilDistr_13";d:0;s:20:"pmNpuschUtilDistr_14";d:0;s:20:"pmNpuschUtilDistr_15";d:0;s:20:"pmNpuschUtilDistr_16";d:0;s:20:"pmNpuschUtilDistr_17";d:0;s:20:"pmNpuschUtilDistr_18";d:0;s:20:"pmNpuschUtilDistr_19";d:0;}}s:4:"file";s:47:"common/files/NB指标模板G120181228085521.csv";}');
		print_r($arr);
		return $arr;
	}
}
