<?php
declare(strict_types=1);

use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Escaper;
use Phalcon\Flash\Direct as Flash;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Session\Adapter\Stream as SessionAdapter;
use Phalcon\Session\Manager as SessionManager;
use Phalcon\Url as UrlResolver;
use Detection\MobileDetect;
/**
 * Shared configuration service
 */
$di->setShared('config', function () {
    return include APP_PATH . "/config/config.php";
});

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->setShared('url', function () {
    $config = $this->getConfig();

    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
});

/**
 * Setting up the view component
 */
$di->setShared('view', function () {
    $config = $this->getConfig();

    $view = new View();
    $view->setDI($this);
//    $view->setViewsDir($config->application->viewsDir);
//    $view->setPartialsDir('./');

    $view->registerEngines([
        '.volt' => function ($view) {
            $config = $this->getConfig();

            $volt = new VoltEngine($view, $this);

            $volt->setOptions([
                'path' => $config->application->cacheDir,
                'separator' => '_',
                //'always' => true
            ]);
            
            $compiler = $volt->getCompiler();
            $compiler->addFilter("date", function($resolvedArgs, $exprArgs) {
                return $resolvedArgs;
            });
            
            $compiler->addFunction('strtotime', 'strtotime');

            return $volt;
        },
        '.phtml' => PhpEngine::class

    ]);

    return $view;
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('db', function () {
    $config = $this->getConfig();

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
    $params = [
        'host'     => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname'   => $config->database->dbname,
        'charset'  => $config->database->charset
    ];

    if ($config->database->adapter == 'Postgresql') {
        unset($params['charset']);
    }

    return new $class($params);
});

$di->set(
    'logger',
    function () {
        $config = $this->getConfig();
        $connection = new Mysql(
            [
                'host'     => $config->database->host,
                'username' => $config->database->username,
                'password' => $config->database->password,
                'dbname'   => $config->database->dbname,
            ]
        );

        $logsName = 'errors';
        $tableName = 'error_logs';
        return new DbLogger($connection, $logsName, $tableName);
    }
);


/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->setShared('modelsMetadata', function () {
    return new MetaDataAdapter();
});

/**
 * Register the session flash service with the Twitter Bootstrap classes
 */
$di->set('flash', function () {
    $escaper = new Escaper();
    $flash = new Flash($escaper);
    $flash->setImplicitFlush(false);
    $flash->setCssClasses([
        'error'   => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice'  => 'alert alert-info',
        'warning' => 'alert alert-warning'
    ]);

    return $flash;
});

/**
 * Start the session the first time some component request the session service
 */
$di->setShared('session', function () {
    $session = new SessionManager();
    $files = new SessionAdapter([
        'savePath' => sys_get_temp_dir(),
    ]);
    $session->setAdapter($files);
    $session->start();

    return $session;
});
//
//$di->setShared('wkhtml', function() {
//    $client = new WkhtmlPDF();
//    $client->setOptions([
//        //'binary' => BASE_PATH.'/app/library/bin/wkhtmltopdf-i386',
//        'binary' => BASE_PATH.'/app/library/bin/wkhtmltopdf-amd64',
//        //'binary' => BASE_PATH.'/app/library/bin/wkhtmltopdf',
//        'ignoreWarnings' => true,
//        'commandOptions' => array(
//            'useExec' => true,      // Can help if generation fails without a useful error message
//            'procEnv' => array(
//                // Check the output of 'locale' on your system to find supported languages
//                'LANG' => 'en_US.utf-8',
//            ),
//        ),
//        'encoding' => 'UTF-8',
//        'page-size' => 'A4',
//        'orientation' => 'portrait',
//        'dpi' => '96',
//        'disable-smart-shrinking',
//        'quiet',
//        'margin-top' => 20,
//        'margin-bottom' => 10
//    ]);
//    return $client;
//});

$di->setShared('mailer', function(): Swift_Mailer {
// Create the Transport
    $transport = (new Swift_SmtpTransport('ssl0.ovh.net', 465, 'ssl'))
        ->setUsername('contact@cryptokart.io')
        ->setPassword('nftrocket@2021!');

    return new Swift_Mailer($transport);
});

$di->setShared('mobileDetect', function() {
    return new MobileDetect();
});