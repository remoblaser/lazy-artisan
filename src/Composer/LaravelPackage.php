<?php namespace Remoblaser\LazyArtisan\Composer;

use Illuminate\Filesystem\Filesystem;
use Remoblaser\LazyArtisan\PhpFileReflector;

class LaravelPackage extends ComposerPackage {

    protected $files;

    function __construct($vendor, $name)
    {
        $this->files = new Filesystem();
        parent::__construct($vendor, $name);
    }

    public static function withFullPackageName($packageName) {
        $packageParts = explode('/', $packageName);
        
        $instance = new self($packageParts[0], $packageParts[1]);

        return $instance;
    }

    public function getFacades()
    {
        $packageFiles = $this->files->allFiles($this->packagePath);

        $facades = array();

        foreach($packageFiles as $file)
        {
            if($this->isFacade($file))
                $facades[] = $file;
        }

        return $facades;
    }

    public function getServiceProviders()
    {
        $packageFiles = $this->files->allFiles($this->packagePath);

        $serviceProviders = array();

        foreach($packageFiles as $file)
        {
            if($this->isServiceProvider($file))
                $serviceProviders[] = $file;
        }

        return $serviceProviders;
    }

    private function isServiceProvider($file)
    {
        return ends_with($file, 'ServiceProvider.php');
    }

    private function isFacade($file)
    {
        $reflector = new PhpFileReflector($file->getContents());

        return ($reflector->getExtendedClass() == "Facade");

    }
} 