<?php namespace Remoblaser\LazyArtisan;

use Illuminate\Support\ServiceProvider;

class LazyArtisanServiceProvider extends ServiceProvider {

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
		$this->registerGenerateProvidersCommand();
		$this->registerGenerateFacadesCommand();
	}

	private function registerGenerateProvidersCommand()
	{
		$this->app->singleton('command.remoblaser.generateproviders', function($app) {
			return $app['Remoblaser\LazyArtisan\Commands\GenerateProvidersCommand'];
		});

		$this->commands('command.remoblaser.generateproviders');
	}

	private function registerGenerateFacadesCommand()
	{
		$this->app->singleton('command.remoblaser.generatefacades', function($app) {
			return $app['Remoblaser\LazyArtisan\Commands\GenerateFacadesCommand'];
		});

		$this->commands('command.remoblaser.generatefacades');
	}

}
