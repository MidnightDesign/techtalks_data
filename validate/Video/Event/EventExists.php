<?php

namespace Lighwand\Validate\Video\Event;

use Lighwand\Validate\DataExtractor;
use Lighwand\Validate\DataExtractorAwareTrait;
use Lighwand\Validate\File;
use Lighwand\Validate\Loader\EventLoader;
use Zend\Validator\AbstractValidator;
use Zend\Validator\Exception;

class EventExists extends AbstractValidator
{
    use DataExtractorAwareTrait;
    const DOES_NOT_EXIST = 'doesNotExist';
    protected $messageTemplates = [
        self::DOES_NOT_EXIST => 'The event "%id%" referenced in %path% does not exist.',
    ];
    protected $messageVariables = [
        'path' => 'path',
        'id' => 'id',
    ];
    /** @var string */
    protected $path;
    /** @var string */
    protected $id;
    /** @var EventLoader */
    private $eventLoader;

    /**
     * EventExists constructor.
     *
     * @param EventLoader   $eventLoader
     * @param DataExtractor $dataExtractor
     */
    public function __construct(EventLoader $eventLoader, DataExtractor $dataExtractor)
    {
        parent::__construct();
        $this->eventLoader = $eventLoader;
        $this->dataExtractor = $dataExtractor;
    }

    /**
     * @param File $file
     * @return bool
     * @throws Exception\RuntimeException If validation of $value is impossible
     */
    public function isValid($file)
    {
        $eventId = $this->getData($file)['event'];
        if (!$this->eventLoader->exists($eventId)) {
            $this->id = $eventId;
            $this->path = $file['path'];
            $this->error(self::DOES_NOT_EXIST);
            return false;
        }
        return true;
    }
}
