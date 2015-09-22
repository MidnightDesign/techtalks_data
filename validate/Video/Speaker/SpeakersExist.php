<?php

namespace Lighwand\Validate\Video\Speaker;

use Lighwand\Validate\DataExtractor;
use Lighwand\Validate\DataExtractorAwareTrait;
use Lighwand\Validate\File;
use Lighwand\Validate\Loader\SpeakerLoader;
use Zend\Validator\AbstractValidator;
use Zend\Validator\Exception;

class SpeakersExist extends AbstractValidator
{
    use DataExtractorAwareTrait;
    const DOES_NOT_EXIST = 'doesNotExist';
    protected $messageTemplates = [
        self::DOES_NOT_EXIST => 'The speaker "%id%" referenced in %path% does not exist.',
    ];
    protected $messageVariables = [
        'path' => 'path',
        'id' => 'id',
    ];
    /** @var string */
    protected $path;
    /** @var string */
    protected $id;
    /** @var SpeakerLoader */
    private $speakerLoader;

    /**
     * SpeakersExist constructor.
     *
     * @param SpeakerLoader $speakerLoader
     * @param DataExtractor $dataExtractor
     */
    public function __construct(SpeakerLoader $speakerLoader, DataExtractor $dataExtractor)
    {
        parent::__construct();
        $this->speakerLoader = $speakerLoader;
        $this->dataExtractor = $dataExtractor;
    }

    /**
     * @param File $file
     * @return bool
     * @throws Exception\RuntimeException If validation of $value is impossible
     */
    public function isValid($file)
    {
        $data = $this->getData($file);
        $isValid = true;
        foreach ($data['speakers'] as $speakerId) {
            if (!$this->speakerLoader->exists($speakerId)) {
                $this->id = $speakerId;
                $this->path = $file['path'];
                $this->error(self::DOES_NOT_EXIST);
                $isValid = false;
            }
        }
        return $isValid;
    }
}
