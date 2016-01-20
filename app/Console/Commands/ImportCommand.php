<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Libraries\ImportManager;

class ImportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import {target=all} {--d|--debug} {--s|--straight}';

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
        $option = $this->argument('target');
        $debug = $this->option('debug');
        $direct = $this->option('straight');

        $a = new ImportManager();
        $a->import($option,$debug,$direct);

    }
}
