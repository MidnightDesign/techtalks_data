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
use Lighwand\Validate\Video\Speaker\SpeakersExist;
use Zend\ServiceManager\ServiceLocatorInterface;

return [
    'validators' => [
        'video' => [
            IdMatchesFileName::class,
            EventExists::class,
            SpeakersExist::class,
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
            SpeakersExist::class => function (ServiceLocatorInterface $sl) {
                /** @var SpeakerLoader $speakerLoader */
                $speakerLoader = $sl->get(SpeakerLoader::class);
                /** @var DataExtractor $dataExtractor */
                $dataExtractor = $sl->get(DataExtractor::class);
                return new SpeakersExist($speakerLoader, $dataExtractor);
            },
            SpeakerLoader::class => function (ServiceLocatorInterface $sl) {
                /** @var Filesystem $filesystem */
                $filesystem = $sl->get(Filesystem::class);
                return new SpeakerLoader($filesystem);
            },
            EventExists::class => function (ServiceLocatorInterface $sl) {
                /** @var EventLoader $eventLoader */
                $eventLoader = $sl->get(EventLoader::class);
                /** @var DataExtractor $dataExtractor */
                $dataExtractor = $sl->get(DataExtractor::class);
                return new EventExists($eventLoader, $dataExtractor);
            },
            EventLoader::class => function (ServiceLocatorInterface $sl) {
                /** @var Filesystem $filesystem */
                $filesystem = $sl->get(Filesystem::class);
                return new EventLoader($filesystem);
            },
            IdMatchesFileName::class => function (ServiceLocatorInterface $sl) {
                /** @var DataExtractor $dataExtractor */
                $dataExtractor = $sl->get(DataExtractor::class);
                return new IdMatchesFileName($dataExtractor);
            },
        ],
        'invokables' => [
            DataExtractor::class => DataExtractor::class,
        ],
        'abstract_factories' => [
            ValidatorFactory::class,
        ],
    ],
];
