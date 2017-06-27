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

$app->get('/register', function ($request, $response, $args) {
    return $this->renderer->render($response, '/register.php');
});

$app->post('/register', function ($request, $response, $args) {
    $body = $request->getParsedBody();

    $stmt = $this->database->prepare('insert into user (name, password, created_at) values (?, ?, ?)');
    if ($stmt === false) {
        return $this->renderer->render($response, '/register.php', [
            'err' => implode(" ", $this->database->errorInfo()),           
        ]);
    }

    $stmt2 = $this->database->prepare('select name from user where name = :username');  
    if ($stmt2 === false) {
        return $this->renderer->render($response, '/register.php', [
            'err2' => implode(" ", $this->database->errorInfo()),
            
        ]);
    }

    function unique_user_name($username){
        $result=[];
            $stmt2->bindParam(':username', $body['login']);
        $res = $stmt2->execute();
        while ($row = $res->fetcharray()){
            $result=$res ;
        }

        if (count($result>0)){
            return false;
        }else{
            return true; 
        }
    }


    $errs=[];

    if ($body['login']==NULL){
            $errs[] = "You must register a user name.";
        }

    if(!unique_user_name($body['login'])) {
        $errs[] = "User name alreay taken, please select new one. (zbra)";
        }
        
    if ($body['password'] != $body['password_confirmation']){
            $errs[] = "Password verification failed.";
        } 

    if (strlen($body['login']) < 7) {
            $errs[] = "Password must exceed 7 characters";
        }

    if (count($errs)>0){
       return $this->renderer->render($response, '/register.php', ['errors'=>implode(" ", $errs),
       ]); 
    }
    $stmt->execute(array($body['login'], password_hash($body['password'], PASSWORD_BCRYPT), date(DATE_RFC3339)));
    return $this->renderer->render($response, '/register_ok.php', ['login'=>$body['login']]);
});

$app->run();


