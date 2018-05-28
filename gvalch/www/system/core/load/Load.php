<?php

namespace system\core\load;

class Load
{

    private $container;

    /**
     * Load constructor.
     * @param $container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->container->get($key);
    }

    /**
     * @param $key
     * @param $val
     */
    public function __set($key, $val)
    {
        $this->container->set($key, $val);
    }

    /**
     * @param $model
     */
    public function model($model)
    {
        list($folder, $class) = explode('/', $model);
        $model_namespace = '\\' . LOCATION . '\\model\\' . $folder . '\\' . ucfirst($class);
        if(class_exists($model_namespace))
        {
            $this->container->set('model_' . $folder . '_' . $class, new $model_namespace($this->container));
        }else{
            trigger_error('Error: Could not load model ' . $model_namespace . '!');
            exit();
        }
    }

    /**
     * @param $language
     * @return mixed
     */
    public function language($language)
    {
        return $this->language->load($language);
    }
}