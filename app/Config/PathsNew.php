<?php

/**
 * Paths Configuration
 * Tells CodeIgniter where the app/ and system/ directories live.
 * Do NOT put under a namespace — this file is loaded before autoloading.
 */
class Paths
{
    /**
     * Path to the CodeIgniter system directory.
     * After `composer install`, the framework lives in vendor/codeigniter4/framework/system.
     */
    public string $systemDirectory = __DIR__ . '/../../vendor/codeigniter4/framework/system';

    /**
     * Path to the application directory.
     */
    public string $appDirectory = __DIR__ . '/..';

    /**
     * Path to the writable directory (logs, cache, sessions, uploads).
     */
    public string $writableDirectory = __DIR__ . '/../../writable';
}
