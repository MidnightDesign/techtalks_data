<?php

namespace Lighwand\Validate\Video\Duration;

use Lighwand\Validate\DataExtractor;
use Lighwand\Validate\DataExtractorAwareTrait;
use Lighwand\Validate\File;
use Zend\Validator\AbstractValidator;
use Zend\Validator\Exception;

class DurationFormat extends AbstractValidator
{
    use DataExtractorAwareTrait;
    const IS_NOT_VALID = 'doesNotExist';
    protected $messageTemplates = [
        self::IS_NOT_VALID => '"%duration%" is not a valid duration in "%path%".',
    ];
    protected $messageVariables = [
        'path' => 'path',
        'duration' => 'duration',
    ];
    /** @var string */
    protected $path;
    /** @var string */
    protected $duration;

    /**
     * SpeakersExist constructor.
     *
     * @param DataExtractor $dataExtractor
     */
    public function __construct(DataExtractor $dataExtractor)
    {
        parent::__construct();
        $this->dataExtractor = $dataExtractor;
    }

    /**
     * @param File $file
     * @return bool
     * @throws Exception\RuntimeException If validation of $value is impossible
     */
    public function isValid($file)
    {
        $duration = $this->getData($file)['duration'];
        if (preg_match('/^(([0-9]+m)|(\d+h)?(([0-5]?[0-9])m))?([0-5]?[0-9])s$/', $duration) === 0) {
            $this->duration = $duration;
            $this->path = $file->getPath();
            $this->error(self::IS_NOT_VALID);
            return false;
        }
        return true;
    }
}
