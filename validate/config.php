<?php

namespace Lighwand\Validate;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\Plugin\ListFiles;
use Lighwand\Validate\Loader\Loader;
use Lighwand\Validate\Loader\LoaderInterface;
use Lighwand\Validate\Video\Duration\DurationFormat;
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
            DurationFormat::class,
            JsonFormat::class,
        ],
        'event' => [
            JsonFormat::class,
        ],
        'event_series' => [
            JsonFormat::class,
        ],
        'speaker' => [
            JsonFormat::class,
        ],
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
            'video_loader' => function (ServiceLocatorInterface $sl) {
                /** @var Filesystem $filesystem */
                $filesystem = $sl->get(Filesystem::class);
                return new Loader($filesystem, 'video');
            },
            SpeakersExist::class => function (ServiceLocatorInterface $sl) {
                /** @var LoaderInterface $speakerLoader */
                $speakerLoader = $sl->get('speaker_loader');
                /** @var DataExtractor $dataExtractor */
                $dataExtractor = $sl->get(DataExtractor::class);
                return new SpeakersExist($speakerLoader, $dataExtractor);
            },
            'speaker_loader' => function (ServiceLocatorInterface $sl) {
                /** @var Filesystem $filesystem */
                $filesystem = $sl->get(Filesystem::class);
                return new Loader($filesystem, 'speaker');
            },
            EventExists::class => function (ServiceLocatorInterface $sl) {
                /** @var LoaderInterface $eventLoader */
                $eventLoader = $sl->get('event_loader');
                /** @var DataExtractor $dataExtractor */
                $dataExtractor = $sl->get(DataExtractor::class);
                return new EventExists($eventLoader, $dataExtractor);
            },
            'event_loader' => function (ServiceLocatorInterface $sl) {
                /** @var Filesystem $filesystem */
                $filesystem = $sl->get(Filesystem::class);
                return new Loader($filesystem, 'event');
            },
            'event_series_loader' => function (ServiceLocatorInterface $sl) {
                /** @var Filesystem $filesystem */
                $filesystem = $sl->get(Filesystem::class);
                return new Loader($filesystem, 'event_series');
            },
            IdMatchesFileName::class => function (ServiceLocatorInterface $sl) {
                /** @var DataExtractor $dataExtractor */
                $dataExtractor = $sl->get(DataExtractor::class);
                return new IdMatchesFileName($dataExtractor);
            },
            DurationFormat::class => function (ServiceLocatorInterface $sl) {
                /** @var DataExtractor $dataExtractor */
                $dataExtractor = $sl->get(DataExtractor::class);
                return new DurationFormat($dataExtractor);
            },
            JsonFormat::class => function (ServiceLocatorInterface $sl) {
                /** @var DataExtractor $dataExtractor */
                $dataExtractor = $sl->get(DataExtractor::class);
                return new JsonFormat($dataExtractor);
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
