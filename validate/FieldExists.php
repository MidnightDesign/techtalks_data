<?php

namespace Lighwand\Validate;

use Zend\Validator\AbstractValidator;
use Zend\Validator\Exception;

class FieldExists extends AbstractValidator
{
    const DOES_NOT_EXIST = 'doesNotExist';
    /** @var array */
    protected $messageTemplates = [
        self::DOES_NOT_EXIST => '"%file%" is missing the required field "%fieldName%".',
    ];
    protected $messageVariables = [
        'file' => 'file',
        'fieldName' => 'fieldName',
    ];
    /** @var string */
    protected $file;
    /** @var string */
    protected $fieldName;

    /**
     * @param string $fieldName
     */
    public function __construct($fieldName)
    {
        parent::__construct(['field_name' => $fieldName]);
    }

    /**
     * Returns true if and only if $value meets the validation requirements
     *
     * If $value fails validation, then this method returns false, and
     * getMessages() will return an array of messages that explain why the
     * validation failed.
     *
     * @param  mixed $value
     * @return bool
     * @throws Exception\RuntimeException If validation of $value is impossible
     */
    public function isValid($value)
    {
        $fieldName = $this->getOption('field_name');
        if (empty($value['data'][$fieldName])) {
            $this->file = $value['path'];
            $this->fieldName = $fieldName;
            $this->error(self::DOES_NOT_EXIST);
            return false;
        }
        return true;
    }
}
