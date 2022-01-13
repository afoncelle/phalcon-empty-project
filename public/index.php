<?php
declare(strict_types=1);

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Application as Application;
use Phalcon\Http\Response;


define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

date_default_timezone_set('Europe/Paris');

try {
    /**
     * The FactoryDefault Dependency Injector automatically registers
     * the services that provide a full stack framework.
     */
    $di = new FactoryDefault();

    $hostname = $_SERVER["HTTP_HOST"];

    switch ($hostname){
        case '@todo-projectname.io' :
        case 'www.@todo-projectname.io' :
            $env = 'prod';
            $protocol = 'https';
            error_reporting(0);
            break;
        case 'devadri.@todo-projectname.io' :
        case 'localhost:2229' :
            $env = 'dev';
            $protocol = 'http';
            error_reporting(E_ALL);
            break;
        default :
            $response = new Response();
            $response->setStatusCode(404, 'Not Found');
            $response->setContent("Host not found");
            $response->send();
            die;
            break;
    }
    define('ENV', $env);
    define('BASE_URL', "{$protocol}://{$hostname}");

    /**
     * Read services
     */
    include APP_PATH . '/config/services.php';

    /**
     * Handle routes
     */
    include APP_PATH . '/config/router.php';

    /**
     * Include Autoloader
     */
    require APP_PATH . "/library/vendor/autoload.php";

    /**
     * Handle the request
     */
    $application = new Application($di);

    // Register the installed modules
    $application->registerModules(
        [
            'public'  => [
                'className' => 'Projectname\Open\Module',
                'path'      => '../app/modules/public/Module.php',
            ],
        ]
    );
    $application->handle($_SERVER['REQUEST_URI'])->send();

} catch (Throwable $e) {
    // Create a message
    $error = $e->getMessage() . "\r\n\r\n"
           . $e->getTraceAsString(). "\r\n\r\n"
           . print_r(array_intersect_key($_SERVER, array_flip(['REQUEST_URI', 'HTTP_USER_AGENT', 'HTTP_X_FORWARDED_FOR', 'HTTP_HOST', 'HTTP_X_REAL_IP', 'REDIRECT_STATUS', 'REMOTE_ADDR', 'SERVER_ADDR', 'argv', 'argc'])),true);
    $message = (new Swift_Message('[' . ENV . '] SITE ERROR - ' . $e->getMessage()))
        ->setFrom(['postmaster@@todo-projectname.io' => 'Projectname'])
        ->setTo(['postmaster@@todo-projectname.io'])
        ->setBody($error);
//    $di->get('mailer')->send($message);

    throw $e;
}
