<?php

namespace system\core\config;

use system\core\AbstractProvider;

class Provider extends AbstractProvider{

    public $name = 'config';

    public function init()
    {
        $config = new Config();
        $this->container->set($this->name, $config);
    }
}