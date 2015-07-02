<?php

namespace Lighwand\Validate\Video\Id;

use Zend\Validator\AbstractValidator;
use Zend\Validator\Exception;

class IdExists extends AbstractValidator
{
    const DOES_NOT_EXIST = 'doesNotExist';

    protected $messageTemplates = [
        self::DOES_NOT_EXIST => "%file% does not have an ID.",
    ];

    protected $messageVariables = [
        'file' => 'file'
    ];

    /** @var string */
    protected $file;

    public function isValid($value)
    {
        if (empty($value['data']['id'])) {
            $this->file = $value['path'];
            $this->error(self::DOES_NOT_EXIST);
            return false;
        }
        return true;
    }
}