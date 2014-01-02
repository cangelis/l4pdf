<?php namespace CanGelis\L4pdf;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Config;

class ServiceProvider extends IlluminateServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('cangelis/l4pdf');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['pdf'] = $this->app->share(function($app)
		{
			return new PDF(Config::get('l4pdf::executable'), storage_path());
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('pdf');
	}

}