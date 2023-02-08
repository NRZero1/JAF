<?php

namespace NRZero\JAF\Core;

class View
{
    public function loadView(string $viewAddress, array $contentAddress = [], array $params = [], string $mode = "variable")
    {
        switch ($mode) {
            case "variable":
                return $this->loadViewVariable($viewAddress, $contentAddress, $params);
                break;
            case "placeholder":
                return $this->loadViewPlaceholder($viewAddress, $contentAddress, $params);
                break;
        }
    }

    // using string like '{{content}}' as placeholder
    public function loadViewPlaceholder(string $viewAddress, array $contentAddress = [], array $params = [])
    {
        if (empty($contentAddress)) {
            return $this->renderView($viewAddress, $params);
        }

        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $$key = $value;
            }
        }

        $layout = [];
        $view = $this->renderView($viewAddress, $params);
        // echo var_dump($contentAddress);
        foreach ($contentAddress as $key => $value) {
            ob_start();
            include_once Application::$ROOT_DIR . "/Views/{$value}.php";
            $layout[$key] = ob_get_contents();
            ob_end_clean();
        }
        // include_once Application::$ROOT_DIR . "/views/dashboard/hero/index.php";

        foreach ($layout as $key => $value) {
            $view = str_replace("{{{$key}}}", $value, $view);
        }
        return $view;
        // return str_replace("{{content}}", $layout, $view);
    }

    // using echo variable
    public function loadViewVariable(string $viewAddress, array $contentAddress = [], array $params = [])
    {
        if (empty($contentAddress)) {
            return $this->renderView($viewAddress, $params);
        }

        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $$key = $value;
            }
        }

        $layout = [];
        // echo var_dump($contentAddress);
        foreach ($contentAddress as $key => $value) {
            ob_start();
            include_once Application::$ROOT_DIR . "/Views/{$value}.php";
            $layout[$key] = ob_get_contents();
            ob_end_clean();
        }
        // include_once Application::$ROOT_DIR . "/views/dashboard/hero/index.php";
        return $this->renderView($viewAddress, $params, $layout);
    }

    private function renderView(string $view, array $params = [], array $layout = [])
    {
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $$key = $value;
            }
        }

        if (!empty($layout)) {
            foreach ($layout as $key => $value) {
                $$key = $value;
            }
        }

        ob_start();
        include_once Application::$ROOT_DIR . "/Views/{$view}.php";
        return ob_get_clean();
    }
}
