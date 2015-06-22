<?php

namespace TechtalksTest;

use League\Flysystem\Filesystem;
use PHPUnit_Framework_Assert;

/**
 * Class Video
 *
 * @package TechtalksTest\Video
 */
class VideoTest extends AbstractTest
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
     * @var SpeakerLoader
     */
    private $speakerLoader;

    /**
     * Video constructor.
     *
     * @param Filesystem $fs
     * @param EventLoader $eventLoader
     * @param SpeakerLoader $speakerLoader
     */
    public function __construct(Filesystem $fs, EventLoader $eventLoader, SpeakerLoader $speakerLoader)
    {
        $this->fs = $fs;
        $this->eventLoader = $eventLoader;
        $this->speakerLoader = $speakerLoader;
    }

    /**
     * @param array $file
     */
    public function testVideo(array $file)
    {
        $this->ensureData($file);

        $this->assertIdEqualsFilename($file);
        $this->assertEventExists($file);
        $this->assertSpeakersExist($file);
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
            $this->eventLoader->exists($eventId),
            sprintf('The event %s referenced by %s does not exist.', $eventId, $file['path'])
        );
    }

    private function assertSpeakersExist(array $file)
    {
        foreach ($file['data']['speakers'] as $speakerId) {
            PHPUnit_Framework_Assert::assertTrue(
                $this->speakerLoader->exists($speakerId),
                sprintf('The speaker %s referenced by %s does not exist.', $speakerId, $file['path'])
            );
        }
    }
}