<?php

/**
 * CodeIgniter 4 Front Controller
 * ─────────────────────────────────────────────────────────────
 * All requests are routed through this file.
 * Do NOT edit unless you know what you are doing.
 */

// ── Path constants ────────────────────────────────────────────
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// ── Set the project root (one level up from /public) ─────────
$pathsConfig = FCPATH . '../app/Config/Paths.php';
require realpath($pathsConfig) ?: $pathsConfig;

$paths = new Config\Paths();

// ── Load the framework bootstrap ─────────────────────────────
require realpath($paths->systemDirectory . '/Boot.php') ?: $paths->systemDirectory . '/Boot.php';

exit(CodeIgniter\Boot::bootWeb($paths));
