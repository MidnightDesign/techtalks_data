<?php

namespace Lighwand\Validate;

use Zend\ServiceManager\Config;
use Zend\ServiceManager\ServiceManager;

try {
    require_once __DIR__ . '/vendor/autoload.php';

    $config = include __DIR__ . '/validate/config.php';
    $serviceManager = new ServiceManager(new Config($config['services']));

    $return = 0;

    $types = ['video', 'event', 'event_series', 'speaker'];
    foreach ($types as $type) {
        $validator = $serviceManager->get($type);
        $files = $serviceManager->get($type . '_loader')->getFiles();
        foreach ($files as $file) {
            if (!$validator->isValid($file)) {
                $return = 1;
                echo join(PHP_EOL, $validator->getMessages()) . PHP_EOL;
            }
        }
    }

    if ($return === 0) {
        echo 'Everything is valid!' . PHP_EOL;
    } else {
        echo 'Invalid data found.' . PHP_EOL;
    }

    return $return;
} catch (\Exception $e) {
    echo $e->getMessage();
    return 1;
}
