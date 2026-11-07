<?php

// Fix SCRIPT_NAME and SCRIPT_FILENAME so Laravel generates URLs correctly without '/api/index.php'
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = __DIR__.'/../public/index.php';

// Create required storage directories in /tmp for serverless environment
$dirs = [
    '/tmp/views',
    '/tmp/cache',
    '/tmp/sessions',
    '/tmp/logs',
];

foreach ($dirs as $dir) {
    if (! is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// Forward Vercel requests to public index.php
require __DIR__.'/../public/index.php';
