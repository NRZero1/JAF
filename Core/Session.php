<?php

namespace NRZero\JAF\Core;

class Session
{
    protected const FLASH_KEY = "flash_messages";
    public function __construct()
    {
        session_start();
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($flashMessages as $key => &$flashMessage) {
            $flashMessage['remove'] = true;
        }
        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }
    public function generateCSRFToken(string $algorithm)
    {
        // generate cstf token with algorithm from given parameter
    }

    public function setSession(string $sessionName, string|array $value)
    {
        $_SESSION[$sessionName] = $value;
    }

    public function hasSession(string $sessionName)
    {
        if (isset($_SESSION[$sessionName])) {
            return true;
        }
        return false;
    }

    public function unsetSession(string $sessionName)
    {
        // unset session, return false if session not exist
        unset($_SESSION[$sessionName]);
    }

    public function flashMessage(string $flashKey): string|bool
    {
        return $_SESSION[self::FLASH_KEY][$flashKey]['message'] ?? false;
    }

    public function setFlashMessage(string $flashKey, string $message)
    {
        $_SESSION[self::FLASH_KEY][$flashKey] = [
            'remove' => false,
            'message' => $message
        ];
    }

    public function __destruct()
    {
        $flashMessages = $_SESSION[self::FLASH_KEY];
        foreach ($flashMessages as $key => $flashMessage) {
            if ($flashMessage['remove'] == true) {
                unset($flashMessages[$key]);
            }
        }
        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }
}
