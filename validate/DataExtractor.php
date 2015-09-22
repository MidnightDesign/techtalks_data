<?php

namespace Lighwand\Validate;

use League\Flysystem\File;

/**
 * Class DataExtractor
 *
 * @package Lighwand\Validate
 */
class DataExtractor
{
    /**
     * @param File $file
     * @return array
     */
    public function extract(File $file)
    {
        return json_decode($file->read(), true);
    }
}
