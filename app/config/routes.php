<?php

use app\controllers\ApiExampleController;
use app\controllers\ApiSalaireChauffeurControler;
use app\controllers\AdminAuthController;
use app\controllers\CategorieController;
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

// Admin auth
$router->get('/admin/login', [AdminAuthController::class, 'showLogin']);
$router->post('/admin/login', [AdminAuthController::class, 'handleLogin']);
$router->get('/admin/logout', [AdminAuthController::class, 'logout']);
$router->get('/admin', [AdminAuthController::class, 'dashboard']);

// Categories
$router->get('/admin/categories', [CategorieController::class, 'showCategories']);
$router->get('/api/categories', [CategorieController::class, 'getAll']);
$router->post('/api/categories', [CategorieController::class, 'create']);
$router->put('/api/categories/@id', [CategorieController::class, 'update']);
$router->delete('/api/categories/@id', [CategorieController::class, 'delete']);

//$router->group('', function (Router $router) use ($app) {
//
//	
//}, [SecurityHeadersMiddleware::class]);
