<?php

namespace Lighwand\Validate\Video\Name;

use Zend\Validator\Exception;
use Zend\Validator\ValidatorChain;

class NameValidator extends ValidatorChain
{
    public function __construct()
    {
        parent::__construct();

        $this->attach(new NameExists());
    }
}