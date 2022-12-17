<?php

namespace NRZero\JASF\Core;

class Application
{
    public Router $router;
    public Request $request;
    public Response $response;
    public static string $ROOT_DIR;
    public static Application $app;
    public Database $db;
    public Session $session;
    public View $view;

    public function __construct(string $ROOT_DIR, array $config)
    {
        self::$ROOT_DIR = $ROOT_DIR;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->view = new View();
        $this->router = new Router($this->request, $this->response);
        $this->db = new Database($config['db_host'], $config['db_name'], $config['db_username'], $config['db_pass']);
    }

    public function run()
    {
        foreach ($this->router->resolve() as $value) {
            echo $value;
        }
    }
}
