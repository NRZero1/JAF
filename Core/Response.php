<?php

namespace NRZero\JASF\Core;

class Response
{
    public function setResponseCode($responseCode)
    {
        http_response_code($responseCode);
    }

    public function redirect(string $url, string $method = 'auto')
    {
        if ($method == "refresh") {
            header("Refresh:0; url=$url");
            exit;
        }
        header("Location: $url");
        exit;
    }

    public function setHeader()
    {
        // setHeader function, takes string param to set http header
    }
}
