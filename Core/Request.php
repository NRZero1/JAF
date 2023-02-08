<?php

namespace NRZero\JAF\Core;

class Request
{
    public function getPath(): string
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $lastChar = $path[strlen($path) - 1];
        if (strlen($path) == 1 && $lastChar === '/') {
            return $path = '/home';
        }
        $queryPosition = strpos($path, '?');
        if ($queryPosition) {
            return substr($path, 0, $queryPosition);
        }
        if ($lastChar === '/') {
            return substr($path, 0, strlen($path) - 1);
        }
        return $path;
    }

    public function getMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function isPost(): bool
    {
        return $this->getMethod() === 'post';
    }

    public function isGet(): bool
    {
        return $this->getMethod() === 'get';
    }
}
