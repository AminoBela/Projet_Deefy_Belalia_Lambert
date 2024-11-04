<?php

namespace iutnc\deefy\controller;

abstract class BaseController
{
    protected ?string $http_method = null;

    public function __construct()
    {
        $this->http_method = $_SERVER['REQUEST_METHOD'];
    }

    abstract public function execute() : string;
}