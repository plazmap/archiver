<?php

namespace Controllers;
use Interop\Container\ContainerInterface;

class Auth extends Base
{
    public function login($request, $response, $args) 
    {
        return $this->render($response, '/login.php', $args);
    }

    public function do_login($request, $response, $args)
    {
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

    }
}
