<?php

class GenericCookieHandler
{
    const DEFAULT_COOKIE_KEY = "PRYCE_AFFILIATE";
    private static $instance;
    private $utm_source_key;
    private $utm_source_value;

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function set($key, $value, $expiration)
    {
        wc_setcookie($key, $value, $expiration);
        $this->utm_source_key = $key;
        $this->utm_source_value = $value;
    }

    public function get($key)
    {
        if (!isset($_COOKIE[$this->utm_source_key])) {
            return $this->utm_source_value;
        }

        return $_COOKIE[$this->utm_source_key];
    }
}
