<?php

require '../vendor/autoload.php';

// Prepare app
$app = new \Slim\Slim([
    'templates.path' => '../templates',
]);

// Prepare view
$view                   = new \Slim\Views\Twig();
$view->parserExtensions = [
    new \Slim\Views\TwigExtension(),
];
$app->view($view);

$pdo = new PDO('sqlite:../data/db.sqlite');
$db  = new NotORM($pdo);

// Render about page
$app->get('/about', function () use ($app) {
    $app->render('about.twig');
})->name('about');

// Resources page
$app->get('/resources', function () use ($app) {
    $app->render('resources.twig');
})->name('resources');

// Define index route
$app->map('/(:filter)', function ($filter = '') use ($app, $db) {

    // Handle post requests
    if ($app->request->isPost()) {

        // Get vars from POST
        $message = $app->request->post('new-todo');
        $message = trim($message);

        if (!empty($message)) {

            $app->log->info('Create new record.');

            // Save them to databse
            $db->todo()->insert([
                'id'         => null,
                'message'    => $message,
                'is_done'    => false,
                'created_at' => strftime('%F %T'),
            ]);

            // Prevent double posting
            $app->redirectTo('home', ['filter' => $filter]);
        }
    }

    $todoList = $db->todo();
    $todoList->order('id DESC');

    if (!empty($filter)) {
        $todoList->where(["is_done" => $filter == 'completed' ? true : false]);
    }

    // Render index view
    $app->render('index.twig', [
        'todoList'      => $todoList,
        'active_filter' => $filter,
    ]);

})->via('GET', 'POST')->name('home')->conditions(['filter' => '(completed|active)']);

// Update row status
$app->post('/do/:id', function ($id) use ($app, $db) {

    $app->log->info('Update status of #' . $id);

    $db->todo(['id' => $id])->update([
        'is_done' => $app->request->post('is_done') == 'true',
    ]);
});

// Delete row
$app->delete('/todo/:id', function ($id) use ($app, $db) {

    $app->log->info('Delete row #' . $id);

    $db->todo(['id' => $id])->delete();
});

// Delete all
$app->delete('/todo/clear/:type', function ($type) use ($app, $db) {

    if ($type == 'all') {
        $db->todo()->delete();
    } elseif ($type == 'completed') {
        $db->todo(['is_done' => 1])->delete();
    }
});

// Run app
$app->run();
