<?php
namespace Remoblaser\EasyPackage\Composer;

use Illuminate\Filesystem\Filesystem;

class ComposerFile {

    protected $files;

    protected $filePath;

    protected $jsonContent;

    function __construct()
    {
        $this->files = new Filesystem();
        $this->filePath =  base_path() . "/composer.json";

        $this->readComposerJson();
    }

    private function readComposerJson()
    {
        $rawJsonFile = $this->files->get($this->filePath);
        $this->jsonContent = json_decode($rawJsonFile);
    }

    public function getJson()
    {
        return $this->jsonContent;
    }

    public function getRequiredPackages()
    {
        $packages = array();
        foreach($this->jsonContent->require as $package => $version)
        {
            $packages[] = $package;
        }

        return $packages;
    }

    public function getRequiredDevPackages()
    {
        return $this->jsonContent->require-dev;
    }


} 