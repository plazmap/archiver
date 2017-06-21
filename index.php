<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\PhpRenderer;

require 'vendor/autoload.php';
$cfg = require 'configuration.php';

$app = new \Slim\App();

$container = $app->getContainer();
$container['renderer'] = function() {
    return new PhpRenderer('./views');
};
$container['database'] = function () {
    global $cfg;
    return new PDO('sqlite:'.$cfg['db']);
};

$app->get('/hello/{name}', function ($request, $response, $args) {
    return $this->renderer->render($response, '/hello.php', $args);
});


$app->get('/login', function ($request, $response, $args) {
    return $this->renderer->render($response, '/login.php', $args);
});


$app->post('/login', function ($request, $response, $args) {
    $body = $request->getParsedBody();

    $userinfo=[];
    $res = $this->database->query('SELECT name, password FROM user');
    while ($row = $res->fetchArray()){
        $userinfo=$row ;
    }
    foreach ($userinfo as $useri){
        if ($userinfo['name']==$body['login'] && password_verify($body['login'],$userinfo['password'])){
            echo 'yep' ;
        }
    }
    echo 'nope' ; 
});

$app->get('/register', function ($request, $response, $args) {
    return $this->renderer->render($response, '/register.php');
});

$app->post('/register', function ($request, $response, $args) {
    $body = $request->getParsedBody();

    $stmt = $this->database->prepare('insert into user (name, password, created_at) values (?, ?, ?)');
    if ($stmt === false) {
        return $this->renderer->render($response, '/register.php', [
            'err' => $this->database->lastErrorMsg(),
        ]);
    }

    $stmt->execute(array($body['login'], password_hash($body['password'], PASSWORD_BCRYPT), date(DATE_RFC3339)));
    echo 'ok';
});

$app->run();

