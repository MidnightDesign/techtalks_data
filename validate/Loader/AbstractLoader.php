<?php

namespace Lighwand\Validate\Loader;

use League\Flysystem\Filesystem;
use Lighwand\Validate\File;

abstract class AbstractLoader
{
    /** @var Filesystem */
    protected $fs;

    /** @param Filesystem $fs */
    public function __construct(Filesystem $fs)
    {
        $this->fs = $fs;
    }

    /**
     * @param string $id
     * @return boolean
     */
    public function exists($id)
    {
        $files = $this->getFiles();
        foreach ($files as $file) {
            if ($file->getFileName() === $id) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return File[]
     */
    public function getFiles()
    {
        $fileData = $this->fs->listFiles($this->getDirectory(), true);
        $filesystem = $this->fs;
        return array_map(function ($data) use ($filesystem) {
            return new File($filesystem, $data['path']);
        }, $fileData);
    }

    abstract protected function getDirectory();
}
