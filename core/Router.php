<?php

namespace NRZero\JASF\Core;

class Router
{
    protected array $routes = [];
    private Request $request;
    
    public function __construct()
    {
        $this->request = new Request();
    }

    public function get(string $path, callable $callback)
    {
        $this->routes['get'][$path] = $callback;
        $callback();
        // or you can use
        // call_user_func($callback);
    }

    public function post(string $path, callable $callback)
    {
        $this->routes['post'][$path] = $callback;
        $callback();
    }

    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();
        /* echo $path;
        echo $method; */
        $callback = $this->routes[$method][$path] ?? false;

        if ($callback === false) {
            http_response_code(404);
            exit(http_response_code() . " Not Found");
        }
        echo $callback();
        /* echo '<pre>';
        var_dump($this->routes);
        echo '</pre>'; */
    }
}