<?php

namespace Lighwand\Validate;

use Zend\Validator\AbstractValidator;
use Zend\Validator\Exception;

class IdMatchesFileName extends AbstractValidator
{
    use DataExtractorAwareTrait;
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

    public function __construct(DataExtractor $dataExtractor)
    {
        $this->dataExtractor = $dataExtractor;
        parent::__construct();
    }

    /**
     * @param File $file
     * @return bool
     */
    public function isValid($file)
    {
        if ($this->getData($file)['id'] . '.json' !== $file->getBaseName()) {
            $this->path = $file->getPath();
            $this->id = $this->getData($file)['id'];
            $this->error(self::DOES_NOT_MATCH);
            return false;
        }
        return true;
    }
}
