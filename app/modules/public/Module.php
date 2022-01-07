<?php


namespace Cryptokart\Open;

use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;

class Module
{
    /**
     * Register a specific autoloader for the module
     */
    public function registerAutoloaders($di = null)
    {
        $loader = new Loader();

        $loader->registerNamespaces(
            [
                'Cryptokart\Controllers'       => '../app/modules/base/controllers/',
                'Cryptokart\Models'            => '../app/modules/base/models/',
                'Cryptokart\Services'          => '../app/modules/base/services/',
                'Cryptokart\Repositories'      => '../app/modules/base/repositories/',
                'Cryptokart\Enums'             => '../app/modules/base/enums/',
                'Cryptokart\Open\Controllers'  => '../app/modules/public/controllers/',
                'Cryptokart\Open\Models'       => '../app/modules/public/models/',
            ]
        );

        $loader->register();
    }

    /**
     * Register specific services for the module
     */
    public function registerServices($di)
    {
        // Registering a dispatcher
        $di->set(
            'dispatcher',
            function () {
                $dispatcher = new Dispatcher();

                $dispatcher->setDefaultNamespace('Cryptokart\Open\Controllers');

                return $dispatcher;
            }
        );

        // Registering the view component
        $di->set(
            'view',
            function () {
                $view = new View();

                $view->setViewsDir('../app/modules/public/views/');
                $view->setPartialsDir('partials/');
                $view->setLayoutsDir('layouts/');

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
                        $compiler->addFunction('strtotime', 'strtotime');

                        return $volt;
                    },
                    '.phtml' => PhpEngine::class

                ]);
                return $view;
            }
        );
    }
}