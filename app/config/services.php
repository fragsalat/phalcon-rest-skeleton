<?php
/**
 * Services are globally registered in this file
 *
 * @var \Phalcon\Config $config
 */
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\View\Simple as SimpleView;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Logger\Adapter\File as FileLogger;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Events\Manager as EventsManager;

/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new FactoryDefault();

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->setShared('url', function () use ($config) {
    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('db', function () use ($config) {
    $dbConfig = $config->database->toArray();
    $adapter = $dbConfig['adapter'];
    unset($dbConfig['adapter']);

    $class = '\\Phalcon\\Db\\Adapter\\Pdo\\' . $adapter;
    return new $class($dbConfig);
});

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->setShared('modelsMetadata', new MetaDataAdapter());

$di->setShared('annotations', new \Phalcon\Annotations\Adapter\Memory());

// Registering a dispatcher
$di->set('dispatcher', function () {
    // Create event manager and attach authentication listener
    $eventsManager = new EventsManager();
    $eventsManager->attach('dispatch', new \TODONS\system\event\listener\AuthenticationListener());
    // Create dispatcher
    $dispatcher = new Dispatcher();
    $dispatcher->setEventsManager($eventsManager);
    $dispatcher->setDefaultNamespace("TODONS\\controller");
    return $dispatcher;
});

/**
 * Create a simple view
 */
$di->set('view', function () use ($config) {
    $view = new SimpleView();
    $view->registerEngines(['.volt' => 'Phalcon\Mvc\View\Engine\Volt']);
    $view->setViewsDir($config->application->viewsDir);

    return $view;
}, true);

/**
 * Start the session the first time some component request the session service
 */
$di->setShared('session', function () {
    $session = new SessionAdapter();
    $session->start();

    return $session;
});

/**
 * Create security service
 */
$di->setShared('security', function () {
    $security = new \Phalcon\Security();
    // Set the password hashing factor to 12 rounds
    $security->setWorkFactor(12);

    return $security;
});

/**
 * Create a general logger
 */
$di->setShared('logger', function() use ($config) {
    $logFile = $config->logging->dir . '/' . date('d-m-Y', time()) . '.log';
    $logLevel = isset($config->logging->level) ? $config->logging->level : \Phalcon\Logger::DEBUG;
    // Create dir if not existing
    if (!is_dir($config->logging->dir)) {
        mkdir($config->logging->dir, 0777, true);
    }
    // Create file logger
    $logger = new FileLogger($logFile);
    $logger->setLogLevel($logLevel);

    return $logger;
});

$di->setShared('mailer', function() use ($config) {
    $phpMailer = new PHPMailer();
    $phpMailer->SMTPDebug = 3;
    $phpMailer->isSMTP();
    $phpMailer->Host = $config->mail->host;
    $phpMailer->SMTPAuth = true;
    $phpMailer->Username = $config->mail->username;
    $phpMailer->Password = $config->mail->password;
    //$phpMailer->SMTPSecure  = 'tls';
    $phpMailer->From = $config->mail->from;

    return $phpMailer;
});
