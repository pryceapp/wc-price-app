<?php

/**
 * When the user first enter the product page we need to get
 * the parameter "utm_source" from the URL and set in the cookie session,
 * but we can't get the cookie value right away until the client refresh his page.
 * 
 * This class is a simple implementation of singleton pattern
 * to hold temporarely the value that was set on the cookie until
 * the client refresh his page.
 */
class PryceAPPGenericCookieHandler
{
    const DEFAULT_COOKIE_KEY = 'PRYCE_AFFILIATE';
    const DEFAULT_AFFILIATE_IDENTIFIER = 'utm_source';
    private static $instance;
    private $utm_source_value;

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function set($value, $expiration)
    {
        wc_setcookie(self::DEFAULT_COOKIE_KEY, $value, $expiration);
        $this->utm_source_value = $value;
    }

    public function get()
    {
        if (!isset($_COOKIE[self::DEFAULT_COOKIE_KEY])) {
            return $this->utm_source_value;
        }

        return $_COOKIE[self::DEFAULT_COOKIE_KEY];
    }
}
