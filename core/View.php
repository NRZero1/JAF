<?php

namespace NRZero\JASF\Core;

class View
{
    public function loadView(string $viewAddress, array $layoutAddress = null)
    {
        $view = $this->renderView($viewAddress);
        if ($layoutAddress != null) {
            $layouts = $this->renderLayout($layoutAddress);
            if (is_array($layouts)) {
                foreach ($layouts as $layout) {
                    yield str_replace("{{$layout}}", $layout, $view);
                }
            }
            yield str_replace("{{$layouts}}", $layout, $view);
        }
        yield $view;
    }

    public function renderLayout(string|array $layoutAddress)
    {
        ob_start();
        if (is_array($layoutAddress)) {
            foreach ($layoutAddress as $layout => $value) {
                include_once Application::$ROOT_DIR . "/views/$value";
            }
        } else {
            include_once Application::$ROOT_DIR . "/views/$layoutAddress";
        }
        // include_once Application::$ROOT_DIR . "/views/dashboard/hero/index.php";
        return ob_get_clean();
    }

    public function renderView(string $view, array $params = [])
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }

        ob_start();
        include_once Application::$ROOT_DIR . "/views/$view";
        return ob_get_clean();
    }
}
