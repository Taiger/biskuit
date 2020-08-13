<?php

if (version_compare($ver = PHP_VERSION, $req = '7.2.0', '<')) {
    exit(sprintf('You are running PHP %s, but Biskuit needs at least <strong>PHP %s</strong> to run.', $ver, $req));
}

if (PHP_SAPI == 'cli-server' && is_file(__DIR__.parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}

if (!isset($_SERVER['HTTP_MOD_REWRITE']) && !isset($_SERVER['REDIRECT_HTTP_MOD_REWRITE'])) {
    $_SERVER['HTTP_MOD_REWRITE'] = 'Off';
} else {
    $_SERVER['HTTP_MOD_REWRITE'] = 'On';
}

date_default_timezone_set('UTC');

$env = 'system';
$path = __DIR__;
$config = array(
    'path'          => $path,
    'path.packages' => $path.'/packages',
    'path.storage'  => $path.'/storage',
    'path.temp'     => $path.'/tmp/temp',
    'path.cache'    => $path.'/tmp/cache',
    'path.logs'     => $path.'/tmp/logs',
    'path.vendor'   => $path.'/vendor',
    'path.artifact' => $path.'/tmp/packages',
    'path.sessions' => $path.'/tmp/sessions',
    'config.file'   => realpath($path.'/config.php'),
    'system.api'    => 'https://pagekit.com'
);

$required_tmp_directories = [$path.'/tmp', $config['path.temp'], $config['path.cache'],
    $config['path.logs'], $config['path.artifact'], $config['path.sessions']];
foreach ($required_tmp_directories as $dir) {
    if(!is_dir($dir)) {
        echo $dir.'<br>';
        if (!@mkdir($dir, 0755)) {
            $error = error_get_last();
            die($error['message']);
        }
        fopen($dir.'/index.html', 'w');
    }
}

if (!$config['config.file']) {
    $env = 'installer';
}

if (PHP_SAPI == 'cli') {
    $env = 'console';
}

require_once "$path/app/$env/app.php";