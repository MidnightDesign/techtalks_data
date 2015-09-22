<?php

namespace Lighwand\Validate;

use League\Flysystem\File as LeagueFile;

class File extends LeagueFile
{
    public function getFileName()
    {
        $parts = explode('.', $this->getBaseName());
        array_pop($parts);
        return join('.', $parts);
    }

    /**
     * @return string
     */
    public function getBaseName()
    {
        $parts = explode('/', $this->path);
        return array_pop($parts);
    }
}
