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

			$myfile = fopen(STORAGE_FOLDER.DS."logs".DS."emails.log", "a+") or die("Unable to open file!");

			foreach ($tickets as $ticket) {
				try {
					EmailsManager::sendEscalation($ticket->id);
					fwrite($myfile, "[".date("Y-m-d H:i:s")."] Escalation email sent for ticket #".$ticket->id."\n");
				}
				catch (\Exception $e) {
					fwrite($myfile, "[".date("Y-m-d H:i:s")."] Error: Escalation email for ticket #".$ticket->id." not sent: ".$e->getMessage()."\n");
				}
			}
			
			fclose($myfile);

        })->everyMinute();
	}
}