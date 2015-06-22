<?php

namespace TechtalksTest;

use League\Flysystem\Filesystem;

class EventTest
{
    /**
     * @var Filesystem
     */
    private $fs;

    /**
     * Video constructor.
     *
     * @param Filesystem $fs
     */
    public function __construct(Filesystem $fs)
    {
        $this->fs = $fs;
    }
}