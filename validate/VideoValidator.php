<?php

namespace Lighwand\Validate;

use Lighwand\Validate\Video\Id\IdValidator;
use Lighwand\Validate\Video\Name\NameValidator;
use Zend\Validator\Exception;
use Zend\Validator\ValidatorChain;

class VideoValidator extends ValidatorChain
{
    public function __construct()
    {
        parent::__construct();

        $this->attach(new IdValidator());
        $this->attach(new NameValidator());
    }

}