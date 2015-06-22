<?php

namespace TechtalksTest;

use League\Flysystem\Filesystem;

abstract class AbstractLoader
{
    /**
     * @var Filesystem
     */
    protected $fs;
    /**
     * @var array
     */
    private $files;

    /**
     * @param Filesystem $fs
     */
    public function __construct(Filesystem $fs)
    {
        $this->fs = $fs;
    }

    /**
     * @return array
     */
    public function getFiles()
    {
        if (!$this->files) {
            $this->files = $this->fs->listFiles($this->getDirectory(), true);
        }
        return $this->files;
    }

    abstract protected function getDirectory();
}