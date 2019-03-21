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
use App\Http\Controllers\KpiNbmQueryHandler;
class DataController extends Controller 
{
	public function getQatData(Request $request) {

		$type = Input::get("dataType");
		$datasoure = Input::get("dataSource");
		switch($datasoure) {
			case "ENIQ":
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
				break;
			case "NBM":
				$query = new NbmQueryHandler();
				break;
			case "ALARM":
				$query = new AlarmQueryHandler();
				break;
		}

		$result = $query->templateQuery($request);
		return $result;
	}
}
