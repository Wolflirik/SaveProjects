<?php

namespace system\core\config;

class Config
{
    /**
     * @param $key
     * @param $group
     * @return null
     * @throws \Exception
     */
    public static function item($key, $group){
        $groupItem = static::file($group);

        return isset($groupItem[$key]) ? $groupItem[$key] : null;
    }

    /**
     * @param $name
     * @return bool|mixed
     * @throws \Exception
     */
    public static function file($name){
        $path = __DIR__ . '/../../config/' . $name . '.php';

        if(file_exists($path))
        {
            $config = require($path);

            if(!empty($config))
            {
                return $config;
            }
            else
            {
                throw new \Exception(sprintf('File %s is empty!', $name));
            }
        }
        else
        {
            throw new \Exception(sprintf('File %s not found in %s!', $name, $path));
        }

        return false;
    }
}