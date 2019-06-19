<?php
/** 
* 站点管理
* 
* @author zhujiaojiao
* @version 0.0   
*/
namespace App\Http\Controllers\SiteManage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Common\DataBaseConnection;
use App\Models\Mongs\SiteLte;
use PDOException;
use App\Models\COLUMNS;

/** 
* 站点管理控制器
* 
* @author zhujiaojiao      
* @version 0.0  
*/
class SiteController extends Controller 
{
	private $dbc;
	public function __construct() {

        $this->dbc  = new DataBaseConnection();

    }
	/**  
    * 获取站点信息
    * 
    * @access public
    * @param  string $currentPage
    * @param  string $pageSize
    * @param  string $city
    * @return array
    */
	public function query() {
		$currentPage   = Input::get('currentPage') == '' ? 1 : Input::get('currentPage');
        $pageSize      = Input::get('pageSize') == '' ? 5 : Input::get('pageSize');
        $offset        = ($currentPage - 1) * $pageSize;
		
		$city          = $this->dbc->getConnName(Input::get('city'));
        $query         = SiteLte::where('city',$city);

        $result["total"] = $query->count();
        $data            = $query->offset($offset)->limit($pageSize)->get();
        $result['data']  =  $data;
        return $result;
	}
	/**  
    * 站点信息上传
    * 
    * @access public
    * @param string $importType
    * @param string $city
    * @return void
    */
	public function upload(Request $request) {
        //print_r(ini_get('post_max_size'));
		$city = $this->dbc->getConnName(Input::get('city'));
        $importType = Input::get('importType');
		$file     = $request->file('file');

		$realPath = $file->getRealPath();
        $fh       =fopen($realPath,"r");
        $title    = implode(",", fgetcsv($fh));

        $path = Storage::putFileAs('site', $file, $file->getClientOriginalName());
        $realPath = env('MYSQL_STORAGE_URL').$path;
        
        if ($importType == 'replace') {
        	SiteLte::where('city', $city)->delete();
        }
        $sql = "LOAD DATA INFILE '$realPath' INTO TABLE siteLte character set UTF8 FIELDS terminated by ',' enclosed by '\"' LINES TERMINATED BY '\n' IGNORE 1 LINES($title) set city = '$city',importDate = '".date('Ymd')."'";
        $db     = DB::connection('mongs')->getPdo();
        try {
            $db->exec($sql);
        } catch(PDOException $e){
           return $e->getMessage();
        }
       
	}
	/**  
    * 站点信息下载
    * 
    * @access public
    * @param string $city
    * @return string $fileName
    */
    public function export() {

        $city          = $this->dbc->getConnName(Input::get('city'));

        $params        = COLUMNS::selectRaw('concat(\'"\',GROUP_CONCAT(COLUMN_NAME SEPARATOR \'","\'),\'"\') as params')->where('TABLE_SCHEMA','=','mongs')->where('TABLE_NAME','=','siteLte')->first()->toArray();
        $title         = 'select '.$params['params'].' union all ';

        $bindParams    = [];
        $query         = SiteLte::where('city',$city);
        $bindParams[]  = $city;
        $sql           = $query->toSql();
        $filePath      = '../QAT/';
        $fileName      = "files/".$city. "_".date('YmdHis').".csv";
        $sql           = $title.$sql." INTO OUTFILE '".$filePath.$fileName."'
                        FIELDS TERMINATED BY ',' 
                        OPTIONALLY ENCLOSED BY '\"' 
                        LINES TERMINATED BY '\\n'";
        $db           = DB::connection('mongs')->getPdo();
        $stmt         = $db->prepare($sql);
        if ($stmt->execute($bindParams)) {
            return $fileName;
        }
    }
}
