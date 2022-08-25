<?php

require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use DI\Container;

$container = new Container();
$container->set('renderer', function () {
    return new \Slim\Views\PhpRenderer(__DIR__ . '/../templates');
});
$app = AppFactory::createFromContainer($container);
$app->addErrorMiddleware(true, true, true);

$users = ['mike', 'mishel', 'adel', 'keks', 'kamila'];

$app->get('/', function ($request, $response) {
    $response->write('Welcome to Slim!');
    return $response;
    // Благодаря пакету slim/http этот же код можно записать короче
    // return $response->write('Welcome to Slim!');
});

$app->get('/users', function ($request, $response) use ($users){
    $term = $request->getQueryParam('term');
    $filteredUsers = array_filter($users, fn($user) => str_contains($user, $term));
    $params = [
        'users' => $filteredUsers
    ];
    return $this->get('renderer')->render($response, 'users/index.phtml', $params);
});
$app->get('/courses/{id}', function ($request, $response, array $args) {
    $id = $args['id'];
    return $response->write("Course id: {$id}");
});
$app->get('/users/{id}', function ($request, $response, array $args) {
    $params = [
        'id' => $args['id'],
        'nickname' => 'user-' . $args['id']
        ];
    return $this->get('renderer')->render($response, 'users/show.phtml', $params);
});
$app->post('/users', function ($request, $response) {
    return $response->withStatus(302);
});


$app->run();