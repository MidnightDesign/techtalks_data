<?php

namespace Lighwand\Validate;

use LogicException;

trait DataExtractorAwareTrait
{
    /** @var DataExtractor */
    private $dataExtractor;

    /**
     * @param File $file
     * @return array
     */
    protected function getData(File $file)
    {
        if (!$this->dataExtractor) {
            throw new LogicException('No data extractor was set.');
        }
        return $this->dataExtractor->extract($file);
    }
}
