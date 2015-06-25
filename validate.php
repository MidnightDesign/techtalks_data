<?php

require_once __DIR__ . '/vendor/autoload.php';

$fs = new \League\Flysystem\Filesystem(new \League\Flysystem\Adapter\Local(__DIR__));
$fs->addPlugin(new \League\Flysystem\Plugin\ListFiles());

$return = 0;

$videoValidator = new \Lighwand\Validate\VideoValidator();
$videoFiles = (new \Lighwand\Validate\Loader\VideoLoader($fs))->getFiles();
foreach ($videoFiles as $file) {
    if (!$videoValidator->isValid($file)) {
        $return = 1;
        echo join(PHP_EOL, $videoValidator->getMessages()) . PHP_EOL;
    }
}

return $return;