<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SiteLte;
use Illuminate\Support\Facades\Redis;
class MongsSiteLte extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'MongsSiteLte:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '备份mongs的siteLte到redis';

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
        $result = SiteLte::select('cellName','siteName','cluster','siteType','siteNameChinese','覆盖属性','cellNameChinese','ecgi')->get()->toArray();

        foreach ($result as $key => $value) {
            Redis::set("siteLte_".$value['cellName'],serialize(array(
                'cellNameChinese'=>$value['cellNameChinese'],
                'cluster'=>$value['cluster'],
                'siteNameChinese'=>$value['siteNameChinese'],
                'ecgi'=>$value['ecgi'],
                'siteType'=>$value['siteType'],
                '覆盖属性'=>$value['覆盖属性'])));
            Redis::set("siteLte_".$value['siteName'],serialize(array(
                'cluster'=>$value['cluster'],
                'siteType'=>$value['siteType'],
                'siteNameChinese'=>$value['siteNameChinese'],
                '覆盖属性'=>$value['覆盖属性'])));
        }

    }
}
