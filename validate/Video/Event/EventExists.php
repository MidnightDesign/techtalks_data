<?php

namespace Lighwand\Validate\Video\Event;

use Lighwand\Validate\Loader\EventLoader;
use Zend\Validator\AbstractValidator;
use Zend\Validator\Exception;

class EventExists extends AbstractValidator
{
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
     * @param EventLoader $eventLoader
     */
    public function __construct(EventLoader $eventLoader)
    {
        parent::__construct();
        $this->eventLoader = $eventLoader;
    }

    /**
     * @param  array $value
     * @return bool
     * @throws Exception\RuntimeException If validation of $value is impossible
     */
    public function isValid($value)
    {
        $eventId = $value['data']['event'];
        if (!$this->eventLoader->exists($eventId)) {
            $this->id = $eventId;
            $this->path = $value['path'];
            $this->error(self::DOES_NOT_EXIST);
            return false;
        }
        return true;
    }
}
