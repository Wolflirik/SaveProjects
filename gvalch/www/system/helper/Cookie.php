<?php

namespace system\helper;

class Cookie{

    /**
     * @param $cookies
     * @param $key
     * @return null
     */
    public static function get($cookie, $key)
    {
        if(isset($cookie[$key])) {
            return $cookie[$key];
        }
        return null;
    }

    /**
     * @param $key
     * @param $val
     * @param int $time
     */
    public static function set($key, $val, $time = 31536000)
    {
        setcookie($key, $val, time() + $time, '/');
    }

    /**
     * @param $cookies
     * @param $key
     */
    public static function delete($cookie, $key)
    {
        if(isset($cookie[$key])) {
            self::set($key, '', -3600);
            unset($_COOKIE[$key]);
        }
    }
}