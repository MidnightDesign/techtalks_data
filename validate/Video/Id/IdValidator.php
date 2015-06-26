<?php

namespace Lighwand\Validate\Video\Id;

use Zend\Validator\Exception;
use Zend\Validator\ValidatorChain;

class IdValidator extends ValidatorChain
{
    public function __construct()
    {
        parent::__construct();

        $this->attach(new IdExists(), true);
        $this->attach(new IdMatchesFileName());
    }
}