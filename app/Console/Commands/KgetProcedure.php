<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class KgetProcedure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kgetProcedure {taskName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'run kget procedure';

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
        $command = 'sudo ssh '.getenv('MPARSER_USERNAME').'@'.getenv('MPARSER_HOST').' "cd /opt/gback/gprocedure/KGET ; sh kgetProcedure.sh '.$this->argument('taskName').'"';
        system($command);
    }
}
