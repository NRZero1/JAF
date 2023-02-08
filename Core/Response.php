<?php

namespace NRZero\JAF\Core;

class Response
{
    public function setResponseCode($responseCode)
    {
        http_response_code($responseCode);
    }

    /**
     * Redirect page
     * @param string $url    destination url
     * @param string $method refresh|auto
     * 
     * refresh = refresh the current active page
     * 
     * auto = normal redirect
     * 
     * set htttp response code will be implemented later
     */
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
