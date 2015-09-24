<?php

namespace Lighwand\Validate;

use Lighwand\Validate\Loader\LoaderInterface;
use Zend\Validator\AbstractValidator;
use Zend\Validator\Exception;

class ReferenceExists extends AbstractValidator
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
    /** @var LoaderInterface */
    private $loader;
    /** @var string */
    private $fieldName;

    /**
     * ReferenceExists constructor.
     *
     * @param string          $fieldName
     * @param LoaderInterface $loader
     * @param DataExtractor   $dataExtractor
     */
    public function __construct($fieldName, LoaderInterface $loader, DataExtractor $dataExtractor)
    {
        parent::__construct();
        $this->loader = $loader;
        $this->dataExtractor = $dataExtractor;
        $this->fieldName = $fieldName;
    }

    /**
     * @param File $file
     * @return bool
     * @throws Exception\RuntimeException If validation of $value is impossible
     */
    public function isValid($file)
    {
        $id = $this->getData($file)[$this->fieldName];
        if (!$this->loader->exists($id)) {
            $this->id = $id;
            $this->path = $file->getPath();
            $this->error(self::DOES_NOT_EXIST);
            return false;
        }
        return true;
    }
}
