<?php
error_reporting(E_ALL);

define('APP_PATH', realpath('./'));

/**
 * Read the configuration
 */
$config = include APP_PATH . "/app/config/config.php";

/**
 * Read auto-loader
 */
include APP_PATH . "/app/config/loader.php";

/**
 * Initialize error handling
 */
new \TODONS\system\exception\ErrorHandler();

/**
 * Try to load 3rdParty libraries using composer autoload
 */
try {
	include APP_PATH . "/app/library/autoload.php";
}
catch (\Exception $e) {
	throw new \TODONS\system\exception\SystemException('Composer not installed', 503, $e);
}

/**
 * Read services
 */
include APP_PATH . "/app/config/services.php";

/**
 * Handle the request
 */
$application = new \Phalcon\Mvc\Application($di);
$application->useImplicitView(false);
$response = $application->handle()->getContent();
echo $response;
