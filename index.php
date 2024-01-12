<?php

// dev/debugging lib (like d() from laravel) - will not be loaded if it does not exist
if(PHP_MAJOR_VERSION === 8)
    ($debug_lib_exists=is_file($f='../kint-new/kint.phar')) ? require_once($f) : null;
else
    ($debug_lib_exists=is_file($f='../kint/Kint.class.php')) ? require_once($f) : null;

function isDev() {
    return
        ($_SERVER['IS_DEV'] ?? false) === 'yes'
        ||
        ($_SERVER['PHPRC'] ?? false) === '\xampp\php' // hopefully a prod server will never ever tell his PHPRC is xampp..
        ||
        ( ($_SERVER['SERVER_SOFTWARE'] ?? false) && strpos($_SERVER['SERVER_SOFTWARE'], 'Development Server') !== false ) // valet / artisan builtin webserver
        ||
        ( isset($_SERVER["DOCUMENT_URI"]) && !empty($_SERVER["DOCUMENT_URI"]) && strpos($_SERVER["DOCUMENT_URI"], 'valet') !== false ) // valet
        ;
}

if(PHP_MAJOR_VERSION === 8 && isDev()) {
    function dd(...$v) {
        d(...$v);
        exit;
    }
}

// ----

/**
 * This file is part of the Cockpit project.
 *
 * (c) Artur Heinze - 🅰🅶🅴🅽🆃🅴🅹🅾, http://agentejo.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

define('COCKPIT_ADMIN', 1);

// set default timezone
date_default_timezone_set('UTC');

// handle php webserver
if (PHP_SAPI == 'cli-server' && is_file(__DIR__.parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}

// bootstrap cockpit
require(__DIR__.'/bootstrap.php');

# admin route
if (COCKPIT_ADMIN && !defined('COCKPIT_ADMIN_ROUTE')) {

    $route = preg_replace('#'.preg_quote(COCKPIT_BASE_URL, '#').'#', '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), 1);

    if ($route == '') {
        $route = '/';
    }

    define('COCKPIT_ADMIN_ROUTE', $route);
}

if (COCKPIT_API_REQUEST) {

    $_cors = $cockpit->retrieve('config/cors', []);

    header('Access-Control-Allow-Origin: '      .($_cors['allowedOrigins'] ?? '*'));
    header('Access-Control-Allow-Credentials: ' .($_cors['allowCredentials'] ?? 'true'));
    header('Access-Control-Max-Age: '           .($_cors['maxAge'] ?? '1000'));
    header('Access-Control-Allow-Headers: '     .($_cors['allowedHeaders'] ?? 'X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding, Cockpit-Token'));
    header('Access-Control-Allow-Methods: '     .($_cors['allowedMethods'] ?? 'PUT, POST, GET, OPTIONS, DELETE'));
    header('Access-Control-Expose-Headers: '    .($_cors['exposedHeaders'] ?? 'true'));

    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        exit(0);
    }
}


// run backend
$cockpit->set('route', COCKPIT_ADMIN_ROUTE)->trigger('admin.init')->run();
