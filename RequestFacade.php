<?php

class RequestFacade
{

    public function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    public function getParam($name, $optional = null)
    {
        return isset($_POST[$name]) ? $_POST[$name] : $optional;
    }

}
