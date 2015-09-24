<?php

namespace Lighwand\Validate;

use Zend\Validator\AbstractValidator;
use Zend\Validator\Exception;

class JsonFormat extends AbstractValidator
{
    use DataExtractorAwareTrait;
    const FORMAT_INVALID = 'formatInvalid';
    protected $messageTemplates = [
        self::FORMAT_INVALID => "Invalid JSON Format in \"%path%\" on line %line%.\nExpected:\n\"%expected%\"\nGot:\n\"%actual%\".",
    ];
    protected $messageVariables = [
        'path' => 'path',
        'line' => 'line',
        'expected' => 'expected',
        'actual' => 'actual',
    ];
    /** @var string */
    protected $path;
    /** @var integer */
    protected $line;
    /** @var string */
    protected $expected;
    /** @var string */
    protected $actual;

    /**
     * JsonFormat constructor.
     *
     * @param DataExtractor $dataExtractor
     */
    public function __construct(DataExtractor $dataExtractor)
    {
        $this->dataExtractor = $dataExtractor;
        parent::__construct();
    }

    /**
     * @param File $file
     * @return bool
     * @throws Exception\RuntimeException If validation of $value is impossible
     */
    public function isValid($file)
    {
        $encodeOptions = JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
        $expected = json_encode($this->getData($file), $encodeOptions);
        $actual = $file->read();
        if ($expected === $actual) {
            return true;
        }
        $expectedLines = preg_split('/\r\n|\r|\n/', $expected);
        $actualLines = preg_split('/\r\n|\r|\n/', $actual);
        foreach ($expectedLines as $line => $expectedLine) {
            $actualLine = $actualLines[$line];
            if ($expectedLine !== $actualLine) {
                $this->path = $file->getPath();
                $this->line = $line + 1;
                $this->expected = $expectedLine;
                $this->actual = $actualLine;
                $this->error(self::FORMAT_INVALID);
                return false;
            }
        }
        return true;
    }
}
