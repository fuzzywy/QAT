<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MParser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mparser {type} {tracePath} {taskName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'mparser';

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
        $command = 'sudo ssh '.getenv('MPARSER_USERNAME').'@'.getenv('MPARSER_HOST').' "cd /opt/gback/mparser/scripts ; ./parser.sh -t '.$this->argument('type').' -r '.$this->argument('tracePath').' -d '.$this->argument('taskName').'"';
        system($command);
    }
}
