<?php

return [
    'default' => 'file1',
    'channels' => [
        'file1' => [
            'driver' => 'stack',
            'path' => FRAME_BASE_PATH . '/storage/',
            'format' => '[%s][%s] %s',
        ],
        'file2' => [
            'driver' => 'daily',
            'path' => FRAME_BASE_PATH . '/storage/',
            'format' => '[%s][%s] %s',
        ]
    ]
];
