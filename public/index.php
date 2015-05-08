<?php

require '../vendor/autoload.php';

// Prepare app
$app = new \Slim\Slim(array(
    'templates.path' => '../templates',
));

// Prepare view
$view = new \Slim\Views\Twig();
$view->parserExtensions = array(
    new \Slim\Views\TwigExtension(),
);
$app->view($view);

// Define routes
$app->get('/', function () use ($app) {
    // Sample log message
    $app->log->info("Slim-Skeleton '/' route");
    // Render index view
    $app->render('index.twig', [
		'title' => 'test my',
		'description' => 'nerves',
	]);
    //$app->render('index.html');
})->name('home');

$app->get('/about', function () use ($app) {
    // Render index view
    $app->render('about.twig', [
		'message' => 'test my nerves'
	]);
})->name('about');

$app->post('/', function () use ($app) {

    $app->log->info('POST to / :'.json_encode($app->request->post()));

	$app->redirectTo('home');
});





// Run app
$app->run();

