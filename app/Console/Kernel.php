<?php namespace App\Console;

use App\Libraries\EmailsManager;
use App\Http\Controllers\TicketsController;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		'App\Console\Commands\Inspire',
		'App\Console\Commands\ImportCommand'
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		$schedule->call(function () {

			$tickets = TicketsController::API()->all([
				'where' => ['deadline|<|0','deadline|>|-60'],
				'paginate' => 'false'
			]);

			foreach ($tickets as $ticket) {
				EmailsManager::sendEscalation($ticket->id);
			}
			
        })->everyMinute();
	}
}