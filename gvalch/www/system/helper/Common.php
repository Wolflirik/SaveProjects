<?php

namespace system\helper;

class Common{

    /**
     * @return mixed
     */
    public static function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * @return bool|string
     */
    public static function getPathUri()
    {
        $pathUri = $_SERVER['REQUEST_URI'];
        if($position = strpos($pathUri, '?'))
        {
            $pathUri = substr($pathUri, 0, $position);
        }
        return $pathUri;
    }
}