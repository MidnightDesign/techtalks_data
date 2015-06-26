<?php

namespace Lighwand\Validate;

use Lighwand\Validate\Loader\VideoLoader;
use Lighwand\Validate\Video\VideoValidator;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\ServiceManager;

try {
    require_once __DIR__ . '/vendor/autoload.php';

    $config = include __DIR__ . '/validate/config.php';
    $serviceManager = new ServiceManager(new Config($config['services']));

    $return = 0;

    $videoValidator = $serviceManager->get(VideoValidator::class);
    $videoFiles = $serviceManager->get(VideoLoader::class)->getFiles();
    foreach ($videoFiles as $file) {
        if (!$videoValidator->isValid($file)) {
            $return = 1;
            echo join(PHP_EOL, $videoValidator->getMessages()) . PHP_EOL;
        }
    }

    return $return;
} catch (\Exception $e) {
    echo $e->getMessage();
    return 1;
}