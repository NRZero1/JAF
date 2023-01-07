<?php

namespace NRZero\JASF\Core;

class Request
{
    public function getPath(): string
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $queryPosition = strpos($path, '?');
        if ($queryPosition) {
            return substr($path, 0, $queryPosition);
        }
        return $path;
    }

    public function getMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function isPost(): bool
    {
        return $this->getMethod() === 'get';
    }

    public function isGet(): bool
    {
        return $this->getMethod() === 'post';
    }
}
