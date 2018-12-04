<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use PDO;
use App\Http\Controllers\Common\DataBaseConnection;
use App\Models\CounterConfig;
use App\Models\CounterTables;
class LoadCounter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'LoadCounter:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->Load4GCounter("TDD");
        $this->Load4GCounter("FDD");
        $this->Load4GCounter("NBIOT");
        $this->Load2GCounter("2G");
        $this->LoadNbmCounter("nbm");
    }

    /**
     * 
     * @param   array()
     */
    public function Load4GCounter($type){

        $counters_type = "Counters_".$type;

        if(Redis::exists($counters_type)){
            Redis::del($counters_type);
        }
        $dbServer =CounterConfig::where("type","4G")->get()->toArray();
        if($dbServer){
            $host     = $dbServer[0]['host'];
            $port     = $dbServer[0]['port'];
            $dbName   = $dbServer[0]['dbName'];
            $userName = $dbServer[0]['userName'];
            $password = $dbServer[0]['password'];
            $pmDbDSN = "dblib:host=".$host.":".$port.";".((float)phpversion()>7.0?'dbName':'dbname')."=".$dbName;

            $pmDB     = new PDO($pmDbDSN, $userName, $password);
            $tables = CounterTables::where("tableType",$type)->get()->toArray();


            foreach ($tables as $key => $value) {
                $sql = "select a.name as Field from dbo.syscolumns a, dbo.sysobjects b where a.id=b.id and b.name='".$value['tableName']."_day'";
                $res = $pmDB->query($sql)->fetchALL(PDO::FETCH_ASSOC);
                foreach ($res as $row) {
                    if(stripos($row['Field'], "pm")===false){
                        continue;
                    }
                    $result[strtolower($row['Field'])]=$value['tableName'];
                }
            }
               
        }
           
        Redis::set($counters_type,serialize($result));
    }

    /**
     * 
     * @param    array()
     */
    public function Load2GCounter($type){
        $counters_type = "Counters_".$type;
        if(Redis::exists($counters_type)){
            Redis::del($counters_type);
        }
        $dbServer =CounterConfig::where("type","2G")->get()->toArray();
        if($dbServer){
            $host     = $dbServer[0]['host'];
            $port     = $dbServer[0]['port'];
            $dbName   = $dbServer[0]['dbName'];
            $userName = $dbServer[0]['userName'];
            $password = $dbServer[0]['password'];
            $pmDbDSN = "dblib:host=".$host.":".$port.";".((float)phpversion()>7.0?'dbName':'dbname')."=".$dbName;
            $pmDB     = new PDO($pmDbDSN, $userName, $password);
            $tables = CounterTables::where("tableType",$type)->get()->toArray();


            foreach ($tables as $key => $value) {
                $sql = "select a.name as Field from dbo.syscolumns a, dbo.sysobjects b where a.id=b.id and b.name='".$value['tableName']."'";
                $res = $pmDB->query($sql)->fetchALL(PDO::FETCH_ASSOC);

                foreach ($res as $row) {
                    $result[strtolower($row['Field'])]=$value['tableName'];
                }
            }
        }
           
        Redis::set($counters_type,serialize($result));
    }

    public function LoadNbmCounter($type){

        $dbc = new DataBaseConnection();
        $db = $dbc->getDB($type);
        $tables = CounterTables::where("tableType",$type)->get()->toArray();

        foreach ($tables as $key => $value) {
            $counters_type = "counters_".$value['tableName'];
            if(Redis::exists($counters_type)){
                Redis::del($counters_type);
            }
            $sql = "SELECT COLUMN_NAME Field FROM information_schema.COLUMNS WHERE table_name = '".$value['tableName']."' and table_schema ='".$type."';";
            $res = $db->query($sql)->fetchALL(PDO::FETCH_ASSOC);
            $result = array();
            foreach ($res as $row) {
               $result[$row['Field']]=$value["tableName"];
            }
             Redis::set($counters_type,serialize($result));
        }

    }
}
