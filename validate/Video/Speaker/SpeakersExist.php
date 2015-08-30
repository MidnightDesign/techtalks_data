<?php

namespace Lighwand\Validate\Video\Speaker;

use Lighwand\Validate\Loader\SpeakerLoader;
use Zend\Validator\AbstractValidator;
use Zend\Validator\Exception;

class SpeakersExist extends AbstractValidator
{
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
     */
    public function __construct(SpeakerLoader $speakerLoader)
    {
        parent::__construct();
        $this->speakerLoader = $speakerLoader;
    }

    /**
     * @param  array $value
     * @return bool
     * @throws Exception\RuntimeException If validation of $value is impossible
     */
    public function isValid($value)
    {
        $isValid = true;
        foreach ($value['data']['speakers'] as $speakerId) {
            if (!$this->speakerLoader->exists($speakerId)) {
                $this->id = $speakerId;
                $this->path = $value['path'];
                $this->error(self::DOES_NOT_EXIST);
                $isValid = false;
            }
        }
        return $isValid;
    }
}
