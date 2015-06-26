<?php

namespace Lighwand\Validate\Video;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Validator\ValidatorInterface;

class VideoValidatorFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return VideoValidator
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new VideoValidator($this->getValidators($serviceLocator));
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return ValidatorInterface[]
     */
    private function getValidators(ServiceLocatorInterface $serviceLocator)
    {
        $video_validators = $serviceLocator->get('Config')['video_validators'];
        return array_map(function ($serviceKey) use ($serviceLocator) {
            return $serviceLocator->get($serviceKey);
        }, $video_validators);
    }
}