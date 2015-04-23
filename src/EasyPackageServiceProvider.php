<?php namespace Remoblaser\EasyPackage;

use Illuminate\Support\ServiceProvider;

class EasyPackageServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerPackageManagerCommand();
	}

	private function registerPackageManagerCommand()
	{
		$this->app->singleton('command.remoblaser.managepackages', function($app) {
			return $app['Remoblaser\EasyPackage\Commands\ManagePackagesCommand'];
		});

		$this->commands('command.remoblaser.managepackages');
	}

}
