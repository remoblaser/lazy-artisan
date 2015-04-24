<?php namespace Remoblaser\LazyArtisan;

class PhpFileReflector {
    const NAMESPACE_REGEX = '/namespace\s+(.*)?\;/';
    const CLASSNAME_REGEX = '/class\s+(\w+)(.*)?\{/';
    const EXTENDS_REGEX = '/extends\s+(\w+)(.*)?\{/';

    protected $fileContents;

    function __construct($fileContents)
    {
        $this->fileContents = $fileContents;
    }

    public function getClassName()
    {
        if (preg_match(self::CLASSNAME_REGEX, $this->fileContents, $matches)) {
            return $matches[1];
        }
    }

    public function getNameSpace()
    {
        if (preg_match(self::NAMESPACE_REGEX, $this->fileContents, $matches)) {
            return $matches[1];
        }
    }

    public function getFullClassName()
    {
        return $this->getNameSpace() . '\\'  . $this->getClassName();
    }

    public function getExtendedClass()
    {
        if (preg_match(self::EXTENDS_REGEX, $this->fileContents, $matches)) {
            return $matches[1];
        }
    }


} 