<?php

namespace Lighwand\Validate;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\Plugin\ListFiles;
use Lighwand\Validate\Loader\VideoLoader;
use Lighwand\Validate\Video\Id\IdValidator;
use Lighwand\Validate\Video\Name\NameValidator;
use Lighwand\Validate\Video\VideoValidator;
use Lighwand\Validate\Video\VideoValidatorFactory;
use Zend\ServiceManager\ServiceLocatorInterface;

return [
    'video_validators' => [
        IdValidator::class,
        NameValidator::class,
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
            VideoValidator::class => VideoValidatorFactory::class,
        ],
        'invokables' => [
            IdValidator::class => IdValidator::class,
            NameValidator::class => NameValidator::class,
        ]
    ],
];