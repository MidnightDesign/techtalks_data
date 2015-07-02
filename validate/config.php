<?php

namespace Lighwand\Validate;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\Plugin\ListFiles;
use Lighwand\Validate\Loader\VideoLoader;
use Lighwand\Validate\Video\Id\IdExists;
use Lighwand\Validate\Video\Id\IdMatchesFileName;
use Lighwand\Validate\Video\Name\NameExists;
use Zend\ServiceManager\ServiceLocatorInterface;

return [
    'validators' => [
        'video' => [
            'id' => [
                IdExists::class => ['break' => true],
                IdMatchesFileName::class,
            ],
            'name' => [
                NameExists::class,
            ],
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
            IdExists::class => IdExists::class,
            IdMatchesFileName::class => IdMatchesFileName::class,
            NameExists::class => NameExists::class,
        ],
        'abstract_factories' => [
            ValidatorFactory::class,
        ],
    ],
];