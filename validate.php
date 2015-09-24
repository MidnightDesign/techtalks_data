<?php

namespace Lighwand\Validate;

use Lighwand\Validate\Loader\EventLoader;
use Lighwand\Validate\Loader\EventSeriesLoader;
use Lighwand\Validate\Loader\SpeakerLoader;
use Lighwand\Validate\Loader\VideoLoader;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\ServiceManager;

try {
    require_once __DIR__ . '/vendor/autoload.php';

    $config = include __DIR__ . '/validate/config.php';
    $serviceManager = new ServiceManager(new Config($config['services']));

    $return = 0;

    $types = [
        ['service' => 'video', 'loader' => VideoLoader::class],
        ['service' => 'event', 'loader' => EventLoader::class],
        ['service' => 'event_series', 'loader' => EventSeriesLoader::class],
        ['service' => 'speaker', 'loader' => SpeakerLoader::class],
    ];
    foreach ($types as $type) {
        $validator = $serviceManager->get($type['service']);
        $files = $serviceManager->get($type['loader'])->getFiles();
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
