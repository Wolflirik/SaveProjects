<?php

namespace system\helper;

class Cookie{

    /**
     * @param $cookie
     * @param $key
     * @return null
     */
    public static function get($cookie, $key)
    {
        if(isset($cookie[$key])) {
            return unserialize(base64_decode($cookie[$key]));
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
        setcookie($key, base64_encode(serialize($val)), time() + $time, '/');
    }

    public static function delete($key)
    {
        if(isset($_COOKIE[$key])) {
            self::set($key, '', -3600);
            unset($_COOKIE[$key]);
        }
    }
}