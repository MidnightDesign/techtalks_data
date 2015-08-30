<?php

namespace Lighwand\Validate;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\Plugin\ListFiles;
use Lighwand\Validate\Loader\EventLoader;
use Lighwand\Validate\Loader\SpeakerLoader;
use Lighwand\Validate\Loader\VideoLoader;
use Lighwand\Validate\Video\Event\EventExists;
use Lighwand\Validate\Video\Id\IdMatchesFileName;
use Lighwand\Validate\Video\Speaker\SpeakersExists;
use Zend\ServiceManager\ServiceLocatorInterface;

return [
    'validators' => [
        'video' => [
            'id' => [IdMatchesFileName::class],
            'name' => [],
            'event' => [EventExists::class],
            'speakers' => [SpeakersExists::class],
            'tags' => ['required' => false],
            'recorded_at' => [],
            'uploaded_at' => [],
            'duration' => [],
            'poster' => ['required' => false],
        ]
    ],
    'services' => [
        'factories' => [
            'Config' => function () {
                /** @noinspection PhpIncludeInspection */
                return include __FILE__;
            },
            Filesystem::class => function () {
                $fs = new Filesystem(new Local(dirname(__DIR__)));
                $fs->addPlugin(new ListFiles());
                return $fs;
            },
            VideoLoader::class => function (ServiceLocatorInterface $sl) {
                /** @var Filesystem $filesystem */
                $filesystem = $sl->get(Filesystem::class);
                return new VideoLoader($filesystem);
            },
            SpeakersExists::class => function (ServiceLocatorInterface $sl) {
                /** @var SpeakerLoader $speakerLoader */
                $speakerLoader = $sl->get(SpeakerLoader::class);
                return new SpeakersExists($speakerLoader);
            },
            SpeakerLoader::class => function (ServiceLocatorInterface $sl) {
                /** @var Filesystem $filesystem */
                $filesystem = $sl->get(Filesystem::class);
                return new SpeakerLoader($filesystem);
            },
            EventExists::class => function (ServiceLocatorInterface $sl) {
                /** @var EventLoader $eventLoader */
                $eventLoader = $sl->get(EventLoader::class);
                return new EventExists($eventLoader);
            },
            EventLoader::class => function (ServiceLocatorInterface $sl) {
                /** @var Filesystem $filesystem */
                $filesystem = $sl->get(Filesystem::class);
                return new EventLoader($filesystem);
            }
        ],
        'invokables' => [
            IdMatchesFileName::class => IdMatchesFileName::class,
        ],
        'abstract_factories' => [
            ValidatorFactory::class,
        ],
    ],
];
