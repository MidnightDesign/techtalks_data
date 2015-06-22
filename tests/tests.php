<?php

namespace TechtalksTest;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\Plugin\ListFiles;

/**
 * @return mixed
 */
function run()
{
    try {
        chdir(dirname(__DIR__));
        /** @noinspection PhpIncludeInspection */
        require_once './vendor/autoload.php';
        $fs = makeFilesystem();

        $videoLoader = new VideoLoader($fs);
        $videoTest = new VideoTest($fs, new EventLoader($fs), new SpeakerLoader($fs));
        $videoTest->uniqueIds($videoLoader->getFiles());
        foreach ($videoLoader->getFiles() as $file) {
            $videoTest->testVideo($file);
        }
    } catch (\PHPUnit_Framework_AssertionFailedError $e) {
        echo $e->getMessage();
        return 1;
    } catch (\Exception $e) {
        return 1;
    }
    return 0;
}

/**
 * @return Filesystem
 */
function makeFilesystem()
{
    $fs = new Filesystem(new Local('.'));
    $fs->addPlugin(new ListFiles());
    return $fs;
}

return run();