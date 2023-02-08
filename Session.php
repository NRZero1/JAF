<?php

namespace NRZero\JAF;

class Session
{
    protected const FLASH_KEY = "flash_messages";
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
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

    public function getSession(string $sessionName)
    {
        return $_SESSION[$sessionName] ?? false;
    }

    public function hasSession(string $sessionName)
    {
        if (isset($_SESSION[$sessionName])) {
            return true;
        }
        return false;
    }

    public function destroySession()
    {
        session_destroy();
    }

    public function unsetAllSession()
    {
        session_unset();
    }

    public function unsetSession(string $sessionName)
    {
        // unset session, return false if session not exist
        unset($_SESSION[$sessionName]);
    }

    public function getFlashData(string $flashKey): string|bool
    {
        return $_SESSION[self::FLASH_KEY][$flashKey]['message'] ?? false;
    }

    public function setFlashData(string $flashKey, string $message)
    {
        $_SESSION[self::FLASH_KEY][$flashKey] = [
            'remove' => false,
            'message' => $message
        ];

        /* if (!empty($customArray)) {
            $_SESSION[self::FLASH_KEY][$flashKey] += $customArray;
        } */
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
