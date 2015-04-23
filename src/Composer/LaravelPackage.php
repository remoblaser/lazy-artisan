<?php namespace Remoblaser\EasyPackage\Composer;

use Illuminate\Filesystem\Filesystem;

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

    public function getFacade()
    {

    }
} 