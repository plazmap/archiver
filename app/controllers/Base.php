<?php

namespace Controllers;
use Interop\Container\ContainerInterface;

class Base
{
    protected $container;
    protected $database;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
        $this->database = $container->database;
    }

    public function render($response, $view, $args)
    {
        return $this->container->renderer->render($response, $view, $args);
    }
}

