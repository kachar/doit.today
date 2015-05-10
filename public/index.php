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

$pdo = new PDO('sqlite:../data/db.sqlite');
$db = new NotORM($pdo);

$createQuery = '
    CREATE TABLE todo (
        `id` integer primary key autoincrement,
        `message` varchar(128), 
        `is_done` integer, 
        `created_at` varchar(20)
    )
';
$db->debug = true;

// Render about page
$app->get('/about', function () use ($app) {
    // Render index view
    $app->render('about.twig', [
        'message' => 'my test message'
    ]);
})->name('about');

// Define index route
$app->map('/(:filter)', function ($filter = '') use ($app, $db) {

    // Handle post requests
    if ($app->request->isPost()) {    
        // Sample log message
        $app->log->info('Create new record.');
        
        // Get vars from POST
        $post = $app->request->post();

        if (!empty($post['new-todo'])) {
            // Save them to databse
            $db->todo()->insert([
                'id' => null,
                'message' => $post['new-todo'],
                'is_done' => false,
                'created_at' => strftime('%F %T'),
            ]);
    
            // Prevent double posting
            $app->redirectTo('home', ['filter' => $filter]);
        }
    }
    
    
    $todoList = $db->todo();
    $todoList->order('id DESC');
    
    if (!empty($filter)){
        $todoList->where(array("is_done" => $filter == 'completed' ? true : false ));
    }    

    // Render index view
    $app->render('index.twig', [
        'todoList' => $todoList,
    ]);

})->via('GET', 'POST')->name('home')->conditions(array('filter' => '(completed|active)'));

// Update row status
$app->post('/do/:id', function ($id) use ($app, $db) {

    $app->log->info('Update status of #'.$id);

    $db->todo(['id' => $id])->update([
        'is_done' => $app->request->post('is_done') == 'true',
    ]);
});

// Delete row
$app->delete('/todo/:id', function ($id) use ($app, $db) {

    $app->log->info('Delete row #'.$id);
    
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

