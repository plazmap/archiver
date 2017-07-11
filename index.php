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

    $stmt = $this->database->prepare('select name, password from user where name = ?');
    if ($stmt === false) {
        return $this->renderer->render($response, '/login.php', ['errs' => [$this->database->lastErrorMsg()]]);
    }

    $res = $stmt->execute(array($body['login']));
    if ($res === false) {
        return $this->renderer->render($response, '/register.php', ['errs' => [$this->database->lastErrorMsg()]]);
    }

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

    $errs = [];

    if (!isset($body['login'])){
        $errs[] = "You must register a user name. Please try again.";
    }

    if ($body['password'] != $body['password_confirmation']){
        $errs[] = "Password verification failed. Please try again.";
    } 

    if (strlen($_POST["password"]) < 7) {
        $errs[] = "Password must exceed 7 characters. Please try again.";
    } 

    $stmt = $this->database->prepare('select count(name) as number from user where name = ?');
    if ($stmt === false) {
        return $this->renderer->render($response, '/register.php', ['errs' => [$this->database->lastErrorMsg()]]);
    }

    $res = $stmt->execute(array($body['login']));
    if ($res === false) {
        return $this->renderer->render($response, '/register.php', ['errs' => [$this->database->lastErrorMsg()]]);
    }

    $res = $stmt->fetch();

    if ($res['number'] != 0){
        $errs[] = "User name alreay taken, please select new one.";
    }

    if (count($errs) != 0){
        return $this->renderer->render($response, '/register.php', [
            'errs' => $errs,
        ]);
    }

    $stmt = $this->database->prepare('insert into user (name, password, created_at) values (?, ?, ?)');
    if ($stmt === false) {
        return $this->renderer->render($response, '/register.php', ['errs' => [$this->database->lastErrorMsg()]]);
    }

    $res = $stmt->execute(array($body['login'], password_hash($body['password'], PASSWORD_BCRYPT), date(DATE_RFC3339)));
    if ($res === false) {
        return $this->renderer->render($response, '/register.php', ['errs' => [$this->database->lastErrorMsg()]]);
    }

    return $this->renderer->render($response, '/register_ok.php', ['login'=>$body['login']]);


});

$app->run();

