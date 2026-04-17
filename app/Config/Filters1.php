<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;
use App\Filters\AuthFilter;

class Filters extends BaseConfig
{
    /**
     * Configures aliases for Filter classes.
     */
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'auth'          => AuthFilter::class,
    ];

    /**
     * List of filter aliases that are always run.
     */
    public array $globals = [
        'before' => [
            'honeypot',
            // 'csrf',   // Enable in production; keep off during API testing
        ],
        'after' => [
            'toolbar',
            'honeypot',
            'secureheaders',
        ],
    ];

    public array $methods = [];

    public array $filters = [];
}
