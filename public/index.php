<?php

require '../vendor/autoload.php';

// Prepare app
$app = new \Slim\Slim(array(
    'templates.path' => '../templates',
));

// Prepare view
//$view = new \Slim\View();
$view = new \Slim\Views\Twig();
$app->view($view);

// Define routes
$app->get('/', function () use ($app) {
    // Sample log message
    $app->log->info("Slim-Skeleton '/' route");
    // Render index view
    $app->render('index.twig', [
		'message' => 'test my nerves'
	]);
    //$app->render('index.html');
});

$app->get('/about', function () use ($app) {
    // Render index view
    $app->render('about.twig', [
		'message' => 'test my nerves'
	]);
    //$app->render('index.html');
});

// Run app
$app->run();

