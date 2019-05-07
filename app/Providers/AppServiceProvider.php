<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */

	public function boot() {
		$cached_settings = Cache::remember('settings', 3600, function () {
			return Setting::pluck('value', 'name')->toArray();
		});
		// dump($cached_settings);
		Config::set('srtpl.settings', $cached_settings);
		Schema::defaultStringLength(191);
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register() {
		//

		$this->app->bind('path.public', function() {
        return base_path('public_html');
    });

	}

}
