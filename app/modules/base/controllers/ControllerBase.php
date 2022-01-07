<?php
declare(strict_types=1);

namespace Cryptokart\Controllers;

use Cryptokart\Models\User;
use Phalcon\Di;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;
use Cryptokart\Repositories\UserRepository;
use Cryptokart\Services\SessionService;

abstract class ControllerBase extends Controller
{
    public $moduleName;
    public $controllerName;
    public $actionName;

    public function beforeExecuteRoute(Dispatcher $di)
    {        
        $router = $this->router;
        $this->moduleName = $router->getModuleName() ? strtolower($router->getModuleName()) : 'Index';
        $this->controllerName = $router->getControllerName() ? strtolower($router->getControllerName()) : 'Index';
        $this->actionName = $router->getActionName();
    }
    
    public function initialize()
    {
        // add css
        $this->assets->addCss("libs/vendor/twbs/bootstrap/dist/css/bootstrap.min.css");
        $this->assets->addCss("libs/vendor/components/jqueryui/themes/blitzer/jquery-ui.min.css");
        $this->assets->addCss("https://unpkg.com/bootstrap-table@1.18.0/dist/extensions/group-by-v2/bootstrap-table-group-by.css", false);
        $this->assets->addCss("https://unpkg.com/bootstrap-table@1.18.0/dist/bootstrap-table.min.css", false);
        $this->assets->addCss("/css/animate.css");
        $this->assets->addCss("/css/jquery.fancybox.min.css");
        $this->assets->addCss("/css/jquery.mCustomScrollbar.min.css");
        $this->assets->addCss("/css/odometer-theme-default.css");
        $this->assets->addCss("/css/owl.carousel.css");
        $this->assets->addCss("/css/smm.css".date("?Ymdhis"));
        $this->assets->addCss("/css/fontawesome-all.css");
        // add js
        $this->assets->addJs("/js/jquery.js");
//        $this->assets->addJs("/js/jquery.loadTemplate-1.4.4.min.js");
        $this->assets->addJs("/js/lazyload.min.js");
        $this->assets->addJs("libs/vendor/components/jqueryui/jquery-ui.min.js");
        $this->assets->addJs("libs/vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js");
        $this->assets->addJs("https://unpkg.com/bootstrap-table@1.18.0/dist/bootstrap-table.min.js", false);
        $this->assets->addJs("https://unpkg.com/bootstrap-table@1.18.0/dist/extensions/group-by-v2/bootstrap-table-group-by.min.js", false);
        $this->assets->addJs("/js/appear.js");
        $this->assets->addJs("/js/jquery.fancybox.js");
        $this->assets->addJs("/js/jquery.mCustomScrollbar.concat.min.js");
        $this->assets->addJs("/js/odometer.js");
        $this->assets->addJs("/js/owl.js");
        $this->assets->addJs("/js/parallax-scroll.js");
        $this->assets->addJs("/js/popper.min.js");
        $this->assets->addJs("/js/smm.js".date("?Ymdhis"));
        $this->assets->addJs("/js/tilt.jquery.min.js");
        $this->assets->addJs("/js/typer-new.js");
        $this->assets->addJs("/js/wow.min.js");

        /* automatically add controller assets */
        $jsControllerFile = "js/modules/{$this->moduleName}/{$this->controllerName}.js";
        if(\file_exists($jsControllerFile)) {
            $this->assets->addJs($jsControllerFile.date("?Ymdhis"));
        }
        $cssControllerFile = "css/modules/{$this->moduleName}/{$this->controllerName}.css";
        if(\file_exists($cssControllerFile)) {
            $this->assets->addCss($cssControllerFile.date("?Ymdhis"));
        }
        
        /* automatically add action assets */
        $jsViewFile = "js/modules/{$this->moduleName}/{$this->controllerName}/{$this->actionName}.js";
        if(\file_exists($jsViewFile)) {
            $this->assets->addJs($jsViewFile.date("?Ymdhis"));
        }
        $cssViewFile = "css/modules/{$this->moduleName}/{$this->controllerName}/{$this->actionName}.css";
        if(\file_exists($cssViewFile)) {
            $this->assets->addCss($cssViewFile.date("?Ymdhis"));
        }

        $mobileDetect = $this->getDI()->getShared('mobileDetect');
        $cssClassOpened = $mobileDetect->isMobile() ? "" : "navopened";
//        $this->view->setVar('isNavOpened', $cssClassOpened);
        $this->view->setLayout('public');

        $this->view->siteDate = date('Y');
        $this->view->setLayout('index');
        $this->view->connectedAdmin = false;
        $this->assets->addCss("/css/modules/public/menu.css");
        if($this->di->get('session')->has('admin') && $this->di->get('session')->get('admin') instanceof User){
            $this->view->connectedAdmin = true;
            $this->view->userConnected = $this->di->get('session')->get('admin');
        }
    }

}
