<?php

namespace TechtalksTest;

use League\Flysystem\Filesystem;

class EventLoader
{
    /**
     * @var Filesystem
     */
    private $fs;
    /**
     * @var array
     */
    private $files;

    /**
     * Video constructor.
     *
     * @param Filesystem $fs
     */
    public function __construct(Filesystem $fs)
    {
        $this->fs = $fs;
    }

    /**
     * @param string $id
     * @return boolean
     */
    public function eventExists($id)
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
     * @return array
     */
    private function getFiles()
    {
        if (!$this->files) {
            $this->files = $this->fs->listFiles('events', true);
        }
        return $this->files;
    }
}