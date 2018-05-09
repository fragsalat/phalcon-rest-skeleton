<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerNamespaces([
    'TODONS\\controller' => $config->application->controllersDir,
    'TODONS\\models' => $config->application->modelsDir,
    'TODONS\\services' => $config->application->servicesDir,
    'TODONS\\factories' => $config->application->factoriesDir,
    'TODONS\\repositories' => $config->application->repositoriesDir,
    'TODONS\\system' => $config->application->systemDir,
])->register();
