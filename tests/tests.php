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
        require_once './vendor/autoload.php';
        $fs = makeFilesystem();
        $files = getVideoFiles($fs);
        $videoTest = new VideoTest($fs, new EventLoader($fs));
        foreach ($files as $file) {
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
 * @param Filesystem $fs
 * @return mixed
 */
function getVideoFiles($fs)
{
    return $fs->listFiles('videos', true);
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