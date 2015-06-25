<?php

namespace Lighwand\validate\Video\Id;

use Zend\Validator\AbstractValidator;
use Zend\Validator\Exception;

class IdMatchesFileName extends AbstractValidator
{
    const DOES_NOT_MATCH = 'doesNotMatch';

    protected $messageTemplates = [
        self::DOES_NOT_MATCH => 'File name/ID mismatch. Path: %path%. ID: %id%.',
    ];

    protected $messageVariables = [
        'path' => 'path',
        'id' => 'id',
    ];

    /** @var string */
    protected $path;

    /** @var string */
    protected $id;

    public function isValid($value)
    {
        if ($value['data']['id'] . '.json' !== $value['basename']) {
            $this->path = $value['path'];
            $this->id = $value['data']['id'];
            $this->error(self::DOES_NOT_MATCH);
            return false;
        }
        return true;
    }
}