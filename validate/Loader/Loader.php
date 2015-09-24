<?php

namespace Lighwand\Validate\Loader;

use League\Flysystem\Filesystem;
use Lighwand\Validate\File;

class Loader implements LoaderInterface
{
    /** @var Filesystem */
    protected $fs;
    /** @var string */
    private $directory;

    /**
     * @param Filesystem $fs
     * @param string     $directory
     */
    public function __construct(Filesystem $fs, $directory)
    {
        $this->fs = $fs;
        $this->directory = $directory;
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
        $fileData = $this->fs->listFiles($this->directory, true);
        $filesystem = $this->fs;
        return array_map(function ($data) use ($filesystem) {
            return new File($filesystem, $data['path']);
        }, $fileData);
    }
}
