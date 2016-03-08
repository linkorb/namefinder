<?php

use NameFinder\Controller\AppController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

include __DIR__ . '/../vendor/autoload.php';

$app = new NameFinder\Application();
$app['debug'] = true;
$controller = new AppController();

$app->get('/', function (Request $request) use ($app, $controller) {
    return $controller->indexController($app, $request);
});

$app->post('/search', function (Request $request) use ($app, $controller) {
    return $controller->searchController($app, $request);
});

$app->get('/names/{name}', function (Request $request, $name) use ($app, $controller) {
    return $controller->viewNameController($app, $request, $name);
});

$app->get('/names/{name}/rate/{rating}', function (Request $request, $name, $rating) use ($app, $controller) {
    return $controller->rateNameController($app, $request, $name, $rating);
});

$app->get('/rating/{rating}', function (Request $request, $rating) use ($app, $controller) {
    return $controller->ratingController($app, $request, $rating);
});

$app->run();
