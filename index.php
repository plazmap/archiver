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
	return new Sqlite3($cfg['db']);
};

$app->get('/hello/{name}', function ($request, $response, $args) {
	return $this->renderer->render($response, '/hello.php', $args);
});

$app->get('/register', function ($request, $response, $args) {
	return $this->renderer->render($response, '/register.php');
});

$app->post('/register', function ($request, $response, $args) {
	$body = $request->getParsedBody();

	$stmt = $this->database->prepare('insert into user (name, password, created_at) values (:name, :password, :created_at)');
	if ($stmt === false) {
		return $this->renderer->render($response, '/register.php', [
			'err' => $this->database->lastErrorMsg(),
		]);
	}

	$stmt->bindValue(':name', $body['login']);
	$stmt->bindValue(':password', password_hash($body['password'], PASSWORD_BCRYPT));
	$stmt->bindValue(':created_at', date(DATE_RFC3339));
	$stmt->execute();

	echo 'ok';
});

$app->run();


