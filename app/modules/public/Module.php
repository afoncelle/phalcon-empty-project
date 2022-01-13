<?php


namespace Projectname\Open;

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
                'Projectname\Controllers'       => '../app/modules/base/controllers/',
                'Projectname\Models'            => '../app/modules/base/models/',
                'Projectname\Services'          => '../app/modules/base/services/',
                'Projectname\Repositories'      => '../app/modules/base/repositories/',
                'Projectname\Enums'             => '../app/modules/base/enums/',
                'Projectname\Open\Controllers'  => '../app/modules/public/controllers/',
                'Projectname\Open\Models'       => '../app/modules/public/models/',
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

                $dispatcher->setDefaultNamespace('Projectname\Open\Controllers');

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