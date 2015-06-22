<?php

namespace TechtalksTest;

use League\Flysystem\Filesystem;
use PHPUnit_Framework_Assert;

/**
 * Class Video
 *
 * @package TechtalksTest\Video
 */
class VideoTest
{
    /**
     * @var Filesystem
     */
    private $fs;
    /**
     * @var EventLoader
     */
    private $eventLoader;

    /**
     * Video constructor.
     *
     * @param Filesystem $fs
     * @param EventLoader $eventLoader
     */
    public function __construct(Filesystem $fs, EventLoader $eventLoader)
    {
        $this->fs = $fs;
        $this->eventLoader = $eventLoader;
    }

    public function idsAreUnique(array $files)
    {
        $idCounts = [];
        foreach ($files as $file) {
            $id = $file['filename'];
            if (!isset($idCounts[$id])) {
                $idCounts[$id] = ['count' => 0, 'paths' => []];
            }
            $idCounts[$id]['count'] += 1;
            $idCounts[$id]['paths'][] = $file['path'];
        }
        foreach ($idCounts as $id => $data) {
            PHPUnit_Framework_Assert::assertEquals(
                1, $data['count'],
                sprintf('The ID %s exists multiple times in the following files: %s.', $id, join(', ', $data['paths']))
            );
        }
    }

    /**
     * @param array $file
     */
    public function testVideo(array $file)
    {
        $this->ensureData($file);

        $this->assertIdEqualsFilename($file);
        $this->assertEventExists($file);
    }

    /**
     * @param array $file
     */
    private function assertIdEqualsFilename(array $file)
    {
        $expected = $file['data']['id'] . '.json';
        $actual = $file['basename'];
        PHPUnit_Framework_Assert::assertEquals(
            $expected,
            $actual,
            sprintf('%s has the wrong file name. It should match the ID: %s.', $file['path'], $expected)
        );
    }

    /**
     * Appends the "data" key containing the file's JSON-encoded data to the array
     *
     * @param array $file
     */
    private function ensureData(array &$file)
    {
        $file['data'] = json_decode($this->fs->read($file['path']), true);
    }

    private function assertEventExists(array $file)
    {
        $eventId = $file['data']['event'];
        PHPUnit_Framework_Assert::assertTrue(
            $this->eventLoader->eventExists($eventId),
            sprintf('The event %s referenced by %s does not exist.', $eventId, $file['path'])
        );
    }
}