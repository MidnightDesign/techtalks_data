<?php

namespace Lighwand\Validate\Loader;

interface LoaderInterface
{
    /**
     * @param string $speakerId
     * @return boolean
     */
    public function exists($speakerId);
}
