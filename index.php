<?php
session_start();

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';
$cfg = require 'configuration.php';

$app = new \Slim\App();

$container = $app->getContainer();
$container['renderer'] = function() {
    return new App\Renderer('./views');
};
$container['database'] = function () {
    global $cfg;
    return new PDO('sqlite:'.$cfg['db']);
};

$app->get('/', function ($request, $response, $args) {
    return $this->renderer->render($response, '/home.php', $args);
});

$app->get('/home', function ($request, $response, $args) {
    return $this->renderer->render($response, '/home.php', $args);
});

$app->get('/stupid', function ($request, $response, $args) {
    return $this->renderer->render($response, '/stupid.php', $args);
});

$app->get('/disconnect', function ($request, $response, $args) {
    session_destroy();
    return $response->withRedirect('/home');
});

$app->get('/login', function ($request, $response, $args) {
    return $this->renderer->render($response, '/login.php', $args);
});

$app->get('/account', function ($request, $response, $args) {
    return $this->renderer->render($response, '/account.php', $args);
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
        $_SESSION['user']=$body['login'];
        return $response->withRedirect('/main');
    }
    return $this->renderer->render($response, '/login.php', ['errs' => 'Login incorrect. Please try again']); 

});

$app->get('/main', function ($request, $response, $args) {
    return $this->renderer->render($response, '/main.php');
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
     $_SESSION['user']=$body['login'];
        return $response->withRedirect('/main');
    

});

$app->run();

