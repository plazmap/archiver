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

    $user =[];
    $stmt = $this->database->prepare('select name, password from user where name = ?');
    $stmt->execute(array($body['login']));
    $user = $stmt->fetch();

    if (password_verify($body['password'],$user['password'])){
        echo 'yep';
    }else{
        echo 'nope';
    }        
});

$app->get('/register', function ($request, $response, $args) {
    return $this->renderer->render($response, '/register.php');
});

$app->post('/register', function ($request, $response, $args) {
    $body = $request->getParsedBody();

<<<<<<< HEAD
    $stmt = $this->database->prepare('insert into user (name, password, created_at) values (?, ?, ?)');
    if ($stmt === false) {
        return $this->renderer->render($response, '/register.php', [
            'err' => $this->database->lastErrorMsg(),
        ]);
    }

    $stmt->execute(array($body['login'], password_hash($body['password'], PASSWORD_BCRYPT), date(DATE_RFC3339)));
    echo 'ok';
=======
    $stmt = $this->database->prepare('insert into user (name, password, created_at) values (:name, :password, :created_at)');
	if ($stmt === false) {
		return $this->renderer->render($response, '/register.php', [
			'err' => $this->database->lastErrorMsg(),
		]);
	}

     $stmt->execute(array($body['login'], password_hash($body['password'], PASSWORD_BCRYPT), date(DATE_RFC3339)));
        return $this->renderer->render($response, '/register_ok.php');;
>>>>>>> Replaced Sql by PDO
});

$app->run();

