<?php
namespace App\Console;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {
	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		Commands\ClearAll::class,
		Commands\NewUser::class,
		Commands\BroadcastEmails::class,
		Commands\SendEmailIfNotAccessedCoachFeedback::class,
		Commands\EmailBefore24HoursOfSession::class,
		Commands\MonthlyEmailActivity::class,
		Commands\CheckSubscriptionPlan::class,
		Commands\UserInactive::class,
		Commands\UserNotBookSchedule::class,
		Commands\UserNotActive::class,
		Commands\LifeStoreyUpdateNotify::class,
		Commands\CompletedSessions::class,
		Commands\GratuateUserMonthlyActivity::class,
		Commands\Useraddedbyadmin::class,
		Commands\CoachNextMonthScheduledWeek::class,
		Commands\createmeeting::class,
		Commands\CoachCreditPayment::class,
        Commands\ChargeClientManagers::class,
        Commands\UpdateClientCredits::class,
		Commands\TestCommand::class
	];
	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule) {
		// $schedule->command('inspire')
		//          ->hourly();
		$schedule->command('email:broadcastEmail')->everyMinute();
         //set to commit there is error in cron
		//$schedule->command('email:email-if-not-accessed-coach-feedback')->everyFiveMinutes();
		$schedule->command('email:email-before-24-hours-of_session')->everyMinute();
		$schedule->command('email:monthly-email-activity')->monthlyOn(1, '09:00');
		// $schedule->command('email:monthly-email-activity')->dailyAt('13:21');
		//$schedule->command('email:monthly-email-activity')->everyFiveMinutes();
		// $schedule->command('subscribe:check-subscription-plan')->everyMinute();
		$schedule->command('subscribe:check-subscription-plan')->everyThirtyMinutes();
		$schedule->command('email:user-inactive')->dailyAt('3:00');
		$schedule->command('email:user-notbooked-schedule')->dailyAt('3:00');
		$schedule->command('email:user-notactive')->dailyAt('3:00');
		$schedule->command('email:lifestorey-update-notify')->dailyAt('3:00');
		$schedule->command('email:email-if-not-accessed-coach-feedback')->dailyAt('3:00');
		$schedule->command('command:completed-sessions')->hourlyAt(20);
		//$schedule->command('command:completed-sessions')->everyMinute();
		$schedule->command('command:gratuate-user-monthly-activity')->dailyAt('3:00');

		$schedule->command('command:coach-next-month-scheduled-Week')->monthlyOn(1, '09:00');
		$schedule->command('subscribe:addedbyadmin')->everyThirtyMinutes();
		$schedule->command('command:createmeeting')->everyMinute();
		$schedule->command('command:coaching-credit-payment-using-paypal-to-coach')->everyMinute();

        $schedule->command('command:charge-client-managers')->monthlyOn(27, '09:00');
        $schedule->command('command:update-client-credits')->monthlyOn(27, '09:00');
		//$schedule->command('test:ToCheckMailWorks')->everyMinute();
	}
	/**
	 * Register the Closure based commands for the application.
	 *
	 * @return void
	 */
	protected function commands() {
		require base_path('routes/console.php');
	}
}
