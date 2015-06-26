<?php

namespace Lighwand\Validate\Video;

use Zend\Validator\Exception;
use Zend\Validator\ValidatorChain;
use Zend\Validator\ValidatorInterface;

class VideoValidator extends ValidatorChain
{
    /**
     * @param ValidatorInterface[] $validators
     */
    public function __construct(array $validators)
    {
        parent::__construct();

        foreach ($validators as $validator) {
            $this->attach($validator);
        }
    }

}