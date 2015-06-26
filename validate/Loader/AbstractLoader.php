<?php

namespace Lighwand\Validate\Loader;

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
            foreach ($this->files as &$file) {
                $this->addData($file);
            }
        }
        return $this->files;
    }

    abstract protected function getDirectory();

    /**
     * @param string $id
     * @return boolean
     */
    public function exists($id)
    {
        $files = $this->getFiles();
        foreach ($files as $file) {
            if ($file['filename'] === $id) {
                return true;
            }
        }
        return false;
    }

    /**
     * Populates the "data" key with the file's JSON data
     *
     * @param array $file
     */
    private function addData(array &$file)
    {
        $data = json_decode($this->fs->read($file['path']), true);
        if ($data === null) {
            throw new \DomainException(
                sprintf('%s is not a valid JSON file.', $file['path'])
            );
        }
        $file['data'] = $data;
    }
}