<?php namespace Remoblaser\LazyArtisan\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Remoblaser\LazyArtisan\Composer\ComposerFile;
use Remoblaser\LazyArtisan\Composer\LaravelPackage;
use Remoblaser\LazyArtisan\PhpFileReflector;

class GenerateFacadesCommand extends Command {
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = "generate:facades";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Automatically add Facades of your packages";

    protected $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    public function fire()
    {
        $this->info('Adding missing Facades to config...');

        $facades = $this->getFacadesFromPackages();
        $purged = $this->removeDefaultFacades($facades);

        $this->writeAppConfigFile($purged);

        $this->info('Added Facades!');
    }

    private function removeDefaultFacades($facades)
    {
        $purged = [];
        foreach($facades as $facade)
        {
            if(!starts_with($facade, 'Illuminate'))
                $purged[] = $facade;
        }

        return $purged;
    }

    private function getFacadesFromPackages()
    {
        $file = new ComposerFile();
        $packageNames = $file->getRequiredPackages();


        $facades = [];
        foreach($packageNames as $packageName)
        {
            $facades = array_merge($facades, $this->getFacadeFrom($packageName));
        }

        return $facades;
    }

    private function getFacadeFrom($packageName)
    {
        $package = LaravelPackage::withFullPackageName($packageName);
        $facades = array();

        foreach($package->getFacades() as $facade) {
            $facades[] = $this->getFullClassFromFacade($facade);
        }

        return $facades;
    }

    private function getFullClassFromFacade($facade)
    {
        $reflector = new PhpFileReflector($facade->getContents());

        return $reflector->getFullClassName();
    }

    private function writeAppConfigFile($facades)
    {
        $appConfig = "";

        $configPath = base_path() . '/config/app.php';
        foreach(file($configPath) as $line) {

            if(trim(preg_replace('/[\t\s]+/', '', $line)) == "'aliases'=>[") {
                $line .= $this->getWriteableFacades($facades);
            }

            $appConfig .= $line;
        }

        $this->files->put($configPath, $appConfig);
    }

    private function getWriteableFacades($facades)
    {
        $content = "";
        foreach($facades as $facade)
        {
            $classParts = explode("\\", $facade);
            $alias = $classParts[sizeof($classParts) - 1];

            $content .= "\t\t" ."'" . $alias . "'\t=> '" . $facade . "'," . PHP_EOL;
        }
        return $content;
    }
} 