<?php declare(strict_types = 1);

return [
    'default' => 'custom',
    'channels' => [
        'custom' => [
            'driver' => 'custom',
            'via' => \App\Logging\MonologLaravelLogger::class,
        ],
    ],
];
