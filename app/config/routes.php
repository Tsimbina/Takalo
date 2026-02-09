<?php

use app\controllers\ApiExampleController;
use app\controllers\ApiSalaireChauffeurControler;
use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;
use app\controllers\ApiTrajetController;
use app\controllers\ApiVehiculeController;
use app\controllers\ApiHomeController;

/** 
 * @var Router $router 
 * @var Engine $app
 */

//$router->group('', function (Router $router) use ($app) {
//
//	
//}, [SecurityHeadersMiddleware::class]);
