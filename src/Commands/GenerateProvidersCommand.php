<?php namespace Remoblaser\LazyArtisan\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Remoblaser\LazyArtisan\Composer\ComposerFile;
use Remoblaser\LazyArtisan\Composer\LaravelPackage;
use Remoblaser\LazyArtisan\ServiceProviderReflector;

class GenerateProvidersCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = "generate:providers";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Automatically add ServiceProviders of your packages";

    protected $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }


    public function fire()
    {
        $this->info('Adding missing ServiceProviders...');

        $packageServiceProviders = $this->getServiceProvidersFromPackages();
        $providersForConfig = $this->mergeProvidersWithConfig($packageServiceProviders);
        $this->writeAppConfigFile($providersForConfig);

        $this->info('Added ServiceProviders!');
    }

    private function getServiceProvidersFromPackages()
    {
        $file = new ComposerFile();
        $packageNames = $file->getRequiredPackages();


        $serviceProviders = [];
        foreach($packageNames as $packageName)
        {
            $serviceProviders = array_merge($serviceProviders, $this->getServiceProvidersFrom($packageName));
        }

        return $serviceProviders;
    }


    private function mergeProvidersWithConfig($providers)
    {
        $loadedProviders = Config::get('app.providers');

        $difference = array_diff($providers, $loadedProviders);

        $purged = $this->removeUnnecessaryLaravelProviders($difference);

        return array_merge($loadedProviders, $purged);
    }

    private function getServiceProvidersFrom($packageName)
    {
        $package = LaravelPackage::withFullPackageName($packageName);
        $serviceProviders = array();

        foreach($package->getServiceProviders() as $serviceProvider) {
            $serviceProviders[] = $this->getFullClassFromProvider($serviceProvider);
        }

        return $serviceProviders;
    }

    private function getFullClassFromProvider($serviceProvider)
    {
        $reflector = new ServiceProviderReflector($serviceProvider->getContents());

        return $reflector->getFullClassName();
    }

    private function removeUnnecessaryLaravelProviders($providers)
    {
        $purged = array();
        //TODO: find a better way to handle this
        foreach($providers as $provider)
        {
            if(!starts_with($provider, 'Illuminate'))
                $purged[] = $provider;
        }

        return $purged;
    }

    public function writeAppConfigFile($serviceProviders)
    {
        $oldProviders = Config::get('app.providers');
        $configPath = base_path() . '/config/app.php';
        $this->files->put($configPath, str_replace($oldProviders, $serviceProviders, $this->files->get($configPath)));

    }




} 