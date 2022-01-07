<?php
use Phalcon\Mvc\Router;

$di->set(
    'router',
    function () {
        $router = new Router();

        $router->setDefaultModule('public');
        $router->setDefaultController('Public');
        $router->setDefaultAction('index');

        // Define your routes here
//        $router->add("/admin/:action/:params",
//            [
//                'controller' => 'Admin',
//                'action' => 1,
//                'params' => 2
//            ]
//        );
        $router->add("/([^/]+)(/){0,1}",
            [
                'action' => 1,
            ]
        );
        $router->add("/gascript/([-A-Z0-9]+)$",
            [
                'action' => 'gascript',
                'analitycsId' => 1
            ]
        );
        $router->add("/robots.txt",
            [
                'action' => 'robots',
            ]
        );
        $router->add("/favicon.ico",
            [
                'action' => 'favicon',
            ]
        );

        return $router;
    }
);
