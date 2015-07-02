<?php

namespace Lighwand\Validate;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\Plugin\ListFiles;
use Lighwand\Validate\Loader\VideoLoader;
use Lighwand\Validate\Video\Id\IdMatchesFileName;
use Zend\ServiceManager\ServiceLocatorInterface;

return [
    'validators' => [
        'video' => [
            'id' => [IdMatchesFileName::class],
            'name' => [],
            'event' => [],
            'speakers' => [],
            'tags' => ['required' => false],
            'recorded_at' => [],
            'uploaded_at' => [],
            'duration' => [],
            'poster' => [],
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
        ],
        'invokables' => [
            IdMatchesFileName::class => IdMatchesFileName::class,
        ],
        'abstract_factories' => [
            ValidatorFactory::class,
        ],
    ],
];