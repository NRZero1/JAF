<?php

namespace NRZero\JASF\Core;

class Router
{
    protected array $routes = [];
    private Request $request;
    private Response $response;
    private View $view;
    public function __construct(Request $request, Response $response)
    {
        $this->request = new $request;
        $this->response = new $response;
        $this->view = new View();
    }

    public function get(string $path, string|callable|array $callback)
    {
        $this->routes['get'][$path] = $callback;
    }

    public function post(string $path, string|callable|array $callback)
    {
        $this->routes['post'][$path] = $callback;
    }

    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();
        $callback = $this->routes[$method][$path] ?? false;

        if ($callback === false) {
            $this->response->setResponseCode(404);
            exit($this->view->loadView("not_found.php"));
        }
        if (is_string($callback)) {
            return $this->view->loadView($callback);
        }
        if (is_array($callback)) {
            $callback[0] = new $callback[0]();
        }
        return call_user_func($callback);
    }

    // public function loadView(string $viewAddress)
    // {
    //     $layout = $this->renderLayout();
    //     $view = $this->renderView($viewAddress);
    //     return str_replace("{content}", $layout, $view);
    // }

    // public function renderLayout()
    // {
    //     ob_start();
    //     include_once Application::$ROOT_DIR . "/views/dashboard/hero/index.php";
    //     return ob_get_clean();
    // }

    // public function renderView(string $view)
    // {
    //     ob_start();
    //     include_once Application::$ROOT_DIR . "/views/$view";
    //     return ob_get_clean();
    // }
}
