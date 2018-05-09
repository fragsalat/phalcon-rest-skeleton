<?php

defined('APP_PATH') || define('APP_PATH', realpath('.'));
defined('DEBUG') || define('DEBUG', false);
defined('NOW') || define('NOW', time());

$config = new \Phalcon\Config(array(
    'application' => array(
        'controllersDir'  => APP_PATH . '/app/controllers/',
        'modelsDir'       => APP_PATH . '/app/models/',
        'migrationsDir'   => APP_PATH . '/app/migrations/',
        'servicesDir'     => APP_PATH . '/app/services/',
        'factoriesDir'    => APP_PATH . '/app/factories/',
        'repositoriesDir' => APP_PATH . '/app/repositories/',
        'libraryDir'      => APP_PATH . '/app/library/',
        'systemDir'       => APP_PATH . '/app/system/',
        'cacheDir'        => APP_PATH . '/app/cache/',
        'viewsDir'        => APP_PATH . '/app/views/',
        'baseUri'         => '/',
    ),
    'auth' => [],
    'logging' => [
        'dir' => APP_PATH . '/log'
    ]
));
$userConfigFile = APP_PATH . "/app/config/user/" . (get_current_user() ?: getenv('USER')) . '.ini';
// Check if user specific config file exists
if (is_file($userConfigFile)) {
    // Load config and merge it into default
    $userConfig = new \Phalcon\Config\Adapter\Ini($userConfigFile);
    $config->merge($userConfig);
} else {
    throw new \Exception('No user config found at: ' . $userConfigFile, 503);
}

return $config;
