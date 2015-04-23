<?php namespace Remoblaser\LazyArtisan\Composer;

class ComposerPackage {

    protected $vendorPath;

    protected $packageName;

    protected $packagePath;

    function __construct($vendor, $name)
    {
        $this->packageName = $vendor . '/' . $name;
        $this->vendorPath = base_path() . "/vendor";

        $this->packagePath = $this->vendorPath . '/' . $this->packageName . '/';
    }

    public function getPath()
    {
        return $this->packagePath;
    }
} 