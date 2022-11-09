<?php

namespace NRZero\JASF\Core;

class Request
{
    public function getPath()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $queryPosition = strpos($path, '?');
        if ($queryPosition) {
            return substr($path, 0, $queryPosition);
        }
        return $path;
    }

    public function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
}
