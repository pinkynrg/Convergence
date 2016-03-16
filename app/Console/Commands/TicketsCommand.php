<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\TicketsController;

class TicketsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tickets {how}';

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
        if ($this->argument('how') == 'escalating') {
            
            $tickets = TicketsController::API()->all([]);

        }
        // $debug = $this->option('debug');
        // $direct = $this->option('straight');
        // $a = new ImportManager();
        // $a->import($option,$debug,$direct);

    }
}
