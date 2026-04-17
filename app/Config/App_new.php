<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class App extends BaseConfig
{
    // ── Base URL ──────────────────────────────────────────────
    /**
     * Set this to your site's URL — include trailing slash.
     * For local dev: 'http://localhost:8080/'
     * For production: 'https://yourdomain.com/'
     */
    public string $baseURL = 'http://localhost:8080/';

    /**
     * Allowed hostnames (for production security).
     * Add your live domain here when deploying.
     */
    public array $allowedHostnames = [];

    // ── Index file ────────────────────────────────────────────
    /**
     * Set to '' when using .htaccess to remove index.php from URLs.
     */
    public string $indexPage = '';

    // ── URI ───────────────────────────────────────────────────
    public string $uriProtocol    = 'REQUEST_URI';
    public string $defaultLocale  = 'en';
    // public string $negotiateLocale = false;
    // public string $supportedLocales = ['en'];
    // public float  $CSPEnabled     = false;

    // ── Cookies ───────────────────────────────────────────────
    public string $cookiePrefix    = '';
    public string $cookieDomain    = '';
    public string $cookiePath      = '/';
    public bool   $cookieSecure    = false;  // set true in production (HTTPS)
    public bool   $cookieHTTPOnly  = true;
    public string $cookieSameSite  = 'Lax';

    // ── Session ───────────────────────────────────────────────
    /**
     * Session driver.
     * 'CodeIgniter\Session\Handlers\FileHandler'     — default
     * 'CodeIgniter\Session\Handlers\DatabaseHandler' — recommended for production
     */
    public string $sessionDriver            = 'CodeIgniter\Session\Handlers\FileHandler';
    public string $sessionCookieName        = 'tea_session';
    public int    $sessionExpiration        = 7200;       // 2 hours
    public string $sessionSavePath         = WRITEPATH . 'session';
    public bool   $sessionMatchIP          = false;
    public int    $sessionTimeToUpdate     = 300;
    public bool   $sessionRegenerateDestroy = false;

    // ── Logging ───────────────────────────────────────────────
    public int    $errorLevel = E_ALL;

    // ── Cache ─────────────────────────────────────────────────
    public string $cacheHandler   = 'file';
    public string $cacheStorePath = WRITEPATH . 'cache/';
    public string $cacheBackupHandler = 'dummy';

    // ── Proxy IPs ─────────────────────────────────────────────
    public string $proxyIPs = '';

    // ── Content security ──────────────────────────────────────
    public bool   $CSRFProtection  = false;   // enable in production
    public string $CSRFTokenName   = 'csrf_token_name';
    public string $CSRFHeaderName  = 'X-CSRF-TOKEN';
    public string $CSRFCookieName  = 'csrf_cookie_name';
    public int    $CSRFExpire      = 7200;
    public bool   $CSRFRegenerate  = true;
    public bool   $CSRFRedirect    = true;
    public string $CSRFSameSite    = 'Lax';

    // ── Honeypot ──────────────────────────────────────────────
    public string $honeypotName     = 'honeypot';
    public string $honeypotTemplate = '';
    public bool   $honeypotHidden   = true;

    // ── Reverse proxy ─────────────────────────────────────────
    public string $reverseProxyIPs = '';
}
