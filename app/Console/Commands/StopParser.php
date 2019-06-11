<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class StopParser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stopParser {taskName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'stop parser';

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
        $command = 'sudo ssh '.getenv('MPARSER_USERNAME').'@'.getenv('MPARSER_HOST').' sh '.getenv('APP_LOCAL_URL').'public/common/sh/stop_monitor.sh '.$this->argument('taskName').'"';
        system($command);
    }
}
